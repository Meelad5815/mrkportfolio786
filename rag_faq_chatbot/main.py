import argparse
import hashlib
import json
import math
import os
import re
from collections import Counter
from dataclasses import dataclass, asdict
from pathlib import Path
from typing import Dict, List, Tuple

try:
    from dotenv import load_dotenv

    load_dotenv()
except Exception:
    pass


@dataclass
class Chunk:
    text: str
    source: str
    idx: int


class LocalVectorStore:
    """Tiny JSON-backed vector store using bag-of-words cosine similarity."""

    def __init__(self, path: str = "local_store.json"):
        self.path = Path(path)
        self.rows: List[Dict] = []
        self._load()

    def _load(self) -> None:
        if self.path.exists():
            self.rows = json.loads(self.path.read_text(encoding="utf-8"))

    def _save(self) -> None:
        self.path.write_text(json.dumps(self.rows, ensure_ascii=False, indent=2), encoding="utf-8")

    @staticmethod
    def _tokenize(text: str) -> List[str]:
        return re.findall(r"[a-zA-Z0-9_]+", text.lower())

    def _vectorize(self, text: str) -> Dict[str, float]:
        tokens = self._tokenize(text)
        counts = Counter(tokens)
        norm = math.sqrt(sum(v * v for v in counts.values())) or 1.0
        return {k: v / norm for k, v in counts.items()}

    @staticmethod
    def _cosine(a: Dict[str, float], b: Dict[str, float]) -> float:
        if len(a) > len(b):
            a, b = b, a
        return sum(value * b.get(key, 0.0) for key, value in a.items())

    def add(self, chunks: List[Chunk]) -> int:
        existing_ids = {row["id"] for row in self.rows}
        added = 0
        for chunk in chunks:
            row_id = hashlib.md5(f"{chunk.source}-{chunk.idx}-{chunk.text}".encode()).hexdigest()
            if row_id in existing_ids:
                continue
            self.rows.append(
                {
                    "id": row_id,
                    "text": chunk.text,
                    "source": chunk.source,
                    "chunk": chunk.idx,
                    "vector": self._vectorize(chunk.text),
                }
            )
            existing_ids.add(row_id)
            added += 1
        if added:
            self._save()
        return added

    def query(self, question: str, n_results: int = 3) -> Tuple[List[str], List[dict]]:
        query_vec = self._vectorize(question)
        scored = [
            (self._cosine(query_vec, row["vector"]), row)
            for row in self.rows
        ]
        scored.sort(key=lambda x: x[0], reverse=True)
        top = [row for score, row in scored[:n_results] if score > 0]
        documents = [row["text"] for row in top]
        metadatas = [{"source": row["source"], "chunk": row["chunk"]} for row in top]
        return documents, metadatas


class RAGPipeline:
    def __init__(self, docs_dir: str = "docs", persist_file: str = "local_store.json"):
        self.docs_dir = Path(docs_dir)
        self.store = LocalVectorStore(path=persist_file)

    @staticmethod
    def _read_file(path: Path) -> str:
        if path.suffix.lower() in {".txt", ".md"}:
            return path.read_text(encoding="utf-8")
        if path.suffix.lower() == ".pdf":
            try:
                from pypdf import PdfReader
            except Exception as exc:
                raise RuntimeError("PDF support requires: pip install pypdf") from exc
            reader = PdfReader(str(path))
            return "\n".join(page.extract_text() or "" for page in reader.pages)
        raise ValueError(f"Unsupported file type: {path.name}")

    @staticmethod
    def _chunk_text(text: str, source: str, chunk_size: int = 500, overlap: int = 100) -> List[Chunk]:
        chunks: List[Chunk] = []
        start = 0
        idx = 0
        while start < len(text):
            chunk_text = text[start:start + chunk_size].strip()
            if chunk_text:
                chunks.append(Chunk(text=chunk_text, source=source, idx=idx))
                idx += 1
            start += max(1, chunk_size - overlap)
        return chunks

    def index_documents(self) -> int:
        all_chunks: List[Chunk] = []
        for file_path in sorted(self.docs_dir.glob("*")):
            if file_path.suffix.lower() not in {".txt", ".md", ".pdf"}:
                continue
            text = self._read_file(file_path)
            all_chunks.extend(self._chunk_text(text, source=file_path.name))
        return self.store.add(all_chunks)

    def retrieve(self, question: str, n_results: int = 3) -> Tuple[List[str], List[dict]]:
        return self.store.query(question, n_results=n_results)

    def answer(self, question: str, n_results: int = 3) -> str:
        docs, metas = self.retrieve(question, n_results=n_results)
        if not docs:
            return "No matching context found. Please add more docs or ask a clearer question."

        context_blocks = [f"[{m['source']} #{m['chunk']}] {d}" for d, m in zip(docs, metas)]
        context = "\n\n".join(context_blocks)

        if os.getenv("OPENAI_API_KEY"):
            try:
                from openai import OpenAI

                client = OpenAI()
                response = client.chat.completions.create(
                    model=os.getenv("OPENAI_CHAT_MODEL", "gpt-4o-mini"),
                    messages=[
                        {
                            "role": "system",
                            "content": "You are a company FAQ assistant. Answer from context only and say if info is missing.",
                        },
                        {"role": "user", "content": f"Context:\n{context}\n\nQuestion: {question}"},
                    ],
                )
                return response.choices[0].message.content or "No answer generated."
            except Exception as exc:
                return f"OpenAI call failed ({exc}).\n\nTop matching context:\n{context}"

        return (
            "Offline retrieval mode (no OPENAI_API_KEY).\n\n"
            f"Top matching context:\n{context}"
        )


def main() -> None:
    parser = argparse.ArgumentParser(description="Mini RAG FAQ chatbot")
    parser.add_argument("--index", action="store_true", help="Index docs folder before asking")
    parser.add_argument("--question", type=str, help="Ask a question")
    parser.add_argument("--docs", type=str, default="docs", help="Path to docs folder")
    parser.add_argument("--store", type=str, default="local_store.json", help="Path to local vector store JSON")
    args = parser.parse_args()

    rag = RAGPipeline(docs_dir=args.docs, persist_file=args.store)

    if args.index:
        count = rag.index_documents()
        print(f"Indexed {count} new chunks.")

    if args.question:
        print(rag.answer(args.question))


if __name__ == "__main__":
    main()

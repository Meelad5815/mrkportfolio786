import argparse
import hashlib
import os
from dataclasses import dataclass
from pathlib import Path
from typing import List, Tuple

import chromadb
from chromadb.utils.embedding_functions import EmbeddingFunction
from dotenv import load_dotenv

load_dotenv()


class OpenAIEmbeddingFunction(EmbeddingFunction):
    def __init__(self, model: str = "text-embedding-3-small"):
        from openai import OpenAI

        self.client = OpenAI()
        self.model = model

    def __call__(self, input: List[str]) -> List[List[float]]:
        response = self.client.embeddings.create(model=self.model, input=input)
        return [item.embedding for item in response.data]


class SentenceTransformerEmbeddingFunction(EmbeddingFunction):
    def __init__(self, model: str = "all-MiniLM-L6-v2"):
        from sentence_transformers import SentenceTransformer

        self.model = SentenceTransformer(model)

    def __call__(self, input: List[str]) -> List[List[float]]:
        return self.model.encode(input).tolist()


@dataclass
class Chunk:
    text: str
    source: str
    idx: int


class RAGPipeline:
    def __init__(
        self,
        docs_dir: str = "docs",
        persist_dir: str = "chroma_db",
        collection_name: str = "faq_collection",
    ):
        self.docs_dir = Path(docs_dir)
        self.client = chromadb.PersistentClient(path=persist_dir)
        self.embedding_function = self._build_embedding_function()
        self.collection = self.client.get_or_create_collection(
            name=collection_name,
            embedding_function=self.embedding_function,
        )

    @staticmethod
    def _build_embedding_function() -> EmbeddingFunction:
        if os.getenv("OPENAI_API_KEY"):
            return OpenAIEmbeddingFunction()
        return SentenceTransformerEmbeddingFunction()

    @staticmethod
    def _read_file(path: Path) -> str:
        if path.suffix.lower() in {".txt", ".md"}:
            return path.read_text(encoding="utf-8")
        if path.suffix.lower() == ".pdf":
            from pypdf import PdfReader

            reader = PdfReader(str(path))
            return "\n".join(page.extract_text() or "" for page in reader.pages)
        raise ValueError(f"Unsupported file type: {path.name}")

    @staticmethod
    def _chunk_text(text: str, source: str, chunk_size: int = 450, overlap: int = 100) -> List[Chunk]:
        chunks: List[Chunk] = []
        start = 0
        idx = 0
        while start < len(text):
            end = start + chunk_size
            chunk_text = text[start:end].strip()
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

        if not all_chunks:
            return 0

        ids = []
        documents = []
        metadatas = []

        for chunk in all_chunks:
            chunk_hash = hashlib.md5(f"{chunk.source}-{chunk.idx}-{chunk.text}".encode()).hexdigest()
            ids.append(chunk_hash)
            documents.append(chunk.text)
            metadatas.append({"source": chunk.source, "chunk": chunk.idx})

        existing = set(self.collection.get(include=[])["ids"])
        new_rows = [i for i, value in enumerate(ids) if value not in existing]
        if not new_rows:
            return 0

        self.collection.add(
            ids=[ids[i] for i in new_rows],
            documents=[documents[i] for i in new_rows],
            metadatas=[metadatas[i] for i in new_rows],
        )
        return len(new_rows)

    def retrieve(self, question: str, n_results: int = 3) -> Tuple[List[str], List[dict]]:
        results = self.collection.query(query_texts=[question], n_results=n_results)
        docs = results["documents"][0]
        metas = results["metadatas"][0]
        return docs, metas

    def answer(self, question: str, n_results: int = 3) -> str:
        docs, metas = self.retrieve(question, n_results=n_results)
        context_blocks = [f"[{m['source']} #{m['chunk']}] {d}" for d, m in zip(docs, metas)]
        context = "\n\n".join(context_blocks)

        if os.getenv("OPENAI_API_KEY"):
            from openai import OpenAI

            client = OpenAI()
            response = client.chat.completions.create(
                model=os.getenv("OPENAI_CHAT_MODEL", "gpt-4o-mini"),
                messages=[
                    {
                        "role": "system",
                        "content": "You are a helpful company FAQ assistant. Reply only from the given context and say if info is missing.",
                    },
                    {
                        "role": "user",
                        "content": f"Context:\n{context}\n\nQuestion: {question}",
                    },
                ],
            )
            return response.choices[0].message.content or "No answer generated."

        return (
            "OPENAI_API_KEY not found, so this is retrieval-only mode.\n\n"
            f"Top matching context:\n{context}"
        )


def main() -> None:
    parser = argparse.ArgumentParser(description="Mini RAG FAQ chatbot")
    parser.add_argument("--index", action="store_true", help="Index docs folder before asking")
    parser.add_argument("--question", type=str, help="Ask a question")
    parser.add_argument("--docs", type=str, default="docs", help="Path to docs folder")
    args = parser.parse_args()

    rag = RAGPipeline(docs_dir=args.docs)

    if args.index:
        count = rag.index_documents()
        print(f"Indexed {count} new chunks.")

    if args.question:
        print(rag.answer(args.question))


if __name__ == "__main__":
    main()

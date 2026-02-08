"""
Simple browser UI without external dependencies.
Run: python app.py
Open: http://127.0.0.1:8000
"""

from http.server import BaseHTTPRequestHandler, HTTPServer
from urllib.parse import parse_qs

from main import RAGPipeline

rag = RAGPipeline(docs_dir="docs", persist_file="local_store.json")


class Handler(BaseHTTPRequestHandler):
    def _send(self, body: str) -> None:
        self.send_response(200)
        self.send_header("Content-Type", "text/html; charset=utf-8")
        self.end_headers()
        self.wfile.write(body.encode("utf-8"))

    def do_GET(self):
        self._send(
            """
            <html><body style='font-family:Arial;max-width:800px;margin:40px auto'>
            <h2>🤖 Company FAQ Chatbot (Mini RAG)</h2>
            <form method='post'>
              <button name='action' value='index'>Index / Refresh Docs</button>
            </form>
            <hr/>
            <form method='post'>
              <input name='question' style='width:100%;padding:8px' placeholder='Ask your question' />
              <button type='submit'>Ask</button>
            </form>
            </body></html>
            """
        )

    def do_POST(self):
        length = int(self.headers.get("Content-Length", 0))
        raw = self.rfile.read(length).decode("utf-8")
        form = parse_qs(raw)

        if form.get("action", [""])[0] == "index":
            added = rag.index_documents()
            answer = f"Index complete. Added {added} new chunks."
        else:
            question = form.get("question", [""])[0]
            answer = rag.answer(question) if question else "Please enter a question."

        self._send(
            f"""
            <html><body style='font-family:Arial;max-width:800px;margin:40px auto'>
            <h2>🤖 Company FAQ Chatbot (Mini RAG)</h2>
            <a href='/'>← Back</a>
            <pre style='white-space:pre-wrap;background:#f5f5f5;padding:12px;border-radius:8px'>{answer}</pre>
            </body></html>
            """
        )


def run():
    server = HTTPServer(("127.0.0.1", 8000), Handler)
    print("Server running on http://127.0.0.1:8000")
    server.serve_forever()


if __name__ == "__main__":
    run()
import streamlit as st

from main import RAGPipeline

st.set_page_config(page_title="Company FAQ Chatbot", page_icon="🤖")
st.title("🤖 Company FAQ Chatbot (Mini RAG)")
st.caption("Ask questions from internal docs in /docs folder")

rag = RAGPipeline(docs_dir="docs")

if st.button("Index / Refresh Docs"):
    added = rag.index_documents()
    st.success(f"Index complete. Added {added} new chunks.")

question = st.text_input("Ask your question")
if question:
    with st.spinner("Finding answer..."):
        answer = rag.answer(question)
    st.write(answer)

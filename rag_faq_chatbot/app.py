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

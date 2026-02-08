# Mini RAG Project: Company FAQ Chatbot

یہ updated template **offline بھی چلتا ہے** (zero mandatory deps) اور چاہیں تو OpenAI کے ساتھ online generation بھی enable کر سکتے ہیں۔

## Why this version is better

- ✅ **Runs without internet installs** (core mode uses Python standard library)
- ✅ Local JSON vector store + cosine retrieval
- ✅ Optional OpenAI answer generation (`OPENAI_API_KEY`)
- ✅ Simple browser UI using built-in `http.server` (no Streamlit required)

## Project Structure

```text
rag_faq_chatbot/
├── app.py
├── main.py
├── requirements.txt
├── .env.example
└── docs/
    ├── pricing.txt
    ├── security.txt
    └── support_policy.txt
```

## Quick Start (Offline)

```bash
cd rag_faq_chatbot
python main.py --index --question "Enterprise plan ka price kya hai?"
```

## Browser Demo (Offline)

```bash
cd rag_faq_chatbot
python app.py
# open http://127.0.0.1:8000
```

## Optional Online/OpenAI Mode

1) Optional dependencies install کریں:

```bash
pip install -r requirements.txt
```

2) `.env` بنائیں:

```bash
OPENAI_API_KEY=your_key_here
OPENAI_CHAT_MODEL=gpt-4o-mini
```

3) پھر وہی سوال کریں:

```bash
python main.py --question "SLA kitne time ka hai?"
```

## "Run and create on internet" (deploy options)

### Option A: GitHub Codespaces
- Repo push کریں
- Codespace open کریں
- `python app.py` چلائیں
- Ports tab میں 8000 expose کریں اور shareable URL بنائیں

### Option B: Railway/Render
- Web service بنائیں
- Start command: `python app.py`
- Port env var کے حساب سے binding چاہیے ہو تو app.py میں host/port env support add کریں (easy next step)

## Freelance pitch line

> "I can build a company knowledge chatbot that answers from internal docs, works offline, and can be upgraded to OpenAI-based RAG when needed."

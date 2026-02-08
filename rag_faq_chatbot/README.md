# Mini RAG Project: Company FAQ Chatbot

یہ updated template **offline بھی چلتا ہے** (zero mandatory deps) اور چاہیں تو OpenAI کے ساتھ online generation بھی enable کر سکتے ہیں۔

## Why this version is better

- ✅ **Runs without internet installs** (core mode uses Python standard library)
- ✅ Local JSON vector store + cosine retrieval
- ✅ Optional OpenAI answer generation (`OPENAI_API_KEY`)
- ✅ Simple browser UI using built-in `http.server` (no Streamlit required)
یہ ایک **ready-to-run mini RAG template** ہے جو آپ GitHub portfolio یا freelancing demo کے لیے استعمال کر سکتے ہیں۔

## What this project does

- `docs/` سے FAQs/SOPs/policies لوڈ کرتا ہے (`.txt`, `.md`, `.pdf`)
- text کو chunks میں split کر کے Chroma vector DB میں index کرتا ہے
- user question کے لیے relevant chunks retrieve کرتا ہے
- اگر `OPENAI_API_KEY` ہو تو GPT سے grounded answer generate کرتا ہے
- اگر key نہ ہو تو retrieval-only mode میں best matching context دکھاتا ہے

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
## Quick Start

```bash
cd rag_faq_chatbot
python -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt
cp .env.example .env
```

Optional: `.env` میں OpenAI key add کریں:

```bash
OPENAI_API_KEY=your_key_here
OPENAI_CHAT_MODEL=gpt-4o-mini
```

## CLI usage

### 1) Index docs

```bash
python main.py --index
```

### 2) Ask question

```bash
python main.py --question "Enterprise plan ka price kya hai?"
```

### 3) One-liner (index + ask)

```bash
python main.py --index --question "SLA kitne time ka hai?"
```

## Streamlit demo

```bash
streamlit run app.py
```

## Freelance pitch line

> "I can build a company knowledge chatbot that answers from your internal docs with secure retrieval-augmented generation (RAG)."

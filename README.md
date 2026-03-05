# layanan-ti-dompu

Stage 1 webhook infrastructure for WhatsApp Cloud API using Laravel-style structure, Redis, Docker Compose, and Ngrok.

## Run

1. Copy env:
   ```bash
   cp .env.example .env
   ```
2. Set `NGROK_AUTHTOKEN` in `.env`.
3. Start services:
   ```bash
   docker compose up -d
   ```
4. Check ngrok URL from:
   - http://localhost:4040
5. Configure Meta webhook URL:
   - `https://<ngrok-id>.ngrok.io/api/webhook`

## Webhook endpoints

- `GET /api/webhook` - verification (`hub.mode`, `hub.verify_token`, `hub.challenge`)
- `POST /api/webhook` - incoming messages logging and Redis storage to `whatsapp_incoming_messages`

## Notes

- Bot does not send first message.
- This stage only receives, logs, and stores incoming messages.

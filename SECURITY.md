Immediate actions recommended:

- Rotate all exposed credentials: DB, SMTP, Aifadian tokens, OAuth client secret.
- Configure production secrets via environment variables (.env) and never commit real values.
- Review VCS history to ensure secrets are purged; consider using git filter-repo.
- Enforce input validation and parameterized queries across the codebase.
- Use unique per-request OAuth state and verify against session.

How to configure locally:

1) Copy .env.example to .env and fill values.
2) Run composer install to get dependencies (Dotenv, PHPMailer).
3) Ensure web server has read access to the .env file.

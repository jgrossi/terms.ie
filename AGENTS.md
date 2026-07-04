# terms.ie — agent context

## What this is
A SaaS that lets **Irish small businesses** (photographers, dentists, trainers, freelancers,
clinics, clubs) create reusable **terms / agreements / consent forms**, send a private link to
a client, and collect a **legally-meaningful electronic signature** — no PDF editors, no client
accounts. On signing we generate a signed PDF (audit trail: name, UTC timestamp, IP, SHA-256).
Trust is the emotional core; the UI is professional and restrained.

## Audience & goals
- Users are non-technical small-business owners in Ireland. Signing works on any device.
- Product goals: dead-simple authoring (plain text + `{{VARIABLE}}` placeholders), one-click
  send, frictionless client signing, tamper-evident record, signed PDF download.
- Business: freemium (Free 1 template/5 sigs; Pro €5/mo). Electronic Commerce Act 2000 framing.
- terms.ie is the **first of many planned SaaS** and the reference implementation of the house
  stack — keep patterns clean and reusable.

## Stack
Laravel (PHP 8.5) · **Inertia 3 + React 19 + TypeScript** · Tailwind v4 + **shadcn/ui** · Vite ·
SQLite (dev) · Pest. Passwordless **magic-link** session auth. IDs are **UUID v7**. Signed PDFs
via dompdf, stored on a configurable disk (`local` dev / **Cloudflare R2** prod, served by
short-lived signed URL). No separate API layer — controllers return `Inertia::render`.

## Conventions
Follow the **`my-laravel-way`** skill (the source of truth): thin controllers, inline
validation, backed enums, scoped bindings, Storage facade, `useForm` + Ziggy + Sonner flash,
shadcn components, `assertInertia` tests, "less code". Read it before writing code here.

## Layout
- Backend: `app/Http/Controllers`, `app/Models`, `app/Enums`, `app/Mail`, `routes/web.php`.
- Frontend: `resources/js/{pages,layouts,components,components/ui,hooks,lib,types}`; root `resources/views/app.blade.php`.
- Domain: `Term` → `TermVersion` (immutable once signed) → `Signature` (belongs to `Client`).
  Signing locks the version. `{{CLIENT_NAME}}`/`{{CLIENT_EMAIL}}` are reserved; others are
  filled when assigning to a client.

## Commands
- `npm run dev` — Vite (use while iterating; avoids Inertia asset-version reloads)
- `npm run build` · `npx tsc --noEmit` — build / typecheck
- `./vendor/bin/pest` — tests (keep green)
- `php artisan migrate:fresh` — reset dev DB · `/dev/login` — local login (auto-creates the dev user)

## Guardrails
- Don't format dates in PHP — pass ISO strings, format in React (`lib/format.ts`, UTC for the audit certificate).
- Models never emit HTML. Keep PDFs small (base-14 Courier/Helvetica + a static signature font, never a variable font).
- R2 needs `R2_ACCESS_KEY_ID`/`R2_SECRET_ACCESS_KEY` in `.env` + `SIGNATURE_DISK=r2` to go live.

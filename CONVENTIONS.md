# Conventions — the house stack

This app is the reference template for all future SaaS. Stack: **Laravel + Inertia 3 +
React 19 + TypeScript + Tailwind v4 + shadcn/ui**. Clone it and follow the patterns below.

## Layout of the frontend (`resources/js/`)

```
app.tsx                 # createInertiaApp — resolves pages/**, mounts <Toaster/>
pages/                  # one file per Inertia page; path mirrors the route
  Dashboard.tsx         #   -> Inertia::render('Dashboard')
  terms/Index.tsx       #   -> Inertia::render('terms/Index')
layouts/                # AppLayout (auth), ClientLayout (public sign), LandingLayout
components/             # shared React components (PageHeader, EmptyState, StatusDot, …)
components/ui/          # shadcn primitives (added via `npx shadcn@latest add …`)
hooks/                  # useFlashToast, …
lib/                    # utils (cn), format (dates)
types/                  # shared + domain TS types
```

## Backend → frontend

- Controllers return `Inertia::render('folder/Page', [...props])` — never `view()`.
  Keep controllers thin; shape response data as plain arrays. Redirects, `validate()`,
  `abort_unless()`, and `->with('toast', ...)` all work unchanged.
- **Dates:** pass ISO strings (`->toIso8601String()`) and format in React with
  `lib/format.ts` (`relativeTime`, `formatDate`, `formatDateTime`). Don't format in PHP.
- **IDs** are prefixed ULIDs (strings): `usr_`, `trm_`, `cli_`, `sig_`, `tmv_`.
- **Shared props** (every page) come from `HandleInertiaRequests::share()`: `auth.user`,
  `flash.toast`, `ziggy`. Validation `errors` are shared automatically by Inertia.

## Pages

- Default-export the page component. Attach a persistent layout with:
  ```tsx
  Page.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
  ```
- Routes in React: `import { route } from 'ziggy-js'`; `route('app.terms.show', id)`.
  Active nav state: `route().current('app.terms.*')`.

## Forms

- `useForm({...})`, `onSubmit` with `e.preventDefault()`, then `form.post/put(route(...))`.
- Inline errors via `form.errors.<field>`. Nested keys (e.g. `variables.PRICE`) come back
  dot-flattened — read them as `form.errors['variables.PRICE']`.
- Destroys: `<ConfirmDialog onConfirm={() => router.delete(route(...))} … />`.

## Toasts

- Server: `redirect()->…->with('toast', 'Saved.')`.
- Client: `useFlashToast()` is called **inside each layout** (it uses `usePage`, which must
  run within the Inertia `<App>` context — never render a `usePage` component as a sibling
  of `<App>`). The Sonner `<Toaster/>` is mounted once in `app.tsx`.

## Styling

- Design tokens are shadcn CSS variables in `resources/css/app.css` (OKLCH). Brand accent is
  `--primary` (blue `#2563EB`); neutrals are zinc; `--paper` is the contract surface.
- Fonts: Inter (UI) + system monospace (contracts), loaded via `<link>` in `app.blade.php`.
- Tailwind v4 note: buttons need an explicit `cursor: pointer` — handled globally in the
  `@layer base` block. Keep it when cloning.

## Adding shadcn components

```
npx shadcn@latest add <name> --yes
```
Gotcha: the generated `sonner.tsx` imports `next-themes` — we removed that and hardcoded
`theme="light"`. Re-apply if you re-add it.

## Testing

- Backend: Pest feature tests. Assert Inertia with
  `->assertInertia(fn (Assert $page) => $page->component('terms/Index')->has('terms', N))`.
  DB / redirect / 403 assertions are unchanged. Run: `./vendor/bin/pest`.
- Typecheck: `npx tsc --noEmit`. Build: `npm run build`. Dev: `npm run dev`.
- While iterating use `npm run dev` — rebuilding assets mid-session triggers Inertia's 409
  asset-version full reload (expected behavior; one graceful reload after each deploy).

## Not yet wired (follow-ups)

- Frontend component tests (Vitest + Testing Library) and a Playwright E2E happy path.
- Real magic-link email (currently logged via `\Log::info`).
- Mobile: add `laravel/sanctum` + `/api` routes only when an app earns a mobile client;
  the React components already exist to reuse.

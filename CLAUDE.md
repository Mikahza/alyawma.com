<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application running on PHP 8.4. You are an expert with the Laravel ecosystem. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists (settled decisions, non-obvious traps, standing constraints). Framework and package guidelines that only apply to specific paths (testing, frontend, components) also live there, under `.ai/rules/boost` — this is not just recorded decisions, it is load-bearing guidance you have not seen inline. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.
- Record a rule with `record-rule` only when the user explicitly asks for one. Instructions for the work at hand are not rules, no matter how emphatic: "remove this typo", "use X here" are work to do, not rules to record. Never record a rule on your own initiative, as a byproduct of a change, or to summarize what you just did. When the user does ask, pass a `glob` (e.g. `app/Http/Controllers/**`), a short `title`, and a few-line `note`. Use `record-rule` rather than your native memory or notes tool, because native memory is personal and session-scoped, while only `.ai/rules` is shared with the team and persists in the repo.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.
- Activate the `deploying-to-cloud` skill whenever deploying to Laravel Cloud, configuring Cloud environments or resources, using the Cloud CLI, or troubleshooting Cloud deployments.

=== tests rules ===

# Test Enforcement

- Add or update tests for behavior and logic changes when a test provides meaningful regression coverage.
- Pure copy, styling, and layout-only changes do not require new or updated tests.
- When test coverage applies, run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

# Inertia v3

- Use all Inertia features from v1, v2, and v3. Check the documentation before making changes to ensure the correct approach.
- New v3 features: standalone HTTP requests (`useHttp` hook), optimistic updates with automatic rollback, layout props (`useLayoutProps` hook), instant visits, simplified SSR via `@inertiajs/vite` plugin, custom exception handling for error pages.
- Carried over from v2: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.
- Axios has been removed. Use the built-in XHR client with interceptors, or install Axios separately if needed.
- `Inertia::lazy()` / `LazyProp` has been removed. Use `Inertia::optional()` instead.
- Prop types (`Inertia::optional()`, `Inertia::defer()`, `Inertia::merge()`) work inside nested arrays with dot-notation paths.
- SSR works automatically in Vite dev mode with `@inertiajs/vite` - no separate Node.js server needed during development.
- Event renames: `invalid` is now `httpException`, `exception` is now `networkError`.
- `router.cancel()` replaced by `router.cancelAll()`.
- The `future` configuration namespace has been removed - all v2 future options are now always enabled.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== wayfinder/core rules ===

# Laravel Wayfinder

Use Wayfinder to generate TypeScript functions for Laravel routes. Import from `@/actions/` (controllers) or `@/routes/` (named routes).

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

# Pest

- This project uses Pest. Create tests with `php artisan make:test --pest {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.
- Do not delete tests or test files without approval. They are part of the application.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/pest` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.
- After the feature tests pass, ask the user to run the complete suite with `php artisan test --compact`.

=== inertia-vue/core rules ===

# Inertia + Vue

Vue components must have a single root element.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

</laravel-boost-guidelines>

---

# Alyawma — Product Context

Daily nutrition tracker. This section is the product specification: what we build and why.
The delivery breakdown lives in `ROADMAP.md` (untracked, kept in French).

## 1. The product

The user has to hit precise daily targets for protein, calories and fibre. They need to record what
they eat in seconds, several times a day, mostly from a phone, and immediately see what is left.

**Protein is the primary counter.** The display order is fixed: protein, then calories, then fibre.
This is not cosmetic, it is the product's priority ordering. No screen may reorder it.

The real usage context is standing in a kitchen, phone in one hand. That constraint arbitrates every
interface decision:

- Every tap counts. Going from "I want to log a usual food" to "it is saved" must fit in **three taps**,
  without going through a search.
- Food search must respond while typing, not on submit.
- Touch targets must be reachable with a thumb, so at the bottom of the screen, not the top.
- Mobile-first. Desktop is a tolerated bonus, not a target.
- The app must be installable to the home screen (PWA). **No offline mode in V1.**

## 2. Accounts and roles

### Authentication

Email and password only. No third-party sign-in (Google, Apple) in V1 — that is a later effort,
outside this document.

| Feature | V1 | Rationale |
|---|---|---|
| Registration | Yes | Open to anyone from launch |
| Password login | Yes | Only way in |
| Password reset | Yes | Not excluded by the spec, and mandatory once third parties hold accounts: without it, a forgotten password means a lost account and a manual support request |
| Email verification | No | Deliberate scope decision |
| Two-factor (TOTP) | No | Deliberate; the threat model of a food log does not justify TOTP, recovery codes and their interface |
| Passkeys / WebAuthn | **Yes** | Reinstated on 16 September 2026. On a mobile-first app, signing in with a fingerprint beats typing a password on a phone keyboard, and it is phishing-resistant with no shared secret. Already built and tested by the starter kit |
| Password confirmation | **Yes** | Not an independent choice: `Features::passkeys(['confirmPassword' => true])` requires it |
| Teams | No | Strictly personal product |

The exclusions above are settled — do not propose re-enabling them. Passkeys were the one exception:
the maintainer reopened the question himself and chose to keep them. Accepted cost:
`@laravel/passkeys` is pre-1.0, so a minor bump is breaking by convention.

### Roles

Two roles, and only two:

- **Standard user.** Owns their journal, personal food catalogue, goals and favourites.
- **Administrator.** Same rights, plus exactly one power: **publishing a food to the shared
  catalogue**. No back office, no user moderation, no usage analytics in V1.

The admin role exists for one reason: registration is open, and a shared catalogue writable by
everyone becomes a dump within weeks. It is data governance, not a feature.

## 3. The five values, and their hierarchy

The app tracks **five nutritional values**, but they do not have equal standing.

**Three values drive** — protein, calories, fibre. Each has a target, a progress gauge, and shows the
remainder (`X / target`, and `Y left`). Always in that order.

**Two values inform** — fat, carbohydrates. Tracked and totalled, displayed as raw values, with no
gauge and no target. They must never occupy the same visual weight as the first three, or the
product's message gets diluted.

This distinction drives everything: the data model stores all five identically, the interface treats
them differently. Do not "harmonise" this display.

## 4. What we are building

### 4.1 Today's journal — the main screen

The landing screen for a signed-in user, and where they will spend 95% of their time.

- The three gauges at the top, protein / calories / fibre, each showing the remainder.
- Fat and carbohydrates as raw values, visually subdued.
- The list of today's entries, each editable and deletable.
- A way to add an entry, always thumb-reachable.
- Navigation to previous and next days, to review and **correct**.

One journal entry = one food plus a **decimal multiplier** (for example 1.5 servings). The multiplier
is the only quantity lever: there is no free-form weight entry and no unit conversion.

### 4.2 The food library

Three sources, one search experience.

**Personal catalogue.** Each user creates, edits and deletes their own foods. A "food" can be an
ingredient (`Chicken breast`) or a whole meal with a free-form name (`My usual breakfast`). In both
cases it is **a single record whose five values are entered by hand**. It is not a recipe composed of
ingredients — that notion is explicitly out of scope and must not leak into the model.

**Shared catalogue.** Visible to every user, populated **only by an administrator**. A standard user
can neither publish to it nor modify it.

**Favourites.** Each user flags the foods they use often, from either catalogue. One user's
favourites are invisible to everyone else.

This is the product's main comfort win: eventually most entries will come from this list without ever
touching search. Favourites are not decoration — they are what makes the three-tap rule reachable.

### 4.3 Goals

The user sets three targets: protein, calories, fibre. Defaults on account creation:
**130 g protein, 2050 kcal, 30 g fibre**.

Non-negotiable rule: **changing a target must not rewrite history.** A week that succeeded under the
old target stays a successful week. Modelling consequence in D2 below.

Fat and carbohydrates have no target, and must not be given one "for completeness".

### 4.4 History

- Seven-day rolling average, for each of the five values.
- Past days with their totals and whether **that day's applicable targets** were met.
- Direct access to a past day to correct it.

This screen comes last in the breakdown, deliberately: it is the most satisfying to build and the
least useful. The app must first be usable day to day.

### 4.5 Obligations from public registration

The app hosts third-party accounts. It therefore needs legal notices, a privacy policy, and a way for
a user to **delete their account and data**. That is a dedicated lot, not a checkbox at the end.

## 5. Data modelling principles

The schema is built **lot by lot**. Each lot introduces only the tables its feature needs, when it
needs them. There is no "all migrations" lot, and no complete schema is fixed here. What follows are
the **principles** that apply when each table arrives.

### D1 — Nutritional values are copied into the journal entry

A journal entry **does not join** to the food's macros: it keeps a copy taken at save time. Editing a
food's macros, or deleting it, must never silently rewrite a past total. Same requirement as for
goals, applied to foods. Settled before this project, carried over unchanged.

*Accepted consequence:* the data is denormalised, and correcting a macro does not propagate to
already-saved entries. That is the intended behaviour.

### D2 — Goals are versioned over time, not overwritten

Changing a target creates a new version effective from a date; it does not overwrite the previous
one. Answering "was the target met on 12 March?" means resolving the goal version applicable on
12 March. **Open — see Q3.**

### D3 — Macros are stored **per reference serving**, not per 100 g

The spec mandates a decimal multiplier (`1.5 servings`) as the only quantity lever, and free-form
meals whose values are hand-entered. A meal like `My usual breakfast` has no meaningful "per 100 g"
value. Each food therefore carries its values **for one serving**, plus a free-form serving label
(`1 bowl`, `100 g`, `1 slice`) used for display only. No arithmetic is ever done on that label.

*Accepted consequence:* you cannot say "I ate 137 g of chicken". You say "1.37 servings". For a food
whose reference serving is 100 g, that is equivalent.

### D4 — No floating point for nutrients

Grams are stored as fixed-precision decimals, calories as integers. Summing floats across dozens of
entries produces totals that do not land clean, and it shows immediately on a gauge.

### D5 — A day is a date, in the user's timezone

A meal eaten at 23:30 Paris time belongs to that day, not the next. An entry therefore carries a
**journal date** distinct from its technical timestamp. V1 uses a single application timezone,
`Europe/Paris`. **Open — see Q4.**

### D6 — Deleting a food does not erase history

Thanks to D1, deleting a food leaves past entries intact and readable. The food disappears from
search and favourites, nothing more.

### D7 — One home for foods, separated by visibility

Personal foods and the shared catalogue live in the same table, distinguished by owner and a
publication flag. Two separate tables would force duplicating search, favourites and the D1 copy in
each path.

### D8 — The admin role is a boolean flag, not a permission system

There is one power to grant (publishing to the shared catalogue) and probably one administrator. A
flag on the account plus a Laravel gate is enough. Installing a role-management package would add a
dependency for a single line of logic.

### D9 — Password reset is kept

Not excluded by the spec, and mandatory once third parties hold accounts.

### D10 — The lot is the validation unit, the PR is the work unit

Resolves C2 without understating the workload.

### D11 — No stack or tooling change is ever proposed

Explicit project constraint.

### D12 — While the version is 0.x, migrations are edited in place

No data is worth preserving yet. A schema mistake is fixed by **editing the migration that introduced
it** and running `php artisan migrate:fresh`, not by stacking a corrective migration on top. A clean
set of migrations is worth more than a faithful record of our hesitations, and the lots ahead will
reshape the model as it is built.

Two boundaries make this safe, and the second one bites silently:

- `migrate:fresh` is a **local** command. Never run it against production.
- Production runs `migrate --force` on every deploy. A migration that has **already run there** is
  recorded in the `migrations` table and will never run again — so editing it leaves production on
  the old schema, with nothing to warn you. **Before editing a migration, check whether it has
  already been deployed.**

When it has already been deployed, the choice becomes explicit: add a corrective migration, or run
`migrate:fresh` in production and accept losing what is in it. The second is only defensible while
the maintainer is the sole user.

This decision expires at 1.0.0, or the day a third party holds data in production — whichever comes
first. It does not soften the additive-migration constraint that the zero-downtime deploy imposes on
anything already live.

## 6. Repository baseline

Surveyed 15 September 2026 at commit `baacd6d`. **No stack element is up for change** — this is a
shared reference point.

| Element | Version |
|---|---|
| PHP | 8.4 |
| Laravel | ^13.17 |
| Inertia | ^3.0 (server and client) |
| Vue | ^3.5 |
| Tailwind CSS | ^4.1 |
| Vite | ^8.0, via `vite-plus` (`vp` command) |
| Starter kit | `laravel/vue-starter-kit` |
| Auth | `laravel/fortify` ^1.37 |
| Typed routes | `laravel/wayfinder` ^0.1 |
| Tests | Pest ^5.2 |
| Static analysis | Larastan ^3.9, level 7 |
| PHP formatting | Pint, `laravel` preset |
| UI components | shadcn-vue / reka-ui (`components.json`) |
| AI tooling | Laravel Boost ^2.2 |

### Secret hygiene — verified

- `.env` has **never been committed**, at any point in history.
- No application key or token anywhere in history (searched across all commits).
- `.env.example` holds no real values.
- `.gitignore` covers `.env`, `.env.backup`, `.env.production`, `/auth.json`, `/storage/*.key`.

**The repository started clean. That is an asset to preserve**, not a permanent state.

## 7. Deployment

Laravel Forge, on a **shared host** that already carries an unrelated production workload. PHP 8.4
and MySQL 8.4 are already installed. Host specifics, and what else runs there, stay in `ROADMAP.md`,
which is untracked — this repository is public and does not describe its infrastructure.

- **Dedicated domain `alyawma.com`.** **Never a subdomain of the co-hosted application**: a wildcard
  DNS record there would make the collision immediate and silent.
- Dedicated Forge site, PHP 8.4, deployed from `main`, Quick Deploy on, Let's Encrypt.
- **Zero-downtime deploys**: the app lives under `/home/forge/alyawma.com/current/`. Any scheduled
  command or process must target that path, never the parent — otherwise it runs against a frozen
  copy of the code after the first redeploy.
- **Dedicated MySQL database**, with a **dedicated MySQL user whose privileges are scoped to that
  database only**. No access to any other database on the host, in either direction.
- **PHP-FPM pool sized low**, in the site's own pool file. Memory is shared with the co-hosted
  workload: a default-sized pool can exhaust the host on its own.
- **No queue worker in V1.**
- **Database backup from the first lot.** An unbacked database is an incident waiting to happen.

### Environment gaps to close

Classic sources of "it worked locally":

1. **Database.** `.env.example` declares SQLite; production will be MySQL 8.4. They differ on decimal
   types, unique constraints and dates. Resolved: MySQL everywhere, local development included.
2. **PHP.** Resolved: CI, `composer.json`, local machine and server all pinned to 8.4.
3. **JS package manager.** Resolved: npm, single lockfile.

### Absolute prohibitions

- Never modify shared server configuration: global PHP version, global nginx config, global MySQL
  config, system cron, or any process belonging to the co-hosted workload.
- Never touch the co-hosted application, its databases, or its files.
- Any irreversible operation, or one touching the shared environment, is **reported to the maintainer
  before execution**, never after.

## 8. Risks

### R1 — Shared-host blast radius

**The dominant risk of the project.** This application does not have its host to itself. A disk
filled by logs or backups, a migration that loops and saturates MySQL, an over-generous FPM pool that
exhausts memory, or an unlucky dependency upgrade does not only break Alyawma — it degrades
everything else on the box.

Alyawma is always the lower-priority workload on that host. **When the two conflict, the other
workload wins.**

Mitigations: constrained FPM pool, log rotation, disk-space check before any heavy operation, and no
operation on the shared environment without explicit prior approval.

### R2 — The repository is public

A secret pushed to a public repository is indexed within minutes. It must be treated as
**compromised even after history rewriting**: the only valid response is revocation.

No secret, key or credential may appear in a tracked file — not in an example, a comment, a test, or
a PR screenshot.

### R3 — Registration is open from launch

Third parties will create accounts and store personal data before legal notices and account deletion
exist, if the legal lot lands late. **Open — see Q2.**

### R4 — Email addresses are unverified

A deliberate scope decision, not up for discussion. The consequence to design around: an address on
an account proves nothing, and a user who mistypes theirs cannot recover it through password reset.
That case is handled manually.

### R5 — Without transactional email, password reset does not work

`MAIL_MAILER=log` by default. A sending service is required, and it **blocks lot 2**. See Q1.

## 9. Open contradictions

### C1 — The installed starter kit does not match the spec

The spec says *"the starter kit is installed with registration enabled and nothing else."* The code
says otherwise. Beyond registration, `config/fortify.php` enables password reset, **email
verification** (and the `dashboard` route sits behind the `verified` middleware), **two-factor auth**
with confirmation, and **passkeys**.

The footprint goes well past configuration: a `create_passkeys_table` migration, 2FA columns on
`users`, the `PasskeyUser` contract and the `PasskeyAuthenticatable` / `TwoFactorAuthenticatable`
traits on the model, five Vue pages, six components, the `@laravel/passkeys` npm dependency, a
`.well-known/passkey-endpoints` route, three rate limiters, and **five test files** that will fail
the moment these features are switched off.

**Resolved in lot 2, on 16 September 2026.** Email verification and two-factor authentication were
removed in full — configuration, routes, middleware, model traits, `two_factor_*` columns, Vue pages
and components, the `vue-input-otp` dependency and the corresponding tests. Passkeys, password reset
and password confirmation were kept. 1341 lines removed, 18 added.

### C2 — "One lot per evening" and "6 to 9 lots" do not hold together

The described scope does not fit into nine 3-to-4-hour evenings. Resolution: see D10. The lot stays
the validation unit, the PR the work unit; lots that overflow name their constituent PRs explicitly.

### C3 — Open registration precedes the legal obligations

See R3 and Q2.

### C4 — The product was designed single-user

Earlier notes described Alyawma as a personal single-user app. The current spec opens registration to
third parties. That is not a config detail: it brings the product into GDPR scope, mandates strict
per-account data isolation, and alone justifies both the admin role and the legal lot.

## 10. Open decisions

Questions awaiting the maintainer's call. Do not resolve them unilaterally.

| # | Question | Blocks |
|---|---|---|
| Q1 | Which transactional email service, and what budget? Password reset does not work until this is answered. | Lot 2 |
| Q2 | Keep registration closed until the legal lot ships, or move that lot earlier? | Lot 8 |
| Q3 | Goals as a date-effective versioned table, or three targets frozen onto each day? | Lot 5 |
| Q4 | `Europe/Paris` hardcoded, or a per-user timezone? | Lot 4 |
| Q5 | Do shared foods published by a deleted administrator survive? | Lot 8 |

### Settled

- **The database runs on MySQL everywhere**, local development included, matching the sibling
  project. Tests stay on SQLite in memory — so a decimal-precision bug would surface in local use,
  not in the suite.
- **The database backup is deferred** by the maintainer. To be revisited before registration opens
  to anyone else; a production database with no backup is an incident waiting to happen.
- **Passkeys are kept**, two-factor authentication and email verification are not. See C1.

## 11. Out of scope for V1

Explicitly deferred — do not propose these in any lot:

composed recipes · barcode scanning · external food databases (OpenFoodFacts and similar) · data
import and export · notifications · social sharing · automated coaching · third-party sign-in ·
offline mode · admin back office · user moderation · weight or body-measurement tracking · targets on
fat or carbohydrates · native mobile app.

---

# Working method

## Cadence

We move **one unit at a time**, in `ROADMAP.md` order:

1. Claude delivers.
2. The maintainer reviews.
3. We rework if there is anything to rework.
4. **The maintainer commits — never Claude.**
5. The maintainer confirms it is committed.
6. **Only then** do we move to the next unit.

Never start the next unit before step 5. Never deliver two units in one exchange.

## Hard limits for Claude

- **Never commit.** No `git commit`, under any circumstance, even when the work is finished and
  reviewed. Commits belong to the maintainer.
- **Never push.** No `git push`.
- **Never read or write `.env`.** Neither production nor local. To document a variable, go through
  `.env.example`, with no real value.

Everything else on the repository is allowed: creating, editing and deleting files, running tests,
local migrations, Artisan commands, static analysis.

## One PR = one responsibility

The git history, the generated changelog and the readability of the PRs are a deliverable in their
own right, alongside the application. A PR that mixes tooling with a feature destroys that value.

- If a PR description contains an "and", it is two PRs.
- Every PR must be demonstrable: a screenshot, a URL, or a passing test.
- Tooling lands **before** the feature it governs.
- Commit messages follow Conventional Commits — `release-please` depends on it.

## Branching

`main` plus short-lived PR branches. **No `develop` branch, no release branches, no long-lived
branches of any kind.** Linear history.

## Language

**Everything in this repository is written in English** — code, identifiers, comments, documentation,
commit messages, PR titles and descriptions. Never write French in a tracked file unless the
maintainer asks for it explicitly.

Two standing exceptions: `ROADMAP.md`, kept in French, and conversation with the maintainer, which
is in French.

## Product invariants

Settled in the product context above, not up for renegotiation in passing:

- **Fixed gauge order: protein → calories → fibre.** Never otherwise.
- **Fat and carbohydrates are raw values**, with no gauge and no target.
- **Three taps** from "I want to log a usual food" to "it is saved".
- **Changing a goal or a food never rewrites history.**
- Mobile-first. No offline mode in V1.

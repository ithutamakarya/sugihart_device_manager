# Native Sugihart Integration Design

Status: implemented and verified on `feature/native-sugihart-integration` (2026-11-07).

Builder check: an independent read-only Laravel design review found "nothing important left" before planning. Implementation then received one independent whole-branch review; its important findings (vendor token rotation and table actions after pagination) were reproduced and fixed with browser regressions.

Verification: 25 application tests / 216 assertions passed independently on SQLite and isolated PostgreSQL; 9 Python converter/archive tests passed. Edge checked CSRF login, CRUD, CCTV subdevices, token preservation, actions after pagination, local assets and kiosk JavaScript. ZIP extraction booted in a fresh Laravel host fixture before/after config, route and view caching. Strict missing-model-attribute handling is covered. The actual vendor host, physical kiosks and external attendance/camera services remain cutover checks.

Deferred minor review finding: the inherited device screen reuses a table ID, so the kiosk table does not receive DataTables sorting/search/pagination. This does not change the kiosk API; UI cleanup is deferred.

## Intent and user decisions

Device manager will become a permanent feature of the rewritten `absensi-hutamakarya` application. The user selected copying source into the host's normal Laravel directories, grouped under Sugihart, instead of continuing to distribute it as a Composer package. Subsequent development follows the absensi project.

The user requests as few files to move as practical and authorizes a new branch, preserving `main` as the working package-based alternative. Existing client URLs and API responses must remain compatible. PostgreSQL, old data/tokens/password hashes, and the existing separate module login/database remain unchanged.

## Branch isolation

The package branch was initially named `master` and was renamed to `main` at the user's request before the first GitHub push.

- The preexisting Laravel 13 package rewrite was uncommitted. It has now been preserved on `main` as commit `c135e509` (`feat: upgrade device manager to Laravel 13 with reusable PostgreSQL package`).
- Work is in `feature/native-sugihart-integration`, created from that commit in the current checkout. No additional worktree is proposed; the user's requested branch split is sufficient for these sequential alternatives.
- Further native design, implementation, tests, and documentation belong only to this feature branch. Do not merge the native refactor back into `main` unless requested.
- The user subsequently authorized pushing both branches; native updates may be pushed only to the feature branch. Environment files, database dumps, local PostgreSQL clusters, and generated handoff archives remain outside Git.
- The package baseline passed 20 application tests/152 assertions, Pint, and 6 converter tests before preserving it. These are baseline results, not evidence that the proposed native implementation already works.
- Branch-switch instructions must run `composer install` and clear Laravel caches after switching, because `vendor` and cached/generated files are not isolated by Git branches. Keep the existing `.env` and `APP_KEY`.

## Goals

1. Replace the Composer-package installation with native `App` classes and normal host directories.
2. Reduce the host application source additions from the current approximately 49 PHP/Blade files to **9 application files**.
3. Produce one optional handoff ZIP with those files, the static assets, and the native merge guide, using paths relative to the host root.
4. Preserve the current standalone app as a runnable/testable Laravel 13 application on the native branch.
5. Retain existing API, admin CRUD, CCTV subdevice creation/shared tokens, audit logs, login/logout, optional registration, password reset, and console installation/admin creation.
6. Preserve independent PostgreSQL connection `sdm`, auth guard/provider/broker `sdm`, route names `sdm.*`, existing table schemas, and `SDM_*` environment variables.

## Non-goals and anti-goals

- No SSO, merging of host/module `users` tables, renaming of production tokens, or destructive data migration.
- No frontend redesign or change to the camera/attendance endpoints provided by other applications.
- No multiple named PHP classes placed in a single file with manual includes merely to evade PSR-4.
- No embedding the static asset tree in a PHP string or making production PHP serve ZIP contents.
- No replacement of the host's Composer files, `APP_KEY`, controller base class, default auth, or application bootstrap with this repo's standalone wrappers.
- No modifications to the unavailable rewritten absensi source or claims that production integration is tested.

## Approaches considered

| Approach | Tradeoff | Decision |
| --- | --- | --- |
| Native Laravel files in Sugihart subdirectories | Familiar to the vendor; source is maintained with absensi; requires explicit integration points | Chosen by user |
| `app/Modules/Sugihart` module with a loader/provider | More centralized source copying; retains a module-specific layout | Not selected |
| Existing Composer package | Independent delivery/versioning and auto-discovery; extra package/repository concepts | Remains available on `main` |

Within the chosen approach, minimizing redundant controllers, table-only models, and Blade partials is the proposed implementation choice. Source clarity still matters: retain one separately autoloadable user model and one middleware class.

## Proposed host application files

| # | Path | Responsibility |
| --- | --- | --- |
| 1 | `app/Http/Controllers/Sugihart/DeviceManagerController.php` | Combine API, admin page/actions, auth actions, validation, transactional audit writes, and small module URL helpers |
| 2 | `app/Models/Sugihart/User.php` | The module's authenticatable user and its module-specific password-reset notification |
| 3 | `app/Http/Middleware/Sugihart/Authenticate.php` | Require an active module user, module-specific login redirects and intended URL, while preserving the host session |
| 4 | `app/Providers/SugihartServiceProvider.php` | Register module database/auth, load routes, and define `sdm:install`/`sdm:admin` console commands |
| 5 | `config/sugihart-device-manager.php` | Existing module configuration and explicit PostgreSQL credentials using the existing `SDM_*` names |
| 6 | `routes/sugihart.php` | All module routes with the existing prefix/name and correct web/stateless middleware boundaries |
| 7 | `database/migrations/sugihart/2026_11_06_000000_create_device_manager_tables.php` | Existing idempotent schema creation on `sdm`, preserving the migration basename/history |
| 8 | `resources/views/sugihart/admin.blade.php` | Combine shared admin layout/pages and guest auth forms in one Blade file, rendering only the selected screen |
| 9 | `resources/views/sugihart/kios.blade.php` | Combine the current kiosk HTML/CSS and inline JS partials without changing kiosk behavior |

Nine is the target application-file count, excluding static assets, documentation, `.env`, and the host's existing provider-list edit. The package currently also ships unused backup/sample views; those do not need native equivalents.

## Backend and auth behavior

- The controller extends `Illuminate\Routing\Controller` directly, avoiding assumptions about the vendor's `App\Http\Controllers\Controller` traits or constructors.
- Device, vendor, and audit-log tables can use the query builder rather than three additional Eloquent model files. Supply the same view data/fields and write timestamps explicitly where Eloquent previously did so. Use the one `User` model for authentication and user operations.
- CRUD writes and their audit log remain one `sdm` transaction. Preserve validation, not-found behavior, soft deactivation, password placeholder handling, and nullable fields.
- Module URL/asset URL helpers move into the controller as small public static helpers called by the consolidated views. No global helper file or Composer autoload entry is added.
- The `User` model sends a module-specific password-reset notification using an anonymous notification class in the same file. It must not register a global reset-URL callback that affects host accounts. Existing mailer/broker behavior and reset-token table remain intact; notifications are not newly queued.
- Preserve login throttling, session regeneration, module-only intended URLs, inactive-user rejection, and module logout preserving host login/session data.
- `sdm:install` and `sdm:admin` are console closures registered by the provider, preserving signatures and password prompting without two additional command-class files.

## Routes, config, and schema

- The host manually appends `App\Providers\SugihartServiceProvider::class` to its existing `bootstrap/providers.php`. This is the one required PHP change to an existing host file; no Composer path repository or custom PSR-4 mapping is required.
- Provider registration does not replace default host database/auth/session settings. Use existing config key `sugihart-device-manager` and the same default route prefix `sugihart_device_manager/public` and asset prefix `vendor/sugihart-device-manager`.
- Load `routes/sugihart.php` under prefix/name once. Apply `web` and `Authenticate` only to the appropriate admin routes; API routes remain stateless and do not require admin login or start sessions.
- Preserve all four legacy API methods, raw device fields/timestamps, HTTP 200 failure payloads, CCTV URL slash substitution, distinct active filters, and NULL-first token sorting.
- Keep the migration in the nested `database/migrations/sugihart/` directory. Default Laravel migration discovery is not recursive, so a normal host `migrate` must not accidentally run module migrations on the host migration repository.
- `sdm:install` explicitly invokes `migrate` with `--database=sdm`, the real path of that nested migration directory, and the requested `--force` value. It must never execute the host's normal migrations against the module database.
- Existing schema creation skips imported tables, adds missing reset-password storage, and rejects destructive `down()`. The original migration basename avoids a redundant migration when switching an already installed module between alternatives.

## Consolidated views

- The admin Blade receives a selected page/auth-screen value. Shared layout and forms can be consolidated mechanically; no speculative UI redesign is required.
- Guest login/register/reset screens do not query/render authenticated sidebar data. Preserve CSRF, validation errors, form actions, remember-me, CRUD forms, delete POST behavior, token generation, and CCTV Create Sub behavior.
- Kiosk consolidation replaces includes with their existing content in the same order, with explicit newline boundaries to avoid JS line-comment concatenation. Preserve safe `Js::from` values, configured URLs, local camera refresh, workers, carousel, and current one-time hash reload behavior.
- Do not remove externally supplied local kiosk files or alter calls to attendance services.

## Static assets and one-file handoff

- Native authoritative static files move from `packages/.../public` to `public/vendor/sugihart-device-manager` on the feature branch. Change that branch's Git ignore rule so the authoritative assets are versioned there. Main retains its original package source/publishing model.
- Preserve required Elmer/third-party asset paths. Exclude executable vendor demos and irrelevant private/generated files from the handoff. Pruning unused static assets is optional and only allowed when dependencies remain complete.
- A build tool outside the nine application files produces `integration/sugihart-native.zip`. It contains the nine application files, the static asset subtree, and a uniquely named native merge guide. The tool is a developer convenience and is not required in the host app.
- The ZIP must never overwrite host `composer.json`, `composer.lock`, `bootstrap/providers.php`, `.env`, or `public/index.php`. Include a manifest listing what will be extracted.
- Asset files still exist individually on the server; the ZIP minimizes handoff/copy steps, not the physical number of font/CSS/JS files. Do not claim the entire deployed feature consists of only nine files.

## Standalone repository on the feature branch

- Remove the root dependency/repository for `sugihart/device-manager` and register the native provider in the standalone wrapper. Refresh the root lock/autoload/discovery state using normal Composer operations; retain Laravel 13 and PHP `~8.5.12`.
- Remove package-based source from this branch after all used behavior/assets have native equivalents. Main already preserves it.
- Keep developer tests/tools/docs separate from the nine copied application files. Update tests to the native implementation, not to legacy classes left around as a shortcut.
- Make the native merge guide the primary guide on this branch; clearly identify the package guide as belonging to main.

## Required verification and acceptance

1. Native branch boots without installing or autoloading `sugihart/device-manager` and uses normal `App` PSR-4 loading.
2. Preserve and extend the 20 application tests for API, login/reset, CRUD/audit, optional registration, NULL ordering, and host guard/database isolation. Exercise transaction rollback/timestamps with query-builder CRUD.
3. Test routes/config/views cached and uncached; no duplicate route names or incorrectly wrapped API routes.
4. Test repeated module installation against existing imported migration history, without running a synthetic host migration on `sdm`.
5. Test the handoff manifest/file count, absence of host-global/private files, and ZIP extraction into a fresh host fixture with only provider registration and env settings added.
6. Smoke-test login and admin interactions, including Create Sub, table editing/deleting, password reset forms, published CSS/JS/fonts, and kiosk HTML/inline JS syntax. Run PostgreSQL-specific tests against an isolated cluster; do not use the user's live/restored local database for destructive tests.
7. Confirm `main` still points to the preserved package baseline and has not acquired native commits. Document switching branches and refreshing dependencies/caches.

Source absensi from the vendor and the physical kiosks are not available; final host middleware/server/browser/hardware verification remains a vendor cutover requirement.

## Left to the implementer

Method naming, exact private-helper decomposition, screen-variable structure, and lossless mechanical template consolidation may be decided during implementation. The nine-file target, native namespaces/directories, branch preservation, API/auth/database isolation, optional ZIP boundaries, and absence of extra host-global file replacements are design constraints. If a tenth application file is necessary to preserve correctness, report the reason instead of silently growing the design or abusing autoloading.

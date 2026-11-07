# Native Sugihart Integration Implementation Plan

> **For agentic workers:** Use superpowers:executing-plans to implement this plan task-by-task with TDD and one final independent review.

**Goal:** Implement the approved nine-file native Laravel integration and produce its ZIP and manual merge guide on the feature branch.

**Architecture:** Keep one native controller, one auth model, middleware, provider, config, route file, migration, and two consolidated views. Query-builder CRUD replaces table-only models; native assets are authoritative under public/vendor. Provider registration is the only existing host PHP edit.

**Tech Stack:** PHP ~8.5.12, Laravel ^13.0, PostgreSQL, Blade, Python stdlib ZIP tooling.

**Spec:** ../specs/2026-11-07-native-sugihart-integration-design.md

## Global Constraints

- Work only in feature/native-sugihart-integration; main remains c135e509.
- Nine application files at exactly the paths specified by the design.
- Preserve SDM_* settings, sdm connection/guard/broker, sdm.* routes, legacy API and auth isolation.
- Nested migration keeps its existing basename and never runs parent migrations on sdm.
- ZIP excludes all host-global replacements, secrets and database dumps.
- User explicitly requested execution of the design; continue implementation without another approval round.

## Review Focus

- Guest forms must not fetch sidebar data or need a live database just to render.
- Query-builder writes must preserve timestamps and roll back when audit insertion fails.
- Migration discovery must not run a synthetic parent migration on sdm.
- Consolidated inline JavaScript must retain Create Sub and valid syntax across screen variants.
- Extraction into a fresh host must boot without package classes or Composer metadata.

### Task 1: Native backend and existing regression suite

Files: native provider, model, middleware, config, routes, controller, nested migration; bootstrap/providers.php, composer.json/lock, tests/TestCase.php and existing Feature tests.

Interfaces: provider registers sdm auth/config and commands; controller exposes existing action names and static page/asset helpers; TestCase uses native migration and database fixtures.

- [x] Adapt tests to native User and database fixtures; observe failures for missing native classes.
- [x] Implement the seven PHP files and remove package Composer wiring from the wrapper.
- [x] Consolidate views in Task 2 before the full legacy suite becomes green; run backend tests independently where practical.
- [x] Add transaction rollback, timestamps, user uniqueness and migration-boundary regressions; run them RED/GREEN as applicable.

### Task 2: Consolidate templates and migrate static assets

Files: resources/views/sugihart/admin.blade.php, kios.blade.php, public/vendor/sugihart-device-manager, .gitignore, pint.json, phpunit.xml.

Interfaces: admin view consumes selected page/screen and original datasets; kiosk view consumes token and pc_local. All URLs use native controller helpers.

- [x] Preserve existing HTTP/UI regression expectations and add missing form/Create Sub checks.
- [x] Mechanically merge admin/auth layouts and screen sections; merge kiosk includes with newline boundaries.
- [x] Move authoritative static files with workspace path checks; remove unused executable demo.
- [x] Run full application suite and rendered JavaScript syntax checks; remove obsolete package source only after native behavior passes.
- [x] Commit native application and record results in the ledger.

### Task 3: Handoff ZIP, manual guide and clean-host test

Files: tools/build_native_handoff.py, tools/test_build_native_handoff.py, PANDUAN_MERGE_MANUAL_ABSENSI_HUTAMAKARYA.md, readme.md and related docs.

Interfaces: build tool exposes build_archive(root, output), APPLICATION_FILES and a CLI; archive contains nine files, static assets, guide, and a SHA-256 manifest.

- [x] Write failing ZIP extraction/privacy/file-count tests; implement deterministic safe paths and manifest.
- [x] Write native-only step-by-step manual guide and branch-switch instructions.
- [x] Build integration/sugihart-native.zip; extract into a fresh Laravel host fixture with no package and boot its routes/auth/migration.
- [x] Verify package main SHA unchanged, all links and archive entries; commit tooling/docs.

### Task 4: PostgreSQL, runtime and whole-branch review

Files: tests and implementation fixes discovered by independent review; plan/spec status.

- [x] Run application suite on isolated PostgreSQL and SQLite; run Pint, platform checks and Python tests.
- [x] Verify route/config/view caches, local HTTP, assets, inline JS and native archive fixture.
- [x] Request one fresh whole-branch review; fix important findings with RED/GREEN tests.
- [x] Commit tested changes and push only the feature branch under the existing push authorization.
- [x] Report artifact, guide, test evidence and actual host/hardware limitations.

Completion evidence: 25/25 application tests with 216 assertions on each of SQLite and PostgreSQL; 9/9 Python tests; Pint and Composer/platform checks passed. Final Edge regressions passed token preservation and later-page actions; the original browser smoke passed CRUD, CSRF login/logout, assets and kiosk JS. The final ZIP manifest and extracted host passed before/after config/route/view caching. See the spec for the deferred minor UI issue and unavailable production-host/hardware checks.

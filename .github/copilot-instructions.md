# Copilot / agent instructions for WePlan

Purpose: Help an AI editing or reviewing this repo to be immediately productive by describing the architecture, important files, conventions, and exact commands to run and verify changes.

Big picture
- This is a small web app with a PHP frontend, MySQL database artifacts, and a few helper scripts in Python.
- PHP pages and examples live under `002-PHP/` (login, CRUD guides, role-based redirects).
- Database DDL and seed scripts are in `000-Bases de Datos/` (e.g. `001-tablas.sql`, `002-Creacion de bases de datos.sql`).

Key files and patterns (examples you should read before editing)
- `002-PHP/002-primera prueba de conexion.php` — simple mysqli usage and sample output formatting.
- `002-PHP/003-procesa_login.php` — login flow: reads `$_POST` values, queries `usuarios` table, sets `$_SESSION['usuario']`, then redirects based on `tipo_de_usuario` to `tipo_de_usuario/{admin,host,usuario}.php`.
- `002-PHP/Guia CRUD/` — canonical CRUD examples (Create/Read/Update/Delete) used as patterns for new handlers.
- `000-Bases de Datos/003-conexion.py` — a Python DB connection example (different DB name and creds) used for ad-hoc checks.
- `css/estilos.css` and `html/` — front-end static assets and example pages.

Developer workflows (commands to run locally)
- Start a quick PHP dev server from the repository root:

  php -S localhost:8000 -t .

- Run the Python DB check (requires `mysql-connector-python`):

  python "000-Bases de Datos/003-conexion.py"

- Search for literal DB credentials or related patterns when changing DB code:

  grep -R "usuario-weplan" -n || grep -R "Clientes123$" -n

Conventions & project-specific notes
- Filenames, comments and identifiers are mostly Spanish — prefer Spanish when adding UI or variable names for consistency.
- Session usage: login handlers call `session_start()` and set `$_SESSION['usuario'] = 'si'` — other pages expect this pattern.
- Role-based redirects: preserve the `tipo_de_usuario` switch/redirect behavior when modifying login flow.
- CRUD samples under `002-PHP/Guia CRUD/` are the canonical examples to mirror for new handlers.

Security and consistency cautions (discoverable issues to be mindful of)
- Credentials are stored in plaintext in several files; any change that centralizes credentials should update all occurrences (`002-PHP/*` and `000-Bases de Datos/*`).
- SQL in `003-procesa_login.php` is built via string concatenation using `$_POST` values — this is vulnerable to SQL injection. If you modernize this code, use parameterized queries (mysqli prepared statements) and ensure behavior stays compatible with pages that rely on the query result shape.
- There are inconsistent DB names/credentials between PHP files (`WePlanDB`, user `usuario-weplan`) and the Python sample (`clientes`). Verify which DB is authoritative before applying schema changes.

What to change and examples
- When updating login validation, keep the existing session and redirect semantics. Example (preserve):

  session_start();
  // set: $_SESSION['usuario'] = 'si';
  // redirect by tipo_de_usuario to ./tipo_de_usuario/{admin,host,usuario}.php

- When adding DB changes, update the SQL DDL in `000-Bases de Datos/` and any connection snippets in `002-PHP/*` and `000-Bases de Datos/003-conexion.py`.

Where to look for more context
- Project overview: `README.md` (root).
- DB DDL: `000-Bases de Datos/*.sql`.
- Login + role pages: `002-PHP/` and `002-PHP/tipo_de_usuario/`.

If anything below is unclear or you need additional examples (tests, CI, or conventions), tell me which area to expand.

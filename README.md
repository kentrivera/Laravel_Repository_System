# Repository File Manager

Laravel + Breeze (Blade) authentication + repository-style file manager (folders + files).

## Functionality

- Authentication (Laravel Breeze): register, login, logout
- Profile management (edit profile, change password)
- Repository manager: create folders, upload files, replace files, rename, delete
- File editing: edit small text files in the browser (size-limited)
- Download support
- Admin UI: responsive sidebar + top nav
- UX: SweetAlert2 toast notifications + delete confirmations

## Requirements

- PHP 8.2+
- Composer
- Node.js + npm
- MySQL/MariaDB (XAMPP is fine)

## Setup

1) Install dependencies

- `composer install`
- `npm install`

2) Configure environment

- Copy `.env.example` to `.env`
- Update your database values in `.env` (XAMPP defaults shown below). In most cases, the only required change is `DB_DATABASE`.

	- `DB_CONNECTION=mysql`
	- `DB_HOST=127.0.0.1`
	- `DB_PORT=3306`
	- `DB_DATABASE=lara_repo_file_manager`
	- `DB_USERNAME=root`
	- `DB_PASSWORD=`

Optional: create the database automatically:

- `php scripts/create_mysql_database.php`

3) App key + migrations

- `php artisan key:generate`
- `php artisan migrate --seed`

4) Run the app

- Development assets: `npm run dev`
- Start server: `php artisan serve`

For a production build, use `npm run build`.

Open: `http://127.0.0.1:8000`

## Pages

- Home: `/`
- About: `/about`
- Register: `/register`
- Login: `/login`
- Dashboard: `/dashboard` (redirects to repository)
- Repository: `/repository` (requires login)

## Users table & login (database-backed)

- The `users` table is created by the migration `0001_01_01_000000_create_users_table`.
- Registration inserts a new record into `users`.
- Login checks credentials against the `users` table (Laravel auth provider) and the UI reads the logged-in user from the database using `Auth::user()`.

After login, all users can access the Repository pages (no roles).

### Default seeded user

When you run `php artisan migrate --seed`, a user is created (or reused if it already exists):

- Email: `test@example.com`
- Password: `password`

You can also register your own account at `/register`.

## Storage location

- Managed files are stored under `storage/app/repository` (private disk).

## Audit log

- Repository actions are logged to the `repository_actions` table.

## Tests

- `vendor/bin/phpunit` runs with an in-memory SQLite database (configured in `phpunit.xml`), so you don't need MySQL running to execute tests.

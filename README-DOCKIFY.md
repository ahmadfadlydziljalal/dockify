# Dockify Starter Kit

Dockify adalah starter kit Yii 2 yang sudah dipaketkan dengan Docker untuk development, testing, dan build asset.
Anda bisa menginstal starter kit ini menggunakan perintah berikut:

~~~
composer create-project --prefer-dist ahmadfadlydziljalal/dockify .
~~~

## Stack

- PHP + Apache untuk aplikasi web
- MySQL untuk database utama dan support
- Node/Gulp untuk build asset frontend
- Selenium/chrome untuk acceptance test

## Quick start

- Siapkan file environment `.env` lalu sesuaikan value yang dibutuhkan. 
- Sesuaikan nama database di sql file di direktori `.docker/mysql/*.sql` dengan `.env` pada section `DB` itu harus sama.
- Build dan jalankan container:

1. Install dependency PHP dan migration di dalam container php,
2. Install dependency Node di dalam container gulp,
3. Jalankan service-service container di background.


```bash
docker compose run --rm gulp npm install && \
docker compose run --rm php sh -c "yii migrate-support && yii migrate && tests/Support/bin/yii migrate" && \
docker compose up -d
```

- Buka aplikasi:

```text
http://domain:8000, atau http://domain:3000 untuk versi support gulp
```
dimana domain bisa: `localhost`, `127.0.0.1` atau `IP address` dari mesin Anda.

## Environment variables

Minimal yang umum dipakai:

- `DB_PORT`
- `DB_NAME`
- `DB_SUPPORT_NAME`
- `DB_USER`
- `DB_PASSWORD`
- `DB_ROOT_PASSWORD`
- `DB_DSN`
- `DB_DSN_SUPPORT`
- `DB_DSN_TEST`

Kalau fitur auth/HRD dan storage aktif, sesuaikan juga:

- `HRD_CLIENT_ID`
- `HRD_CLIENT_SECRET`
- `HRD_AUTH_URL`
- `HRD_TOKEN_URL`
- `HRD_API_BASE_URL`
- `HRD_API_USER_INFO`
- `SPACES_DO_KEY`
- `SPACES_DO_SECRET`
- `SPACES_DO_REGION`
- `SPACES_DO_ENDPOINT`
- `SPACES_DO_BUCKET`
- `SPACES_DO_URL`

## Build asset frontend

Gulp container sudah disiapkan untuk compile SCSS dan JS.
Gunakan perintah di bawah bila ingin build asset secara manual:

```bash
docker compose run --rm gulp npm run build
```

Saat development, service `gulp` bisa dijalankan untuk watch/reload.

## Testing

```bash
docker compose exec -T php vendor/bin/codecept build
docker compose exec -T php vendor/bin/codecept run
```

Untuk acceptance test yang memakai browser real, aktifkan service `chrome` lalu sesuaikan suite Codeception.

## Production note

Image PHP dibuat production-ready di Dockerfile. 
Untuk local development, `docker-compose.yml` masih bisa override env ke `YII_ENV=dev` dan `YII_DEBUG=1` di `docker-compose.yml`.

## GitHub Actions

Project ini siap dipakai dengan GitHub Actions untuk validasi CI (build/test) pada setiap push dan pull request.

### Branch default: `main`

Gunakan branch `main` sebagai default branch (bukan `init`).
Lihat [GitHub Actions workflow](.github/workflows/main.yml) untuk panduan mengubah default branch.

```bash
git branch -m main
```

Lalu di GitHub

- Buka **Settings → Branches**
- Ubah **Default branch** ke `main`

### Repository secrets untuk Docker Hub

Sebelum workflow dijalankan, pastikan repository GitHub Anda memiliki secrets berikut di
**Settings → Secrets and variables → Actions**:

```yaml
username: ${{ secrets.DOCKERHUB_USERNAME }}
password: ${{ secrets.DOCKERHUB_PASSWORD }}
```

Nama secret yang wajib dibuat:

- `DOCKERHUB_USERNAME`
- `DOCKERHUB_PASSWORD`

### Trigger workflow yang disarankan

Untuk starter kit ini, trigger yang umum:

- `push` ke branch selain `main`
- `pull_request` ke `main`

Dengan begitu, semua perubahan ke branch utama selalu tervalidasi otomatis sebelum/ketika merge.

# Exo – FTP-Friendly PHP Web Root

A classic shared-hosting PHP project.  Upload files via FTP to `public_html` and open your domain — no build step, no Composer, no SSH required.

---

## Requirements

| Requirement | Minimum |
|---|---|
| PHP | 8.1+ |
| MySQL | 5.7+ / 8.0+ (recommended) |
| Web server | Apache or LiteSpeed |

No Composer. No Node. No npm. No CLI install steps.

---

## Deployment (FTP)

1. Upload **all files** (except `frontend-nextjs/`) to your `public_html` directory.
2. Open `https://yourdomain.com/install.php` in a browser.
3. Fill in the installer form (DB credentials, admin account, site name).
4. Done — visit `https://yourdomain.com/` to see your site.

---

## File Structure

```
/index.php          ← Public frontend (router)
/install.php        ← One-time web installer
/admin.php          ← Admin panel
/.env               ← Created by installer (or copy .env.example)
/.env.example       ← Environment template
/.gitignore
/src/
  bootstrap.php     ← Class autoloader (no Composer needed)
  Config.php        ← .env loader
  Database.php      ← PDO/MySQL wrapper
  Auth.php          ← Session authentication
  Csrf.php          ← CSRF token helpers
  Storage/
    StorageInterface.php
    MysqlDriver.php ← Full-featured MySQL backend
    TxtDriver.php   ← Demo-only flat-file backend
/storage/
  pages/            ← JSON files used by TxtDriver
  settings.json     ← Settings used by TxtDriver
  install.lock      ← Created after installation
/frontend-nextjs/   ← Original Next.js scaffold (NOT part of runtime)
```

---

## Storage Drivers

### MySQL (default, recommended)

- Full page CRUD
- Admin authentication
- Site settings
- Requires a MySQL database and credentials in `.env`

### TXT / JSON (demo only)

- Flat-file storage under `storage/`
- **Does NOT support admin login**
- Suitable only for simple static content preview
- Used automatically when `DB_NAME` is empty in `.env`

> **Admin authentication ALWAYS requires MySQL.** The TXT driver is provided
> solely for zero-database demos and cannot be used to log in to the admin panel.

---

## Admin Panel

Visit `/admin.php`. Features:

- Secure session-based login (CSRF-protected, `password_hash`/`password_verify`)
- Site title & meta description editing
- Page CRUD (slug, title, HTML content, meta description, meta keywords)

---

## SEO

Every page outputs:

- `<title>` with page title + site name
- `<meta name="description">`
- `<meta name="keywords">` (when set)
- Open Graph tags (`og:title`, `og:description`, `og:url`, `og:type`)
- Twitter Card tags
- `<link rel="canonical">`

---

## Security Notes

- Admin login is MySQL-only (no flat-file auth).
- All database queries use **prepared statements** — no raw interpolation.
- CSRF tokens protect all state-changing forms.
- Session is regenerated on login.
- Secure, HttpOnly, SameSite=Strict session cookies.
- `.env` is never committed (listed in `.gitignore`).
- `DB_PASS` is never displayed after form submission.

---

## Development / Next.js Frontend

The original Next.js scaffold is preserved in `/frontend-nextjs/`.  It is **not served** by the PHP runtime.

To work on the Next.js app:

```bash
cd frontend-nextjs
npm install
npm run dev
```

---

## License

See [LICENSE](LICENSE).

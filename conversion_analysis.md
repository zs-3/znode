# ZNode PHP Conversion Mapping

## Core Architecture
| Component | Current (Node/React) | Plain PHP Equivalent |
| :--- | :--- | :--- |
| Language | TypeScript / JavaScript | PHP 8.x |
| Framework | Express.js / React | Plain PHP (No Framework) |
| Routing | React Router / Express Router | File-based routing (index.php, login.php) |
| Database | Prisma (MySQL) | PDO (MySQL) |
| Auth | JWT / Passport.js | Native PHP Sessions |
| Styling | Tailwind CSS (Compiled) | Tailwind CSS (via CDN for simplicity) |
| Icons | Lucide React | Lucide SVG / Feather Icons |

## Functionality Mapping
| Feature | Implementation (Current) | PHP Implementation |
| :--- | :--- | :--- |
| MOFH API | `fetch` with Basic Auth | `curl` with `CURLOPT_USERPWD` |
| VistaPanel | `jsdom` | `DOMDocument` & `DOMXPath` |
| Email | `nodemailer` | `mail()` or custom SMTP socket class |
| Backup | `adm-zip` | `ZipArchive` |
| SEO | Dynamic Sitemap/Robots | PHP script generating XML/Text |
| I18n | `react-i18next` | PHP Arrays (`en.php`, `vi.php`) |

## File Structure Plan
- `/php-version/`
  - `index.php` (Dashboard)
  - `login.php`
  - `register.php`
  - `hosting_create.php`
  - `includes/`
    - `config.php` (DB & API Keys)
    - `functions.php` (Shared logic)
    - `db.php` (PDO Connection)
    - `auth.php` (Session & Login logic)
    - `mofh.php` (API wrapper)
  - `templates/`
    - `header.php`
    - `footer.php`
    - `sidebar.php`
  - `api/` (For AJAX calls if needed)
    - `check_domain.php`

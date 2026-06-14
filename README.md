# afriJudith.online

Personal portfolio for **Judith Afriyie** — Data Analyst & Web Developer,
final-year Computer Science student at **Takoradi Technical University**.

Built in plain PHP 8 using a small, hand-rolled MVC framework so it stays
dependency-free, easy to read, and trivially upgradable to a database
backend later.

## Project layout

```
afrijudith.online/
├── index.php                  Front controller — every request starts here
├── .htaccess                  Pretty-URL rewrites (Apache / XAMPP)
├── config/
│   └── config.php             App + database config
├── app/
│   ├── core/
│   │   ├── Autoloader.php        PSR-4 autoloader for App\*
│   │   ├── App.php               Tiny router (URL → controller/action)
│   │   ├── Controller.php        Base Controller (view + model helpers)
│   │   └── Model.php             Base Model (lazy PDO when DB is enabled)
│   ├── controllers/
│   │   ├── HomeController.php    /          (single-screen landing)
│   │   ├── AboutController.php   /about
│   │   ├── SkillsController.php  /skills
│   │   ├── WorkController.php    /work
│   │   └── ContactController.php /contact
│   ├── models/
│   │   └── ProfileModel.php      Judith's bio / skills / projects / socials
│   └── views/
│       ├── layouts/main.php      Master layout (landing-aware)
│       ├── partials/             header, footer, preloader
│       ├── home/index.php        100vh landing
│       ├── about/index.php
│       ├── skills/index.php
│       ├── work/index.php
│       ├── contact/index.php
│       └── errors/404.php
├── public/
│   └── assets/
│       ├── img/logo.png       Transparent logo (afriJudith.online)
│       ├── css/style.css      Design system + responsive UI
│       └── js/main.js         Preloader + scroll reveal
└── tools/
    └── make_transparent.php   One-off: strip white bg from a logo source
```

## Running locally

### XAMPP (recommended on this machine)

The project already lives in `htdocs`, so just:

1. Start Apache from the XAMPP control panel.
2. Open <http://localhost/afrijudith.online/>.

### PHP built-in server (no Apache needed)

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/afrijudith.online
php -S localhost:8000 router.php
```

Then visit <http://localhost:8000>.

## URL routing

| URL          | Resolves to                  | Notes                              |
| ------------ | ---------------------------- | ---------------------------------- |
| `/`          | `HomeController::index`      | 100vh landing — no scroll          |
| `/about`     | `AboutController::index`     | Bio, school, role, stats           |
| `/skills`    | `SkillsController::index`    | Highlights + animated skill bars   |
| `/work`      | `WorkController::index`      | Project cards with stack chips     |
| `/contact`   | `ContactController::index`   | Info panel + (placeholder) form    |

Anything that doesn't resolve falls through to the styled 404 view.
Generic shape: `/<controller>/<action>/<...params>` → `App\Controllers\<Name>Controller::<action>(...)`.

## Adding a database later

1. Set `database.enabled = true` and fill credentials in `config/config.php`.
2. In any model, call `$this->db()` — you get a configured `PDO` instance.
3. Swap the static arrays in `ProfileModel` for real queries.

No other layer needs to change. The controller, view, router, and asset
pipeline already speak the same shape.

## Regenerating the logo

If the brand mark ever changes, drop the source image somewhere and run:

```bash
php tools/make_transparent.php <path/to/source.png-or-jpg> public/assets/img/logo.png
```

The script removes near-white pixels and produces an anti-aliased
transparent PNG.

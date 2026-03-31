# Config Finder

Sometimes configuration forms exist, but nothing links to them.

Instead of digging through the modules list to find them, this module collects every module that declares a `configure` route and lists them in one place.

---

## Installation


`composer require cms-alchemy/config-finder
drush en config_finder`


> Drupal uses `config_finder` (underscores) as the module name, even though the Composer package is `config-finder`.

---

## Usage

Go to:


/admin/config/system/config-finder


(Configuration → System → Module configuration index)

---

## What it does

Config Finder scans enabled modules and:

- Finds modules that declare a `configure` route
- Verifies the route exists
- Checks whether the current user has access
- Lists them in one place

Each module is shown with a status:

- **Accessible** — you can click through
- **Access denied** — route exists but you lack permission
- **Missing route** — module declares config but the route does not exist

---

## Settings


`/admin/config/custom/config_finder`


- **Hide inaccessible or invalid configuration routes**  
  When enabled (default), only accessible configuration pages are shown.

---

## License

GPL-3.0-or-later

# Config Finder

Sometimes configuration forms exist, but nothing links to them.

Instead of digging through the modules list to find them, this module collects every module that declares a `configure` route and lists them in one place.

---

## Installation


```
composer require cms-alchemy/config-finder
drush en config_finder
```


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

By default, only configuration routes you can access are shown.  
Unchecking the option in settings will show all detected entries.

Each module is shown with a status:

- **Accessible** — the route exists and you can access it
- **Access denied** — the route exists but you do not have permission
- **Unresolved route** — the module declares a `configure` entry, but it does not resolve to a direct, accessible route

---

## Settings


`/admin/config/custom/config_finder`


- **Hide inaccessible or invalid configuration routes**  
  When enabled (default), only accessible configuration pages are shown.

---

## License

GPL-3.0-or-later

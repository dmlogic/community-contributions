# Community Contributions

This project provides tools to build a small community who can register, contribute to funding and keep updated as to how that funding is being spent.

## Features

* Database of properties, residents and funds
* User roles for Admin, Resident and Supplier
* Admins can create a request for funding which is emailed to Residents
* Residents can pay via Stripe or manually log an offline payment
* Admins can manually log & verify offline payments and attribute to residents
* Suppliers (or Admins) can log upcoming or completed works and the charge to the fund
* Dashboard shows the current state of funding and upcoming work
* Resident contributions are anonymised to all expect admins

## Software stack

This is Laravel 10 app built from a Breeze/Intertia/Vue.js starter kit.

It has been built to run a the Fly.io free tier with a SQLite database mounted from small a shared volume.

The app could also be hosted anywhere that supports PHP8.2 and common extensions.

## Local development

Sail is best. `./vendor/bin/sail up -d`

### Testing

For server side tests, run `php artisan test`

For VueJS component tests, run `@todo`.

## Deployment

With reference to the [Fly.io Laravel documentation](https://fly.io/docs/laravel/), create a suitable project and `fly.toml` file.

Create a small [Volume](https://fly.io/docs/reference/volumes/) for the app and be sure to add mount in your fly.toml file.
The following is recommended as it also solves the issue of persistent storage.

```
[mounts]
source="myapp_data"
destination="/var/www/html/storage"
```

Adjust your fly ENV values to attach to a database in this mount, for example

```
  DB_CONNECTION="sqlite"
  DB_DATABASE="/var/www/html/storage/database.sqlite"
  ```

  Finally, you will need to seed at least one admin account. This could be done via an [console session](https://fly.io/docs/flyctl/ssh-console/) using the `artisan tinker` command, creating a production Seeder class or by transferring a database via [SFTP](https://fly.io/docs/flyctl/ssh-sftp/).

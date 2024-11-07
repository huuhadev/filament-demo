# Filament Demo App

A demo application to illustrate how Filament Admin works.

## Installation

Clone the repo locally:

```sh
git clone https://github.com/laravel-filament/demo.git filament-demo && cd filament-demo
```

### Installation with Docker

> Make sure you have Docker installed on your local machine.

#### Environment Demo

You can execute it via the `docker compose up` command in your favorite terminal.
Please note that the speed of building images and initializing containers depends on your local machine and internet connection - it may take some time.

```bash
cp .env.docker.example .env
docker-compose up
```

The filament demo will be available to `https://filament-demo.com` in your browser.

#####  Log into Admin panel

You're ready to go! Visit the url in your browser, and login with:

-   **Username:** huuhadev@gmail.com
-   **Password:** password

### Installation Manuals

```sh
composer install
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

> **Note**  
> If you get an "Invalid datetime format (1292)" error, this is probably related to the timezone setting of your database.  
> Please see https://dba.stackexchange.com/questions/234270/incorrect-datetime-value-mysql


Create a symlink to the storage:

```sh
php artisan storage:link
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```

You're ready to go! Visit the url in your browser, and login with:

-   **Username:** huuhadev@gmail.com
-   **Password:** password

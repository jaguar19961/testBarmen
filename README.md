## Installation

- Clone this repository.
- Create `.env` file and set your database credentials.
- Run `composer install`.
- Run `php artisan key:generate`.
- Run migrations `php artisan migrate`

NOTE: 

*Run `php artisan migrate --seed` to migrate tables and generate fake data using factories*

When using seeds, tables will be truncated first

Also, when using seeds, default account will be generated with these credentials:

Email: `barmen@mail.com`
Password: `secret`


- Generate passport keys `php artisan passport:install`
- Serve your app by running `php artisan serve`
- Enjoy :)

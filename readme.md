# Laravel RSS

RSS reader application built on the [Laravel](https://laravel.com) framework.

## Installation

1. Run `composter install`
2. Copy `.env.example` to `.env`
3. Open `.env` and configure the database settings for your system
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed` to setup the database and seed data.
6. Run `npm install`
7. Run `npm run dev` to compile front-end assets
8. Start the server with `php artisan serve`

The following test user will be setup during database seeding, and can be used to login:

| Username | Email         | Password |
| -------- | ------------- | -------- |
| test     | test@test.com | test     |

## Artisan Commands

`load:posts [options] <ids>`

Load posts for any or all feeds in the database.

**Example**

```bash
php artisan load:posts --all            # loads posts for ALL feeds
php artisan load:posts 1,4,7,8          # loads posts for specific feeds, by ID
```

## License

Laravel RSS is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

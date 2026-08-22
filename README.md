<p align="center">
  <a href="https://akramdev.com/blog/professional-laravel-api-guide">
    <img src="https://akramdev.com/assets/images/blog/laravel/laravel-13-professional-api-guide.webp" width="800" alt="Building Production-Ready APIs with Laravel 13">
  </a>
</p>

<h1 align="center">Professional Laravel API Guide</h1>

<p align="center">
  <strong>Building Production-Ready APIs with Laravel 13</strong><br>
  A complete reference for designing, versioning, securing, filtering, authorizing,<br>testing, and documenting a production-ready API.
</p>

<p align="center">
  <a href="https://akramdev.com/blog/professional-laravel-api-guide"><img src="https://img.shields.io/badge/Read%20the%20guide-akramdev.com-FF2D20?style=flat-square" alt="Read the guide"></a>
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.3+">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="MIT License">
</p>

---

## About

This repository is the companion codebase for the guide
**[Building Production-Ready APIs with Laravel 13](https://akramdev.com/blog/professional-laravel-api-guide)**.

The guide is a progressive technical reference: it starts from a fresh Laravel install and
builds **Orders Hub**, a real API, one checkpoint at a time — consistent JSON responses,
resource-oriented URLs, versioning, Sanctum authentication, API Resources, filtering and
sorting, the full CRUD verb set, policies and token abilities, error handling, and generated
documentation.

Every checkpoint is small, verifiable, and builds on the one before it. Nothing is hand-waved.

## Requirements

| Requirement | Version / Notes |
| --- | --- |
| PHP | 8.3 or newer (required by Laravel 13) |
| Composer | Latest stable |
| MySQL | Any recent 8.x |
| Git | Latest stable |
| HTTP client | Postman, Insomnia, or `curl` |

You should already be comfortable with Laravel routing, controllers, Eloquent, migrations,
and validation. The guide teaches API design — not Laravel fundamentals.

## Initial Setup

Create the project from scratch, exactly as the guide does:

```bash
composer create-project laravel/laravel:^13.0 orders-hub
cd orders-hub
php artisan install:api
php artisan migrate
```

Then point `.env` at a MySQL database named `orders_hub`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=orders_hub
DB_USERNAME=root
DB_PASSWORD=
```

### Or clone this repository

```bash
git clone https://github.com/akramghaleb/professional-laravel-api-guide.git
cd professional-laravel-api-guide
composer install
cp .env.example .env
php artisan key:generate
# configure your MySQL credentials in .env, then:
php artisan migrate --seed
php artisan serve
```

The API is then available at `http://localhost:8000`.

## Postman Quick Start

This repository includes a Postman Collection v2.1 and a local environment:

- [`public/docs/collection.json`](public/docs/collection.json) — all documented API requests
- [`postman/Orders-Hub-Local.postman_environment.json`](postman/Orders-Hub-Local.postman_environment.json) — local URL and bearer-token variables

After running `php artisan migrate:fresh --seed` and `php artisan serve`:

1. In Postman, select **Import** and import both JSON files above.
2. Select the **Orders Hub - Local** environment.
3. Run **Authentication / Login**. The included local-only account is
   `manager@manager.com` with password `password`.
4. The login test script saves the returned token automatically. All protected requests
   inherit `Bearer {{token}}` from the collection.
5. Run any request individually, or use the Collection Runner to exercise the collection.

The seeded manager credential is for local development only. Never deploy it or run the
demonstration seeder in production. If Scribe regenerates the documentation collection,
verify the login script afterward; Scribe owns `public/docs/collection.json`.

> **Note:** Laravel 13 uses the slim application skeleton — API routes, middleware, and
> exception rendering are registered in [`bootstrap/app.php`](bootstrap/app.php), not in
> legacy service providers.

## Guide Roadmap

The guide contains 27 implementation steps. Every link below points to the published article.

**Foundations**

1. [Consistent JSON Responses and HTTP Status Codes](https://akramdev.com/blog/professional-laravel-api-guide#consistent-json-responses-and-http-status-codes)
2. [Testing APIs with Postman](https://akramdev.com/blog/professional-laravel-api-guide#testing-apis-with-postman)
3. [Designing Resource-Oriented URLs](https://akramdev.com/blog/professional-laravel-api-guide#designing-resource-oriented-urls)
4. [Structuring a Versioned API](https://akramdev.com/blog/professional-laravel-api-guide#structuring-a-versioned-api)

**Authentication & Responses**

5. [Token Authentication with Laravel Sanctum](https://akramdev.com/blog/professional-laravel-api-guide#token-authentication-with-laravel-sanctum)
6. [Token Revocation and Secure Logout](https://akramdev.com/blog/professional-laravel-api-guide#token-revocation-and-secure-logout)
7. [Designing Stable Response Payloads](https://akramdev.com/blog/professional-laravel-api-guide#designing-stable-response-payloads)
8. [Conditional Fields and Relationships](https://akramdev.com/blog/professional-laravel-api-guide#conditional-fields-and-relationships)
9. [Optional Relationship Loading](https://akramdev.com/blog/professional-laravel-api-guide#optional-relationship-loading)

**Filtering & Sorting**

10. [Reusable Query Filters](https://akramdev.com/blog/professional-laravel-api-guide#reusable-query-filters)
11. [Nested Resources and Relationship Filters](https://akramdev.com/blog/professional-laravel-api-guide#nested-resources-and-relationship-filters)
12. [Safe Client-Controlled Sorting](https://akramdev.com/blog/professional-laravel-api-guide#safe-client-controlled-sorting)

**Writing Data**

13. [Creating Resources with POST](https://akramdev.com/blog/professional-laravel-api-guide#creating-resources-with-post)
14. [Deleting Resources with DELETE](https://akramdev.com/blog/professional-laravel-api-guide#deleting-resources-with-delete)
15. [Full Resource Replacement with PUT](https://akramdev.com/blog/professional-laravel-api-guide#full-resource-replacement-with-put)
16. [Partial Resource Updates with PATCH](https://akramdev.com/blog/professional-laravel-api-guide#partial-resource-updates-with-patch)

**Authorization**

17. [Resource Authorization with Policies](https://akramdev.com/blog/professional-laravel-api-guide#resource-authorization-with-policies)
18. [Access Control with Token Abilities](https://akramdev.com/blog/professional-laravel-api-guide#access-control-with-token-abilities)
19. [Fine-Grained Field Permissions](https://akramdev.com/blog/professional-laravel-api-guide#fine-grained-field-permissions)
20. [Customer-Owned Order Operations](https://akramdev.com/blog/professional-laravel-api-guide#customer-owned-order-operations)
21. [Secure User Management](https://akramdev.com/blog/professional-laravel-api-guide#secure-user-management)
22. [Applying the Principle of Least Privilege](https://akramdev.com/blog/professional-laravel-api-guide#applying-the-principle-of-least-privilege)

**Errors, Documentation & Verification**

23. [Consistent API Error Handling](https://akramdev.com/blog/professional-laravel-api-guide#consistent-api-error-handling)
24. [Generating API Documentation with Scribe](https://akramdev.com/blog/professional-laravel-api-guide#generating-api-documentation-with-scribe)
25. [Using One Response Format Everywhere](https://akramdev.com/blog/professional-laravel-api-guide#using-one-response-format-everywhere)
26. [Testing the Response Format](https://akramdev.com/blog/professional-laravel-api-guide#testing-the-response-format)
27. [Production Hardening Checklist](https://akramdev.com/blog/professional-laravel-api-guide#production-hardening-checklist)

## Following Along

Implementation checkpoints live on numbered branches. To start from the bare application:

```bash
git branch -a
git switch --track origin/00-initial-setup
```

To inspect the response-format or testing checkpoints:

```bash
git switch 25-standardized-api-responses
git switch 26-automated-api-tests
```

Branches are cumulative: each checkpoint contains the work from the preceding steps. The
production hardening checklist is operational guidance, so it does not add application code.

## Useful Commands

```bash
php artisan serve       # run the development server
php artisan migrate     # run migrations
php artisan test        # run the test suite
composer test           # clear config, then run the test suite
./vendor/bin/pint       # format code (Laravel Pint)
npm run dev             # Vite dev server (front-end assets)
```

## Author

**Akram Ghaleb** — [akramdev.com](https://akramdev.com) · [GitHub](https://github.com/akramghaleb)

## License

Released under the [MIT License](LICENSE).

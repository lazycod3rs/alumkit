# Installation

Install :package_name into a Laravel application with Composer:

```bash
composer require :vendor_slug/:package_slug
```

Publish the package resources with the umbrella tag:

```bash
php artisan vendor:publish --tag=":package_slug"
```

If the package ships migrations, run them after publishing:

```bash
php artisan migrate
```

Update this page with any configuration, environment variables, or install checks that :package_name requires.

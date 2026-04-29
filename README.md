<div align="center">
    <h1>:package_name</h1>
    :package_description
</div>

<p align="center">
    <a href="https://packagist.org/packages/:vendor_slug/:package_slug"><img src="https://img.shields.io/packagist/v/:vendor_slug/:package_slug.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/:vendor_slug/:package_slug"><img src="https://img.shields.io/packagist/php-v/:vendor_slug/:package_slug.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/:vendor_slug/:package_slug"><img src="https://badge.laravel.cloud/badge/:vendor_slug/:package_slug?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/:vendor_slug/:package_slug/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/:vendor_slug/:package_slug/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/:vendor_slug/:package_slug"><img src="https://img.shields.io/packagist/dt/:vendor_slug/:package_slug.svg?style=flat-square" alt="Total Downloads"></a>
</p>

<!--delete-->
---
This repo can be used to scaffold a Laravel package. Follow these steps to get started:

1. Press the "Use this template" button at the top of this repo to create a new repo with the contents of this skeleton.
2. Run `php ./configure.php` to replace every `:token` placeholder across the package.
3. Run `composer test` to confirm the toolchain (PHPStan, Pint, Pest type coverage, Pest) is green.
4. Run `composer build` to (re)build the bundled workbench app under `workbench/`.
5. Run `composer serve` to boot the workbench app at `http://localhost:8000` and test your package end-to-end.
6. Have fun creating your package.

## Manual GitHub Setup

Some GitHub automation needs repository-level settings after you create your package repository:

1. Enable GitHub Pages and set the source to GitHub Actions so `.github/workflows/docs.yml` can deploy the MkDocs site.
2. Enable auto-merge and allow GitHub Actions to create and merge pull requests so Dependabot minor and patch updates can be merged automatically.
3. Create the labels used by generated release notes if you want clean categories: `breaking`, `enhancement`, `bug`, `documentation`, `dependencies`, `maintenance`, `skip-changelog`, and `duplicate`.
4. Review branch protection rules for `main`. The changelog workflow needs GitHub Actions to be allowed to commit `CHANGELOG.md` after a release is published.

No additional repository secrets are required; the workflows use GitHub's built-in `GITHUB_TOKEN`.

## Previewing Documentation Locally

The documentation scaffold uses MkDocs Material. To preview it locally, install MkDocs Material and run the development server:

```bash
pip install mkdocs-material
mkdocs serve
```

Then open `http://127.0.0.1:8000` in your browser.
---
<!--/delete-->

:package_description

## Installation

You can install the package via composer:

```bash
composer require :vendor_slug/:package_slug
```

You can publish all of the package's resources at once using the umbrella tag:

```bash
php artisan vendor:publish --tag=":package_slug"
```

Alternatively, you can publish each resource individually using the tags below.

### Publishing the Configuration File

```bash
php artisan vendor:publish --tag=":package_slug-config"
```

### Publishing and Running the Migrations

```bash
php artisan vendor:publish --tag=":package_slug-migrations"
php artisan migrate
```

### Publishing the Views

```bash
php artisan vendor:publish --tag=":package_slug-views"
```

### Publishing the Translations

```bash
php artisan vendor:publish --tag=":package_slug-lang"
```

### Publishing the Public Assets

```bash
php artisan vendor:publish --tag=":package_slug-assets"
```

## Usage

Document how to use :package_name here.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to :package_name! You can read the contribution guide [here](.github/CONTRIBUTING.md).

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [:author_name](https://github.com/:author_username)
- [All Contributors](../../contributors)

## License

:package_name is open-sourced software licensed under the [MIT license](LICENSE.md).

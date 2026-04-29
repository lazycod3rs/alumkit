# Testing

Run the full :package_name validation suite with Composer:

```bash
composer test
```

During development, you can run individual checks:

```bash
composer analyse
composer lint:check
composer test:types
composer test:unit
```

Use the bundled workbench when :package_slug needs to be exercised inside a real Laravel application:

```bash
composer build
composer serve
```

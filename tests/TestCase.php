<?php

declare(strict_types=1);

namespace Alumkit\Alumkit\Tests;

use Alumkit\Alumkit\AlumkitServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\AliasLoader;
use Laravel\Fortify\FortifyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use TallStackUi\Facades\TallStackUi;
use TallStackUi\TallStackUiServiceProvider;
use Workbench\App\Models\User;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Workbench\\Database\\Factories\\'.class_basename($modelName).'Factory';
        });

        AliasLoader::getInstance()->alias('TallStackUi', TallStackUi::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            TallStackUiServiceProvider::class,
            FortifyServiceProvider::class,
            AlumkitServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode('12345678901234567890123456789012'));
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('alumkit.auth.user_model', User::class);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../workbench/database/migrations');
    }
}

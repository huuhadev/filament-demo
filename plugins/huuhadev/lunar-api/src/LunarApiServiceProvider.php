<?php

namespace Huuhadev\LunarApi;

use Illuminate\Container\Container;
use LaravelJsonApi\Core\JsonApiService;
use LaravelJsonApi\Core\Support\AppResolver;
use Huuhadev\LunarApi\Http\Middleware\LunarApi;
use Illuminate\Contracts\Foundation\Application;
use LaravelJsonApi\Contracts;
use LaravelJsonApi\Core\Server\ServerRepository;
use LaravelJsonApi\Core\Support\ContainerResolver;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LunarApiServiceProvider extends PackageServiceProvider
{
    public static string $name = 'lunar-api';
    public static string $viewNamespace = 'lunar-api';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('huuhadev/lunar-api');
            })
            ->hasRoute('api');

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
        $this->setConfigs();
        $this->bindResolvers();
        $this->bindAuthorizer();
        $this->bindService();
        $this->bindServer();
    }

    public function packageBooted(): void
    {
        $router = app('router');
        $router->aliasMiddleware('lunar-api', LunarApi::class);
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_lunar_api_table',
        ];
    }

    private function setConfigs(): void
    {
        $servers = config('lunar-api.servers');
        if ($servers){
            $this->app['config']->set('jsonapi.servers', $servers);
        }

    }

    /**
     * Bind the Octane-compatible lazy instance resolvers into the service container.
     *
     * @return void
     */
    private function bindResolvers(): void
    {
        $this->app->bind(AppResolver::class, static function () {
            return new AppResolver(static fn() => app());
        });

        $this->app->bind(ContainerResolver::class, static function () {
            return new ContainerResolver(static fn() => Container::getInstance());
        });
    }

    /**
     * Bind the authorizer instance into the service container.
     *
     * @return void
     */
    private function bindAuthorizer(): void
    {
        $this->app->bind(Contracts\Auth\Authorizer::class, static function (Application $app) {
            /** @var Contracts\Routing\Route $route */
            $route = $app->make(Contracts\Routing\Route::class);
            return $route->authorizer();
        });
    }

    /**
     * Bind the JSON API service into the service container.
     *
     * @return void
     */
    private function bindService(): void
    {
        $this->app->bind(JsonApiService::class);
    }

    /**
     * Bind server services into the service container.
     *
     * @return void
     */
    private function bindServer(): void
    {
        $this->app->singleton(Contracts\Server\Repository::class, ServerRepository::class);

        $this->app->bind(Contracts\Store\Store::class, static function (Application $app) {
            return $app->make(Contracts\Server\Server::class)->store();
        });

        $this->app->bind(Contracts\Schema\Container::class, static function (Application $app) {
            return $app->make(Contracts\Server\Server::class)->schemas();
        });

        $this->app->bind(Contracts\Resources\Container::class, static function (Application $app) {
            return $app->make(Contracts\Server\Server::class)->resources();
        });
    }

}

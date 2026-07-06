<?php

declare(strict_types=1);

namespace App;

use App\DTO\TenantRule;
use Closure;
use RuntimeException;

use App\Managers\RuleManager;
use App\Rules\{
    ProhibitedTermsRule,
    MaxSizeRule,
    RequiredMetadataRule};

class Application
{
    /**
     * @var array<string, Closure> List of factories for creating objects
     */
    protected static array $bindings = [];

    /**
     * @var array<string, mixed> Cache of already created singletons
     */
    protected static array $instances = [];

    protected static string $basePath;


    public function __construct(string $basePath)
    {
        self::$basePath = rtrim($basePath, '/');
    }

    public function boot(): self
    {
        Application::singleton('rules', function() {
            $ruleManager = new RuleManager();

            $ruleManager->register('max_size', MaxSizeRule::class);
            $ruleManager->register('required_metadata', RequiredMetadataRule::class);
            $ruleManager->register('prohibited_terms', ProhibitedTermsRule::class);

            return $ruleManager;
        });

        return $this;
    }

    public static function singleton(string $abstract, Closure $callback): void
    {
        self::$bindings[$abstract] = $callback;
        unset(self::$instances[$abstract]);
    }

    /**
     * @template TInstance of mixed
     * @param TInstance $instance
     */
    public static function instance(string $abstract, mixed $instance): void
    {
        self::$instances[$abstract] = $instance;
    }

    public static function resolve(string $abstract): mixed
    {
        // If the object has already been created, we return it from the cache.
        if (isset(self::$instances[$abstract])) {
            return self::$instances[$abstract];
        }

        // If a factory is registered for the key, we call it
        if (isset(self::$bindings[$abstract])) {
            $callback = self::$bindings[$abstract];

            return self::$instances[$abstract] = $callback();
        }

        // Automatic resolution for classes without parameters in the constructor
        if (class_exists($abstract)) {
            return self::$instances[$abstract] = new $abstract;
        }

        throw new RuntimeException("Target [{$abstract}] is not bound in App container.");
    }

    public static function pathTo(string $path): string
    {
        return self::$basePath . DIRECTORY_SEPARATOR . $path;

    }

    public static function flush(): void
    {
        self::$bindings  = [];
        self::$instances = [];
    }
}

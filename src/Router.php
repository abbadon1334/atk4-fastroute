<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute;

use Abbadon1334\ATKFastRoute\Handler\Contracts\iAfterRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iBeforeRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iNeedAppRun;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use Abbadon1334\ATKFastRoute\Route\iRoute;
use Abbadon1334\ATKFastRoute\Route\Route;
use Abbadon1334\ATKFastRoute\View\MethodNotAllowed;
use Abbadon1334\ATKFastRoute\View\NotFound;
use Atk4\Core\AppScopeTrait;
use Atk4\Core\ConfigTrait;
use Atk4\Ui\App;
use Atk4\Ui\Layout;
use Closure;
use function FastRoute\cachedDispatcher;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\RequestInterface;

class Router
{
    use AppScopeTrait {
        setApp as _parentSetApp;
    }
    use ConfigTrait {
        ConfigTrait::setConfig as protected;
        ConfigTrait::getConfig as protected;
        ConfigTrait::_lookupConfigElement as protected;
        ConfigTrait::readConfig as protected _readConfig;
    }

    protected bool $use_cache = false;

    protected string $cache_file;

    /** @var array<iRoute> */
    protected array $route_collection = [];

    protected string $base_dir = '/';

    /**
     * Default View to show when route = not found.
     */
    protected string $_default_not_found = NotFound::class;

    /**
     * Default View to show when route = method not allowed.
     */
    protected string $_default_method_not_allowed = MethodNotAllowed::class;

    public function __construct(App $app)
    {
        $this->setApp($app);
    }

    public function setApp(object $app): void
    {
        $this->_parentSetApp($app);

        // prepare ui\App for pretty urls
        $this->getApp()->setDefaults([
            //'always_run' => false, cannot be changed after _construct
            'url_building_ext' => '',
        ]);

        $this->getApp()->addMethod('getRouter', function () {
            return $this;
        });
        /*
         * Removed
         * Some handler don't need to run the application
         * moved to router run
         * $this->app->addHook('beforeRender', function (): void {
         * $this->handleRouteRequest();
         * });
         */
    }

    public function enableCacheRoutes(string $cache_path): void
    {
        $this->use_cache = true;
        $this->cache_file = $cache_path;
    }

    public function setBaseDir(string $base_dir): void
    {
        $this->base_dir = '/'.trim($base_dir, '/').'/';
    }

    public function addRoute(string $routePattern, array $methods = null, iOnRoute $handler = null): iRoute
    {
        $pattern = $this->buildPattern($routePattern);

        return $this->_addRoute(new Route($pattern, $methods ?? [], $handler));
    }

    protected function buildPattern(string $routePattern): string
    {
        return $this->base_dir.trim($routePattern, '/');
    }

    protected function _addRoute(iRoute $route): iRoute
    {
        $this->route_collection[] = $route;

        return $route;
    }

    public function run(RequestInterface $request = null): void
    {
        foreach ($this->config as $route_array) {
            $this->_addRoute(Route::fromArray($route_array));
        }

        $this->handleRouteRequest($request);
    }

    protected function handleRouteRequest(RequestInterface $request = null): bool
    {
        $dispatcher = $this->getDispatcher();

        $request = $request ?? ServerRequestFactory::fromGlobals();
        $uri_path = $request->getUri()->getPath();

        // for atk4 / and /index are the same
        // for fastroute obviously not.
        if (substr($uri_path, -5) === 'index') {
            $uri_path = substr($uri_path, 0, -5);
        }

        $route = $dispatcher->dispatch($request->getMethod(), $uri_path);
        $status = $route[0];

        if ($status !== Dispatcher::FOUND) {
            $allowed_methods = $route[1] ?? [];
            $this->onRouteFail($request, $status, $allowed_methods);

            $this->getApp()->run();

            return false;
        }

        http_response_code(200);

        /** @var iOnRoute $handler */
        $handler = $route[1];
        $parameters = array_values($route[2]);

        if ($handler instanceof iBeforeRoutable) {
            $handler->OnBeforeRoute($this->getApp(), ...$parameters);
        }

        $handler->onRoute(...$parameters);

        if ($handler instanceof iAfterRoutable) {
            $handler->OnAfterRoute($this->getApp(), ...$parameters);
        }

        if ($handler instanceof iNeedAppRun) {
            $this->getApp()->run();
        }

        return true;
    }

    protected function getDispatcher(): Dispatcher
    {
        $closure = Closure::fromCallable([$this, 'routeCollect']);

        if ($this->use_cache === false) {
            return simpleDispatcher($closure);
        }

        return cachedDispatcher($closure, [
            'cacheFile'     => $this->cache_file,
            'cacheDisabled' => false,
        ]);
    }

    protected function onRouteFail(RequestInterface $request, int $status, array $allowed_methods = []): bool
    {
        if (!isset($this->getApp()->html)) {
            $this->getApp()->initLayout([Layout::class]);
        }

        if ($status === Dispatcher::METHOD_NOT_ALLOWED) {
            return $this->routeMethodNotAllowed($request, $allowed_methods);
        }

        return $this->routeNotFound($request);
    }

    private function routeMethodNotAllowed(RequestInterface $request, array $allowed_methods = []): bool
    {
        http_response_code(405);
        $this->getApp()->add(new $this->_default_method_not_allowed($request, [
            '_allowed_methods' => $allowed_methods,
        ]));

        return false;
    }

    protected function routeNotFound(RequestInterface $request): bool
    {
        http_response_code(404);
        $this->getApp()->add(new $this->_default_not_found($request));

        return false;
    }

    public function loadRoutes(string $file, string $format_type): void
    {
        $this->_readConfig([$file], $format_type);
    }

    protected function routeCollect(RouteCollector $routeCollector): void
    {
        foreach ($this->route_collection as $route) {
            $routeCollector->addRoute(...$route->toArray());
        }
    }
}

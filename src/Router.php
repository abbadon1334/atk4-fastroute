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
use atk4\core\ConfigTrait;
use atk4\ui\App;
use atk4\ui\Exception;
use Closure;
use function FastRoute\cachedDispatcher;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

class Router
{
    use ConfigTrait {
        ConfigTrait::setConfig as protected;
        ConfigTrait::getConfig as protected;
        ConfigTrait::_lookupConfigElement as protected;
        readConfig as _readConfig;
        ConfigTrait::readConfig as protected;
    }

    /**
     * @var bool
     */
    protected $use_cache = false;
    /**
     * @var
     */
    protected $cache_file;

    /** @var iRoute[] */
    protected $route_collection = [];

    /**
     * @var string
     */
    protected $base_dir = '/';

    /**
     * Default View to show when route = not found.
     *
     * @var string
     */
    protected $_default_not_found = NotFound::class;

    /**
     * Default View to show when route = method not allowed.
     *
     * @var string
     */
    protected $_default_method_not_allowed = MethodNotAllowed::class;

    /**
     * Router constructor.
     *
     * @param App $app
     *
     * @throws \atk4\core\Exception
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->setUpApp();
    }

    /**
     * @param $cache_path
     */
    public function enableCacheRoutes($cache_path): void
    {
        $this->use_cache = true;
        $this->cache_file = $cache_path;
    }

    /**
     * @throws \atk4\core\Exception
     */
    protected function setUpApp(): void
    {
        // prepare ui\App for pretty urls
        $this->app->setDefaults([
            'always_run' => false,
            'url_building_ext' => '',
        ]);

        /*
         * Removed
         * Some handler don't need to run the application
         * moved to router run
         * $this->app->addHook('beforeRender', function (): void {
         * $this->handleRouteRequest();
         * });
         */
    }

    /**
     * @param ServerRequestInterface|null $request
     *
     * @throws Exception
     *
     * @return bool
     */
    protected function handleRouteRequest(?ServerRequestInterface $request = null)
    {
        $dispatcher = $this->getDispatcher();

        $request = $request ?? ServerRequestFactory::fromGlobals();

        $route = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
        $status = $route[0];

        if (Dispatcher::FOUND !== $status) {
            $allowed_methods = $route[1] ?? [];
            $this->onRouteFail($request, $status, $allowed_methods);

            return $this->app->run();
        }

        http_response_code(200);

        /** @var iOnRoute $handler */
        $handler = $route[1];
        $parameters = array_values($route[2]);

        if ($handler instanceof iBeforeRoutable) {
            $handler->OnBeforeRoute($this->app, ...$parameters);
        }

        $handler->onRoute(...$parameters);

        if ($handler instanceof iAfterRoutable) {
            $handler->OnAfterRoute($this->app, ...$parameters);
        }

        if ($handler instanceof iNeedAppRun) {
            $this->app->run();
        }

        return true;
    }

    /**
     * @return Dispatcher
     */
    protected function getDispatcher()
    {
        $closure = Closure::fromCallable([$this, 'routeCollect']);

        if (false === $this->use_cache) {
            return simpleDispatcher($closure);
        }

        return cachedDispatcher($closure, [
            'cacheFile'     => $this->cache_file,
            'cacheDisabled' => false,
        ]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param                        $status
     * @param array                  $allowed_methods
     *
     * @return bool
     */
    protected function onRouteFail(ServerRequestInterface $request, $status, array $allowed_methods = []): bool
    {
        if (! isset($this->app->html)) {
            $this->app->initLayout('Generic');
        }

        if (Dispatcher::METHOD_NOT_ALLOWED === $status) {
            return $this->routeMethodNotAllowed($request, $allowed_methods);
        }

        return $this->routeNotFound($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     * @throws Exception
     */
    protected function routeNotFound(ServerRequestInterface $request): bool
    {
        http_response_code(404);
        $this->app->add(new $this->_default_not_found($request));

        return false;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array $allowed_methods
     *
     * @return bool
     * @throws Exception
     */
    private function routeMethodNotAllowed(ServerRequestInterface $request, array $allowed_methods = []): bool
    {
        http_response_code(405);
        $this->app->add(new $this->_default_method_not_allowed($request, [
            '_allowed_methods' => $allowed_methods,
        ]));

        return false;
    }

    /**
     * @param string $base_dir
     */
    public function setBaseDir(string $base_dir): void
    {
        $this->base_dir = '/'.trim($base_dir, '/').'/';
    }

    /**
     * @param string        $routePattern
     * @param array|null    $methods
     * @param iOnRoute|null $handler
     *
     * @return iRoute
     */
    public function addRoute(string $routePattern, ?array $methods = null, ?iOnRoute $handler = null): iRoute
    {
        $pattern = $this->buildPattern($routePattern);

        return $this->_addRoute(new Route($pattern, $methods ?? [], $handler));
    }

    /**
     * @param iRoute $route
     *
     * @return iRoute
     */
    protected function _addRoute(iRoute $route) : iRoute
    {
        $this->route_collection[] = $route;

        return $route;
    }

    /**
     * @param $routePattern
     *
     * @return string
     */
    protected function buildPattern($routePattern)
    {
        return $this->base_dir.trim($routePattern, '/');
    }

    /**
     * @param RouteCollector $routeCollector
     */
    protected function routeCollect(RouteCollector $routeCollector): void
    {
        foreach ($this->route_collection as $route) {
            $routeCollector->addRoute(...$route->toArray());
        }
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $this->handleRouteRequest();
    }

    /**
     * @param $file
     * @param $format_type
     *
     * @throws \atk4\core\Exception
     */
    public function loadRoutes($file, $format_type)
    {
        $this->_readConfig([$file], $format_type);

        foreach ($this->config as $route_array) {
            $this->_addRoute(Route::fromArray($route_array));
        }
    }
}

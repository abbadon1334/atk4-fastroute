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
use Atk4\Core\ConfigTrait;
use Atk4\Ui\App;
use Atk4\Ui\Exception;
use Atk4\Ui\Layout;
use Closure;
use function FastRoute\cachedDispatcher;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use Zend\Diactoros\ServerRequestFactory;

class Router
{
    use ConfigTrait {
        ConfigTrait::setConfig as protected;
        ConfigTrait::getConfig as protected;
        ConfigTrait::_lookupConfigElement as protected;
        ConfigTrait::readConfig as protected _readConfig;
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
     * @var App
     */
    protected $app;

    /**
     * Router constructor.
     *
     * @throws \Atk4\Core\Exception
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->setUpApp();
    }

    protected function setUpApp(): void
    {
        // prepare ui\App for pretty urls
        $this->app->setDefaults([
            //'always_run' => false, cannot be changed after _construct
            'url_building_ext' => '',
        ]);

        $this->app->addMethod('getRouter', function () {
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

    /**
     * @param $cache_path
     */
    public function enableCacheRoutes($cache_path): void
    {
        $this->use_cache  = true;
        $this->cache_file = $cache_path;
    }

    public function setBaseDir(string $base_dir): void
    {
        $this->base_dir = '/'.trim($base_dir, '/').'/';
    }

    public function addRoute(string $routePattern, ?array $methods = null, ?iOnRoute $handler = null): iRoute
    {
        $pattern = $this->buildPattern($routePattern);

        return $this->_addRoute(new Route($pattern, $methods ?? [], $handler));
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

    protected function _addRoute(iRoute $route): iRoute
    {
        $this->route_collection[] = $route;

        return $route;
    }

    /**
     * @throws Exception
     * @throws \Atk4\Core\Exception
     * @throws ReflectionException
     */
    public function run(?ServerRequestInterface $request = null): void
    {
        foreach ($this->config as $route_array) {
            $this->_addRoute(Route::fromArray($route_array));
        }

        $this->handleRouteRequest($request);
    }

    /**
     * @throws Exception
     * @throws \Atk4\Core\Exception
     *
     * @return bool
     */
    protected function handleRouteRequest(?ServerRequestInterface $request = null)
    {
        $dispatcher = $this->getDispatcher();

        $request  = $request ?? ServerRequestFactory::fromGlobals();
        $uri_path = $request->getUri()->getPath();

        // for atk4 / and /index are the same
        // for fastroute obviously not.
        if ('index' === substr($uri_path, -5)) {
            $uri_path = substr($uri_path, 0, -5);
        }

        $route  = $dispatcher->dispatch($request->getMethod(), $uri_path);
        $status = $route[0];

        if (Dispatcher::FOUND !== $status) {
            $allowed_methods = $route[1] ?? [];
            $this->onRouteFail($request, $status, $allowed_methods);

            $this->app->run();

            return false;
        }

        http_response_code(200);

        /** @var iOnRoute $handler */
        $handler    = $route[1];
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
     * @param $status
     *
     * @throws Exception
     * @throws \Atk4\Core\Exception
     */
    protected function onRouteFail(ServerRequestInterface $request, $status, array $allowed_methods = []): bool
    {
        if (!isset($this->app->html)) {
            $this->app->initLayout([Layout::class]);
        }

        if (Dispatcher::METHOD_NOT_ALLOWED === $status) {
            return $this->routeMethodNotAllowed($request, $allowed_methods);
        }

        return $this->routeNotFound($request);
    }

    /**
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
     * @throws Exception
     */
    protected function routeNotFound(ServerRequestInterface $request): bool
    {
        http_response_code(404);
        $this->app->add(new $this->_default_not_found($request));

        return false;
    }

    /**
     * @param $file
     * @param $format_type
     *
     * @throws \Atk4\Core\Exception
     */
    public function loadRoutes($file, $format_type): void
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

<?php declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute;

use Abbadon1334\ATKFastRoute\Handler\iHandler;
use Abbadon1334\ATKFastRoute\Route\iRoute;
use Abbadon1334\ATKFastRoute\Route\Route;
use atk4\core\AppScopeTrait;
use atk4\core\InitializerTrait;
use atk4\ui\Exception;
use atk4\ui\jsExpressionable;
use Closure;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;
use function FastRoute\cachedDispatcher;
use function FastRoute\simpleDispatcher;

class Router
{
    use AppScopeTrait;
    use InitializerTrait {
        init as _init;
    }

    public $use_cache      = false;
    public $cache_disabled = false;
    public $cache_file;

    /** @var iRoute[] */
    protected $route_collection = [];

    protected $base_dir = '/';

    /**
     * @throws \atk4\core\Exception
     */
    public function init(): void
    {
        $this->_init();

        $this->setUpApp();
    }

    /**
     * @throws \atk4\core\Exception
     */
    protected function setUpApp(): void
    {
        // prepare ui\App for pretty urls
        $this->app->setDefaults(['url_building_ext' => '']);

        // catch beforeRender to output request
        $this->app->addHook('beforeRender', function (): void {
            $this->handleRouteRequest();
        });
    }

    /**
     * @param ServerRequestInterface|null $request
     *
     * @return bool
     * @throws Exception
     */
    protected function handleRouteRequest(?ServerRequestInterface $request = NULL)
    {
        $dispatcher = $this->getDispatcher();

        $request = $request ?? ServerRequestFactory::fromGlobals();

        $route  = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
        $status = (int)$route[0];

        if (Dispatcher::FOUND !== $status) {
            $this->onRouteFail($request, $status);

            return false;
        }

        /** @var iHandler $handler */
        $handler    = $route[1];
        $parameters = $route[2];

        $result = $handler->onRoute(...$parameters);

        if ($result instanceof jsExpressionable) {
            $this->app->add($result);
        }

        return true;
    }

    protected function getDispatcher()
    {
        $closure = Closure::fromCallable([$this, 'routeCollect']);

        if (false === $this->use_cache) {
            return simpleDispatcher($closure);
        }

        return cachedDispatcher($closure, [
            'cacheFile'     => $this->cache_file,
            'cacheDisabled' => $this->cache_disabled,
        ]);
    }

    protected function onRouteFail(?ServerRequestInterface $request, $status): bool
    {
        if (Dispatcher::METHOD_NOT_ALLOWED === $status) {
            return $this->routeMethodNotAllowed();
        }

        return $this->routeNotFound();
    }

    protected function routeNotFound(): bool
    {
        return false;
    }

    private function routeMethodNotAllowed(): bool
    {
        return false;
    }

    /**
     * @param string $base_dir
     */
    public function setBaseDir(string $base_dir): void
    {
        $this->base_dir = "/" . trim($base_dir, '/');
    }

    public function addRoute(array $methods, string $routePattern, iHandler $handler): void
    {
        $this->route_collection[] = new Route($this->buildPattern($routePattern), $methods, $handler);
    }

    protected function buildPattern($routePattern)
    {
        return $this->base_dir . trim($routePattern, '/');
    }

    protected function routeCollect(RouteCollector $rc): void
    {
        foreach ($this->route_collection as $r) {
            $rc->addRoute(...$r->toArray());
        }
    }
}

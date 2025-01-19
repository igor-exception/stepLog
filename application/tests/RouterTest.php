<?php

use PHPUnit\Framework\TestCase;
use APP\Router;


class RouterTest extends TestCase
{
    private $routes;

    public function setUp(): void
    {
        $this->routes = [
            'GET' => [
                '/' => [
                    'controller'    => 'APP\InitController',
                    'action'        => 'index',
                    'dependencies'  => []
                ],
                '/user/create' => [
                    'controller'    => 'APP\UserController',
                    'action'        => 'create',
                    'dependencies'  => [UserService::class]
                ],
                '/users' => [
                    'controller'    => 'APP\UserController',
                    'action'        => 'index',
                    'dependencies'  => [UserService::class]
                ],
                '/users/:id' => [
                    'controller'    => 'APP\UserController',
                    'action'        => 'show',
                    'dependencies'  => [UserService::class]
                ],
                '/users/:id/edit' => [
                    'controller'    => 'APP\UserController',
                    'action'        => 'edit',
                    'dependencies'  => [UserService::class]
                ]
            ],
            'POST' => [
                '/user/create' => [
                    'controller'    => 'APP\UserController',
                    'action'        => 'store',
                    'dependencies'  => [UserService::class]
                ]
            ]
        ];
    }

    public function testSimpleValidEndPoint()
    {
        $simpleRoute = "/users";
        $method = 'GET';
        $router = new Router($this->routes);
        $resolvedRoute = $router->resolveURL($simpleRoute, $method);
        $allEndPoints = $router->getRoutes();
        $this->assertEquals('index', $allEndPoints[$method][$resolvedRoute]['action']);
    }

    public function testRequestWithMissmatchResourcesLength()
    {
        $simpleRoute = "/users/too/long";
        $method = 'GET';
        $router = new Router($this->routes);
        $resolvedRoute = $router->resolveURL($simpleRoute, $method);
        $this->assertSame(false, $resolvedRoute);
    }

    public function testSimpleInvalidEndPoint()
    {
        $simpleRoute = "/invalid";
        $method = 'GET';
        $router = new Router($this->routes);
        $resolvedRoute = $router->resolveURL($simpleRoute, $method);

        $this->assertSame(false, $resolvedRoute);
    }

    public function testEndPointWithDynamicParam()
    {
        $simpleRoute = "/users/1";
        $method = 'GET';
        $router = new Router($this->routes);
        $resolvedRoute = $router->resolveURL($simpleRoute, $method);
        $allEndPoints = $router->getRoutes();
        $this->assertEquals('show', $allEndPoints[$method][$resolvedRoute]['action']);
    }

    public function testEndPointWithDynamicParamBetweenResources()
    {
        $simpleRoute = "/users/1/edit";
        $method = 'GET';
        $router = new Router($this->routes);
        $resolvedRoute = $router->resolveURL($simpleRoute, $method);
        $allEndPoints = $router->getRoutes();
        $this->assertEquals('edit', $allEndPoints[$method][$resolvedRoute]['action']);
    }

    public function testEndPointWithDynamicInvalidParamBetweenResources()
    {
        $simpleRoute = "/users/1/invalid";
        $method = 'GET';
        $router = new Router($this->routes);
        $resolvedRoute = $router->resolveURL($simpleRoute, $method);

        $this->assertEquals(false, $resolvedRoute);
    }

}
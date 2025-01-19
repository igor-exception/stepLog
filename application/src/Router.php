<?php

namespace APP;
use APP\UserService;
use APP\UserController;
use APP\UserRepository;

class Router
{
    private $endPoints;
    private $dynamicParams;

    public function __construct(array $endPoints)
    {
        $this->endPoints = $endPoints;
    }

    public function getRoutes(): array
    {
        return $this->endPoints;
    }

    public function resolveURL($request, $method)
    {
        $allRoutes = $this->endPoints;
        $endPoints = $allRoutes[$method];
        if(isset($endPoints[$request])){
            return $request;
        }
        $foundedMatch = false;
        $splittedRequest = $this->splitURL($request);
        foreach($endPoints as $url=>$content) {
            $splittedURL = $this->splitURL($url);
            // se tamanho for diferente, ja descarta
            if(count($splittedRequest) != count($splittedURL)) {
                continue;
            }
            
            foreach($splittedURL as $key=>$partsOfUrl) {
                if($splittedRequest[$key] != $partsOfUrl) {
                    
                    if($this->hasDynamicParam($partsOfUrl)){
                        $foundedMatch = true;
                        $this->dynamicParams[] = $splittedRequest[$key];
                    }else{
                        $foundedMatch = false;
                    }
                }
                if($partsOfUrl == end($splittedURL)){
                    if($foundedMatch) {
                        return $url;
                    }
                }
            }
        }

        return false;
    }

    private function splitURL($url)
    {
        return explode('/', $url);
    }

    private function hasDynamicParam($str)
    {
        return strpos($str, ':') !== false;
    }

    private function splitDynamicParams($param)
    {
        if($this->hasDynamicParam($param)){
            return substr($param, 1);
        }
        return false;
    }

    public function getDynamicParams()
    {
        return $this->dynamicParams;
    }

    public function resolveDependencies(string $dependencyName): object
    {
        if($dependencyName == UserController::class) {
            $pdo = new \PDO('mysql:host=steplogdb;dbname=steplog;charset=utf8mb4', 'root', 'passwd');
            $repository = new UserRepository($pdo);
            $service = new UserService($repository);
            return new UserController($service);
        }elseif($dependencyName == UserService::class) {
            $pdo = new \PDO('mysql:host=steplogdb;dbname=steplog;charset=utf8mb4', 'root', 'passwd');
            $repository = new UserRepository($pdo);
            return new UserService($repository);
        }elseif($dependencyName == UserRepository::class) {
            $pdo = new \PDO('mysql:host=steplogdb;dbname=steplog;charset=utf8mb4', 'root', 'passwd');
            return new UserRepository($pdo);
        }
    }
}
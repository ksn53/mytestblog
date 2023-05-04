<?php
namespace App;

class Route
{
    private $method;
    private $path;
    private $callback;
    private $data;

    public function __construct($method, $path, $callback, $data)
    {
        $this->method = $method;
        $this->path = $this->removeSlashes($path);
        $this->callback = 'App\\' . $callback;
        $this->data = $data;
    }

    public function removeSlashes($string)
    {
        return preg_replace(["#^/#", "#/$#"], "", $string);
    }

    public function parseUri($uri)
    {
        $urlParts = explode('/', $this->removeSlashes($uri));
        $routeParts = explode('/', $this->getPath());
        $params = [];
        foreach ($routeParts as $key => $part) {
            if ($part === '*') {
                $params[] = $urlParts[$key];
            }
        }
        return $params;
    }

    private function prepareCallback($uri)
    {
        $controller = $this->callback;
        $dataToView = ['maindata' => $this->data, 'uridata' => $this->parseUri($uri)];
        return new $controller($this->getMainPath(), $dataToView);
    }

    public function getMainPath()
    {
        return explode('/', $this->path)[0];
    }
    public function getPath()
    {
        return $this->path;
    }
    public function matchUri($uri)
    {
        $pattern = '/^' . str_replace(['*', '/'], ['\w+', '\/'], $this->getPath()) . '$/';
        $uri = $this->removeSlashes($uri);
        if ($uri == "") {
            $uri = "main";
        }
        return preg_match($pattern, $uri);
    }

    public function match($method, $uri) : bool
    {
        if ((strcasecmp($this->method, $method) == 0) && ($this->matchUri($uri) == 1)) {
            return true;
        }
        return false;
    }

    public function run($uri)
    {
        $callback = $this->prepareCallback($uri);
        if (isset($callback->view)) {
            return $callback->view;
        } else {
            return $callback;
        }

    }
}

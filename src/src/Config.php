<?php
namespace App;

final class Config
{
    private static $_instance = null;
    private $configs = array();

    private function __construct() {
        $configFileName = 'db';
        $this->configs  = include($_SERVER['DOCUMENT_ROOT'] . '/configs/' . $configFileName . '.php');
    }

    protected function __clone() {

    }

    public function get($config, $default = null)
    {
        return arrayGet($this->configs, $config, $default);
    }

    public static function getInstance()
    {
        if (self::$_instance != null) {
            return self::$_instance;
        }
        return new self;
    }
}
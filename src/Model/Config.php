<?php
/**
 * Created by Michael A. Sivolobov
 * Date: 19.06.13
 */

/**
 * Реализация паттерна Singleton для хранения конфига
 * Class Config
 */
class Config
{
    /**
     * @var Config
     */
    protected static $_instance;

    protected $config;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return SingletonTest
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function loadFromFile($filename)
    {
        if (is_readable($filename)) {
            $this->config = require_once($filename);
        } else {
            throw new Exception('Невозможно прочитать файл ' . $filename);
        }
    }

    public function get($key, $default = null)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        } else {
            return $default;
        }
    }

    public function set($key, $value) {
        $this->config[$key] = $value;
    }
}
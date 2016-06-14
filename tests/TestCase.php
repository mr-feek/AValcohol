<?php

class TestCase extends Laravel\Lumen\Testing\TestCase
{

    protected $utils;
    protected $token;
    protected $authHeader = [];

    public function __construct()
    {
        $this->utils = new Utils();
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}

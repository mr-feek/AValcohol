<?php

use App\Models\User;

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    protected $user;
    protected $utils;
    protected $token;
    protected $authHeader = [];

    public function __construct()
    {
        $this->utils = new Utils();
    }

    public function prepareRequestsWithAdminPrivileges() {
        $this->user = User::whereHas('roles', function($query) {
            $query->where('role_id', 1);
        })->first();

        $this->token = $this->utils->generateTokenForUser($this->user);
        $this->authHeader = ['Authorization' => 'Bearer ' . $this->token];
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

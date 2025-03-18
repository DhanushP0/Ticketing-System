<?php

namespace App\Controllers;

class Test extends BaseController
{
    public function index()
    {
        session()->set('test_key', 'Session is working!');
        return "Session value set. Now visit /test/check";
    }

    public function check()
    {
        return session('test_key') ?? 'Session is NOT working!';
    }
}

<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('all_view'); // This loads the All View page
    }
}
?>
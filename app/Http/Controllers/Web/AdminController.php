<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin-tenant/dashboard');
    }

    // app/Http/Controllers/Web/AdminController.php

    public function grupos()
    {
        return view('admin-tenant/grupo-restaurant');
    }

    public function restaurants()
    {
        return view('admin-tenant/restaurants');
    }

}

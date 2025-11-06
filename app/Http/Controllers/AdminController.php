<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // You can later add logic here to fetch users, stats, etc.
        return view('dashboard');
    }
}

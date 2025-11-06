<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelloController extends Controller
{
    public function index()
    {
        $name = "Raphael";
        return view('hello', ['name' => $name]);
    }

     public function greet($name)
    {
        $name = ucfirst($name); // Capitalize first letter
        return view('hello', ['name' => $name]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Route;

class IndexController extends Controller
{
    public function index(){
        return view("index");
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keuzedeel;

class DashboardController extends Controller
{
    public function index(){
            
    
       return view('admin.dashboard');
    }

    public function keuzedeel(){
        $keuzedeel = Keuzedeel::all();

      return view('admin.keuzedeel' ,['keuzedeels'=> $keuzedeel]);
    }

    
}

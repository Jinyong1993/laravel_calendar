<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class StarController extends BaseController
{
    public function index()
    {
        return view('star.index');
    }
    
    
    public function sun()
    {
        return view('star.sun');
    }
    
    
    public function moon()
    {
        return view('star.moon');
    }
}

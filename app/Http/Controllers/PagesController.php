<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Employee;

class PagesController extends Controller
{
    public function index ()
    {
        return view('index');
    }
}

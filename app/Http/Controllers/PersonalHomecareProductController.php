<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PersonalHomecareProductController extends Controller
{
    public function index()
    {
        return view("admin.list_homecareproduct");
    }
}

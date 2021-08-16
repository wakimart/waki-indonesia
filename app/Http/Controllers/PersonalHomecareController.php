<?php

namespace App\Http\Controllers;

use App\PersonalHomecare;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalHomecareController extends Controller
{
    public function index()
    {
        $personalhomecares = PersonalHomecare::where('active', true)
            ->orderBy('code', 'asc')
            ->paginate(10);

        return view("admin.list_all_personalhomecare", compact("personalhomecares"))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
}

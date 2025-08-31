<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

class DashboardGuruController extends Controller
{
    public function index()
    {
        return view('guru.dashboard');
    }
}

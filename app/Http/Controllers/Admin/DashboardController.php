<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Pastikan view 'admin.dashboard' ada
        return view('admin.dashboard');
    }
}
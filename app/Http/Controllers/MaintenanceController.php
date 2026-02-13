<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    // 1. TAMPILKAN HALAMAN KALENDER
    public function index()
    {
        return view('it_maintenance');
    }

}
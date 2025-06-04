<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DriverTask;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $onProgress = DriverTask::where('status', 'on_progress')->count();
        $finished = DriverTask::where('status', 'finish')->count();
        $canceled = DriverTask::where('status', 'not_started')->count();
        return view('pages.dashboard.index', compact('onProgress', 'finished', 'canceled'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function logs()
    {
        $logs = Log::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.logs', compact('logs'));
    }
}

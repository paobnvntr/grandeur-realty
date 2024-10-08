<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Property;
use App\Models\ListWithUs;
use App\Models\ContactUs;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;

        $thisYearSoldData = Property::whereYear('date_sold', $currentYear)
            ->selectRaw('MONTH(date_sold) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $lastYearSoldData = Property::whereYear('date_sold', $lastYear)
            ->selectRaw('MONTH(date_sold) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $thisYearSoldData = array_replace(array_fill(1, 12, 0), $thisYearSoldData);
        $lastYearSoldData = array_replace(array_fill(1, 12, 0), $lastYearSoldData);

        $thisYearSoldData = array_values($thisYearSoldData);
        $lastYearSoldData = array_values($lastYearSoldData);

        $listWithUsRequests = ListWithUs::count();
        $availableProperties = Property::where('status', 'available')->count();
        $soldProperties = Property::where('status', 'sold')->count();

        $totalForCalculation = $listWithUsRequests + $availableProperties + $soldProperties;

        if ($totalForCalculation > 0) {
            $listWithUsRequests = ($listWithUsRequests / $totalForCalculation) * 100;
            $availableProperties = ($availableProperties / $totalForCalculation) * 100;
            $soldProperties = ($soldProperties / $totalForCalculation) * 100;
        } else {
            $listWithUsRequests = 0;
            $availableProperties = 0;
            $soldProperties = 0;
        }

        $contactUsMessages = ContactUs::count();
        $logs = Log::orderBy('created_at', 'DESC')->limit(5)->get();

        return view('admin.dashboard', compact('logs', 'listWithUsRequests', 'availableProperties', 'soldProperties', 'contactUsMessages', 'lastYearSoldData', 'thisYearSoldData'));
    }
}

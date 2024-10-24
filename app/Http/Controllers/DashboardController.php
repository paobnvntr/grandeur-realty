<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Property;
use App\Models\ListWithUs;
use App\Models\ContactUs;
use App\Models\ListingAnalytics;
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
            $listWithUsRequestsPercentage = ($listWithUsRequests / $totalForCalculation) * 100;
            $availablePropertiesPercentage = ($availableProperties / $totalForCalculation) * 100;
            $soldPropertiesPercentage = ($soldProperties / $totalForCalculation) * 100;
        } else {
            $listWithUsRequestsPercentage = 0;
            $availablePropertiesPercentage = 0;
            $soldPropertiesPercentage = 0;
        }

        $contactUsMessages = ContactUs::count();

        $logs = Log::orderBy('created_at', 'DESC')->limit(5)->get();

        $totalViews = ListingAnalytics::sum('views');
        $totalInteractions = ListingAnalytics::sum('interactions');

        // Calculate total
        $total = $totalViews + $totalInteractions;

        // Calculate percentages
        $percentageViews = $total > 0 ? ($totalViews / $total) * 100 : 0;
        $percentageInteractions = $total > 0 ? ($totalInteractions / $total) * 100 : 0;

        // Round percentages to avoid decimal values if necessary
        $percentageViews = round($percentageViews, 2);
        $percentageInteractions = round($percentageInteractions, 2);

        // Ensure total percentage equals 100
        $percentageTotal = $percentageViews + $percentageInteractions;
        if ($percentageTotal < 100) {
            // Adjust one of the percentages to make total exactly 100
            $percentageViews += (100 - $percentageTotal);
        }

        return view('admin.dashboard', compact('logs', 'listWithUsRequests', 'availableProperties', 'soldProperties', 'contactUsMessages', 'lastYearSoldData', 'thisYearSoldData', 'percentageViews', 'percentageInteractions', 'totalViews', 'totalInteractions', 'listWithUsRequestsPercentage', 'availablePropertiesPercentage', 'soldPropertiesPercentage'));
    }
}

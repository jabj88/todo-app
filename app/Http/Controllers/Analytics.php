<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class Analytics extends Controller
{
    public function index(Request $request)
    {

        try {
            $request->validate([
                'start' => 'required|date|date_format:Y-m-d',
                'end' => 'required|date|date_format:Y-m-d',
            ]);

            $startDate = $request->get('start');
            $endDate = $request->get('end');

            return view(
                'livewire.analytics',
                [
                    'startDate' => $startDate,
                    'endDate' => $endDate
                ]
            );
        } catch (Exception $e) {
            //When parameters are invalid redirect with default date range.
            $startDate = Carbon::now()->subDays(7)->format('Y-m-d');
            $endDate =  Carbon::now()->format('Y-m-d');
            $link = "analytics?start={$startDate}&end={$endDate}";
            return redirect($link);
        }
    }
}

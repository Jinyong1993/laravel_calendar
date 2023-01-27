<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class CalendarController extends BaseController
{
    public function index(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('n');

        if(isset($request->year) || isset($request->month)){
            $date_validator = Validator::make($request->all(),[
                'year' => 'numeric|integer|between:1,9999',
                'month' => 'numeric|integer|between:1,12',
            ]);
            
            if($date_validator->fails()){
                return redirect()->route('calendar.index')->withErrors($date_validator);
            }
        }


        $data = array(
            'year' => $year,
            'month' => $month,
        );

        return view('calendar.calendar_view', $data);
    }

    public function update(Request $request)
    {
        var_dump($request->kkkkday);
    }

    private function date_validate($year, $month){

    }
}

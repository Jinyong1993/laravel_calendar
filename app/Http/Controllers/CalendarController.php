<?php

namespace App\Http\Controllers;

use App\Models\Event;
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

        $select_query = $this->select($year, $month);

        $data = array(
            'year' => $year,
            'month' => $month,
            'select_query' => $select_query,
        );

        return view('calendar.calendar_view', $data);
    }

    public function select($year, $month)
    {       
        $query = array();

        // フォメット　01,02...12
        $month = sprintf('%02d', $month);

        $first_date = "$year-$month-01";
        $time_stamp = strtotime($first_date);
        $last_date = date('Y-m-t', $time_stamp);

        $selects = Event::where('user_id', auth()->user()->id)
                        ->where('date_from', '<=', $last_date)
                        ->where('date_to', '>=', $first_date)
                        ->get();
        
        foreach($selects as $select){
            if($select->date_from <= $first_date){
                $from = $first_date; // 1
            } else {
                $from = $select->date_from;
            }

            if($select->date_to <= $last_date){
                $to = $select->date_to;
            } else {
                $to = $last_date;
            }

            $from = date('d', strtotime($from));
            $to = date('d', strtotime($to));

            // 配列生成
            $day_range = range($from, $to);

            foreach($day_range as $day){
                $query[$day][] = $select;
            }
        }
        return $query;
    }

    public function update(Request $request)
    {
        $event = new Event();

        $event->user_id = auth()->user()->id;
        $event->date_from = $request->date_from;
        $event->date_to = $request->date_to;
        $event->title = $request->title;
        $event->text = $request->text;
        $event->tag_id = $request->tag_id ?? 0;

        $event->save();

        $response = array(
            'success' => true
        );

        return json_encode($response);
    }
}

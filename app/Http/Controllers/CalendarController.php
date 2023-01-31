<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Tag;
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
        $tag_query = $this->color_select($request);

        $data = array(
            'year' => $year,
            'month' => $month,
            'select_query' => $select_query,
            'tag_query' => $tag_query,
        );

        return view('calendar.calendar_view', $data);
    }

    private function select($year, $month)
    {       
        $query = array();

        // フォメット　01,02...12
        $month = sprintf('%02d', $month);

        $first_date = "$year-$month-01";
        $time_stamp = strtotime($first_date);
        $last_date = date('Y-m-t', $time_stamp);

        $selects = Event::where('event.user_id', auth()->user()->id)
                        ->where('event.date_from', '<=', $last_date)
                        ->where('event.date_to', '>=', $first_date)
                        ->leftjoin('tag', 'tag.tag_id', '=', 'event.tag_id')
                        ->select('event.*', 'tag.tag_id', 'tag.tag_color')
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
        $validator = Validator::make($request->all(),[
            'date_from' => 'required',
            'date_to' => 'required',
            'title' => 'required',
        ]);

        if($validator->fails()){
            $error = $validator->errors();
            $response = array(
                'error' => implode('', $error->all()),
            );
            return $response;
        }

        if($request->event_id){
            $event = Event::find($request->event_id); // update
        } else {
            $event = new Event(); // insert
        }

        $event->user_id = auth()->user()->id;
        $event->date_from = $request->date_from;
        $event->date_to = $request->date_to;
        $event->title = $request->title;
        $event->text = $request->text;
        $event->tag_id = $request->tag_id;

        $event->save();

        $response = array(
            'success' => true
        );

        return json_encode($response);
    }

    public function select_ajax(Request $request)
    {
        $event = Event::find($request->event_id);

        return json_encode($event);
    }

    public function delete_ajax(Request $request)
    {
        $event = Event::find($request->event_id); // primary key

        $event->delete();

        $response = array(
            'success' => true
        );

        return json_encode($response);
    }

    public function search_ajax(Request $request)
    {
        $keyword = $request->input('text');
        $tag_id = $request->input('tag_id');

        $search = Event::where('event.user_id', auth()->user()->id)
                ->leftjoin('tag', 'tag.tag_id', '=', 'event.tag_id')
                ->select('event.*', 'tag.tag_id','tag.tag_name' ,'tag.tag_color');

        if(!empty($tag_id)){
            $search->where('tag.tag_id', $tag_id);
        }
        if(!empty($keyword)){
            $search->where('event.text', 'like', "%{$keyword}%");
        }
        $search_result = $search->get();
        
        return json_encode($search_result);
    }

    public function color_update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'tag_name' => 'required',
            'tag_color' => 'required',
        ]);

        if($validator->fails()){
            $error = $validator->errors();
            $response = array(
                'error' => implode('', $error->all()),
            );
            return $response;
        }

        if($request->tag_id){
            $tag = Tag::find($request->tag_id);
        } else {
            $tag = new Tag();
        }

        $tag->user_id = auth()->user()->id;
        $tag->tag_name = $request->tag_name;
        $tag->tag_note = $request->tag_note;
        $tag->tag_color = $request->tag_color;

        $tag->save();

        $response = array(
            'success' => true,
        );

        return json_encode($response);
    }

    public function color_select(Request $request)
    {
        $select = Tag::where('user_id', auth()->user()->id)
                    ->get();
        return $select;
    }

    public function color_select_ajax(Request $request)
    {
        $tag = Tag::find($request->tag_id);

        return json_encode($tag);
    }

    public function color_delete_ajax(Request $request)
    {
        $tag = Tag::find($request->tag_id);

        $tag->delete();

        $response = array(
            'success' => true
        );

        return json_encode($response);
    }
}

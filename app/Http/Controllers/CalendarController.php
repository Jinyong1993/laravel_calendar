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

        function twoSum($nums, $target) {
            $n = count($nums);
            for($i=0; $i<=$n; $i++){
                for($j=$i+1; $j<=$n; $j++){
                    $num = $nums[$i] + $nums[$j];
                    if($num == $target){
                        return array($i,$j);
                    }
                }
            }
        
        }
        
        function isPalindrome($x) {
            $x_str = $x;
            $rev = strrev($x);
            if($x_str == $rev){
                return true;
            } else {
                return false;
            }
        }
        
        function romanToInt($s) {
            $roman_arr = array(
                'I' => 1, 
                'V' => 5,
                'X' => 10,
                'L' => 50,
                'C' => 100,
                'D' => 500,
                'M' => 1000,
            );
            $str_arr = str_split($s);
            $sum = 0;
            foreach($str_arr as $str => $val){
                $next_val = $str_arr[$str+1];
                
                if($roman_arr[$val] < $roman_arr[$next_val]){
                    $sum -= $roman_arr[$val];
                } else {
                    $sum += $roman_arr[$val];
                }
            }
            return $sum;
        }
        
        function removeElement(&$nums, $val) {
            foreach($nums as $num => $value) {
                if($val == $value) {
                    unset($nums[$num]);
                }
            }
        }
        
        function moveZeroes(&$nums) {
            $zero_locations = array_keys($nums, 0);
        
            foreach ($zero_locations as $location_index) {
                unset($nums[$location_index]);
                $nums[] = 0;
            }
            return $nums;
        }
        
        function lengthOfLastWord($s) {
            $words = explode(' ', trim($s));
            var_dump($words);
            return strlen(end($words));
        }
        $s = "Hello World  ";
        $s1 = "   fly me   to  the    moo   n   ";
        
        function searchInsert($nums, $target) {
            for($i = 0; $i < count($nums); $i++){
                if($nums[$i] == $target){
                    return $i;
                }
                if($nums[$i] > $target){
                    return $i;
                }
            }
            return $i;
        }
        
        function addToArrayForm($num, $k) {
            $num = array_reverse($num); // [4,7,2]
            $k = array_reverse(str_split($k)); // [8,1]
        
            if(count($num) < count($k)) { // 3 < 2
                $tmp = $num; // [4,7,2]
                $num = $k; // [8,1]
                $k = $tmp; // [4,7,2]
            }
        
            $carry = false;
            foreach($num as $i => $n) {
        
                if(isset($k[$i]) || $carry) {
                    if(isset($k[$i])) {
                        $num[$i] = $n + $k[$i]; // [12] => [2], [8]
                    }
                    if($carry) {
                        $num[$i]++; // [8] => [9]
                        $carry = false; // false
        
                    }
                    if($num[$i] >= 10) {
                        $num[$i] -= 10; // [2]
                        $carry = true;
                    }
                } else {
                    break;
                }
            }
            if($carry) {
                $num[] = 1;
            }
            return array_reverse($num);
        }
        // $num = [2,7,4]; 
        // $k = 181;
        
        function plusOne($digits) {
            $digits = array_reverse($digits);
            $digits[0]++;
            $carry = false;
            foreach($digits as $i => $digit){
                if($carry){
                    $digits[$i]++;
                    $carry = false;
                }
                if($digits[$i] >= 10){
                    $digits[$i] -= 10;
                    $carry = true;
                } else {
                    break;
                }
            }
            if($carry){
                $digits[] = 1;
            }
            return array_reverse($digits);
        }
        

        function majorityElement1($nums) {
            $numsValues = array_count_values($nums);
            $maxVal = max($numsValues);
            return array_search($maxVal, $numsValues);
        }

        function majorityElement2($nums) {
            $array = array_count_values($nums); // 1=>3, 2=>2, 3=>6
            $prevVal = 0;
            $prevKey = '';
            foreach ($array as $key => $value) { // 1=>3, 2=>2
                if ($prevVal < $value) { // // 0 < 3, 3 < 2
                    $prevVal = $value; // 3
                    $prevKey = $key; // 1
                }
            }
            
            return $prevKey; // 2
        }

        function arrayCountValues($nums) {
            $arr = [];
            foreach($nums as $k){
                if(!isset($arr[$k])){
                    $arr[$k] = 0;
                }
                $arr[$k] += 1;
            }
    
        }

        function detectCapitalUse($word) {
            if(strlen($word) == 1) return true;
            $upper = false;
            $lower = false;
            $f_upper = ctype_upper($word[0]); // false
    
            for($i=1; $i<strlen($word); $i++){
                if(ctype_upper($word[$i])){ // 1
                    if(!$f_upper || $lower) {
                        return false;
                    }
                    $upper = true;
                } else { // 2
                    if($upper) {
                        return false;
                    }
                    $lower = true; // 3
                }
            }
            return true;
        }

        function findMaxConsecutiveOnes($nums) {
            $cnt = 0;
            $cnt_save = 0;
            foreach($nums as $i => $num) {
                if($num == 1){
                    $cnt++;
                } else {
                    if($cnt_save < $cnt){
                        $cnt_save = $cnt;
                    }
                    $cnt = 0;
                }
            }
            if($cnt_save < $cnt){
                return $cnt;
            } else {
                return $cnt_save;
            }
        }

        function generate($numRows) {
            $arr=[];
            for($i=0; $i<$numRows; $i++){
                $arr[$i][0] = 1;
                for($j=1; $j<$i; $j++){
                  $arr[$i][$j] = $arr[$i-1][$j-1] + $arr[$i-1][$j];
                }
                $arr[$i][$i] = 1;
            }
            return $arr;
        }

        function addDigits($num) {
            $num = str_split($num);
            $num = array_sum($num);
            if($num < 10){
                return $num;
            } else {
                // return $this->addDigits($num);
            }
        }

        function findTheDifference($s, $t) {
            $s_arr = str_split($s);
            sort($s_arr);

            $t_arr = str_split($t);
            sort($t_arr);

            for($i=0; $i<strlen($s); $i++){
                if($s_arr[$i] != $t_arr[$i]){
                    return $t_arr[$i];
                }
            }
            return $t_arr[$i];
        }

        function containsDuplicate($nums) {
            $nums = array_count_values($nums);
    
            foreach($nums as $num){
                if($num > 1){
                    return true;
                }
            }
            return false;
        }

        function toLowerCase($s) {
            for($i=0; $i<strlen($s); $i++){
                if(ctype_upper($s[$i])){
                    $s = strtolower($s);
                }
            }
            return $s;
        }

        function maximum69Number ($num) {
            $num = str_split($num);
    
            for($i=0; $i<count($num); $i++){
                if($num[$i] == 6){
                    $num[$i] = 9;
                    break;
                }
            }
            return implode($num);
        }

        function alternateDigitSum($num) {
            $isSum = false;
            $num = str_split($num);
            $result = 0;
            foreach($num as $n){
                if($isSum == false){
                    $result += $n;
                    $isSum = true;
                } else {
                    $result -= $n;
                    $isSum = false;
                }
            }
            return $result;
        }

        function longestCommonPrefix($strs) {
            if (count($strs) == 1) return $strs[0];
            $prefix = $strs[0];
            for ($i=1; $i<count($strs); $i++) {
                for ($j=0; $j<strlen($prefix); $j++) {
                    if (!isset($strs[$i][$j]) || $prefix[$j] != $strs[$i][$j]) {
                        break;
                    }
                }
                $prefix = substr($prefix, 0, $j);
            }
            return $prefix;
        }

        // $strs = ["1234","123","12"];
        // longestCommonPrefix($strs);

        function searchInsert_binary($nums, $target) {
            sort($nums);
            $low = 0;
            $high = count($nums) - 1;

            if($nums[$high] < $target){
                return $high + 1;
            } else if($nums[$low] >= $target){
                return 0;
            }

            while($low <= $high){
                $mid = floor(($high + $low) / 2);
        
                if ($nums[$mid] == $target) {
                    return $mid;
                } else if ($nums[$mid] > $target){
                    $high = $mid - 1;
                } else {
                    $low = $mid + 1;
                }
            }
            return $low;
        }
        // $nums = [1,3,5,6,8,10,12,13,17,20];
        // $target = 2;
        // searchInsert($nums, $target);

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

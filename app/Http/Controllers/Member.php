<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Member extends Controller
{
    public function user_info(Request $request)
    {
        $select = auth()->user();
        $data = array(
            'select' => $select,
        );

        return view('auth.user_info', $data);
    }

    public function user_update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'password' => 'required|current_password',
            'email_change' => 'unique:users,email|confirmed',
            'password_change' => 'confirmed',
        ]);
        
        if($validator->fails()){
            return redirect()->route('auth.user_info')->withErrors($validator);
        }

        $user = auth()->user();
        
        if(isset($request->name)){
            $user->name = $request->name;
        }
        if(isset($request->email_change)){
            $user->email = $request->email_change;
        }
        if(isset($request->password_change)){
            $hash = Hash::make($request->password_change);
            $user->password = $hash;
        }
        $user->save();

        return redirect()->route('auth.user_info');
    }
}

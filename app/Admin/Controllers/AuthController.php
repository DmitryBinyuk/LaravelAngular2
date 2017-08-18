<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use \Validator;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function getLogin()
    {
	$expirationDate = \Carbon\Carbon::now()->subDays(180);
	var_dump($expirationDate);
	die;
	
        return view('admin.login');
    }

    public function postLogin(Request $request)
    {
        $input = $request->only('email', 'password');
    	if (!$token = JWTAuth::attempt($input)) {
            \Session::flash('message', "Wrong email or password.");
            return \Redirect::back();
        }

//	header('Authorization: bearer'.$token);
//        JWTAuth::setToken($token);
//        $token = JWTAuth::parseToken();

	return \Redirect::route('admin.dashboard');//, array('token'=> $token));
    }

    public function postRegistration(Request $request)
    {
        $rules = array(
            'name' => 'required|min:1|max:50',
            'email' => 'required|email',
            'password' => 'required|min:6',
        );

        $rules_messages = array(
            "name.required" => 'Name field is required.',
            "email" => 'Email field is required.',
            "password" => 'Password field is required.',
        );

        $validator = Validator::make($request->all(), $rules, $rules_messages);

        if ($validator->fails()) {

            $errors = $validator->getMessageBag();

            return \Redirect::back()->withInput()->withErrors($errors);
        } else {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');

            $password=\Hash::make($request->input('password'));
            $user->password = $password;

            $user->save();

            return \Redirect::route('admin.login.get');
        }
    }
}
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{

    public function __construct(){
        $this->middleware('jwt', ['except' => ['check_username',
                                               'check_email',
                                               'reset_password',
                                               'list_all_users',
                                               'list_all_emails',
                                               'set_token'
                                               ]]);
    }

    public function message_error() {
        return response()->json([
            'error' => 'error en els paràmetres'
        ], 400);
    }

    public function username($username){
        if($username !== null && $username !== "") return "ok";
        return $this->message_error();
    }

    public function username_req(Request $request){
        if($request->username !== null && $request->username !== "") return "ok";
        return $this->message_error();
    }

    public function email(Request $request){
        if($request->email !== null && $request->email !== "") return "ok";
        else return $this->message_error();
    }

    public function update_info_user(Request $request, $username) {
        if ($this->username($username) === "ok" && $this->username_req($request) === "ok" &&
            $request->first_name !== null && $request->first_name !== "" && $request->last_name !== null &&
            $request->last_name !== "" && $request->birth_date !== null && $request->birth_date !== "" &&
            $this->email($request) === "ok")
            return "ok";
        return $this->message_error();
    }
}
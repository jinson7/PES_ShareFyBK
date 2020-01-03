<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\UserDataController;
use App\Http\Controllers\Api\RequestController;

class UserController extends Controller
{
    protected $user;
    protected $req_contr;

    public function __construct(){
        $this->middleware('jwt', ['except' => ['check_username',
                                               'check_email',
                                               'reset_password',
                                               'list_all_users',
                                               'list_all_emails',
                                               'set_token',
                                               'set_token_notification'
                                               ]]);
        $this->user = new UserDataController();
        $this->req_contr = new RequestController();
    }

    public function check_username(Request $request) {
        $result = $this->req_contr->username_req($request);
        if ( $result === 'ok' )
            return $this->user->check_username($request->username);
        return $result;
    }

    public function check_email(Request $request) {
        $result = $this->req_contr->email($request);
        if ( $result === 'ok' )
            return $this->user->check_email($request->email);
        return $result;
    }

    public function reset_password(Request $request) {
        $result = $this->req_contr->email($request);
        if ( $result === 'ok' )
            return $this->user->reset_password($request);
        return $result;
    }

    public function get_info_user($username) {
        $result = $this->req_contr->username($username);
        if ( $result === 'ok' )
            return $this->user->get_info($username);
        return $result;
    }

    public function list_all_users() {
        return $this->user->list_all();
    }

    public function list_all_emails() {
        return $this->user->list_all_emails();
    }

    public function update_info_user(Request $request, $username) {
        $result = $this->req_contr->update_info_user($request, $username);
        if ( $result === 'ok' )
            return $this->user->update_info($request, $username);
        return $result;
    }

    public function set_configurations(Request $request, $username) {
        $result = $this->req_contr->username($username);
        if ( $result === 'ok' )
            return $this->user->set_configurations($request, $username);
        return $result;
    }

    public function set_token(Request $request, $username) {
        $result = $this->req_contr->username($username);
        if ( $result === 'ok' )
            return $this->user->set_token($request, $username);
        return $result;
    }

    public function set_token_notification(Request $request, $username) {
        $result = $this->req_contr->username($username);
        if ( $result === 'ok' )
            return $this->user->set_token_notification($request, $username);
        return $result;
    }

    public function follow_requests(Request $request, $username) {
        $result = $this->req_contr->username($username);
        if ( $result === 'ok' )
            return $this->user->follow_requests($request, $username);
        return $result;
    }
}
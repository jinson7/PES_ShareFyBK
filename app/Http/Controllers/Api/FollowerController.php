<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Api\FollowerDataController;

class FollowerController extends Controller
{
    protected $follow;

    public function __construct(){
        //$this->middleware('jwt');
        $this->follow = new FollowerDataController();
    }

    public function create(Request $request, $username) {
        if($username !== null && $username !== "" && $request->follower_username != null &&
            $request->follower_username != ""){
            if ($username !== $request->follower_username)
                return $this->follow->create($request, $username);
            else {
                return response()->json([
                    'error' => 'Restricció: un usuari no pot seguir-se a ell mateix.'
                ], 403);
            }
        }
        return response()->json([
            'error' => 'error en els paràmetres'
        ], 400);
    }

    public function delete($follower, $followed) {
        if($follower !== null && $follower !== "" && $followed != null && $followed != "") {
            if ($follower !== $followed)
                return $this->follow->delete($follower, $followed);
            else {
                return response()->json([
                    'error' => 'Restricció: un usuari no pot deixar de seguir-se a ell mateix.'
                ], 401);
            }
        }
        return response()->json([
            'error' => 'error en els paràmetres'
        ], 400);
    }

    public function is_following($follower, $followed) {
        if($follower !== null && $follower !== "" && $followed != null && $followed != "") {
            if ($follower !== $followed)
                return $this->follow->is_following($follower, $followed);
            else {
                return response()->json([
                    'error' => 'Restricció: un usuari no pot seguir-se a ell mateix.'
                ], 401);
            }
        }
        return response()->json([
            'error' => 'error en els paràmetres'
        ], 400);
    }

    public function get_followers($id_user) {
        if ($id_user !== null && $id_user !== "")
            return $this->follow->get_followers($id_user);
        return response()->json([
            'error' => 'error en els paràmetres'
        ], 400);
    }

    public function get_followed($id_user) {
        if ($id_user !== null && $id_user !== "")
            return $this->follow->get_followed($id_user);
        return response()->json([
            'error' => 'error en els paràmetres'
        ], 400);
    }

    public function get_id_followed($id_user) {
        if ($id_user !== null && $id_user !== "")
            return $this->follow->get_id_followed($id_user);
        return response()->json([
            'error' => 'error en els paràmetres'
        ], 400);
    }

    public function accept_follow_requests(Request $request, $username) {
        if($username !== null && $username !== "" && $request->follower_username != null &&
            $request->follower_username != ""){
            if ($username !== $request->follower_username)
                return $this->follow->accept_follow_requests($request, $username);
            else {
                return response()->json([
                    'error' => 'Restricció: un usuari no pot seguir-se a ell mateix.'
                ], 401);
            }
        }
        return response()->json([
            'error' => 'error en els paràmetres'
        ], 400);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CommentDataController;

use App\Comment;

class CommentController extends Controller
{
    protected $comment;

    public function __construct(){
        //$this->middleware('jwt');
        $this->comment = new CommentDataController();
    }

    public function create(Request $request, $username, $id_publication){
        if($username !== null && $username !== "" && $id_publication!== null && $id_publication!== "" &&
            $request->text !== null && $request->text !== ""){
            return $this->comment->create($request, $username, $id_publication);
        }else{
            return response()->json([
                'error' => 'error en els paràmetres'
            ], 400);
        }
    }

    public function get($id_publication){
        if($id_publication!== null && $id_publication!== ""){
            return $this->comment->get($id_publication);
        }else{
            return response()->json([
                'error' => 'error en els paràmetres'
            ], 400);
        }
    }

    public function get_comments($id) {
        if ($id !== null && $id !== "")
            return $this->comment->get_comments($id);
        return $this->req_contr->message_error();
    }

    public function delete($id_publication){
        if($id_publication!== null && $id_publication!== ""){
            return $this->comment->delete($id_publication);
        }else{
            return response()->json([
                'error' => 'error en els paràmetres'
            ], 400);
        }
    }
}
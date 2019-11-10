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
            $request->text !== null && $request->text !== "" && $request->date !== null){
            return $this->comment->create($request, $username, $id_publication);
        }else{
            return response()->json([
                'error' => 'error en els paràmetres'
            ], 400);
        }
    }

}

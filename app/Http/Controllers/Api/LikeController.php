<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Api\LikeDataController;
use App\Http\Controllers\Api\RequestController;


class LikeController extends Controller
{
    protected $like_data;
    protected $publication;

    public function __construct(){
        //$this->middleware('jwt');
        $this->like_data = new LikeDataController();
        $this->req_contr = new RequestController();
        $this->publication = new PublicationController();
    }

    public function get_info_user($id) {
        if ($id !== null && $id !== "")
            return $this->like_data->get_info_user($id);
        return $this->req_contr->message_error();
    }

    public function is_like($username, $id_publication) {
        $result = $this->req_contr->username($username);
        if ( $result === 'ok' && $id_publication!== null && $id_publication!== "")
            return $this->like_data->is_like($username, $id_publication);
        return $result;
    }

    public function get_publications_by_user($id) {
        if ($id !== null && $id !== "") {
            $id_publications = $this->like_data->get_id_publications_by_user($id);
            return $this->publication->get_publications($id_publications);
        }
        return $this->req_contr->message_error();
    }

    public function set_like($username, $id_publication){
        if($username !== null && $username !== "" && $id_publication!== null && $id_publication!== ""){
            return $this->like_data->set_like($username, $id_publication);
        }else{
            return response()->json([
                'error' => 'error en els paràmetres'
            ], 400);
        }
    }

    public function unset_like($username, $id_publication){
        if($username !== null && $username !== "" && $id_publication!== null && $id_publication!== ""){
            return $this->like_data->unset_like($username, $id_publication);
        }else{
            return response()->json([
                'error' => 'error en els paràmetres'
            ], 400);
        }
    }
}

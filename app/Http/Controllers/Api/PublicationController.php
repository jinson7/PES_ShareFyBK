<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\PublicationDataController;
use App\Http\Controllers\Api\FollowerController;
use App\Http\Controllers\Api\UserController;

class PublicationController extends Controller
{
    protected $publication;
    protected $followed;
    protected $user;

    public function __construct(){
        //$this->middleware('jwt', [ 'except' => ['store', 'show']]);
        $this->publication = new PublicationDataController();
        $this->followed = new FollowerController();
        $this->user = new UserController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function list_publication_user($id_user) {
        return $this->publication->list_user($id_user);
    }

    public function store(Request $request){
        // agafar el video
        $file = $request->file('video');
        $ext = $file->getClientOriginalExtension();
        $ext = strtolower($ext);
        if( $ext === 'mp4' || $ext === 'jpg' || $ext === 'png' ){
            // crear publicació
            return $this->publication->create($request, $file, $ext);
        }else{
            return response()->json([
                'error' => "El vídeo no té un format vàlid, format suportat .mp4"
            ], 401);
        }
    }

    public function show($id) {
        return $this->publication->show($id);
    }

    public function wall($username) {
        $id = $this->user->get_id($username);
        if ($id != -1) {
            $followed = $this->followed->get_id_followed($id);
            $my_publications = collect(['id_followed' => (int)$id]);
            $followed->push($my_publications);
            return $this->publication->wall($followed);
        }
        return response()->json([
            'error' => "El username no existeix."
        ], 404);
    }

    public function edit(Request $request, $id) {
    }

    public function update(Request $request, $id) {
        return $this->publication->update($request, $id);
    }

    public function destroy($id) {
        return $this->publication->delete($id);
    }
}

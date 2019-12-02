<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\GameDataController;

class GameController extends Controller
{
    protected $game;

    public function __construct(){
        //$this->middleware('jwt');
        $this->game = new GameDataController();
    }

    public function index(){
        return $this->game->all();
    }

    public function game_publications($id){
        return $this->game->publications($id);
    }

    public function game_info_lang($id, $lang){
        return $this->game->info_lang($id, $lang);
    }
}

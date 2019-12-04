<?php

namespace App\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\NotificationInterface;

use App\Http\Controllers\FirebaseController;

use App\User;
use App\Game;

class NotificationInvite implements NotificationInterface
{
    private $from_user;
    private $game;
    private $to_user;
    private $firebase;

    public function __construct($url){

        $this->firebase = new FirebaseController();
        
        $options = explode("/", $url);
        $this->to_user = $options[count($options)-1];
        $this->game = str_replace("%20", " ", $options[count($options)-2]);
        $this->from_user = $options[count($options)-3];
    }

    public function getUser($username){
        return User::where('username', $username)->first();
    }

    public function getGame($game){
        return Game::where('name_en', $game)->first();
    }

    public function createNotification(){
        $title = "T'han invitat a un joc!!";
        $body = "En ".$this->from_user->username." t'ha invitat a fer una partida al joc: ".$this->game->name_en;
        $imageUrl = "http://sharefy.tk/images/logojusto.png";
        return $this->firebase->createNotificatoin($title, $body, $imageUrl);
    }

    public function createMessaging($notification){
        return $this->firebase->createMessaging($this->to_user->token_notification, $notification);
    }

    public function send(){
        $this->from_user = $this->getUser($this->from_user);
        $this->to_user = $this->getUser($this->to_user);
        $this->game = $this->getGame($this->game);
        if($this->from_user === null) return response()->json(['error' => 'from_user no trobat'], 400);
        if($this->to_user === null) return response()->json(['error' => 'to_user no trobat'], 400);
        if($this->game === null) return response()->json(['error' => 'game no trobat'], 400);
        $notification = $this->createNotification();
        return $this->createMessaging($notification);
    }
}

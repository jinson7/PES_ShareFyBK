<?php

namespace App;

use App\Http\Interfaces\NotificationInterface;

class SendNotification
{
    private $notification;

    public function __construct(NotificationInterface $notification){
        $this->notification = $notification;
    }

    public function send(){
        return $this->notification->send();
    }
}

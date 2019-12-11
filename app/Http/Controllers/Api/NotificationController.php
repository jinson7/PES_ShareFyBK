<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\FirebaseController;

use App\Notifications\NotificationLike;
use App\Notifications\NotificationInvite;
use App\Notifications\NotificationShare;
use App\Notifications\NotificationFollow;
use App\Notifications\NotificationComment;

use App\SendNotification;

class NotificationController extends Controller
{

    public function __construct(){
        $this->middleware('jwt');
    }

    protected $type_notifications = [
        'like' =>  NotificationLike::class,
        'comment' =>  NotificationComment::class,
        'invite' =>  NotificationInvite::class,
        'follow' =>  NotificationFollow::class,
        'share' =>  NotificationShare::class,
    ];

    /** @OA\Post(
     *     path="/api/notification/follow/{from_username}/{to_username}",
     *     tags={"notification"},
     *     summary="Envia una notificació follow a {to_username}.",
     *     description="Envia una notificació follow a {to_username}.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un json : {'message' => 'Notificació enviada correctament'}"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Retorna un json : {'error' => 'Destinatari no te les notificacions activades'} o {'error' => 'to_user no trobat'} o {'error' => 'from_user no trobat'}"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    /** @OA\Post(
     *     path="/api/notification/share/{from_username}/{to_username}",
     *     tags={"notification"},
     *     summary="Envia una notificació share a {to_username}.",
     *     description="Envia una notificació share a {to_username}.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un json : {'message' => 'Notificació enviada correctament'}"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Retorna un json : {'error' => 'Destinatari no te les notificacions activades'} o {'error' => 'to_user no trobat'} o {'error' => 'from_user no trobat'}"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    /** @OA\Post(
     *     path="/api/notification/comment/{from_username}/{to_username}",
     *     tags={"notification"},
     *     summary="Envia una notificació comment a {to_username}.",
     *     description="Envia una notificació comment a {to_username}.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un json : {'message' => 'Notificació enviada correctament'}"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Retorna un json : {'error' => 'Destinatari no te les notificacions activades'} o {'error' => 'to_user no trobat'} o {'error' => 'from_user no trobat'}"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    /** @OA\Post(
     *     path="/api/notification/like/{from_username}/{to_username}",
     *     tags={"notification"},
     *     summary="Envia una notificació like a {to_username}.",
     *     description="Envia una notificació like a {to_username}.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un json : {'message' => 'Notificació enviada correctament'}"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Retorna un json : {'error' => 'Destinatari no te les notificacions activades'} o {'error' => 'to_user no trobat'} o {'error' => 'from_user no trobat'}"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    /** @OA\Post(
     *     path="/api/notification/invite/{from_username}/{game->name_en}/{to_username}",
     *     tags={"notification"},
     *     summary="Envia una notificació invite a {to_username}.",
     *     description="Envia una notificació invite a {to_username}.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un json : {'message' => 'Notificació enviada correctament'}"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Retorna un json : {'error' => 'Destinatari no te les notificacions activades'} o {'error' => 'to_user no trobat'} o {'error' => 'from_user no trobat'} o {'error' => 'game no trobat'}"
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    **/
    public function sendNotification($type, Request $request){
        $type_notification = new $this->type_notifications[$type]($request->path());
        $notification = new SendNotification($type_notification);
        return $notification->send();
    }
}

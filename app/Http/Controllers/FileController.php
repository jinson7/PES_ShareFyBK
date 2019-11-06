<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class FileController extends Controller
{
    public function __construct(){
        $this->middleware('jwt');
    }
    
    /**
     * @OA\Post(
     *     path="/user/{username}/photo",
     *     tags={"user"},
     *     summary="Subir foto de perfil",
     *     description="Un usuario logeado puede subir una foto de perfil.",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna json 'message' : 'foto subida correctamente.'"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Retorna json 'error' : usuari no trobat a la base de dades.'"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Retorna json 'error' : 'no pot subir la foto, token no valido.'"
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="String amb el valor del username",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="photo",
     *         in="query",
     *         description="String amb el valor de photo",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * )
    */
    public function upload_photo(Request $request, $username){
        //$username = $request->username;
        $path = '/media/profiles/';
        $user = User::where('username', $username)->first();
        if($user === null ) return response()->json(['error' => 'usuari no trobat a la base de dades.'], 400);
        if($user->token_password !== $request->token) return response()->json(['error' => 'no pot subir la foto, token no valido.'], 401);
        $pub_path = public_path($path);
        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();
        if ($ext === 'jpg' || $ext === 'png') {
            $file->move($pub_path, $username . '.' . $ext);
            $user->photo_path = $path.$username.'.'.$ext;
            $user->save();
            return response()->json([
                'message' => 'foto subida correctamente.'
            ], 200);
        }
        return response()->json([
            'error' => "type of file invalid, only extension *.jpg and *.png"
        ], 400);
    }
}
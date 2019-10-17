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
     * @OA\Get(
     *     path="/api/upload_photo",
     *     tags={"user"},
     *     summary="Subir/Cambiar su foto de perfil",
     *     description="Un usuario puede subir/cambiar su foto de perfil",
     *     @OA\Response(
     *         response=200,
     *         description="Retorna un json 'message : foto subida correctamente.'"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Retorna un json 'error : usuari no trobat a la base de dades.'"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Retorna un json 'error : no pots modificar dades d\'un altre usuari.'"
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="String amb el valor del username",
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
    public function upload_photo(Request $request){ // /profile/{username.png}
        $username = $request->username;
        $user = User::where('username', $username)->first();
        if($user === null ) return response()->json(['error' => 'usuari no trobat a la base de dades.'], 400);
        if($user->token_password !== $request->token) return response()->json(['error' => 'no pots modificar dades d\'un altre usuari.'], 401);
        $path = public_path('/media/profiles');
        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();
        if ($ext === 'jpg' || $ext === 'png') {
            $file->move($path, $username . '.' . $ext);
            $user->photo_path = '/media/profile/'.$username.'.'.$ext;
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
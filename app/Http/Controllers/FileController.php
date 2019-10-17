<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/{username}?token=valor",
     *     tags={"user"},
     *     summary="Dado un username existente, devuelve su información.",
     *     description="Dado un username existente, devuelve su información.",
     *     @OA\Response(
     *         response=200,
     *         description="Devuelve un json con la información del usuario."
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="string amb el valor del username",
     *         required=true
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Valor del token_access",
     *         required=true
     *     )
     * 
     * )
    */

    public function upload_photo(Request $request){ // /profile/{username.png}
        $path = public_path('/media/profiles');
        $username = $request->username;
        $file = $request->file('photo');
        $ext = $file->getClientOriginalExtension();
        if ($ext === 'jpg' || $ext === 'png') {
            $file->move($path, $username . $ext);
            return response()->json([
                'value' => true
            ], 200);
        }
        return response()->json([
            'error' => "type of file invalid, only extension *.jpg and *.png"
        ], 400);
    }
}

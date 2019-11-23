<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\AppDataController;

class AppController extends Controller
{
    public function __construct(){
        //$this->middleware('jwt');
        $this->app = new AppDataController();
    }

    public function search($data) {
        if ($data != null && $data != '') {
            return $this->app->search($data);
        }
        return response()->json([
            'error' => 'error en els paràmetres'
        ], 400);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use function PHPSTORM_META\map;
use function Symfony\Component\String\b;

class CurrentUserController extends Controller {
    public function show() {
        return response()->json([
            'status' => 'success',
            'data' => Auth::user()
        ]);
    }
}

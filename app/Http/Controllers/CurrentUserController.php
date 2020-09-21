<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrentUserController extends Controller {
    public function show() {
        return response()->json([
            'status' => 'success',
            'data' => Auth::user()
        ]);
    }
}

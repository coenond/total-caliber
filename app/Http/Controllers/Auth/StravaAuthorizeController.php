<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class StravaAuthorizeController extends Controller
{
    public function authorized(Request $request)
    {
      $user = $request->user();
      \Log::info($user);
      \Log::info($request->all());

    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StravaAuthorizeController extends Controller
{
    public function authorized(Request $request)
    {
      $user = $request->user();
      \Log::info($user);
      \Log::info($request->all());

      // array (
      //   'state' => NULL,
      //   'code' => '656c164a168133bb414deded4ab821c18b24f1a6',
      //   'scope' => 'read,activity:write,activity:read_all',
      // )  
    }

}

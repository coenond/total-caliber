<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StravaWebhookController extends Controller
{
    public function handle(Request $req)
    {
        return new JsonResponse(['message' => 'ok']);
    }

    /**
     * When subscribing to strava webhooks, Strava will verify the URL on this end-point
     *
     * @param Request $req
     *
     * @return void
     */
    public function verify(Request $req)
    {
        return new JsonResponse(['hub.challenge' => $req->get('hub_challenge')]);
    }
}

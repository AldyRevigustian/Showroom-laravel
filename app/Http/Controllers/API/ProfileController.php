<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index($user_id)
    {
        $client = new Client();

        $profile = $client->get("https://www.showroom-live.com/api/user/profile?user_id={$user_id}");

        return response()->json(
            json_decode($profile->getBody()->getContents())
        );
    }
}

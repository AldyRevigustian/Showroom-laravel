<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FetchAllRoom extends Controller
{
    public $HOME = 'https://campaign.showroom-live.com/akb48_sr/data/room_status_list.json';
    public $BASE_URL = 'https://www.showroom-live.com/api';
    public $ROOM = "https://www.showroom-live.com/aONpi/room";
    public $LIVE = "https://www.showroom-live.com/api/live";
    public $ACADEMY = [
        '400710',
        '400713',
        '400712',
        '400714',
        '400715',
        '400716',
        '400717',
        '400718',
    ];

    public function rooms()
    {
        $res = Http::get($this->HOME);
        $resBod = json_decode($res->body());
        $roomList = [];

        foreach ($resBod as $resB) {
            // if (str_contains($resB->name, 'JKT48')) {
            //     $roomList[] = $resB;
            // }
            // $roomList[] = $resB;
            if (str_contains($resB->name, 'AKB48')) {
                $roomList[] = $resB;
            }
        }

        return response()->json(    $roomList
        );
    }

    public function onLives()
    {
        $onLive = [];
        $memberLive = [];
        $res = Http::get($this->LIVE . '/onlives');
        $resBod = json_decode($res->body());
        $onlives = $resBod->onlives;

        foreach ($onlives as $onlive) {
            if ($onlive->genre_name == 'Idol') {
                $onLive[] = $onlive;
            }
        }

        if (count($onLive) > 0) {
            $lives = $onLive[0]->lives;

            foreach ($lives as $l) {
                if (str_contains($l->room_url_key, 'JKT48')) {
                    $memberLive[] = $l;
                }
            }
        }


        return response()->json([
            'data' => $memberLive
        ]);
    }

    public function profile($room_id)
    {
        $res = Http::get($this->ROOM . '/profile?room_id=' . $room_id);
        $resBod = json_decode($res->body());

        return response()->json([
            $resBod
        ]);
    }

    public function nextLive($room_id)
    {
        $res = Http::get($this->ROOM . '/next_live?room_id=' . $room_id);
        $resBod = json_decode($res->body());
        return response()->json([
            $resBod
        ]);
    }
}

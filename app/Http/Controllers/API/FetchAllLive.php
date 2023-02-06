<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FetchAllLive extends Controller
{
    public $STREAMING_URL = 'https://www.showroom-live.com/api/live/streaming_url?room_id=';
    public $COMMENT_LOG = 'https://www.showroom-live.com/api/live/comment_log?room_id=';

    public function live($id)
    {
        $str = Http::get($this->STREAMING_URL . $id);
        $streaming = json_decode($str->body());

        $com = Http::get($this->COMMENT_LOG . $id);
        $comment = json_decode($com->body());


        return response()->json([
            'streaming' => $streaming,
            'comment' => $comment
        ]);
    }
}

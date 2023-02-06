<?php

use App\Http\Controllers\API\FetchAllRoom;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\DomCrawler\Crawler;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



function newSession()
{
    // Cookies
    $client = new Client();
    $response = $client->get('https://www.showroom-live.com');

    $cook = $response->getHeader('set-cookie');
    $cookies_id = explode('; ', $cook[0])[0];
    $cookies_f = explode('; ', $cook[1])[0];

    // Csrf
    $html = $response->getBody()->getContents();
    $crawler = new Crawler($html);

    $csrfs  = [];
    $crawler->filter('input[name="csrf_token"]')->each(function (Crawler $node) use (&$csrfs) {
        $csrfs[] = $node->attr('value');
    });
    $csrf = $csrfs[0];


    Session::put('cookies_id', $cookies_id);
    Session::put('cookies_f', $cookies_f);
    Session::put('csrf', $csrf);
}


Route::post('/login', function (Request $request) {
    $client = new Client();

    if (Session::get('cookies_id') == null || Session::get('csrf') == null) {
        newSession();
    }

    $cookies_id = Session::get('cookies_id');
    $cookies_f = Session::get('cookies_f');
    $csrf = Session::get('csrf');

    $login = $client->post('https://www.showroom-live.com/user/login', [
        'headers' => [
            'Cookie' => $cookies_id,
        ],
        'form_params' => [
            'csrf_token' => $csrf,
            'account_id' => '21032005',
            'password' => 'Akunbaru123',
            'captcha_word' => $request->captcha_word,
        ],
    ]);

    return response()->json(
        json_decode($login->getBody()->getContents())
    );
});

Route::post('/comment', function (Request $request) {
    if (Session::get('cookies') == null || Session::get('csrf') == null) {
        newSession();
    }

    $cookies_id = Session::get('cookies_id');
    $cookies_f = Session::get('cookies_f');
    $csrf = Session::get('csrf');

    $client = new Client();

    // dd($cookies_f. '; ' .$cookies_id);

    $comment = $client->post('https://www.showroom-live.com/api/live/post_live_comment', [
        'headers' => [
            'Content-Type'=> 'multipart/form-data; boundary=----WebKitFormBoundarydMIgtiA2YeB1Z0kl',
            'Host'=> 'www.showroom-live.com',
            'Cookie'=> 'f=8AE0877C-A551-11ED-8DA1-6E804D742A46; sr_id=hHtVDVLGbHlQXpoS53tmlpN26t2vb1xrwQsL2vN-NnvRup_bNkYts0P3C0x2HW0b',
            'Content-Length'=> '383',
        ],

        'form_params' => [
            'live_id' => '17182016',
            'comment' => 'tesss',
            'csrf_token' => 'KCX_pAiJT8hDUgF1FWY9cDI97QuH3ry8tlJgMvkq',
        ],
    ]);


    return response()->json(
        // $comment;
        json_decode($comment->getBody()->getContents())
    );
});



Route::prefix('rooms')->controller(FetchAllRoom::class)->group(function () {
    Route::get('/', 'rooms');

    Route::get('/onlives', 'onLives');
    Route::get('/profile/{room_id}', 'profile');
    Route::get('/next_live/{room_id}', 'nextLive');
    Route::get('/total_rank/{room_id}', 'totalRank');
    Route::get('/fan_letter/{room_id}', 'fanLetter');
});

Route::prefix('live')->controller(FetchAllLive::class)->group(function () {
    Route::get('/{id}', 'live')->name('member.live');
});

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Session;
use Symfony\Component\DomCrawler\Crawler;

class AuthController extends Controller
{

    public function newSession()
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

        return ['cookies_id' => $cookies_id, 'cookies_f' => $cookies_f, 'csrf' => $csrf];
        // Session::put('cookies_id', $cookies_id);
        // Session::put('cookies_f', $cookies_f);
        // Session::put('csrf', $csrf);
    }

    public function accountProfile($user_id)
    {
        $client = new Client();
        $profile = $client->get("https://www.showroom-live.com/api/user/profile?user_id={$user_id}");
        return  json_decode($profile->getBody()->getContents());
    }

    public function login(Request $request)
    {
        $client = new Client();
        // if (Session::get('cookies_id') == null || Session::get('csrf') == null) {
        $sess = $this->newSession();
        // }

        // $cookies_id = Session::get('cookies_id');
        // $cookies_f = Session::get('cookies_f');
        // $csrf = Session::get('csrf');

        $cookies_id = $sess['cookies_id'];
        $cookies_f = $sess['cookies_f'];
        $csrf = $sess['csrf'];

        $login = $client->post('https://www.showroom-live.com/user/login', [
            'headers' => [
                'Cookie' => $cookies_id,
            ],
            'form_params' => [
                'csrf_token' => $csrf,
                'account_id' => $request->account_id,
                'password' => $request->password,
                'captcha_word' => $request->captcha_word,
            ],
        ]);

        if ($login->getStatusCode() == '200') {

            $cook = $login->getHeader('Set-Cookie');
            $cookies_login = explode('; ', $cook[0])[0];
            // Session::put('cookies_login_id', $cookies_login);

            $loginJson = json_decode($login->getBody()->getContents());

            return response()->json(
                [
                    'session' => [
                        'cookies sr_id' => $cookies_id,
                        'cookie_login_id' => $cookies_login,
                        'cookies f' => $cookies_f,
                        'csrf_token' => $csrf,
                    ],
                    'user' => $loginJson,
                    'profile' => $this->accountProfile($loginJson->user_id)
                ]
            );
        }
    }
}

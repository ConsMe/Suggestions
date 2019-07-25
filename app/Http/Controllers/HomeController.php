<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Search;
use App\Suggestion;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function suggest(Request $request)
    {
        $url = 'https://suggest-maps.yandex.ru/suggest-geo?callback=id_156399828641450527879&v=5&search_type=tp&part='.$request->str.'&lang=ru_RU&n=6&origin=jsapi2Geocoder&bbox=33.948997488124995%2C44.85490259903078%2C34.278587331874995%2C45.040196655699525&local_only=0';
        $client = new Client();
        $result = $client->request('GET', $url);
        $result = json_decode(substr($result->getBody(), 25, -1));
        $search = Search::create(['text' => $request->str]);
        $insert = [];
        foreach ($result[1] as $address) {
            $insert[] = [
                'address' => $address[1],
                'search_id' => $search->id
            ];
        }
        DB::table('suggestions')->insert($insert);
        return response($result);
    }
}

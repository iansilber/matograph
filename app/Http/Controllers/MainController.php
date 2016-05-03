<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp;
use Cache;
use Carbon\Carbon;
use Log;

class MainController extends Controller {


    public function index()
    {
        return view('search');
    }

    public function details(Request $request)
    {
        return view('details');
    }

    public function getMovieDetails(Request $request)
    {
        define("ROTTEN_TOMATOES_API", "wcaad6vkbjb5hjwzhjwf7jw3");

        foreach ($request->input('movies') as $movie) {

            // print_r($movie['release_date']);
            // print("\n");
            $year = NULL;
            if (!empty($movie['release_date'])) {
                $year = Carbon::createFromFormat('Y-m-d', $movie['release_date'])->year;
            }
            $url = "http://api.rottentomatoes.com/api/public/v1.0/movies.json?q=" . urlencode($movie['original_title']);

            $data = Cache::rememberForever($url, function() use ($url) {
                $response = 0;
                while ($response != 200) {
                    $client = new GuzzleHttp\Client();
                    $res = $client->get($url . "&apikey=" . ROTTEN_TOMATOES_API);
                    $response = $res->getStatusCode();
                    if ($response == 500) {
                        sleep(1);
                    }
                }   
                return json_decode($res->getBody());
            });

            $found_movie = NULL;

            if ($data->total == 1 || ($data->total == 1 && !empty($year))) {
                $found_movie = $data->movies[0];

            } else if ($data->total > 1) {
                foreach ($data->movies as $movie) {
                    if ($movie->year == $year) {
                        $found_movie = $movie;
                        break;
                    }
                }
            
            }

            if ($found_movie) {
                $rt_movies[] = $found_movie;
            }            

        }

        return json_encode($rt_movies);
    }

    public function suggestions(Request $request) {
        try{
            $term = trim(strtolower($request->input('term')));
            $search = str_replace(array(" ", "(", ")"), array("_", "", ""), $term);
            $firstchar = substr($search,0,1);
            $url = "http://sg.media-imdb.com/suggests/${firstchar}/${search}.json";
            $jsonp = @file_get_contents($url);
            preg_match('/^imdb\$.*?\((.*?)\)$/ms', $jsonp, $matches); //convert JSONP to JSON
            $json = $matches[1];
            $arr = json_decode($json, true);
            $s = array();
            // print_r($arr);
            if(isset($arr['d'])){
                foreach($arr['d'] as $d){
                    preg_match("/^nm/", $d['id'], $matches);
                    // print_r($matches);
                    if (count($matches) > 0) {
                        if (array_key_exists('i', $d)) {
                            $img = preg_replace('/_V1_.*?.jpg/ms', "_V1._SY50.jpg", $d['i'][0]);
                        } else {
                            $img = null;
                        }
                        $s[] = array('label' => $d['l'], 'value' => $d['l'], 'cast' => $d['s'], 'img' => $img);
                    }
                }
            }
            header('content-type: application/json; charset=utf-8');
            return json_encode($s); //Convert it to JSON again
        } catch (Exception $e){
            //Do nothing
        }
    }

    public function imdbPoster(Request $request) {
        header("Content-type: image/jpeg");   
        $url = base64_decode($request->input('url'));
        return file_get_contents($url);
    }

}
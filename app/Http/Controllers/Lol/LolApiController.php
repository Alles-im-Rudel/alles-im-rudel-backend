<?php

namespace App\Http\Controllers\Lol;

use App\Http\Controllers\BaseController;
use App\Http\Requests\LolApi\LolApiSummonerRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LolApiController extends BaseController
{
	public function index(LolApiSummonerRequest $request)
	{
		$http = new Client();
		try {
			$response = $http->get(env('RIOT_API_URL').'/lol/summoner/v4/summoners/by-name/'.$request->summonerName.'?api_key='.env('RIOT_API_KEY'));
		} catch (GuzzleException $exception) {
			return $exception;
		}
		return $response;
	}
}

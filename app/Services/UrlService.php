<?php
/**
 * Created by PhpStorm.
 * User: Bekzod
 * Date: 13.04.2019
 * Time: 16:39
 */

namespace App\Services;

use App\Url;
use Illuminate\Http\Request;

class UrlService
{

    protected const CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    protected const LENGTH = 6;

    public function handleUrl(Request $request)
    {
        if(!$this->checkUrl($request->url)){
            throw new \Exception('Invalid Url', 422);
        }

        $url = new Url();
        $url->url = $request->url;
        if($expiration = $request->expiration){
            $url->expires_in = $expiration;
            $url->expires = true;
        }else{
            $url->expires_in = null;
            $url->expires = false;
        }

        $url->count = 0;
        $url->user_id = $request->user()->id;

        $url->short_code = $this->makeShortCode();
        $url->save();

        return $url;
    }

    protected function makeShortCode()
    {
        $max_length  = strlen(self::CHARS) - 1;
        $short_code = '';
        for($i = 0; $i < self::LENGTH; $i++){
            $short_code .= self::CHARS[mt_rand(0, $max_length)];
        }

        return $short_code;
    }

    protected function checkUrl(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }

    public function getUrl($short_code)
    {
        $url = Url::where('short_code', $short_code)->first();
        if(!$url){
            throw new \Exception('Url not found', 404);
        }
        if($this->isExpired($url)){
            throw new \Exception('Url expired', 400);
        }
        $url->count += 1;

        $url->save();
        return $url;
    }

    public function isExpired($url)
    {
        if($url->expires){
            $now = now()->unix();
            return $now > strtotime($url->expires_in);
        }
        return false;
    }
}
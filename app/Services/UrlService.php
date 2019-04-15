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

    /**
     * Chars to build random string
     */
    protected const CHARS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * length of the random string
     */
    protected const LENGTH = 6;

    /**
     * Handle Request and create url
     *
     * @param Request $request
     * @return Url
     * @throws \Exception
     */
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

    /**
     * Check if the given url address is active and valid
     *
     * @param string $url
     * @return bool
     */
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

    /**
     * Get the full url by its short_code
     *
     * @param $short_code
     * @return mixed
     * @throws \Exception
     */
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

    /**
     * Check if the given url is expired
     *
     * @param $url
     * @return bool
     */
    public function isExpired($url)
    {
        if($url->expires){
            $now = now()->unix();
            return $now > strtotime($url->expires_in);
        }
        return false;
    }
}
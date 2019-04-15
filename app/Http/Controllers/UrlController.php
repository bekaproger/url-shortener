<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlPostRequest;
use App\Url;
use Illuminate\Http\Request;
use App\Services\UrlService;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{

    protected $service;

    public function __construct(UrlService $service)
    {
        $this->service = $service;
    }

    /**
     * Redirect the user to the actual url address
     *
     * @param $short_code
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function getUrl($short_code)
    {
        try{
            $url = $this->service->getUrl($short_code);
        }catch (\Exception $e){
            return response($e->getMessage(), $e->getCode());
        }
        return redirect()->away($url->url);
    }

    public function create(UrlPostRequest $request)
    {
        try{
            $this->service->handleUrl($request);
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['url' => $e->getMessage()]);
        }
        return redirect()->route('home');

    }


    public function showAnalytics($short_code)
    {
        $url = $this->service->getUrl($short_code);
        return view('url.analytics', $url);
    }
}

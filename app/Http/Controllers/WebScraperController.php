<?php

namespace App\Http\Controllers;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Http\Request;

class WebScraperController extends Controller
{
    public function scrape(Request $request)
    {
        $browser = new HttpBrowser(HttpClient::create());

        $url = $request->query('url');
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return response()->json(['error' => 'Invalid URL'], 400);
        }

        $crawler = $browser->request('GET', $url);

        $links = $crawler->filter('a')->each(function ($node) {
            return $node->text().' - '.$node->link()->getUri();
        });

        return response()->json(['links' => $links]);
    }
}

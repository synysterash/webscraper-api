<?php

namespace App\Http\Controllers;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Http\Request;

class WebScraperController extends Controller
{
    public function scrape(Request $request)
    {
        // Create a new browser instance
        $browser = new HttpBrowser(HttpClient::create());

        // Get the URL from the query string and validate it
        $url = $request->query('url');
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return response()->json(['error' => 'Invalid URL'], 400);
        }

        // Visit the URL
        $crawler = $browser->request('GET', $url);

        // Example: Extract all links
        $links = $crawler->filter('a')->each(function ($node) {
            return $node->text().' - '.$node->link()->getUri();
        });

        // Return the scraped data as JSON
        return response()->json(['links' => $links]);
    }
}

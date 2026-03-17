<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $clinics = Clinic::where('status', 'active')
            ->where('website_enabled', true)
            ->select('slug', 'updated_at')
            ->get();

        $content = view('sitemap.index', compact('clinics'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $sitemap = url('/sitemap.xml');

        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /dashboard\n";
        $content .= "Disallow: /dashboard/*\n";
        $content .= "Disallow: /super-admin\n";
        $content .= "Disallow: /super-admin/*\n";
        $content .= "Disallow: /doctor\n";
        $content .= "Disallow: /doctor/*\n";
        $content .= "Disallow: /profile\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /register\n";
        $content .= "Disallow: /forgot-password\n";
        $content .= "Disallow: /reset-password\n\n";
        $content .= "Sitemap: {$sitemap}\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}

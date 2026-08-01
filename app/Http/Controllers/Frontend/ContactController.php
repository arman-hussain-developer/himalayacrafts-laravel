<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\FrontendData;

class ContactController extends Controller
{
    public function index()
    {
        $settings = FrontendData::settings();

        return view('frontend.contact', compact('settings'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotifyController extends Controller
{
    function banned()
    {
        return view('notify.banned');
    }
}

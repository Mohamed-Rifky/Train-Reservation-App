<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicAreaController extends Controller
{
    public function viewTrains(){
        return view('trains.booking_train');
    }
}

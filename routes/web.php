<?php

use App\Mail\Verification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    Mail::to('brahimradhoin09@gmail.com')->send(new Verification);
});
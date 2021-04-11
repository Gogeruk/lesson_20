<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Project;
use App\Models\Label;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

// NOTHING TO SEE HERE
// GO AWAY
// PLEASE...

Route::get('/test', function () {

    DB::enableQueryLog();

    // start your laravel query builder actions go here goes here

    //User::get();

    // end your laravel query builder actions
    $laQuery = DB::getQueryLog();

    $lcWhatYouWant0 = $laQuery[0]['query'];
    /*
    $lcWhatYouWant1 = $laQuery[1]['query'];
    $lcWhatYouWant2 = $laQuery[2]['query'];
    $lcWhatYouWant3 = $laQuery[3]['query'];
    $lcWhatYouWant4 = $laQuery[4]['query'];
    $lcWhatYouWant5 = $laQuery[5]['query'];
    */

    dd( $lcWhatYouWant0,
        //$lcWhatYouWant1,
        //$lcWhatYouWant2,
        //$lcWhatYouWant3,
        //$lcWhatYouWant4,
        //$lcWhatYouWant5,
    );

    //
    // optionally disable the query log:
    //DB::disableQueryLog();
    dd('DEATH__SET__1');


})->name('test');

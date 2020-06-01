<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['web', 'vodShareData'])->group(function (){
    Route::get('/', 'MainController@indexStreaming')->name('index');

    Route::post('video/search', 'MainController@search')->name('video.search');

    Route::get('list/{kind}/{value}', 'MainController@videoList')->name('video.list');

    Route::post('getListElems', 'MainController@getVideoListElems')->name('video.list.getElems');


    Route::get('streaming/show/{code}', 'MainController@showStreaming')->name('streaming.show');

    Route::get('streaming/live/{room?}', 'MainController@streamingLive')->name('streaming.live');


    Route::middleware(['auth'])->group(function () {
        Route::get('streaming/uploadPage', 'MainController@uploadVideoPage')->name('streaming.uploadPage');

        Route::post('streaming/storeVideo', 'MainController@storeVideo')->name('streaming.storeVideo');

        Route::post('streaming/storeVideoInfo', 'MainController@storeVideoInfo')->name('streaming.storeVideoInfo');

        Route::post('streaming/setVideoFeedback', 'MainController@setVideoFeedback')->name('streaming.setVideoFeedback');

        Route::post('streaming/setVideoComment', 'MainController@setVideoComment')->name('streaming.setVideoComment');

        Route::post('streaming/live/sendBroadcastMsg', 'MainController@sendBroadcastMsg')->name('sendBroadcastMsg');
        Route::post('streaming/live/setVideoFeedback', 'MainController@setLiveFeedback')->name('streaming.live.setLiveFeedback');

    });

    Route::get('/importVideoToDB', 'StreamingController@importVideoToDB');

    Route::get('/setVideoDuration', 'StreamingController@setVideoDuration');

    Route::get('policies', function(){
        dd('policies');
    })->name('policies');
});

Auth::routes();



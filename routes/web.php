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

Auth::routes();

Route::middleware(['web', 'vodShareData'])->group(function (){
    Route::get('/', 'StreamingController@indexStreaming')->name('streaming.index');

    Route::get('streaming/show/{code}', 'StreamingController@showStreaming')->name('streaming.show');

    Route::get('streaming/live/{room?}', 'StreamingController@streamingLive')->name('streaming.live');

    Route::post('streaming/search', 'StreamingController@search')->name('streaming.search');

    Route::get('streaming/list/{kind}/{value}', 'StreamingController@videoList')->name('streaming.list');

    Route::post('streaming/getListElems', 'StreamingController@getVideoListElems')->name('streaming.list.getElems');

    Route::middleware(['auth'])->group(function () {
        Route::get('streaming/uploadPage', 'StreamingController@uploadVideoPage')->name('streaming.uploadPage');

        Route::post('streaming/storeVideo', 'StreamingController@storeVideo')->name('streaming.storeVideo');

        Route::post('streaming/storeVideoInfo', 'StreamingController@storeVideoInfo')->name('streaming.storeVideoInfo');

        Route::post('streaming/setVideoFeedback', 'StreamingController@setVideoFeedback')->name('streaming.setVideoFeedback');

        Route::post('streaming/setVideoComment', 'StreamingController@setVideoComment')->name('streaming.setVideoComment');

        Route::post('streaming/live/sendBroadcastMsg', 'StreamingController@sendBroadcastMsg')->name('sendBroadcastMsg');
        Route::post('streaming/live/setVideoFeedback', 'StreamingController@setLiveFeedback')->name('streaming.live.setLiveFeedback');

    });

    Route::get('/importVideoToDB', 'StreamingController@importVideoToDB');

    Route::get('/setVideoDuration', 'StreamingController@setVideoDuration');

    Route::get('/confirmAll', 'StreamingController@confirmAll');
});


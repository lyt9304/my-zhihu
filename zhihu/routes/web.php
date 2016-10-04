<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api', function () {
   return ['version' => 0.1];
});

// Hint: 尼玛路径里不能含有user
// 貌似新版本会对这样的路径做处理
// 所以会强制跳到login的页面
Route::get('/api/you', function () {
    $user = new App\User();
    return $user->signup();
//    return 'haha';
});
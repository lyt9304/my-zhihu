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

function user_init() {
    return new App\User();
}

function question_init() {
    return new App\Question();
}

function answer_init() {
    return new App\Answer();
}

Route::get('/', function () {
    return view('index');
});

Route::any('/api', function () {
   return ['version' => 0.1];
});

// Hint: 尼玛路径里不能含有user
// 貌似新版本会对这样的路径做处理
// 所以会强制跳到login的页面
Route::any('/api/signup', function () {
    return user_init()->signup();
//    return 'haha';
});

Route::any('/api/login', function () {
    return user_init()->login();
//    return 'haha';
});

Route::any('/api/logout', function () {
    return user_init()->logout();
//    return 'haha';
});

Route::any('/test/islogin', function () {
    dd(user_init()->is_logged_in());
});

Route::any('/api/question/add', function () {
    return question_init()->add();
});

Route::any('/api/question/change', function () {
    return question_init()->change();
});

Route::any('/api/question/read', function () {
    return question_init()->read();
});

Route::any('/api/question/remove', function () {
    return question_init()->remove();
});

Route::any('/api/answer/add', function () {
    return answer_init()->add();
});

Route::any('/api/answer/change', function () {
    return answer_init()->change();
});

Route::any('/api/answer/read', function () {
    return answer_init()->read();
});
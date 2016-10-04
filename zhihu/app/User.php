<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;

class User extends Model
{
	/**
	 * 注册API
	 * @return array
	 */
    public function signup() {
//		dd(Request::get('user'));
//		dd(Request::has('user'));
//		dd(Request::all());

		$username = Request::get('username');
		$password = Request::get('password');

		if(!$username || !$password) {
			return [
				'status' => 0,
				'msg' => '用户名和密码不可为空'
			];
		}

		// Question: model是怎么和migration连在一起的,是因为同名嘛?
		// User -> users?
		$user_exists = $this
			->where('username', $username)
			->exists();

		if($user_exists) {
			return [
				'status' => 0,
				'msg' => '用户名已存在'
			];
		}

		$hashed_password = Hash::make($password);
//		$hashed_password = bcrypt($password);

		$user = $this;
		$user->password = $hashed_password;
		$user->username = $username;
		if($user->save()) {
			return [
				'status' => '1',
				'id' => $user->id
			];
		} else {
			return [
				'status' => '0',
				'msg' => '数据库保存失败'
			];
		}
	}

	/**
	 * 登出API
	 */
	public function logout() {
//		session()->flush();

//		session()->set('persion.name', 'xiaoming');

//		session()->put('username', null);
//		session()->pull('username');

		session()->forget('username');
		session()->forget('user_id');

//		return redirect('/');

		return [
			'status' => 1
		];


//		dd(session()->all());

	}

	/**
	 * 登陆API
	 */
	public function login() {
		$username = Request::get('username');
		$password = Request::get('password');

		if(!$username || !$password) {
			return [
				'status' => 0,
				'msg' => '用户名和密码不可为空'
			];
		}

		$user = $this
			->where('username', $username)
			->first();

		if(!$user) {
			return [
				'status' => 0,
				'msg' => '用户名不存在'
			];
		}

		$hashed_password = $user->password;

		if(!Hash::check($password, $hashed_password)) {
			return [
				'status' => 0,
				'msg' => '密码错误'
			];
		}

		// 将用户写入session中,之后可以使用是否有username来判断是否登陆
		session()->put('username', $user->username);
		session()->put('user_id', $user->id);
//		var_dump(session('abc'));
//		var_dump(session('abc', 'cde'));
//		dd(session()->all());

		return [
			'status' => 1,
		];
	}

	public function is_logged_in() {
		return session('user_id') ?: false;
	}
}

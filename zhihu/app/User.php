<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Hash;

class User extends Model
{
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
}

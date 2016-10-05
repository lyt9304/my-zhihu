<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Question extends Model
{
	/**
	 * 创建问题
	 * @return array
	 */
    public function add() {

//		检查用户是否登录
		if(!user_init()->is_logged_in()) {
			return [
				'status' => '0',
				'msg' => '需要登录'
			];
		}

		$title = Request::get('title');
		$desc = Request::get('desc');

		if(!$title) {
			return [
				'status' => '0',
				'msg' => '问题标题不能为空'
			];
		}

		$this->title = $title;
		$this->user_id = session('user_id');

		if($desc) {
			$this->desc = $desc;
		}

		return $this->save() ?
			['status' => 1, 'id' => $this->id] :
			['status' => 0, 'msg' => '数据库保存失败'];
	}

	public function change() {
		if(!user_init()->is_logged_in()) {
			return [
				'status' => '0',
				'msg' => '需要登录'
			];
		}

		$id = Request::get('id');

		if(!$id) {
			return [
				'status' => '0',
				'msg' => '需要提供id'
			];
		}

		$question = $this->find($id); // 返回 id = 1 的那个question model

		if(!$question) {
			return [
				'status' => '0',
				'msg' => '问题不存在'
			];
		}

		if($question->user_id != session('user_id')) {
			return [
				'status' => '0',
				'msg' => '没有权限更改问题'
			];
		}

		$title = Request::get('title');
		$desc = Request::get('desc');

		if($title) {
			$question->title = $title;
		}

		if($desc) {
			$question->desc = $desc;
		}

		return $question->save() ?
			['status' => 1, 'id' => $question->id] :
			['status' => 0, 'msg' => '数据库保存失败'];
	}
}

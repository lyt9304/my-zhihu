<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Answer extends Model
{
    public function add() {
		if(!user_init()->is_logged_in()) {
			return [
				'status' => '0',
				'msg' => '需要登录'
			];
		}

		$question_id = Request::get('question_id');
		$content = Request::get('content');
		$user_id = session('user_id');

		if(!$question_id || !$content) {
			return [
				'status' => '0',
				'msg' => '问题id和回答内容不能为空'
			];
		}

		$question = question_init()->find($question_id);

		if(!$question) {
			return [
				'status' => '0',
				'msg' => '问题不存在'
			];
		}

		$answer = $this->where(['user_id' => $user_id, 'question_id' => $question_id])
					->count();

		if($answer > 0) {
			return [
				'status' => '0',
				'msg' => '同一问题不能回答两次'
			];
		}

		$this->user_id = $user_id;
		$this->content = $content;
		$this->question_id = $question_id;

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
				'msg' => '需要提供回答id'
			];
		}

		$answer = $this->find($id); // 返回 id = 1 的那个question model

		if(!$answer) {
			return [
				'status' => '0',
				'msg' => '回答不存在'
			];
		}

		if($answer->user_id != session('user_id')) {
			return [
				'status' => '0',
				'msg' => '没有权限更改回答'
			];
		}

		$content = Request::get('content');

		if(!$content) {
			return [
				'status' => '0',
				'msg' => '回答不能为空'
			];
		}

		$answer->content = $content;

		return $answer->save() ?
			['status' => 1, 'id' => $answer->id] :
			['status' => 0, 'msg' => '数据库保存失败'];
	}

	public function read() {
		$id = Request::get('id');
		$question_id = Request::get('question_id');

		if(!$id && !$question_id) {
			return [
				'status' => '0',
				'msg' => '必须提供answer或者question的id'
			];
		}

		if($id) {
			$answer = $this->find($id);
			if(!$answer) {
				return [
					'status' => '0',
					'msg' => '回答不存在'
				];
			}
			return [
				'status' => '1',
				'data' => $answer
			];
		}

		if($question_id) {
			$question = question_init()->find($question_id);
			if(!$question) {
				return [
					'status' => '0',
					'msg' => '问题不存在'
				];
			}
			$res = $this->where(['question_id' => $question_id])
						->get()
						->keyBy('id');
			return [
				'status' => '1',
				'data' => $res
			];
		}
	}
}

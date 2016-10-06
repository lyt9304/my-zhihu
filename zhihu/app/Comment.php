<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Comment extends Model
{
    public function add() {
		if(!user_init()->is_logged_in()) {
			return [
				'status' => '0',
				'msg' => '需要登录'
			];
		}

		$content = Request::get('content');
		$user_id = session('user_id');

		if(!$content) {
			return [
				'status' => '0',
				'msg' => '评论内容不能为空'
			];
		}

		$answer_id = Request::get('answer_id');
		$question_id = Request::get('question_id');
		$reply_to = Request::get('reply_to');

		// 回复answer或者question, 有且只能有一个
		if((!$answer_id && !$question_id)
			||
			($answer_id && $question_id)
		) {
			return [
				'status' => '0',
				'msg' => '只能对问题或者回答其中一个进行评论'
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

			$this->question_id = $question_id;
		}

		if($answer_id) {
			$answer = answer_init()->find($answer_id);

			if(!$answer) {
				return [
					'status' => '0',
					'msg' => '回答不存在'
				];
			}

			$this->answer_id = $answer_id;
		}

		if($reply_to) {
			$target = $this->find($reply_to);

			if(!$target) {
				return [
					'status' => '0',
					'msg' => '目标不存在'
				];
			}

			// 不可以回复自己
			if($target->user_id == $user_id) {
				return [
					'status' => '0',
					'msg' => '不可以回复自己'
				];
			}

			$this->reply_to = $reply_to;
		}

		$this->user_id = $user_id;
		$this->content = $content;

		return $this->save() ?
			['status' => 1, 'id' => $this->id] :
			['status' => 0, 'msg' => '数据库保存失败'];
	}

	public function read() {
		$answer_id = Request::get('answer_id');
		$question_id = Request::get('question_id');

		// 回复answer或者question, 有且只能有一个
		if((!$answer_id && !$question_id)
			||
			($answer_id && $question_id)
		) {
			return [
				'status' => '0',
				'msg' => '必须只提供一个 answer id 或者 question id'
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

			$data = $this->where(['question_id' => $question_id])->get();
		}

		if($answer_id) {
			$answer = answer_init()->find($answer_id);

			if(!$answer) {
				return [
					'status' => '0',
					'msg' => '回答不存在'
				];
			}
			$data = $this->where(['answer_id' => $answer_id])->get();
		}

		return [
			'status' => '1',
			'data' => $data->keyBy('id')
		];
	}

	public function removeRef($id) {
		$models = $this->where(['reply_to' => $id])->get()->all();
		foreach($models as $key => $item) {
			if(!$item->removeRef((string)$item->id)) {
				return false;
			}
		}
		return $this->where(['id' => $id])->delete();
	}

	public function remove() {
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
				'msg' => '需要提供评论id'
			];
		}

		$comment = $this->find($id); // 返回 id = 1 的那个question model

		if(!$comment) {
			return [
				'status' => '0',
				'msg' => '评论不存在'
			];
		}

		if($comment->user_id != session('user_id')) {
			return [
				'status' => '0',
				'msg' => '没有权限删除评论'
			];
		}

		return $this->removeRef($id) ?
			['status' => 1] :
			['status' => 0, 'msg' => '数据库删除失败'];
	}
}

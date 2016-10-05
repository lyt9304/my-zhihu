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

	/**
	 * 修改问题API
	 * @return array
	 */
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

		if(!$title) {
			return [
				'status' => '0',
				'msg' => '问题标题不能为空'
			];
		}

		$question->title = $title;

		if($desc) {
			$question->desc = $desc;
		}

		return $question->save() ?
			['status' => 1, 'id' => $question->id] :
			['status' => 0, 'msg' => '数据库保存失败'];
	}

	/**
	 * 查看问题API
	 */
	public function read() {
		$id = Request::get('id');
		if($id) {
			return [
				'status' => '1',
				'data' => $this->find($id)
			];
		}

//		如果不存在id, 则默认给予某几条
		$limit = Request::get('limit')?:15;
		$page = Request::get('page')?:1;
		$skip = ($page - 1) * $limit;

		$res = $this->orderBy('created_at')
			->limit($limit)
//			->get(['id', 'title', 'desc'])
			->skip($skip)
			->get()
			->keyBy('id'); // get collection

		return [
			'status' => '1',
			'data' => $res
		];
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
				'msg' => '需要提供问题id'
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
				'msg' => '没有权限删除问题'
			];
		}

		return $question->delete() ?
			['status' => 1] :
			['status' => 0, 'msg' => '数据库删除失败'];
	}

}

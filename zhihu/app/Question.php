<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Question extends Model
{
    public function add() {
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
}

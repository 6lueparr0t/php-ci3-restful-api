<?php
defined('BASEPATH') or exit('No direct script access allowed');

class V1 extends CI_Controller
{
	private $user_search_key;
	private $user_update_key;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model', 'user');
		$this->user_search_key = array('idx','name','nick','tel','email','code');
		$this->user_update_key = array('name','pswd','nick','tel','email');

		$this->load->library('UserManager');
	}

	public function index()
	{
		echo json_encode(['code' => '-1', 'result' => 'wrong path']);
		exit();
	}

	function restful_check() {
		$method = $this->input->method();

		if ($method == 'get') {
			$data = $this->input->get();
		} else {
			$data = json_decode($this->input->raw_input_stream, true);
		}

		if (!$data) {
			echo json_encode(['code' => '-1', 'result' => 'input data']);
			exit();
		}

		return array($method, $data);
	}

	public function user()
	{
		list($method, $data) = $this->restful_check();

		switch ($method) {
			case 'get':
				$result = $this->get($data);
				break;
			case 'post':
				$result = $this->join($data);
				break;
			case 'put':
				$result = $this->update($data);
				break;
			case 'delete':
				$result = $this->del($data);
				break;
			default:
				break;
		}

		echo json_encode($result);
	}

	public function userlist()
	{
		list($method, $data) = $this->restful_check();

		switch ($method) {
			case 'get':
				$result = $this->list($data);
				break;
			default:
				break;
		}

		echo json_encode($result);
	}

	function join($data) {
		// $result = ['code' => '-1', 'result' => 'wrong key'];
		$cleanData = $this->um->joinCheck($data);
		
		if($this->user->count_rcmd($cleanData['rcmd']) > 5) {
			return ['code' => '-1', 'result' => 'This customer is not eligible for referrals.'];
		}
		if($cleanData['rcmd'] && !$this->user->user_select ('rcmd', $cleanData['rcmd'])){
			return ['code' => '-1', 'result' => 'referrals is not found.'];
		}
		
		while(1) {
			$code = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", 5)), 0, 5);
			if(!$this->user->user_select ('code', $code)) break;
		}
		
		$cleanData['code'] = $code;
		$result = $this->user->user_insert($cleanData);

		return ['code' => '200', 'result' => 'insert success (id:'.$result.')'];
	}
	
	function update($data) {
		if(!array_key_exists('key', $data)) return ['code' => '-1', 'result' => 'Unauthorized key1'];
		foreach($data['request'] as $key => $val) {
			if(!in_array($key, $this->user_update_key)) {
				return ['code' => '-1', 'result' => 'Unauthorized key'];
			}
		}
		
		if(in_array($data['key'], $this->user_search_key) == true) {
			$cleanData = $this->um->joinCheck($data['request'], true);
			$result = $this->user->user_update($cleanData, $data['key'], $data['value']);
		} else {
			$result = ['code' => '-1', 'result' => 'Unauthorized key2'];
		}

		if($result) {
			return ['code' => '200', 'result' => 'update success'];
		} else {
			return ['code' => '-1', 'result' => 'update fail'];
		}
	}
	
	function del($data) {
		if(in_array($data['key'], $this->user_search_key) == true) {
			$result = $this->user->user_delete ($data['key'], $data['value']);
		} else {
			return ['code' => '-1', 'result' => 'Unauthorized key'];
		}

		if($result) {
			return ['code' => '200', 'result' => 'delete success'];
		} else {
			return ['code' => '-1', 'result' => 'delete fail'];
		}
	}

	function get($data) {

		if(in_array($data['key'], $this->user_search_key) == true) {
			$ret = $this->user->user_select ($data['key'], $data['value']);
		} else {
			$ret = ['code' => '-1', 'result' => 'Unauthorized key'];
		}

		return $ret;
		
	}

	function list($data) {
		// $result = ['code' => '-1', 'result' => 'wrong key'];
		$ret = $this->user->user_page($data['page'], (isset($data['count'])?$data['count']:0));

		return $ret;
	}
}

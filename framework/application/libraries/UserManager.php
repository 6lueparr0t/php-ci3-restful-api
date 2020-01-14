<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserManager {

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->allow=array('');
    }

    public function joinCheck ($data, $opt = false) {
        $checkArray = $data;

		$ret['result'] = false;

		try {
			preg_match('/^[a-zA-Z\xEA-\xED\x80-\xBF]*$/', (isset($checkArray['name'])?$checkArray['name']:false), $name);
			preg_match('/^[a-z]*$/', (isset($checkArray['nick'])?$checkArray['nick']:false), $nick);
			preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[~!@#$%^&*-_=+`\[\]\{\}|\\:;\'\"<\<>,.\/?]).*$/', (isset($checkArray['pswd'])?$checkArray['pswd']:false), $pswd);
			preg_match('/^[0-9]*$/', (isset($checkArray['tel'])?$checkArray['tel']:false), $tel);
			
			if((!$name && !$opt) || (mb_strlen($name[0], 'utf-8') < 0) || (mb_strlen($name[0], 'utf-8') > 20)) {
				$ret['data']['msg'] = 'Name : Korean, Enlgish only';
			} else if((!$nick && !$opt) || (mb_strlen($nick[0], 'utf-8') < 0 || mb_strlen($nick[0], 'utf-8') > 10)) {
				$ret['data']['msg'] = 'Nick : English Lower Only';
			} else if((!$pswd && !$opt)) {
				if(strlen($pswd[0]) < 10) $ret['data']['msg'] = 'Password : At least 10 characters long, Korean, Korean, special characters, numbers';
			} else if((!$tel && !$opt) || (strlen($tel[0]) < 0 || strlen($tel[0]) > 20)) {
				$ret['data']['msg'] = 'Tel : 20 characters maximum';
			} else if((!isset($checkArray['email']) && !$opt)) {
				if(strlen($checkArray['email']) > 100) $ret['data']['msg'] = 'Email : 100 characters maximum';
			} else if(!$opt && strlen($checkArray['rcmd']) > 5) {
				$ret['data']['msg'] = 'Rcmd : 5 characters maximum';
			} else if(!$opt && !in_array($checkArray['gender'], ['f','m',''])) {
				$ret['data']['msg'] = 'Gender : Invalid gender';
			} else {
				$ret['result'] = true;
			}
		} catch (exception $e) {
			$ret['data']['msg'] = 'An error occurred.';
		}

        if($ret['result'] == false) {
			echo json_encode($ret);
			exit();
        } else {
            $ret = $data;
		}

        return $ret;
    }

}

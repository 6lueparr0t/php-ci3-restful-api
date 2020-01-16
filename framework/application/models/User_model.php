<?php
defined('BASEPATH') or exit('No direct script access allowed');
define('DEFAULT_COUNT', 30);
class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function user_insert ($data) {

        $query = "INSERT INTO user ( name, nick, pswd, tel, email, gender, code, rcmd ) VALUES ( ?, ?, Password(?), ?, ?, ?, ?, ? )";

        $this->db->query($query, [
            $data['name'],
            $data['nick'],
            $data['pswd'],
            $data['tel'],
            $data['email'],
            $data['gender'],
            $data['code'],
            $data['rcmd']
        ]);
        
        $result = $this->db->insert_id();

		return $result;
    }

    public function user_update ($set, $key, $value) {

        $update_str = "";
        $tmp = [];
        foreach($set as $k => $v) {
            if($k == 'pswd') {
                $tmp[] = "{$k} = Password('".$this->db->escape_str($v)."')";
            } else {
                $tmp[] = "{$k} = '".$this->db->escape_str($v)."'";
            }
        }
        $update_str = implode(",", $tmp);


        $query = "UPDATE user SET {$update_str} WHERE {$key} = ? ";
        $result = $this->db->query($query, $this->db->escape_str($value));

        return $result;
    }

    public function user_delete ($key, $value) {
        $query = "DELETE FROM user WHERE {$key} = ?";
        $result = $this->db->query($query, $this->db->escape_str($value));
        
        return $result;
    }

    public function user_select ($key, $value) {
        $query = "SELECT * FROM user WHERE {$key} = ?";

        $find = $this->db->query($query, $this->db->escape_str($value));
        
        $result = $find->row();
        
        return $result;
    }
	
    public function count_rcmd ($rcmd) {
        $query = "SELECT count(*) cnt FROM user WHERE rcmd = ?";

        $find = $this->db->query($query, $this->db->escape_str($rcmd));
        
        $result = $find->row()->cnt;
        
        return $result;
    }

    public function user_page ($page, $count) {
        $page = $this->db->escape_str($page);
        if($count == 0) {
            $count = 30;
        } else {
            $count = $this->db->escape_str($count);
        }

        $query = "SELECT a.* FROM (SELECT idx FROM user ORDER BY idx DESC LIMIT ".$page*$count.", ".$count.") b JOIN user a on b.idx = a.idx";
        $find = $this->db->query($query);
        
        $result = $find->result();

        return $result;
    }
}

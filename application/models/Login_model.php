<?php
class Login_model extends CI_Model{
	
	public function checkLogin($email,$password){
		$this->db->where('email',$email);
        $this->db->where('password',$password);
        $query = $this->db->get('users');
        return $query->result();
	}
}
?>
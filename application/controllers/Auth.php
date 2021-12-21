<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');

	}
	public function index(){
		if($this->session->userdata('email')){
			redirect('user');
		}
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Password','trim|required');


		if ($this->form_validation->run() == false){
		$data['title'] = 'Login page';
		$this->load->view('templates/auth_header',$data);
		$this->load->view('auth/login');
		$this->load->view('templates/auth_footer');
		}else{
			//ketika validasinya success
			$this->_login();
		}
		
	}




	private function _login(){
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$user = $this->db->get_where('user', ['email' => $email])->row_array();
		 
		 // jika usernya ada
		 if($user){
		 	//jika usernya aktif
		 	if($user['is_active'] == 1){
		 		//cek password
		 		if(password_verify($password, $user['password'])){
		 			$data = [
		 				'email' => $user['email'],
		 				'role_id' => $user['role_id']
		 			];
		 			$this->session->set_userdata($data);
		 			if($user['role_id'] == 1){
		 				redirect('admin');
		 			}else{
		 				redirect('user');
		 			}
		 			
		 		}else{
		 			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password</div>');
			redirect('auth');
		 		}
		 	}else{
		 		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This email has not been activated!</div>');
			redirect('auth');
		 	}
		 }else{
		 	$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email is not registered!</div>');
			redirect('auth');
		 }
	}




	public function registration(){
		if($this->session->userdata('email')){
			redirect('user');
		}
		$this->form_validation->set_rules('name','Name','required|trim');
		$this->form_validation->set_rules('email','Email','required|trim|valid_email|is_unique[user.email]',[
			'is_unique' =>'This email has already registered!'
		]);
		$this->form_validation->set_rules('password1','Password','required|trim|min_length[3]|matches[password2]',[

			'matches' => 'Password dont match!',
			'min_length'=>'Password too short'
		]);
		$this->form_validation->set_rules('password2','Password','required|trim|matches[password1]');

		if ($this->form_validation->run() == false){
		$this->load->view('templates/auth_header');
		$this->load->view('auth/registration');
		$this->load->view('templates/auth_footer');
		}else{
			$email = $this->input->post('email',true);
			$data = [
				'name' => htmlspecialchars($this->input->post('name', true)),
				'email' => htmlspecialchars($this->unput->post('email',true)),
				'image' => 'default.jpg',
				'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
				'role_id' => 2,
				'is_active' => 1,
				'date_created' => time()
			];
			$this->db->insert('user',$data);
			$this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Congratulation! your account has been created. Please login</div>');
			redirect('auth');
		}
	}

				

	// 			//siapkan token
	// 		$token = base64_encode(random_bytes(32));
	// 		$user_token = [
	// 			'email' => $email,
	// 			'token' => $token,
	// 			'date_created' => time()
	// 		];


	// 		$this->db->insert('user',$data);
	// 		$this->db->insert('user_token',$user_token);

	// 		$this->_sendEmail($token,'verify');

	// 		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Congratulation! your account has been created. Please activete your account</div>');
	// 		redirect('auth');
	// 	}
		
	// }


	// private function _sendEmail($token,$type)
	// {
	// 	$config = [
 //            'protocol'  => 'smtp',
 //            'smtp_host' => 'ssl://smtp.googlemail.com',
 //            'smtp_user' => 'hanyates60@gmail.com',
 //            'smtp_pass' => '123456789',
 //            'smtp_port' => 465,
 //            'mailtype'  => 'html',
 //            'charset'   => 'utf-8',
 //            'newline'   => "\r\n"
 //        ];

 //        $this->load->library('email',$config);
 //        $this->email->initialize($config); 

 //        $this->email->from('hanyates60@gmail.com','Web Programing UNPAS');
 //        $this->email->to($this->input->post('email'));

 //        if($type == 'verify')
 //        {
 //        $this->email->subject('Account Verification');
 //        $this->email->message('Click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>'); 
 //        }
        


 //        if($this->email->send()){
 //        	return true;
 //        }else{
 //        	echo $this->email->print_debugger();
 //        	die;
 //        }
	// }

	// public function verify()
	// {
	// 	$email = $this->input->get('email');
	// 	$token = $this->input->get('token');

	// 	$user = $this->db->get_where('user', ['email' => $email])->row_array();

	// 	if($user){
	// 		$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

	// 		if($user_token){
	// 			if(time() - $user_token['date_created'] < (60*60*24)){
	// 				$this->db->set('is_active', 1);
	// 				$this->db->where('email',$email);
	// 				$this->db->update('user');

	// 				$this->db->delete('user_token', ['email' => $email]);

	// 				$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">'. $email .' has been activated! Pleace login.</div>');
	// 		redirect('auth');
	// 			}else{

	// 				$this->db->delete('user',['email' => $email]);
	// 				$this->db->delete('user_token', ['email' => $email]);

	// 				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">account activation failed! Token Expired.</div>');
	// 		redirect('auth');
	// 			}
	// 		}else{
	// 			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">account activation failed! Wrong token.</div>');
	// 		redirect('auth');
	// 		}
	// 	}else{
	// 		$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">account activation failed! Wrong email.</div>');
	// 		redirect('auth');
	// 	}




	public function logout(){
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');

		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out!</div>');
			redirect('auth');
	}

	public function blocked()
	{
		$this->load->view('auth/blocked');
	}
}
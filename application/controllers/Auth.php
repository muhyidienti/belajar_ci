<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function index(){
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if($this->form_validation->run() == false){
            

            $this->load->view('template_auth/header');
            $this->load->view('auth/index');
            $this->load->view('template_auth/footer');
        }else{
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $user = $this->db->get_where('user', ['email' => $email])->row_array();

            //cek jika emailnya ada
            if($user){
               //cek  jika emailnya aktif
               if($user['is_active'] == 1){

               }else{
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                Sorry your email not active!
                </div>');
                 redirect('auth/index');
               }
            }else{
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">
                Sorry your email not found!
                </div>');
                 redirect('auth/index');
            }
            

        }
       
    }

    public function register(){

        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'min_length' => 'min 3 characters!',
            'matches' => 'password not match'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|min_length[3]|matches[password1]');
        if($this->form_validation->run() == false){
            $this->load->view('template_auth/header');
            $this->load->view('auth/register');
            $this->load->view('template_auth/footer');
        }else{
          $data = [
              'name' => htmlspecialchars($this->input->post('name')),
              'email' => htmlspecialchars($this->input->post('email')),
              'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
              'image' => 'default.jpg',
              'role_id' => 2,
              'is_active' => 1,
              'data_created' => time()
          ];

          $this->db->insert('user', $data);
          $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">
                Congralutions! your account has been register, please login.
                </div>');
           redirect('auth/index');
        }
      
    }
}

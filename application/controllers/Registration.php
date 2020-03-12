<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('user_helper');
    }

    public function index() {
        $data['title'] = "Registrasi Semnas";

        $this->load->view('template/header', $data);
        $this->load->view('registration/index');
        $this->load->view('template/footer');
    }

    public function pemakalah() {
        $this->form_validation->set_rules('judultim','judul tim', 'trim|required');
        $this->form_validation->set_rules('namapenulis','nama penulis', 'trim|required');
        $this->form_validation->set_rules('subtema','sub tema', 'trim|required');
        $this->form_validation->set_rules('institusi','institusi', 'trim|required');
        $this->form_validation->set_rules('status','status', 'trim|required');
        $this->form_validation->set_rules('email','email', 'trim|required');
        $this->form_validation->set_rules('notelp','no telphone', 'trim|required');
        $this->form_validation->set_rules('alamat','alamat', 'trim|required');
        if(empty($_FILES['uploadfile']['name'])) {
            $this->form_validation->set_rules('uploadfile', 'document', 'required');
        }

        if($this->form_validation->run() == false) {
            $data['title'] = "Registrasi Pemakalah";

            $this->load->view('template/header', $data);
            $this->load->view('registration/pemakalah');
            $this->load->view('template/footer');
        } else {
            $query = $this->db->query('select * from pemakalah');
            $row = $query->num_rows();

            if($row <> 0) {
                $index = $row + 1; 
            } else {
                $index = 1;
            }

            $create_code = str_pad($index, 3, "0", STR_PAD_LEFT);
            $code = "PSNEKTI".$create_code;

            $upload_makalah = $_FILES['uploadfile']['name'];
            if($upload_makalah) {
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = '2048';
                $config['file_name'] = $this->input->post('namatim');
                $config['upload_path'] = './file';

                $this->load->library('upload', $config);

                if($this->upload->do_upload('uploadfile')) {
                    $makalah = $this->upload->data('file_name');
                } else {
                    $this->upload->display_errors();
                }
            }
            
            $data = [
                'kode_pemakalah'    =>  $code,
                'judul_tim'         =>  htmlspecialchars($this->input->post('judultim')),
                'nama_penulis'      =>  htmlspecialchars($this->input->post('namapenulis')),
                'sub_tema'          =>  htmlspecialchars($this->input->post('subtema')),
                'institusi'         =>  htmlspecialchars($this->input->post('institusi')),
                'status'            =>  htmlspecialchars($this->input->post('status')),
                'email'             =>  htmlspecialchars($this->input->post('email')),
                'no_telp'           =>  htmlspecialchars($this->input->post('notelp')),
                'alamat'            =>  htmlspecialchars($this->input->post('alamat')),
                'nama_file'         =>  $upload_makalah
            ];
            
            $this->db->insert('pemakalah', $data);
            $this->session->set_flashdata('msg_pemakalah', 'Dilakukan');
            redirect('registration/pemakalah');
        }
    }

    private function _generatecode($length) {
        $str_result = "0123456789";
        return substr(str_shuffle($str_result), 0, $length);
    }

    public function getregist() {
        $this->form_validation->set_rules('namalengkap', 'Nama Lengkap', 'trim|required', 
            ['required' => 'Nama Lengkap Harus Diisi']
        );
        $this->form_validation->set_rules('nomorinduk', 'Nomor Induk', 'trim|required',
            ['required' => 'Nama Induk Harus Diisi']
        );
        $this->form_validation->set_rules('asalinstansi', 'Asal Instansi', 'trim|required',
            ['required' => 'Asal Instansi Harus Diisi']
        );
        $this->form_validation->set_rules('jeniskelamin', 'Jenis Kelamin', 'trim|required',
            ['required' => 'Jenis Kelamin Harus Diisi']
        );
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', 
            ['required' => 'Email Harus Diisi']
        );
        $this->form_validation->set_rules('notelp', 'No Telepon/HP', 'trim|required|numeric',
            ['required' => 'No Telephone Harus Diisi']
        );

        if($this->form_validation->run() == false) {
            $data['title'] = "Registrasi Semnas";
            
            $this->load->view('template/header', $data);
            $this->load->view('registration/index');
            $this->load->view('template/footer');
        } else {
            $kode = $this->_generatecode(5);
            $data = $this->db->query('select * from registrasi where kode='. $kode);
            $count = $data->num_rows();

            if( $count < 1 ) {
                $code = $kode;
            }
            // $code = _generatecode();
            // echo $code;
            // die;

            // $query = $this->db->get('registrasi')->result_array();
            // $row = $query->num_rows();

            // if($row <> 0) {
            //     $index = $row + 1; 
            // } else {
            //     $index = 1;
            // }

            // $create_code = str_pad($index, 4, "0", STR_PAD_LEFT);
            // $code = "SNEKTI".$create_code;
            
            $data = [
                'kode'          =>  $code,
                'nama_lengkap'  =>  htmlspecialchars($this->input->post('namalengkap')),
                'nomor_induk'   =>  htmlspecialchars($this->input->post('nomorinduk')),
                'asal_instansi' =>  htmlspecialchars($this->input->post('asalinstansi')),
                'jenis_kelamin' =>  htmlspecialchars($this->input->post('jeniskelamin')),
                'email'         =>  htmlspecialchars($this->input->post('email')),
                'no_telp'       =>  htmlspecialchars($this->input->post('notelp')),
                'status_bayar'  =>  "0"
            ];
            
            $this->db->insert('registrasi', $data);
            $this->session->set_flashdata('msg_semnas', 'Dilakukan');
            redirect('registration');
        }
    }

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('user_helper');
    }

    public function index() {
        if(!$this->session->userdata('username')) {
            redirect('auth');
        }

        $url_css = [];
        $url_js = [];
        $data['title'] = "Dashboard";
        $data['c_title'] = "Dashboard";
        $data['file_css'] = $url_css;
        $data['file_js'] = $url_js;

        $content['c_title'] = "Dashboard";

        // seminar nasional
        $content['jumlah_peserta_semnas'] = $this->db->count_all_results('registrasi');
        $content['peserta_belum_bayar'] = $this->db->where('status_bayar', 0)->count_all_results('registrasi');
        $content['kehadiran_peserta_semnas'] = $this->db->where('status_kehadiran', 0)->count_all_results('registrasi');
        $content['ketidakhadiran_peserta_semnas'] = $this->db->where('status_kehadiran', 1)->count_all_results('registrasi');

        // call for paper
        $content['jumlah_tim'] = $this->db->count_all_results('pemakalah');

        $this->load->view('admin_template/header', $data);
        $this->load->view('admin/index', $content);
        $this->load->view('admin_template/footer');
    }

    public function semnas($request) {
        switch($request) {
            case 'peserta' :
                $url_css = ['assets2/plugins/datatables-bs4/css/dataTables.bootstrap4.css'];
                $url_js = [
                    'assets2/plugins/datatables/jquery.dataTables.js',
                    'assets2/plugins/datatables-bs4/js/dataTables.bootstrap4.js'
                ];
        
                $data['title'] = "Seminar Nasional";
                $data['c_title'] = "Data Peserta Seminar Nasional";
                $data['file_css'] = $url_css;
                $data['file_js'] = $url_js;
        
                $content['c_title'] = "Data Peserta Seminar Nasional";
        
                $this->load->model('SemnasModel');
                $content['data_semnas'] = $this->SemnasModel->getAllData();
        
                $this->load->view('admin_template/header', $data);
                $this->load->view('semnas/index', $content);
                $this->load->view('admin_template/footer');
                break;

            case 'pembayaran' :
                $url_css = ['assets2/plugins/datatables-bs4/css/dataTables.bootstrap4.css'];
                $url_js = [
                    'assets2/plugins/datatables/jquery.dataTables.js',
                    'assets2/plugins/datatables-bs4/js/dataTables.bootstrap4.js'
                ];
        
                $data['title'] = "Seminar Nasional";
                $data['c_title'] = "Data Pembayaran Seminar Nasional";
                $data['file_css'] = $url_css;
                $data['file_js'] = $url_js;
        
                $content['c_title'] = "Data Pembayaran Seminar Nasional";
        
                $this->load->model('SemnasModel');
                $content['data_bayar'] = $this->SemnasModel->getAllDataBelumBayar();
        
                $this->load->view('admin_template/header', $data);
                $this->load->view('semnas/pembayaran', $content);
                $this->load->view('admin_template/footer');
                break;
        }
    }

    public function editsemnas() {
        $kode = $this->input->post('kode');

        $data = [
            'nama_lengkap'  => $this->input->post('nm_lengkap'),
            'nomor_induk'   => $this->input->post('nmr_induk'),
            'asal_instansi' => $this->input->post('asal_instansi'),
            'jenis_kelamin' => $this->input->post('jns_kelamin'),
            'email'         => $this->input->post('email'),
            'no_telp'       => $this->input->post('no_telp')
        ];

        $this->db->where('kode', $kode);
        $this->db->update('registrasi', $data);
        $this->session->set_flashdata('message', 'Diubah');
        redirect('admin/semnas');
    }

    public function deletesemnas($kode) {
        $this->db->delete('registrasi', [ 'kode' => $kode ]);
        $this->session->set_flashdata('message', 'Dihapus');
        redirect('admin/semnas/peserta');
    }

    public function verifikasipembayaran() {
        $kode = $this->input->post('kode');

        $config = [
            'protocol'  => 'smtp',  //simple mail transfer protocol
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'noprisigit@gmail.com',
            'smtp_pass' => '19111998',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->load->library('email', $config);
        $this->email->from('no-reply@snekti.com', 'Snekti.com');
        $this->email->to($this->input->post('email'));
        $this->email->subject('SNEKTI 2020');
        $this->email->message("Ini adalah contoh email yang dikirim menggunakan SMTP Gmail pada CodeIgniter.<br><br> Klik <strong><a href='https://masrud.com/post/kirim-email-dengan-smtp-gmail' target='_blank' rel='noopener'>disini</a></strong> untuk melihat tutorialnya.");
        if ($this->email->send()) {
            echo "<script>alert('Sukses! Email terkirim')</script>";
        } else {
            echo "<script>alert('Sukses! Email tidak terkirim')</script>";
        }
        
        $this->db->set('status_bayar', '1');
        $this->db->where('kode', $kode);
        $this->db->update('registrasi');

        $this->session->set_flashdata('message', 'Dilakukan');
        redirect('admin/semnas/pembayaran');
    }

    public function callforpaper($request) {
        switch ($request) {
            case 'peserta':

                $url_css = ['assets2/plugins/datatables-bs4/css/dataTables.bootstrap4.css'];
                $url_js = [
                    'assets2/plugins/datatables/jquery.dataTables.js',
                    'assets2/plugins/datatables-bs4/js/dataTables.bootstrap4.js'
                ];
        
                $data['title'] = "Call For Paper";
                $data['c_title'] = "Data Tim Call For Paper";
                $data['file_css'] = $url_css;
                $data['file_js'] = $url_js;
        
                $content['c_title'] = "Data Tim Call For Paper";

                $this->load->model('PemakalahModel');
                $content['data_makalah'] = $this->PemakalahModel->getAllData();
        
                $this->load->view('admin_template/header', $data);
                $this->load->view('callforpaper/index', $content);
                $this->load->view('admin_template/footer');
                break;
            
            default:
                # code...
                break;
        }
    }

    public function downloadmakalah() {
        $file = $this->input->get('file');
        $this->load->helper('download');
        force_download('file/'.$file, NULL);
    }

    public function materi() {
        $this->form_validation->set_rules('nama_pembicara', 'Nama Pembicara', 'trim|required');
        $this->form_validation->set_rules('judul_materi', 'Judul Materi', 'trim|required');

        if($this->form_validation->run() == false) {
            $url_css = ['assets2/plugins/datatables-bs4/css/dataTables.bootstrap4.css'];
            $url_js = [
                'assets2/plugins/datatables/jquery.dataTables.js',
                'assets2/plugins/datatables-bs4/js/dataTables.bootstrap4.js'
            ];
    
            $data['title'] = "Materi Pembicara";
            $data['c_title'] = "Upload Materi Pembicara";
            $data['file_css'] = $url_css;
            $data['file_js'] = $url_js;
    
            $content['c_title'] = "Upload Materi Pembicara";
    
            $this->load->model('MateriModel');
            $content['data_materi'] = $this->MateriModel->getAllMateri();
    
            $this->load->view('admin_template/header', $data);
            $this->load->view('materi/index', $content);
            $this->load->view('admin_template/footer');   
        } else {
            $nama_pembicara = $this->input->post('nama_pembicara');
            $judul_materi = $this->input->post('judul_materi');
            $materi_pembicara = $_FILES['nama_file']['name'];

            if($materi_pembicara) {
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = '2048';
                $config['file_name'] = $this->input->post('judul_materi');
                $config['upload_path'] = './file/materi';

                $this->load->library('upload', $config);

                if($this->upload->do_upload('nama_file')) {
                    $nama_file = $this->upload->data('file_name');
                } else {
                    $this->upload->display_errors();
                }
            }

            $data = [
                'nama_pemateri' => $nama_pembicara,
                'judul_materi'  => $judul_materi,
                'nama_file'   => $nama_file
            ];

            $this->db->insert('materi', $data);
            $this->session->set_flashdata('message', 'Ditambah');   
            redirect('admin/materi');
        }
    }

    public function editmateri() {
        $this->form_validation->set_rules('nama_pembicara', 'Nama Pembicara', 'trim|required');
        $this->form_validation->set_rules('judul_materi', 'Judul Materi', 'trim|required');

        if($this->form_validation->run() == false) {
            $url_css = ['assets2/plugins/datatables-bs4/css/dataTables.bootstrap4.css'];
            $url_js = [
                'assets2/plugins/datatables/jquery.dataTables.js',
                'assets2/plugins/datatables-bs4/js/dataTables.bootstrap4.js'
            ];
    
            $data['title'] = "Materi Pembicara";
            $data['c_title'] = "Upload Materi Pembicara";
            $data['file_css'] = $url_css;
            $data['file_js'] = $url_js;
    
            $content['c_title'] = "Upload Materi Pembicara";
    
            $this->load->model('MateriModel');
            $content['data_materi'] = $this->MateriModel->getAllMateri();
    
            $this->load->view('admin_template/header', $data);
            $this->load->view('materi/index', $content);
            $this->load->view('admin_template/footer');   
        } else {
            $id_materi = $this->input->post('materiid');
            $nama_pembicara = $this->input->post('nama_pembicara');
            $judul_materi = $this->input->post('judul_materi');
            $file_lama = $this->input->post('nama_file_lama');
            $file_baru = $_FILES['nama_file_baru']['name'];

            if($file_baru) {
                $target = "./file/materi/" . $file_lama;
                if(isset($target)) {
                    unlink($target);
                }

                $config['allowed_types'] = 'pdf';
                $config['max_size'] = '2048';
                $config['file_name'] = $this->input->post('judul_materi');
                $config['upload_path'] = './file/materi';

                $this->load->library('upload', $config);

                if($this->upload->do_upload('nama_file_baru')) {
                    $nama_file = $this->upload->data('file_name');
                } else {
                    $this->upload->display_errors();
                }
            } else {
                $nama_file = $file_lama;
            }

            $data = [
                'nama_pemateri' => $nama_pembicara,
                'judul_materi'  => $judul_materi,
                'nama_file'   => $nama_file
            ];
            
            $this->db->where('id', $id_materi);
            $this->db->update('materi', $data);
            $this->session->set_flashdata('message', 'Diubah');   
            redirect('admin/materi');
        }
    }

    public function deletemateri($id) {
        $this->db->delete('materi', [ 'id' => $id ]);
        $this->session->set_flashdata('message', 'Dihapus');
        redirect('admin/materi');
    }

    public function downloadmateri() {
        $file = $this->input->get('file');
        $this->load->helper('download');
        force_download('file/materi/'.$file, NULL);
    }

    public function users() {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('f_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('l_name', 'Last Name', 'trim|required');
        

        if ($this->form_validation->run() == false) {
            $url_css = [
                'assets2/plugins/datatables-bs4/css/dataTables.bootstrap4.css',
                'assets2/plugins/icheck-bootstrap/icheck-bootstrap.min.css'
            ];
            $url_js = [
                'assets2/plugins/datatables/jquery.dataTables.js',
                'assets2/plugins/datatables-bs4/js/dataTables.bootstrap4.js'
            ];

            $data['title'] = "Manajemen User";
            $data['c_title'] = "Manajamen User";
            $data['file_css'] = $url_css;
            $data['file_js'] = $url_js;

            $this->load->model('UserModel');
            $content['c_title'] = "Manajemen User";
            $content['data_users'] = $this->UserModel->getAllData();

            $this->load->view('admin_template/header', $data);
            $this->load->view('user/index', $content);
            $this->load->view('admin_template/footer');
        } else {
            $data = [
                'username'  =>  $this->input->post('username'),
                'password'  =>  password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'f_name'    =>  $this->input->post('f_name'),
                'l_name'    =>  $this->input->post('l_name'),
                'level'     =>  2,
                'is_active' =>  1
            ];

            $this->db->insert('users', $data);
            $this->session->set_flashdata('message', 'Ditambah');
            redirect('admin/users');
        }
    }

    public function edituser() {
        $this->form_validation->set_rules('newpassword', 'New Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $url_css = [
                'assets2/plugins/datatables-bs4/css/dataTables.bootstrap4.css',
                'assets2/plugins/icheck-bootstrap/icheck-bootstrap.min.css'
            ];
            $url_js = [
                'assets2/plugins/datatables/jquery.dataTables.js',
                'assets2/plugins/datatables-bs4/js/dataTables.bootstrap4.js'
            ];

            $data['title'] = "Manajemen User";
            $data['c_title'] = "Manajamen User";
            $data['file_css'] = $url_css;
            $data['file_js'] = $url_js;

            $this->load->model('UserModel');
            $content['c_title'] = "Manajemen User";
            $content['data_users'] = $this->UserModel->getAllData();

            $this->load->view('admin_template/header', $data);
            $this->load->view('user/index', $content);
            $this->load->view('admin_template/footer');
        } else {
            $data = [
                'username'  =>  $this->input->post('username'),
                'password'  =>  password_hash($this->input->post('newpassword'), PASSWORD_DEFAULT),
                'f_name'    =>  $this->input->post('f_name'),
                'l_name'    =>  $this->input->post('l_name'),
                'level'     =>  2,
                'is_active' =>  1
            ];

            $this->db->where('id', $this->input->post('userid'));
            $this->db->update('users', $data);
            $this->session->set_flashdata('message', 'Diubah');
            redirect('admin/users');
        }
    }

    public function deleteuser($id) {
        $this->db->delete('users', [ 'id' => $id ]);
        $this->session->set_flashdata('message', 'Dihapus');
        redirect('admin/users');
    }

    

	// public function index() {
    //     if(!$this->session->userdata('username')) {
    //         redirect('auth');
    //     }

    //     // $queryTotalPeserta = $this->db->query('select count(kode) from registrasi');
    //     $countPeserta = $this->db->count_all_results('registrasi');
    //     $data['totalpeserta'] = $countPeserta;
        
    //     $countBayar = $this->db->where('status_bayar', 0)->count_all_results('registrasi');
    //     $data['bayar'] = $countBayar;

    //     $data['title'] = 'Dashboard';
    //     $data['header'] = 'Dashboard';
        
    //     $this->load->view('template_admin/admin_header', $data);
    //     $this->load->view('admin/index', $data);
    //     $this->load->view('template_admin/admin_footer');
    // }

    // public function pembayaran() {
    //     $data['title'] = "Data Pembayaran";
    //     $data['header'] = "Pembayaran Seminar Nasional";
    //     $data['registrasi'] = $this->db->get_where('registrasi', ['status_bayar' => 0])->result_array();

    //     $this->load->view('template_admin/admin_header', $data);
    //     $this->load->view('admin/pembayaran', $data);
    //     $this->load->view('template_admin/admin_footer');
    // }

    // public function kehadiran() {
    //     $data['title'] = "Data Kehadiran";
    //     $data['header'] = "Kehadiran Seminar Nasional";

    //     $this->load->view('template_admin/admin_header', $data);
    //     $this->load->view('admin/kehadiran');
    //     $this->load->view('template_admin/admin_footer');
    // }

    // public function addDataPeserta() {
    //     $namalengkap = $this->input->post('namalengkap');
    //     $nomorinduk = $this->input->post('nomorinduk');
    //     $jnskelamin = $this->input->post('jnskelamin');
    //     $asalinstansi = $this->input->post('asalinstansi');
    //     $email = $this->input->post('email');
    //     $notelp = $this->input->post('notelp');
        
    //     $query = $this->db->query('select * from registrasi');
    //     $row = $query->num_rows();

    //     if($row <> 0) {
    //         $index = $row + 1; 
    //     } else {
    //         $index = 1;
    //     }

    //     $create_code = str_pad($index, 4, "0", STR_PAD_LEFT);
    //     $code = "SNEKTI".$create_code;
        
    //     $data = [
    //         'kode'          =>  $code,
    //         'nama_lengkap'  =>  htmlspecialchars($namalengkap),
    //         'nomor_induk'   =>  htmlspecialchars($nomorinduk),
    //         'asal_instansi' =>  htmlspecialchars($asalinstansi),
    //         'jenis_kelamin' =>  htmlspecialchars($jnskelamin),
    //         'email'         =>  htmlspecialchars($email),
    //         'no_telp'       =>  htmlspecialchars($notelp),
    //         'status_bayar'  =>  0
    //     ];
        
    //     $this->db->insert('registrasi', $data);
    //     $this->session->set_flashdata('message', 'Ditambah');
    //     redirect('admin/pendaftaran');
    // }

    // public function updateDataPeserta() {
    //     $kode = $this->input->post('editkode');
    //     $namalengkap = $this->input->post('editnamalengkap');
    //     $nomorinduk = $this->input->post('editnomorinduk');
    //     $jnskelamin = $this->input->post('editjnskelamin');
    //     $asalinstansi = $this->input->post('editasalinstansi');
    //     $email = $this->input->post('editemail');
    //     $notelp = $this->input->post('editnotelp');

    //     $data = [
    //         'nama_lengkap'  =>  htmlspecialchars($namalengkap),
    //         'nomor_induk'   =>  htmlspecialchars($nomorinduk),
    //         'asal_instansi' =>  htmlspecialchars($asalinstansi),
    //         'jenis_kelamin' =>  htmlspecialchars($jnskelamin),
    //         'email'         =>  htmlspecialchars($email),
    //         'no_telp'       =>  htmlspecialchars($notelp),
    //     ];

    //     $this->db->where('kode', $kode);
    //     $this->db->update('registrasi', $data);
    //     $this->session->set_flashdata('message', 'Diubah');
    //     redirect('admin/pendaftaran');
    // }

    // public function deleteDataPeserta($kode) {
    //     $this->db->delete('registrasi', [ 'kode' => $kode]);
    //     $this->session->set_flashdata('message', 'Dihapus');
    //     redirect('admin/pendaftaran');
    // }

    // public function deleteDataPemakalah($kode) {
    //     $this->db->delete('pemakalah', ['kode_pemakalah' => $kode]);
    //     $this->session->set_flashdata('message', 'Dihapus');
    //     redirect('admin/pemakalah');
    // }

    // public function updateDataUser() {
    //     $id = $this->input->post('id');
    //     $username = $this->input->post('editusername');
    //     $password = $this->input->post('editpassword');
    //     $f_name = $this->input->post('editfirstname');
    //     $l_name = $this->input->post('editlastname');
    //     $level = $this->input->post('editlevel');

    //     $data = [
    //         'username'  =>  $username,
    //         'password'  =>  password_hash($password, PASSWORD_DEFAULT),
    //         'f_name'    =>  $f_name,
    //         'l_name'    =>  $l_name,
    //         'level'     =>  $level
    //     ];

    //     $this->db->where('id', $id);
    //     $this->db->update('users', $data);
    //     $this->session->set_flashdata('msg_user', 'Diubah');
    //     redirect('admin/kelolauser');
    // }

    // public function deleteUser($id) {
    //     $this->db->delete('users', [ 'id' => $id]);
    //     $this->session->set_flashdata('msg_user', 'Dihapus');
    //     redirect('admin/kelolauser');
    // }

    // public function updateBayar() {
    //     $kode = $this->input->post('kode');

    //     $config = [
    //         'protocol'  => 'smtp',  //simple mail transfer protocol
    //         'smtp_host' => 'ssl://smtp.googlemail.com',
    //         'smtp_user' => 'noprisigit@gmail.com',
    //         'smtp_pass' => '19111998',
    //         'smtp_port' => 465,
    //         'mailtype'  => 'html',
    //         'charset'   => 'utf-8',
    //         'newline'   => "\r\n"
    //     ];

    //     $this->load->library('email', $config);
    //     $this->email->from('no-reply@snekti.com', 'Snekti.com');
    //     $this->email->to($this->input->post('email'));
    //     $this->email->subject('SNEKTI 2020');
    //     $this->email->message("Ini adalah contoh email yang dikirim menggunakan SMTP Gmail pada CodeIgniter.<br><br> Klik <strong><a href='https://masrud.com/post/kirim-email-dengan-smtp-gmail' target='_blank' rel='noopener'>disini</a></strong> untuk melihat tutorialnya.");
    //     if ($this->email->send()) {
    //         echo "<script>alert('Sukses! Email terkirim')</script>";
    //     } else {
    //         echo "<script>alert('Sukses! Email tidak terkirim')</script>";
    //     }
        
    //     $this->db->set('status_bayar', '1');
    //     $this->db->where('kode', $kode);
    //     $this->db->update('registrasi');

    //     $this->session->set_flashdata('flash', 'Diubah');
    //     redirect('admin/pembayaran');
    // }

    // public function downloadFile() {
    //     $file = $this->input->get('file');
    //     $this->load->helper('download');
    //     force_download('file/'.$file, NULL);
    // }    

    // public function uploadMateri() {
    //     $this->form_validation->set_rules('nama_pembicara', 'Nama Pembicara', 'trim|required',
    //         ['required' => 'Nama Pembicara Harus Diisi']
    //     );
    //     $this->form_validation->set_rules('judul_materi', 'Judul Materi', 'trim|required',
    //         ['required' => 'Judul Materi Harus Diisi']
    //     );
    //     if(empty($_FILES['materi_pembicara']['name'])) {
    //         $this->form_validation->set_rules('materi_pembicara', 'Materi Pembicara', 'required',
    //             ['required' => 'Materi Pembicara Harus Diisi']
    //         );
    //     }

    //     if($this->form_validation->run() == false) {
    //         $data['title'] = "Upload Materi";
    //         $data['header'] = "Upload Materi Seminar Nasional";
    //         $data['materi'] = $this->db->get('materi')->result_array();

    //         $this->load->view('template_admin/admin_header', $data);
    //         $this->load->view('admin/upload_materi', $data);
    //         $this->load->view('template_admin/admin_footer');
    //     } else {
    //         $nama_pembicara = $this->input->post('nama_pembicara');
    //         $judul_materi = $this->input->post('judul_materi');
    //         $materi_pembicara = $_FILES['materi_pembicara']['name'];

    //         $data = [
    //             'nama_pemateri' => $nama_pembicara,
    //             'judul_materi'  => $judul_materi,
    //             'nama_file'   => $materi_pembicara
    //         ];

    //         $this->db->insert('materi', $data);
    //         $this->session->set_flashdata('message', 'Ditambah');   
    //         redirect('admin/uploadMateri');
    //     }
    // }
}

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Doctor extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('doctor_model');

        $this->load->model('department/department_model');
        $this->load->model('finance/finance_model');
        $this->load->model('appointment/appointment_model');
        $this->load->model('patient/patient_model');
        $this->load->model('prescription/prescription_model');
        $this->load->model('schedule/schedule_model');
        $this->load->model('doctorvisit/doctorvisit_model');
        $this->load->model('hospital/hospital_model');
        $this->load->module('patient');
        $this->load->module('sms');
        if (!$this->ion_auth->in_group(array('admin', 'superadmin', 'Accountant', 'Doctor', 'Receptionist', 'Nurse', 'Laboratorist', 'Patient', 'onlinecenter', 'casetaker'))) {
            redirect('home/permission');
        }
    }

    public function index()
    {

        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['departments'] = $this->department_model->getDepartment();
        $this->load->view('home/dashboard');
        $this->load->view('doctor', $data);
        $this->load->view('home/footer');
    }

    public function addNewView()
    {
        $data = array();
        $data['departments'] = $this->department_model->getDepartment();
        $this->load->view('home/dashboard');
        $this->load->view('add_new', $data);
        $this->load->view('home/footer');
    }

    public function addNew()
    {

        $id = $this->input->post('id');
        if (!$this->ion_auth->in_group(array('superadmin'))) {
            if (empty($id)) {
                $limit = $this->doctor_model->getLimit();
                if ($limit <= 0) {
                    $this->session->set_flashdata('feedback', lang('doctor_limit_exceed'));
                    redirect('doctor');
                }
            }
        }
        if ($this->ion_auth->in_group(array('superadmin'))) {
            $hospital_id = $this->input->post('hospital_id');
        } else {
            $hospital_id = $this->session->userdata('hospital_id');
        }
        $name = $this->input->post('name');
        $password = $this->input->post('password');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $department = $this->input->post('department');
        $profile = $this->input->post('profile');
        $new_visit = $this->input->post('new_visit');
        $old_visit = $this->input->post('old_visit');
        $new_visit_with_medicine = $this->input->post('new_visit_with_medicine');
        $old_visit_with_medicine = $this->input->post('old_visit_with_medicine');
        $chamber_address = $this->input->post('chamber_address');
        $description = $this->input->post('description');
        $registration_issue_institution_name = $this->input->post('registration_issue_institution_name');
        $registration_number = $this->input->post('registration_number');
        $registration_expiry_date = $this->input->post('registration_expiry_date');
        $registration_certificate = $this->input->post('registration_certificate');
        $nid_no = $this->input->post('nid_no');
        $nid_certificate = $this->input->post('nid_certificate');
        $about_baseurl = $this->input->post('about_baseurl');
        $about_title = $this->input->post('about_title');

        $country = $this->input->post('country');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // Validating Name Field
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        // Validating Password Field
        if (empty($id)) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[100]|xss_clean');
        }
        // Validating Email Field
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[5]|max_length[100]|xss_clean');
        // Validating Address Field   
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[1]|max_length[500]|xss_clean');
        // Validating Phone Field           
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[1]|max_length[50]|xss_clean');
        // Validating Department Field   
        $this->form_validation->set_rules('department', 'Department', 'trim|min_length[1]|max_length[500]|xss_clean');
        // Validating Phone Field           
        $this->form_validation->set_rules('profile', 'Profile', 'trim|required|min_length[1]|max_length[5000]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            if (!empty($id)) {
                $data = array();
                $data['departments'] = $this->department_model->getDepartment();
                $data['doctor'] = $this->doctor_model->getDoctorById($id);
                $this->load->view('home/dashboard');
                $this->load->view('add_new', $data);
                $this->load->view('home/footer');
            } else {
                $data = array();
                $data['setval'] = 'setval';
                $data['departments'] = $this->department_model->getDepartment();
                $this->load->view('home/dashboard');
                $this->load->view('add_new', $data);
                $this->load->view('home/footer');
            }
        } else {
            $file_name = $_FILES['img_url']['name'];
            $file_name_pieces = explode('_', $file_name);
            $new_file_name = '';
            $count = 1;
            foreach ($file_name_pieces as $piece) {
                if ($count !== 1) {
                    $piece = ucfirst($piece);
                }

                $new_file_name .= $piece;
                $count++;
            }
            $config = array(
                'file_name' => $new_file_name,
                'upload_path' => "./uploads/",
                'allowed_types' => "gif|jpg|png|jpeg|pdf",
                'overwrite' => False,
                'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                'max_height' => "1768",
                'max_width' => "2024"
            );

            $this->load->library('Upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('img_url')) {
                $path = $this->upload->data();
                $img_url = "uploads/" . $path['file_name'];
                $data = array();
                $data = array(
                    'img_url' => $img_url,
                    'name' => $name,
                    'email' => $email,
                    'address' => $address,
                    'hospital_id' => $hospital_id,
                    'phone' => $phone,
                    'department' => $department,
                    'profile' => $profile,
                    'new_visit' => $new_visit,
                    'old_visit' => $old_visit,
                    'new_visit_with_medicine' => $new_visit_with_medicine,
                    'old_visit_with_medicine' => $old_visit_with_medicine,
                    'registration_issue_institution_name' => $registration_issue_institution_name,
                    'registration_number' => $registration_number,
                    'registration_expiry_date' => $registration_expiry_date,
                    'nid_no' => $nid_no,
                    'country' => $country,
                    'chamber_address' => $chamber_address,
                    'description' => $description,
                    'about_baseurl' => $about_baseurl,
                    'about_title' => $about_title
                );
            } else {

                $data = array();
                $data = array(
                    'name' => $name,
                    'email' => $email,
                    'address' => $address,
                    'hospital_id' => $hospital_id,
                    'phone' => $phone,
                    'department' => $department,
                    'profile' => $profile,
                    'new_visit' => $new_visit,
                    'old_visit' => $old_visit,
                    'new_visit_with_medicine' => $new_visit_with_medicine,
                    'old_visit_with_medicine' => $old_visit_with_medicine,
                    'registration_issue_institution_name' => $registration_issue_institution_name,
                    'registration_number' => $registration_number,
                    'registration_expiry_date' => $registration_expiry_date,
                    'nid_no' => $nid_no,
                    'country' => $country,
                    'chamber_address' => $chamber_address,
                    'description' => $description,
                    'about_baseurl' => $about_baseurl,
                    'about_title' => $about_title
                );
            }


            $username = $this->input->post('name');
            if (empty($id)) {     // Adding New Doctor
                if ($this->ion_auth->email_check($email)) {
                    $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));
                    redirect('doctor/addNewView');
                } else {
                    $dfg = 4;
                    $this->ion_auth->register($username, $password, $email, $dfg);
                    $ion_user_id = $this->db->get_where('users', array('email' => $email))->row()->id;
                    if ($this->ion_auth->in_group(array('superadmin'))) {
                        $this->doctor_model->insertDoctorBySuperadmin($data);
                    } else {
                        $this->doctor_model->insertDoctor($data);
                    }
                    $inserted_id = $this->db->insert_id();
                    $commission = $this->settings_model->getCommissionSettings();
                    $commission_data = array();
                    $commission_data = array(
                        'doctor' => $inserted_id,
                        'medicine_fee' => $commission->medicine_fee,
                        'medicine_fee_rupee' => $commission->medicine_fee_rupee,
                        'medicine_fee_dollar' => $commission->medicine_fee_dollar,
                        'medicine_fee_edit' => 1,
                        'medicine_fee_policy' => $commission->medicine_fee_policy,
                        'courier_fee' => $commission->courier_fee,
                        'courier_fee_rupee' => $commission->courier_fee_rupee,
                        'courier_fee_dollar' => $commission->courier_fee_dollar,
                        'courier_fee_edit' => 1,
                        'courier_fee_policy' => $commission->courier_fee_policy,
                        'casetaker_fee' => $commission->casetaker_fee,
                        'casetaker_fee_rupee' => $commission->casetaker_fee_rupee,
                        'casetaker_fee_dollar' => $commission->casetaker_fee_dollar,
                        'casetaker_fee_edit' => 1,
                        'casetaker_fee_policy' => $commission->casetaker_fee_policy,
                        'onlinecenter_fee' => $commission->onlinecenter_fee,
                        'onlinecenter_fee_rupee' => $commission->onlinecenter_fee_rupee,
                        'onlinecenter_fee_dollar' => $commission->onlinecenter_fee_dollar,
                        'onlinecenter_fee_edit' => 1,
                        'onlinecenter_fee_policy' => $commission->onlinecenter_fee_policy,
                        'developer_fee' => $commission->developer_fee,
                        'developer_fee_rupee' => $commission->developer_fee_rupee,
                        'developer_fee_dollar' => $commission->developer_fee_dollar,
                        'developer_fee_edit' => ' ',
                        'developer_fee_policy' => $commission->developer_fee_policy,
                        'current_hospital' => $commission->current_hospital,
                        'current_hospital_rupee' => $commission->current_hospital_rupee,
                        'current_hospital_dollar' => $commission->current_hospital_dollar,
                        'current_hospital_fee_edit' => ' ',
                        'current_hospital_fee_policy' => $commission->current_hospital_fee_policy,
                        'foreign_hospital' => $commission->foreign_hospital,
                        'foreign_hospital_rupee' => $commission->foreign_hospital_rupee,
                        'foreign_hospital_dollar' => $commission->foreign_hospital_dollar,
                        'foreign_hospital_fee_edit' => ' ',
                        'foreign_hospital_fee_policy' => $commission->foreign_hospital_fee_policy,
                        'superadmin_fee' => $commission->superadmin_fee,
                        'superadmin_fee_rupee' => $commission->superadmin_fee_rupee,
                        'superadmin_fee_dollar' => $commission->superadmin_fee_dollar,
                        'superadmin_fee_edit' => ' ',
                        'superadmin_fee_policy' => $commission->superadmin_fee_policy,
                        'doctor_login_fee' => $commission->doctor_login_fee,
                        'doctor_login_fee_rupee' => $commission->doctor_login_fee_rupee,
                        'doctor_login_fee_dollar' => $commission->doctor_login_fee_dollar,
                        'doctor_login_fee_edit' => ' ',
                        'doctor_login_fee_policy' => $commission->doctor_login_fee_policy,
                        'visit_charge_edit' => 1,
                        'visit_des_edit' => 1,
                        'courier_fee_applicable_edit' => 1,

                        'custom_medical_board_visit_charges' => $commission->custom_medical_board_visit_charges,
                        'custom_medical_board_visit_charges_rupee' => $commission->custom_medical_board_visit_charges_rupee,
                        'custom_medical_board_visit_charges_dollar' => $commission->custom_medical_board_visit_charges_dollar,
                        'custom_medical_board_visit_charges_edit' => 1,
                        'custom_medical_board_visit_charges_policy' => $commission->custom_medical_board_visit_charges_policy,

                        'individual_medical_board_visit_charges' => $commission->individual_medical_board_visit_charges,
                        'individual_medical_board_visit_charges_rupee' => $commission->individual_medical_board_visit_charges_rupee,
                        'individual_medical_board_visit_charges_dollar' => $commission->individual_medical_board_visit_charges_dollar,
                        'individual_medical_board_visit_charges_edit' => ' ',
                        'individual_medical_board_visit_charges_policy' => $commission->individual_medical_board_visit_charges_policy,
                    );
                    if ($this->ion_auth->in_group(array('superadmin'))) {
                        $this->doctor_model->insertDoctorCommissionBySuperadmin($data);
                    } else {
                        $this->doctor_model->insertDoctorCommission($commission_data);
                    }

                    $data4 = array();
                    $file_name = $_FILES['registration_certificate']['name'];
                    $file_name_pieces = explode('_', $file_name);
                    $new_file_name = '';
                    $count = 1;
                    foreach ($file_name_pieces as $piece) {
                        if ($count !== 1) {
                            $piece = ucfirst($piece);
                        }

                        $new_file_name .= $piece;
                        $count++;
                    }
                    $config = array(
                        'file_name' => $new_file_name,
                        'upload_path' => "./uploads/",
                        'allowed_types' => "gif|jpg|png|jpeg|pdf",
                        'overwrite' => False,
                        'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                        'max_height' => "10000",
                        'max_width' => "10000"
                    );

                    $this->load->library('Upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('registration_certificate')) {
                        $path = $this->upload->data();
                        $registration_certificate = "uploads/" . $path['file_name'];

                        $data4 = array(
                            'registration_certificate' => $registration_certificate
                        );
                        $this->doctor_model->updateDoctor($inserted_id, $data4);
                    }

                    $data3 = array();
                    $file_name = $_FILES['nid_certificate']['name'];
                    $file_name_pieces = explode('_', $file_name);
                    $new_file_name = '';
                    $count = 1;
                    foreach ($file_name_pieces as $piece) {
                        if ($count !== 1) {
                            $piece = ucfirst($piece);
                        }

                        $new_file_name .= $piece;
                        $count++;
                    }
                    $config = array(
                        'file_name' => $new_file_name,
                        'upload_path' => "./uploads/",
                        'allowed_types' => "gif|jpg|png|jpeg|pdf",
                        'overwrite' => False,
                        'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                        'max_height' => "10000",
                        'max_width' => "10000"
                    );

                    $this->load->library('Upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('nid_certificate')) {
                        $path = $this->upload->data();
                        $nid_certificate = "uploads/" . $path['file_name'];

                        $data3 = array(
                            'nid_certificate' => $nid_certificate
                        );
                        $this->doctor_model->updateDoctor($inserted_id, $data3);
                    }

                    $doctor_user_id = $this->db->get_where('doctor', array('email' => $email))->row()->id;
                    $id_info = array('ion_user_id' => $ion_user_id);
                    $this->doctor_model->updateDoctor($doctor_user_id, $id_info);
                    $this->hospital_model->addHospitalIdToIonUser($ion_user_id, $this->hospital_id);
                    $base_url = str_replace(array('http://', 'https://', ' '), '', base_url()) . "auth/login";
                    //sms
                    $set['settings'] = $this->settings_model->getSettings();
                    $autosms = $this->sms_model->getAutoSmsByType('doctor');
                    $message = $autosms->message;
                    $to = $phone;
                    $name1 = explode(' ', $name);
                    if (!isset($name1[1])) {
                        $name1[1] = null;
                    }
                    $data1 = array(
                        'firstname' => $name1[0],
                        'lastname' => $name1[1],
                        'name' => $name,
                        'base_url' => $base_url,
                        'email' => $email,
                        'password' => $password,
                        'department' => $department,
                        'company' => $set['settings']->system_vendor
                    );

                    if ($autosms->status == 'Active') {
                        $messageprint = $this->parser->parse_string($message, $data1);
                        $data2[] = array($to => $messageprint);
                        // $this->sms->sendSms($to, $message, $data2);
                        $this->sms->sendSmsByCountry($to, $message, $data2, $country, $hospital_id);
                    }
                    //end
                    //email

                    $autoemail = $this->email_model->getAutoEmailByType('doctor');
                    if ($autoemail->status == 'Active') {
                        $mail_provider = $this->settings_model->getSettings()->emailtype;
                        $settngs_name = $this->settings_model->getSettings()->system_vendor;
                        $email_Settings = $this->email_model->getEmailSettingsByType($mail_provider);
                        $message1 = $autoemail->message;
                        $messageprint1 = $this->parser->parse_string($message1, $data1);
                        if ($mail_provider == 'Domain Email') {
                            $this->email->from($email_Settings->admin_email);
                        }
                        if ($mail_provider == 'Smtp') {
                            $this->email->from($email_Settings->user, $settngs_name);
                        }
                        $this->email->to($email);
                        $this->email->subject('Registration confirmation');
                        $this->email->message($messageprint1);
                        $this->email->send();
                    }

                    //end


                    $this->session->set_flashdata('feedback', lang('added'));
                }
            } else { // Updating Doctor
                $ion_user_id = $this->db->get_where('doctor', array('id' => $id))->row()->ion_user_id;
                if (empty($password)) {
                    $password = $this->db->get_where('users', array('id' => $ion_user_id))->row()->password;
                } else {
                    $password = $this->ion_auth_model->hash_password($password);
                }
                $this->doctor_model->updateIonUser($username, $email, $password, $ion_user_id);
                $this->doctor_model->updateDoctor($id, $data);
                $data4 = array();
                $file_name = $_FILES['registration_certificate']['name'];
                $file_name_pieces = explode('_', $file_name);
                $new_file_name = '';
                $count = 1;
                foreach ($file_name_pieces as $piece) {
                    if ($count !== 1) {
                        $piece = ucfirst($piece);
                    }

                    $new_file_name .= $piece;
                    $count++;
                }
                $config = array(
                    'file_name' => $new_file_name,
                    'upload_path' => "./uploads/",
                    'allowed_types' => "gif|jpg|png|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('registration_certificate')) {
                    $path = $this->upload->data();
                    $registration_certificate = "uploads/" . $path['file_name'];

                    $data4 = array(
                        'registration_certificate' => $registration_certificate
                    );
                    $this->doctor_model->updateDoctor($id, $data4);
                }

                $data3 = array();
                $file_name = $_FILES['nid_certificate']['name'];
                $file_name_pieces = explode('_', $file_name);
                $new_file_name = '';
                $count = 1;
                foreach ($file_name_pieces as $piece) {
                    if ($count !== 1) {
                        $piece = ucfirst($piece);
                    }

                    $new_file_name .= $piece;
                    $count++;
                }
                $config = array(
                    'file_name' => $new_file_name,
                    'upload_path' => "./uploads/",
                    'allowed_types' => "gif|jpg|png|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('nid_certificate')) {
                    $path = $this->upload->data();
                    $nid_certificate = "uploads/" . $path['file_name'];

                    $data3 = array(
                        'nid_certificate' => $nid_certificate
                    );
                    $this->doctor_model->updateDoctor($id, $data3);
                }
                $this->session->set_flashdata('feedback', lang('updated'));
            }
            // Loading View
            if ($this->ion_auth->in_group(array('superadmin'))) {
                redirect('superadmin/doctor');
            } else {
                redirect('doctor');
            }
        }
    }

    function editDoctor()
    {
        $data = array();
        $data['departments'] = $this->department_model->getDepartment();
        $id = $this->input->get('id');
        $data['doctor'] = $this->doctor_model->getDoctorById($id);
        $this->load->view('home/dashboard');
        $this->load->view('add_new', $data);
        $this->load->view('home/footer');
    }

    function details()
    {

        $data = array();

        if ($this->ion_auth->in_group(array('Doctor'))) {
            $data['patient'] = $this->input->get('id');
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $id = $this->doctor_model->getDoctorByIonUserId($doctor_ion_id)->id;
            $data['doctor'] = $this->doctor_model->getDoctorById($id);
            $data['todays_appointments'] = $this->appointment_model->getAppointmentByDoctorByToday($id);
            $data['appointments'] = $this->appointment_model->getAppointmentByDoctor($id);
            $data['patients'] = $this->patient_model->getPatient();
            $data['appointment_patients'] = $this->patient->getPatientByAppointmentByDctorId($id);
            $data['doctors'] = $this->doctor_model->getDoctor();
            $data['prescriptions'] = $this->prescription_model->getPrescriptionByDoctorId($id);
            $data['holidays'] = $this->schedule_model->getHolidaysByDoctor($id);
            $data['schedules'] = $this->schedule_model->getScheduleByDoctor($id);

            $this->load->view('home/dashboard');
            $this->load->view('details', $data);
            $this->load->view('home/footer');
        } else {
            redirect('home');
        }
    }

    function editDoctorByJason()
    {
        $id = $this->input->get('id');
        $data['doctor'] = $this->doctor_model->getDoctorById($id);
        echo json_encode($data);
    }

    function delete()
    {

        if ($this->ion_auth->in_group(array('Patient'))) {
            redirect('home/permission');
        }

        $data = array();
        $id = $this->input->get('id');
        $user_data = $this->db->get_where('doctor', array('id' => $id))->row();
        $path = $user_data->img_url;

        if (!empty($path)) {
            unlink($path);
        }
        $ion_user_id = $user_data->ion_user_id;
        $this->db->where('id', $ion_user_id);
        $this->db->delete('users');
        $this->doctor_model->delete($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('doctor');
    }

    function getDoctor()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        if ($limit == -1) {
            if (!empty($search)) {
                $data['doctors'] = $this->doctor_model->getDoctorBysearch($search);
            } else {
                $data['doctors'] = $this->doctor_model->getDoctor();
            }
        } else {
            if (!empty($search)) {
                $data['doctors'] = $this->doctor_model->getDoctorByLimitBySearch($limit, $start, $search);
            } else {
                $data['doctors'] = $this->doctor_model->getDoctorByLimit($limit, $start);
            }
        }

        $i = 0;
        foreach ($data['doctors'] as $doctor) {
            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist', 'superadmin'))) {
                $options1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="doctor/editDoctor?id=' . $doctor->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';
            }
            $options2 = '<a class="btn btn-info btn-xs detailsbutton" title="' . lang('appointments') . '"  href="appointment/getAppointmentByDoctorId?id=' . $doctor->id . '"> <i class="fa fa-calendar"> </i> ' . lang('appointments') . '</a>';
            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {
                $options3 = '<a class="btn btn-info btn-xs btn_width delete_button" title="' . lang('delete') . '" href="doctor/delete?id=' . $doctor->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i> ' . lang('delete') . '</a>';
            }



            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist', 'superadmin'))) {
                $options4 = '<a href="schedule/holidays?doctor=' . $doctor->id . '" class="btn btn-info btn-xs btn_width" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-book"></i> ' . lang('holiday') . '</a>';
                $options5 = '<a href="schedule/timeSchedule?doctor=' . $doctor->id . '" class="btn btn-info btn-xs btn_width" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-book"></i> ' . lang('time_schedule') . '</a>';
                $options6 = '<a type="button" class="btn btn-info btn-xs btn_width detailsbutton inffo" title="' . lang('info') . '" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-info"> </i> ' . lang('info') . '</a>';
            }
            $i = $i + 1;
            $info[] = array(
                $doctor->id,
                $doctor->name,
                $doctor->email,
                $doctor->phone,
                $doctor->country,
                $doctor->department,
                $doctor->profile,
                $options6 . ' ' . $options1 . ' ' . $options2 . ' ' . $options4 . ' ' . $options5 . ' ' . $options3,
            );
        }

        if (!empty($data['doctors'])) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => $i,
                "recordsFiltered" => $i,
                "data" => $info
            );
        } else {
            $output = array(
                // "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }

    public function getDoctorInfo()
    {
        // Search term
        $searchTerm = $this->input->post('searchTerm');
        $hospital_id = $this->input->get('hospital_id');
        // Get users
        //    if ($this->ion_auth->in_group(array('casetaker', 'onlinecenter'))) {
        //     // $response = $this->doctor_model->getAllDoctor();
        //        $id = $this->input->get('id');
        //        $data['patient'] = $this->patient_model->getPatientById($id);
        //        $response = $this->doctor_model->getDoctorByPatientHospitalId($data['patient']->hospital_id);
        //    } else {
        $response = $this->doctor_model->getDoctorInfo($searchTerm, $hospital_id);
        //    }


        echo json_encode($response);
    }

    public function getDoctorWithAddNewOption()
    {
        // Search term
        $id = $this->input->post('id');
        $searchTerm = $this->input->post('searchTerm');
        //        $hospital_id = $this->patient_model->getPatientByOnlinecenter($id)->hospital_id;
        //$hospital_id = $this->db->get_where('patient', array('id' => $payment->patient))->row()->hospital_id;
        //$hospital_id = 1;
        // Get users
        $response = $this->doctor_model->getDoctorWithAddNewOptionget($searchTerm);
        //         $response = $this->doctor_model->getDoctorWithAddNewOptionByOnlinecenter($hospital_id, $searchTerm);

        echo json_encode($response);
    }

    public function getDoctorsByHospital()
    {
        $hospital_id = $this->input->get('hospital_id');
    }

    function doctorHistory()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $data['settings'] = $this->settings_model->getSettings();
        $date_from = strtotime($this->input->post('date_from'));
        $date_to = strtotime($this->input->post('date_to'));
        if (!empty($date_to)) {
            $date_to = $date_to + 86399;
        }
        //        $casetaker = $this->input->post('casetaker');
        $doctor = $this->input->post('doctor');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        //        $data['casetaker_select'] = $casetaker;
        $data['doctor_select'] = $doctor;
        //        if ($this->ion_auth->in_group(array('casetaker'))) {
        //        }
        $casetaker_ion_id = $this->ion_auth->get_user_id();
        $casetaker_id = $this->casetaker_model->getCasetakerByIonUserId($casetaker_ion_id)->id;
        $onlinecenter_ion_id = $this->ion_auth->get_user_id();
        $onlinecenter_id = $this->onlinecenter_model->getOnlinecenterByIonUserId($onlinecenter_ion_id)->id;
        $data['doctors'] = $this->doctor_model->getAllDoctor();
        $data['casetakers'] = $this->casetaker_model->getCasetakerByOnlinecenter($onlinecenter_id);

        if (!empty($date_from)) {
            if ($this->ion_auth->in_group(array('onlinecenter'))) {

                if ($doctor == 'all') {
                    $data['appointments'] = $this->appointment_model->getAppointmentByDateByOnlinecenter($onlinecenter_id, strtotime($date_from), strtotime($date_to));
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentByDateByOnlinecenterByDoctor($doctor, $onlinecenter_id, strtotime($date_from), strtotime($date_to));
                }
                if ($doctor == 'all') {
                    $data['payments'] = $this->finance_model->getPaymentByByDateForOnlinecenterId($onlinecenter_id, strtotime($date_from), strtotime($date_to));
                } else {
                    //                    $data['appointments'] = $this->appointment_model->getAppointmentByDateByOnlinecenterByCasetaker($casetaker,$onlinecenter_id, $date_from, $date_to);
                    $data['payments'] = $this->finance_model->getPaymentByDateForOnlinecenterByDoctor($doctor, $onlinecenter_id, strtotime($date_from), strtotime($date_to));
                }
                if ($doctor == 'all') {
                    $data['deposits'] = $this->finance_model->getDepositByDateByOnlinecenterId($onlinecenter_id, strtotime($date_from), strtotime($date_to));
                } else {
                    $data['deposits'] = $this->finance_model->getDepositByDateByOnlinecenterIdByDoctor($onlinecenter_id, $doctor, strtotime($date_from), strtotime($date_to));
                }
            }
        } else {
            if ($this->ion_auth->in_group(array('onlinecenter'))) {




                if ($doctor == 'all') {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id);
                    $data['payments'] = $this->finance_model->getPaymentForOnlinecenterId($onlinecenter_id);
                    $data['deposits'] = $this->finance_model->getDepositForOnlinecenterId($onlinecenter_id);
                }
                if ($doctor != 'all') {

                    $data['appointments'] = $this->appointment_model->getAppointmentByOnlinecenterByDoctor($doctor, $onlinecenter_id);
                    $data['payments'] = $this->finance_model->getPaymentByOnlinecenterByDoctor($doctor, $onlinecenter_id);
                    $data['deposits'] = $this->finance_model->getDepositByOnlinecenterIdByDoctor($onlinecenter_id, $doctor);
                }
            }
        }


        $this->load->view('home/dashboard');
        $this->load->view('doctor_history', $data);
        $this->load->view('home/footer');
    }
    public function getDoctorVisit()
    {
        $id = $this->input->get('id');
        // $description = $this->input->get('description');
        $first_visits = $this->doctor_model->getDoctorFirstVisitByDoctorId($id);
        $visits = $this->doctor_model->getDoctorVisitByDoctorId($id);
        // $optionn = '<input type="hidden" value="' . $first_visits->id . '">';
        foreach ($visits as $visit) {

            $option .= '<option value="' . $visit->id . '">' . $visit->visit_description . '</option>';
        }
        foreach ($first_visits as $visitt) {

            $optionn =  $visitt->id;
        }
        $data['response'] = $option;
        $data['responsee'] = $optionn;
        echo json_encode($data);
    }
    public function getDoctorVisitCharges()
    {
        $id = $this->input->get('id');
        $data['response'] = $this->doctorvisit_model->getDoctorvisitById($id);


        echo json_encode($data);
    }
    public function getDoctorVisitForEdit()
    {
        $id = $this->input->get('id');
        $description = $this->input->get('description');
        $visits = $this->doctor_model->getDoctorVisitByDoctorId($id);
        $option = '<option value="">' . lang('select') . '</option>';
        foreach ($visits as $visit) {
            if ($visit->id == $description) {
                $option .= '<option value="' . $visit->id . '" selected ="selected">' . $visit->visit_description . '</option>';
            } else {
                $option .= '<option value="' . $visit->id . '">' . $visit->visit_description . '</option>';
            }
        }
        $data['response'] = $option;
        $data['visit_description'] = $option;
        echo json_encode($data);
    }

    function getDoctorVisitingPlace()
    {
        $id = $this->input->get('id');
        $data['doctor'] = $this->doctor_model->getDoctorById($id);
        $visiting_places = ['online', 'chamber'];
        $visiting_place_in_list = explode(",", $data['doctor']->visiting_place);
        $option = '';
        for ($i = 0; $i < count($visiting_places); $i++) {
            if (in_array($visiting_places[$i], $visiting_place_in_list)) {
                if ($visiting_places[$i] == 'online') {
                    $option .= '<div class="col-md-6"><input type="radio" checked id="online" name="visit_type" value="Online Visit"> ' . lang('online_visit') . '<br></div>';
                } elseif ($visiting_places[$i] == 'chamber') {
                    $option .= '<div class="col-md-6"><input type="radio" id="chamber" name="visit_type" value="Chamber Visit"> ' . lang('chamber_visit') . '<br></div>';
                }
                // else {
                //     $option .= '<div class="col-md-6"><i class="fa fa-check"></i> ' . lang($visiting_places[$i]) . '<br></div>';
                // }
            }
        }
        $data['option'] = $option;

        echo json_encode($data);
    }
    function getDoctorVisitingPlaceByAppointmentId()
    {
        $id = $this->input->get('id');
        $appointment_id = $this->input->get('appointment_id');
        $appointment = $this->appointment_model->getAppointmentById($appointment_id)->visit_type;
        $data['doctor'] = $this->doctor_model->getDoctorById($id);
        $visiting_places = ['online', 'chamber'];
        $visiting_place_in_list = explode(",", $data['doctor']->visiting_place);
        $option = '';
        for ($i = 0; $i < count($visiting_places); $i++) {
            if (in_array($visiting_places[$i], $visiting_place_in_list, $appointment)) {
                if ($appointment == 'Online Visit') {
                    if ($visiting_places[$i] == 'online') {

                        $option .= '<div class="col-md-6"><input type="radio" id="online" name="visit_type" value="Online Visit" checked> ' . lang('online_visit') . '<br></div>';
                    } elseif ($visiting_places[$i] == 'chamber') {

                        $option .= '<div class="col-md-6"><input type="radio" id="chamber" name="visit_type" value="Chamber Visit"> ' . lang('chamber_visit') . '<br></div>';
                    }
                }
                if ($appointment == 'Chamber Visit') {
                    if ($visiting_places[$i] == 'online') {

                        $option .= '<div class="col-md-6"><input type="radio" id="online" name="visit_type" value="Online Visit" > ' . lang('online_visit') . '<br></div>';
                    } elseif ($visiting_places[$i] == 'chamber') {

                        $option .= '<div class="col-md-6"><input type="radio" id="chamber" name="visit_type" value="Chamber Visit" checked> ' . lang('chamber_visit') . '<br></div>';
                    }
                }
                if ($appointment == '') {
                    if ($visiting_places[$i] == 'online') {

                        $option .= '<div class="col-md-6"><input type="radio" id="online" name="visit_type" value="Online Visit" > ' . lang('online_visit') . '<br></div>';
                    } elseif ($visiting_places[$i] == 'chamber') {

                        $option .= '<div class="col-md-6"><input type="radio" id="chamber" name="visit_type" value="Chamber Visit"> ' . lang('chamber_visit') . '<br></div>';
                    }
                }
                // else {
                //     $option .= '<div class="col-md-6"><i class="fa fa-check"></i> ' . lang($visiting_places[$i]) . '<br></div>';
                // }
            }
        }
        $data['option'] = $option;

        echo json_encode($data);
    }

    public function getDoctorDetails()
    {
        $id = $this->input->get('id');
        $data['response'] = $this->doctor_model->getDoctorDetailsById($id);


        echo json_encode($data);
    }
    function getDoctorCommissionByJason()
    {
        $id = $this->input->get('id');
        $data['commission'] = $this->doctor_model->getDoctorCommissionSettingsById($id);
        // $data['doctor'] = $this->doctor_model->getDoctorById($data['doctorvisit']->doctor_id);
        echo json_encode($data);
    }
    public function getDoctorListForSelect2()
    {
// Search term
        $searchTerm = $this->input->post('searchTerm');

// Get users
        $response = $this->doctor_model->getBoardDoctoInfo($searchTerm);

        echo json_encode($response);
    }
    function getBoardDoctorByJason()
    {
        $id = $this->input->get('id');
        $data['doctor'] = $this->doctor_model->getDoctorById($id);
        $data['hospital'] = $this->hospital_model->getHospitalById($data['doctor']->hospital_id);
        $data['hospital_category'] = $this->hospital_model->getHospitalCategoryById($data['hospital']->category);
        echo json_encode($data);
    }

    function getRequestedDoctor()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        if ($limit == -1) {
            if (!empty($search)) {
                $data['doctors'] = $this->doctor_model->getRequestedDoctorBySearch($search);
            } else {
                $data['doctors'] = $this->doctor_model->getRequestedDoctor();
            }
        } else {
            if (!empty($search)) {
                $data['doctors'] = $this->doctor_model->getRequestedDoctorByLimitBySearch($limit, $start, $search);
            } else {
                $data['doctors'] = $this->doctor_model->getRequestedDoctorByLimit($limit, $start);
            }
        }


        foreach ($data['doctors'] as $doctor) {
          
            
            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {
                
            }



            if ($this->ion_auth->in_group(array('admin'))) {
                if($doctor->status == 'Requested'){
                    $options2 = '<a class="btn btn-info btn-xs " title="' . lang('approved') . '"  href="doctor/acceptRequest?id=' . $doctor->id . '" onclick="return confirm(\'Do you want to approve this doctor request?\');"> <i class="fa fa-plus"> </i> ' . lang('approved') . '</a>';
                    $options4 = '<a href="doctor/decline?id=' . $doctor->id . '" class="btn btn-danger btn-xs btn_width" onclick="return confirm(\'Do you want to decline this doctor request?\');"><i class="fa fa-book"></i> ' . lang('decline') . '</a>';
                }else{
                    $options2 = '';
                    $options4 = '';
                }
                
                $options3 = '<a class="btn btn-info btn-xs btn_width delete_button" title="' . lang('delete') . '" href="doctor/deleteRequest?id=' . $doctor->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i> ' . lang('delete') . '</a>';
               
                $options6 = '<a type="button" class="btn btn-info btn-xs btn_width detailsbutton inffo" title="' . lang('info') . '" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-info"> </i> ' . lang('info') . '</a>';
            }

            $info[] = array(
                $doctor->id,
                $doctor->name,
                $doctor->email,
                $doctor->phone,
                $doctor->country,
                $doctor->department,
                $doctor->status,
                $options6 . ' ' . $options2 . ' ' . $options4 . ' ' . $options3,
            );
        }

        if (!empty($data['doctors'])) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => count($this->doctor_model->getRequestedDoctor()),
                "recordsFiltered" => count($this->doctor_model->getRequestedDoctor()),
                "data" => $info
            );
        } else {
            $output = array(
                // "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }
    public function requestedDoctor()
    {

        
        $this->load->view('home/dashboard');
        $this->load->view('doctor_request');
        $this->load->view('home/footer');
    }
    function editRequestedDoctorByJason()
    {
        $id = $this->input->get('id');
        $data['doctor'] = $this->doctor_model->getRequestedDoctorById($id);
        echo json_encode($data);
    }
    public function acceptRequest()
    {

        $id = $this->input->get('id');
        
        $request = $this->doctor_model->getRequestedDoctorById($id);
        if(empty($request->password)){
            $password = 12345;
        }else{
            $password = $request->password;
        }
        
        $email = $request->email;
        if (!$this->ion_auth->in_group(array('superadmin'))) {
            if (empty($id)) {
                $limit = $this->doctor_model->getLimit();
                if ($limit <= 0) {
                    $this->session->set_flashdata('feedback', lang('doctor_limit_exceed'));
                    redirect('doctor/requestedDoctor');
                }
            }
        }
        


                $data = array();
                $data = array(
                   
                    'name' => $request->name,
                    'email' => $request->email,
                    'address' => $request->address,
                    'hospital_id' => $request->hospital_id,
                    'phone' => $request->phone,
                    'department' => $request->department,
                    'profile' => $request->profile,
                    'registration_issue_institution_name' => $request->registration_issue_institution_name,
                    'registration_number' => $request->registration_number,
                    'registration_expiry_date' => $request->registration_expiry_date,
                    'nid_no' => $request->nid_no,
                    'country' => $request->country,
                    'chamber_address' => $request->chamber_address,
                    'description' => $request->description,
                    'img_url' => $request->img_url,
                    'registration_certificate' => $request->registration_certificate,
                    'nid_certificate' => $request->nid_certificate
                );
          


            $username = $request->name;
           // Adding New Doctor
                if ($this->ion_auth->email_check($email)) {
                    $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));
                    redirect('doctor/requestedDoctor');
                } else {
                    $dfg = 4;
                    $this->ion_auth->register($username, $password, $email, $dfg);
                    $ion_user_id = $this->db->get_where('users', array('email' => $email))->row()->id;
                
                        $this->doctor_model->insertApprovedDoctor($data);
             
                        $inserted_id = $this->db->insert_id();
                        $commission = $this->settings_model->getCommissionSettings();
                        $commission_data = array();
                        $commission_data = array(
                            'doctor' => $inserted_id,
                            'medicine_fee' => $commission->medicine_fee,
                            'medicine_fee_rupee' => $commission->medicine_fee_rupee,
                            'medicine_fee_dollar' => $commission->medicine_fee_dollar,
                            'medicine_fee_edit' => 1,
                            'medicine_fee_policy' => $commission->medicine_fee_policy,
                            'courier_fee' => $commission->courier_fee,
                            'courier_fee_rupee' => $commission->courier_fee_rupee,
                            'courier_fee_dollar' => $commission->courier_fee_dollar,
                            'courier_fee_edit' => 1,
                            'courier_fee_policy' => $commission->courier_fee_policy,
                            'casetaker_fee' => $commission->casetaker_fee,
                            'casetaker_fee_rupee' => $commission->casetaker_fee_rupee,
                            'casetaker_fee_dollar' => $commission->casetaker_fee_dollar,
                            'casetaker_fee_edit' => 1,
                            'casetaker_fee_policy' => $commission->casetaker_fee_policy,
                            'onlinecenter_fee' => $commission->onlinecenter_fee,
                            'onlinecenter_fee_rupee' => $commission->onlinecenter_fee_rupee,
                            'onlinecenter_fee_dollar' => $commission->onlinecenter_fee_dollar,
                            'onlinecenter_fee_edit' => 1,
                            'onlinecenter_fee_policy' => $commission->onlinecenter_fee_policy,
                            'developer_fee' => $commission->developer_fee,
                            'developer_fee_rupee' => $commission->developer_fee_rupee,
                            'developer_fee_dollar' => $commission->developer_fee_dollar,
                            'developer_fee_edit' => ' ',
                            'developer_fee_policy' => $commission->developer_fee_policy,
                            'current_hospital' => $commission->current_hospital,
                            'current_hospital_rupee' => $commission->current_hospital_rupee,
                            'current_hospital_dollar' => $commission->current_hospital_dollar,
                            'current_hospital_fee_edit' => ' ',
                            'current_hospital_fee_policy' => $commission->current_hospital_fee_policy,
                            'foreign_hospital' => $commission->foreign_hospital,
                            'foreign_hospital_rupee' => $commission->foreign_hospital_rupee,
                            'foreign_hospital_dollar' => $commission->foreign_hospital_dollar,
                            'foreign_hospital_fee_edit' => ' ',
                            'foreign_hospital_fee_policy' => $commission->foreign_hospital_fee_policy,
                            'superadmin_fee' => $commission->superadmin_fee,
                            'superadmin_fee_rupee' => $commission->superadmin_fee_rupee,
                            'superadmin_fee_dollar' => $commission->superadmin_fee_dollar,
                            'superadmin_fee_edit' => ' ',
                            'superadmin_fee_policy' => $commission->superadmin_fee_policy,
                            'doctor_login_fee' => $commission->doctor_login_fee,
                            'doctor_login_fee_rupee' => $commission->doctor_login_fee_rupee,
                            'doctor_login_fee_dollar' => $commission->doctor_login_fee_dollar,
                            'doctor_login_fee_edit' => ' ',
                            'doctor_login_fee_policy' => $commission->doctor_login_fee_policy,
                            'visit_charge_edit' => 1,
                            'visit_des_edit' => 1,
                            'courier_fee_applicable_edit' => 1,
    
                            'custom_medical_board_visit_charges' => $commission->custom_medical_board_visit_charges,
                            'custom_medical_board_visit_charges_rupee' => $commission->custom_medical_board_visit_charges_rupee,
                            'custom_medical_board_visit_charges_dollar' => $commission->custom_medical_board_visit_charges_dollar,
                            'custom_medical_board_visit_charges_edit' => 1,
                            'custom_medical_board_visit_charges_policy' => $commission->custom_medical_board_visit_charges_policy,
    
                            'individual_medical_board_visit_charges' => $commission->individual_medical_board_visit_charges,
                            'individual_medical_board_visit_charges_rupee' => $commission->individual_medical_board_visit_charges_rupee,
                            'individual_medical_board_visit_charges_dollar' => $commission->individual_medical_board_visit_charges_dollar,
                            'individual_medical_board_visit_charges_edit' => ' ',
                            'individual_medical_board_visit_charges_policy' => $commission->individual_medical_board_visit_charges_policy,
                        );
                        if ($this->ion_auth->in_group(array('superadmin'))) {
                            $this->doctor_model->insertDoctorCommissionBySuperadmin($data);
                        } else {
                            $this->doctor_model->insertDoctorCommission($commission_data);
                        }

                    $doctor_user_id = $this->db->get_where('doctor', array('email' => $email))->row()->id;
                    $id_info = array('ion_user_id' => $ion_user_id);
                    $this->doctor_model->updateDoctor($doctor_user_id, $id_info);
                    $this->hospital_model->addHospitalIdToIonUser($ion_user_id, $this->hospital_id);

                    $data1 = array('status' => 'Approved');
                    $this->doctor_model->updateDoctorRequest($id, $data1);



                    $base_url = str_replace(array('http://', 'https://', ' '), '', base_url()) . "auth/login";
                    //sms
                    $set['settings'] = $this->settings_model->getSettings();
                    $autosms = $this->sms_model->getAutoSmsByType('doctor');
                    $message = $autosms->message;
                    $to = $request->phone;
                    $name1 = explode(' ', $request->name);
                    if (!isset($name1[1])) {
                        $name1[1] = null;
                    }
                    $data1 = array(
                        'firstname' => $name1[0],
                        'lastname' => $name1[1],
                        'name' => $request->name,
                        'base_url' => $base_url,
                        'email' => $request->email,
                        'password' => $password,
                        'department' => $request->department,
                        'company' => $set['settings']->system_vendor
                    );

                    if ($autosms->status == 'Active') {
                        $messageprint = $this->parser->parse_string($message, $data1);
                        $data2[] = array($to => $messageprint);
                        // $this->sms->sendSms($to, $message, $data2);
                        $this->sms->sendSmsByCountry($to, $message, $data2, $request->country, $request->hospital_id);
                    }
                    //end
                    //email

                    $autoemail = $this->email_model->getAutoEmailByType('doctor');
                    if ($autoemail->status == 'Active') {
                        $mail_provider = $this->settings_model->getSettings()->emailtype;
                        $settngs_name = $this->settings_model->getSettings()->system_vendor;
                        $email_Settings = $this->email_model->getEmailSettingsByType($mail_provider);
                        $message1 = $autoemail->message;
                        $messageprint1 = $this->parser->parse_string($message1, $data1);
                        if ($mail_provider == 'Domain Email') {
                            $this->email->from($email_Settings->admin_email);
                        }
                        if ($mail_provider == 'Smtp') {
                            $this->email->from($email_Settings->user, $settngs_name);
                        }
                        $this->email->to($email);
                        $this->email->subject('Registration confirmation');
                        $this->email->message($messageprint1);
                        $this->email->send();
                    }

                    //end


                    $this->session->set_flashdata('feedback', 'Request Accepted');
                }
          
            // Loading View
        
                redirect('doctor/requestedDoctor');
         
       
      
    }
    function decline()
    {
        $id = $this->input->get('id');
        $data = array('status' => 'Decline');
        $this->doctor_model->updateDoctorRequest($id, $data);
        $this->session->set_flashdata('feedback', 'Request Decline');
        redirect('doctor/requestedDoctor');
    }
    function deleteRequest()
    {

  
        $data = array();
        $id = $this->input->get('id');
        $this->doctor_model->deleteRequest($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('doctor/requestedDoctor');
    }
}

/* End of file doctor.php */
/* Location: ./application/modules/doctor/controllers/doctor.php */
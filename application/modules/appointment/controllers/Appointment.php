<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Twilio\Rest\Client;

class Appointment extends MX_Controller
{
    protected $app_key;
    protected $app_secret;
    protected $username;
    protected $password;
    protected $base_url;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('appointment_model');
        $this->load->model('pgateway/pgateway_model');
        $this->load->model('doctor/doctor_model');
        $this->load->model('patient/patient_model');
        $this->load->model('finance/finance_model');
        $this->load->model('team/team_model');
        $this->load->model('sms/sms_model');
        $this->load->model('doctorvisit/doctorvisit_model');
        $this->load->model('pgateway/pgateway_model');
        $this->load->model('onlinecenter/onlinecenter_model');
        $this->load->model('casetaker/casetaker_model');
        $this->load->module('sms');
        require APPPATH . 'third_party/stripe/stripe-php/init.php';
        $this->load->module('paypal');

        if (!$this->ion_auth->in_group(array('admin', 'Nurse', 'Doctor', 'Patient', 'Receptionist', 'onlinecenter', 'superadmin', 'casetaker'))) {
            redirect('home/permission');
        }
        $this->username="sandboxTokenizedUser02";
        $this->password="sandboxTokenizedUser02@12345";  
        $this->app_key="4f6o0cjiki2rfm34kfdadl1eqq";
        $this->app_secret="2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b";
    
    
      // Sandbox Base URL
      $this->base_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';
    
      // Live Base URL
      //$this->base_url = 'https://checkout.pay.bka.sh/v1.2.0-beta/tokenized'; 
    }

    public function index()
    {

        if ($this->ion_auth->in_group(array('Patient'))) {
            redirect('home/permission');
        }

        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $data['categories'] = $this->hospital_model->getHospitalCategory();
            //            $data['settings'] = $this->settings_model->getSettings();
        } else {
            //            $data['patients'] = $this->patient_model->getPatient();
            $data['doctors'] = $this->doctor_model->getDoctor();
            $data['settings'] = $this->settings_model->getSettings();
            $data['gateway'] = $this->finance_model->getGatewayByName($data['settings']->payment_gateway);
        }
        $data['b_doctors'] = $this->doctor_model->getBoardDoctor();
        $this->load->view('home/dashboard', $data);
        $this->load->view('appointment', $data);
        $this->load->view('home/footer');
    }

    public function boardAppointment()
    {

        if ($this->ion_auth->in_group(array('Patient'))) {
            redirect('home/permission');
        }

        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $data['categories'] = $this->hospital_model->getHospitalCategory();
            //            $data['settings'] = $this->settings_model->getSettings();
        } else {
            //            $data['patients'] = $this->patient_model->getPatient();
            $data['doctors'] = $this->doctor_model->getDoctor();
            $data['settings'] = $this->settings_model->getSettings();
            $data['gateway'] = $this->finance_model->getGatewayByName($data['settings']->payment_gateway);
        }

        $this->load->view('home/dashboard', $data);
        $this->load->view('board_appointment', $data);
        $this->load->view('home/footer');
    }
    public function aamarpay()
    {

        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data);
        $this->load->view('add_new', $data);
        $this->load->view('home/footer');
    }
    public function request()
    {
        $data['patients'] = $this->patient_model->getPatient();
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['settings'] = $this->settings_model->getSettings();
        $data['gateway'] = $this->finance_model->getGatewayByName($data['settings']->payment_gateway);
        $this->load->view('home/dashboard', $data);
        $this->load->view('appointment_request', $data);
        $this->load->view('home/footer');
    }

    public function todays()
    {
        if ($this->ion_auth->in_group(array('Patient'))) {
            redirect('home/permission');
        }

        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $data['categories'] = $this->hospital_model->getHospitalCategory();
            //            $data['settings'] = $this->settings_model->getSettings();
        } else {
            //            $data['patients'] = $this->patient_model->getPatient();
            //            $data['doctors'] = $this->doctor_model->getDoctor();
            $data['settings'] = $this->settings_model->getSettings();
            $data['gateway'] = $this->finance_model->getGatewayByName($data['settings']->payment_gateway);
        }
        $data['b_doctors'] = $this->doctor_model->getBoardDoctor();
        $this->load->view('home/dashboard', $data);
        $this->load->view('todays', $data);
        $this->load->view('home/footer');
    }

    public function upcoming()
    {

        if ($this->ion_auth->in_group(array('Patient'))) {
            redirect('home/permission');
        }
        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $data['categories'] = $this->hospital_model->getHospitalCategory();
            //            $data['settings'] = $this->settings_model->getSettings();
        } else {
            //            $data['patients'] = $this->patient_model->getPatient();
            //            $data['doctors'] = $this->doctor_model->getDoctor();
            $data['settings'] = $this->settings_model->getSettings();
            $data['gateway'] = $this->finance_model->getGatewayByName($data['settings']->payment_gateway);
        }
        $data['b_doctors'] = $this->doctor_model->getBoardDoctor();
        $this->load->view('home/dashboard', $data);
        $this->load->view('upcoming', $data);
        $this->load->view('home/footer');
    }

    public function myTodays()
    {
        if (!$this->ion_auth->in_group(array('Patient'))) {
            redirect('home/permission');
        }

        $data['patients'] = $this->patient_model->getPatient();
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['settings'] = $this->settings_model->getSettings();
        $data['gateway'] = $this->finance_model->getGatewayByName($data['settings']->payment_gateway);
        $this->load->view('home/dashboard', $data);
        $this->load->view('my_todays', $data);
        $this->load->view('home/footer');
    }

    public function calendar()
    {

        if ($this->ion_auth->in_group(array('Patient'))) {
            redirect('home/permission');
        }

        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            $data['appointments'] = $this->appointment_model->getAppointmentByDoctor($doctor);
        } else {
            $data['appointments'] = $this->appointment_model->getAppointment();
        }

        //        $data['patients'] = $this->patient_model->getPatient();
        //        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data);
        $this->load->view('calendar', $data);
        $this->load->view('home/footer');
    }

    public function addNewView()
    {

        if ($this->ion_auth->in_group(array('Patient'))) {
            redirect('home/permission');
        }
        $data['appointment'] = '';
        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $data['categories'] = $this->hospital_model->getHospitalCategory();
            //            $data['settings'] = $this->settings_model->getSettings();
        } else {
            $data['patients'] = $this->patient_model->getPatient();
            $data['doctors'] = $this->doctor_model->getDoctor();
            $data['settings'] = $this->settings_model->getSettings();
            $data['gateway'] = $this->finance_model->getGatewayByName($data['settings']->payment_gateway);
        }

        $data['b_doctors'] = $this->doctor_model->getBoardDoctor();
        $this->load->view('home/dashboard', $data);
        $this->load->view('add_new', $data);
        $this->load->view('home/footer');
    }

    public function addNew()
    {
        $id = $this->input->post('id');
        $patient = $this->input->post('patient');

        $date = $this->input->post('date');
        if (!empty($date)) {
            $date = strtotime($date);
        }

        $visit_type = $this->input->post('visit_type');
        $superadmin = $this->input->post('superadmin');
        $time_slot = $this->input->post('time_slot');

        $time_slot_explode = explode('To', $time_slot);

        $s_time = trim($time_slot_explode[0]);
        $e_time = trim($time_slot_explode[1]);

        $remarks = $this->input->post('remarks');

        $sms = $this->input->post('sms');

        $status = $this->input->post('status');

        $redirect = $this->input->post('redirect');

        $request = $this->input->post('request');

        if (empty($request)) {
            $request = '';
        }
        $currency = $this->input->post('currency');
        $redirectlink = $this->input->post('redirectlink');
        $user = $this->ion_auth->get_user_id();

        if ($this->ion_auth->in_group(array('Patient'))) {
            $user = '';
        }


        $consultant_fee = $this->input->post('visit_charges');

        $casetaker_fee = $this->input->post('casetaker_fee');
        $onlinecenter_fee = $this->input->post('onlinecenter_fee');
        $developer_fee = $this->input->post('developer_fee');
        $superadmin_fee = $this->input->post('superadmin_fee');
        $medicine_fee = $this->input->post('medicine_fee');
        $doctor_amount = $this->input->post('doctor_amount');
        $total_charges = $this->input->post('total_charges');
        $appointment_subtotal = $this->input->post('appointment_subtotal');

        $account_number = $this->input->post('account_number');
        $transaction_id = $this->input->post('transaction_id');

        $additional_fee = $this->input->post('additional_fee');
        if (!$this->input->post('pay_for_courier')) {
            $courier_fee = '';
        } else {
            $courier_fee = $this->input->post('courier_fee');
        }
        if ((empty($id))) {
            $add_date = date('m/d/y');
            $registration_time = time();
            $patient_add_date = $add_date;
            $patient_registration_time = $registration_time;
        } else {
            $add_date = $this->appointment_model->getAppointmentById($id)->add_date;
            $registration_time = $this->appointment_model->getAppointmentById($id)->registration_time;
        }

        $s_time_key = $this->getArrayKey($s_time);
        $country = $this->input->post('country');
        $p_name = $this->input->post('p_name');
        $p_phone = $this->input->post('p_phone');
        $p_email = $this->input->post('p_email');
        if (empty($p_email)) {
            $p_email = $p_name . '-' . $p_phone . rand(10000, 1000000) . '@hd.com';
        }
        if (!empty($p_name)) {
            $password = $p_name . '-' . rand(1, 100000000);
        }

        // $p_age = $this->input->post('p_age');
        $p_gender = $this->input->post('p_gender');
        $p_address = $this->input->post('p_address');
        $p_birthdate = $this->input->post('p_birthdate');
        //        $patient_id = rand(10000, 1000000);
        $patient_id = $this->input->post('p_id');
        
            $years = $this->input->post('years');
            $months = $this->input->post('months');
            $days = $this->input->post('days');
     

        $p_age = $years . '-' . $months . '-' . $days;
     
        $medical_board_type = $this->input->post('medical_board_type');
        if ($medical_board_type == 'Medical Board') {
            $team = $this->input->post('team');
            $board_doctor = $this->input->post('board_doctor');
            $doctor = '';
        } else {
            $doctor = $this->input->post('doctor');
        }
        if ($medical_board_type == 'Custom Board') {
            $team = '';
            $board_doctor = $this->input->post('custom_board_doctor');
        }
        // $board_doctor = $this->input->post('board_doctor');
        $board_doctor = implode(',', $board_doctor);

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($patient == 'add_new') {
            $this->form_validation->set_rules('p_name', 'Patient Name', 'trim|required|min_length[1]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('p_phone', 'Patient Phone', 'trim|required|min_length[1]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('country', 'Country', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        }

        // Validating Name Field
        $this->form_validation->set_rules('patient', 'Patient', 'trim|min_length[1]|max_length[100]|xss_clean');
        // Validating Password Field
        $this->form_validation->set_rules('doctor', 'Doctor', 'trim|min_length[1]|max_length[100]|xss_clean');

        // Validating Email Field
        $this->form_validation->set_rules('date', 'Date', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        // Validating Email Field
        $this->form_validation->set_rules('s_time', 'Start Time', 'trim|min_length[1]|max_length[100]|xss_clean');
        // Validating Email Field
        $this->form_validation->set_rules('e_time', 'End Time', 'trim|min_length[1]|max_length[100]|xss_clean');
        // Validating Address Field
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|min_length[1]|max_length[1000]|xss_clean');

        if ($this->form_validation->run() == false) {
            if (!empty($id)) {
                redirect("appointment/editAppointment?id=$id");
            } else {
                $data['patients'] = $this->patient_model->getPatient();
                $data['doctors'] = $this->doctor_model->getDoctor();
                $data['settings'] = $this->settings_model->getSettings();
                $data['gateway'] = $this->pgateway_model->getPaymentGatewaySettingsByName($data['settings']->payment_gateway);
                $this->load->view('home/dashboard', $data);
                $this->load->view('add_new', $data);
                $this->load->view('home/footer');
            }
        } else {

            if ($patient == 'add_new') {
                if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                    $limit = $this->patient_model->getLimit();
                    if ($limit <= 0) {
                        $this->session->set_flashdata('feedback', lang('patient_limit_exceed'));
                        redirect('patient');
                    }
                }
                if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                    $hospital_id = $this->input->post('hospital_id');
                    $onlinecenter_id = $this->input->post('onlinecenter_id');
                    $casetaker_id = $this->input->post('casetaker_id');
                } else {
                    $hospital_id = $this->session->userdata('hospital_id');
                }
                $data_p = array(
                    'patient_id' => $patient_id,
                    'superadmin' => $superadmin,
                    'hospital_id' => $hospital_id,
                    'onlinecenter_id' => $onlinecenter_id,
                    'casetaker_id' => $casetaker_id,
                    'name' => $p_name,
                    'email' => $p_email,
                    'phone' => $p_phone,
                    'sex' => $p_gender,
                    'age' => $p_age,
                    'birthdate' => $p_birthdate,
                    'address' => $p_address,
                    'country' => $country,
                    'doctor' => $doctor,
                    'add_date' => $patient_add_date,
                    'registration_time' => $patient_registration_time,
                    'how_added' => 'from_appointment',
                );
                $username = $this->input->post('p_name');
                // Adding New Patient
                if ($this->ion_auth->email_check($p_email)) {
                    $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));
                    if (!empty($redirect)) {
                        redirect($redirect);
                    } else {
                        redirect('appointment');
                    }
                } else {
                    $dfg = 5;
                    $this->ion_auth->register($username, $password, $p_email, $dfg);
                    $ion_user_id = $this->db->get_where('users', array('email' => $p_email))->row()->id;
                    //                    $this->patient_model->insertPatient($data_p);
                    if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                        $this->patient_model->insertPatientByOnlinecenter($data_p);
                    } else {
                        $this->patient_model->insertPatient($data_p);
                    }
                    $inserted_id = $this->db->insert_id();
                    $patient_id = 10000 + $inserted_id;
                    $data3 = array(
                        'patient_id' => $patient_id,
                    );
                    if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                        $this->patient_model->insertPatientIdByOnlinecenter($inserted_id, $data3);
                    } else {
                        $this->patient_model->insertPatientId($inserted_id, $data3);
                    }
                    $patient_user_id = $this->db->get_where('patient', array('email' => $p_email))->row()->id;
                    $id_info = array('ion_user_id' => $ion_user_id);
                    $this->patient_model->updatePatient($patient_user_id, $id_info);
                    $this->hospital_model->addHospitalIdToIonUser($ion_user_id, $this->hospital_id);
                }

                $patient = $patient_user_id;
                //    }
            } elseif ($patient == '') {
                $patient = $this->input->post('patientt');
            }

            $patient_phone = $this->patient_model->getPatientById($patient)->phone;

            if (empty($id)) {
                $room_id = 'hms-meeting-' . $patient_phone . '-' . rand(10000, 1000000) . '-' . $this->hospital_id;
                // $live_meeting_link = 'https://meet.jit.si/' . $room_id;
                $live_meeting_link = 'https://8x8.vc/' . $room_id;
            } else {
                $appointment_details = $this->appointment_model->getAppointmentById($id);
                $room_id = $appointment_details->room_id;
                $live_meeting_link = $appointment_details->live_meeting_link;
            }

            $patientname = $this->patient_model->getPatientByOnlinecenter($patient)->name;
            $patient_id = $this->patient_model->getPatientByOnlinecenter($patient)->patient_id;
            $doctorname = $this->doctor_model->getDoctorById($doctor)->name;
            $patient_details = $this->patient_model->getPatientByOnlinecenter($patient);
            $data = array();

            if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                $hospital_id = $this->session->userdata('hospital_id');
                $data = array(
                    'patient' => $patient,
                    'patientname' => $patientname,
                    'patient_phone' => $patient_details->phone,
                    'patient_id' => $patient_id,
                    'doctor' => $doctor,
                    'doctorname' => $doctorname,
                    'board_doctor' => $board_doctor,
                    'medical_board_type' => $medical_board_type,
                    'team' => $team,
                    'date' => $date,
                    's_time' => $s_time,
                    'e_time' => $e_time,
                    'time_slot' => $time_slot,
                    'remarks' => $remarks,
                    //                    'superadmin' => $superadmin,
                    'add_date' => $add_date,
                    'currency' => $currency,
                    'registration_time' => $registration_time,
                    'status' => $status,
                    's_time_key' => $s_time_key,
                    'user' => $user,
                    'request' => $request,
                    'room_id' => $room_id,
                    'live_meeting_link' => $live_meeting_link,
                    'visit_type' => $visit_type,
                    'appointment_subtotal' => $appointment_subtotal,
                    'visit_charges' => $this->input->post('visit_charges'),
                    'visit_description' => $this->input->post('visit_description'),
                    'casetaker_fee' => $casetaker_fee,
                    'onlinecenter_fee' => $onlinecenter_fee,
                    'developer_fee' => $developer_fee,
                    'superadmin_fee' => $superadmin_fee,
                    'medicine_fee' => $medicine_fee,
                    'courier_fee' => $courier_fee,
                    'additional_fee' => $additional_fee,
                    'doctor_amount' => $doctor_amount,
                    'total_charges' => $total_charges,
                    'account_number' => $account_number,
                    'transaction_id' => $transaction_id,
                );
                $data_appointment = array(
                    'category_name' => 'Consultant Fee',
                    'patient' => $patient,
                    'amount' => $this->input->post('deposited_amount'),
                    'doctor' => $doctor,
                    'discount' => '0',
                    'flat_discount' => '0',
                    'gross_total' => $this->input->post('visit_charges'),
                    'status' => 'unpaid',
                    'hospital_amount' => '0',
                    'doctor_amount' => $this->input->post('doctor_amount'),
                    'user' => $user,
                    'currency' => $currency,
                    'patient_name' => $patient_details->name,
                    'patient_phone' => $patient_details->phone,
                    'patient_address' => $patient_details->address,
                    'doctor_name' => $doctorname,
                    'remarks' => $remarks,
                    'payment_from' => 'appointment',
                    'hospital_id' => $hospital_id,
                );
            } else {
                $hospital_id = $this->input->post('hospital_id');
                $onlinecenter_id = $this->input->post('onlinecenter_id');
                if (!empty($this->input->post('casetaker_id'))) {
                    $casetaker_id = $this->input->post('casetaker_id');
                    $data['casetaker_id'] = $casetaker_id;
                    $data_appointment['casetaker_id'] = $casetaker_id;
                }

                $data = array(
                    'patient' => $patient,
                    'patientname' => $patientname,
                    'patient_phone' => $patient_details->phone,
                    'patient_id' => $patient_id,
                    'doctor' => $doctor,
                    'doctorname' => $doctorname,
                    'board_doctor' => $board_doctor,
                    'medical_board_type' => $medical_board_type,
                    'team' => $team,
                    'date' => $date,
                    's_time' => $s_time,
                    'e_time' => $e_time,
                    'time_slot' => $time_slot,
                    'hospital_id' => $hospital_id,
                    'onlinecenter_id' => $onlinecenter_id,
                    'casetaker_id' => $casetaker_id,
                    'remarks' => $remarks,
                    'superadmin' => $superadmin,
                    'add_date' => $add_date,
                    'registration_time' => $registration_time,
                    'status' => $status,
                    'currency' => $currency,
                    's_time_key' => $s_time_key,
                    'user' => $user,
                    'request' => $request,
                    'room_id' => $room_id,
                    'live_meeting_link' => $live_meeting_link,
                    'visit_type' => $visit_type,
                    'appointment_subtotal' => $appointment_subtotal,
                    'visit_charges' => $this->input->post('visit_charges'),
                    'visit_description' => $this->input->post('visit_description'),
                    'casetaker_fee' => $casetaker_fee,
                    'onlinecenter_fee' => $onlinecenter_fee,
                    'developer_fee' => $developer_fee,
                    'superadmin_fee' => $superadmin_fee,
                    'medicine_fee' => $medicine_fee,
                    'courier_fee' => $courier_fee,
                    'additional_fee' => $additional_fee,
                    'doctor_amount' => $doctor_amount,
                    'total_charges' => $total_charges,
                    'account_number' => $account_number,
                    'transaction_id' => $transaction_id,
                );
                $data_appointment = array(
                    'category_name' => 'Consultant Fee',
                    'patient' => $patient,
                    'amount' => $this->input->post('deposited_amount'),
                    'doctor' => $doctor,
                    'discount' => '0',
                    'flat_discount' => '0',
                    'gross_total' => $this->input->post('visit_charges'),
                    'status' => 'unpaid',
                    'hospital_amount' => '0',
                    'doctor_amount' => $this->input->post('doctor_amount'),
                    'user' => $user,
                    'currency' => $currency,
                    'patient_name' => $patient_details->name,
                    'patient_phone' => $patient_details->phone,
                    'patient_address' => $patient_details->address,
                    'doctor_name' => $doctorname,
                    'remarks' => $remarks,
                    'superadmin' => $superadmin,
                    'onlinecenter_id' => $onlinecenter_id,
                    'casetaker_id' => $casetaker_id,
                    'payment_from' => 'appointment',
                    'hospital_id' => $hospital_id,
                );
            }

            $username = $this->input->post('name');
            if (empty($id)) { // Adding New department
                $data['payment_status'] = 'unpaid';
                if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                    $this->appointment_model->insertAppointment($data);
                } else {
                    $this->appointment_model->insertAppointmentByOnlinecenter($data);
                }
                $appointment_id = $this->db->insert_id('appointment');
                $data_appointment['appointment_id'] = $appointment_id;
                $data_appointment['date'] = time();
                $data_appointment['date_string'] = date('d-m-Y');
                if (!$this->input->post('pay_now_appointment') && $status == 'Requested') {
                    $inserted_id = '';
                } else {

                    $this->finance_model->insertPaymentForAllAccess($data_appointment);

                    $inserted_id = $this->db->insert_id('payment');
                    $deposit_type = $this->input->post('deposit_type');
                    $data_update_payment_id_in_appointment = array('payment_id' => $inserted_id);
                    $this->appointment_model->updateAppointment($appointment_id, $data_update_payment_id_in_appointment);
                }

                $patient_doctor = $this->patient_model->getPatientByOnlinecenter($patient)->doctor;

                $patient_doctors = explode(',', $patient_doctor);

                if (!in_array($doctor, $patient_doctors)) {
                    $patient_doctors[] = $doctor;
                    $doctorss = implode(',', $patient_doctors);
                    $data_d = array();
                    $data_d = array('doctor' => $doctorss);
                    $this->patient_model->updatePatient($patient, $data_d);
                }
                $response = $this->sendSmsDuringAppointment($id, $data, $patient, $doctor, $status, $hospital_id, $appointment_id, $password);
                // $response = '';
                $pay_now_appointment = $this->input->post('pay_now_appointment');
                if (!empty($pay_now_appointment)) {
                    $data_for_payment = array();
                    $data_for_payment = array(
                        'card_type' => $this->input->post('card_type'),
                        'card_number' => $this->input->post('card_number'),
                        'expire_date' => $this->input->post('expire_date'),
                        'cardHoldername' => $this->input->post('cardholder'),
                        'cvv' => $this->input->post('cvv'),
                        'token' => $this->input->post('token'),
                        'discount' => '0',
                        'grand_total' => $this->input->post('visit_charges'),
                        'deposited_amount' => $this->input->post('deposited_amount'),
                        'account_number' => $this->input->post('account_number'),
                        'transaction_id' => $this->input->post('transaction_id'),
                    );
                    $date = time();

                    $this->appointmentPayment($superadmin, $deposit_type, $data_for_payment, $patient, $doctor, $consultant_fee, $date, $inserted_id, $redirectlink);
                } else {
                    if ($redirectlink == 'my_today') {
                        redirect('appointment/todays');
                    } elseif ($redirectlink == 'upcoming') {
                        redirect('appointment/upcoming');
                    } elseif ($redirectlink == 'med_his') {
                        redirect("patient/medicalHistory?id=" . $patient);
                    } elseif ($redirectlink == 'request') {
                        redirect("appointment/request");
                    } elseif ($redirectlink == 'frontend') {
                        redirect("frontend");
                    } elseif ($redirectlink == 'modal') {
                        redirect("appointment/refresh");
                    } else {
                        redirect('appointment');
                    }
                }
                $this->session->set_flashdata('feedback', lang('added'));
            } else { // Updating department
                $previous_status = $this->appointment_model->getAppointmentById($id)->status;
                $appointment_id = $this->appointment_model->getAppointmentById($id)->id;
                if ($previous_status != "Confirmed") {
                    if ($status == "Confirmed") {
                        $response = $this->sendSmsDuringAppointment($id, $data, $patient, $doctor, $status, $hospital_id, $appointment_id, $password);
                    }
                }
                $appointment_contingent = $this->appointment_model->getAppointmentById($id);

                if ($appointment_contingent->payment_status == 'unpaid' || empty($appointment_contingent->payment_status)) {
                    $this->appointment_model->updateAppointment($id, $data);
                    $data['visit_charges'] = $this->input->post('visit_charges');
                    $data['discount'] = '0';
                    $data['grand_total'] = $this->input->post('visit_charges');
                    $data['deposited_amount'] = $this->input->post('deposited_amount');
                    $data['visit_type'] = $this->input->post('visit_type');

                    $this->finance_model->updatePayment($appointment_contingent->payment_id, $data_appointment);

                    $pay_now_appointment = $this->input->post('pay_now_appointment');
                    if (!empty($pay_now_appointment)) {

                        $deposit_type = $this->input->post('deposit_type');
                        $data_for_payment = array();
                        $data_for_payment = array(
                            'card_type' => $this->input->post('card_type'),
                            'card_number' => $this->input->post('card_number'),
                            'expire_date' => $this->input->post('expire_date'),
                            'cardHoldername' => $this->input->post('cardholder'),
                            'cvv' => $this->input->post('cvv'),
                            'token' => $this->input->post('token'),
                            'discount' => '0',
                            'grand_total' => $this->input->post('visit_charges'),
                            'deposited_amount' => $this->input->post('deposited_amount'),
                            'account_number' => $this->input->post('account_number'),
                            'transaction_id' => $this->input->post('transaction_id'),
                        );
                        $date = time();

                        $this->appointmentPayment($superadmin, $deposit_type, $data_for_payment, $patient, $doctor, $consultant_fee, $date, $appointment_contingent->payment_id, $redirectlink);
                    }
                } else {
                    $this->appointment_model->updateAppointment($id, $data);
                }

                $this->session->set_flashdata('feedback', lang('updated'));
                if ($redirectlink == 'my_today') {
                    redirect('appointment/todays');
                } elseif ($redirectlink == 'upcoming') {
                    redirect('appointment/upcoming');
                } elseif ($redirectlink == 'med_his') {
                    redirect("patient/medicalHistory?id=" . $patient);
                } elseif ($redirectlink == 'request') {
                    redirect("appointment/request");
                } elseif ($redirectlink == 'modal') {
                    redirect("appointment/refresh");
                } elseif ($redirectlink == 'frontend') {
                    redirect("frontend");
                } else {
                    redirect('appointment');
                }
            }
        }
    }

    public function successStatus($data, $inserted_id)
    {

        $data_payment = array('amount_received' => $data['deposited_amount'], 'deposit_type' => 'Aamarpay', 'status' => 'paid');
        $this->finance_model->updatePayment($inserted_id, $data_payment);

        $appointment_id = $this->finance_model->getPaymentById($inserted_id)->appointment_id;

        $appointment_details = $this->appointment_model->getAppointmentById($appointment_id);

        if ($appointment_details->status == 'Requested' || $appointment_details->status == 'Pending Confirmation') {

            $data_appointment_status = array('payment_status' => 'paid');
        } else {
            $data_appointment_status = array('payment_status' => 'paid');
        }

        $this->appointment_model->updateAppointment($appointment_id, $data_appointment_status);

        redirect('appointment');
    }

    public function appointmentPayment($superadmin, $deposit_type, $data, $patient, $doctor, $consultant_fee, $date, $inserted_id, $redirectlink)
    {

        $patient_details = $this->patient_model->getPatientByOnlinecenter($patient);
        $user = $this->ion_auth->get_user_id();
        $doctorname = $this->doctor_model->getDoctorById($doctor)->name;
        $pay = $this->db->get_where('payment', array('id' => $inserted_id))->row();
        $hospital_id = $this->db->get_where('payment', array('id' => $inserted_id))->row()->hospital_id;
        if ($deposit_type == 'Card') {
            $gateway = $this->db->get_where('settings', array('hospital_id' => $patient_details->hospital_id))->row()->payment_gateway;
            if ($gateway == 'PayPal') {

                $card_type = $data['cardtype'];
                $card_number = $data['card_number'];
                $expire_date = $data['expire_date'];
                $cardHoldername = $data['cardHoldername'];
                $cvv = $data['cvv'];

                $all_details = array(
                    'patient' => $patient,
                    'date' => $date,
                    'amount' => $consultant_fee,
                    'doctor' => $doctor,
                    'currency' => $currency,
                    'superadmin' => $superadmin,
                    'gross_total' => $data['grand_total'],
                    //'hospital_amount' => $hospital_amount,
                    // 'doctor_amount' => $doctor_amount,
                    'patient_name' => $patient_details->name,
                    'patient_phone' => $patient_details->phone,
                    'patient_address' => $patient_details->address,
                    'doctor_name' => $doctorname,
                    'date_string' => date('d-m-y', $date),
                    'deposited_amount' => $data['deposited_amount'],
                    'payment_id' => $inserted_id,
                    'card_type' => $card_type,
                    'card_number' => $card_number,
                    'expire_date' => $expire_date,
                    'cvv' => $cvv,
                    'from' => 'appointment',
                    'user' => $this->ion_auth->get_user_id(),
                    'cardholdername' => $cardHoldername,
                    'from' => $redirectlink,
                    'hospital_id' => $hospital_id,
                );

                $this->paypal->paymentPaypal($all_details);
            } elseif ($gateway == 'Stripe') {

                $card_number = $data['card_number'];
                $expire_date = $data['expire_date'];

                $cvv = $data['cvv'];

                $token = $data['token'];
                $stripe = $this->db->get_where('paymentGateway', array('hospital_id' => $hospital_id, 'name =' => 'Stripe'))->row();
                \Stripe\Stripe::setApiKey($stripe->secret);
                $charge = \Stripe\Charge::create(array(
                    "amount" => $data['deposited_amount'] * 100,
                    "currency" => "usd",
                    "source" => $token,
                ));
                $chargeJson = $charge->jsonSerialize();
                if ($chargeJson['status'] == 'succeeded') {
                    $data1 = array(
                        'date' => $date,
                        'patient' => $patient,
                        'doctor' => $doctor,
                        'superadmin' => $superadmin,
                        'payment_id' => $inserted_id,
                        'deposited_amount' => $data['deposited_amount'],
                        'deposit_type' => 'Card',
                        'amount_received_id' => $inserted_id . '.' . 'gp',
                        'gateway' => 'Stripe',
                        'user' => $user,
                        'payment_from' => 'appointment',
                    );

                    if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {

                        $this->finance_model->insertDeposit($data1);
                    } else {
                        $data1['hospital_id'] = $hospital_id;

                        $data1['onlinecenter_id'] = $pay->onlinecenter_id;
                        if (!empty($pay->casetaker_id)) {
                            $data1['casetaker_id'] = $pay->casetaker_id;
                        }
                        $this->finance_model->insertDepositByOnlinecenter($data1);
                    }

                    $data_payment = array('amount_received' => $data['deposited_amount'], 'deposit_type' => $deposit_type, 'status' => 'paid', 'date' => time(), 'date_string' => date('d-m-y', time()));
                    $this->finance_model->updatePayment($inserted_id, $data_payment);
                    $appointment_id = $this->finance_model->getPaymentByIdForOnlinecenter($inserted_id)->appointment_id;

                    $appointment_details = $this->appointment_model->getAppointmentById($appointment_id);

                    if ($appointment_details->status == 'Requested' || $appointment_details->status == 'Pending Confirmation') {

                        $data_appointment_status = array('payment_status' => 'paid');
                    } else {
                        $data_appointment_status = array('payment_status' => 'paid');
                    }

                    $this->appointment_model->updateAppointment($appointment_id, $data_appointment_status);
                    $this->session->set_flashdata('feedback', lang('payment_successful'));
                } else {
                    $this->session->set_flashdata('feedback', lang('transaction_failed'));
                }
            } elseif ($gateway == 'Pay U Money') {
                redirect("payu/check5?deposited_amount=" . $data['deposited_amount'] . '&payment_id=' . $inserted_id . '&redirectlink=' . $redirectlink);
            } elseif ($gateway == 'Paystack') {

                $ref = date('Y') . '-' . rand() . date('d') . '-' . date('m');
                $amount_in_kobo = $data['deposited_amount'];
                $this->load->module('paystack');
                $this->paystack->paystack_standard($amount_in_kobo, $ref, $patient, $inserted_id, $this->ion_auth->get_user_id(), $redirectlink);

                // $email=$patient_email;
            } else {
                $this->session->set_flashdata('feedback', lang('payment_failed_no_gateway_selected'));
                $appointment_id = $this->finance_model->getPaymentById($inserted_id)->appointment_id;
                $data_appointment_status = array('payment_status' => 'unpaid');
                $this->appointment_model->updateAppointment($appointment_id, $data_appointment_status);
            }
        } elseif ($deposit_type == 'Aamarpay') {
            // $patient = $this->input->post('patient');
            $patient_details = $this->patient_model->getPatientByOnlinecenter($patient);
            $doctor = $this->input->post('doctor');
            $doctor_details = $this->doctor_model->getDoctorById($doctor);
            $pgateway = $this->db->get_where('paymentGateway', array('hospital_id' => $doctor_details->hospital_id, 'name =' => 'Aamarpay'))->row();
            // if ($patient == 'add_new') {
            //     $patient_name =  $this->input->post('p_name');
            //     $patient_email =  $this->input->post('p_email');
            //     $patient_address =  $this->input->post('address');
            //     $patient_phone =  $this->input->post('phone');
            //  }else{
            $patient_name = $patient_details->name;
            $patient_email = $patient_details->email;
            $patient_address = $patient_details->address;
            $patient_phone = $patient_details->phone;
            //  }
            //  $url = 'https://sandbox.aamarpay.com/request.php';
            $url = 'https://secure.aamarpay.com/request.php';
            // live url https://secure.aamarpay.com/request.php
            $fields = array(
                'store_id' => $pgateway->store_id, //store id will be aamarpay,  contact integration@aamarpay.com for test/live id
                'amount' => $this->input->post('deposited_amount'), //transaction amount
                'payment_type' => 'VISA', //no need to change
                'currency' => 'BDT', //currenct will be USD/BDT
                'tran_id' => rand(1111111, 9999999), //transaction id must be unique from your end
                'cus_name' => $patient_name, //customer name
                'cus_email' => 'hdhomeo@gmail.com', //customer email address
                'cus_add1' => $patient_address, //customer address
                'cus_add2' => $patient_address, //customer address
                'cus_city' => 'Dhaka', //customer city
                'cus_state' => 'Dhaka', //state
                'cus_postcode' => '1206', //postcode or zipcode
                'cus_country' => 'Bangladesh', //country
                'cus_phone' => $patient_phone, //customer phone number
                'cus_fax' => 'Not¬Applicable', //fax
                'ship_name' => $patient_name, //ship name
                'ship_add1' => $patient_address, //ship address
                'ship_add2' => $patient_address,
                'ship_city' => 'Dhaka',
                'ship_state' => 'Dhaka',
                'ship_postcode' => '1212',
                'ship_country' => 'Bangladesh',
                'desc' => 'payment description',
                'success_url' => 'https://hdhealth.org/hospital/appointment/success', //your success route
                'fail_url' => 'https://hdhealth.org/hospital/appointment', //your fail route
                'cancel_url' => 'https://hdhealth.org/hospital/appointment', //your cancel url
                'opt_a' => 'Reshad', //optional paramter
                'opt_b' => 'Akil',
                'opt_c' => 'Liza',
                'opt_d' => $inserted_id,
                'signature_key' => $pgateway->signature_key,
            ); //signature key will provided aamarpay, contact integration@aamarpay.com for test/live signature key

            $fields_string = http_build_query($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $url_forward = str_replace('"', '', stripslashes(curl_exec($ch)));
            curl_close($ch);

            $this->redirect_to_merchant($url_forward);
        } elseif ($deposit_type == 'Paytm') {
            $data1 = array();
            $data1 = array(
                'date' => $date,
                'patient' => $patient,
                'deposited_amount' => $data['deposited_amount'],
                'payment_id' => $inserted_id,
                'amount_received_id' => $inserted_id . '.' . 'gp',
                'deposit_type' => $deposit_type,
                'user' => $this->ion_auth->get_user_id(),
                'payment_from' => 'appointment',
                'account_number' => $data['account_number'],
                'transaction_id' => $data['transaction_id'],
            );

            $data1['hospital_id'] = $hospital_id;

            $data1['onlinecenter_id'] = $pay->onlinecenter_id;
            if (!empty($pay->casetaker_id)) {
                $data1['casetaker_id'] = $pay->casetaker_id;
            }
            $this->finance_model->insertDepositByOnlinecenter($data1);
            //            }

            $this->session->set_flashdata('feedback', lang('payment_successful'));
        } else {
            $data1 = array();
            $data1 = array(
                'date' => $date,
                'patient' => $patient,
                'doctor' => $doctor,
                'superadmin' => $superadmin,
                'deposited_amount' => $data['deposited_amount'],
                'payment_id' => $inserted_id,
                'amount_received_id' => $inserted_id . '.' . 'gp',
                'deposit_type' => $deposit_type,
                'user' => $this->ion_auth->get_user_id(),
                'payment_from' => 'appointment',

            );
            if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {

                $this->finance_model->insertDeposit($data1);
            } else {
                $data1['hospital_id'] = $hospital_id;

                $data1['onlinecenter_id'] = $pay->onlinecenter_id;
                if (!empty($pay->casetaker_id)) {
                    $data1['casetaker_id'] = $pay->casetaker_id;
                }
                $this->finance_model->insertDepositByOnlinecenter($data1);
            }

            $data_payment = array('amount_received' => $data['deposited_amount'], 'deposit_type' => 'Cash', 'status' => 'paid');
            $this->finance_model->updatePayment($inserted_id, $data_payment);

            $appointment_id = $this->finance_model->getPaymentById($inserted_id)->appointment_id;

            $appointment_details = $this->appointment_model->getAppointmentById($appointment_id);

            if ($appointment_details->status == 'Requested' || $appointment_details->status == 'Pending Confirmation') {

                $data_appointment_status = array('payment_status' => 'paid');
            } else {
                $data_appointment_status = array('payment_status' => 'paid');
            }

            $this->appointment_model->updateAppointment($appointment_id, $data_appointment_status);
            $this->session->set_flashdata('feedback', lang('payment_successful'));
        }
        if ($redirectlink == '10') {
            redirect("appointment");
        } elseif ($redirectlink == 'my_today') {
            redirect("appointment/todays");
        } elseif ($redirectlink == 'upcoming') {
            redirect("appointment/upcoming");
        } elseif ($redirectlink == 'med_his') {
            redirect("patient/medicalHistory?id=" . $patient);
        } elseif ($redirectlink == 'request') {
            redirect("appointment/request");
        } elseif ($redirectlink == 'modal') {
            redirect("appointment/refresh");
        }
    }

    public function addNewByOnlinecenter()
    {
        $id = $this->input->post('id');
        $hospital_id = $this->input->post('hospital_id');
        $onlinecenter_id = $this->input->post('onlinecenter_id');
        $casetaker_id = $this->input->post('casetaker_id');
        $patient = $this->input->post('patient');
        $doctor = $this->input->post('doctor');
        $date = $this->input->post('date');
        if (!empty($date)) {
            $date = strtotime($date);
        }

        $superadmin = $this->input->post('superadmin');
        $time_slot = $this->input->post('time_slot');

        $time_slot_explode = explode('To', $time_slot);

        $s_time = trim($time_slot_explode[0]);
        $e_time = trim($time_slot_explode[1]);

        $remarks = $this->input->post('remarks');

        $sms = $this->input->post('sms');

        $status = $this->input->post('status');

        $redirect = $this->input->post('redirect');

        $request = $this->input->post('request');

        if (empty($request)) {
            $request = '';
        }

        $user = $this->ion_auth->get_user_id();

        if ($this->ion_auth->in_group(array('Patient'))) {
            $user = '';
        }

        if ((empty($id))) {
            $add_date = date('m/d/y');
            $registration_time = time();
            $patient_add_date = $add_date;
            $patient_registration_time = $registration_time;
        } else {
            $add_date = $this->appointment_model->getAppointmentById($id)->add_date;
            $registration_time = $this->appointment_model->getAppointmentById($id)->registration_time;
        }

        $s_time_key = $this->getArrayKey($s_time);

        $p_name = $this->input->post('p_name');
        $p_email = $this->input->post('p_email');
        if (empty($p_email)) {
            $p_email = $p_name . '-' . rand(1, 1000) . '-' . $p_name . '-' . rand(1, 1000) . '@example.com';
        }
        if (!empty($p_name)) {
            $password = $p_name . '-' . rand(1, 100000000);
        }
        $p_phone = $this->input->post('p_phone');
        $p_age = $this->input->post('p_age');
        $p_gender = $this->input->post('p_gender');
        $patient_id = rand(10000, 1000000);

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($patient == 'add_new') {
            $this->form_validation->set_rules('p_name', 'Patient Name', 'trim|required|min_length[1]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('p_phone', 'Patient Phone', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        }

        // Validating Name Field
        $this->form_validation->set_rules('patient', 'Patient', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        // Validating Password Field
        $this->form_validation->set_rules('doctor', 'Doctor', 'trim|required|min_length[1]|max_length[100]|xss_clean');

        // Validating Email Field
        $this->form_validation->set_rules('date', 'Date', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        // Validating Email Field
        $this->form_validation->set_rules('s_time', 'Start Time', 'trim|min_length[1]|max_length[100]|xss_clean');
        // Validating Email Field
        $this->form_validation->set_rules('e_time', 'End Time', 'trim|min_length[1]|max_length[100]|xss_clean');
        // Validating Address Field
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|min_length[1]|max_length[1000]|xss_clean');

        if ($this->form_validation->run() == false) {
            if (!empty($id)) {
                redirect("appointment/editAppointment?id=$id");
            } else {
                $data['patients'] = $this->patient_model->getPatient();
                $data['doctors'] = $this->doctor_model->getDoctor();
                $data['settings'] = $this->settings_model->getSettings();
                $this->load->view('home/dashboard', $data);
                $this->load->view('onlinecenter/add_appointment', $data);
                $this->load->view('home/footer');
            }
        } else {

            if ($patient == 'add_new') {

                $limit = $this->patient_model->getLimit();
                if ($limit <= 0) {
                    $this->session->set_flashdata('feedback', lang('patient_limit_exceed'));
                    redirect('patient');
                }

                $data_p = array(
                    'patient_id' => $patient_id,
                    'name' => $p_name,
                    'email' => $p_email,
                    'phone' => $p_phone,
                    'sex' => $p_gender,
                    'age' => $p_age,
                    'add_date' => $patient_add_date,
                    'registration_time' => $patient_registration_time,
                    'how_added' => 'from_appointment',
                );
                $username = $this->input->post('p_name');
                // Adding New Patient
                if ($this->ion_auth->email_check($p_email)) {
                    $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));
                    if (!empty($redirect)) {
                        redirect($redirect);
                    } else {
                        redirect('appointment');
                    }
                } else {
                    $dfg = 5;
                    $this->ion_auth->register($username, $password, $p_email, $dfg);
                    $ion_user_id = $this->db->get_where('users', array('email' => $p_email))->row()->id;
                    $this->patient_model->insertPatient($data_p);
                    $patient_user_id = $this->db->get_where('patient', array('email' => $p_email))->row()->id;
                    $id_info = array('ion_user_id' => $ion_user_id);
                    $this->patient_model->updatePatient($patient_user_id, $id_info);
                    $this->hospital_model->addHospitalIdToIonUser($ion_user_id, $this->hospital_id);
                }

                $patient = $patient_user_id;
                //    }
            }

            $patient_phone = $this->patient_model->getPatientByOnlinecenter($patient)->phone;

            if (empty($id)) {
                $room_id = 'hms-meeting-' . $patient_phone . '-' . rand(10000, 1000000) . '-' . $this->hospital_id;
                // $live_meeting_link = 'https://meet.jit.si/' . $room_id;
                $live_meeting_link = 'https://8x8.vc/' . $room_id;
            } else {
                $appointment_details = $this->appointment_model->getAppointmentByOnlinecenter($id);
                $room_id = $appointment_details->room_id;
                $live_meeting_link = $appointment_details->live_meeting_link;
            }

            $patientname = $this->patient_model->getPatientByOnlinecenter($patient)->name;
            $doctorname = $this->doctor_model->getDoctorByOnlinecenter($doctor)->name;
            $data = array();
            $data = array(
                'patient' => $patient,
                'patientname' => $patientname,
                'doctor' => $doctor,
                'doctorname' => $doctorname,
                'date' => $date,
                's_time' => $s_time,
                'e_time' => $e_time,
                'hospital_id' => $hospital_id,
                'onlinecenter_id' => $onlinecenter_id,
                'casetaker_id' => $casetaker_id,
                'time_slot' => $time_slot,
                'remarks' => $remarks,
                'superadmin' => $superadmin,
                'add_date' => $add_date,
                'registration_time' => $registration_time,
                'status' => $status,
                's_time_key' => $s_time_key,
                'user' => $user,
                'request' => $request,
                'room_id' => $room_id,
                'live_meeting_link' => $live_meeting_link,
            );
            $username = $this->input->post('name');
            if (empty($id)) { // Adding New department
                $this->appointment_model->insertAppointmentByOnlinecenter($data);

                $patient_doctor = $this->patient_model->getPatientByOnlinecenter($patient)->doctor;

                $patient_doctors = explode(',', $patient_doctor);

                if (!in_array($doctor, $patient_doctors)) {
                    $patient_doctors[] = $doctor;
                    $doctorss = implode(',', $patient_doctors);
                    $data_d = array();
                    $data_d = array('doctor' => $doctorss);
                    $this->patient_model->updatePatient($patient, $data_d);
                }
                $response = $this->sendSmsDuringAppointment($id, $data, $patient, $doctor, $status, $hospital_id);
                $this->session->set_flashdata('feedback', lang('added'));
            } else { // Updating department
                $previous_status = $this->appointment_model->getAppointmentById($id)->status;
                if ($previous_status != "Confirmed") {
                    if ($status == "Confirmed") {
                        $response = $this->sendSmsDuringAppointment($id, $data, $patient, $doctor, $status, $hospital_id);
                    }
                }
                $this->appointment_model->updateAppointment($id, $data);

                $this->session->set_flashdata('feedback', lang('updated'));
            }

            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect('onlinecenter/appointment');
            }
        }
    }



    function sendSmsByCountryy($to, $message, $data, $country, $hospital_id)
    {

        if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $bulksmsSettings = $this->sms_model->getSmsSettingsByGatewayName('Bulksmsbd');
        } else {
            $bulksmsSettings = $this->sms_model->getSmsSettingsByGatewayNameByhospital($hospital_id, 'Bulksmsbd');
        }

        if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $twilioSettings = $this->sms_model->getSmsSettingsByGatewayName('Twilio');
        } else {
            $twilioSettings = $this->sms_model->getSmsSettingsByGatewayNameByhospital($hospital_id, 'Twilio');
        }

        $j = sizeof($data);
        foreach ($data as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if ($key2 != 'country') {

                    if ($value['country'] == 'India') {
                        $sid = $twilioSettings->sid;
                        $token = $twilioSettings->token;
                        $sendername = $twilioSettings->sendernumber;
                        $message_service_id = $twilioSettings->message_service_id;
                        if (!empty($sid) && !empty($token) && !empty($sendername)) {
                            $client = new Client($sid, $token);
                            $client->messages->create(
                                $key2, // Text this number
                                array(
                                    'messagingServiceSid' => $message_service_id, // From a valid Twilio number
                                    'body' => $value2
                                )
                            );
                        }
                    }

                    if ($value['country'] == 'Bangladesh') {

                        $apikey = $bulksmsSettings->login_password;
                        $senderId = $bulksmsSettings->login_id;
                        $value2 = urlencode($value2);
                        $response = file_get_contents('https://bulksmsbd.net/api/smsapi?api_key=' . $apikey . '&type=text&number=' . $key2 . '&senderid=' . $senderId . '&message=' . $value2);
                        $status1 = json_decode($response, true);
                        if ($status1['response_code'] == '202') {
                            $status = 'success';
                        }
                    }
                }
            }
        }
    }



    public function sendSmsDuringAppointment($id, $data, $patient, $doctor, $status, $hospital_id, $appointment_id, $password)
    {
        if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $set['settings'] = $this->settings_model->getSettings();
        } else {
            $set['settings'] = $this->db->get_where('settings', array('hospital_id' => $hospital_id))->row();
        }
        $appointment_details = $this->appointment_model->getAppointmentById($appointment_id);
        $patientdetails = $this->patient_model->getPatientById($patient);
        $doctordetails = $this->doctor_model->getDoctorById($doctor);
        if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            if (empty($id)) {
                if ($status != 'Confirmed') {
                    $autosmsdoc = $this->sms_model->getAutoSmsByType('doctor');
                    $autosms = $this->sms_model->getAutoSmsByType('appoinment_creation');
                    $autoemail = $this->email_model->getAutoEmailByType('appoinment_creation');
                } else {
                    $autosmsdoc = $this->sms_model->getAutoSmsByType('doctor');
                    $autosms = $this->sms_model->getAutoSmsByType('appoinment_confirmation');
                    $autoemail = $this->email_model->getAutoEmailByType('appoinment_confirmation');
                }
            } else {
                $autosmsdoc = $this->sms_model->getAutoSmsByType('doctor');
                $autosms = $this->sms_model->getAutoSmsByType('appoinment_confirmation');
                $autoemail = $this->email_model->getAutoEmailByType('appoinment_confirmation');
            }
        } else {
            if (empty($id)) {
                if ($status != 'Confirmed') {
                    $autosmsdoc = $this->sms_model->getAutoSmsByTypeByHospitalId($hospital_id, 'doctor');
                    $autosms = $this->sms_model->getAutoSmsByTypeByHospitalId($hospital_id, 'appoinment_creation');
                    $autoemail = $this->email_model->getAutoEmailByTypeByHospitalId($hospital_id, 'appoinment_creation');
                } else {
                    $autosmsdoc = $this->sms_model->getAutoSmsByTypeByHospitalId($hospital_id, 'doctor');
                    $autosms = $this->sms_model->getAutoSmsByTypeByHospitalId($hospital_id, 'appoinment_confirmation');
                    $autoemail = $this->email_model->getAutoEmailByTypeByHospitalId($hospital_id, 'appoinment_confirmation');
                }
            } else {
                $autosmsdoc = $this->sms_model->getAutoSmsByTypeByHospitalId($hospital_id, 'doctor');
                $autosms = $this->sms_model->getAutoSmsByTypeByHospitalId($hospital_id, 'appoinment_confirmation');
                $autoemail = $this->email_model->getAutoEmailByTypeByHospitalId($hospital_id, 'appoinment_confirmation');
            }
        }
        // $visit_place = $doctordetails->chamber_address;

        if (!empty($patient)) {
            $message = $autosms->message;
            $to = $patientdetails->phone;
            $country = $patientdetails->country;
            $name1 = explode(' ', $patientdetails->name);
            if (!isset($name1[1])) {
                $name1[1] = null;
            }
            if ($appointment_details->visit_type == 'Online Visit') {
                $visit_place = $appointment_details->live_meeting_link;
            }
            if ($appointment_details->visit_type == 'Chamber Visit') {
                $visit_place = $doctordetails->chamber_address;
            }
            $base_url = str_replace(array('http://', 'https://', ' '), '', base_url()) . "auth/login";
            $data1 = array(
                'firstname' => $name1[0],
                'lastname' => $name1[1],
                'name' => $patientdetails->name,
                'email' => $patientdetails->email,
                'patient_id' => $patientdetails->patient_id,
                'doctorname' => $doctordetails->name,
                'base_url' => $base_url,
                'password' => $password,
                'doctor_about_link' => $doctordetails->about_baseurl . $doctordetails->about_title,
                'visit_place' => $visit_place,
                'appointment_no' => $appointment_details->id,
                'appoinmentdate' => date('d-m-Y', $data['date']),
                'time_slot' => $data['time_slot'],
                'hospital_name' => $set['settings']->system_vendor,
            );

            if ($autosms->status == 'Active') {
                $messageprint = $this->parser->parse_string($message, $data1);

                $data2[] = array($to => $messageprint, 'country' => $country);
                //  $response = $this->sms->sendSmsDuringAppointmentCreation($to, $message, $data2, $hospital_id);
                $response = $this->sendSmsByCountryy($to, $message, $data2, $country, $hospital_id);
                // $response = '';
                // if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                //     $bulksmsSettings = $this->sms_model->getSmsSettingsByGatewayName('Bulksmsbd');
                // } else {
                //     $bulksmsSettings = $this->sms_model->getSmsSettingsByGatewayNameByhospital($hospital_id, 'Bulksmsbd');
                // }

                // if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                //     $twilioSettings = $this->sms_model->getSmsSettingsByGatewayName('Twilio');
                // } else {
                //     $twilioSettings = $this->sms_model->getSmsSettingsByGatewayNameByhospital($hospital_id, 'Twilio');
                // }







                // $url = "http://66.45.237.70/api.php";
                // // $number="8801749335";
                // // $text="Hello Bangladesh";
                // $login_id = 'health';
                // $login_password = '6W8BZ5SK';
                // $data = array(
                //     'username' => $login_id,
                //     'password' => $login_password,
                //     'number' => '01749335508',
                //     'message' => 'hi'
                // );

                // $ch = curl_init(); // Initialize cURL
                // curl_setopt($ch, CURLOPT_URL, $url);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // $smsresult = curl_exec($ch);
                // $p = explode("|", $smsresult);
                // $sendstatus = $p[0];





            }
        }

        if (!empty($doctor)) {
            $appointment_link = str_replace(array('http://', 'https://', ' '), '', base_url()) . "appointment";
            $message = $autosmsdoc->message;
            $to = $doctordetails->phone;
            $country = $doctordetails->country;
            $name1 = explode(' ', $doctordetails->name);
            if (!isset($name1[1])) {
                $name1[1] = null;
            }
            if ($appointment_details->visit_type == 'Online Visit') {
                $visit_place = $appointment_details->live_meeting_link;
            }
            if ($appointment_details->visit_type == 'Chamber Visit') {
                $visit_place = $doctordetails->chamber_address;
            }
            $data1 = array(
                'firstname' => $name1[0],
                'lastname' => $name1[1],
                'name' => $patientdetails->name,
                'doctorname' => $doctordetails->name,
                'visit_place' => $visit_place,
                'appointment_link' => $appointment_link,
                'appointment_no' => $appointment_details->id,
                'appoinmentdate' => date('d-m-Y', $data['date']),
                'time_slot' => $data['time_slot'],
                'hospital_name' => $set['settings']->system_vendor,
            );

            if ($autosmsdoc->status == 'Active') {
                $messageprint = $this->parser->parse_string($message, $data1);

                $data2[] = array($to => $messageprint, 'country' => $country);
                //  $response = $this->sms->sendSmsDuringAppointmentCreation($to, $message, $data2, $hospital_id);
                $response = $this->sendSmsByCountryy($to, $message, $data2, $country, $hospital_id);
                // $response = '';
            }
        }

        if ($autoemail->status == 'Active') {
            if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                $mail_provider = $this->settings_model->getSettings()->emailtype;
                $email_Settings = $this->email_model->getEmailSettingsByType($mail_provider);
            } else {
                $mail_provider = $this->db->get_where('settings', array('hospital_id' => $hospital_id))->row()->email_type;
                $email_Settings = $this->email_model->getEmailSettingsByTypeByHospital($mail_provider, $hospital_id);
            }

            $settngs_name = $set['settings']->system_vendor;

            $message1 = $autoemail->message;
            $messageprint1 = $this->parser->parse_string($message1, $data1);
            if ($mail_provider == 'Domain Email') {
                $this->email->from($email_Settings->admin_email);
            }
            if ($mail_provider == 'Smtp') {
                $this->email->from($email_Settings->user, $settngs_name);
            }
            $this->email->to($patientdetails->email);
            $this->email->subject(lang('appointment'));
            $this->email->message($messageprint1);
            $this->email->send();
        }
        return $response;
    }

    public function getArrayKey($s_time)
    {
        $all_slot = array(
            0 => '12:00 AM',
            1 => '12:05 AM',
            2 => '12:10 AM',
            3 => '12:15 AM',
            4 => '12:20 AM',
            5 => '12:25 AM',
            6 => '12:30 AM',
            7 => '12:35 AM',
            8 => '12:40 PM',
            9 => '12:45 AM',
            10 => '12:50 AM',
            11 => '12:55 AM',
            12 => '01:00 AM',
            13 => '01:05 AM',
            14 => '01:10 AM',
            15 => '01:15 AM',
            16 => '01:20 AM',
            17 => '01:25 AM',
            18 => '01:30 AM',
            19 => '01:35 AM',
            20 => '01:40 AM',
            21 => '01:45 AM',
            22 => '01:50 AM',
            23 => '01:55 AM',
            24 => '02:00 AM',
            25 => '02:05 AM',
            26 => '02:10 AM',
            27 => '02:15 AM',
            28 => '02:20 AM',
            29 => '02:25 AM',
            30 => '02:30 AM',
            31 => '02:35 AM',
            32 => '02:40 AM',
            33 => '02:45 AM',
            34 => '02:50 AM',
            35 => '02:55 AM',
            36 => '03:00 AM',
            37 => '03:05 AM',
            38 => '03:10 AM',
            39 => '03:15 AM',
            40 => '03:20 AM',
            41 => '03:25 AM',
            42 => '03:30 AM',
            43 => '03:35 AM',
            44 => '03:40 AM',
            45 => '03:45 AM',
            46 => '03:50 AM',
            47 => '03:55 AM',
            48 => '04:00 AM',
            49 => '04:05 AM',
            50 => '04:10 AM',
            51 => '04:15 AM',
            52 => '04:20 AM',
            53 => '04:25 AM',
            54 => '04:30 AM',
            55 => '04:35 AM',
            56 => '04:40 AM',
            57 => '04:45 AM',
            58 => '04:50 AM',
            59 => '04:55 AM',
            60 => '05:00 AM',
            61 => '05:05 AM',
            62 => '05:10 AM',
            63 => '05:15 AM',
            64 => '05:20 AM',
            65 => '05:25 AM',
            66 => '05:30 AM',
            67 => '05:35 AM',
            68 => '05:40 AM',
            69 => '05:45 AM',
            70 => '05:50 AM',
            71 => '05:55 AM',
            72 => '06:00 AM',
            73 => '06:05 AM',
            74 => '06:10 AM',
            75 => '06:15 AM',
            76 => '06:20 AM',
            77 => '06:25 AM',
            78 => '06:30 AM',
            79 => '06:35 AM',
            80 => '06:40 AM',
            81 => '06:45 AM',
            82 => '06:50 AM',
            83 => '06:55 AM',
            84 => '07:00 AM',
            85 => '07:05 AM',
            86 => '07:10 AM',
            87 => '07:15 AM',
            88 => '07:20 AM',
            89 => '07:25 AM',
            90 => '07:30 AM',
            91 => '07:35 AM',
            92 => '07:40 AM',
            93 => '07:45 AM',
            94 => '07:50 AM',
            95 => '07:55 AM',
            96 => '08:00 AM',
            97 => '08:05 AM',
            98 => '08:10 AM',
            99 => '08:15 AM',
            100 => '08:20 AM',
            101 => '08:25 AM',
            102 => '08:30 AM',
            103 => '08:35 AM',
            104 => '08:40 AM',
            105 => '08:45 AM',
            106 => '08:50 AM',
            107 => '08:55 AM',
            108 => '09:00 AM',
            109 => '09:05 AM',
            110 => '09:10 AM',
            111 => '09:15 AM',
            112 => '09:20 AM',
            113 => '09:25 AM',
            114 => '09:30 AM',
            115 => '09:35 AM',
            116 => '09:40 AM',
            117 => '09:45 AM',
            118 => '09:50 AM',
            119 => '09:55 AM',
            120 => '10:00 AM',
            121 => '10:05 AM',
            122 => '10:10 AM',
            123 => '10:15 AM',
            124 => '10:20 AM',
            125 => '10:25 AM',
            126 => '10:30 AM',
            127 => '10:35 AM',
            128 => '10:40 AM',
            129 => '10:45 AM',
            130 => '10:50 AM',
            131 => '10:55 AM',
            132 => '11:00 AM',
            133 => '11:05 AM',
            134 => '11:10 AM',
            135 => '11:15 AM',
            136 => '11:20 AM',
            137 => '11:25 AM',
            138 => '11:30 AM',
            139 => '11:35 AM',
            140 => '11:40 AM',
            141 => '11:45 AM',
            142 => '11:50 AM',
            143 => '11:55 AM',
            144 => '12:00 PM',
            145 => '12:05 PM',
            146 => '12:10 PM',
            147 => '12:15 PM',
            148 => '12:20 PM',
            149 => '12:25 PM',
            150 => '12:30 PM',
            151 => '12:35 PM',
            152 => '12:40 PM',
            153 => '12:45 PM',
            154 => '12:50 PM',
            155 => '12:55 PM',
            156 => '01:00 PM',
            157 => '01:05 PM',
            158 => '01:10 PM',
            159 => '01:15 PM',
            160 => '01:20 PM',
            161 => '01:25 PM',
            162 => '01:30 PM',
            163 => '01:35 PM',
            164 => '01:40 PM',
            165 => '01:45 PM',
            166 => '01:50 PM',
            167 => '01:55 PM',
            168 => '02:00 PM',
            169 => '02:05 PM',
            170 => '02:10 PM',
            171 => '02:15 PM',
            172 => '02:20 PM',
            173 => '02:25 PM',
            174 => '02:30 PM',
            175 => '02:35 PM',
            176 => '02:40 PM',
            177 => '02:45 PM',
            178 => '02:50 PM',
            179 => '02:55 PM',
            180 => '03:00 PM',
            181 => '03:05 PM',
            182 => '03:10 PM',
            183 => '03:15 PM',
            184 => '03:20 PM',
            185 => '03:25 PM',
            186 => '03:30 PM',
            187 => '03:35 PM',
            188 => '03:40 PM',
            189 => '03:45 PM',
            190 => '03:50 PM',
            191 => '03:55 PM',
            192 => '04:00 PM',
            193 => '04:05 PM',
            194 => '04:10 PM',
            195 => '04:15 PM',
            196 => '04:20 PM',
            197 => '04:25 PM',
            198 => '04:30 PM',
            199 => '04:35 PM',
            200 => '04:40 PM',
            201 => '04:45 PM',
            202 => '04:50 PM',
            203 => '04:55 PM',
            204 => '05:00 PM',
            205 => '05:05 PM',
            206 => '05:10 PM',
            207 => '05:15 PM',
            208 => '05:20 PM',
            209 => '05:25 PM',
            210 => '05:30 PM',
            211 => '05:35 PM',
            212 => '05:40 PM',
            213 => '05:45 PM',
            214 => '05:50 PM',
            215 => '05:55 PM',
            216 => '06:00 PM',
            217 => '06:05 PM',
            218 => '06:10 PM',
            219 => '06:15 PM',
            220 => '06:20 PM',
            221 => '06:25 PM',
            222 => '06:30 PM',
            223 => '06:35 PM',
            224 => '06:40 PM',
            225 => '06:45 PM',
            226 => '06:50 PM',
            227 => '06:55 PM',
            228 => '07:00 PM',
            229 => '07:05 PM',
            230 => '07:10 PM',
            231 => '07:15 PM',
            232 => '07:20 PM',
            233 => '07:25 PM',
            234 => '07:30 PM',
            235 => '07:35 PM',
            236 => '07:40 PM',
            237 => '07:45 PM',
            238 => '07:50 PM',
            239 => '07:55 PM',
            240 => '08:00 PM',
            241 => '08:05 PM',
            242 => '08:10 PM',
            243 => '08:15 PM',
            244 => '08:20 PM',
            245 => '08:25 PM',
            246 => '08:30 PM',
            247 => '08:35 PM',
            248 => '08:40 PM',
            249 => '08:45 PM',
            250 => '08:50 PM',
            251 => '08:55 PM',
            252 => '09:00 PM',
            253 => '09:05 PM',
            254 => '09:10 PM',
            255 => '09:15 PM',
            256 => '09:20 PM',
            257 => '09:25 PM',
            258 => '09:30 PM',
            259 => '09:35 PM',
            260 => '09:40 PM',
            261 => '09:45 PM',
            262 => '09:50 PM',
            263 => '09:55 PM',
            264 => '10:00 PM',
            265 => '10:05 PM',
            266 => '10:10 PM',
            267 => '10:15 PM',
            268 => '10:20 PM',
            269 => '10:25 PM',
            270 => '10:30 PM',
            271 => '10:35 PM',
            272 => '10:40 PM',
            273 => '10:45 PM',
            274 => '10:50 PM',
            275 => '10:55 PM',
            276 => '11:00 PM',
            277 => '11:05 PM',
            278 => '11:10 PM',
            279 => '11:15 PM',
            280 => '11:20 PM',
            281 => '11:25 PM',
            282 => '11:30 PM',
            283 => '11:35 PM',
            284 => '11:40 PM',
            285 => '11:45 PM',
            286 => '11:50 PM',
            287 => '11:55 PM',
        );

        $key = array_search($s_time, $all_slot);
        return $key;
    }

    public function getAppointmentByJasonByDoctor()
    {

        $id = $this->input->get('id');

        $query = $this->appointment_model->getAppointmentByDoctor($id);

        $jsonevents = array();

        foreach ($query as $entry) {

            $doctor = $this->doctor_model->getDoctorById($entry->doctor);
            if (!empty($doctor)) {
                $doctor = $doctor->name;
            } else {
                $doctor = '';
            }
            $time_slot = $entry->time_slot;
            $time_slot_new = explode(' To ', $time_slot);
            $start_time = explode(' ', $time_slot_new[0]);
            $end_time = explode(' ', $time_slot_new[1]);

            if ($start_time[1] == 'AM') {
                $start_time_second = explode(':', $start_time[0]);
                if ($start_time_second[0] == 12) {
                    $day_start_time_second = $start_time_second[1] * 60;
                } else {
                    $day_start_time_second = $start_time_second[0] * 60 * 60 + $start_time_second[1] * 60;
                }
            } else {
                $start_time_second = explode(':', $start_time[0]);
                if ($start_time_second[0] == 12) {
                    $day_start_time_second = 12 * 60 * 60 + $start_time_second[1] * 60;
                } else {
                    $day_start_time_second = 12 * 60 * 60 + $start_time_second[0] * 60 * 60 + $start_time_second[1] * 60;
                }
            }

            if ($end_time[1] == 'AM') {
                $end_time_second = explode(':', $end_time[0]);
                if ($end_time_second[0] == 12) {
                    $day_end_time_second = $end_time_second[1] * 60;
                } else {
                    $day_end_time_second = $end_time_second[0] * 60 * 60 + $end_time_second[1] * 60;
                }
            } else {
                $end_time_second = explode(':', $end_time[0]);
                if ($end_time_second[0] == 12) {
                    $day_end_time_second = 12 * 60 * 60 + $end_time_second[1] * 60;
                } else {
                    $day_end_time_second = 12 * 60 * 60 + $end_time_second[0] * 60 * 60 + $end_time_second[1] * 60;
                }
            }

            $patient_details = $this->patient_model->getPatientById($entry->patient);

            if (!empty($patient_details)) {
                $patient_mobile = $patient_details->phone;
                $patient_name = $patient_details->name;
            } else {
                $patient_mobile = '';
                $patient_name = '';
            }

            if ($entry->status == 'Pending Confirmation') {
                $appointment_status = lang('pending_confirmation');
            } elseif ($entry->status == 'Confirmed') {
                $appointment_status = lang('confirmed');
            } elseif ($entry->status == 'Treated') {
                $appointment_status = lang('treated');
            } elseif ($entry->status == 'Cancelled') {
                $appointment_status = lang('cancelled');
            } elseif ($entry->status == 'Requested') {
                $appointment_status = lang('requested');
            }

            $info = '<br/>' . lang('status') . ': ' . $appointment_status . '<br>' . lang('patient') . ': ' . $patient_name . '<br/>' . lang('phone') . ': ' . $patient_mobile . '<br/> Doctor: ' . $doctor . '<br/>' . lang('remarks') . ': ' . $entry->remarks;
            if ($entry->status == 'Pending Confirmation') {

                $color = 'yellowgreen';
            }
            if ($entry->status == 'Confirmed') {
                $color = '#009988';
            }
            if ($entry->status == 'Treated') {
                $color = '#112233';
            }
            if ($entry->status == 'Cancelled') {
                $color = 'red';
            }
            if ($entry->status == 'Requested') {
                $color = '#6883a3';
            }

            $jsonevents[] = array(
                'id' => $entry->id,
                'title' => $info,
                'start' => date('Y-m-d H:i:s', $entry->date + $day_start_time_second),
                'end' => date('Y-m-d H:i:s', $entry->date + $day_end_time_second),
                'color' => $color,
            );
        }

        echo json_encode($jsonevents);
    }

    public function getAppointmentByJason()
    {

        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            $query = $this->appointment_model->getAppointmentByDoctor($doctor);
        } elseif ($this->ion_auth->in_group(array('Patient'))) {
            $patient_ion_id = $this->ion_auth->get_user_id();
            $patient = $this->db->get_where('patient', array('ion_user_id' => $patient_ion_id))->row()->id;
            $query = $this->appointment_model->getAppointmentByPatient($patient);
        } elseif ($this->ion_auth->in_group(array('casetaker'))) {
            $casetaker_ion_id = $this->ion_auth->get_user_id();
            $casetaker = $this->db->get_where('casetaker', array('ion_user_id' => $casetaker_ion_id))->row()->id;
            $query = $this->appointment_model->getAppointmentByCasetaker($casetaker);
        } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
            $onlinecenter_ion_id = $this->ion_auth->get_user_id();
            $onlinecenter = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
            $query = $this->appointment_model->getAppointmentByOnlinecenterList($onlinecenter);
        } else {
            $query = $this->appointment_model->getAppointmentForCalendar();
        }
        $jsonevents = array();

        foreach ($query as $entry) {
            if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                $doctor = $this->doctor_model->getDoctorById($entry->doctor);
            } else {
                $doctor = $this->doctor_model->getDoctorByOnlinecenter($entry->doctor);
            }

            if (!empty($doctor)) {
                $doctor = $doctor->name;
            } else {
                $doctor = '';
            }
            $time_slot = $entry->time_slot;
            $time_slot_new = explode(' To ', $time_slot);
            $start_time = explode(' ', $time_slot_new[0]);
            $end_time = explode(' ', $time_slot_new[1]);

            if ($start_time[1] == 'AM') {
                $start_time_second = explode(':', $start_time[0]);
                if ($start_time_second[0] == 12) {
                    $day_start_time_second = $start_time_second[1] * 60;
                } else {
                    $day_start_time_second = $start_time_second[0] * 60 * 60 + $start_time_second[1] * 60;
                }
            } else {
                $start_time_second = explode(':', $start_time[0]);
                if ($start_time_second[0] == 12) {
                    $day_start_time_second = 12 * 60 * 60 + $start_time_second[1] * 60;
                } else {
                    $day_start_time_second = 12 * 60 * 60 + $start_time_second[0] * 60 * 60 + $start_time_second[1] * 60;
                }
            }

            if ($end_time[1] == 'AM') {
                $end_time_second = explode(':', $end_time[0]);
                if ($end_time_second[0] == 12) {
                    $day_end_time_second = $end_time_second[1] * 60;
                } else {
                    $day_end_time_second = $end_time_second[0] * 60 * 60 + $end_time_second[1] * 60;
                }
            } else {
                $end_time_second = explode(':', $end_time[0]);
                if ($end_time_second[0] == 12) {
                    $day_end_time_second = 12 * 60 * 60 + $end_time_second[1] * 60;
                } else {
                    $day_end_time_second = 12 * 60 * 60 + $end_time_second[0] * 60 * 60 + $end_time_second[1] * 60;
                }
            }
            if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                $patient_details = $this->patient_model->getPatientById($entry->patient);
            } else {
                $patient_details = $this->patient_model->getPatientByOnlinecenter($entry->patient);
            }

            if (!empty($patient_details)) {
                $patient_mobile = $patient_details->phone;
                $patient_name = $patient_details->name;
            } else {
                $patient_mobile = '';
                $patient_name = '';
            }

            if ($entry->status == 'Pending Confirmation') {
                $appointment_status = lang('pending_confirmation');
            } elseif ($entry->status == 'Confirmed') {
                $appointment_status = lang('confirmed');
            } elseif ($entry->status == 'Treated') {
                $appointment_status = lang('treated');
            } elseif ($entry->status == 'Cancelled') {
                $appointment_status = lang('cancelled');
            } elseif ($entry->status == 'Requested') {
                $appointment_status = lang('requested');
            }

            $info = '<br/>' . lang('status') . ': ' . $appointment_status . '<br>' . lang('patient') . ': ' . $patient_name . '<br/>' . lang('phone') . ': ' . $patient_mobile . '<br/> Doctor: ' . $doctor . '<br/>' . lang('remarks') . ': ' . $entry->remarks;
            if ($entry->status == 'Pending Confirmation') {

                $color = 'yellowgreen';
            }
            if ($entry->status == 'Confirmed') {
                $color = '#009988';
            }
            if ($entry->status == 'Treated') {
                $color = '#112233';
            }
            if ($entry->status == 'Cancelled') {
                $color = 'red';
            }
            if ($entry->status == 'Requested') {
                $color = '#6883a3';
            }

            $jsonevents[] = array(
                'id' => $entry->id,
                'title' => $info,
                'description' => 'Click to see the patient history',
                'start' => date('Y-m-d H:i:s', $entry->date + $day_start_time_second),
                'end' => date('Y-m-d H:i:s', $entry->date + $day_end_time_second),
                'color' => $color,
            );
        }

        echo json_encode($jsonevents);
    }

    public function getAppointmentByDoctorId()
    {
        $id = $this->input->get('id');
        if (!$this->ion_auth->in_group(array('superadmin'))) {
            $doctor_details = $this->doctor_model->getDoctorById($id);
            if ($doctor_details->hospital_id != $this->session->userdata('hospital_id')) {
                redirect('home/permission');
            }
        } else {
            $doctor_details = $this->doctor_model->getDoctorByIdBySuper($id);
        }
        $data['doctor_id'] = $id;
        $data['appointments'] = $this->appointment_model->getAppointment();
        $data['patients'] = $this->patient_model->getPatient();
        $data['mmrdoctor'] = $this->doctor_model->getDoctorById($id);
        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['appointments'] = $this->appointment_model->getAppointmentBySuper();
        $data['patients'] = $this->patient_model->getPatientBySuper();
        $data['mmrdoctor'] = $this->doctor_model->getDoctorByIdBySuper($id);
        $data['doctors'] = $this->doctor_model->getDoctorBySuper();
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data);
        $this->load->view('appointment_by_doctor', $data);
        $this->load->view('home/footer');
    }

    public function editAppointment()
    {
        $data = array();
        $id = $this->input->get('id');

        $data['redirectlink'] = $this->input->get('redirect');
        $data['appointment'] = $this->appointment_model->getAppointmentById($id);
        $data['patients'] = $this->patient_model->getPatientById($data['appointment']->patient);
        $data['doctors'] = $this->doctor_model->getDoctorById($data['appointment']->doctor);
        $data['teams'] = $this->team_model->getTeamById($data['appointment']->team);
        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $data['visits'] = $this->doctorvisit_model->getDoctorvisitByOnlinecenter($data['appointment']->hospital_id);
            $data['categories'] = $this->hospital_model->getHospitalCategory();
            $data['hospital'] = $this->hospital_model->getHospitalByOnlinecenter($data['appointment']->hospital_id);
        } else {
            $data['visits'] = $this->doctorvisit_model->getDoctorVisit();
            $data['settings'] = $this->settings_model->getSettings();
            $data['gateway'] = $this->pgateway_model->getPaymentGatewaySettingsByName($data['settings']->payment_gateway);
        }
        $data['b_doctors'] = $this->doctor_model->getBoardDoctor();
        $this->load->view('home/dashboard', $data);
        $this->load->view('add_new', $data);
        $this->load->view('home/footer');
    }
    public function editAppointmentFromLive()
    {
        $data = array();
        $id = $this->input->get('id');

        $data['redirectlink'] = $this->input->get('redirect');
        $data['appointment'] = $this->appointment_model->getAppointmentById($id);
        $data['patients'] = $this->patient_model->getPatientById($data['appointment']->patient);
        $data['doctors'] = $this->doctor_model->getDoctorById($data['appointment']->doctor);
        $data['teams'] = $this->team_model->getTeamById($data['appointment']->team);
        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $data['visits'] = $this->doctorvisit_model->getDoctorvisitByOnlinecenter($data['appointment']->hospital_id);
            $data['categories'] = $this->hospital_model->getHospitalCategory();
            $data['hospital'] = $this->hospital_model->getHospitalByOnlinecenter($data['appointment']->hospital_id);
        } else {
            $data['visits'] = $this->doctorvisit_model->getDoctorVisit();
            $data['settings'] = $this->settings_model->getSettings();
            $data['gateway'] = $this->pgateway_model->getPaymentGatewaySettingsByName($data['settings']->payment_gateway);
        }
        $data['b_doctors'] = $this->doctor_model->getBoardDoctor();
        $this->load->view('frontend/dashboard', $data);
        $this->load->view('add_new', $data);
        $this->load->view('home/footer');
    }

    public function editAppointmentByJason()
    {
        $id = $this->input->get('id');
        $data['appointment'] = $this->appointment_model->getAppointmentById($id);
        $data['patient'] = $this->patient_model->getPatientById($data['appointment']->patient);
        $data['doctor'] = $this->doctor_model->getDoctorById($data['appointment']->doctor);
        echo json_encode($data);
    }

    public function treatmentReport()
    {
        $data['settings'] = $this->settings_model->getSettings();
        $data['doctors'] = $this->doctor_model->getDoctor();

        $date_from = strtotime($this->input->post('date_from'));
        $date_to = strtotime($this->input->post('date_to'));
        if (!empty($date_to)) {
            $date_to = $date_to + 24 * 60 * 60;
        }
        if ($this->ion_auth->in_group(array('superadmin'))) {
            $data['doctors'] = $this->doctor_model->getDoctorBySuper();
            if (empty($date_from) || empty($date_to)) {
                $data['appointments'] = $this->appointment_model->getAppointmentBySuper();
            } else {
                $data['appointments'] = $this->appointment_model->getAppointmentByDateBySuper($date_from, $date_to);
                $data['from'] = $this->input->post('date_from');
                $data['to'] = $this->input->post('date_to');
            }
        } else {
            if (empty($date_from) || empty($date_to)) {
                $data['appointments'] = $this->appointment_model->getAppointment();
            } else {
                $data['appointments'] = $this->appointment_model->getAppointmentByDate($date_from, $date_to);
                $data['from'] = $this->input->post('date_from');
                $data['to'] = $this->input->post('date_to');
            }
        }
        $this->load->view('home/dashboard', $data);
        $this->load->view('treatment_history', $data);
        $this->load->view('home/footer');
    }

    public function myAppointments()
    {
        $data['appointments'] = $this->appointment_model->getAppointment();
        $data['settings'] = $this->settings_model->getSettings();
        $user_id = $this->ion_auth->user()->row()->id;
        $data['user_id'] = $this->db->get_where('patient', array('ion_user_id' => $user_id))->row()->id;
        $this->load->view('home/dashboard', $data);
        $this->load->view('myappointments', $data);
        $this->load->view('home/footer');
    }

    public function delete()
    {
        $data = array();
        $id = $this->input->get('id');
        $doctor_id = $this->input->get('doctor_id');
        $this->appointment_model->delete($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        if (!empty($doctor_id)) {
            redirect('appointment/getAppointmentByDoctorId?id=' . $doctor_id);
        } else {
            redirect('appointment');
        }
    }

    public function deleteAppointment()
    {
        $data = array();
        $id = $this->input->get('id');
        $doctor_id = $this->input->get('doctor_id');
        $this->appointment_model->delete($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        if (!empty($doctor_id)) {
            redirect('appointment/getAppointmentByDoctorId?id=' . $doctor_id);
        } else {
            redirect('onlinecenter/appointment');
        }
    }

    public function getAppointment()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        if ($limit == -1) {
            if (!empty($search)) {
                $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search);
            } else {
                $data['appointments'] = $this->appointment_model->getAppointment();
            }
        } else {
            if (!empty($search)) {
                $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search);
            } else {
                $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start);
            }
        }

        foreach ($data['appointments'] as $appointment) {

            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {

                $options1 = ' <a type="button" class="btn editbutton" title="' . lang('edit') . '" data-toggle = "modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> </i> ' . lang('edit') . '</a>';
            }

            $options2 = '<a class="btn detailsbutton buttoncolor" title="' . lang('info') . '"  href="appointment/appointmentDetails?id=' . $appointment->id . '"><i class="fa fa-info"></i> ' . lang('info') . '</a>';

            $options3 = '<a class="btn green buttoncolor" title="' . lang('history') . '"  href="appointment/medicalHistory?id=' . $appointment->id . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';

            $options4 = '<a class="btn invoicebutton buttoncolor" title="' . lang('payment') . '"  href="finance/appointmentPaymentHistory?appointment=' . $appointment->id . '"><i class="fa fa-money"></i> ' . lang('payment') . '</a>';

            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {
                $options5 = '<a class="btn delete_button" title="' . lang('delete') . '" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"></i> ' . lang('delete') . '</a>';
            }

            $info[] = array(
                $appointment->id,
                $appointment->name,
                $appointment->phone,
                $this->settings_model->getSettings()->currency . $this->appointment_model->getDueBalanceByAppointmentId($appointment->id),
                $options1 . ' ' . $options2 . ' ' . $options3 . ' ' . $options4 . ' ' . $options5,
            );
        }

        if (!empty($data['appointments'])) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => count($this->appointment_model->getAppointment()),
                "recordsFiltered" => count($this->appointment_model->getAppointment()),
                "data" => $info,
            );
        } else {
            $output = array(
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }

    public function getAppoinmentList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByDoctor($doctor, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByDoctor($doctor);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByDoctor($doctor, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByDoctor($doctor, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('casetaker'))) {
            $casetaker_ion_id = $this->ion_auth->get_user_id();
            $casetaker_id = $this->casetaker_model->getCasetakerByIonUserId($casetaker_ion_id)->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByCasetaker($casetaker_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByCasetaker($casetaker_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByCasetaker($casetaker_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByCasetaker($casetaker_id, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
            $onlinecenter_ion_id = $this->ion_auth->get_user_id();
            $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByOnlinecenter($onlinecenter_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByOnlinecenter($onlinecenter_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByOnlinecenter($onlinecenter_id, $limit, $start);
                }
            }
        } else {
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointment();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start);
                }
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {
            $i = $i + 1;

            $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';

            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            $patientdetails = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patientdetails)) {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }
            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $appointment->doctorname;
            }

            if ($this->ion_auth->in_group(array('Doctor', 'onlinecenter', 'casetaker'))) {
                if ($appointment->status == 'Confirmed') {
                    if ($appointment->status == 'Confirmed') {
                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton buttoncolor" title="' . lang('start_live') . '"  href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {
                        $options7 = '';
                    }
                } else {
                    $options7 = '';
                }
            } else {
                $options7 = '';
            }

            if ($appointment->status == 'Confirmed') {
                $option_status = '<button type="button" class="btn btn-info btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Pending Confirmation') {
                $option_status = '<button type="button" class="btn btn-warning btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Treated') {
                $option_status = '<button type="button" class="btn btn-success btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Cancelled') {
                $option_status = '<button type="button" class="btn btn-danger btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } else {
                $option_status = '<button type="button" class="btn btn-default btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            }


            if ($appointment->visit_description == 'new_visit') {
                $visit = lang('new_visit');
            } elseif ($appointment->visit_description == 'old_visit') {
                $visit = lang('old_visit');
            } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                $visit = lang('new_visit_with_medicine');
            } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                $visit = lang('old_visit_with_medicine');
            } else {
                $visit_type = $this->doctorvisit_model->GetDoctorVisitById($appointment->visit_description);
                if ($visit_type) {
                    $visit = $visit_type->visit_description;
                }
            }
            if ($appointment->payment_status == 'paid') {
                $payment_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $payment_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
            }
            $payment_details = $this->finance_model->getPaymentById($appointment->payment_id);
            $total_deposited_amount = $this->finance_model->getDepositAmountByPaymentId($payment_details->id);
            $total_due = $payment_details->gross_total - $total_deposited_amount;
            if ($payment_details->gross_total == $total_due) {
                if ($payment_details->gross_total != 0) {
                    $bill_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
                } else {
                    $bill_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
                }
            } elseif ($total_due == 0) {
                $bill_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $bill_status = '<span class=" label label-warning">' . lang('due') . '</span>';
            }
            $deposit = ' <a type="button" class="btn btn-xs btn-primary depositButton" title="' . lang('deposit') . '" data-toggle = "modal" data-id="' . $payment_details->id . '" data-from="' . $payment_details->payment_from . '"><i class="fa fa-money"> </i> ' . lang('deposit') . '</a>';
            if (empty($appointment->board_doctor)) {
                $info[] = array(
                    $appointment->id,
                    $patientname,
                    $appointment->patient_id,
                    $options7,
                    $appointment->patient_phone,
                    $doctorname,
                    date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                    $visit,
                    $payment_details->gross_total,
                    $this->finance_model->getDepositAmountByPaymentId($appointment->payment_id),
                    ($payment_details->gross_total - $this->finance_model->getDepositAmountByPaymentId($appointment->payment_id)),
                    $bill_status,
                    $appointment->remarks,
                    $option_status,
                    $appointment->account_number,
                    $appointment->transaction_id,
                    $option1 . ' ' . $option2 . ' ' . $deposit,
                );
            }
        }

        if (!empty($data['appointments'])) {
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointmentListByDoctor($doctor)),
                    "recordsFiltered" => count($this->appointment_model->getAppointmentListByDoctor($doctor)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('casetaker'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointmentListByCasetaker($casetaker_id)),
                    "recordsFiltered" => count($this->appointment_model->getAppointmentListByCasetaker($casetaker_id)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id)),
                    "recordsFiltered" => count($this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id)),
                    "data" => $info,
                );
            } else {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointment()),
                    "recordsFiltered" => count($this->appointment_model->getAppointment()),
                    "data" => $info,
                );
            }
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

    public function getRequestedAppointmentList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getRequestAppointmentBysearchByDoctor($doctor, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getRequestAppointmentByDoctor($doctor);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getRequestAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getRequestAppointmentByLimitByDoctor($doctor, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('casetaker'))) {
            $casetaker_ion_id = $this->ion_auth->get_user_id();
            $casetaker_id = $this->casetaker_model->getCasetakerByIonUserId($casetaker_ion_id)->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getRequestedAppointmentBySearchByCasetaker($casetaker_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getRequestedAppointmentByCasetaker($casetaker_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getRequestedAppointmentByLimitBySearchByCasetaker($casetaker_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getRequestedAppointmentByLimitByCasetaker($casetaker_id, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
            $onlinecenter_ion_id = $this->ion_auth->get_user_id();
            $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getRequestedAppointmentBySearchByOnlinecenter($onlinecenter_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getRequestedAppointmentByOnlinecenter($onlinecenter_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getRequestedAppointmentByLimitBySearchByOnlinecenter($onlinecenter_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getRequestedAppointmentByLimitByOnlinecenter($onlinecenter_id, $limit, $start);
                }
            }
        } else {
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getRequestAppointmentBysearch($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getRequestAppointment();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getRequestAppointmentByLimitBySearch($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getRequestAppointmentByLimit($limit, $start);
                }
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {

            $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';

            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            $patientdetails = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patientdetails)) {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }
            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $appointment->doctorname;
            }
            if ($appointment->visit_description == 'new_visit') {
                $visit = lang('new_visit');
            } elseif ($appointment->visit_description == 'old_visit') {
                $visit = lang('old_visit');
            } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                $visit = lang('new_visit_with_medicine');
            } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                $visit = lang('old_visit_with_medicine');
            } else {
                $visit_type = $this->doctorvisit_model->GetDoctorVisitById($appointment->visit_description);
                if ($visit_type) {
                    $visit = $visit_type->visit_description;
                }
            }
            if ($appointment->payment_status == 'paid') {
                $payment_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $payment_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
            }

            $info[] = array(
                $appointment->id,
                $patientname,
                $appointment->patient_id,
                $appointment->patient_phone,
                $doctorname,
                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                $visit,
                $payment_status,
                $appointment->remarks,
                $appointment->status,
                $appointment->account_number,
                $appointment->transaction_id,
                $option1 . ' ' . $option2,
            );
            $i = $i + 1;
        }

        if (!empty($data['appointments'])) {
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getRequestAppointmentByDoctor($doctor)),
                    "recordsFiltered" => count($this->appointment_model->getRequestAppointmentByDoctor($doctor)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('casetaker'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getRequestedAppointmentByCasetaker($casetaker_id)),
                    "recordsFiltered" => count($this->appointment_model->getRequestedAppointmentByCasetaker($casetaker_id)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getRequestedAppointmentByOnlinecenter($onlinecenter_id)),
                    "recordsFiltered" => count($this->appointment_model->getRequestedAppointmentByOnlinecenter($onlinecenter_id)),
                    "data" => $info,
                );
            } else {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getRequestAppointment()),
                    "recordsFiltered" => count($this->appointment_model->getRequestAppointment()),
                    "data" => $info,
                );
            }
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

    public function getPendingAppoinmentList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentBysearchByDoctor($doctor, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByDoctor($doctor);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimitByDoctor($doctor, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('casetaker'))) {
            $casetaker_ion_id = $this->ion_auth->get_user_id();
            $casetaker_id = $this->casetaker_model->getCasetakerByIonUserId($casetaker_ion_id)->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentBySearchByCasetaker($casetaker_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByCasetaker($casetaker_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimitBySearchByCasetaker($casetaker_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimitByCasetaker($casetaker_id, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
            $onlinecenter_ion_id = $this->ion_auth->get_user_id();
            $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentBySearchByOnlinecenter($onlinecenter_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByOnlinecenter($onlinecenter_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimitBySearchByOnlinecenter($onlinecenter_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimitByOnlinecenter($onlinecenter_id, $limit, $start);
                }
            }
        } else {
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentBysearch($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getPendingAppointment();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimitBySearch($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimit($limit, $start);
                }
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {

            $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';

            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patientdetails)) {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }
            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $appointment->doctorname;
            }
            if ($appointment->visit_description == 'new_visit') {
                $visit = lang('new_visit');
            } elseif ($appointment->visit_description == 'old_visit') {
                $visit = lang('old_visit');
            } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                $visit = lang('new_visit_with_medicine');
            } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                $visit = lang('old_visit_with_medicine');
            } else {
                $visit_type = $this->doctorvisit_model->GetDoctorVisitById($appointment->visit_description);
                if ($visit_type) {
                    $visit = $visit_type->visit_description;
                }
            }
            if ($appointment->payment_status == 'paid') {
                $payment_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $payment_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
            }
            $payment_details = $this->finance_model->getPaymentById($appointment->payment_id);
            $total_deposited_amount = $this->finance_model->getDepositAmountByPaymentId($payment_details->id);
            $total_due = $payment_details->gross_total - $total_deposited_amount;
            if ($payment_details->gross_total == $total_due) {
                if ($payment_details->gross_total != 0) {
                    $bill_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
                } else {
                    $bill_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
                }
            } elseif ($total_due == 0) {
                $bill_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $bill_status = '<span class=" label label-warning">' . lang('due') . '</span>';
            }
            $deposit = ' <a type="button" class="btn btn-xs btn-primary depositButton" title="' . lang('deposit') . '" data-toggle = "modal" data-id="' . $payment_details->id . '" data-from="' . $payment_details->payment_from . '"><i class="fa fa-money"> </i> ' . lang('deposit') . '</a>';
            if ($appointment->status == 'Confirmed') {
                $option_status = '<button type="button" class="btn btn-info btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Pending Confirmation') {
                $option_status = '<button type="button" class="btn btn-warning btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Treated') {
                $option_status = '<button type="button" class="btn btn-success btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Cancelled') {
                $option_status = '<button type="button" class="btn btn-danger btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } else {
                $option_status = '<button type="button" class="btn btn-default btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            }


            if (empty($appointment->board_doctor)) {
                $info[] = array(
                    $appointment->id,
                    $patientname,
                    $appointment->patient_id,
                    $appointment->patient_phone,
                    $doctorname,
                    date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                    $visit,
                    $payment_details->gross_total,
                    $this->finance_model->getDepositAmountByPaymentId($appointment->payment_id),
                    ($payment_details->gross_total - $this->finance_model->getDepositAmountByPaymentId($appointment->payment_id)),
                    $bill_status,
                    $appointment->remarks,
                    $option_status,
                    $appointment->account_number,
                    $appointment->transaction_id,
                    $option1 . ' ' . $option2,
                );

                $i = $i + 1;
            }
        }

        if (!empty($data['appointments'])) {
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getPendingAppointmentByDoctor($doctor)),
                    "recordsFiltered" => count($this->appointment_model->getPendingAppointmentByDoctor($doctor)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('casetaker'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getPendingAppointmentByCasetaker($casetaker_id)),
                    "recordsFiltered" => count($this->appointment_model->getPendingAppointmentByCasetaker($casetaker_id)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getPendingAppointmentByOnlinecenter($onlinecenter_id)),
                    "recordsFiltered" => count($this->appointment_model->getPendingAppointmentByOnlinecenter($onlinecenter_id)),
                    "data" => $info,
                );
            } else {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getPendingAppointment()),
                    "recordsFiltered" => count($this->appointment_model->getPendingAppointment()),
                    "data" => $info,
                );
            }
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

    public function getConfirmedAppoinmentList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentBysearchByDoctor($doctor, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByDoctor($doctor);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByLimitByDoctor($doctor, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('casetaker'))) {
            $casetaker_ion_id = $this->ion_auth->get_user_id();
            $casetaker_id = $this->casetaker_model->getCasetakerByIonUserId($casetaker_ion_id)->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentBySearchByCasetaker($casetaker_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByCasetaker($casetaker_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByLimitBySearchByCasetaker($casetaker_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByLimitByCasetaker($casetaker_id, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
            $onlinecenter_ion_id = $this->ion_auth->get_user_id();
            $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentBySearchByOnlinecenter($onlinecenter_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByOnlinecenter($onlinecenter_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByLimitBySearchByOnlinecenter($onlinecenter_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByLimitByOnlinecenter($onlinecenter_id, $limit, $start);
                }
            }
        } else {
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentBysearch($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointment();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByLimitBySearch($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getConfirmedAppointmentByLimit($limit, $start);
                }
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {

            $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';

            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            $patientdetails = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patientdetails)) {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }
            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $appointment->doctorname;
            }

            if ($this->ion_auth->in_group(array('Doctor', 'onlinecenter', 'casetaker'))) {
                if ($appointment->status == 'Confirmed') {
                    $options7 = '<a class="btn btn btn-info btn-xs btn_width  detailsbutton buttoncolor" title="' . lang('start_live') . '"  href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                } else {
                    $options7 = '';
                }
            } else {
                $options7 = '';
            }
            if ($appointment->visit_description == 'new_visit') {
                $visit = lang('new_visit');
            } elseif ($appointment->visit_description == 'old_visit') {
                $visit = lang('old_visit');
            } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                $visit = lang('new_visit_with_medicine');
            } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                $visit = lang('old_visit_with_medicine');
            } else {
                $visit_type = $this->doctorvisit_model->GetDoctorVisitById($appointment->visit_description);
                if ($visit_type) {
                    $visit = $visit_type->visit_description;
                }
            }
            if ($appointment->payment_status == 'paid') {
                $payment_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $payment_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
            }
            if ($appointment->status == 'Confirmed') {
                $option_status = '<button type="button" class="btn btn-info btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Pending Confirmation') {
                $option_status = '<button type="button" class="btn btn-warning btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Treated') {
                $option_status = '<button type="button" class="btn btn-success btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Cancelled') {
                $option_status = '<button type="button" class="btn btn-danger btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } else {
                $option_status = '<button type="button" class="btn btn-default btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            }

            $info[] = array(
                $appointment->id,
                $patientname,
                $appointment->patient_id,
                $options7,
                $appointment->patient_phone,
                $doctorname,
                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                $visit,
                $payment_status,
                $appointment->remarks,
                $option_status,
                $appointment->account_number,
                $appointment->transaction_id,
                $option1 . ' ' . $option2,
            );
            $i = $i + 1;
        }

        if (!empty($data['appointments'])) {
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getConfirmedAppointmentByDoctor($doctor)),
                    "recordsFiltered" => count($this->appointment_model->getConfirmedAppointmentByDoctor($doctor)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('casetaker'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getConfirmedAppointmentByCasetaker($casetaker_id)),
                    "recordsFiltered" => count($this->appointment_model->getConfirmedAppointmentByCasetaker($casetaker_id)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getConfirmedAppointmentByOnlinecenter($onlinecenter_id)),
                    "recordsFiltered" => count($this->appointment_model->getConfirmedAppointmentByOnlinecenter($onlinecenter_id)),
                    "data" => $info,
                );
            } else {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getConfirmedAppointment()),
                    "recordsFiltered" => count($this->appointment_model->getConfirmedAppointment()),
                    "data" => $info,
                );
            }
        } else {
            $output = array(
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }

    public function getTreatedAppoinmentList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentBysearchByDoctor($doctor, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByDoctor($doctor);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByLimitByDoctor($doctor, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('casetaker'))) {
            $casetaker_ion_id = $this->ion_auth->get_user_id();
            $casetaker_id = $this->casetaker_model->getCasetakerByIonUserId($casetaker_ion_id)->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentBySearchByCasetaker($casetaker_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByCasetaker($casetaker_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByLimitBySearchByCasetaker($casetaker_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByLimitByCasetaker($casetaker_id, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
            $onlinecenter_ion_id = $this->ion_auth->get_user_id();
            $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentBySearchByOnlinecenter($onlinecenter_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByOnlinecenter($onlinecenter_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByLimitBySearchByOnlinecenter($onlinecenter_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByLimitByOnlinecenter($onlinecenter_id, $limit, $start);
                }
            }
        } else {
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentBysearch($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointment();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByLimitBySearch($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getTreatedAppointmentByLimit($limit, $start);
                }
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {

            $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';

            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            $patientdetails = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patientdetails)) {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }
            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $appointment->doctorname;
            }

            if ($this->ion_auth->in_group(array('admin', 'Doctor', 'onlinecenter', 'casetaker'))) {
                if ($appointment->status == 'Confirmed') {
                    $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton buttoncolor" title="' . lang('start_live') . '"  href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                } else {
                    $options7 = '';
                }
            } else {
                $options7 = '';
            }
            if ($appointment->visit_description == 'new_visit') {
                $visit = lang('new_visit');
            } elseif ($appointment->visit_description == 'old_visit') {
                $visit = lang('old_visit');
            } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                $visit = lang('new_visit_with_medicine');
            } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                $visit = lang('old_visit_with_medicine');
            } else {
                $visit_type = $this->doctorvisit_model->GetDoctorVisitById($appointment->visit_description);
                if ($visit_type) {
                    $visit = $visit_type->visit_description;
                }
            }
            if ($appointment->payment_status == 'paid') {
                $payment_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $payment_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
            }
            if ($appointment->status == 'Confirmed') {
                $option_status = '<button type="button" class="btn btn-info btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Pending Confirmation') {
                $option_status = '<button type="button" class="btn btn-warning btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Treated') {
                $option_status = '<button type="button" class="btn btn-success btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Cancelled') {
                $option_status = '<button type="button" class="btn btn-danger btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } else {
                $option_status = '<button type="button" class="btn btn-default btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            }
            $info[] = array(
                $appointment->id,
                $patientname,
                $appointment->patient_id,
                $appointment->patient_phone,
                $doctorname,
                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                $visit,
                $payment_status,
                $appointment->remarks,
                $option_status,
                $appointment->account_number,
                $appointment->transaction_id,
                $option1 . ' ' . $option2,
            );
            $i = $i + 1;
        }

        if (!empty($data['appointments'])) {
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getTreatedAppointmentByDoctor($doctor)),
                    "recordsFiltered" => count($this->appointment_model->getTreatedAppointmentByDoctor($doctor)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('casetaker'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getTreatedAppointmentByCasetaker($casetaker_id)),
                    "recordsFiltered" => count($this->appointment_model->getTreatedAppointmentByCasetaker($casetaker_id)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getTreatedAppointmentByOnlinecenter($onlinecenter_id)),
                    "recordsFiltered" => count($this->appointment_model->getTreatedAppointmentByOnlinecenter($onlinecenter_id)),
                    "data" => $info,
                );
            } else {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getTreatedAppointment()),
                    "recordsFiltered" => count($this->appointment_model->getTreatedAppointment()),
                    "data" => $info,
                );
            }
        } else {
            $output = array(
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }

    public function getCancelledAppoinmentList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentBysearchByDoctor($doctor, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByDoctor($doctor);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimitByDoctor($doctor, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('casetaker'))) {
            $casetaker_ion_id = $this->ion_auth->get_user_id();
            $casetaker_id = $this->casetaker_model->getCasetakerByIonUserId($casetaker_ion_id)->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentBySearchByCasetaker($casetaker_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByCasetaker($casetaker_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimitBySearchByCasetaker($casetaker_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimitByCasetaker($casetaker_id, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
            $onlinecenter_ion_id = $this->ion_auth->get_user_id();
            $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentBySearchByOnlinecenter($onlinecenter_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByOnlinecenter($onlinecenter_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimitBySearchByOnlinecenter($onlinecenter_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimitByOnlinecenter($onlinecenter_id, $limit, $start);
                }
            }
        } else {
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentBysearch($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointment();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimitBySearch($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimit($limit, $start);
                }
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {

            $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';

            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            $patientdetails = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patientdetails)) {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }
            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $appointment->doctorname;
            }
            if ($appointment->visit_description == 'new_visit') {
                $visit = lang('new_visit');
            } elseif ($appointment->visit_description == 'old_visit') {
                $visit = lang('old_visit');
            } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                $visit = lang('new_visit_with_medicine');
            } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                $visit = lang('old_visit_with_medicine');
            } else {
                $visit_type = $this->doctorvisit_model->GetDoctorVisitById($appointment->visit_description);
                if ($visit_type) {
                    $visit = $visit_type->visit_description;
                }
            }
            if ($appointment->payment_status == 'paid') {
                $payment_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $payment_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
            }
            if ($appointment->status == 'Confirmed') {
                $option_status = '<button type="button" class="btn btn-info btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Pending Confirmation') {
                $option_status = '<button type="button" class="btn btn-warning btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Treated') {
                $option_status = '<button type="button" class="btn btn-success btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Cancelled') {
                $option_status = '<button type="button" class="btn btn-danger btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } else {
                $option_status = '<button type="button" class="btn btn-default btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            }
            $info[] = array(
                $appointment->id,
                $patientname,
                $appointment->patient_id,
                $appointment->patient_phone,
                $doctorname,
                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                $visit,
                $payment_status,
                $appointment->remarks,
                $option_status,
                $appointment->account_number,
                $appointment->transaction_id,
                $option1 . ' ' . $option2,
            );
            $i = $i + 1;
        }

        if (!empty($data['appointments'])) {
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getCancelledAppointmentByDoctor($doctor)),
                    "recordsFiltered" => count($this->appointment_model->getCancelledAppointmentByDoctor($doctor)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('casetaker'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getCancelledAppointmentByCasetaker($casetaker_id)),
                    "recordsFiltered" => count($this->appointment_model->getCancelledAppointmentByCasetaker($casetaker_id)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getCancelledAppointmentByOnlinecenter($onlinecenter_id)),
                    "recordsFiltered" => count($this->appointment_model->getCancelledAppointmentByOnlinecenter($onlinecenter_id)),
                    "data" => $info,
                );
            } else {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getCancelledAppointment()),
                    "recordsFiltered" => count($this->appointment_model->getCancelledAppointment()),
                    "data" => $info,
                );
            }
        } else {
            $output = array(
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }

    public function getTodaysAppoinmentList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByDoctor($doctor, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByDoctor($doctor);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByDoctor($doctor, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByDoctor($doctor, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('casetaker'))) {
            $casetaker_ion_id = $this->ion_auth->get_user_id();
            $casetaker_id = $this->casetaker_model->getCasetakerByIonUserId($casetaker_ion_id)->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByCasetaker($casetaker_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByCasetaker($casetaker_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByCasetaker($casetaker_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByCasetaker($casetaker_id, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
            $onlinecenter_ion_id = $this->ion_auth->get_user_id();
            $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByOnlinecenter($onlinecenter_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByOnlinecenter($onlinecenter_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByOnlinecenter($onlinecenter_id, $limit, $start);
                }
            }
        } else {
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointment();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start);
                }
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {

            $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';

            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            $patientdetails = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patientdetails)) {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {
                $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }
            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $appointment->doctorname;
            }

            if ($this->ion_auth->in_group(array('admin', 'Doctor'))) {
                if ($appointment->status == 'Confirmed') {
                    $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton buttoncolor" title="' . lang('start_live') . '" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                } else {
                    $options7 = '';
                }
            } else {
                $options7 = '';
            }
            if ($appointment->visit_description == 'new_visit') {
                $visit = lang('new_visit');
            } elseif ($appointment->visit_description == 'old_visit') {
                $visit = lang('old_visit');
            } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                $visit = lang('new_visit_with_medicine');
            } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                $visit = lang('old_visit_with_medicine');
            } else {
                $visit_type = $this->doctorvisit_model->GetDoctorVisitById($appointment->visit_description);
                if ($visit_type) {
                    $visit = $visit_type->visit_description;
                }
            }
            if ($appointment->payment_status == 'paid') {
                $payment_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $payment_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
            }
            if ($appointment->status == 'Confirmed') {
                $option_status = '<button type="button" class="btn btn-info btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Pending Confirmation') {
                $option_status = '<button type="button" class="btn btn-warning btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Treated') {
                $option_status = '<button type="button" class="btn btn-success btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Cancelled') {
                $option_status = '<button type="button" class="btn btn-danger btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } else {
                $option_status = '<button type="button" class="btn btn-default btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            }
            if (date('Y-m-d', $appointment->date) == date('Y-m-d')) {
                $info[] = array(
                    $appointment->id,
                    $patientname,
                    $appointment->patient_id,
                    $doctorname,
                    date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                    $visit,
                    $payment_status,
                    $appointment->remarks,
                    $option_status,
                    $option1 . ' ' . $option2 . ' ' . $options7,
                );
                $i = $i + 1;
            } else {
                $info1[] = array(
                    $appointment->id,
                    $appointment->patientname,
                    $appointment->patient_id,
                    $appointment->doctorname,
                    date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                    $appointment->remarks,
                    $option_status,
                    $option1 . ' ' . $option2 . ' ' . $options7,
                );
            }
        }

        if ($i !== 0) {
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointmentListByDoctor($doctor)),
                    "recordsFiltered" => count($this->appointment_model->getAppointmentListByDoctor($doctor)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('casetaker'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointmentListByCasetaker($casetaker_id)),
                    "recordsFiltered" => count($this->appointment_model->getAppointmentListByCasetaker($casetaker_id)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id)),
                    "recordsFiltered" => count($this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id)),
                    "data" => $info,
                );
            } else {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointment()),
                    "recordsFiltered" => count($this->appointment_model->getAppointment()),
                    "data" => $info,
                );
            }
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

    public function getUpcomingAppoinmentList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByDoctor($doctor, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByDoctor($doctor);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByDoctor($doctor, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByDoctor($doctor, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('casetaker'))) {
            $casetaker_ion_id = $this->ion_auth->get_user_id();
            $casetaker_id = $this->casetaker_model->getCasetakerByIonUserId($casetaker_ion_id)->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByCasetaker($casetaker_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByCasetaker($casetaker_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByCasetaker($casetaker_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByCasetaker($casetaker_id, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
            $onlinecenter_ion_id = $this->ion_auth->get_user_id();
            $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByOnlinecenter($onlinecenter_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByOnlinecenter($onlinecenter_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByOnlinecenter($onlinecenter_id, $limit, $start);
                }
            }
        } else {
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointment();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start);
                }
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {

            $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';

            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            if ($appointment->date > strtotime(date('Y-m-d'))) {
                $patientdetails = $this->patient_model->getPatientById($appointment->patient);
                if (!empty($patientdetails)) {
                    $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
                } else {
                    $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
                }
                $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
                if (!empty($doctordetails)) {
                    $doctorname = $doctordetails->name;
                } else {
                    $doctorname = $appointment->doctorname;
                }
                if ($this->ion_auth->in_group(array('admin', 'Doctor'))) {
                    if ($appointment->status == 'Confirmed') {
                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton buttoncolor" title="' . lang('start_live') . '"  href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {
                        $options7 = '';
                    }
                } else {
                    $options7 = '';
                }
                if ($appointment->visit_description == 'new_visit') {
                    $visit = lang('new_visit');
                } elseif ($appointment->visit_description == 'old_visit') {
                    $visit = lang('old_visit');
                } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                    $visit = lang('new_visit_with_medicine');
                } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                    $visit = lang('old_visit_with_medicine');
                } else {
                    $visit_type = $this->doctorvisit_model->GetDoctorVisitById($appointment->visit_description);
                    if ($visit_type) {
                        $visit = $visit_type->visit_description;
                    }
                }
                if ($appointment->payment_status == 'paid') {
                    $payment_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
                } else {
                    $payment_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
                }
                if ($appointment->status == 'Confirmed') {
                    $option_status = '<button type="button" class="btn btn-info btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
                } elseif ($appointment->status == 'Pending Confirmation') {
                    $option_status = '<button type="button" class="btn btn-warning btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
                } elseif ($appointment->status == 'Treated') {
                    $option_status = '<button type="button" class="btn btn-success btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
                } elseif ($appointment->status == 'Cancelled') {
                    $option_status = '<button type="button" class="btn btn-danger btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
                } else {
                    $option_status = '<button type="button" class="btn btn-default btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
                }
                $info[] = array(
                    $appointment->id,
                    $patientname,
                    $appointment->patient_id,
                    $doctorname,
                    date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                    $visit,
                    $payment_status,
                    $appointment->remarks,
                    $option_status,
                    $option1 . ' ' . $option2 . ' ' . $options7,
                );
                $i = $i + 1;
            } else {
                if ($this->ion_auth->in_group(array('admin', 'Doctor'))) {
                    if ($appointment->status == 'Confirmed') {
                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton buttoncolor" title="' . lang('start_live') . '"  href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {
                        $options7 = '';
                    }
                } else {
                    $options7 = '';
                }
                $info1[] = array(
                    $appointment->id,
                    $appointment->patientname,
                    $appointment->patient_id,
                    $appointment->doctorname,
                    date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                    $appointment->remarks,
                    $option_status,
                    $option1 . ' ' . $option2 . ' ' . $options7,
                );
            }
        }

        if ($i !== 0) {
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointmentListByDoctor($doctor)),
                    "recordsFiltered" => count($this->appointment_model->getAppointmentListByDoctor($doctor)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('casetaker'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointmentListByCasetaker($casetaker_id)),
                    "recordsFiltered" => count($this->appointment_model->getAppointmentListByCasetaker($casetaker_id)),
                    "data" => $info,
                );
            } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id)),
                    "recordsFiltered" => count($this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id)),
                    "data" => $info,
                );
            } else {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointment()),
                    "recordsFiltered" => count($this->appointment_model->getAppointment()),
                    "data" => $info,
                );
            }
        } else {
            $output = array(
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }

    public function getMyTodaysAppoinmentList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByDoctor($doctor, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByDoctor($doctor);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByDoctor($doctor, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByDoctor($doctor, $limit, $start);
                }
            }
        } else {
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointment();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start);
                }
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {

            $patient_ion_id = $this->ion_auth->get_user_id();
            $patient_details = $this->patient_model->getPatientByIonUserId($patient_ion_id);
            $patient_id = $patient_details->id;
            if ($patient_id == $appointment->patient) {
                $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';
                $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
                $patientdetails = $this->patient_model->getPatientById($appointment->patient);
                if (!empty($patientdetails)) {
                    $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
                } else {
                    $patientname = ' <a type="button" class="" target="_blank" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
                }
                $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
                if (!empty($doctordetails)) {
                    $doctorname = $doctordetails->name;
                } else {
                    $doctorname = $appointment->doctorname;
                }

                if ($this->ion_auth->in_group(array('Patient'))) {
                    if ($appointment->status == 'Confirmed') {
                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton buttoncolor" title="' . lang('start_live') . '"  href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {
                        $options7 = '';
                    }
                } else {
                    $options7 = '';
                }
                if ($appointment->visit_description == 'new_visit') {
                    $visit = lang('new_visit');
                } elseif ($appointment->visit_description == 'old_visit') {
                    $visit = lang('old_visit');
                } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                    $visit = lang('new_visit_with_medicine');
                } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                    $visit = lang('old_visit_with_medicine');
                } else {
                    $visit_type = $this->doctorvisit_model->GetDoctorVisitById($appointment->visit_description);
                    if ($visit_type) {
                        $visit = $visit_type->visit_description;
                    }
                }
                if ($appointment->payment_status == 'paid') {
                    $payment_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
                } else {
                    $payment_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
                }
                if (date('Y-m-d', $appointment->date) == date('Y-m-d')) {
                    $info[] = array(
                        $appointment->id,
                        $patientname,
                        $doctorname,
                        date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                        $visit,
                        $payment_status,
                        $appointment->remarks,
                        $appointment->status,
                        $options7,
                    );
                    $i = $i + 1;
                } else {
                    $info1[] = array(
                        $appointment->id,
                        $appointment->patientname,
                        $appointment->doctorname,
                        date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                        $appointment->remarks,
                        $appointment->status,
                        $options7,
                    );
                }
            }
        }

        if ($i !== 0) {
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointmentListByDoctor($doctor)),
                    "recordsFiltered" => count($this->appointment_model->getAppointmentListByDoctor($doctor)),
                    "data" => $info,
                );
            } else {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointment()),
                    "recordsFiltered" => count($this->appointment_model->getAppointment()),
                    "data" => $info,
                );
            }
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

    public function getDoctorVisitCharges()
    {
        $id = $this->input->get('id');
        $doctor_id = $this->input->get('doctor');
        $doctor = $this->doctor_model->getDoctorById($doctor_id);
        if ($id == 'new_visit') {
            $visit = $doctor->new_visit;
        } elseif ($id == 'old_visit') {
            $visit = $doctor->old_visit;
        } elseif ($id == 'old_visit_with_medicine') {
            $visit = $doctor->old_visit_with_medicine;
        } elseif ($id == '') {
            $visit = '0';
        } else {
            $visit = $doctor->new_visit_with_medicine;
        }
        if (!empty($visit)) {
            $data['response'] = $visit;
        } else {
            $data['response'] = '0';
        }

        echo json_encode($data);
    }

    public function getHospitalSettings()
    {
        $id = $this->input->get('id');
        $data['settings'] = $this->db->get_where('settings', array('hospital_id' => $id))->row();
        if ($data['settings']->payment_gateway == 'Stripe') {
            $data['gateway_details'] = $this->db->get_where('paymentGateway', array('hospital_id' => $id, 'name' => 'Stripe'))->row();
        } else {
            $data['gateway_details'] = ' ';
        }
        echo json_encode($data);
    }
    public function getHospitalCommissionSettings()
    {
        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $id = $this->input->get('id');
        } else {
            $id = $this->session->userdata('hospital_id');
        }
        // $id = $this->input->get('id');
        $data['commission'] = $this->db->get_where('payment_commission', array('hospital_id' => $id))->row();

        echo json_encode($data);
    }
    public function getHospitalCommissionSettingsByPatientId()
    {

        $id = $this->input->get('id');

        $data['commission'] = $this->db->get_where('payment_commission', array('hospital_id' => $id))->row();

        echo json_encode($data);
    }

    public function getHospitalByCategory()
    {
        $category = $this->input->get('category');
        $data['hospital'] = $this->hospital_model->getHospitalByCategory($category);
        echo json_encode($data);
    }

    public function getCategoryByHospital()
    {
        $hospital = $this->input->get('hospital');
        $data['hospital'] = $this->hospital_model->getHospitalById($hospital);
        echo json_encode($data);
    }

    public function refresh()
    {
        $this->load->view('home/dashboard');
        $this->load->view('refresh');
        $this->load->view('home/footer');
    }

    public function addAppointmentInModal()
    {
        $id = $this->input->get('id');
        $response = array();
        $data['appointment'] = $this->appointment_model->getAppointmentById($id);
        // if ($this->ion_auth->in_group(array('casetaker', 'onlinecenter'))) {
        //     $data['patients'] = $this->patient_model->getPatientById($data['appointment']->patient);
        //     $data['doctors'] = $this->doctor_model->getDoctorByPatientHospitalId($data['appointmentt']->hospital_id);
        // } else {
        //     $data['patients'] = $this->patient_model->getPatientById($data['appointment']->patient);
        //     $data['doctors'] = $this->doctor_model->getDoctorById($data['appointment']->doctor);
        // }
        // $data['appointment'] = '';
        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $data['categories'] = $this->hospital_model->getHospitalCategory();
            $data['patients'] = $this->patient_model->getPatientById($data['appointment']->patient);
            //            $data['settings'] = $this->settings_model->getSettings();
        } else {
            $data['patients'] = $this->patient_model->getPatientById($data['appointment']->patient);
            // $data['patients'] = $this->patient_model->getPatient();
            $data['doctors'] = $this->doctor_model->getDoctor();
            $data['settings'] = $this->settings_model->getSettings();
            $data['gateway'] = $this->finance_model->getGatewayByName($data['settings']->payment_gateway);
        }

        $data['b_doctors'] = $this->doctor_model->getBoardDoctor();

        //        $data['prescription'] = $this->prescription_model->getPrescriptionById($id);
        $response['view_appointment'] = $this->load->view('appointment/add_appointment_view_by_modal', $data, true);
        echo json_encode($response);
    }
    public function addAppointmentViewByDoctor()
    {

        $data = array();
        $id = $this->input->get('patientId');
        $data['id'] = $this->input->get('patientId');
        $data['redirectlink'] = $this->input->get('redirect');
        $data['hospitals'] = $this->hospital_model->getHospital();

        $data['patient'] = $this->patient_model->getPatientById($id);
        $data['doctors'] = $this->doctor_model->getDoctorByHospital($data['patient']->hospital_id);
        $data['settings'] = $this->settings_model->getSettingsByHospital($data['patient']->hospital_id);
        $data['gateway'] = $this->finance_model->getGatewayByName($data['settings']->payment_gateway);
        // print_r($data['patient']);
        // die();
        $data['b_doctors'] = $this->doctor_model->getBoardDoctor();
        $this->load->view('frontend/dashboard', $data);
        $this->load->view('add_new', $data);
        $this->load->view('home/footer');
    }

    public function editAppointmentInModal()
    {
        $id = $this->input->get('id');
        $response = array();
        $data['appointment'] = $this->appointment_model->getAppointmentById($id);
        $data['patients'] = $this->patient_model->getPatientById($data['prescription']->patient);
        $data['doctors'] = $this->doctor_model->getDoctorById($data['prescription']->doctor);
        //        $data['prescription'] = $this->prescription_model->getPrescriptionById($id);
        $response['view_appointment'] = $this->load->view('appointment/edit_appointment_view_by_modal', $data, true);
        echo json_encode($response);
    }

    //function editAppointmentInModal() {
    //        $id = $this->input->get('id');
    //        $response = array();
    //        $data['appointment'] = $this->appointment_model->getAppointmentById($id);
    //        $data['patients'] = $this->patient_model->getPatientById($data['appointment']->patient);
    //        $data['doctors'] = $this->doctor_model->getDoctorById($data['appointment']->doctor);
    ////        $data['prescription'] = $this->prescription_model->getPrescriptionById($id);
    //        $response['view_appointment'] = $this->load->view('appointment/add_appointment_view_by_modal', $data, TRUE);
    //        echo json_encode($response);
    //    }

    public function getAppoinmentListForLive()
    {
        $patient = $this->input->get('id');
        $appointment_id = $this->input->get('appointment_id');
        $doctor_idd = $this->appointment_model->getAppointmentById($appointment_id)->doctor;
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        if ($limit == -1) {
            if (!empty($search)) {
                $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByPatient($patient, $search);
            } else {
                $data['appointments'] = $this->appointment_model->getAppointmentListByPatient($patient);
            }
        } else {
            if (!empty($search)) {
                $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByPatient($patient, $limit, $start, $search);
            } else {
                $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByPatient($patient, $limit, $start);
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {
            $i = $i + 1;
            if ($this->ion_auth->in_group(array('onlinecenter'))) {
                $onlinecenter_ion_id = $this->ion_auth->get_user_id();
                $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
                if ($appointment->onlinecenter_id == $onlinecenter_id) {
                    $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';
                } else {
                    $option1 = '';
                }
            }
            if ($this->ion_auth->in_group(array('casetaker'))) {
                $casetaker_ion_id = $this->ion_auth->get_user_id();
                $casetaker_id = $this->db->get_where('casetaker', array('ion_user_id' => $casetaker_ion_id))->row()->id;
                if ($appointment->casetaker_id == $casetaker_id) {
                    $option9 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';
                } else {
                    $option9 = '';
                }
            }
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $doctor_ion_id = $this->ion_auth->get_user_id();
                $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
                if ($doctor == $doctor_idd) {
                    if ($appointment->doctor == $doctor && $appointment->onlinecenter_id == '') {
                        $option10 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';
                    } else {
                        $option10 = '';
                    }
                }
            }

            //            $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';
            //            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            $patientdetails = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patientdetails)) {
                $patientname = ' <a type="button" class="" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {
                $patientname = ' <a type="button" class="" href="patient/medicalHistory?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }
            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $appointment->doctorname;
            }

            if ($this->ion_auth->in_group(array('Doctor', 'Patient'))) {
                if ($appointment->status == 'Confirmed') {
                    if ($appointment->status == 'Confirmed') {
                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton buttoncolor" title="' . lang('start_live') . '"  href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {
                        $options7 = '';
                    }
                } else {
                    $options7 = '';
                }
            } else {
                $options7 = '';
            }
            if ($appointment->visit_description == 'new_visit') {
                $visit = lang('new_visit');
            } elseif ($appointment->visit_description == 'old_visit') {
                $visit = lang('old_visit');
            } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                $visit = lang('new_visit_with_medicine');
            } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                $visit = lang('old_visit_with_medicine');
            } else {
                $visit = ' ';
            }
            if ($appointment->payment_status == 'paid') {
                $payment_status = lang('paid');
            } else {
                $payment_status = lang('unpaid');
            }
            //            if ($appointment->onlinecenter_id == $onlinecenter_id) {

            $user_info = $this->patient_model->getUserById($appointment->user);
            $user_group_id = $this->patient_model->getGroupByUserId($user_info->id)->group_id;
            $user_group_info = $this->patient_model->getGroupNameById($user_group_id);
            if ($user_group_info->name == 'Doctor') {
                $doctor_info = $this->doctor_model->getDoctorByIonUserId($appointment->user);
                $hospital_info = $this->hospital_model->getHospitalById($doctor_info->hospital_id);
                $hospital_category_info = $this->hospital_model->getHospitalCategoryById($hospital_info->category);
                $userName = $user_info->username . '|' . $user_group_info->name . ' | ' . $hospital_category_info->name . ' | ' . $hospital_info->name . ' | ' . $doctor_info->phone;
            } elseif ($user_group_info->name == 'onlinecenter') {
                $onlinecenter_info = $this->onlinecenter_model->getOnlinecenterByIonUserId($appointment->user);
                $userName = $user_info->username . ' | ' . $user_group_info->name . ' | ' . $onlinecenter_info->name . ' | ' . $onlinecenter_info->phone;
            } elseif ($user_group_info->name == 'casetaker') {
                $casetaker_info = $this->casetaker_model->getCasetakerByIonUserId($appointment->user);
                $onlinecenter_info = $this->onlinecenter_model->getOnlinecenterById($casetaker_info->onlinecenter_id);
                $userName = $user_info->username . ' | ' . $user_group_info->name . ' | ' . $onlinecenter_info->name . ' | ' . $casetaker_info->phone;
            } elseif ($user_group_info->name == 'Patient') {
                $patient_info = $this->patient_model->getPatientByIonUserId($appointment->user);
                $userName = $user_info->username . ' | ' . $user_group_info->name . ' | ' . $patient_info->name . ' | ' . $patient_info->phone;
            } else {
                $userName = $user_info->username . ' | ' . $user_group_info->name;
            }


            if ($this->ion_auth->in_group(array('Doctor', 'Patient'))) {
                $info[] = array(
                    date('d-m-Y', $appointment->date),
                    $appointment->s_time . '-' . $appointment->e_time,
                    $doctorname,
                    $appointment->status,
                    $userName,
                    $option10 . ' ' . $options7,
                );
            }
            if ($this->ion_auth->in_group(array('onlinecenter'))) {
                $info[] = array(
                    date('d-m-Y', $appointment->date),
                    $appointment->s_time . '-' . $appointment->e_time,
                    $doctorname,
                    $appointment->status,
                    $userName,
                    $option1,
                );
            }
            if ($this->ion_auth->in_group(array('casetaker'))) {
                $info[] = array(
                    date('d-m-Y', $appointment->date),
                    $appointment->s_time . '-' . $appointment->e_time,
                    $doctorname,
                    $appointment->status,
                    $userName,
                    $option9,
                );
            }
        }

        if (!empty($data['appointments'])) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => count($this->appointment_model->getAppointmentListByPatient($patient)),
                "recordsFiltered" => count($this->appointment_model->getAppointmentListByPatient($patient)),
                "data" => $info,
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

    //-----------------

    public function aamarpayy()
    {
        // $id = $this->input->get('id');

        $patient = $this->input->post('patient');
        $patient_details = $this->patient_model->getPatientByOnlinecenter($patient);
        $pgateway = $this->db->get_where('paymentGateway', array('hospital_id' => $patient_details->hospital_id, 'name =' => 'Aamarpay'))->row();
        // $gateway = $this->db->get_where('paymentGateway', array('hospital_id' => 6))->row();
        // $pgateway = $this->pgateway_model->getPaymentGatewaySettingsById($patient_details->hospital_id);
        $url = 'https://sandbox.aamarpay.com/request.php'; // live url https://secure.aamarpay.com/request.php
        $fields = array(
            'store_id' => $pgateway->store_id, //store id will be aamarpay,  contact integration@aamarpay.com for test/live id
            'amount' => $this->input->post('visit_charges'), //transaction amount
            'payment_type' => 'VISA', //no need to change
            'currency' => 'BDT', //currenct will be USD/BDT
            'tran_id' => rand(1111111, 9999999), //transaction id must be unique from your end
            'cus_name' => $patient_details->name, //customer name
            'cus_email' => $patient_details->email, //customer email address
            'cus_add1' => $patient_details->address, //customer address
            'cus_add2' => $patient_details->address, //customer address
            'cus_city' => 'Dhaka', //customer city
            'cus_state' => 'Dhaka', //state
            'cus_postcode' => '1206', //postcode or zipcode
            'cus_country' => 'Bangladesh', //country
            'cus_phone' => $patient_details->phone, //customer phone number
            'cus_fax' => 'Not¬Applicable', //fax
            'ship_name' => $patient_details->name, //ship name
            'ship_add1' => $patient_details->address, //ship address
            'ship_add2' => $patient_details->address,
            'ship_city' => 'Dhaka',
            'ship_state' => 'Dhaka',
            'ship_postcode' => '1212',
            'ship_country' => 'Bangladesh',
            'desc' => 'payment description',
            'success_url' => 'https://hdhealth.org/hospital/appointment', //your success route
            'fail_url' => 'https://hdhealth.org/hospital/appointment', //your fail route
            'cancel_url' => 'https://hdhealth.org/hospital/appointment', //your cancel url
            'opt_a' => 'Reshad', //optional paramter
            'opt_b' => 'Akil',
            'opt_c' => 'Liza',
            'opt_d' => 'Tanvir',
            'signature_key' => $pgateway->signature_key,
        ); //signature key will provided aamarpay, contact integration@aamarpay.com for test/live signature key

        $fields_string = http_build_query($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $url_forward = str_replace('"', '', stripslashes(curl_exec($ch)));
        curl_close($ch);

        $this->redirect_to_merchant($url_forward);
    }

    public function redirect_to_merchant($url)
    {

?>
        <html xmlns="http://www.w3.org/1999/xhtml">

        <head>
            <script type="text/javascript">
                function closethisasap() {
                    document.forms["redirectpost"].submit();
                }
            </script>
        </head>

        <body onLoad="closethisasap();">

            <form name="redirectpost" method="post" action="<?php echo 'https://secure.aamarpay.com/' . $url; ?>"></form>
            <!-- for live url https://secure.aamarpay.com -->
        </body>

        </html>
<?php
        exit;
    }

    public function success()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $paystatus = $_POST['opt_d'];
            $amount = $_POST['amount'];
            // $amount = $this->input->post('visit_charges');
            // $pay_amount = explode('.', $amount);
            $payment_details = $this->finance_model->getPaymentById($paystatus);
            // echo $paystatus;
            //you can get all parameter from post request
            // print_r($_POST);
            $data1 = array(
                'date' => $payment_details->date,
                'patient' => $payment_details->patient,
                'doctor' => $payment_details->doctor,
                'superadmin' => $payment_details->superadmin,
                'payment_id' => $paystatus,
                'deposited_amount' => $amount,
                'deposit_type' => 'Aamarpay',
                'amount_received_id' => $paystatus . '.' . 'gp',
                'gateway' => 'Aamarpay',
                'user' => $this->ion_auth->get_user_id(),
                'payment_from' => 'appointment',
            );
            $pay = $this->db->get_where('payment', array('id' => $paystatus))->row();
            if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {

                $this->finance_model->insertDeposit($data1);
            } else {
                $data1['hospital_id'] = $payment_details->hospital_id;

                $data1['onlinecenter_id'] = $pay->onlinecenter_id;
                if (!empty($pay->casetaker_id)) {
                    $data1['casetaker_id'] = $pay->casetaker_id;
                }
                $this->finance_model->insertDepositByOnlinecenter($data1);
            }

            $data_payment = array('amount_received' => $amount, 'deposit_type' => 'Aamarpay', 'status' => 'paid');
            $this->finance_model->updatePayment($paystatus, $data_payment);

            $appointment_id = $this->finance_model->getPaymentById($paystatus)->appointment_id;

            $appointment_details = $this->appointment_model->getAppointmentById($appointment_id);

            if ($appointment_details->status == 'Requested' || $appointment_details->status == 'Pending Confirmation') {

                $data_appointment_status = array('payment_status' => 'paid');
            } else {
                $data_appointment_status = array('payment_status' => 'paid');
            }

            $this->appointment_model->updateAppointment($appointment_id, $data_appointment_status);
            $this->session->set_flashdata('feedback', lang('payment_successful'));
            redirect('appointment');
        }
    }

    public function fail($request)
    {
        return $request;
    }

    public function getDoctorCommissionSettings()
    {

        $id = $this->input->get('id');

        $data['commission'] = $this->db->get_where('doctor_commission_setting', array('doctor' => $id))->row();

        echo json_encode($data);
    }

    public function commissionReport()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $patient = $this->input->get('patient');
        if (empty($patient)) {
            $patient = $this->input->post('patient');
        }

        $data['settings'] = $this->settings_model->getSettings();
        $date_from = strtotime($this->input->post('date_from'));
        $date_to = strtotime($this->input->post('date_to'));
        if (!empty($date_to)) {
            $date_to = $date_to + 86399;
        }
        $casetaker = $this->input->post('casetaker');
        $currency = $this->input->post('currency');
        $onlinecenter_id = $this->input->post('onlinecenter_id');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['onlinecenter_select'] = $onlinecenter_id;
        $data['casetaker_select'] = $casetaker;

        $data['onlinecenters'] = $this->onlinecenter_model->getOnlinecenter();
        $data['casetakers'] = $this->appointment_model->getCasetakerByOnlinecenter($onlinecenter_id);
        $data['onlinecenter_ids'] = $this->onlinecenter_model->getOnlinecenter();
        if (!empty($date_from)) {
            $data['onlinecenter_info'] = $this->db->get_where('onlinecenter', array('id' => $this->input->post('onlinecenter_id')))->row();
            $data['casetaker_info'] = $this->db->get_where('casetaker', array('id' => $this->input->post('casetaker')))->row();

            if (!empty($onlinecenter_id) && ($casetaker == '')) {
                $data['appointments'] = $this->appointment_model->getTreatedAppointmentByDateByOnlinecenter($onlinecenter_id, strtotime($date_from), strtotime($date_to), $currency);
            } elseif (!empty($onlinecenter_id) && ($casetaker != '')) {
                $data['appointments'] = $this->appointment_model->getTreatedAppointmentByDateByCasetaker($casetaker, strtotime($date_from), strtotime($date_to), $currency);
            }
        } else {
            $data['onlinecenter_info'] = $this->db->get_where('onlinecenter', array('id' => $this->input->post('onlinecenter_id')))->row();
            $data['casetaker_info'] = $this->db->get_where('casetaker', array('id' => $this->input->post('casetaker')))->row();

            if (!empty($onlinecenter_id) && ($casetaker == '')) {
                $data['appointments'] = $this->appointment_model->getAppointmentByOnlinecenterList($onlinecenter_id, $currency);
            } elseif (!empty($onlinecenter_id) && ($casetaker != '')) {
                $data['appointments'] = $this->appointment_model->getTreatedAppointmentByCasetaker($casetaker, $currency);
            }
        }
        //

        $this->load->view('home/dashboard');
        $this->load->view('commission_report', $data);
        $this->load->view('home/footer');
    }

    public function getCasetaker()
    {
        $id = $this->input->get('id');
        // $description = $this->input->get('description');
        $casetakers = $this->appointment_model->getCasetakerByOnlinecenter($id);
        $option = '<option value="">' . lang('select') . '</option>';
        foreach ($casetakers as $casetaker) {

            $option .= '<option value="' . $casetaker->id . '">' . $casetaker->name . '</option>';
        }
        $data['response'] = $option;
        echo json_encode($data);
    }

    public function getBoardAppoinmentList()
    {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor_ion_id = $this->ion_auth->get_user_id();
            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByBoardDoctor($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByBoardDoctor();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByBoardDoctor($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByBoardDoctor($limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('casetaker'))) {
            $casetaker_ion_id = $this->ion_auth->get_user_id();
            $casetaker_id = $this->casetaker_model->getCasetakerByIonUserId($casetaker_ion_id)->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByCasetaker($casetaker_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByCasetaker($casetaker_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByCasetaker($casetaker_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByCasetaker($casetaker_id, $limit, $start);
                }
            }
        } elseif ($this->ion_auth->in_group(array('onlinecenter'))) {
            $onlinecenter_ion_id = $this->ion_auth->get_user_id();
            $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $onlinecenter_ion_id))->row()->id;
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByOnlinecenter($onlinecenter_id, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByOnlinecenter($onlinecenter_id);
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByOnlinecenter($onlinecenter_id, $limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByOnlinecenter($onlinecenter_id, $limit, $start);
                }
            }
        } else {
            if ($limit == -1) {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointment();
                }
            } else {
                if (!empty($search)) {
                    $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search);
                } else {
                    $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start);
                }
            }
        }

        $i = 0;
        foreach ($data['appointments'] as $appointment) {


            if ($this->ion_auth->in_group(array('Doctor'))) {
                $doctor_ion_id = $this->ion_auth->get_user_id();
                $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
                if ($appointment->medical_board_type == 'Custom Board') {
                    if ($appointment->doctor == $doctor) {
                        $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';
                    } else {
                        $option1 = '';
                    }
                } else {
                    $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';
                }
            } else {
                $option1 = '<a type="button" class="btn btn-info btn-xs btn_width" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></a>';
            }
            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            $patientdetails = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patientdetails)) {
                $patientname = ' <a type="button" class="" href="patient/medicalHistoryForBoard?id=' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {
                $patientname = ' <a type="button" class="" href="patient/medicalHistoryForBoard?id=' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }
            $history = ' <a type="button" class="btn btn-info btn-xs btn_width" href="patient/medicalHistoryForBoard?id=' . $appointment->patient . '"> ' . lang('history') . '</a>';
            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $appointment->doctorname;
            }

            if ($this->ion_auth->in_group(array('Doctor', 'onlinecenter', 'casetaker'))) {
                if ($appointment->status == 'Confirmed') {
                    if ($appointment->status == 'Confirmed') {
                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton buttoncolor" title="' . lang('start_live') . '"  href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {
                        $options7 = '';
                    }
                } else {
                    $options7 = '';
                }
            } else {
                $options7 = '';
            }

            if ($appointment->visit_description == 'new_visit') {
                $visit = lang('new_visit');
            } elseif ($appointment->visit_description == 'old_visit') {
                $visit = lang('old_visit');
            } elseif ($appointment->visit_description == 'new_visit_with_medicine') {
                $visit = lang('new_visit_with_medicine');
            } elseif ($appointment->visit_description == 'old_visit_with_medicine') {
                $visit = lang('old_visit_with_medicine');
            } else {
                $visit_type = $this->doctorvisit_model->GetDoctorVisitById($appointment->visit_description);
                if ($visit_type) {
                    $visit = $visit_type->visit_description;
                }
            }
            if ($appointment->payment_status == 'paid') {
                $payment_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $payment_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
            }

            if ($appointment->status == 'Confirmed') {
                $option_status = '<button type="button" class="btn btn-info btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Pending Confirmation') {
                $option_status = '<button type="button" class="btn btn-warning btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Treated') {
                $option_status = '<button type="button" class="btn btn-success btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } elseif ($appointment->status == 'Cancelled') {
                $option_status = '<button type="button" class="btn btn-danger btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            } else {
                $option_status = '<button type="button" class="btn btn-default btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
            }

            $payment_details = $this->finance_model->getPaymentById($appointment->payment_id);
            $total_deposited_amount = $this->finance_model->getDepositAmountByPaymentId($payment_details->id);
            $total_due = $payment_details->gross_total - $total_deposited_amount;
            if ($payment_details->gross_total == $total_due) {
                if ($payment_details->gross_total != 0) {
                    $bill_status = '<span class=" label label-warning">' . lang('unpaid') . '</span>';
                } else {
                    $bill_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
                }
            } elseif ($total_due == 0) {
                $bill_status = '<span class=" label label-primary">' . lang('paid') . '</span>';
            } else {
                $bill_status = '<span class=" label label-warning">' . lang('due') . '</span>';
            }
            $deposit = ' <a type="button" class="btn btn-xs btn-primary depositButton" title="' . lang('deposit') . '" data-toggle = "modal" data-id="' . $payment_details->id . '" data-from="' . $payment_details->payment_from . '"><i class="fa fa-money"> </i> ' . lang('deposit') . '</a>';


            $doctor = explode(',', $appointment->board_doctor);
            $doctorlist = '';
            foreach ($doctor as $key => $value) {
                $doctor_name = $this->doctor_model->getDoctorById($value)->name;
                $doctorlist .= '<p>' . $doctor_name . '</p>';
                // print_r($value);
                // die();
            }
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $board_doctor = $appointment->board_doctor;
                if (!empty($board_doctor)) {
                    $number_of_doctor = $appointment->board_doctor . ',' . $appointment->doctor;
                    $board_doctor = explode(',', $number_of_doctor);

                    // $number_of_doctor = $board_doctor . ',' .  $appointment->doctor;
                    $doctor_ion_id = $this->ion_auth->get_user_id();
                    $doctorr = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
                    if (in_array($doctorr, $board_doctor)) {
                        if (!empty($appointment->board_doctor)) {
                            $i = $i + 1;
                            $info[] = array(
                                $appointment->id,
                                $patientname,
                                $appointment->patient_id,
                                $options7,
                                $appointment->patient_phone,
                                $doctorname,
                                $doctorlist,
                                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                                $visit,
                                $payment_details->gross_total,
                                $this->finance_model->getDepositAmountByPaymentId($appointment->payment_id),
                                ($payment_details->gross_total - $this->finance_model->getDepositAmountByPaymentId($appointment->payment_id)),
                                $bill_status,
                                $appointment->remarks,
                                $option_status,
                                $appointment->account_number,
                                $appointment->transaction_id,
                                $option1 . ' ' . $option2 . ' ' . $history,
                            );
                        }
                    }
                }
            } else {
                if (!empty($appointment->board_doctor)) {
                    $info[] = array(
                        $appointment->id,
                        $patientname,
                        $appointment->patient_id,
                        $options7,
                        $appointment->patient_phone,
                        $doctorname,
                        $doctorlist,
                        date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,
                        $visit,
                        $payment_details->gross_total,
                        $this->finance_model->getDepositAmountByPaymentId($appointment->payment_id),
                        ($payment_details->gross_total - $this->finance_model->getDepositAmountByPaymentId($appointment->payment_id)),
                        $bill_status,
                        $appointment->remarks,
                        $option_status,
                        $appointment->account_number,
                        $appointment->transaction_id,
                        $option1 . ' ' . $option2 . '  ' . $deposit,
                    );
                }
            }
        }

        if (!empty($info)) {
            if ($this->ion_auth->in_group(array('Doctor'))) {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => $i,
                    "recordsFiltered" => $i,
                    // "recordsTotal" => count($this->appointment_model->getAppointmentListByBoardDoctor($doctor)),
                    // "recordsFiltered" => count($this->appointment_model->getAppointmentListByBoardDoctor($doctor)),
                    "data" => $info,
                );
            } else {
                $output = array(
                    "draw" => intval($requestData['draw']),
                    "recordsTotal" => count($this->appointment_model->getAppointment()),
                    "recordsFiltered" => count($this->appointment_model->getAppointment()),
                    "data" => $info,
                );
            }
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

    function tt()
    {
        $url = "http://66.45.237.70/api.php";
        $number = "88017,49335508";
        $text = "Hello Bangladesh";
        $data = array(
            'username' => "health",
            'password' => "6W8BZ5SK",
            'number' => $number,
            'message' => $text
        );



        print_r($data);

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus = $p[0];
    }

    function apiTest()
    {
        $apiEndpoint = 'https://esmsbd.net/_backend/index.php?route=extension/module/all_sms_gateway/api/sms/send';
        $accessToken = 'Nq8a4RjHnwCvh1kC4DJQYIBiDOjIMOfMXGfbTFJq';
        $data = array(
            'sender_id' => 'YourName123',
            'type' => 'plain',
            'phone' => '8801749335508',
            'message' => 'This is a test message'
        );
        $data_string = http_build_query($data);

        $ch = curl_init($apiEndpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($data_string),
            'Authorization: Bearer ' . $accessToken
        ));
        $result = curl_exec($ch);
        $res_array = json_decode($result);

        return $result;

        $message1 = '<strong>Dear {name}</strong> ,<br>
    You are registred to {company}.<br>
    Url: {base_url}.<br>
    Username: {username}.<br>
    Password: {password}.<br>
    Regards';
    }


    public function testt()
    {

        // $this->load->view('home/dashboard', $data);
        $this->load->view('test', $data);
        // $this->load->view('home/footer');
    }

    function updateStatus()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $redirect = $this->input->post('redirect');
        $data = array('status' => $status);

        $this->appointment_model->updateAppointment($id, $data);
        $this->session->set_flashdata('feedback', lang('updated'));

        redirect($redirect);
    }















    //-------------Bkash--------------//

  

    public function curlWithBody($url, $header, $method, $body_data_json)
    {   $burl = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta';
        $curl = curl_init($burl . $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body_data_json);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function authHeaders()
    {
        $appkey = "4f6o0cjiki2rfm34kfdadl1eqq";
        return array(
            'Content-Type:application/json',
            'Authorization:' . $this->grant(),
            'X-APP-Key:' . $appkey
        );
    }


    public function grant()
    {
        $username = "sandboxTokenizedUser02";
         $password = "sandboxTokenizedUser02@12345";
          $appkey = "4f6o0cjiki2rfm34kfdadl1eqq";
           $appsecret = "4f6o0cjiki2rfm34kfdadl1eqq";
        $header = array(
            'Content-Type:application/json',
            'username:' . $username,
            'password:' . $password
        );

        $body_data = array(
            'app_key' => $appkey,
            'app_secret' => $appsecret
        );

        $body_data_json = json_encode($body_data);

        $response = $this->curlWithBody('/checkout/token/grant', $header, 'POST', $body_data_json);


        $token = json_decode($response)->id_token;

        return $token;
    }



    public function create()
    {
        $info = $this->input->post();

        $header = $this->authHeaders();
       

        $body_data = array(
            'mode'           => '0011',
            'payerReference' => ' ',
            'callbackURL'    =>  "" . base_url() . "appointment/callback",
            'amount'         => 100, //your payment amount
            'currency'       => 'BDT',
            'intent'         => 'sale',
            'merchantInvoiceNumber' => 12, //your generate invoice id must be unique
        );
        $body_data_json = json_encode($body_data);
        
        $response = $this->curlWithBody('/checkout/create', $header, 'POST', $body_data_json);
        print_r($response);die();
        $bkashURL = json_decode($response)->bkashURL;
       
        redirect($bkashURL, 'refresh');
        exit;
    }

    public function execute($paymentID)
    {

        $header = $this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentID
        );
        $body_data_json = json_encode($body_data);

        $response =  $this->curlWithBody('/checkout/execute', $header, 'POST', $body_data_json);

        return $response;
    }

    public function query($paymentID)
    {
        $header = $this->authHeaders();

        $body_data = array(
            'paymentID' => $paymentID
        );
        $body_data_json = json_encode($body_data);

        $response =  $this->curlWithBody('/checkout/payment/status', $header, 'POST', $body_data_json);


        return $response;
    }

    public function callback()
    {
        $allRequest = $this->input->get();

        if (isset($allRequest['status']) && $allRequest['status'] == 'failure') {
            //echo "failed payment"; // wrong otp //your view
            $sdata = [
                'exception' => '<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> Payment Failed !</h4></div>'
            ];
            $this->session->set_userdata($sdata);
            redirect('appointment'); // your failed page

        } else if (isset($allRequest['status']) && $allRequest['status'] == 'cancel') {
            // echo "you have cancled your payment"; //your view
            $sdata = [
                'exception' => '<div class="alert alert-success alert-dismissible">
        <h4><i class="icon fa fa-check"></i> You have canclled your payment !</h4></div>'
            ];
            $this->session->set_userdata($sdata);
            redirect('appointment'); // your failed page

        } else {

            $response = $this->execute($allRequest['paymentID']);

            $arr = json_decode($response, true);

            if (array_key_exists("statusCode", $arr) && $arr['statusCode'] != '0000') {
                //echo "payment failed ".$arr['statusMessage'];
                $sdata = [
                    'exception' => '<div class="alert alert-success alert-dismissible">
            <h4><i class="icon fa fa-check"></i> Payment Failed !! ' . $arr['statusMessage'] . ' !</h4></div>'
                ];
                $this->session->set_userdata($sdata);
                redirect('appointment'); // your failed page
                exit;
            } else if (array_key_exists("message", $arr)) {
                // if execute api failed to response
                sleep(1);
                $response = $this->query($allRequest['paymentID']);

                //your DB operation

                redirect('appointment'); // your success page

            }

            //your DB operation

            redirect('appointment'); // your success page


        }
    }
}

/* End of file appointment.php */
/* Location: ./application/modules/appointment/controllers/appointment.php */

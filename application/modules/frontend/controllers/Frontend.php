<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Frontend extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('frontend_model');
        $this->load->model('doctor/doctor_model');
        $this->load->model('finance/finance_model');
        $this->load->model('hospital/package_model');
        $this->load->model('privacy/privacy_model');
        $this->load->model('onlinecenter/onlinecenter_model');
        $this->load->model('casetaker/casetaker_model');
        $this->load->model('hospital/hospital_model');
        $this->load->model('appointment/appointment_model');
        $this->load->model('patient/patient_model');
        $this->load->model('frontend/slide_model');
        $this->load->model('frontend/service_model');
        $this->load->model('featured/featured_model');
        $this->load->model('department/department_model');
        require APPPATH . 'third_party/stripe/stripe-php/init.php';
        $this->load->module('paypal');
        $this->load->module('sms');
        $this->load->model('email/email_model');
        $this->load->model('pgateway/pgateway_model');
        $this->load->model('hospital/hospital_model');
        $this->load->model('doctorvisit/doctorvisit_model');
        $this->load->model('donor/donor_model');
        $this->load->model('lab/lab_model');
        $this->load->model('bed/bed_model');
        $this->load->model('medicine/medicine_model');
        $this->load->model('prescription/prescription_model');
        $this->load->model('sms/sms_model');
        $this->load->model('team/team_model');
    }

    public function index()
    {
        $data = array();
        $data['categories'] = $this->db->get('hospital_category')->result();
        $data['hospitals'] = $this->hospital_model->getHospital();
        $data['doctors'] = $this->doctor_model->getDoctor();
        //        $data['doctors'] = $this->doctor_model->getDoctorBySuperadmin();
        $data['packages'] = $this->package_model->getPackage();
        $data['slides'] = $this->slide_model->getSlide();
        $data['services'] = $this->service_model->getService();
        $data['featureds'] = $this->featured_model->getFeatured();
        $data['settings1'] = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row();
        $data['gateway'] = $this->db->get_where('paymentGateway', array('name' => $data['settings1']->payment_gateway, 'hospital_id' => 'superadmin'))->row();
        $this->load->view('front_end', $data);
    }

    public function addNew()
    {
        $id = $this->input->post('id');

        $patient = $this->input->post('patient');

        $doctor = $this->input->post('doctor');
        $date = $this->input->post('date');
        if (!empty($date)) {
            $date = strtotime($date);
        }

        $currency = $this->input->post('currency');
        $time_slot = $this->input->post('time_slot');

        $time_slot_explode = explode('To', $time_slot);

        $s_time = trim($time_slot_explode[0]);
        $e_time = trim($time_slot_explode[1]);

        $remarks = $this->input->post('remarks');

        $sms = $this->input->post('sms');

        $status = 'Requested';

        $redirect = 'frontend';

        $request = 'Yes';

        $user = '';

        if ((empty($id))) {
            $add_date = date('m/d/y');
            $registration_time = time();
            $patient_add_date = $add_date;
            $patient_registration_time = $registration_time;
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

        if ($patient == 'patient_id') {
            $this->form_validation->set_rules('patient_id', 'Patient Name', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        }


        // Validating Name Field
        $this->form_validation->set_rules('patient', 'Patient', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        // Validating Doctor Field
        $this->form_validation->set_rules('doctor', 'Doctor', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        // Validating Date Field
        $this->form_validation->set_rules('date', 'Date', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        // Validating Address Field   
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|min_length[1]|max_length[1000]|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $this->session->set_flashdata('feedback', 'Form Validation Error !');
            redirect("frontend");
        } else {


            if ($patient == 'patient_id') {
                $patient = $this->input->post('patient_id');

                if (!empty($patient)) {
                    $patient_exist = $this->patient_model->getPatientById($patient)->id;
                }

                if (empty($patient_exist)) {
                    $this->session->set_flashdata('feedback', 'Invalid Patient Id !');
                    redirect("frontend");
                }
            }

            if ($patient == 'add_new') {
                $data_p = array(
                    'patient_id' => $patient_id,
                    'name' => $p_name,
                    'email' => $p_email,
                    'phone' => $p_phone,
                    'sex' => $p_gender,
                    'age' => $p_age,
                    'add_date' => $patient_add_date,
                    'registration_time' => $patient_registration_time,
                    'how_added' => 'from_appointment'
                );
                $username = $this->input->post('p_name');
                // Adding New Patient
                if ($this->ion_auth->email_check($p_email)) {
                    $this->session->set_flashdata('feedback', 'Email Address of Patient Is Already Registered');
                    redirect($redirect);
                } else {
                    $dfg = 5;
                    $this->ion_auth->register($username, $password, $p_email, $dfg);
                    $ion_user_id = $this->db->get_where('users', array('email' => $p_email))->row()->id;
                    $this->patient_model->insertPatient($data_p);
                    $patient_user_id = $this->db->get_where('patient', array('email' => $p_email))->row()->id;
                    $id_info = array('ion_user_id' => $ion_user_id);
                    $this->patient_model->updatePatient($patient_user_id, $id_info);
                }

                $patient = $patient_user_id;
                //    }
            }
            //$error = array('error' => $this->upload->display_errors());
            $data = array();
            $data = array(
                'patient' => $patient,
                'doctor' => $doctor,
                'date' => $date,
                's_time' => $s_time,
                'e_time' => $e_time,
                'time_slot' => $time_slot,
                'remarks' => $remarks,
                'add_date' => $add_date,
                'registration_time' => $registration_time,
                'status' => $status,
                'currency' => $currency,
                's_time_key' => $s_time_key,
                'user' => $user,
                'request' => $request
            );
            $username = $this->input->post('name');
            if (empty($id)) {     // Adding New department
                $this->frontend_model->insertAppointment($data);

                //                if (!empty($sms)) {
                //                    $this->sms->sendSmsDuringAppointment($patient, $doctor, $date, $s_time, $e_time);
                //                }

                $patient_doctor = $this->patient_model->getPatientById($patient)->doctor;

                $patient_doctors = explode(',', $patient_doctor);

                if (!in_array($doctor, $patient_doctors)) {
                    $patient_doctors[] = $doctor;
                    $doctorss = implode(',', $patient_doctors);
                    $data_d = array();
                    $data_d = array('doctor' => $doctorss);
                    $this->patient_model->updatePatient($patient, $data_d);
                }
                $this->session->set_flashdata('feedback', 'Appointment Added Successfully. Please wait. You will get a confirmation sms.');
            }

            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect('appointment');
            }
        }
    }

    function getArrayKey($s_time)
    {
        $all_slot = array(
            0 => '12:00 PM',
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
            144 => '12:00 AM',
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

    public function settings()
    {
        if (!$this->ion_auth->in_group('superadmin')) {
            redirect('home/permission');
        }
        $data = array();
        $data['settings'] = $this->frontend_model->getSettings();
        $this->load->view('home/dashboard'); // just the header file
        $this->load->view('settings', $data);
        $this->load->view('home/footer'); // just the footer file
    }

    public function update()
    {
        if (!$this->ion_auth->in_group('superadmin')) {
            redirect('home/permission');
        }
        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $emergency = $this->input->post('emergency');
        $support = $this->input->post('support');
        $currency = $this->input->post('currency');
        $logo = $this->input->post('logo');
        $block_1_text_under_title = $this->input->post('block_1_text_under_title');
        $service_block_text_under_title = $this->input->post('service_block__text_under_title');
        $doctor_block_text_under_title = $this->input->post('doctor_block__text_under_title');
        $registration_block_text = $this->input->post('registration_block_text');
        $facebook_id = $this->input->post('facebook_id');
        $twitter_id = $this->input->post('twitter_id');
        $twitter_username = $this->input->post('twitter_username');
        $google_id = $this->input->post('google_id');
        $youtube_id = $this->input->post('youtube_id');
        $skype_id = $this->input->post('skype_id');
        $comment_1 = $this->input->post('comment_1');
        $comment_2 = $this->input->post('comment_2');
        $verified_1 = $this->input->post('verified_1');
        $verified_2 = $this->input->post('verified_2');
        $comment_logo_1 = $this->input->post('comment_logo_1');
        $comment_logo_2 = $this->input->post('comment_logo_2');
        $partner_header_title = $this->input->post('partner_header_title');
        $partner_header_description = $this->input->post('partner_header_description');
        $section_title_1 = $this->input->post('section_title_1');
        $section_title_2 = $this->input->post('section_title_2');
        $section_title_3 = $this->input->post('section_title_3');
        $section_description_1 = $this->input->post('section_description_1');
        $section_description_2 = $this->input->post('section_description_2');
        $section_description_3 = $this->input->post('section_description_3');
        $section_1_text_1 = $this->input->post('section_1_text_1');
        $section_1_text_2 = $this->input->post('section_1_text_2');
        $section_1_text_3 = $this->input->post('section_1_text_3');
        $section_2_text_1 = $this->input->post('section_2_text_1');
        $section_2_text_2 = $this->input->post('section_2_text_2');
        $section_2_text_3 = $this->input->post('section_2_text_3');
        $section_3_text_1 = $this->input->post('section_3_text_1');
        $section_3_text_2 = $this->input->post('section_3_text_2');
        $section_3_text_3 = $this->input->post('section_3_text_3');
        $partner_image_1 = $this->input->post('partner_image_1');
        $partner_image_2 = $this->input->post('partner_image_2');
        $partner_image_3 = $this->input->post('partner_image_3');
        $market_title = $this->input->post('market_title');
        $market_description = $this->input->post('market_description');
        $market_button_link = $this->input->post('market_button_link');
        $market_image = $this->input->post('market_image');
        $market_commentator_name = $this->input->post('market_commentator_name');
        $market_commentator_designation = $this->input->post('market_commentator_designation');
        $market_comment = $this->input->post('market_comment');
        $commentator_profile_image = $this->input->post('commentator_profile_image');
        $commentator_logo_1 = $this->input->post('commentator_logo_1');
        $commentator_logo_2 = $this->input->post('commentator_logo_2');
        $commentator_logo_3 = $this->input->post('commentator_logo_3');
        $team_title = $this->input->post('team_title');
        $team_description = $this->input->post('team_description');
        $team_button_link = $this->input->post('team_button_link');
        $team_commentator_name = $this->input->post('team_commentator_name');
        $team_commentator_designation = $this->input->post('team_commentator_designation');
        $team_comment = $this->input->post('team_comment');
        $team_verified = $this->input->post('team_verified');
        $team_review_logo = $this->input->post('team_review_logo');
        $team_commentator_image = $this->input->post('team_commentator_image');
        $contact_us = $this->input->post('contact_us');
        $chat_js = $this->input->post('chat_js');
        if (!empty($email)) {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            // Validating Title Field
            $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[100]|xss_clean');
            // Validating Email Field
            $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[1]|max_length[100]|xss_clean');
            // Validating Address Field   
            $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[1]|max_length[1000]|xss_clean');
            // Validating Phone Field           
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[1]|max_length[100]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('currency', 'Currency', 'trim|required|min_length[1]|max_length[100]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('logo', 'Logo', 'trim|min_length[1]|max_length[100]|xss_clean');

            // Validating Currency Field   
            $this->form_validation->set_rules('emergency', 'Emergency', 'trim|min_length[1]|max_length[100]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('support', 'Support', 'trim|min_length[1]|max_length[100]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('logo', 'Logo', 'trim|min_length[1]|max_length[100]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('block_1_text_under_title', 'Block 1 Text Under Title', 'trim|min_length[1]|max_length[500]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('service_block__text_under_title', 'Service Block Text Under Title', 'trim|min_length[1]|max_length[500]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('doctor_block__text_under_title', 'Doctor Block Text Under Title', 'trim|min_length[1]|max_length[500]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('facebook_id', 'Facebook Id', 'trim|min_length[1]|max_length[100]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('twitter_id', 'Teitter Id', 'trim|min_length[1]|max_length[100]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('twitter_username', 'Teitter Username', 'trim|min_length[1]|max_length[100]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('google_id', 'Google Id', 'trim|min_length[1]|max_length[100]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('youtube_id', 'Youtube Id', 'trim|min_length[1]|max_length[100]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('skype_id', 'Skype Id', 'trim|min_length[1]|max_length[100]|xss_clean');

            // Validating Currency Field   
            $this->form_validation->set_rules('comment_1', 'Comment 1', 'trim|min_length[1]|max_length[1000]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('comment_2', 'Comment 2', 'trim|min_length[1]|max_length[1000]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('verified_1', 'Verified 1', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('verified_2', 'Verified 2', 'trim|min_length[1]|max_length[200]|xss_clean');

            // Validating Currency Field   
            $this->form_validation->set_rules('partner_header_title', 'Header Title', 'trim|min_length[1]|max_length[300]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('partner_header_description', 'Header Description', 'trim|min_length[1]|max_length[800]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_title_1', 'Title', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_description_1', 'Description', 'trim|min_length[1]|max_length[800]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_1_text_1', 'Text 1', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_1_text_2', 'Text 2', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_1_text_3', 'Text 3', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_title_2', 'Title', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_description_2', 'Description', 'trim|min_length[1]|max_length[800]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_2_text_1', 'Text 1', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_2_text_2', 'Text 2', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_2_text_3', 'Text 3', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_title_3', 'Title', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_description_3', 'Description', 'trim|min_length[1]|max_length[800]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_3_text_1', 'Text 1', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_3_text_2', 'Text 2', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('section_3_text_3', 'Text 3', 'trim|min_length[1]|max_length[200]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('market_title', 'Title', 'trim|min_length[1]|max_length[300]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('market_description', 'Description', 'trim|min_length[1]|max_length[800]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('market_button_link', 'Button Link', 'trim|min_length[1]|max_length[300]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('market_commentator_name', 'Commentator Name', 'trim|min_length[1]|max_length[300]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('market_commentator_designation', 'Commentator Designation', 'trim|min_length[1]|max_length[500]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('market_comment', 'Comment', 'trim|min_length[1]|max_length[1000]|xss_clean');

            // Validating Currency Field   
            $this->form_validation->set_rules('team_title', 'Title', 'trim|min_length[1]|max_length[300]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('team_description', 'Description', 'trim|min_length[1]|max_length[800]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('team_button_link', 'Button Link', 'trim|min_length[1]|max_length[300]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('team_commentator_name', 'Commentator Name', 'trim|min_length[1]|max_length[300]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('team_commentator_designation', 'Commentator Designation', 'trim|min_length[1]|max_length[500]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('team_comment', 'Comment', 'trim|min_length[1]|max_length[800]|xss_clean');
            // Validating Currency Field   
            $this->form_validation->set_rules('contact_us', 'Contact Us Text', 'trim|min_length[1]|max_length[300]|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                $data = array();
                $data['settings'] = $this->settings_model->getSettings();
                $this->load->view('home/dashboard'); // just the header file
                $this->load->view('settings', $data);
                $this->load->view('home/footer'); // just the footer file
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
                        'title' => $title,
                        'address' => $address,
                        'phone' => $phone,
                        'email' => $email,
                        'currency' => $currency,
                        'emergency' => $emergency,
                        'support' => $support,
                        'block_1_text_under_title' => $block_1_text_under_title,
                        'service_block__text_under_title' => $service_block_text_under_title,
                        'doctor_block__text_under_title' => $doctor_block_text_under_title,
                        'registration_block_text' => $registration_block_text,
                        'facebook_id' => $facebook_id,
                        'twitter_id' => $twitter_id,
                        'twitter_username' => $twitter_username,
                        'google_id' => $google_id,
                        'youtube_id' => $youtube_id,
                        'skype_id' => $skype_id,
                        'logo' => $img_url,
                        'comment_1' => $comment_1,
                        'comment_2' => $comment_2,
                        'verified_1' => $verified_1,
                        'verified_2' => $verified_2,
                        'partner_header_title' => $partner_header_title,
                        'partner_header_description' => $partner_header_description,
                        'section_title_1' => $section_title_1,
                        'section_description_1' => $section_description_1,
                        'section_1_text_1' => $section_1_text_1,
                        'section_1_text_2' => $section_1_text_2,
                        'section_1_text_3' => $section_1_text_3,
                        'section_title_2' => $section_title_2,
                        'section_description_2' => $section_description_2,
                        'section_2_text_1' => $section_2_text_1,
                        'section_2_text_2' => $section_2_text_2,
                        'section_2_text_3' => $section_2_text_3,
                        'section_title_3' => $section_title_3,
                        'section_description_3' => $section_description_3,
                        'section_3_text_1' => $section_3_text_1,
                        'section_3_text_2' => $section_3_text_2,
                        'section_3_text_3' => $section_3_text_3,
                        'market_title' => $market_title,
                        'market_description' => $market_description,
                        'market_button_link' => $market_button_link,
                        'market_commentator_name' => $market_commentator_name,
                        'market_commentator_designation' => $market_commentator_designation,
                        'market_comment' => $market_comment,
                        'team_title' => $team_title,
                        'team_description' => $team_description,
                        'team_button_link' => $team_button_link,
                        'team_commentator_name' => $team_commentator_name,
                        'team_commentator_designation' => $team_commentator_designation,
                        'team_comment' => $team_comment,
                        'team_verified' => $team_verified,
                        'contact_us' => $contact_us,
                        'chat_js' => $chat_js
                    );
                } else {
                    $data = array();
                    $data = array(
                        'title' => $title,
                        'address' => $address,
                        'phone' => $phone,
                        'email' => $email,
                        'currency' => $currency,
                        'emergency' => $emergency,
                        'support' => $support,
                        'block_1_text_under_title' => $block_1_text_under_title,
                        'service_block__text_under_title' => $service_block_text_under_title,
                        'doctor_block__text_under_title' => $doctor_block_text_under_title,
                        'registration_block_text' => $registration_block_text,
                        'facebook_id' => $facebook_id,
                        'twitter_id' => $twitter_id,
                        'twitter_username' => $twitter_username,
                        'google_id' => $google_id,
                        'youtube_id' => $youtube_id,
                        'skype_id' => $skype_id,
                        'comment_1' => $comment_1,
                        'comment_2' => $comment_2,
                        'verified_1' => $verified_1,
                        'verified_2' => $verified_2,
                        'partner_header_title' => $partner_header_title,
                        'partner_header_description' => $partner_header_description,
                        'section_title_1' => $section_title_1,
                        'section_description_1' => $section_description_1,
                        'section_1_text_1' => $section_1_text_1,
                        'section_1_text_2' => $section_1_text_2,
                        'section_1_text_3' => $section_1_text_3,
                        'section_title_2' => $section_title_2,
                        'section_description_2' => $section_description_2,
                        'section_2_text_1' => $section_2_text_1,
                        'section_2_text_2' => $section_2_text_2,
                        'section_2_text_3' => $section_2_text_3,
                        'section_title_3' => $section_title_3,
                        'section_description_3' => $section_description_3,
                        'section_3_text_1' => $section_3_text_1,
                        'section_3_text_2' => $section_3_text_2,
                        'section_3_text_3' => $section_3_text_3,
                        'market_title' => $market_title,
                        'market_description' => $market_description,
                        'market_button_link' => $market_button_link,
                        'market_commentator_name' => $market_commentator_name,
                        'market_commentator_designation' => $market_commentator_designation,
                        'market_comment' => $market_comment,
                        'team_title' => $team_title,
                        'team_description' => $team_description,
                        'team_button_link' => $team_button_link,
                        'team_commentator_name' => $team_commentator_name,
                        'team_commentator_designation' => $team_commentator_designation,
                        'team_comment' => $team_comment,
                        'team_verified' => $team_verified,
                        'contact_us' => $contact_us,
                        'chat_js' => $chat_js
                    );
                }


                $this->frontend_model->updateSettings($id, $data);
                $data2 = array();
                $file_name = $_FILES['block_img_url']['name'];
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

                if ($this->upload->do_upload('block_img_url')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data2 = array(
                        'block_img_url' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data2);
                }
                $data3 = array();
                $file_name = $_FILES['comment_logo_1']['name'];
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

                if ($this->upload->do_upload('comment_logo_1')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data3 = array(
                        'comment_logo_1' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data3);
                }

                $data4 = array();
                $file_name = $_FILES['comment_logo_2']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('comment_logo_2')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data4 = array(
                        'comment_logo_2' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data4);
                }

                $data5 = array();
                $file_name = $_FILES['partner_image_1']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('partner_image_1')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data5 = array(
                        'partner_image_1' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data5);
                }

                $data6 = array();
                $file_name = $_FILES['partner_image_2']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('partner_image_2')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data6 = array(
                        'partner_image_2' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data6);
                }

                $data7 = array();
                $file_name = $_FILES['partner_image_3']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('partner_image_3')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data7 = array(
                        'partner_image_3' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data7);
                }
                $data8 = array();
                $file_name = $_FILES['market_image']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('market_image')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data8 = array(
                        'market_image' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data8);
                }
                $data9 = array();
                $file_name = $_FILES['commentator_profile_image']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('commentator_profile_image')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data9 = array(
                        'commentator_profile_image' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data9);
                }
                $data10 = array();
                $file_name = $_FILES['commentator_logo_1']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('commentator_logo_1')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data10 = array(
                        'commentator_logo_1' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data10);
                }
                $data11 = array();
                $file_name = $_FILES['commentator_logo_2']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('commentator_logo_2')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data11 = array(
                        'commentator_logo_2' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data11);
                }
                $data12 = array();
                $file_name = $_FILES['commentator_logo_3']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('commentator_logo_3')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data12 = array(
                        'commentator_logo_3' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data12);
                }

                $data13 = array();
                $file_name = $_FILES['team_review_logo']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('team_review_logo')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data13 = array(
                        'team_review_logo' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data13);
                }

                $data14 = array();
                $file_name = $_FILES['team_commentator_image']['name'];
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
                    'allowed_types' => "gif|jpg|png|svg|jpeg|pdf",
                    'overwrite' => False,
                    'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
                    'max_height' => "10000",
                    'max_width' => "10000"
                );

                $this->load->library('Upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('team_commentator_image')) {
                    $path = $this->upload->data();
                    $img_url = "uploads/" . $path['file_name'];

                    $data14 = array(
                        'team_commentator_image' => $img_url
                    );
                    $this->frontend_model->updateSettings($id, $data14);
                }
                $this->session->set_flashdata('feedback', 'Updated');
                // Loading View
                redirect('frontend/settings');
            }
        } else {
            $this->session->set_flashdata('feedback', 'Email Required!');
            redirect('frontend/settings', 'refresh');
        }
    }

    function send()
    {
        $emailSettings = $this->email_model->getContactEmailSettings();
        $other_email = $this->input->post('other_email');
        $message = $this->input->post('message');
        $subject = $this->input->post('subject');
        $name = $this->input->post('name');
        $msg = $this->input->post('msg');
        $phone = $this->input->post('phone');
        $hospital_name = $this->input->post('hospital_name');

        $data1 = array(
            'other_email' => $other_email,
            'msg' => $msg,
            'name' => $name,
            'phone' => $phone,
            'hospital_name' => $hospital_name
        );

        $recipient = $other_email;
        if (!empty($other_email)) {
            $to = $other_email;
        } else {
            if (!empty($to)) {
                $to = implode(',', $to);
            }
        }

        if (!empty($to)) {
            $autoemail = $this->email_model->getContactEmailByType('contactus');
            $subject = $this->input->post('subject');
            $message1 = $autoemail->message;
            $messageprint1 = $this->parser->parse_string($message1, $data1);
            $this->email->from($other_email);
            $this->email->to($emailSettings->admin_email);
            $this->email->subject('Contact Email');
            $this->email->message($messageprint1);
            $this->email->send();

            $this->session->set_flashdata('feedbac', lang('request_sent_successfully'));
        } else {
            $this->session->set_flashdata('feedbac', lang('not_sent'));
        }
        redirect('frontend#contact');
    }

    public function addNewHospitalPayment()
    {
        $trial_version = $this->input->post('trial_version');
        $country = $this->input->post('country');
        $name = $this->input->post('name');
        //$password = $this->input->post('password');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $package = $this->input->post('package');
        $language = $this->input->post('language');
        $package_duration = $this->input->post('package_duration');
        $price = $this->input->post('price');

        $package_details = $this->db->get_where('package', array('id' => $package))->row();

        if ($trial_version == '1') {
            $data = array();
            $data = array(
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'phone' => $phone,
                'package' => $package,
                'language' => $language,
                'package_duration' => $package_duration,
                'price' => '0',
                'country' => $country,
                'package_details' => 'trial'
            );

            $this->addNewhospital($data);
        } else {
            $data = array();
            $data = array(
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'phone' => $phone,
                'package' => $package,
                'language' => $language,
                'package_duration' => $package_duration,
                'country' => $country,
                'price' => $price
            );
            $gateway = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row()->payment_gateway;

            if ($gateway == 'PayPal') {
                $data['cardholder'] = $this->input->post('cardholder');
                $data['card_type'] = $this->input->post('card_type');
                $data['card_number'] = $this->input->post('card_number');
                $data['expire_date'] = $this->input->post('expire_date');
                $data['cvv'] = $this->input->post('cvv_number');
                $response = $this->paypal->paymentPaypalFromFrontend($data, 'Frontend');

                if ($response == 'yes') {
                    $data['gateway'] = 'PayPal';
                    $this->addNewhospital($data);
                } else {
                    $this->session->set_flashdata('feedback', lang('Please_check_card_details'));
                    redirect('frontend#book');
                }
            } elseif ($gateway == 'Stripe') {

                $token = $this->input->post('token');
                $stripe = $this->db->get_where('paymentGateway', array('hospital_id' => 'superadmin', 'name' => 'Stripe'))->row();

                \Stripe\Stripe::setApiKey($stripe->secret);
                $charge = \Stripe\Charge::create(array(
                    "amount" => $price * 100,
                    "currency" => "usd",
                    "source" => $token
                ));
                $chargeJson = $charge->jsonSerialize();
                if ($chargeJson['status'] == 'succeeded') {
                    $data['gateway'] = 'Stripe';
                    $this->addNewhospital($data);
                } else {
                    $this->session->set_flashdata('feedback', lang('Please_check_card_details'));
                    redirect('frontend#book');
                }
            } elseif ($gateway == 'Pay U Money') {
                $dfg = 11;
                $this->ion_auth->register($name, '12345', $email, $dfg);
                $ion_user_id = $this->db->get_where('users', array('email' => $email))->row()->id;
                $this->hospital_model->insertHospital($data);
                $hospital_user_id = $this->db->get_where('hospital', array('email' => $email))->row()->id;
                $id_info = array('ion_user_id' => $ion_user_id);
                $this->hospital_model->updateHospital($hospital_user_id, $id_info);
                $this->load->module('payu');
                $this->payu->check4($data, $price, $hospital_user_id, 'frontend');
            } elseif ($gateway == 'Paystack') {

                $paystack = $this->db->get_where('paymentGateway', array('hospital_id' => 'superadmin', 'name' => 'Paystack'))->row();

                $ref = date('Y') . '-' . rand() . date('d') . '-' . date('m');
                $amount_in_kobo = $price;
                $callback_url = base_url() . 'frontend#book';
                $postdata = array('first_name' => $name, 'email' => $email, 'amount' => $amount_in_kobo * 100, "reference" => $ref, 'callback_url' => $callback_url);

                $url = "https://api.paystack.co/transaction/initialize";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));  //Post Fields
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $headers = [
                    'Authorization: Bearer ' . $paystack->secret,
                    'Content-Type: application/json',
                ];
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $request = curl_exec($ch);
                curl_close($ch);

                if ($request) {
                    $result = json_decode($request, true);
                }

                $redir = $result['data']['authorization_url'];

                header("Location: " . $redir);
                if ($result['status'] == 1) {
                    $data['gateway'] = 'Paystack';

                    $this->addNewhospital($data);
                } else {
                    $this->session->set_flashdata('feedback', lang('Please_check_card_details'));
                    redirect('frontend#book');
                }
                exit();
            }
        }
    }

    public function addNewhospital($data1)
    {

        $name = $data1['name'];
        $password = '12345';
        $email = $data1['email'];
        $address = $data1['address'];
        $phone = $data1['phone'];
        $package = $data1['package'];
        $language = $data1['language'];
        $package_duration = $data1['package_duration'];
        $price = $data1['price'];
        $country = $data1['country'];
        $package_details = $data1['package_details'];
        if (empty($package_details)) {
            $gateway = $data1['gateway'];
        } else {
            $gateway = 'trial';
        }
        //$package_details=$this->package_model->getPackageById($package);
        if (!empty($package)) {
            $module = $this->package_model->getPackageById($package)->module;
            $p_limit = $this->package_model->getPackageById($package)->p_limit;
            $d_limit = $this->package_model->getPackageById($package)->d_limit;
        }




        $language_array = array('english', 'arabic', 'spanish', 'french', 'italian', 'portuguese');

        if (!in_array($language, $language_array)) {
            $language = 'english';
        }

        $data = array();
        $data = array(
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'package' => $package,
            'p_limit' => $p_limit,
            'd_limit' => $d_limit,
            'module' => $module,
            'country' => $country,
        );

        $username = $name;

        // Adding New Hospital
        if ($this->ion_auth->email_check($email)) {
            $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));
            redirect('frontend#book');
        } else {
            $dfg = 11;
            $this->ion_auth->register($username, $password, $email, $dfg);
            $ion_user_id = $this->db->get_where('users', array('email' => $email))->row()->id;
            $this->hospital_model->insertHospital($data);
            $hospital_user_id = $this->db->get_where('hospital', array('email' => $email))->row()->id;
            $id_info = array('ion_user_id' => $ion_user_id);
            $this->hospital_model->updateHospital($hospital_user_id, $id_info);
            $hospital_settings_data = array();
            $hospital_settings_data = array(
                'hospital_id' => $hospital_user_id,
                'title' => $name,
                'email' => $email,
                'address' => $address,
                'phone' => $phone,
                'language' => $language,
                'system_vendor' => 'Code Aristos - Hospital management System',
                'discount' => 'flat',
                'sms_gateway' => 'Twilio',
                'currency' => '$',
                'emailtype' => 'Smtp'
            );
            $this->settings_model->insertSettings($hospital_settings_data);
            $hospital_blood_bank = array();
            $hospital_blood_bank = array('A+' => '0 Bags', 'A-' => '0 Bags', 'B+' => '0 Bags', 'B-' => '0 Bags', 'AB+' => '0 Bags', 'AB-' => '0 Bags', 'O+' => '0 Bags', 'O-' => '0 Bags');
            foreach ($hospital_blood_bank as $key => $value) {
                $data_bb = array('group' => $key, 'status' => $value, 'hospital_id' => $hospital_user_id);
                $this->donor_model->insertBloodBank($data_bb);
                $data_bb = NULL;
            }

            $data_sms_clickatell = array();
            $data_sms_clickatell = array(
                'name' => 'Clickatell',
                'username' => 'Your ClickAtell Username',
                'password' => 'Your ClickAtell Password',
                'api_id' => 'Your ClickAtell Api Id',
                'user' => 'self',
                'hospital_id' => $hospital_user_id
            );

            $this->sms_model->addSmsSettings($data_sms_clickatell);

            $data_sms_msg91 = array(
                'name' => 'MSG91',
                'username' => 'Your MSG91 Username',
                'api_id' => 'Your MSG91 API ID',
                'sender' => 'Sender Number',
                'authkey' => 'Your MSG91 Auth Key',
                'user' => $this->ion_auth->get_user_id(),
                'hospital_id' => $hospital_user_id
            );

            $this->sms_model->addSmsSettings($data_sms_msg91);

            $data_sms_twilio = array(
                'name' => 'Twilio',
                'sid' => 'SID Number',
                'token' => 'Token Number',
                'sendernumber' => 'Sender Number',
                'user' => $this->ion_auth->get_user_id(),
                'hospital_id' => $hospital_user_id
            );

            $this->sms_model->addSmsSettings($data_sms_twilio);
            $data_sms_80kobo = array(
                'name' => '80Kobo',
                'email' => 'Your 80Kobo Username',
                'password' => 'Your 80Kobo Password',
                'sender_name' => 'Sender Name',
                'user' => $this->ion_auth->get_user_id(),
                'hospital_id' => $hospital_user_id
            );

            $this->sms_model->addSmsSettings($data_sms_80kobo);
            $data_pgateway_paypal = array(
                'name' => 'PayPal', // Sandbox / testing mode option.
                'APIUsername' => 'PayPal API Username', // PayPal API username of the API caller
                'APIPassword' => 'PayPal API Password', // PayPal API password of the API caller
                'APISignature' => 'PayPal API Signature', // PayPal API signature of the API caller
                'status' => 'test',
                'hospital_id' => $hospital_user_id
            );

            $this->pgateway_model->addPaymentGatewaySettings($data_pgateway_paypal);

            $data_pgateway_payumoney = array(
                'name' => 'Pay U Money', // Sandbox / testing mode option.
                'merchant_key' => 'Merchant key', // PayPal API username of the API caller
                'salt' => 'Salt', // PayPal API password of the API caller
                'status' => 'test',
                'hospital_id' => $hospital_user_id
            );

            $this->pgateway_model->addPaymentGatewaySettings($data_pgateway_payumoney);

            $data_pgateway_stripe = array(
                'name' => 'Stripe', // Sandbox / testing mode option.
                'secret' => 'Secret', // Sandbox / testing mode option.
                'publish' => 'Publish', // PayPal API username of the API caller
                'hospital_id' => $hospital_user_id
            );

            $this->pgateway_model->addPaymentGatewaySettings($data_pgateway_stripe);

            $data_pgateway_payumoney = array(
                'name' => 'Paystack', // Sandbox / testing mode option.
                'public_key' => 'Public key', // PayPal API username of the API caller
                'secret' => 'secret', // PayPal API password of the API caller
                'status' => 'test',
                'hospital_id' => $hospital_user_id
            );

            $this->pgateway_model->addPaymentGatewaySettings($data_pgateway_payumoney);
            $data_email_settings = array(
                'type' => 'Domain Email',
                'admin_email' => 'Admin Email', // Sandbox / testing mode option.
                'hospital_id' => $hospital_user_id
            );
            $data_email_settings_smtp = array(
                'type' => 'Smtp',
                'smtp_host' => 'smtp_host',
                'smtp_port' => 'smtp_port',
                'send_multipart' => 'send_multipart',
                'mail_provider' => 'mail_provider',
                'hospital_id' => $hospital_user_id
            );

            $base_url = str_replace(array('http://', 'https://', ' '), '', base_url()) . "auth/login";
            $set['settings'] = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row();
            $name1 = explode(' ', $name);
            if (!isset($name1[1])) {
                $name1[1] = null;
            }
            if (empty($package_details)) {
                if ($package_duration == 'monthly') {
                    $next_due_date_stamp = time() + 2592000;
                    $package_lang = lang('monthly');
                } else {
                    $next_due_date_stamp = time() + 31536000;
                    $package_lang = lang('yearly');
                }
            } else {
                if ($package_duration == 'monthly') {
                    $package_lang = lang('monthly');
                } else {
                    $package_lang = lang('yearly');
                }
                $next_due_date_stamp = time() + 1296000;
                $package_lang = lang($package_lang);
            }
            $next_due_date = date('d-m-Y', $next_due_date_stamp);
            $package_name = $this->db->get_where('package', array('id' => $package))->row()->name;
            $data1 = array(
                'name' => $name,
                'package_name' => $package_name,
                'subscription_duration' => $package_lang,
                'base_url' => $base_url,
                'amount' => $price,
                'password' => $password,
                'username' => $email,
                'phone' => $set['settings']->phone,
                'next_payment_date' => $next_due_date
            );
            //  $autoemail = $this->email_model->getAutoEmailByTypee('hospital');
            //if ($autoemail->status == 'Active') {

            $mail_provider = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row()->emailtype;
            $settngs_name = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row()->system_vendor;
            $email_Settings = $this->email_model->getAdminEmailSettingsByIdByType($mail_provider);

            $message1 = '<strong>{name}</strong> ,<br>
Your hospital is registered successfully . Please check the details Below.<br>
Package Name: {package_name}.<br>
Subscription Length: {subscription_duration}.<br>
Amount Paid: {amount}.<br>
Next Payment Date: {next_payment_date}.<br>
<u><b>Login Details:</b></u><br>
Url: {base_url}<br>
Username: {username}<br>
Password: {password}.<br>

For Any Support Please Contact with Phone No: {phone}';
            $messageprint1 = $this->parser->parse_string($message1, $data1);
            if ($mail_provider == 'Domain Email') {
                $this->load->library('email');
                $this->email->from($email_Settings->admin_email);
            }
            if ($mail_provider == 'Smtp') {
                $config['protocol'] = 'smtp';
                $config['mailpath'] = '/usr/sbin/sendmail';
                $config['smtp_host'] = $email_Settings->smtp_host;
                $config['smtp_port'] = number_format($email_Settings->smtp_port);
                $config['smtp_user'] = $email_Settings->user;
                $config['smtp_pass'] = base64_decode($email_Settings->password);
                $config['smtp_crypto'] = 'tls';
                $config['mailtype'] = 'html';
                $config['charset'] = 'utf-8';
                $config['wordwrap'] = TRUE;
                $config['send_multipart'] = TRUE;
                $config['newline'] = "\r\n";
                $this->load->library('email');
                $this->email->initialize($config);
                $this->load->library('email');
                $this->email->from($email_Settings->user, $settngs_name);
            }
            $this->email->to($email);
            $this->email->subject('Hospital Registration confirmation');
            $this->email->message($messageprint1);
            $this->email->send();

            $this->email_model->addEmailSettings($data_email_settings_smtp);
            $this->email_model->addEmailSettings($data_email_settings);

            $this->hospital_model->createAutoSmsTemplate($hospital_user_id);
            $this->hospital_model->createAutoEmailTemplate($hospital_user_id);

            $data_payment = array();
            $data_payment = array(
                'hospital_user_id' => $hospital_user_id,
                'price' => $price,
                'package_duration' => $package_duration,
                'next_due_date_stamp' => $next_due_date_stamp,
                'next_due_date' => $next_due_date,
                'add_date_stamp' => time(),
                'add_date' => date('d-m-Y', time()),
                'package' => $package
            );
            $this->frontend_model->addHospitalPayment($data_payment);
            $inserted_id = $this->db->insert_id('hospital_payment');
            $data_deposit = array();
            $data_deposit = array(
                'payment_id' => $inserted_id,
                'date' => time(),
                'deposited_amount' => $price,
                'deposited_amount_id' => $inserted_id . 'gp',
                'gateway' => $gateway,
                'hospital_user_id' => $hospital_user_id,
                'next_due_date_stamp' => $next_due_date_stamp,
                'next_due_date' => $next_due_date,
                'add_date_stamp' => time(),
                'add_date' => date('d-m-Y', time()),
            );
            $deposit = $this->frontend_model->addHospitalDeposit($data_deposit);
            if ($deposit) {
                $data_payment_update = array('status' => 'paid');
                $this->frontend_model->updateHospitalPayment($inserted_id, $data_payment_update);
            }
            $this->session->set_flashdata('feedback', lang('new_hospital_created'));
            if ($gateway != 'Paystack') {

                redirect('frontend#book');
            }
        }

        // Loading View
        //}
    }

    function getPackageForHospitalRegisteration()
    {
        $id = $this->input->get('id');
        $data['package'] = $this->package_model->getPackageById($id);
        // $data['settings'] = $this->settings_model->getSettingsByHId($id);
        echo json_encode($data);
    }

    public function getHospitalinfo()
    {
        // Search term
        $searchTerm = $this->input->post('searchTerm');

        // Get users
        $response = $this->frontend_model->getHospitalInfo($searchTerm);

        echo json_encode($response);
    }

    function getHospitalBlock()
    {

        $this->db->select('*');

        //            $this->db->where("name like '%" . $searchTerm . "%' OR id like '%" . $searchTerm . "%'");
        $data = $this->db->get('hospital');
        //            $data = $fetched_records->result_array();
        //        $data = array();
        //        foreach ($users as $user) {
        //            $data[] = array("id" => $user['id'], "text" => $user['name'] . ' (' . lang('id') . ': ' . $user['id'] . ')');
        //        }
        return $data->result();
    }

    //function getHospitalBlock() {
    ////        $this->db->where('id', $id);
    //        $data = $this->db->get("hospital");
    //        if ($data->num_rows() > 0) {
    //
    //            return $data->result();
    //        } else {
    //            return fasle;
    //        }
    //    }
    public function getOnlinecenter()
    {
        // Search term
        $searchTerm = $this->input->post('searchTerm');

        // Get users
        $response = $this->frontend_model->getOnlinecenter($searchTerm);

        echo json_encode($response);
    }

    public function getDoctorInfo()
    {
        // Search term
        $searchTerm = $this->input->post('searchTerm');
        $category = $this->input->post('catchange');
        $medid = $this->input->post('medid');
        // Get users
        $response = $this->doctor_model->getDoctorInfoByOnlinecenter($category, $searchTerm);

        echo json_encode($response);
    }

    public function getDepartmentInfo()
    {
        // Search term
        $searchTerm = $this->input->post('searchTerm');
        $category = $this->input->post('catchange');
        $medid = $this->input->post('medid');
        // Get users
        $response = $this->frontend_model->getDepartmentInfoBySuperadmin($category, $searchTerm);

        echo json_encode($response);
    }

    public function getCasetaker()
    {
        // Search term
        $searchTerm = $this->input->post('searchTerm');
        $category = $this->input->post('catchange');
        $medid = $this->input->post('medid');
        // Get users
        $response = $this->frontend_model->getCasetakerByOnlinecenter($category, $searchTerm);

        echo json_encode($response);
    }

    public function getPatientInfo()
    {
        // Search term
        $searchTerm = $this->input->post('searchTerm');
        $category = $this->input->post('catchange');
        $medid = $this->input->post('medid');
        // Get users
        $response = $this->frontend_model->getPatientInfo($category, $searchTerm);

        echo json_encode($response);
    }

    public function getPatientForBoard()
    {
        // Search term
        $searchTerm = $this->input->post('searchTerm');
        $category = $this->input->post('catchange');
        $medid = $this->input->post('medid');
        // Get users
        $response = $this->frontend_model->getPatientForBoard($searchTerm);

        echo json_encode($response);
    }

    public function getPatientInfoAddNew()
    {
        // Search term
        $searchTerm = $this->input->post('searchTerm');
        $category = $this->input->post('catchange');
        $medid = $this->input->post('medid');
        // Get users
        $response = $this->patient_model->getPatientInfoByOnlinecenterAddNewOption($category, $searchTerm);

        echo json_encode($response);
    }

    public function getPatientinfoWithAddNewOption()
    {
        // Search term
        $searchTerm = $this->input->post('searchTerm');

        // Get users
        $response = $this->patient_model->getPatientinfoWithAddNewOptionn($searchTerm);

        echo json_encode($response);
    }

    function getAvailableSlotByDoctorByDateByJason()
    {
        $data = array();
        $date = $this->input->get('date');
        if ($date != "") {
            if (!empty($date)) {
                $date = strtotime($date);
            }
            $doctor = $this->input->get('doctor');
            $data['aslots'] = $this->frontend_model->getAvailableSlotByDoctorByDate($date, $doctor);
            echo json_encode($data);
        } else {
            $data['aslots'] = "";
            echo json_encode($data);
        }
    }

    public function getAvailableDoctor()
    {
        $data = array();
        $hospital_id = $this->input->get('hospital_id');
        //        $medid = $this->input->post('medid');
        // Get users
        $data['adoctors'] = $this->doctor_model->getAvailableDoctor($hospital_id);

        echo json_encode($data);
    }

    public function searchAvailableDoctor()
    {
        $data = array();
        $hospital_id = $this->input->get('hospital_id');
        $search = $this->input->get('search');
        $data['adoctors'] = $this->frontend_model->searchAvailableDoctor($hospital_id, $search);

        echo json_encode($data);
    }

    public function getPatientinfoWithAddNewOption2()
    {
        // Search term
        $id = $this->input->post('id');
        $searchTerm = $this->input->post('searchTerm');

        // Get users
        $response = $this->frontend_model->getPatientinfoWithAddNewOption($searchTerm, $id);

        echo json_encode($response);
    }

    public function test()
    {
        $id = $this->input->post('id');
        $patient = $this->input->post('patient');
        $doctor = $this->input->post('doctor');
        $phone = $this->input->post('phone');
        $hospital_id = $this->input->post('hospital_id');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // Validating Category Name Field
        $this->form_validation->set_rules('patient', 'Name', 'trim|min_length[1]|max_length[100]|xss_clean');
        // Validating Description Field
        //        $this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            if (!empty($id)) {
                $data = array();
                $data['bed'] = $this->bed_model->getBedCategoryById($id);
                $this->load->view('home/dashboard');
                $this->load->view('add_category_view', $data);
                $this->load->view('home/footer');
            } else {
                $data = array();
                $data['setval'] = 'setval';
                $this->load->view('home/dashboard');
                $this->load->view('add_category', $data);
                $this->load->view('home/footer');
            }
        } else {
            $data = array();
            $data = array(
                'patient' => $patient,
                'doctor' => $doctor,
                'phone' => $phone,
                'hospital_id' => $hospital_id
            );
            if (empty($id)) {
                $this->frontend_model->insertTest($data);
                $this->session->set_flashdata('feedback', lang('added'));
            } else {
                $this->bed_model->updateBedCategory($id, $data);
                $this->session->set_flashdata('feedback', lang('updated'));
            }
            redirect('frontend');
        }
    }

    public function addNewAppointmentRequest()
    {
        $id = $this->input->post('id');
        $hospital_id = $this->input->post('hospital');
        //        $onlinecenter_id = $this->input->post('onlinecenter_id');
        //        $casetaker_id = $this->input->post('casetaker_id');
        $patient = $this->input->post('patient');
        $doctor = $this->input->post('doctor');
        $date = $this->input->post('date');
        if (!empty($date)) {
            $date = strtotime($date);
        }


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
            $p_email =  $p_name . '-' . rand(1, 1000) . '@hd.com';
        }
        if (!empty($p_name)) {
            $password = $p_name . '-' . rand(1, 100000000);
        }
        $p_phone = $this->input->post('p_phone');
        $p_age = $this->input->post('p_age');
        $p_gender = $this->input->post('p_gender');
        //        $patient_id = rand(10000, 1000000);
        $patient_id = $this->input->post('p_id');

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

        if ($this->form_validation->run() == FALSE) {

            if (!empty($id)) {
                redirect("frontend");
            } else {
                redirect("frontend");
            }
        } else {

            if ($patient == 'add_new') {

                //                $limit = $this->patient_model->getLimit();
                //                if ($limit <= 0) {
                //                    $this->session->set_flashdata('feedback', lang('patient_limit_exceed'));
                //                    redirect('patient');
                //                }

                $data_p = array(
                    'patient_id' => $patient_id,
                    'name' => $p_name,
                    'email' => $p_email,
                    'hospital_id' => $hospital_id,
                    'phone' => $p_phone,
                    'sex' => $p_gender,
                    'age' => $p_age,
                    'add_date' => $patient_add_date,
                    'registration_time' => $patient_registration_time,
                    'how_added' => 'from_appointment'
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
                    $this->patient_model->insertPatientByOnlinecenter($data_p);
                    $inserted_id = $this->db->insert_id();
                    $patient_id = 10000 + $inserted_id;
                    $data3 = array(
                        'patient_id' => $patient_id,
                    );

                    $this->patient_model->insertPatientId($inserted_id, $data3);

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
                $live_meeting_link = 'https://meet.jit.si/' . $room_id;
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
                //                'onlinecenter_id' => $onlinecenter_id,
                //                'casetaker_id' => $casetaker_id,
                'time_slot' => $time_slot,
                'remarks' => $remarks,
                'add_date' => $add_date,
                'registration_time' => $registration_time,
                'status' => 'Pending Confirmation',
                's_time_key' => $s_time_key,
                'user' => $user,
                'request' => $request,
                'room_id' => $room_id,
                'live_meeting_link' => $live_meeting_link
            );
            $username = $this->input->post('name');
            if (empty($id)) {     // Adding New department
                $this->frontend_model->insertAppointment($data);
                $this->session->set_flashdata('appointment', lang('appointment_added_successfully_please_wait_you_will_get_a_confirmation_sms'));
                $patient_doctor = $this->patient_model->getPatientByOnlinecenter($patient)->doctor;

                $patient_doctors = explode(',', $patient_doctor);

                if (!in_array($doctor, $patient_doctors)) {
                    $patient_doctors[] = $doctor;
                    $doctorss = implode(',', $patient_doctors);
                    $data_d = array();
                    $data_d = array('doctor' => $doctorss);
                    $this->patient_model->updatePatient($patient, $data_d);
                }
                //                $this->sendSmsDuringAppointment($id, $data, $patient, $doctor, $status);
                $this->session->set_flashdata('feedback', lang('added'));
            } else { // Updating department
                //                $previous_status = $this->appointment_model->getAppointmentById($id)->status;
                //                if ($previous_status != "Confirmed") {
                //                    if ($status == "Confirmed") {
                //                        $this->sendSmsDuringAppointment($id, $data, $patient, $doctor, $status);
                //                    }
                //                }
                $this->appointment_model->updateAppointment($id, $data);

                $this->session->set_flashdata('feedback', lang('updated'));
            }


            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect('frontend');
            }
        }
    }
    public function addAppointmentFromIframe()
    {
        $id = $this->input->post('id');
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

        $redirectlink = $this->input->post('redirectlink');
        $user = $this->ion_auth->get_user_id();

        if ($this->ion_auth->in_group(array('Patient'))) {
            $user = '';
        }

        $consultant_fee = $this->input->post('visit_charges');

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
        //        $patient_id = rand(10000, 1000000);
        $patient_id = $this->input->post('p_id');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($patient == 'add_new') {
            $this->form_validation->set_rules('p_name', 'Patient Name', 'trim|required|min_length[1]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('p_phone', 'Patient Phone', 'trim|required|min_length[1]|max_length[100]|xss_clean');
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

        if ($this->form_validation->run() == FALSE) {


            $this->session->set_flashdata('appointment', lang('form_validation_error'));
            redirect('frontend');
        } else {

            if ($patient == 'add_new') {


                $hospital_id = $this->input->post('hospital');
                $onlinecenter_id = $this->input->post('onlinecenter_id');
                $casetaker_id = $this->input->post('casetaker_id');


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
                    'add_date' => $patient_add_date,
                    'registration_time' => $patient_registration_time,
                    'how_added' => 'from_appointment'
                );
                $username = $this->input->post('p_name');
                // Adding New Patient

                if ($this->ion_auth->email_check($p_email)) {
                    $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));
                    if (!empty($redirect)) {
                        redirect($redirect);
                    } else {

                        redirect('frontend');
                    }
                } else {

                    $dfg = 5;
                    $this->ion_auth->register($username, $password, $p_email, $dfg);
                    $ion_user_id = $this->db->get_where('users', array('email' => $p_email))->row()->id;
                    //                    $this->patient_model->insertPatient($data_p);
                    //                    if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                    $this->patient_model->insertPatientByOnlinecenter($data_p);
                    //                    } elseif (!$this->ion_auth->logged_in()){
                    //                        $this->patient_model->insertPatientByOnlinecenter($data_p);
                    //                    }
                    //                    else {
                    //                        $this->patient_model->insertPatient($data_p);
                    //                    }
                    $inserted_id = $this->db->insert_id();
                    $patient_id = 10000 + $inserted_id;
                    $data3 = array(
                        'patient_id' => $patient_id,
                    );
                    //                    if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                    $this->patient_model->insertPatientIdByOnlinecenter($inserted_id, $data3);
                    //                    } elseif (!$this->ion_auth->logged_in()){
                    //                        $this->patient_model->insertPatientIdByOnlinecenter($inserted_id, $data3);
                    //                    }
                    //                    else {
                    //                        $this->patient_model->insertPatientId($inserted_id, $data3);
                    //                    }
                    $patient_user_id = $this->db->get_where('patient', array('email' => $p_email))->row()->id;
                    $id_info = array('ion_user_id' => $ion_user_id);
                    $this->patient_model->updatePatient($patient_user_id, $id_info);

                    $this->hospital_model->addHospitalIdToIonUser($ion_user_id, $this->input->post('hospital'));
                }

                $patient = $patient_user_id;
                //    }
            }

            //            $patient_phone = $this->patient_model->getPatientById($patient)->phone;
            $patient_phone = $this->patient_model->getPatientByOnlinecenter($patient)->phone;
            if (empty($id)) {
                $room_id = 'hms-meeting-' . $patient_phone . '-' . rand(10000, 1000000) . '-' . $this->input->post('hospital');
                $live_meeting_link = 'https://meet.jit.si/' . $room_id;
            } else {
                $appointment_details = $this->appointment_model->getAppointmentById($id);
                $room_id = $appointment_details->room_id;
                $live_meeting_link = $appointment_details->live_meeting_link;
            }




            $patientname = $this->patient_model->getPatientByOnlinecenter($patient)->name;
            $doctorname = $this->doctor_model->getDoctorById($doctor)->name;
            $patient_details = $this->patient_model->getPatientByOnlinecenter($patient);
            $data = array();

            //            if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $hospital_id = $this->input->post('hospital');
            $data = array(
                'patient' => $patient,
                'patientname' => $patientname,
                'doctor' => $doctor,
                'doctorname' => $doctorname,
                'date' => $date,
                's_time' => $s_time,
                'e_time' => $e_time,
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
                'visit_charges' => $this->input->post('visit_charges'),
                'visit_description' => $this->input->post('visit_description'),
                'hospital_id' => $hospital_id
            );
            $data_appointment = array(
                'category_name' => 'Consultant Fee',
                'patient' => $patient,
                'amount' => $this->input->post('visit_charges'),
                'doctor' => $doctor,
                'discount' => '0',
                'flat_discount' => '0',
                'gross_total' => $this->input->post('visit_charges'),
                'status' => 'unpaid',
                'hospital_amount' => '0',
                'doctor_amount' => $this->input->post('visit_charges'),
                'user' => $user,
                'patient_name' => $patient_details->name,
                'patient_phone' => $patient_details->phone,
                'patient_address' => $patient_details->address,
                'doctor_name' => $doctorname,
                'remarks' => $remarks,
                'payment_from' => 'appointment',
                'hospital_id' => $hospital_id
            );

            $username = $this->input->post('name');
            if (empty($id)) {     // Adding New department
                $data['payment_status'] = 'unpaid';
                //                if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                //                    $this->appointment_model->insertAppointment($data);
                //                } elseif (!$this->ion_auth->logged_in()){
                //                    $this->appointment_model->insertAppointmentByOnlinecenter($data);
                //                }
                //                else {
                $this->appointment_model->insertAppointmentByOnlinecenter($data);
                //                }
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
                //                $response = $this->sendSmsDuringAppointment($id, $data, $patient, $doctor, $status, $hospital_id);
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
                    );
                    $date = time();

                    $this->appointmentPayment($deposit_type, $data_for_payment, $patient, $doctor, $consultant_fee, $date, $inserted_id, $redirectlink);
                } else {
                    $this->session->set_flashdata('feedback', lang('request_sent_successfully'));
                    redirect('frontend/appointmentFront');
                }
                //                $this->session->set_flashdata('feedback', lang('added'));
            } else { // Updating department
                $appointment_contingent = $this->appointment_model->getAppointmentById($id);

                if ($appointment_contingent->payment_status == 'unpaid' || empty($appointment_contingent->payment_status)) {

                    $data['visit_charges'] = $this->input->post('visit_charges');
                    $data['discount'] = '0';
                    $data['grand_total'] = $this->input->post('visit_charges');
                    $this->appointment_model->updateAppointment($id, $data);

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
                        );
                        $date = time();

                        $this->appointmentPayment($deposit_type, $data_for_payment, $patient, $doctor, $consultant_fee, $date, $appointment_contingent->payment_id, $redirectlink);
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
                } else {
                    redirect('frontend');
                }
            }
        }
    }
    public function hospitalBlock()
    {
        //        $doctor_id = $this->input->post('doctor_id');


        $query = $this->hospital_model->getHospital();

        $list = null;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $value) {
                $list .= "<span><i class='fa fa-calendar'></i> $value->id [$value->name] $value->name</span><br>";
            }
            $data['message'] = $list;
            $data['status'] = true;
        } else {
            $data['message'] = "No Doctor available";
            $data['status'] = false;
        }


        echo json_encode($data);
    }

    public function getHospitalByCategory()
    {
        $category = $this->input->get('category');
        $data['hospitals'] = $this->db->get_where('hospital', array('category' => $category))->result();
        echo json_encode($data);
    }

    public function addNewAppointment()
    {
        $id = $this->input->post('id');
        $patient = $this->input->post('patient');

        $visit_type = $this->input->post('visit_type');
        $appointment_subtotal = $this->input->post('appointment_subtotal');
        $date = $this->input->post('date');
        if (!empty($date)) {
            $date = strtotime($date);
        }
        $currency = $this->input->post('currency');
        $onlinecenter_id = $this->input->post('onlinecenter_id');
        $casetaker_id = $this->input->post('casetaker_id');

        $superadmin = $this->input->post('superadmin');
        $time_slot = $this->input->post('time_slot');

        $time_slot_explode = explode('To', $time_slot);

        $s_time = trim($time_slot_explode[0]);
        $e_time = trim($time_slot_explode[1]);

        $remarks = $this->input->post('remarks');

        $sms = $this->input->post('sms');

        $pay_now_appointment = $this->input->post('pay_now_appointment');
        $country = $this->input->post('country');
        $redirect = $this->input->post('redirect');

        $request = $this->input->post('request');

        if (empty($request)) {
            $request = '';
        }

        $redirectlink = $this->input->post('redirectlink');
        $user = $this->ion_auth->get_user_id();

        if ($this->ion_auth->in_group(array('Patient'))) {
            $user = '';
        }

        $consultant_fee = $this->input->post('visit_charges');
        $account_number = $this->input->post('account_number');
        $transaction_id = $this->input->post('transaction_id');
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
        $p_phone = $this->input->post('p_phone');
        $p_name = $this->input->post('p_name');
        $p_email = $this->input->post('p_email');
        if (empty($p_email)) {
            $p_email =  $p_name . '-' . $p_phone . rand(1, 100000) . '@hd.com';
        }
        if (!empty($p_name)) {
            $password = $p_name . '-' . rand(1, 100000000);
        }

        // $p_age = $this->input->post('p_age');
        $p_address = $this->input->post('p_address');
        $p_gender = $this->input->post('p_gender');
        $p_birthdate = $this->input->post('p_birthdate');
        //        $patient_id = rand(10000, 1000000);
        $patient_id = $this->input->post('p_id');
        if (empty($p_birthdate)) {
            $years = $this->input->post('years');
            $months = $this->input->post('months');
            $days = $this->input->post('days');
            if (empty($years)) {
                $years = '0';
            }
            if (empty($months)) {
                $months = '0';
            }
            if (empty($days)) {
                $days = '0';
            }
        } else {
            $dateOfBirth = $p_birthdate;
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $years = $diff->format('%y');
            $months = $diff->format('%m');
            $days = $diff->format('%d');
        }
        if (!empty($p_birthdate)) {
            $bdate = strtotime($p_birthdate);
            $p_birthdate = date('d-m-Y', $bdate);
        } else {
            $p_birthdate = date('d-m-Y', strtotime("-$years years -$months months -$days days"));
        }
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
        if (!$this->input->post('pay_for_courier')) {
            $courier_fee = '';
        } else {
            $courier_fee = $this->input->post('courier_fee');
        }
        $casetaker_fee = $this->input->post('casetaker_fee');
        $onlinecenter_fee = $this->input->post('onlinecenter_fee');
        $developer_fee = $this->input->post('developer_fee');
        $superadmin_fee = $this->input->post('superadmin_fee');
        $medicine_fee = $this->input->post('medicine_fee');
        $doctor_amount = $this->input->post('doctor_amount');
        $total_charges = $this->input->post('total_charges');
        $appointment_subtotal = $this->input->post('appointment_subtotal');
        $additional_fee = $this->input->post('additional_fee');

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

        if ($this->form_validation->run() == FALSE) {


            $this->session->set_flashdata('appointment', lang('form_validation_error'));
            redirect('frontend');
        } else {

            if ($patient == 'add_new') {


                $hospital_id = $this->input->post('hospital');
                $onlinecenter_id = $this->input->post('onlinecenter_id');
                $casetaker_id = $this->input->post('casetaker_id');


                $data_p = array(
                    'patient_id' => $patient_id,
                    'superadmin' => $superadmin,
                    'hospital_id' => $hospital_id,
                    'onlinecenter_id' => $onlinecenter_id,
                    'casetaker_id' => $casetaker_id,
                    'name' => $p_name,
                    'email' => $p_email,
                    'phone' => $p_phone,
                    'address' => $p_address,
                    'sex' => $p_gender,
                    'age' => $p_age,
                    'birthdate' => $p_birthdate,
                    'country' => $country,
                    'doctor' => $doctor,
                    'add_date' => $patient_add_date,
                    'registration_time' => $patient_registration_time,
                    'how_added' => 'from_appointment'
                );
                $username = $this->input->post('p_name');
                // Adding New Patient

                if ($this->ion_auth->email_check($p_email)) {
                    $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));
                    if (!empty($redirect)) {
                        redirect($redirect);
                    } else {

                        redirect('frontend');
                    }
                } else {

                    $dfg = 5;
                    $this->ion_auth->register($username, $password, $p_email, $dfg);
                    $ion_user_id = $this->db->get_where('users', array('email' => $p_email))->row()->id;
                    //                    $this->patient_model->insertPatient($data_p);
                    //                    if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                    $this->patient_model->insertPatientByOnlinecenter($data_p);
                    //                    } elseif (!$this->ion_auth->logged_in()){
                    //                        $this->patient_model->insertPatientByOnlinecenter($data_p);
                    //                    }
                    //                    else {
                    //                        $this->patient_model->insertPatient($data_p);
                    //                    }
                    $inserted_id = $this->db->insert_id();
                    $patient_id = 10000 + $inserted_id;
                    $data3 = array(
                        'patient_id' => $patient_id,
                    );
                    //                    if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                    $this->patient_model->insertPatientIdByOnlinecenter($inserted_id, $data3);
                    //                    } elseif (!$this->ion_auth->logged_in()){
                    //                        $this->patient_model->insertPatientIdByOnlinecenter($inserted_id, $data3);
                    //                    }
                    //                    else {
                    //                        $this->patient_model->insertPatientId($inserted_id, $data3);
                    //                    }
                    $patient_user_id = $this->db->get_where('patient', array('email' => $p_email))->row()->id;
                    $id_info = array('ion_user_id' => $ion_user_id);
                    $this->patient_model->updatePatient($patient_user_id, $id_info);

                    $this->hospital_model->addHospitalIdToIonUser($ion_user_id, $this->input->post('hospital'));
                }

                $patient = $patient_user_id;
                //    }
            }elseif ($patient == '') {
                $patient = $this->input->post('patientt');
            }

            //            $patient_phone = $this->patient_model->getPatientById($patient)->phone;
            $patient_phone = $this->patient_model->getPatientByOnlinecenter($patient)->phone;
            if (empty($id)) {
                $room_id = 'hms-meeting-' . $patient_phone . '-' . rand(10000, 1000000) . '-' . $this->input->post('hospital');
                $live_meeting_link = 'https://meet.jit.si/' . $room_id;
            } else {
                $appointment_details = $this->appointment_model->getAppointmentById($id);
                $room_id = $appointment_details->room_id;
                $live_meeting_link = $appointment_details->live_meeting_link;
            }
            if (!empty($pay_now_appointment)) {
                $status = 'Requested';
            } else {
                $status = $this->input->post('status');
            }
            $patient_id = $this->patient_model->getPatientByOnlinecenter($patient)->patient_id;
            $patientname = $this->patient_model->getPatientByOnlinecenter($patient)->name;
            $doctorname = $this->doctor_model->getDoctorById($doctor)->name;
            $patient_details = $this->patient_model->getPatientByOnlinecenter($patient);
            $data = array();

            //            if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $hospital_id = $this->input->post('hospital');
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
                'superadmin' => $superadmin,
                'add_date' => $add_date,
                'currency' => $currency,
                'registration_time' => $registration_time,
                'status' => $status,
                'onlinecenter_id' => $onlinecenter_id,
                'casetaker_id' => $casetaker_id,
                's_time_key' => $s_time_key,
                'user' => $user,
                'request' => $request,
                'room_id' => $room_id,
                'account_number' => $account_number,
                'transaction_id' => $transaction_id,
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

                'hospital_id' => $hospital_id
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
                'doctor_amount' => $this->input->post('visit_charges'),
                'user' => $user,
                'currency' => $currency,
                'patient_name' => $patient_details->name,
                'patient_phone' => $patient_details->phone,
                'patient_address' => $patient_details->address,
                'doctor_name' => $doctorname,
                'remarks' => $remarks,
                'payment_from' => 'appointment',
                'hospital_id' => $hospital_id
            );

            $username = $this->input->post('name');
            if (empty($id)) {     // Adding New department
                $data['payment_status'] = 'unpaid';
                //                if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                //                    $this->appointment_model->insertAppointment($data);
                //                } elseif (!$this->ion_auth->logged_in()){
                //                    $this->appointment_model->insertAppointmentByOnlinecenter($data);
                //                }
                //                else {
                $this->appointment_model->insertAppointmentByOnlinecenter($data);

                //                }
                $appointment_id = $this->db->insert_id('appointment');
                $data_appointment['appointment_id'] = $appointment_id;
                $data_appointment['date'] = time();
                $data_appointment['date_string'] = date('d-m-Y');
                // if (!$this->input->post('pay_now_appointment') && $status == 'Requested') {
                //     $inserted_id = '';
                // } else {

                $this->finance_model->insertPaymentForAllAccess($data_appointment);
                // print_r($data_appointment);
                // die();
                $inserted_id = $this->db->insert_id('payment');
                $deposit_type = $this->input->post('deposit_type');
                $data_update_payment_id_in_appointment = array('payment_id' => $inserted_id);
                $this->appointment_model->updateAppointment($appointment_id, $data_update_payment_id_in_appointment);
                // }

                $patient_doctor = $this->patient_model->getPatientByOnlinecenter($patient)->doctor;

                $patient_doctors = explode(',', $patient_doctor);

                if (!in_array($doctor, $patient_doctors)) {
                    $patient_doctors[] = $doctor;
                    $doctorss = implode(',', $patient_doctors);
                    $data_d = array();
                    $data_d = array('doctor' => $doctorss);
                    $this->patient_model->updatePatient($patient, $data_d);
                }
                $response = $this->sendSmsDuringAppointment($id, $data, $patient, $doctor, $status, $hospital_id, $appointment_id);
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

                    $this->appointmentPayment($deposit_type, $data_for_payment, $patient, $doctor, $consultant_fee, $date, $inserted_id, $redirectlink, $redirect);
                } else {
                    $this->session->set_flashdata('feedback', lang('request_sent_successfully'));
                    //                    redirect('frontend#appointmentt');
                    if (!empty($redirect)) {
                        $this->session->set_flashdata('feedback', lang('request_sent_successfully'));
                        redirect($redirect);
                    } else {

                        redirect('frontend#appointmentt');
                    }
                }
                //                $this->session->set_flashdata('feedback', lang('added'));
            } else { // Updating department
                $appointment_contingent = $this->appointment_model->getAppointmentById($id);

                if ($appointment_contingent->payment_status == 'unpaid' || empty($appointment_contingent->payment_status)) {

                    $data['visit_charges'] = $this->input->post('visit_charges');
                    $data['discount'] = '0';
                    $data['grand_total'] = $this->input->post('visit_charges');
                    $data['deposited_amount'] = $this->input->post('deposited_amount');
                    $this->appointment_model->updateAppointment($id, $data);

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

                        $this->appointmentPayment($deposit_type, $data_for_payment, $patient, $doctor, $consultant_fee, $date, $appointment_contingent->payment_id, $redirectlink, $redirect);
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
                } else {
                    redirect('frontend');
                }
            }
        }
    }

    public function appointmentPayment($deposit_type, $data, $patient, $doctor, $consultant_fee, $date, $inserted_id, $redirectlink, $redirect)
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
                    'hospital_id' => $hospital_id
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
                    "source" => $token
                ));
                $chargeJson = $charge->jsonSerialize();
                if ($chargeJson['status'] == 'succeeded') {
                    $data1 = array(
                        'date' => $date,
                        'patient' => $patient,
                        'payment_id' => $inserted_id,
                        'deposited_amount' => $data['deposited_amount'],
                        'deposit_type' => 'Card',
                        'amount_received_id' => $inserted_id . '.' . 'gp',
                        'gateway' => 'Stripe',
                        'user' => $user,
                        'payment_from' => 'appointment'
                    );

                    //                    if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                    //
                    //                        $this->finance_model->insertDeposit($data1);
                    //                    } elseif (!$this->ion_auth->logged_in()){
                    //                        $data1['hospital_id'] = $hospital_id;
                    //                        $this->finance_model->insertDepositByOnlinecenter($data1);
                    //                    }
                    //                    else {
                    $data1['hospital_id'] = $hospital_id;

                    $data1['onlinecenter_id'] = $pay->onlinecenter_id;
                    if (!empty($pay->casetaker_id)) {
                        $data1['casetaker_id'] = $pay->casetaker_id;
                    }
                    $this->finance_model->insertDepositByOnlinecenter($data1);
                    //                    }

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

            $redirect = $this->input->post('redirect');
            // $hospital = $this->input->post('hospital');
            $patient_details = $this->patient_model->getPatientByOnlinecenter($patient);
            $pgateway = $this->db->get_where('paymentGateway', array('hospital_id' => $patient_details->hospital_id, 'name =' => 'Aamarpay'))->row();
            // if ($patient == 'add_new') {
            //     $patient_name =  $this->input->post('name');
            //     $patient_email =  $this->input->post('email');
            //     $patient_address =  $this->input->post('address');
            //     $patient_phone =  $this->input->post('phone');
            //  }else{
            $patient_name =  $patient_details->name;
            $patient_email =  $patient_details->email;
            $patient_address =  $patient_details->address;
            $patient_phone =  $patient_details->phone;
            //  }
            // $url = 'https://sandbox.aamarpay.com/request.php'; 
            $url = 'https://secure.aamarpay.com/request.php';
            // live url https://secure.aamarpay.com/request.php
            $fields = array(
                'store_id' => $pgateway->store_id, //store id will be aamarpay,  contact integration@aamarpay.com for test/live id
                'amount' => $this->input->post('deposited_amount'), //transaction amount
                'payment_type' => 'VISA', //no need to change
                'currency' => 'BDT',  //currenct will be USD/BDT
                'tran_id' => rand(1111111, 9999999), //transaction id must be unique from your end
                'cus_name' => $patient_name,  //customer name
                'cus_email' => 'hdhomeo@gmail.com', //customer email address
                'cus_add1' => $patient_address,  //customer address
                'cus_add2' => $patient_address, //customer address
                'cus_city' => 'Dhaka',  //customer city
                'cus_state' => 'Dhaka',  //state
                'cus_postcode' => '1206', //postcode or zipcode
                'cus_country' => 'Bangladesh',  //country
                'cus_phone' => $patient_phone, //customer phone number
                'cus_fax' => 'Not¬Applicable',  //fax
                'ship_name' => $patient_name, //ship name
                'ship_add1' => $patient_address,  //ship address
                'ship_add2' => $patient_address,
                'ship_city' => 'Dhaka',
                'ship_state' => 'Dhaka',
                'ship_postcode' => '1212',
                'ship_country' => 'Bangladesh',
                'desc' => 'payment description',
                'success_url' => 'https://hdhealth.org/hospital/frontend/success', //your success route
                'fail_url' => 'https://hdhealth.org', //your fail route
                'cancel_url' => 'https://hdhealth.org', //your cancel url
                'opt_a' => 'Reshad',  //optional paramter
                'opt_b' => 'Akil',
                'opt_c' => $redirect,
                'opt_d' => $inserted_id,
                'signature_key' => $pgateway->signature_key
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
            //            if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            //
            //                $this->finance_model->insertDeposit($data1);
            //            } elseif (!$this->ion_auth->logged_in()){
            //                $data1['hospital_id'] = $hospital_id;
            //                $this->finance_model->insertDepositByOnlinecenter($data1);
            //            }
            //            else {
            $data1['hospital_id'] = $hospital_id;

            $data1['onlinecenter_id'] = $pay->onlinecenter_id;
            if (!empty($pay->casetaker_id)) {
                $data1['casetaker_id'] = $pay->casetaker_id;
            }
            $this->finance_model->insertDepositByOnlinecenter($data1);
            //            }

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
        }else{
            $data1 = array();
            $data1 = array(
                'date' => $date,
                'patient' => $patient,
                'doctor' => $doctor,
                'deposited_amount' => $data['deposited_amount'],
                'payment_id' => $inserted_id,
                'amount_received_id' => $inserted_id . '.' . 'gp',
                'deposit_type' => 'Cash',
                'user' => $this->ion_auth->get_user_id(),
                'payment_from' => 'appointment'
            );
          
            $data1['hospital_id'] = $hospital_id;

            $data1['onlinecenter_id'] = $pay->onlinecenter_id;
            if (!empty($pay->casetaker_id)) {
                $data1['casetaker_id'] = $pay->casetaker_id;
            }
            $this->finance_model->insertDepositByOnlinecenter($data1);
            //            }

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
        // if ($redirectlink == 'frontend') {
        redirect($redirect);
        // }
    }



    function redirect_to_merchant($url)
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
            $redirect = $_POST['opt_c'];
            $amount = $_POST['amount'];
            // $amount = $this->input->post('visit_charges');
            // $pay_amount = explode('.', $amount);
            $payment_details = $this->frontend_model->getPaymentById($paystatus);
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
                'payment_from' => 'appointment'
            );
            $pay = $this->db->get_where('payment', array('id' => $paystatus))->row();
            // if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {

            //     $this->finance_model->insertDeposit($data1);
            // } else {
            $data1['hospital_id'] = $payment_details->hospital_id;

            // $data1['onlinecenter_id'] = $pay->onlinecenter_id;
            // if (!empty($pay->casetaker_id)) {
            //     $data1['casetaker_id'] = $pay->casetaker_id;
            // }
            $this->finance_model->insertDepositByOnlinecenter($data1);
            // }

            $data_payment = array('amount_received' => $amount, 'deposit_type' => 'Aamarpay', 'status' => 'paid');
            $this->frontend_model->updatePayment($paystatus, $data_payment);

            $appointment_id = $this->frontend_model->getPaymentById($paystatus)->appointment_id;

            $appointment_details = $this->frontend_model->getAppointmentById($appointment_id);

            if ($appointment_details->status == 'Requested' || $appointment_details->status == 'Pending Confirmation') {

                $data_appointment_status = array('payment_status' => 'paid');
            } else {
                $data_appointment_status = array('payment_status' => 'paid');
            }

            $this->frontend_model->updateAppointment($appointment_id, $data_appointment_status);
            $this->session->set_flashdata('feedback', lang('payment_successful'));
            // $this->session->set_flashdata('feedback', lang('request_sent_successfully'));
            redirect($redirect);
        }
    }

    function sendSmsDuringAppointment($id, $data, $patient, $doctor, $status, $hospital_id = '', $appointment_id)
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
            $data1 = array(
                'firstname' => $name1[0],
                'lastname' => $name1[1],
                'name' => $patientdetails->name,
                'doctorname' => $doctordetails->name,
                'visit_place' => $visit_place,
                'appointment_no' => $appointment_details->id,
                'appoinmentdate' => date('d-m-Y', $data['date']),
                'time_slot' => $data['time_slot'],
                'hospital_name' => $set['settings']->system_vendor
            );

            if ($autosms->status == 'Active') {
                $messageprint = $this->parser->parse_string($message, $data1);

                $data2[] = array($to => $messageprint, 'country' => $country);
                $response = $this->sms->sendSmsByCountry($to, $message, $data2, $country, $hospital_id);
                // $response = $this->sms->sendSmsDuringAppointmentCreation($to, $message, $data2, $hospital_id);
            }
        }

        if (!empty($doctor)) {
            $appointment_link = str_replace(array('http://', 'https://', ' '), '', base_url()) . "appointment";
            $message = $autosmsdoc->message;
            $to = $doctordetails->phone;
            $country = $doctordetails->country;
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
                'hospital_name' => $set['settings']->system_vendor
            );

            if ($autosmsdoc->status == 'Active') {
                $messageprint = $this->parser->parse_string($message, $data1);

                $data2[] = array($to => $messageprint, 'country' => $country);
                $response = $this->sms->sendSmsByCountry($to, $message, $data2, $country, $hospital_id);
                // $response = $this->sms->sendSmsDuringAppointmentCreation($to, $message, $data2, $hospital_id);
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

    function getHospitalByCategoryy()
    {
        $category = $this->input->get('category');
        $data['hospital'] = $this->hospital_model->getHospitalByCategory($category);
        echo json_encode($data);
    }


    function getHospitalSettings()
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

    public function appointmentFront()
    {
        $data = array();
        $data['categories'] = $this->db->get('hospital_category')->result();
        $data['hospitals'] = $this->hospital_model->getHospital();
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['b_doctors'] = $this->doctor_model->getBoardDoctor();
        //        $data['doctors'] = $this->doctor_model->getDoctorBySuperadmin();
        $data['packages'] = $this->package_model->getPackage();
        $data['slides'] = $this->slide_model->getSlide();
        $data['services'] = $this->service_model->getService();
        $data['featureds'] = $this->featured_model->getFeatured();
        $data['settings1'] = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row();
        $data['gateway'] = $this->db->get_where('paymentGateway', array('name' => $data['settings1']->payment_gateway, 'hospital_id' => 'superadmin'))->row();
        $this->load->view('appointment_front', $data);
    }
    public function getDoctorVisit()
    {
        $id = $this->input->get('id');
        $first_visits = $this->doctor_model->getDoctorFirstVisitByDoctorId($id);
        $visits = $this->doctor_model->getDoctorVisitByDoctorId($id);
        // $option = '<option value="">' . lang('select') . '</option>';
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
    public function getDoctorVisitChargess()
    {
        $id = $this->input->get('id');
        $data['response'] = $this->doctorvisit_model->getDoctorvisitById($id);


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
            // else {
            //     if ($visiting_places[$i] == 'finance') {
            //         $option .= '<div class="col-md-6"><i class="fa fa-times"></i> ' . lang('financial_activities') . '<br></div>';
            //     } elseif ($visiting_places[$i] == 'lab') {
            //         $option .= '<div class="col-md-6"><i class="fa fa-times"></i> ' . lang('lab_tests') . '<br></div>';
            //     } else {
            //         $option .= '<div class="col-md-6"><i class="fa fa-times"></i> ' . lang($visiting_places[$i]) . '<br></div>';
            //     }
            // }
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

    public function appointmentLink()
    {
        $data = array();

        $id = $this->input->get('id');
        $data['casetaker_id'] = $this->input->get('casetaker_id');
        $data['onlinecenter_id'] = $this->input->get('onlinecenter_id');
        $data['video'] = $this->db->get_where('tutorial', array('id' => 1))->row();
        $data['doctor'] = $this->doctor_model->getDoctorById($id);
        $data['hospital'] = $this->hospital_model->getHospitalById($data['doctor']->hospital_id);

        $data['settingss'] = $this->db->get_where('settings', array('hospital_id' => $data['hospital']->id))->row();
        $data['settings1'] = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row();
        $data['gateway'] = $this->db->get_where('paymentGateway', array('name' => $data['settings1']->payment_gateway, 'hospital_id' => 'superadmin'))->row();
        $this->load->view('appointment_front_link', $data);
    }

    public function hospitalAppointmentLink()
    {
        $data = array();

        $id = $this->input->get('id');
        $data['id'] = $this->input->get('id');
        // $data['hospital'] = $this->hospital_model->getHospitalById($id);
        // $data['categories'] = $this->db->get('hospital_category')->result();
        // $data['hospitals'] = $this->hospital_model->getHospital();
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['b_doctors'] = $this->doctor_model->getBoardDoctor();
        //        $data['doctors'] = $this->doctor_model->getDoctorBySuperadmin();
        // $data['packages'] = $this->package_model->getPackage();
        // $data['slides'] = $this->slide_model->getSlide();
        // $data['services'] = $this->service_model->getService();
        // $data['featureds'] = $this->featured_model->getFeatured();
        // $data['settings1'] = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row();
        // $data['gateway'] = $this->db->get_where('paymentGateway', array('name' => $data['settings1']->payment_gateway, 'hospital_id' => 'superadmin'))->row();
        $data['casetaker_id'] = $this->input->get('casetaker_id');
        $data['onlinecenter_id'] = $this->input->get('onlinecenter_id');
        $data['video'] = $this->db->get_where('tutorial', array('id' => 19))->row();
        // $data['doctor'] = $this->doctor_model->getDoctorById($id);
        $data['hospital'] = $this->hospital_model->getHospitalById($data['id']);

        $data['settingss'] = $this->db->get_where('settings', array('hospital_id' => $data['hospital']->id))->row();
        $data['settings1'] = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row();
        $data['gateway'] = $this->db->get_where('paymentGateway', array('name' => $data['settings1']->payment_gateway, 'hospital_id' => 'superadmin'))->row();
        $this->load->view('hospital_appointment_front_link', $data);
    }

    function getHospitalCommissionSettings()
    {
        $hospital_id = $this->input->get('hospital_id');
        $data['commission'] = $this->db->get_where('payment_commission', array('hospital_id' => $hospital_id))->row();

        echo json_encode($data);
    }

    function getHospitalCommissionSettingsByDoctor()
    {
        $id = $this->input->get('id');
        $hospital_id = $this->doctor_model->getDoctorById($id)->hospital_id;
        $data['commission'] = $this->db->get_where('payment_commission', array('hospital_id' => $hospital_id))->row();

        echo json_encode($data);
    }

    public function privacyPolicy()
    {
        $id = $this->input->get('id');
        $data['privacys'] = $this->privacy_model->getPrivacy();
        $data['privacy'] = $this->privacy_model->getPrivacyById($id);
        $data['settings'] = $this->settings_model->getSettings();
        // $this->load->view('home/dashboard', $data);
        $this->load->view('privacy_policy_front', $data);
        // $this->load->view('home/footer');
    }
    function view()
    {
        $data = array();
        $id = $this->input->get('id');
        $data['privacy'] = $this->privacy_model->getPrivacyById($id);
        $this->load->view('home/dashboard');
        $this->load->view('add_new', $data);
        $this->load->view('home/footer');
    }

    function getDoctorCommissionSettingsByDoctor()
    {
        $id = $this->input->get('id');
        $data['commission'] = $this->db->get_where('doctor_commission_setting', array('doctor' => $id))->row();

        echo json_encode($data);
    }

    public function getTeamNamelist()
    {
        $searchTerm = $this->input->post('searchTerm');
        $hospital_id = $this->input->get('id');
        $data = $this->frontend_model->getTeamByAvailablity($searchTerm, $hospital_id);

        echo json_encode($data);
    }

    public function getTeamDetails()
    {
        $id = $this->input->get('id');
        $data['team'] = $this->team_model->getTeamById($id);
        $doctors = explode(",", $data['team']->doctor);
        foreach ($doctors as $key => $value) {
            // $med_explode = explode("***", $med);
            $doctor_name = $this->doctor_model->getDoctorById($value)->name;
            $doctor_implode[] = $value . '****' . $doctor_name;
        }
        $data['doctor_list'] = implode(",", $doctor_implode);
        echo json_encode($data);
    }
    public function getAppointmentDetails()
    {
        $id = $this->input->get('id');
        $data['team'] = $this->team_model->getTeamById($id);
        echo json_encode($data);
    }
    function getBoardDoctorByJason()
    {
        $id = $this->input->get('id');
        $data['doctor'] = $this->doctor_model->getDoctorById($id);
        $data['hospital'] = $this->hospital_model->getHospitalById($data['doctor']->hospital_id);
        $data['hospital_category'] = $this->hospital_model->getHospitalCategoryById($data['hospital']->category);
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

    public function medicalBoardAppointmentLink()
    {
        $data = array();

        $id = $this->input->get('id');
        $data['id'] = $this->input->get('id');
        // $data['hospital'] = $this->hospital_model->getHospitalById($id);
        // $data['categories'] = $this->db->get('hospital_category')->result();
        // $data['hospitals'] = $this->hospital_model->getHospital();
        $data['doctors'] = $this->doctor_model->getDoctor();
        //        $data['doctors'] = $this->doctor_model->getDoctorBySuperadmin();
        // $data['packages'] = $this->package_model->getPackage();
        // $data['slides'] = $this->slide_model->getSlide();
        // $data['services'] = $this->service_model->getService();
        // $data['featureds'] = $this->featured_model->getFeatured();
        // $data['settings1'] = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row();
        // $data['gateway'] = $this->db->get_where('paymentGateway', array('name' => $data['settings1']->payment_gateway, 'hospital_id' => 'superadmin'))->row();
        $data['casetaker_id'] = $this->input->get('casetaker_id');
        $data['onlinecenter_id'] = $this->input->get('onlinecenter_id');
        $data['video'] = $this->db->get_where('tutorial', array('id' => 20))->row();
        // $data['doctor'] = $this->doctor_model->getDoctorById($id);
        $data['hospital'] = $this->hospital_model->getHospitalById($data['id']);
        $data['team'] = $this->team_model->getTeamById($data['id']);

        $data['settingss'] = $this->db->get_where('settings', array('hospital_id' => $data['hospital']->id))->row();
        $data['settings1'] = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row();
        $data['gateway'] = $this->db->get_where('paymentGateway', array('name' => $data['settings1']->payment_gateway, 'hospital_id' => 'superadmin'))->row();
        $this->load->view('board_appointment_link', $data);
    }

    public function getBoardDetails()
    {
        $id = $this->input->get('id');
        $data['response'] = $this->team_model->getTeamById($id);


        echo json_encode($data);
    }
    public function doctorRegistration()
    {
        $data = array();
        $data['categories'] = $this->hospital_model->getHospitalCategory();
        $data['settings1'] = $this->db->get_where('settings', array('hospital_id' => 'superadmin'))->row();
        $this->load->view('doctor_registration_form', $data);
    }

    public function addDoctor()
    {

        $id = $this->input->post('id');


        $hospital_id = $this->input->post('hospital_id');
        $status = $this->input->post('status');
        $name = $this->input->post('name');
        $password = $this->input->post('password');
        $gender = $this->input->post('gender');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $department = $this->input->post('department');
        $profile = $this->input->post('profile');
        $new_visit = $this->input->post('new_visit');

        $chamber_address = $this->input->post('chamber_address');
        $description = $this->input->post('description');

        $registration_issue_institution_name = $this->input->post('registration_issue_institution_name');
        $registration_number = $this->input->post('registration_number');
        $registration_expiry_date = $this->input->post('registration_expiry_date');
        $registration_expiry_datee = strtotime($registration_expiry_date);
        $registration_certificate = $this->input->post('registration_certificate');
        $nid_no = $this->input->post('nid_no');
        $nid_certificate = $this->input->post('nid_certificate');

        $country = $this->input->post('country');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // Validating Name Field
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[1]|max_length[100]|xss_clean');

        // Validating Email Field
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[5]|max_length[100]|xss_clean');
        // Validating Address Field   
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[1]|max_length[500]|xss_clean');
        // Validating Phone Field           
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[1]|max_length[50]|xss_clean');
        // Validating Department Field   
        $this->form_validation->set_rules('department', 'Department', 'trim|min_length[1]|max_length[500]|xss_clean');
        // Validating Phone Field           
        $this->form_validation->set_rules('profile', 'Profile', 'trim|min_length[1]|max_length[5000]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $data['categories'] = $this->hospital_model->getHospitalCategory();
            $this->load->view('doctor_registration_form', $data);
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
                    'password' => $password,
                    'address' => $address,
                    'status' => $status,
                    'gender' => $gender,
                    'hospital_id' => $hospital_id,
                    'phone' => $phone,
                    'department' => $department,
                    'profile' => $profile,
                    'registration_issue_institution_name' => $registration_issue_institution_name,
                    'registration_number' => $registration_number,
                    'registration_expiry_date' => $registration_expiry_datee,
                    'nid_no' => $nid_no,
                    'country' => $country,
                    'chamber_address' => $chamber_address,
                    'description' => $description
                );
            } else {

                $data = array();
                $data = array(
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'address' => $address,
                    'status' => $status,
                    'hospital_id' => $hospital_id,
                    'phone' => $phone,
                    'gender' => $gender,
                    'department' => $department,
                    'profile' => $profile,
                    'registration_issue_institution_name' => $registration_issue_institution_name,
                    'registration_number' => $registration_number,
                    'registration_expiry_date' => $registration_expiry_datee,
                    'nid_no' => $nid_no,
                    'country' => $country,
                    'chamber_address' => $chamber_address,
                    'description' => $description
                );
            }


            $username = $this->input->post('name');

            if ($this->ion_auth->email_check($email)) {
                $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));
                redirect('frontend/doctorRegistration');
            } else {

                $this->doctor_model->insertDoctorRequest($data);

                $inserted_id = $this->db->insert_id();


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
                    $this->doctor_model->updateDoctorRequest($inserted_id, $data4);
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
                    $this->doctor_model->updateDoctorRequest($inserted_id, $data3);
                }



                $this->session->set_flashdata('feedback', lang('request_sent_successfully'));
            }

            // Loading View

            redirect('frontend/doctorRegistration');
        }
    }

    public function onlinecenterRegistration()
    {
        $data = array();
        $this->load->view('onlinecenter_registration_form', $data);
    }

    public function addOnlinecenter()
    {

        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $password = $this->input->post('password');
        $email = $this->input->post('email');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $owner_name = $this->input->post('owner_name');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // Validating Name Field
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[100]|xss_clean');

        // Validating Email Field
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[5]|max_length[100]|xss_clean');
        // Validating Address Field   
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[5]|max_length[500]|xss_clean');
        // Validating Phone Field           
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[5]|max_length[50]|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $data = array();
            $this->load->view('onlinecenter_registration_form', $data);
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
                    'owner_name' => $owner_name,
                    'email' => $email,
                    'password' => $password,
                    'superadmin' => 'superadmin',
                    'address' => $address,
                    'phone' => $phone,
                    'status' => 'Requested'
                );
            } else {

                $data = array();
                $data = array(
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'owner_name' => $owner_name,
                    'superadmin' => 'superadmin',
                    'address' => $address,
                    'phone' => $phone,
                    'status' => 'Requested'
                );
            }


            if ($this->ion_auth->email_check($email)) {
                $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));
                redirect('frontend/onlinecenterRegistration');
            } else {
                $this->onlinecenter_model->insertOnlinecenterRequest($data);
                $inserted_id = $this->db->insert_id();

                $files = $_FILES;
                $cpt = count($_FILES['academic_certificate']['name']);
                for ($i = 0; $i < $cpt; $i++) {
                    $_FILES['academic_certificate']['name'] = $files['academic_certificate']['name'][$i];
                    $_FILES['academic_certificate']['type'] = $files['academic_certificate']['type'][$i];
                    $_FILES['academic_certificate']['tmp_name'] = $files['academic_certificate']['tmp_name'][$i];
                    $_FILES['academic_certificate']['error'] = $files['academic_certificate']['error'][$i];
                    $_FILES['academic_certificate']['size'] = $files['academic_certificate']['size'][$i];

                    $file_name = $_FILES['academic_certificate']['name'];
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
                    $this->upload->do_upload('academic_certificate');
                    $path = $this->upload->data();
                    $academic_certificate[] = "uploads/" . $path['file_name'];
                }

                if ($this->upload->do_upload('academic_certificate')) {
                    $path = $this->upload->data();
                    if (!empty($id)) {
                        $previous_academic_certificate_array = array();
                        $previous_academic_certificate = $this->db->get_where('onlinecenter', array('id' => $id))->row()->academic_certificate;
                        $previous_academic_certificate_array = explode(',', $previous_academic_certificate);
                        $academic_certificate = implode(',', array_merge($academic_certificate, $previous_academic_certificate_array));
                    } else {
                        $academic_certificate = implode(',', $academic_certificate);
                    }
                    $data2 = array();
                    $data2 = array(

                        'academic_certificate' => $academic_certificate,

                    );
                    $this->onlinecenter_model->updateOnlinecenterRequest($inserted_id, $data2);
                }


                $files = $_FILES;
                $cpt = count($_FILES['nid_card']['name']);
                for ($i = 0; $i < $cpt; $i++) {
                    $_FILES['nid_card']['name'] = $files['nid_card']['name'][$i];
                    $_FILES['nid_card']['type'] = $files['nid_card']['type'][$i];
                    $_FILES['nid_card']['tmp_name'] = $files['nid_card']['tmp_name'][$i];
                    $_FILES['nid_card']['error'] = $files['nid_card']['error'][$i];
                    $_FILES['nid_card']['size'] = $files['nid_card']['size'][$i];

                    $file_name = $_FILES['nid_card']['name'];
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
                    $this->upload->do_upload('nid_card');
                    $path = $this->upload->data();
                    $nid_card[] = "uploads/" . $path['file_name'];
                }

                if ($this->upload->do_upload('nid_card')) {
                    $path = $this->upload->data();
                    if (!empty($id)) {
                        $previous_nid_card_array = array();
                        $previous_nid_card = $this->db->get_where('onlinecenter', array('id' => $id))->row()->nid_card;
                        $previous_nid_card_array = explode(',', $previous_nid_card);
                        $nid_card = implode(',', array_merge($nid_card, $previous_nid_card_array));
                    } else {
                        $nid_card = implode(',', $nid_card);
                    }
                    $data3 = array();
                    $data3 = array(

                        'nid_card' => $nid_card,

                    );
                    $this->onlinecenter_model->updateOnlinecenterRequest($inserted_id, $data3);
                }




                $onlinecenter_user_id = $this->db->get_where('onlinecenter', array('email' => $email))->row()->id;
                $id_info = array('ion_user_id' => $ion_user_id);
                $this->onlinecenter_model->updateOnlinecenterRequest($onlinecenter_user_id, $id_info);

                $this->session->set_flashdata('feedback', 'Request Sent Successfully');
            }

            // Loading View
            redirect('frontend/onlinecenterRegistration');
        }
    }


    function createPatient()
    {
        $arr = array();
        $doctor = $this->input->post('doctor');
        $p_phone = $this->input->post('p_phone');
        $p_name = $this->input->post('p_name');
        $p_email = $this->input->post('p_email');
        if (empty($p_email)) {
            $p_email =  $p_name . '-' . $p_phone . rand(1, 100000) . '@hd.com';
        }
        if (!empty($p_name)) {
            $password = $p_name . '-' . rand(1, 100000000);
        }

        // $p_age = $this->input->post('p_age');
        $p_address = $this->input->post('p_address');
        $p_gender = $this->input->post('p_gender');
        $p_birthdate = $this->input->post('p_birthdate');
        //        $patient_id = rand(10000, 1000000);
        $patient_id = $this->input->post('p_id');
        if (empty($p_birthdate)) {
            $years = $this->input->post('years');
            $months = $this->input->post('months');
            $days = $this->input->post('days');
            if (empty($years)) {
                $years = '0';
            }
            if (empty($months)) {
                $months = '0';
            }
            if (empty($days)) {
                $days = '0';
            }
        } else {
            $dateOfBirth = $p_birthdate;
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dateOfBirth), date_create($today));
            $years = $diff->format('%y');
            $months = $diff->format('%m');
            $days = $diff->format('%d');
        }
        if (!empty($p_birthdate)) {
            $bdate = strtotime($p_birthdate);
            $p_birthdate = date('d-m-Y', $bdate);
        } else {
            $p_birthdate = date('d-m-Y', strtotime("-$years years -$months months -$days days"));
        }
        $p_age = $years . '-' . $months . '-' . $days;

        $hospital_id = $this->input->post('hospital_id');
        $onlinecenter_id = $this->input->post('onlinecenter_id');
        $casetaker_id = $this->input->post('casetaker_id');
        $country = $this->input->post('country');


        $data_p = array(
            'patient_id' => $patient_id,
            'superadmin' => $superadmin,
            'hospital_id' => $hospital_id,
            'onlinecenter_id' => $onlinecenter_id,
            'casetaker_id' => $casetaker_id,
            'name' => $p_name,
            'email' => $p_email,
            'phone' => $p_phone,
            'address' => $p_address,
            'sex' => $p_gender,
            'age' => $p_age,
            'birthdate' => $p_birthdate,
            'country' => $country,
            'doctor' => $doctor,
            'add_date' => $patient_add_date,
            'registration_time' => $patient_registration_time,
            'how_added' => 'from_appointment'
        );
        $username = $this->input->post('p_name');
        // Adding New Patient

        if ($this->ion_auth->email_check($p_email)) {
            // $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));
            $errormsg = '<p style="color:red; font-size:30px!important;">'. lang('this_email_address_is_already_registered') . '</p>';
            $arr['message'] = array('message' => $errormsg);

        } else {

            $dfg = 5;
            $this->ion_auth->register($username, $password, $p_email, $dfg);
            $ion_user_id = $this->db->get_where('users', array('email' => $p_email))->row()->id;
            $this->patient_model->insertPatientByOnlinecenter($data_p);
            $inserted_id = $this->db->insert_id();
            $patient_name = $this->db->get_where('patient', array('id' => $inserted_id))->row()->name;
            $patient_id = 10000 + $inserted_id;
            $data3 = array(
                'patient_id' => $patient_id,
            );
            $this->patient_model->insertPatientIdByOnlinecenter($inserted_id, $data3);
            $patient_user_id = $this->db->get_where('patient', array('email' => $p_email))->row()->id;
            $id_info = array('ion_user_id' => $ion_user_id);
            $this->patient_model->updatePatient($patient_user_id, $id_info);

            $this->hospital_model->addHospitalIdToIonUser($ion_user_id, $this->input->post('hospital'));
            $patient = $patient_user_id;
            $added = '<p style="color:green; font-size:30px!important;"> Patient Registration Successfull</p>';
            $arr['message'] = array('message' => $added);
    
            // $arr['option'] = array('title' => lang('added'));
            $arr['option'] = array('patientt' => $inserted_id);
            $arr['optionn'] = array('patientname' => $patient_name);
        }

       


        echo json_encode($arr);
    }

    function medicalHistory()
    {
        $data = array();
        $id = $this->input->get('id');
        $data['redirect_link'] = 'history';
        if ($this->ion_auth->in_group(array('Patient'))) {
            $patient_ion_id = $this->ion_auth->get_user_id();
            $id = $this->patient_model->getPatientByIonUserId($patient_ion_id)->id;
        }


        $patient_hospital_id = $this->patient_model->getPatientById($id)->hospital_id;
        if (!$this->ion_auth->in_group(array('casetaker', 'onlinecenter'))) {
            // if ($patient_hospital_id != $this->session->userdata('hospital_id')) {
            //     redirect('home/permission');
            // }
        }
        //$data['patient'] = $this->patient_model->getPatientByOnlinecenter($id);
        $data['settings'] = $this->settings_model->getSettings();
        $data['gateway'] = $this->finance_model->getGatewayByName($data['settings']->payment_gateway);
        if ($this->ion_auth->in_group(array('casetaker', 'onlinecenter'))) {
            $data['patient'] = $this->patient_model->getPatientByOnlinecenter($id);
            $data['appointments'] = $this->appointment_model->getAppointmentByPatientByOnlinecenter($data['patient']->id);
            $data['labs'] = $this->lab_model->getLabByPatientIdByOnlinecenter($id);
            $data['medical_histories'] = $this->patient_model->getMedicalHistoryByPatientIdByOnlinecenter($id);
            $data['patient_materials'] = $this->patient_model->getPatientMaterialByPatientIdByOnlinecenter($id);
            $data['prescriptions'] = $this->prescription_model->getPrescriptionByPatientIdByOnlinecenter($id);
            $data['beds'] = $this->bed_model->getBedAllotmentsByPatientIdByOnlinecenter($id);
            $data['doctors'] = $this->doctor_model->getDoctorByPatientHospitalId($data['patient']->hospital_id);
        } else {
            $data['patient'] = $this->patient_model->getPatientById($id);
            $data['appointments'] = $this->appointment_model->getAppointmentByPatient($data['patient']->id);
            $data['prescriptions'] = $this->prescription_model->getPrescriptionByPatientId($id);
            $data['labs'] = $this->lab_model->getLabByPatientId($id);
            $data['beds'] = $this->bed_model->getBedAllotmentsByPatientId($id);
            $data['medical_histories'] = $this->patient_model->getMedicalHistoryByPatientId($id);
            $data['patient_materials'] = $this->patient_model->getPatientMaterialByPatientId($id);
            $data['doctors'] = $this->doctor_model->getDoctor();
        }

        $data['patients'] = $this->patient_model->getPatient();


        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
            $data['groups'] = $this->donor_model->getBloodBankByOnlinecenter();
        } else {
            $data['groups'] = $this->donor_model->getBloodBank();
        }
        $this->load->view('dashboard');
        $this->load->view('patient/medical_history', $data);
        $this->load->view('home/footer');
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
        $this->load->view('dashboard', $data);
        $this->load->view('appointment/add_new', $data);
        $this->load->view('home/footer');
    }
}

/* End of file appointment.php */
    /* Location: ./application/modules/appointment/controllers/appointment.php */

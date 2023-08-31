<link href="common/extranal/css/appointment/add_new.css" rel="stylesheet">
<section id="main-content">
    <style>
        .board_doc {
            pointer-events: none;
        }

        .btn-default {
            background-color: #fff;
            color: #000 !important;
            margin-top: 0;
            height: 37px !important;

        }

        td {
            border: 1px solid #ccc;
            width: 75%;
        }

        .td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .select2-container--bgform .select2-selection--multiple .select2-selection__choice {
            clear: both !important;
        }

        @media only screen and (max-width: 600px) {
            .site-min-height {
                min-height: 1500px;
            }

            .inputt {
                padding: 0px;
                width: 65px;
                border: none;
                margin: -5px -5px;
            }

            .apmt-box {
                width: 430px;
                margin-left: -40px;
            }
        }
    </style>
    <section class="wrapper site-min-height">

        <section class="apmt-box panel col-md-12 row">
            <header class="panel-heading">
                <?php
                if (!empty($appointment->id)) {
                    echo lang('edit_appointment');
                } else {
                    echo lang('add_appointment');
                }

                ?>
            </header>

            <div class="panel-body">
                <div class="adv-table editable-table flashmessage">
                    <?php echo validation_errors(); ?>

                </div>
                <form onsubmit="return validateForm()" role="form" id="addAppointmentForm" action="appointment/addNew" class="clearfix row" method="post" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <?php
                        if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                            $casetaker_id = $onlinecenter_id = ' ';
                            $current_user = $this->ion_auth->get_user_id();
                            if ($this->ion_auth->in_group('onlinecenter')) {

                                $onlinecenter_id = $this->db->get_where('onlinecenter', array('ion_user_id' => $current_user))->row()->id;
                            }
                        ?>
                            <?php
                            $current_user = $this->ion_auth->get_user_id();
                            if ($this->ion_auth->in_group('casetaker')) {
                                $casetaker_id = $this->db->get_where('casetaker', array('ion_user_id' => $current_user))->row()->id;
                                $onlinecenter_id = $this->db->get_where('casetaker', array('ion_user_id' => $current_user))->row()->onlinecenter_id;
                            }
                            ?>

                            <div class="form-group col-md-12">
                                <input type="hidden" id="count" value="1">

                                <label for="exampleInputEmail1"> Pathy Selection</label>

                                <div class="hospital_category_div">
                                    <?php
                                    if ($appointment->payment_status == 'paid') {
                                        $hospital_category = $this->db->get_where('hospital_category', array('id' => $hospital->category))->row()->name;
                                    ?>
                                        <input type="text" name="hospital_category" class="form-control" value="<?php echo $hospital_category ?>" disabled="">
                                    <?php } else { ?>
                                        <select class="form-control js-example-basic-single" id="hospital_category" name="hospital_category" value='' required="">

                                            <option value="" disabled selected hidden>
                                                <?php echo lang('select_a_hospital_cateogry'); ?></option>
                                            <?php foreach ($categories as $category) { ?>
                                                <option value="<?php echo $category->id; ?>" <?php
                                                                                                if (!empty($hospital->id)) {
                                                                                                    if ($hospital->category == $category->id) {
                                                                                                        echo 'selected';
                                                                                                    }
                                                                                                }
                                                                                                ?>>
                                                    <?php echo $category->name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group col-md-12">

                                <label for="exampleInputEmail1">Practice Country / Hospital</label>

                                <?php if ($appointment->payment_status == 'paid') { ?>
                                    <div class="">
                                        <input type="text" name="hospital_name" class="form-control" value="<?php echo $hospital->name ?>" disabled="">
                                        <input type="hidden" name="hospital_id" class="form-control" value="<?php echo $hospital->id ?>">
                                    </div>

                                <?php } else { ?>
                                    <div class="hospital_div">
                                        <select class="form-control m-bot15" id="hospitalchoose1" name="hospital_id" value=''>

                                        </select>
                                    </div>
                                <?php } ?>

                            </div>
                        <?php }
                        ?>


                        <?php if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) { ?>
                            <input type="hidden" name="superadmin" value="1">
                        <?php } else { ?>
                            <input type="hidden" name="superadmin" value='<?php
                                                                            if (!empty($appointment->superadmin)) {
                                                                                echo $appointment->superadmin;
                                                                            }
                                                                            ?>'>
                        <?php } ?>
                        <?php if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) { ?>
                            <input type="hidden" id="casetaker_id" name="casetaker_id" value="<?php echo $casetaker_id ?>">
                            <input type="hidden" id="onlinecenter_id" name="onlinecenter_id" value="<?php echo $onlinecenter_id ?>">
                        <?php } ?>
                        <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                            <div class="col-md-12 payment pad_bot">

                                <label for="exampleInputEmail1"> Appointment Type</label>

                                <div class="">
                                    <select class="form-control m-bot15 select_board" name="medical_board_type" id="select_board" value=''>
                                        <option value="Single Doctor" <?php
                                                                        if (!empty($appointment->medical_board_type)) {
                                                                            if ($appointment->medical_board_type == 'Single Doctor') {
                                                                                echo 'selected';
                                                                            }
                                                                        }
                                                                        ?>> Single Doctor Appointment </option>
                                        <option value="Medical Board" <?php
                                                                        if (!empty($appointment->medical_board_type)) {
                                                                            if ($appointment->medical_board_type == 'Medical Board') {
                                                                                echo 'selected';
                                                                            }
                                                                        }
                                                                        ?>> Medical Board Appointment </option>
                                        <option value="Custom Board" <?php
                                                                        if (!empty($appointment->medical_board_type)) {
                                                                            if ($appointment->medical_board_type == 'Custom Board') {
                                                                                echo 'selected';
                                                                            }
                                                                        }
                                                                        ?>> Custom Board Appointment</option>

                                    </select>
                                </div>
                            </div>

                        <?php } ?>



                        <div class="col-md-12 pane doctor_div <?php if (!empty($appointment->id)) {
                                                                    if ($appointment->medical_board_type == 'Medical Board') {
                                                                        echo 'hidden';
                                                                    }
                                                                }  ?>">

                            <label for="exampleInputEmail1" class="doc_title"> <?php echo lang('doctor'); ?></label>
                            <label class="hidden leader_title" for="exampleInputEmail1"> Board Leader Doctor: (Medication and online center will manage)</label>

                            <div class="">

                                <?php if ($this->ion_auth->in_group(array('Doctor'))) {
                                    $current_user = $this->ion_auth->get_user_id();
                                    $doctor_id = $this->db->get_where('doctor', array('ion_user_id' => $current_user))->row()->id;
                                    $doctor_name = $this->db->get_where('doctor', array('ion_user_id' => $current_user))->row()->name;
                                ?>
                                    <span class="form-control m-bot15"><?php echo $doctor_name ?></span>
                                    <input type="hidden" name="doctor" id="adoctors" value="<?php echo $doctor_id ?>">
                                    <!-- <input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctor_id ?>"> -->
                                <?php  } else {  ?>
                                    <select class="form-control m-bot15" id="adoctors" name="doctor" value=''>
                                        <?php if (!empty($appointment)) { ?>
                                            <option value="<?php echo $doctors->id; ?>" selected="selected">
                                                <?php echo $doctors->name; ?> - <?php echo $doctors->id; ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                                <input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctor_id ?>">
                            </div>


                        </div>





                        <div class="col-md-12 panel">

                            <label for="exampleInputEmail1"> </label>

                            <div class="" id="doctor_name">

                            </div>
                        </div>

                        <input type="hidden" id="team_id" value="<?php
                                                                    if (!empty($appointment->team)) {
                                                                        echo $appointment->team;
                                                                    }
                                                                    ?>">
                        <input type="hidden" name="board_leader_id" id="board_leader_id" value="">



                        <div class="<?php if (!empty($appointment->id)) {
                                        if ($appointment->medical_board_type == 'Custom Board') {
                                            echo 'hidden medical_team';
                                        } elseif ($appointment->medical_board_type == 'Medical Board') {
                                            echo 'medical_team';
                                        } elseif ($appointment->medical_board_type == 'Single Doctor') {
                                            echo 'hidden medical_team';
                                        } else {
                                            echo 'medical_team';
                                        }
                                    } else {
                                        echo "hidden medical_team";
                                    } ?>">
                            <div class="col-md-12 panel">

                                <label class=""> Medical Board</label>

                                <div class="">

                                    <select class="form-control m-bot15 team" id="my_select1_team_disabled" name="team" value=''>
                                        <?php if (!empty($appointment->id)) { ?>
                                            <option value="<?php echo $teams->id; ?>" selected="selected">
                                                <?php echo $teams->name; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>

                            <div class="col-md-12 panel">

                                <label for="exampleInputEmail1"> Board Member Doctor:</label>



                                <div class="" style="pointer-events: none;">
                                    <?php if (empty($appointment->id)) { ?>
                                        <select class="form-control m-bot15 board_doc" id="my_select1_disabled" multiple="multiple" name="board_doctor[]" value=''>

                                        </select>
                                    <?php } else { ?>
                                        <select class="form-control js-example-basic-single board_doc" multiple="multiple" id="my_select1_disabled" name="board_doctor[]" value=''>

                                            <option value=""><?php echo lang('select_doctor'); ?></option>
                                            <?php foreach ($b_doctors as $doctor) { ?>
                                                <option value="<?php echo $doctor->id; ?>" <?php
                                                                                            if (!empty($appointment->id)) {
                                                                                                if (!empty($appointment->team)) {
                                                                                                    $board_doctor = explode(',', $appointment->board_doctor);
                                                                                                    foreach ($board_doctor as $key => $value) {
                                                                                                        if ($doctor->id == $value) {
                                                                                                            echo 'selected';
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                            ?>>

                                                    <?php $hospital_details = $this->hospital_model->getHospitalById($doctor->hospital_id);
                                                    $hospital_category = $this->hospital_model->getHospitalCategoryById($hospital_details->category);
                                                    ?>
                                                    <?php echo $doctor->name; ?> / <?php echo $hospital_category->name; ?> /
                                                    <?php echo $hospital_details->name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>

                        <div class="<?php if (!empty($appointment->id)) {
                                        if ($appointment->medical_board_type == 'Medical Board') {
                                            echo 'hidden custom_board';
                                        } elseif ($appointment->medical_board_type == 'Custom Board') {
                                            echo 'custom_board';
                                        } elseif ($appointment->medical_board_type == 'Single Doctor') {
                                            echo 'hidden custom_board';
                                        } else {
                                            echo 'hidden custom_board';
                                        }
                                    } else {
                                        echo 'hidden custom_board';
                                    } ?> ">
                            <div class="col-md-12 panel">

                                <label for="exampleInputEmail1">Board Member Doctor: </label>



                                <div class="">

                                    <select class="form-control js-example-basic-single" multiple="multiple" id="bdoc" name="custom_board_doctor[]" value=''>

                                        <option value=""><?php echo lang('select_doctor'); ?></option>
                                        <?php foreach ($b_doctors as $doctor) {
                                            $price = $this->doctorvisit_model->getDoctorCommissionSettingByDoctorId($doctor->id);
                                        ?>
                                            <option value="<?php echo $doctor->id; ?>" data-price="<?php if (!empty($price->custom_medical_board_visit_charges)) {
                                                                                                        echo $price->custom_medical_board_visit_charges;
                                                                                                    } else {
                                                                                                        echo 0;
                                                                                                    } ?>" <?php
                                                                                                            if (!empty($appointment->id)) {
                                                                                                                if (empty($appointment->team)) {
                                                                                                                    $board_doctor = explode(',', $appointment->board_doctor);
                                                                                                                    foreach ($board_doctor as $key => $value) {
                                                                                                                        if ($doctor->id == $value) {
                                                                                                                            echo 'selected';
                                                                                                                        }
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                            ?>>

                                                <?php $hospital_details = $this->hospital_model->getHospitalById($doctor->hospital_id);
                                                $hospital_category = $this->hospital_model->getHospitalCategoryById($hospital_details->category);
                                                ?>
                                                <?php echo $doctor->name; ?> / <?php echo $hospital_category->name; ?> /
                                                <?php echo $hospital_details->name; ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                </div>

                            </div>
                        </div>
                        <div class="pat_div">
                            <?php if ($this->ion_auth->in_group(array('Patient'))) {
                                $patient_ion_id = $this->ion_auth->get_user_id();
                                $patient_info = $this->db->get_where('patient', array('ion_user_id' => $patient_ion_id))->row();
                            ?>
                                <div class="col-md-12 payment pad_bot " style="margin-bottom:10px;">

                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                        <?php echo lang('name'); ?></label>

                                    <div class="">
                                        <span class="form-control"> <?php echo $patient_info->name; ?></span>
                                    </div>
                                </div>

                                <input type="hidden" name="patient" value="<?php echo $patient_info->id ?>">
                            <?php } else { ?>
                                <div class="col-md-12 panel">
                                    <?php if (!empty($id)) { ?>

                                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>

                                        <span class="form-control"> <?php echo $patient->name; ?></span>
                                        <input type="hidden" name="patient" id="exampleInputEmail1" value='<?php echo $patient->id; ?>' placeholder="">


                                    <?php } else { ?>
                                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>

                                        <div class="">
                                            <select class="form-control m-bot15  pos_select" id="pos_select" name="patient" value=''>
                                                <?php if (!empty($appointment)) { ?>
                                                    <option value="<?php echo $patients->id; ?>" selected="selected">
                                                        <?php echo $patients->name; ?> - <?php echo $patients->patient_id; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="pos_client clearfix">
                                <div class="col-md-12 payment pad_bot ">

                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                        <?php echo lang('name'); ?></label>

                                    <div class="">
                                        <input type="text" class="form-control pay_in" id="p_name" name="p_name" value='<?php
                                                                                                                        if (!empty($payment->p_name)) {
                                                                                                                            echo $payment->p_name;
                                                                                                                        }
                                                                                                                        ?>' placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-12 payment pad_bot ">

                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                        <?php echo lang('email'); ?></label>

                                    <div class="">
                                        <input type="text" class="form-control pay_in" id="p_email" name="p_email" value='<?php
                                                                                                                            if (!empty($payment->p_email)) {
                                                                                                                                echo $payment->p_email;
                                                                                                                            }
                                                                                                                            ?>' placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-12 payment pad_bot ">

                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                        <?php echo lang('phone'); ?></label>

                                    <div class="">
                                        <input type="text" class="form-control pay_in" id="p_phone" name="p_phone" value='<?php
                                                                                                                            if (!empty($payment->p_phone)) {
                                                                                                                                echo $payment->p_phone;
                                                                                                                            }
                                                                                                                            ?>' placeholder="">
                                    </div>
                                </div>


                                <div class="col-md-12 payment pad_bot ">

                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                        <?php echo lang('address'); ?></label>

                                    <div class="">
                                        <input type="text" class="form-control pay_in" id="p_address" name="p_address" value='<?php
                                                                                                                                if (!empty($payment->p_address)) {
                                                                                                                                    echo $payment->p_address;
                                                                                                                                }
                                                                                                                                ?>' placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-12 payment pad_bot">

                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                        <?php echo lang('birth_date'); ?></label>

                                    <div class="">
                                        <input type="text" class="form-control default-date-picker" autocomplete="off" id="p_birthdate" name="p_birthdate" value='<?php
                                                                                                                                                                    if (!empty($payment->p_birthdate)) {
                                                                                                                                                                        echo $payment->p_birthdate;
                                                                                                                                                                    }
                                                                                                                                                                    ?>' placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-12">

                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                        <?php echo lang('age'); ?></label>

                                    <div class="">
                                        <div class="input-group m-bot15">

                                            <input type="number" class="form-control" id="years" name="years" value='' placeholder="years">
                                            <span class="input-group-addon">Y</span>
                                            <input type="number" class="form-control input-group-addon" id="months" name="months" value='0' placeholder="<?php echo lang('months'); ?>">
                                            <span class="input-group-addon">M</span>
                                            <input type="number" class="form-control input-group-addon" id="days" name="days" value='0' placeholder="<?php echo lang('days'); ?>">
                                            <span class="input-group-addon">D</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 payment pad_bot ">

                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                        <?php echo lang('gender'); ?></label>

                                    <div class="">
                                        <select class="form-control m-bot15" name="p_gender" id="p_gender" value=''>

                                            <option value="Male" <?php
                                                                    if (!empty($patient->sex)) {
                                                                        if ($patient->sex == 'Male') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>> Male </option>
                                            <option value="Female" <?php
                                                                    if (!empty($patient->sex)) {
                                                                        if ($patient->sex == 'Female') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>> Female </option>
                                            <option value="Others" <?php
                                                                    if (!empty($patient->sex)) {
                                                                        if ($patient->sex == 'Others') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>> Others </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 payment pad_bot " style="margin-bottom: 10px;">

                                    <label for="exampleInputEmail1"> <?php echo lang('country'); ?></label>

                                    <div class="">
                                        <select class="form-control selectpicker countrypicker m-bot15" data-live-search="true" id="country" data-flag="true" <?php if (!empty($patient->id)) { ?>data-default="<?php echo $patient->country; ?>" <?php } else { ?> data-default="United States" <?php } ?> name="country"></select>
                                    </div>
                                </div>

                                <div class="col-md-12" style="padding:0px; margin:15px;">
                                    <button type="button" id="create_patient" class="btn btn-info btn-group pull-center" style="margin-left: 0% !important;"> Create Patient Account</button>
                                </div>
                            </div>
                        </div>

                        <div class="patid_div">

                            <div class="col-md-12 panel">
                                <!-- <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> </label> -->
                                <input type="hidden" class="form-control" name="patientt" value="" id="patientt">
                                <span class="form-control" id="patientname"></span>
                            </div>
                        </div>
                        <span id="add_message"></span>
                        <div class="col-md-12 panel">

                            <label for="exampleInputEmail1">Appointment <?php echo lang('date'); ?></label>

                            <div class="">
                                <input type="text" class="form-control" id="date" name="date" id="exampleInputEmail1" value='<?php
                                                                                                                                if (!empty($appointment->date)) {
                                                                                                                                    echo date('d-m-Y', $appointment->date);
                                                                                                                                } else {
                                                                                                                                    echo date('d-m-Y');
                                                                                                                                }
                                                                                                                                ?>' placeholder="" onkeypress="return false;" autocomplete="off" required="">
                            </div>
                        </div>

                        <div class="col-md-12 panel">

                            <label class=""><?php echo lang('available_slots'); ?></label>

                            <div class="">
                                <select class="form-control m-bot15" name="time_slot" id="aslots" value=''>

                                </select>
                            </div>
                        </div>
                        <?php if ($appointment->payment_status != 'paid') { ?>
                            <!-- <div class="col-md-12 panel">
                                <div class="col-md-3 payment_label">
                                    <label class=""><?php echo lang('visit'); ?> <?php echo lang('description'); ?></label>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control js-example-basic-single" name="visit_description" id="visit_description" value='' required="">
                                        <option value="" disabled selected hidden><?php echo lang('select'); ?> <?php echo lang('visit'); ?> <?php echo lang('type'); ?></option>
                                        <option value="new_visit" <?php
                                                                    if ($appointment->visit_description == 'new_visit') {
                                                                        echo 'selected';
                                                                    }
                                                                    ?>><?php echo lang('new_visit'); ?></option>
                                        <option value="old_visit" <?php
                                                                    if ($appointment->visit_description == 'old_visit') {
                                                                        echo 'selected';
                                                                    }
                                                                    ?>><?php echo lang('old_visit'); ?></option>
                                        <option value="new_visit_with_medicine" <?php
                                                                                if ($appointment->visit_description == 'new_visit_with_medicine') {
                                                                                    echo 'selected';
                                                                                }
                                                                                ?>><?php echo lang('new_visit_with_medicine'); ?></option>
                                        <option value="old_visit_with_medicine" <?php
                                                                                if ($appointment->visit_description == 'old_visit_with_medicine') {
                                                                                    echo 'selected';
                                                                                }
                                                                                ?>><?php echo lang('old_visit_with_medicine'); ?></option>
                                    </select>
                                </div>
                            </div> -->
                        <?php } ?>

                        <div class="col-md-12 panel">

                            <label for="exampleInputEmail1"> <?php echo lang('currency'); ?> </label>

                            <div class="">
                                <select class="form-control m-bot15" id="currency" name="currency" value=''>

                                    <option value="BDT" <?php
                                                        if (!empty($appointment->currency)) {
                                                            if ($appointment->currency == 'BDT') {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>> <?php echo lang('taka'); ?> </option>
                                    <option value="INR" <?php
                                                        if (!empty($appointment->currency)) {
                                                            if ($appointment->currency == 'INR') {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>> <?php echo lang('rupee'); ?> </option>

                                    <option value="USD" <?php
                                                        if (!empty($appointment->currency)) {
                                                            if ($appointment->currency == 'USD') {
                                                                echo 'selected';
                                                            }
                                                        }
                                                        ?>> <?php echo lang('dollar'); ?> </option>


                                </select>
                            </div>
                        </div>
                        <div class="visitt">
                            <div class="col-md-12 panel">

                                <label class=""><?php echo lang('visit'); ?> <?php echo lang('description'); ?></label>

                                <div class="">
                                    <select class="form-control m-bot15" name="visit_description" style="<?php if ($appointment->payment_status == 'paid') {
                                                                                                                echo ' pointer-events: none;';
                                                                                                            } ?>" id="visit_description" value=''>
                                        <?php
                                        if (!empty($appointment->id)) {
                                        ?>
                                            <option value=""><?php echo lang('select'); ?></option>
                                            <?php
                                            foreach ($visits as $visit) {
                                            ?>
                                                <option value="<?php echo $visit->id; ?>" <?php
                                                                                            if ($visit->id == $appointment->visit_description) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                            ?>>
                                                    <?php echo $visit->visit_description ?> </option>
                                        <?php }
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" value="<?php echo $appointment->visit_description; ?>" id="visit_description">
                                </div>
                            </div>
                            <input type="hidden" class="visit_id" name="visit_id" id="visit_id" value="">


                            <div class="col-md-12 panel">

                                <label class=""><?php echo lang('visit_type'); ?></label>

                                <div class="" id="visiting_place_list">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 panel">

                            <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>

                            <div class="">
                                <input type="text" class="form-control" name="remarks" id="exampleInputEmail1" value='<?php
                                                                                                                        if (!empty($appointment->remarks)) {
                                                                                                                            echo $appointment->remarks;
                                                                                                                        }
                                                                                                                        ?>' placeholder="">
                            </div>
                        </div>


                        <div class="col-md-12 panel">

                            <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?>
                                <?php echo lang('status'); ?></label>

                            <div class="">
                                <select class="form-control m-bot15" name="status" value=''>
                                    <?php if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) { ?>
                                        <option value="Confirmed" <?php
                                                                    if (!empty($appointment->status)) {
                                                                        if ($appointment->status == 'Confirmed') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>> <?php echo lang('confirmed'); ?> </option>
                                        <option value="Pending Confirmation" <?php
                                                                                if (!empty($appointment->status)) {
                                                                                    if ($appointment->status == 'Pending Confirmation') {
                                                                                        echo 'selected';
                                                                                    }
                                                                                }
                                                                                ?>>
                                            <?php echo lang('pending_confirmation'); ?> </option>


                                        <option value="Treated" <?php
                                                                if (!empty($appointment->status)) {
                                                                    if ($appointment->status == 'Treated') {
                                                                        echo 'selected';
                                                                    }
                                                                }
                                                                ?>> <?php echo lang('treated'); ?> </option>
                                        <option value="Cancelled" <?php
                                                                    if (!empty($appointment->status)) {
                                                                        if ($appointment->status == 'Cancelled') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>> <?php echo lang('cancelled'); ?> </option>
                                    <?php } else { ?>
                                        <option value="Pending Confirmation" <?php
                                                                                if (!empty($appointment->status)) {
                                                                                    if ($appointment->status == 'Pending Confirmation') {
                                                                                        echo 'selected';
                                                                                    }
                                                                                }
                                                                                ?>>
                                            <?php echo lang('pending_confirmation'); ?> </option>
                                    <?php } ?>
                                    <!-- <option value="Requested" <?php
                                                                    if (!empty($appointment->status)) {
                                                                        if ($appointment->status == 'Requested') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>> <?php echo lang('requested'); ?> </option> -->

                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="id" id="appointment_id" value='<?php
                                                                                    if (!empty($appointment->id)) {
                                                                                        echo $appointment->id;
                                                                                    }
                                                                                    ?>'>

                        <input type="hidden" name="redirectlink" value="<?php
                                                                        if (!empty($appointment->id)) {
                                                                            echo $redirectlink;
                                                                        } else {
                                                                            if (!empty($redirectlink)) {
                                                                                echo $redirectlink;
                                                                            } else {
                                                                                echo '10';
                                                                            }
                                                                        }
                                                                        ?>">
                    </div>
                    <!-- <input type="text" name="test" id="test" value=""> -->
                    <input type="hidden" name="doctor_amount" id="doctor_amount" value="">
                    <input type="hidden" name="total_charges" id="visit_charges" value="">
                    <input type="hidden" name="additional_fee" id="total_fee" value="">
                    <?php if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker', 'Doctor'))) { ?>
                        <input type="hidden" name="casetaker_fee" id="casetaker_fee" value="">
                        <input type="hidden" name="onlinecenter_fee" id="onlinecenter_fee" value="">
                    <?php } ?>
                    <input type="hidden" name="hospital_fee" id="hospital_fee" value="">
                    <input type="hidden" name="developer_fee" id="developer_fee" value="">
                    <input type="hidden" name="superadmin_fee" id="superadmin_fee" value="">
                    <input type="hidden" name="medicine_fee" id="medicine_fee" value="">
                    <input type="hidden" name="courier_fee" id="courier_fee" value="">

                    <input type="hidden" name="custom_doc_fee" id="custom_doc_fee" value="0">

                    <input type="hidden" name="charge_without_courier" id="charge_without_courier" value="">
                    <input type="hidden" name="hidden_total_charges" id="hidden_total_charges" value="">

                    <div class="col-md-12 clearfix visit_div">
                        <div class="form-group col-md-12">

                            <div class="form-group pay_for_courier" style="margin-top: 20px; margin-bottom: 0px;">
                                <!--  <input type="checkbox" checked id="pay_for_courier" name="pay_for_courier" value="pay_for_courier">
                            <label for=""> <?php echo lang('courier'); ?></label><br>
                        </div>
                        <div class="form-group col-md-12 visit_description_div">
                            <label for="exampleInputEmail1"><?php echo lang('visit'); ?> <?php echo lang('charges'); ?></label>
                            <input type="number" class="form-control" name="visit_charges" id="total_charges" value="<?php
                                                                                                                        if (!empty($appointment->id)) {
                                                                                                                            echo $appointment->visit_charges;
                                                                                                                        }
                                                                                                                        ?>" placeholder="" readonly="">
                        </div> -->
                                <table style="width: 100%;">
                                    <tr>
                                        <td>
                                            <div class="col-md-12 td"> <label for="">Subtotal</label> </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12 td" id="">
                                                <input style="border:none" type="number" class="form-control inputt" name="appointment_subtotal" id="new_subtotal_fee" value='<?php
                                                                                                                                                                                if (!empty($appointment->id)) {
                                                                                                                                                                                    echo $appointment->appointment_subtotal;
                                                                                                                                                                                }
                                                                                                                                                                                ?>' placeholder="" readonly="">
                                            </div><input type="hidden" id="subtotal_fee" name="subtotal_fee">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-12 td"><label for="">Payment gateway fee</label></div>
                                        </td>
                                        <td>
                                             <input type="hidden" name="gateway_amount" id="gateway_amount" value="">
                                            <inp class="col-md-12 td" id="gateway_fee" style="margin-left:15px;"><?php
                                                                                                                    if (!empty($appointment->id)) {
                                                                                                                        if (!empty($appointment->appointment_subtotal)) {
                                                                                                                            $gateway_fee = $appointment->appointment_subtotal * 2.5 / 100;
                                                                                                                            echo $gateway_fee;
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?>
                                            </inp>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-1"> <input type="checkbox" <?php if (!empty($appointment->id)) {
                                                                                                if (!empty($appointment->courier_fee)) {
                                                                                                    echo 'checked';
                                                                                                } else {
                                                                                                    echo '';
                                                                                                }
                                                                                            } else {
                                                                                                echo 'checked';
                                                                                            } ?> id="pay_for_courier" name="pay_for_courier" value="pay_for_courier">
                                            </div>
                                            <div class="col-md-11"> <label for="">
                                                    <?php echo lang('courier'); ?></label><br></div>
                                        </td>
                                        <td>
                                            <div class="col-md-12 td" id="shipping_fee" style="margin-left:15px;"><?php
                                                                                                                    if (!empty($appointment->id)) {
                                                                                                                        if (!empty($appointment->courier_fee)) {

                                                                                                                            echo $appointment->courier_fee;
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-12 td"><label>আপনি যদি কুরিয়ারে ঔষধ না নেন তাহলে টিক চিহ্ন
                                                    উঠিয়ে দিন </label> <label>If you do not take medicine in courier then
                                                    remove the tick mark</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="col-md-12 td"><label for="">Total</label></div>
                                        </td>
                                        <td>
                                            <div class="col-md-12 td"><input style="border:none" type="number" class="form-control inputt" name="visit_charges" id="total_charges" value='<?php
                                                                                                                                                                                            if (!empty($appointment->id)) {
                                                                                                                                                                                                echo $appointment->visit_charges;
                                                                                                                                                                                            }
                                                                                                                                                                                            ?>' placeholder="" readonly=""></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-12 td"><label for=""> Advance Payment (Minimum 200)</label></div>
                                        </td>
                                        <td>
                                            <div class="col-md-12 td">
                                                <input style="border:none" type="text" class="form-control inputt" name="deposited_amount" id="deposited_amount" value='<?php
                                                                                                                                                                        if (!empty($appointment->id)) {
                                                                                                                                                                            echo $this->finance_model->getDepositAmountByPaymentId($appointment->payment_id);
                                                                                                                                                                        }
                                                                                                                                                                        ?>' placeholder="200">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-12 td"><label for=""> Due Amount</label></div>
                                        </td>
                                        <td>
                                            <div class="col-md-12 td">
                                                <input style="border:none" type="number" class="form-control inputt" name="due_amount" id="due_amount" value='<?php
                                                                                                                                                                if (!empty($appointment->id)) {
                                                                                                                                                                    echo $appointment->visit_charges - $this->finance_model->getDepositAmountByPaymentId($appointment->payment_id);
                                                                                                                                                                }
                                                                                                                                                                ?>' placeholder="" readonly>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-1"> <input type="checkbox" checked id="terms" name="terms" value="terms" required></div>
                                            <div class="col-md-11"> <label for=""> I have read and agree to the Appointment
                                                    <a href="frontend/privacyPolicy?id=1" target="_blank">terms and
                                                        conditions</a> </label><br></div>
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php if (!$this->ion_auth->in_group(array('Nurse'))) { ?>
                            <?php if ($appointment->payment_status == 'paid') { ?>
                                <div class="form-group col-md-12 visit_description_div">
                                    <label for="exampleInputEmail1"><?php echo lang('payment'); ?>
                                        <?php echo lang('status'); ?></label>
                                    <input type="text" class="form-control" name="" id="" value='<?php echo lang('paid'); ?>' placeholder="" readonly="">
                                </div>
                                <div class="form-group  payment  right-six col-md-12">
                                    <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right">
                                        <?php echo lang('submit'); ?></button>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-12 clearfix">

                                    <div class="pay_now_div">
                                        <input type="checkbox" checked id="pay_now_appointment" name="pay_now_appointment" value="pay_now_appointment">
                                        <label for=""> <?php echo lang('pay_now'); ?></label><br>
                                        <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                                            <span class="text_paynow"><?php echo lang('if_pay_now_checked_please_select_status_to_confirmed') ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-12 clearfix">

                                    <div class="payment_label deposit_type">
                                        <label for="exampleInputEmail1"><?php echo lang('deposit_type'); ?></label>

                                        <div class="">
                                            <select class="form-control m-bot15 js-example-basic-single selecttype" id="selecttype" name="deposit_type" value=''>
                                            <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                                    <option value="Cash"> <?php echo lang('cash'); ?> </option>
                                                <?php } ?>
                                                <option value="Aamarpay"> Dollar & Taka: Card/Mobile Banking </option>
                                                <!-- <option value="Card"> <?php echo lang('card'); ?> </option> -->
                                                <option value="Paytm"> Indian Rupee </option>
                                                

                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <?php
                                    if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                                        $payment_gateway = $settings->payment_gateway;
                                    }
                                    ?>

                                    <div class="paytm">
                                        <div class="col-md-12 payment pad_bot">
                                            <label for="exampleInputEmail1">
                                                <p style="font-size: 15px;">Total amount you need to Payments made to this QR,
                                                    or on <strong>9733263889</strong> number.
                                                    Then fill the form below and submit.</p>
                                            </label>

                                        </div>
                                        <div class="col-md-12 payment pad_bot">
                                            <div class="payment pad_bot col-md-4">
                                                <a class="example-image-link" href="uploads/Paytm.jpg" data-lightbox="example-1">
                                                    <img class="example-image" src="uploads/Paytm.jpg" alt="image-1" height="90" width="90" /></a>

                                            </div>
                                            <div class="payment pad_bot col-md-4">
                                                <a class="example-image-link" href="uploads/Gpay.jpg" data-lightbox="example-1">
                                                    <img class="example-image" src="uploads/Gpay.jpg" alt="image-1" height="90" width="90" /></a>

                                            </div>
                                            <div class="payment pad_bot col-md-4">
                                                <a class="example-image-link" href="uploads/PhonePe.jpg" data-lightbox="example-1">
                                                    <img class="example-image" src="uploads/PhonePe.jpg" alt="image-1" height="90" width="90" /></a>

                                            </div>
                                        </div>
                                        <div class="col-md-12 payment pad_bot">
                                            <label for="exampleInputEmail1"> Account number</label>
                                            <input type="text" id="cardholder" class="form-control pay_in" name="account_number" value='' placeholder="">
                                        </div>
                                        <div class="col-md-12 payment pad_bot">
                                            <label for="exampleInputEmail1"> Last 6 digits of Transaction ID</label>
                                            <input type="text" id="cardholder" class="form-control pay_in" name="transaction_id" value='' placeholder="">
                                        </div>

                                    </div>

                                    <div class="card">

                                        <hr>

                                        <div class="col-md-12 payment pad_bot">
                                            <label for="exampleInputEmail1"> <?php echo lang('accepted'); ?>
                                                <?php echo lang('cards'); ?></label>
                                            <div class="payment pad_bot">
                                                <img src="uploads/card.png" width="100%">
                                            </div>
                                        </div>



                                        <?php
                                        if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                                            if ($payment_gateway == 'PayPal') {
                                        ?>
                                                <div class="col-md-12 payment pad_bot">
                                                    <label for="exampleInputEmail1"> <?php echo lang('card'); ?>
                                                        <?php echo lang('type'); ?></label>
                                                    <select class="form-control m-bot15" name="card_type" value=''>

                                                        <option value="Mastercard"> <?php echo lang('mastercard'); ?> </option>
                                                        <option value="Visa"> <?php echo lang('visa'); ?> </option>
                                                        <option value="American Express"> <?php echo lang('american_express'); ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                            <?php if ($payment_gateway == 'PayPal') {
                                            ?>
                                                <div class="col-md-12 payment pad_bot">
                                                    <label for="exampleInputEmail1"> <?php echo lang('cardholder'); ?>
                                                        <?php echo lang('name'); ?></label>
                                                    <input type="text" id="cardholder" class="form-control pay_in" name="cardholder" value='' placeholder="">
                                                </div>
                                            <?php } ?>
                                            <?php if ($payment_gateway != 'Pay U Money' && $payment_gateway != 'Paystack') { ?>
                                                <div class="col-md-12 payment pad_bot">
                                                    <label for="exampleInputEmail1"> <?php echo lang('card'); ?>
                                                        <?php echo lang('number'); ?></label>
                                                    <input type="text" id="card" class="form-control pay_in" name="card_number" value='' placeholder="">
                                                </div>



                                                <div class="col-md-8 payment pad_bot">
                                                    <label for="exampleInputEmail1"> <?php echo lang('expire'); ?>
                                                        <?php echo lang('date'); ?></label>
                                                    <input type="text" class="form-control pay_in" id="expire" data-date="" data-date-format="MM YY" placeholder="Expiry (MM/YY)" name="expire_date" maxlength="7" aria-describedby="basic-addon1" value='' placeholder="">
                                                </div>
                                                <div class="col-md-4 payment pad_bot">
                                                    <label for="exampleInputEmail1"> <?php echo lang('cvv'); ?> </label>
                                                    <input type="text" class="form-control pay_in" id="cvv" maxlength="3" name="cvv" value='' placeholder="">
                                                </div>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <input type="hidden" name="stripe_publish" value="">


                                            <div class="col-md-12 payment pad_bot card_type">
                                                <label for="exampleInputEmail1"> <?php echo lang('card'); ?>
                                                    <?php echo lang('type'); ?></label>
                                                <select class="form-control m-bot15" name="card_type" value=''>

                                                    <option value="Mastercard"> <?php echo lang('mastercard'); ?> </option>
                                                    <option value="Visa"> <?php echo lang('visa'); ?> </option>
                                                    <option value="American Express"> <?php echo lang('american_express'); ?>
                                                    </option>
                                                </select>
                                            </div>


                                            <div class="col-md-12 payment pad_bot cardholder_name">
                                                <label for="exampleInputEmail1"> <?php echo lang('cardholder'); ?>
                                                    <?php echo lang('name'); ?></label>
                                                <input type="text" id="cardholder" class="form-control pay_in" name="cardholder" value='' placeholder="">
                                            </div>


                                            <div class="col-md-12 payment pad_bot cardNumber">
                                                <label for="exampleInputEmail1"> <?php echo lang('card'); ?>
                                                    <?php echo lang('number'); ?></label>
                                                <input type="text" id="card" class="form-control pay_in" name="card_number" value='' placeholder="">
                                            </div>



                                            <div class="col-md-8 payment pad_bot expireNumber">
                                                <label for="exampleInputEmail1"> <?php echo lang('expire'); ?>
                                                    <?php echo lang('date'); ?></label>
                                                <input type="text" class="form-control pay_in" id="expire" data-date="" data-date-format="MM YY" placeholder="Expiry (MM/YY)" name="expire_date" maxlength="7" aria-describedby="basic-addon1" value='' placeholder="">
                                            </div>
                                            <div class="col-md-4 payment pad_bot cvvNumber">
                                                <label for="exampleInputEmail1"> <?php echo lang('cvv'); ?> </label>
                                                <input type="text" class="form-control pay_in" id="cvv" maxlength="3" name="cvv" value='' placeholder="">
                                            </div>
                                        <?php } ?>
                                    </div>


                                </div>
                                <div class="col-md-12 panel">
                                    <div class="col-md-3 payment_label">
                                    </div>
                                    <div class="col-md-9">

                                        <div class="form-group cashsubmit payment  right-six col-md-12">
                                            <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right"> <?php echo lang('submit'); ?></button>
                                        </div>

                                        <div class="form-group cardsubmit  right-six col-md-12 hidden">
                                            <button type="submit" name="pay_now" id="submit-btn" class="btn btn-info row pull-right" <?php
                                                                                                                                        if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                                                                                                                                            if ($settings->payment_gateway == 'Stripe') {
                                                                                                                                        ?>onClick="stripePay(event);" <?php
                                                                                                                                                                    }
                                                                                                                                                                }
                                                                                                                                                                        ?>> <?php echo lang('submit'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="form-group  payment  right-six col-md-12">
                                <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right">
                                    <?php echo lang('submit'); ?></button>
                            </div>
                        <?php } ?>
                    </div>
                </form>
            </div>

        </section>
        <!-- page end-->
    </section>
</section>



<script src="common/js/codearistos.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<?php
if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
    if (!empty($gateway->publish)) {
        $gateway_stripe = $gateway->publish;
    } else {
        $gateway_stripe = '';
    }
?>
    <script type="text/javascript">
        var publish = "<?php echo $gateway_stripe; ?>";
    </script>
    <script type="text/javascript">
        var case_taker_online = "<?php echo 'no'; ?>";
    </script>
<?php } else { ?>
    <script type="text/javascript">
        var case_taker_online = "<?php echo 'yes'; ?>";
    </script>
<?php }
?>
<?php
if (!empty($appointment->id)) {
    if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
?>
        <script type="text/javascript">
            var hospital = "<?php echo $hospital->id; ?>";
        </script>
        <script type="text/javascript">
            var iid = "<?php echo $appointment->id; ?>";
        </script>
    <?php } ?>
    <script src="common/extranal/js/appointment/edit_appointment.js"></script>
    <!-- <script src="common/extranal/js/appointment/appointment_select2.js"></script> -->
    <?php } else {
    if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) { ?>

        <script type="text/javascript">
            var hospital = " ";
        </script>

    <?php } ?>
    <!--<script src="common/extranal/js/appointment/add_new.js"></script>-->

<?php } ?>


<script type="text/javascript">
    var select_doctor = "<?php echo lang('select_doctor'); ?>";
</script>
<script type="text/javascript">
    var select_patient = "<?php echo lang('select_patient'); ?>";
    var select_team = "<?php echo lang('select_team'); ?>";
</script>
<script src="common/extranal/js/appointment/appointment_select2.js"></script>
<script>
    $(document).ready(function() {

        var appointment_id = $("#appointment_id").val();
        if (appointment_id == '') {


            var onlinecenter_id = $("#onlinecenter_id").val();
            var casetaker_id = $("#casetaker_id").val();
            var doctor_id = $('#doctor_id').val();
            var currency = $("#currency").val();
            $("#new_subtotal_fee").empty();
            $("#gateway_fee").empty();
            $("#shipping_fee").empty();

            $.ajax({
                url: "appointment/getDoctorCommissionSettings?id=" + doctor_id,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (currency == "BDT") {
                        var casetaker_fee = response.commission.casetaker_fee;
                        var onlinecenter_fee = response.commission.onlinecenter_fee;
                        var developer_fee = response.commission.developer_fee;
                        var hospital_fee = response.commission.current_hospital;
                        var superadmin_fee = response.commission.superadmin_fee;
                        var medicine_fee = response.commission.medicine_fee;
                        var courier_fee = response.commission.courier_fee;
                    }
                    if (currency == "INR") {
                        var casetaker_fee = response.commission.casetaker_fee_rupee;
                        var onlinecenter_fee = response.commission.onlinecenter_fee_rupee;
                        var developer_fee = response.commission.developer_fee_rupee;
                        var hospital_fee = response.commission.current_hospital_rupee;
                        var superadmin_fee = response.commission.superadmin_fee_rupee;
                        var medicine_fee = response.commission.medicine_fee_rupee;
                        var courier_fee = response.commission.courier_fee_rupee;
                    }
                    if (currency == "USD") {
                        var casetaker_fee = response.commission.casetaker_fee_dollar;
                        var onlinecenter_fee = response.commission.onlinecenter_fee_dollar;
                        var developer_fee = response.commission.developer_fee_dollar;
                        var hospital_fee = response.commission.current_hospital_dollar;
                        var superadmin_fee = response.commission.superadmin_fee_dollar;
                        var medicine_fee = response.commission.medicine_fee_dollar;
                        var courier_fee = response.commission.courier_fee_dollar;
                    }
                    if ($("#pay_for_courier").prop("checked") == true) {
                        var courier = courier_fee;
                    } else {
                        var courier = 0;
                    }
                    // if (doctor_id.trim() != "") {
                    //   var total_fee =
                    //     parseFloat(casetaker_fee) +
                    //     parseFloat(onlinecenter_fee) +
                    //     parseFloat(developer_fee) +
                    //     parseFloat(hospital_fee) +
                    //     parseFloat(superadmin_fee) +
                    //     parseFloat(medicine_fee) +
                    //     parseFloat(courier);
                    // } else {
                    //   var total_fee =
                    //     parseFloat(casetaker_fee) +
                    //     parseFloat(onlinecenter_fee) +
                    //     parseFloat(developer_fee) +
                    //     parseFloat(hospital_fee) +
                    //     parseFloat(superadmin_fee) +
                    //     parseFloat(medicine_fee) +
                    //     parseFloat(courier);
                    // }
                    var total_fee_without_courier =
                        parseFloat(casetaker_fee) +
                        parseFloat(onlinecenter_fee) +
                        parseFloat(developer_fee) +
                        parseFloat(hospital_fee) +
                        parseFloat(superadmin_fee) +
                        parseFloat(medicine_fee);
                    var gateway_fee = (total_fee_without_courier * 2.5) / 100;
                    var total_fee =
                        parseFloat(total_fee_without_courier) + parseFloat(courier);

                    $("#casetaker_fee").val(casetaker_fee).end();
                    $("#onlinecenter_fee").val(onlinecenter_fee).end();
                    $("#developer_fee").val(developer_fee).end();
                    $("#hospital_fee").val(hospital_fee).end();
                    $("#superadmin_fee").val(superadmin_fee).end();
                    $("#medicine_fee").val(medicine_fee).end();
                    $("#courier_fee").val(courier_fee).end();
                    $("#total_fee").val(total_fee).end();
                    $("#shipping_fee").append(courier_fee).end();
                    $("#new_subtotal_fee").val(total_fee_without_courier).end();
                    $("#gateway_fee").append(gateway_fee).end();
                    $("#subtotal_fee").val(total_fee_without_courier).end();
                    $("#total_charges").empty();
                },
            });
        }
    });
    $(document).ready(function() {
        "use strict";

        var appointment_id = $("#appointment_id").val();
        if (appointment_id == '') {
            var iid = $('#date').val();
            var doctorr = $('#doctor_id').val();
            $('#aslots').find('option').remove();

            $.ajax({
                url: 'schedule/getAvailableSlotByDoctorByDateByJason?date=' + iid + '&doctor=' +
                    doctorr,
                method: 'GET',
                data: '',
                dataType: 'json',
                success: function(response) {
                    "use strict";
                    $("#doctor_id").val(doctorr).end();
                    var slots = response.aslots;
                    $.each(slots, function(key, value) {
                        "use strict";
                        $('#aslots').append($('<option>').text(value).val(value)).end();
                    });

                    if ($('#aslots').has('option').length == 0) { //if it is blank.
                        $('#aslots').append($('<option>').text('No Further Time Slots').val(
                            'Not Selected')).end();
                    }
                }
            })
            $("#visit_description").html(" ");
            $("#visit_charges").val(" ");
            $.ajax({
                url: "doctor/getDoctorVisit?id=" + doctorr,
                method: "GET",
                data: "",
                dataType: "json",
                success: function(response1) {
                    $("#visit_description").html(response1.response).end();
                    // $("#visit_id").html(response1.responsee).end();
                    $("#addAppointmentForm").find('[name="visit_id"]').val(response1.responsee).end();
                },
            });

            $('#visiting_place_list').html("");
            if (doctorr !== null) {
                $.ajax({
                    url: 'doctor/getDoctorVisitingPlace?id=' + doctorr,
                    method: 'GET',
                    data: '',
                    dataType: 'json',
                    success: function(response) {

                        //   $('#visiting_place_list').html('<input type="radio" id="+ permited_modules +" name="fav_language" value="HTML"><br>' + response.option);
                        $('#visiting_place_list').html(
                            '<label for="exampleInputEmail1"> </label><br>' + response
                            .option);
                    }
                })

            }

            // var id = $(this).val();
            setTimeout(function() {


                $("#new_subtotal_fee").empty();
                $("#gateway_fee").empty();
                $("#visit_charges").val(" ");
                $("#total_charges").val(" ");
                // var doctor_id = $("#doctor_id").val();
                var total_fee = $("#total_fee").val();
                var courier_fee = $("#courier_fee").val();
                var casetaker_fee = $("#casetaker_fee").val();
                var onlinecenter_fee = $("#onlinecenter_fee").val();
                var currency = $("#currency").val();
                var subtotal = $("#subtotal_fee").val();
                var visit_id = $("#visit_id").val();

                console.log(subtotal);
                $.ajax({
                    url: "doctor/getDoctorVisitCharges?id=" + visit_id,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {

                        if (currency == "BDT") {
                            var visit = response.response.visit_charges;
                        }
                        if (currency == "INR") {
                            var visit = response.response.visit_charges_rupi;
                        }
                        if (currency == "USD") {
                            var visit = response.response.visit_charges_doller;
                        }
                        if ($("#pay_for_courier").prop("checked") == true) {
                            var courier = courier_fee;
                        } else {
                            var courier = 0;
                        }
                        if (doctorr.trim() != "") {
                            var total_doctor_amount =
                                parseFloat(visit) +
                                parseFloat(casetaker_fee) +
                                parseFloat(onlinecenter_fee);
                        } else {
                            var total_doctor_amount = visit;
                        }
                        var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
                        var selecttype = $("#selecttype").val();
                        if(selecttype === 'Cash'){
                            var gateway_fee = 0;
                        }else{
                            var gateway_fee = new_subtotal * 2.5 / 100;
                        }
                        var gateway_amount = new_subtotal * 2.5 / 100;

                        $("#visit_charges").val(visit).end();
                        var total = parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier)
                        $("#total_charges")
                            .val(parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier))
                            .end();
                        $("#charge_without_courier")
                            .val(parseFloat(visit) + parseFloat(subtotal))
                            .end();
                        $("#hidden_total_charges")
                            .val(parseFloat(visit) + parseFloat(subtotal) + parseFloat(courier))
                            .end();
                        $("#doctor_amount").val(total_doctor_amount).end();
                        $('#new_subtotal_fee').val(new_subtotal).end();
                        $('#gateway_amount').val(gateway_amount).end();
                        $('#gateway_fee').append(gateway_fee).end();

                        if (currency == "BDT") {
                            var mimimum_amount = 300;
                        }
                        if (currency == "INR") {
                            var mimimum_amount = 300;
                        }
                        if (currency == "USD") {
                            var mimimum_amount = 5;
                        }
                        // $("#deposited_amount").val(mimimum_amount).end();
                        var deposited_amount = $("#deposited_amount").val();
                        $("#due_amount").val(total - deposited_amount).end();
                    },
                });
            }, 3000);

        }
    });
</script>
<script>
    $(document).ready(function() {
        "use strict";
        $(".doctor_div").on("change", "#adoctors", function() {
            "use strict";

            var iid = $('#date').val();
            var doctorr = $('#adoctors').val();
            $('#aslots').find('option').remove();

            $.ajax({
                url: 'schedule/getAvailableSlotByDoctorByDateByJason?date=' + iid + '&doctor=' +
                    doctorr,
                method: 'GET',
                data: '',
                dataType: 'json',
                success: function(response) {
                    "use strict";
                    $("#doctor_id").val(doctorr).end();
                    var slots = response.aslots;
                    $.each(slots, function(key, value) {
                        "use strict";
                        $('#aslots').append($('<option>').text(value).val(value)).end();
                    });

                    if ($('#aslots').has('option').length == 0) { //if it is blank.
                        $('#aslots').append($('<option>').text('No Further Time Slots').val(
                            'Not Selected')).end();
                    }
                }
            })
            $("#visit_description").html(" ");
            $("#visit_charges").val(" ");
            $.ajax({
                url: "doctor/getDoctorVisit?id=" + doctorr,
                method: "GET",
                data: "",
                dataType: "json",
                success: function(response1) {
                    $("#visit_description").html(response1.response).end();
                    // $("#visit_id").html(response1.responsee).end();
                    $("#addAppointmentForm").find('[name="visit_id"]').val(response1.responsee).end();
                },
            });

            $('#visiting_place_list').html("");
            if (doctorr !== null) {
                $.ajax({
                    url: 'doctor/getDoctorVisitingPlace?id=' + doctorr,
                    method: 'GET',
                    data: '',
                    dataType: 'json',
                    success: function(response) {

                        //   $('#visiting_place_list').html('<input type="radio" id="+ permited_modules +" name="fav_language" value="HTML"><br>' + response.option);
                        $('#visiting_place_list').html(
                            '<label for="exampleInputEmail1"> </label><br>' + response
                            .option);
                    }
                })

            }

            // var id = $(this).val();
            setTimeout(function() {


                $("#new_subtotal_fee").empty();
                $("#gateway_fee").empty();
                $("#visit_charges").val(" ");
                $("#total_charges").val(" ");
                // var doctor_id = $("#doctor_id").val();
                var total_fee = $("#total_fee").val();
                var courier_fee = $("#courier_fee").val();
                var casetaker_fee = $("#casetaker_fee").val();
                var onlinecenter_fee = $("#onlinecenter_fee").val();
                var currency = $("#currency").val();
                var subtotal = $("#subtotal_fee").val();
                var visit_id = $("#visit_id").val();

                console.log(subtotal);
                $.ajax({
                    url: "doctor/getDoctorVisitCharges?id=" + visit_id,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {

                        if (currency == "BDT") {
                            var visit = response.response.visit_charges;
                        }
                        if (currency == "INR") {
                            var visit = response.response.visit_charges_rupi;
                        }
                        if (currency == "USD") {
                            var visit = response.response.visit_charges_doller;
                        }
                        if ($("#pay_for_courier").prop("checked") == true) {
                            var courier = courier_fee;
                        } else {
                            var courier = 0;
                        }
                        if (doctorr.trim() != "") {
                            var total_doctor_amount =
                                parseFloat(visit) +
                                parseFloat(casetaker_fee) +
                                parseFloat(onlinecenter_fee);
                        } else {
                            var total_doctor_amount = visit;
                        }
                        var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
                        var selecttype = $("#selecttype").val();
                        if(selecttype === 'Cash'){
                            var gateway_fee = 0;
                        }else{
                            var gateway_fee = new_subtotal * 2.5 / 100;
                        }
                        var gateway_amount = new_subtotal * 2.5 / 100;

                        $("#visit_charges").val(visit).end();
                        var total = parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier)
                        $("#total_charges")
                            .val(parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier))
                            .end();
                        $("#charge_without_courier")
                            .val(parseFloat(visit) + parseFloat(subtotal))
                            .end();
                        $("#hidden_total_charges")
                            .val(parseFloat(visit) + parseFloat(subtotal) + parseFloat(courier))
                            .end();
                        $("#doctor_amount").val(total_doctor_amount).end();
                        $('#new_subtotal_fee').val(new_subtotal).end();
                        $('#gateway_amount').val(gateway_amount).end();
                        $('#gateway_fee').append(gateway_fee).end();

                        if (currency == "BDT") {
                            var mimimum_amount = 300;
                        }
                        if (currency == "INR") {
                            var mimimum_amount = 300;
                        }
                        if (currency == "USD") {
                            var mimimum_amount = 5;
                        }
                        // $("#deposited_amount").val(mimimum_amount).end();
                        var deposited_amount = $("#deposited_amount").val();
                        $("#due_amount").val(total - deposited_amount).end();
                    },
                });
            }, 3000);
        });

    });
</script>

<script>
    $(document).ready(function() {
        "use strict";
        $("#date")
            .datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            })
            //Listen for the change even on the input
            .change(dateChanged)
            .on("changeDate", dateChanged);
    });

    function dateChanged() {
        "use strict";

        var id = $("#appointment_id").val();
        var date = $("#date").val();


        var doctor = $("#doctor_id").val();

        if (doctor == null || doctor == " ") {
            var doctorr = $("#board_leader_id").val();
        } else {
            var doctorr = $("#doctor_id").val();
        }


        $("#aslots").find("option").remove();

        $.ajax({
            url: "schedule/getAvailableSlotByDoctorByDateByJason?date=" +
                date +
                "&doctor=" +
                doctorr,
            method: "GET",
            data: "",
            dataType: "json",
            success: function(response) {
                "use strict";
                // console.log(2);
                var slots = response.aslots;
                $.each(slots, function(key, value) {
                    $("#aslots").append($("<option>").text(value).val(value)).end();
                });

                if ($("#aslots").has("option").length == 0) {
                    //if it is blank.
                    $("#aslots")
                        .append(
                            $("<option>").text("No Further Time Slots").val("Not Selected")
                        )
                        .end();
                }
            },
        });
    }
</script>
<script>
    $(document).ready(function() {
        "use strict";
        $(".flashmessage").delay(3000).fadeOut(100);
    });
</script>
<script>
    $(document).ready(function() {


        $("#my_select1_team_disabled").select2({
            placeholder: '<?php echo lang('select_team'); ?>',
            closeOnSelect: true,
            ajax: {
                url: 'team/getTeamNamelist',
                dataType: "json",
                type: "post",
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term, // search term
                        catchange: $("#hospitalchoose1").val(),
                        medid: $(this).val(),
                        page: params.page,
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: params.page * 1 < data.total_count,
                        },
                    };
                },
                cache: true,
            },
        });

        $('#my_select1_team_disabled').on('change', function() {
            var id = $(this).val();
            var count = 0;
            var team_id = $('#team_id').val();
            $.ajax({
                url: 'team/getTeamDetails?id=' + id,
                method: 'GET',
                data: '',
                dataType: 'json',
                success: function(response2) {
                    var team_doctor1 = response2.doctor_list.split(",");

                    $.each(team_doctor1, function(index, value) {
                        let team_doctor_extended111 = [];
                        team_doctor_extended111 = value.split("****");
                        var $select = $("#my_select1_disabled");
                        var idToRemove = team_doctor_extended111[0] + '*' +
                            team_doctor_extended111[1];
                        $("#my_select1_disabled option[value='" +
                            team_doctor_extended111[0] + '*' +
                            team_doctor_extended111[1] + "']").remove();
                        $('.select2 - selection__clear').find('[title="' +
                            team_doctor_extended111[1] + '"]').remove();
                        var values = $select.val();
                        if (values) {
                            var i = values.indexOf(idToRemove);
                            if (i >= 0) {
                                values.splice(i);
                                $select.val(values).change();
                            }
                        }
                        $('#med_selected_section-' + team_doctor_extended111[0])
                            .remove();
                    });
                },
            });
            $(".board_doc").find("option").remove();
            $.ajax({
                url: 'team/getAppointmentDetails?id=' + id,
                method: 'GET',
                data: '',
                dataType: 'json',
                //  timeout: 5000
                success: function(response) {
                    $('#team_id').val(id);
                    var lead_doctor = response.team.lead_doctor
                    $('#board_leader_id').val(lead_doctor);
                    var team_doctor = response.team.doctor.split(",");
                    $.each(team_doctor, function(index, value) {
                        var team_doctor_extended = [];
                        team_doctor_extended = value.split("***");
                        var med_id = team_doctor_extended[0];
                        $.ajax({
                            url: 'doctor/getBoardDoctorByJason?id=' + med_id,
                            method: 'GET',
                            data: '',
                            dataType: 'json',
                            success: function(response1) {
                                var id = response1.doctor.id;
                                // var id = $(this).data('id');
                                var med_id = response1.doctor.id;
                                var med_name = response1.doctor.name;
                                var hospital_category = response1.hospital_category.name;
                                var hospital = response1.hospital.name;

                                var option = new Option(med_name + ' - ' + hospital_category + ' - ' + hospital, response1.doctor.id, true,
                                    true);
                                $('.board_doc').append(option).trigger(
                                    'change');
                            },




                        });
                    });
                },

            });

            setTimeout(function() {

                var onlinecenter_id = $("#onlinecenter_id").val();
                var casetaker_id = $("#casetaker_id").val();
                var doctor_id = $("#board_leader_id").val();
                var currency = $("#currency").val();
                $("#new_subtotal_fee").empty();
                $("#gateway_fee").empty();
                $("#shipping_fee").empty();

                $.ajax({
                    url: "appointment/getDoctorCommissionSettings?id=" + doctor_id,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (currency == "BDT") {
                            var casetaker_fee = response.commission.casetaker_fee;
                            var onlinecenter_fee = response.commission.onlinecenter_fee;
                            var developer_fee = response.commission.developer_fee;
                            var hospital_fee = response.commission.current_hospital;
                            var superadmin_fee = response.commission.superadmin_fee;
                            var medicine_fee = response.commission.medicine_fee;
                            var courier_fee = response.commission.courier_fee;
                        }
                        if (currency == "INR") {
                            var casetaker_fee = response.commission.casetaker_fee_rupee;
                            var onlinecenter_fee = response.commission.onlinecenter_fee_rupee;
                            var developer_fee = response.commission.developer_fee_rupee;
                            var hospital_fee = response.commission.current_hospital_rupee;
                            var superadmin_fee = response.commission.superadmin_fee_rupee;
                            var medicine_fee = response.commission.medicine_fee_rupee;
                            var courier_fee = response.commission.courier_fee_rupee;
                        }
                        if (currency == "USD") {
                            var casetaker_fee = response.commission.casetaker_fee_dollar;
                            var onlinecenter_fee = response.commission.onlinecenter_fee_dollar;
                            var developer_fee = response.commission.developer_fee_dollar;
                            var hospital_fee = response.commission.current_hospital_dollar;
                            var superadmin_fee = response.commission.superadmin_fee_dollar;
                            var medicine_fee = response.commission.medicine_fee_dollar;
                            var courier_fee = response.commission.courier_fee_dollar;
                        }
                        if ($("#pay_for_courier").prop("checked") == true) {
                            var courier = courier_fee;
                        } else {
                            var courier = 0;
                        }

                        var total_fee_without_courier =
                            parseFloat(casetaker_fee) +
                            parseFloat(onlinecenter_fee) +
                            parseFloat(developer_fee) +
                            parseFloat(hospital_fee) +
                            parseFloat(superadmin_fee) +
                            parseFloat(medicine_fee);
                        var gateway_fee = total_fee_without_courier * 2.5 / 100;
                        var total_fee =
                            parseFloat(total_fee_without_courier) +
                            parseFloat(courier);

                        $("#casetaker_fee").val(casetaker_fee).end();
                        $("#onlinecenter_fee").val(onlinecenter_fee).end();
                        $("#developer_fee").val(developer_fee).end();
                        $("#hospital_fee").val(hospital_fee).end();
                        $("#superadmin_fee").val(superadmin_fee).end();
                        $("#medicine_fee").val(medicine_fee).end();
                        $("#courier_fee").val(courier_fee).end();
                        $("#total_fee").val(total_fee).end();
                        $('#shipping_fee').append(courier_fee).end();
                        $('#new_subtotal_fee').val(total_fee_without_courier).end();
                        $('#gateway_fee').append(gateway_fee).end();
                        $("#subtotal_fee").val(total_fee_without_courier).end();
                        $("#total_charges").empty();
                    },
                });
            }, 2000);
            setTimeout(function() {
                var id = $('#team_id').val();
                var courier_fee = $("#courier_fee").val();
                $("#gateway_fee").empty();
                // var currency = $("#currency").val();
                var subtotal = $("#subtotal_fee").val();
                $.ajax({
                    url: "frontend/getBoardDetails?id=" + id,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {

                        var visit = response.response.total_board_visit;


                        var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
                        var selecttype = $("#selecttype").val();
                        if(selecttype === 'Cash'){
                            var gateway_fee = 0;
                        }else{
                            var gateway_fee = new_subtotal * 2.5 / 100;
                        }
                        var gateway_amount = new_subtotal * 2.5 / 100;
                        if ($("#pay_for_courier").prop("checked") == true) {
                            var courier = courier_fee;
                        } else {
                            var courier = 0;
                        }
                        $("#visit_charges").val(visit).end();
                        $("#total_charges")
                            .val(parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier))
                            .end();
                        $("#hidden_total_charges")
                            .val(parseFloat(visit) + parseFloat(subtotal)  + parseFloat(courier))
                            .end();
                        // $("#doctor_amount").val(total_doctor_amount).end();
                        $('#new_subtotal_fee').val(new_subtotal).end();
                        $('#gateway_fee').append(gateway_fee).end();
                        $('#gateway_amount').val(gateway_amount).end();


                        var total = parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier)

                        var deposited_amount = $("#deposited_amount").val();
                        $("#due_amount").val(total - deposited_amount).end();

                    },
                });
            }, 3000);


        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#my_select1_disabledd").select2({
            placeholder: '<?php echo lang('select_doctor'); ?>',
            multiple: true,
            allowClear: true,
            ajax: {
                url: 'doctor/getDoctorListForSelect2',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }

        });
    });
    $(document).ready(function() {
        $("#my_select1_disabled").select2({
            placeholder: '<?php echo lang('select_doctor'); ?>',
            multiple: true,
            allowClear: true,
            ajax: {
                url: 'doctor/getDoctorListForSelect2',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }

        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.board_doc').select2();

        $('#bdoc').on('change', function() {
            $('#gateway_fee').empty("").end();
            var courier_fee = $("#courier_fee").val();
            var amount = $("#charge_without_courier").val();
            var selectedOptions = $(this).find(':selected');

            var totalPrice = Array.from(selectedOptions).reduce((sum, option) => {
                var price = parseFloat(option.dataset.price);
                return sum + price;

            }, 0);
            var type = $("#selecttype").val();
            $('#custom_doc_fee').val(totalPrice);
            var total_without_gfee = parseFloat(amount) + parseFloat(totalPrice)
            if(type === 'Cash'){
                            var gateway_fee = 0;
                        }else{
                            var gateway_fee = total_without_gfee * 2.5 / 100;
                        }
                        var gateway_amount = total_without_gfee * 2.5 / 100;
            // var gateway_fee = total_without_gfee * 2.5 / 100;
            // if (type == "Cash") {
                // var total = parseFloat(total_without_gfee)  + parseFloat(courier_fee)
            // } else {
                var total = parseFloat(total_without_gfee) + parseFloat(gateway_fee) + parseFloat(courier_fee)
            // }
            $('#total_charges').val(total);




            var deposited_amount = $("#deposited_amount").val();
            $("#due_amount").val(total - deposited_amount).end();
            $('#gateway_fee').append(gateway_fee).end();
            $('#gateway_amount').val(gateway_amount).end();
        });

        //   $('.my_select1_disabledd').on('click', function() {
        //     var price = parseFloat(event.params.data.element.dataset.price);
        //     console.log(price);
        //     var currentTotal = parseFloat($('#total_charges').val());
        //     $('#total_charges').val(" ");
        //   });
    });

    $("#deposited_amount").keyup(function() {
        var deposit = $(this).val();
        var currency = $("#currency").val();
        var total = $("#total_charges").val();
        if (currency == "BDT") {
            var mimimum_amount = 300;
        }
        if (currency == "INR") {
            var mimimum_amount = 300;
        }
        if (currency == "USD") {
            var mimimum_amount = 5;
        }
        // var deposited_amount = $("#deposited_amount").val();

        // $("#deposited_amount").val(mimimum_amount);
        console.log(deposit, mimimum_amount);
        // if (parseFloat(200) > parseFloat(deposit)) {
        //     setTimeout(function () {  $("#deposited_amount").val(200);  }, 1500);
        // }
        $("#due_amount").val(total - deposit).end();
        // var due = total_charges - deposit;
        // $("#due_amount").val(due);

    });
</script>
<script>
    function validateForm() {
        var quantityInput = document.getElementById("deposited_amount");
        var quantityValue = quantityInput.value;

        if (quantityValue < 200) {
            alert("Advance Payment (Minimum 200).");
            return false; // prevent form submission
        } else {
            return true; // allow form submission
        }
    }
</script>

<script>
    $(document).ready(function() {
        $(".patid_div").hide();
        $('#create_patient').on('click', function() {
            var doctor = $('#adoctors').val();
            var p_name = $('#p_name').val();
            var p_phone = $('#p_phone').val();
            var p_email = $('#p_email').val();
            var p_address = $('#p_address').val();
            var p_gender = $('#p_gender').val();
            var p_birthdate = $('#p_birthdate').val();
            var years = $('#years').val();
            var months = $('#months').val();
            var days = $('#days').val();
            var onlinecenter_id = $('#onlinecenter_id').val();
            var casetaker_id = $('#casetaker_id').val();
            var hospital_id = $('#hospitalchoose1').val();
            var country = $('#country').val();
            var errors = '';
            if (p_name == '') {
                errors += 'Patient Name is required.\n';
            }
            if (p_phone == '') {
                errors += 'Phone No is required.\n';
            }
            if (p_address == '') {
                errors += 'Patient Address is required.\n';
            }
            if (country == '') {
                errors += 'Patient Country is required.\n';
            }
            if (errors != '') {
                alert(errors);
                return false;
            }
            $.ajax({
                url: 'patient/createPatient',
                type: 'POST',
                dataType: 'json',
                data: {
                    doctor: doctor,
                    p_name: p_name,
                    p_phone: p_phone,
                    p_email: p_email,
                    p_address: p_address,
                    p_gender: p_gender,
                    p_birthdate: p_birthdate,
                    years: years,
                    country: country,
                    months: months,
                    days: days,
                    onlinecenter_id: onlinecenter_id,
                    casetaker_id: casetaker_id,
                    hospital_id: hospital_id
                },
                success: function(response) {
                    console.log();
                    // var id = response.option.patientt;
                    // $("#errormsg").html(response.message.message);
                    $("#add_message").html(response.message.message);
                    var id = response.option.patientt;
                    $("#addAppointmentForm").find('[name="patientt"]').val(response.option.patientt).end();
                    $("#patientname").html(response.optionn.patientname);
                    $("#pos_select").val(" ");
                    if (id != null) {
                        // if(response.message.message === 'Added'){
                        $(".pat_div").hide();
                        $(".patid_div").show();
                    } else {
                        $(".pat_div").show();
                        $(".patid_div").hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle error response
                    console.log(jqXHR);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
  $("#p_birthdate").change(function () {
    var birthdateStr = $("#p_birthdate").val();
    var parts = birthdateStr.split("-");
    var birthdate = new Date(parts[2], parts[1] - 1, parts[0]);
    var today = new Date();

    var age = today.getFullYear() - birthdate.getFullYear();
    var months = today.getMonth() - birthdate.getMonth();
    var days = today.getDate() - birthdate.getDate();

    if (months < 0 || (months === 0 && today.getDate() < birthdate.getDate())) {
      age--;
      months += 12;
    }

    if (days < 0) {
      var prevMonthLastDay = new Date(
        today.getFullYear(),
        today.getMonth(),
        0
      ).getDate();
      days = prevMonthLastDay - birthdate.getDate() + today.getDate();
      months--;
    }

    $("#years").val(age);
    $("#months").val(months);
    $("#days").val(days);
  });

  $("#p_birthdate").keyup(function () {
    var birthdateStr = $("#p_birthdate").val();
    var parts = birthdateStr.split("-");
    var birthdate = new Date(parts[2], parts[1] - 1, parts[0]);
    var today = new Date();

    var age = today.getFullYear() - birthdate.getFullYear();
    var months = today.getMonth() - birthdate.getMonth();
    var days = today.getDate() - birthdate.getDate();

    if (months < 0 || (months === 0 && today.getDate() < birthdate.getDate())) {
      age--;
      months += 12;
    }

    if (days < 0) {
      var prevMonthLastDay = new Date(
        today.getFullYear(),
        today.getMonth(),
        0
      ).getDate();
      days = prevMonthLastDay - birthdate.getDate() + today.getDate();
      months--;
    }

    $("#years").val(age);
    $("#months").val(months);
    $("#days").val(days);
  });
});

$(document).ready(function () {
  $("#years, #months, #days").keyup(function () {
    //   $('#years, #months, #days').change(function() {
    calculateBirthdate();
  });

  function calculateBirthdate() {
    var years = parseInt($("#years").val());
    var months = parseInt($("#months").val());
    var days = parseInt($("#days").val());
    // var day = days - 1;
    var today = new Date();

    var birthdate = new Date(
      today.getFullYear() - years,
      today.getMonth() - months,
      today.getDate() - days
    );

    var dd = String(birthdate.getDate()).padStart(2, "0");
    var mm = String(birthdate.getMonth() + 1).padStart(2, "0");
    var yyyy = birthdate.getFullYear();

    var birthdateString = dd + "-" + mm + "-" + yyyy;
    $("#p_birthdate").val(birthdateString);
  }
});
</script>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <link href="common/extranal/css/patient/medical_history.css" rel="stylesheet">


        <style>
        .tdd {
            border: 1px solid #ccc;
            width: 75%;
        }

        .td {

            padding-top: 5px;
            padding-bottom: 5px;
        }

        .wrapper {
            margin-top: 0px !important;
            padding: 0px !important;
        }

        #main-content {
            margin-left: 0px !important;
        }
        </style>

        <?php  $user_ion_id = $this->ion_auth->get_user_id(); 
$currentDate = date("d-m-Y"); ?>

        <section class="col-md-9">
            <header class="panel-heading clearfix">
                <div class="col-md-7">
                    <?php echo lang('history'); ?> | <?php echo $patient->name; ?>
                    <?php
                $message = $this->session->flashdata('feedbackk');
                if (!empty($message)) {
                ?>
                    <code class="flashmessage pull-right"> <?php echo $message; ?></code>
                    <?php } ?>


                </div>
                <div class="col-md-4 no-print pull-right">
                    <a href="home">
                        <div class="btn-group pull-right">
                            <button id="" class="btn green btn-xs">
                                <i class="fa fa-home"></i> Home
                            </button>
                        </div>
                    </a>
                </div>
            </header>

            <section class="panel-body">
                <header class="panel-heading tab-bg-dark-navy-blueee">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#score">Score </a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#home"><?php echo lang('case_history'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#appointments"><?php echo lang('appointments'); ?></a>
                        </li>


                        <li class="">
                            <a data-toggle="tab" href="#about"><?php echo lang('prescription'); ?></a>
                        </li>

                        <li class="">
                            <a data-toggle="tab" href="#lab"><?php echo lang('lab'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#profile"><?php echo lang('documents'); ?></a>
                        </li>
                        <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                        <li class="">
                            <a data-toggle="tab" href="#contact"><?php echo lang('bed'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#timeline"><?php echo lang('timeline'); ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </header>
                <div class="panel">
                    <div class="tab-content">
                        <div id="appointments" class="tab-pane ">
                            <div class="">


                                <div class=" no-print">
                                    <a class="btn btn-info btn_width btn-xs" data-toggle="modal"
                                        href="appointment/addAppointmentViewByDoctor?patientId=<?php echo $patient->id; ?>&redirect=med_his">
                                        <i class="fa fa-plus-circle"> </i> <?php echo lang('add_new'); ?>
                                    </a>
                                </div>


                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('date'); ?></th>
                                                <th><?php echo lang('time_slot'); ?></th>
                                                <th><?php echo lang('doctor'); ?></th>
                                                <th><?php echo lang('status'); ?></th>
                                                <th>Appointment By</th>

                                                <th class="no-print"><?php echo lang('options'); ?></th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($appointments as $appointment) { ?>
                                            <tr class="">

                                                <td><?php echo date('d-m-Y', $appointment->date); ?></td>
                                                <td><?php echo $appointment->time_slot; ?></td>
                                                <td>
                                                    <?php
                                                        $doctor_details = $this->doctor_model->getDoctorById($appointment->doctor);
                                                        $doctor_details = $this->doctor_model->getDoctorByOnlinecenter($appointment->doctor);
                                                        if (!empty($doctor_details)) {
                                                            $appointment_doctor = $doctor_details->name;
                                                        } else {
                                                            $appointment_doctor = '';
                                                        }
                                                        echo $appointment_doctor;
                                                        ?>
                                                </td>
                                                <td><?php
                                                        if ($appointment->status == 'Pending Confirmation') {
                                                            $appointment_status = '<button type="button" class="btn btn-warning btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
                                                        } elseif ($appointment->status == 'Confirmed') {
                                                            $appointment_status = '<button type="button" class="btn btn-info btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
                                                        } elseif ($appointment->status == 'Treated') {
                                                            $appointment_status = '<button type="button" class="btn btn-success btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
                                                        } elseif ($appointment->status == 'Cancelled') {
                                                            $appointment_status = '<button type="button" class="btn btn-danger btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
                                                        } elseif ($appointment->status == 'Requested') {
                                                            $appointment_status = '<button type="button" class="btn btn-default btn-xs btn_width statusbutton" data-toggle="modal" data-id="' . $appointment->id . '"> ' . $appointment->status . '</button>';
                                                        }
                                                        echo $appointment_status;
                                                        ?></td>

                                                <?php $user_info = $this->patient_model->getUserById($appointment->user);
                                                    $user_group_id = $this->patient_model->getGroupByUserId($user_info->id)->group_id;
                                                    $user_group_info = $this->patient_model->getGroupNameById($user_group_id);
                                                    ?>

                                                <?php if ($user_group_info->name == 'Doctor') {
                                                        $doctor_info = $this->doctor_model->getDoctorByIonUserId($appointment->user);
                                                        $hospital_info = $this->hospital_model->getHospitalById($doctor_info->hospital_id);
                                                        $hospital_category_info = $this->hospital_model->getHospitalCategoryById($hospital_info->category)
                                                    ?>
                                                <td><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $hospital_category_info->name; ?> |
                                                    <?php echo $hospital_info->name; ?> |
                                                    <?php echo $doctor_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'onlinecenter') {
                                                        $onlinecenter_info = $this->onlinecenter_model->getOnlinecenterByIonUserId($appointment->user);
                                                    ?>
                                                <td><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $onlinecenter_info->name; ?> |
                                                    <?php echo $onlinecenter_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'casetaker') {
                                                        $casetaker_info = $this->casetaker_model->getCasetakerByIonUserId($appointment->user);
                                                        $onlinecenter_info = $this->onlinecenter_model->getOnlinecenterById($casetaker_info->onlinecenter_id);
                                                    ?>
                                                <td><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $onlinecenter_info->name; ?> |
                                                    <?php echo $casetaker_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'Patient') {
                                                        $patient_info = $this->patient_model->getPatientByIonUserId($appointment->user);
                                                    ?>
                                                <td><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $patient_info->name; ?> |
                                                    <?php echo $patient_info->phone; ?></td>
                                                <?php } else { ?>
                                                <td><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?></td>
                                                <?php } ?>
                                                <?php

                                                    $timestamp = $appointment->date;
                                                    $current_timestamp = time();
                                                    $diff = date_diff(date_create('@' . $timestamp), date_create('@' . $current_timestamp));
                                                    $days_diff = $diff->format('%a');
                                                    ?>
                                                <?php if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) { ?>
                                                <td class="no-print edit_appointment_button">
                                                    <?php if($user_ion_id == $appointment->user) { ?>
                                                    <a type="button" class="btn btn-info btn-xs btn_width"
                                                        title="<?php echo lang('edit'); ?>"
                                                        href="appointment/editAppointmentFromLive?id=<?php echo $appointment->id; ?>&redirect=med_his"><i
                                                            class="fa fa-edit"></i> </a>
                                                    <?php } ?>
                                                    <?php if ($days_diff >= 15) { ?>

                                                    <?php } else { ?>
                                                    <a class="btn btn-info btn_width btn-xs addCase"
                                                        data-id="<?php echo $appointment->id; ?>" data-toggle="modal"
                                                        href="#myModal">
                                                        <i class="fa fa-plus-circle"> </i>
                                                        <?php echo lang('add_case'); ?>
                                                    </a>
                                                    <a class="btn btn-info btn_width btn-xs addFile"
                                                        data-id="<?php echo $appointment->id; ?>" data-toggle="modal"
                                                        href="#myModal1">
                                                        <i class="fa fa-plus-circle"> </i>
                                                        <?php echo lang('add_file'); ?>
                                                    </a>
                                                    <a class="btn btn-info btn_width btn-xs"
                                                        href="prescription/addPrescriptionViewByDoctor?id=<?php echo $appointment->patient; ?>&appointment=<?php echo $appointment->id; ?>">

                                                        <i class="fa fa-plus-circle"> </i>
                                                        <?php echo lang('add_prescription'); ?>
                                                    </a>
                                                    <?php } ?>
                                                </td>
                                                <?php } ?>
                                                <?php if (!$this->ion_auth->in_group(array('Patient', 'onlinecenter', 'casetaker'))) {

                                                    ?>
                                                <td class="no-print edit_appointment_button">
                                                    <?php if($user_ion_id == $appointment->user) { ?>
                                                    <a type="button" class="btn btn-info btn-xs btn_width"
                                                        title="<?php echo lang('edit'); ?>"
                                                        href="appointment/editAppointmentFromLive?id=<?php echo $appointment->id; ?>&redirect=med_his"><i
                                                            class="fa fa-edit"></i> </a>
                                                    <a class="btn btn-info btn-xs btn_width delete_button"
                                                        title="<?php echo lang('delete'); ?>"
                                                        href="appointment/delete?id=<?php echo $appointment->id; ?>"><i
                                                            class="fa fa-trash"></i> </a>
                                                    <!-- <a type="button" class="btn btn-xs btn-primary depositButton" title="' . lang('deposit') . '" data-toggle = "modal" data-id="' . $payment->id . '" data-from="' . $payment->payment_from . '"><i class="fa fa-money"> </i> ' . lang('deposit') . '</a> -->
                                                    <?php } ?>

                                                    <?php if ($days_diff >= 15) { ?>

                                                    <?php } else { ?>
                                                    <a class="btn btn-info btn_width btn-xs addCase"
                                                        data-id="<?php echo $appointment->id; ?>" data-toggle="modal"
                                                        href="#myModal">
                                                        <i class="fa fa-plus-circle"> </i>
                                                        <?php echo lang('add_case'); ?>
                                                    </a>
                                                    <a class="btn btn-info btn_width btn-xs addFile"
                                                        data-id="<?php echo $appointment->id; ?>" data-toggle="modal"
                                                        href="#myModal1">
                                                        <i class="fa fa-plus-circle"> </i>
                                                        <?php echo lang('add_file'); ?>
                                                    </a>
                                                    <a class="btn btn-info btn_width btn-xs"
                                                        href="prescription/addPrescriptionViewByDoctor?id=<?php echo $appointment->patient; ?>&appointment=<?php echo $appointment->id; ?>">

                                                        <i class="fa fa-plus-circle"> </i>
                                                        <?php echo lang('add_prescription'); ?>
                                                    </a>
                                                    <?php } ?>
                                                    <?php if ($this->ion_auth->in_group(array('Patient', 'Doctor'))) {
                                                            if ($appointment->status == 'Confirmed') { ?>

                                                    <a class="btn btn-info btn-xs btn_width detailsbutton buttoncolor"
                                                        title="Start Live"
                                                        href="meeting/instantLive?id=<?php echo $appointment->id; ?>"
                                                        target="_blank"
                                                        onclick="return confirm('Are you sure you want to start a live meeting ?');"><i
                                                            class="fa fa-headphones"></i> Live </a>

                                                    <?php }
                                                        } ?>
                                                </td>
                                                <?php } ?>


                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="home" class="tab-pane ">
                            <div class="">

                                <?php if (!$this->ion_auth->in_group(array(''))) { ?>
                                <!-- <div class=" no-print">
                                        <a class="btn btn-info btn_width btn-xs" data-toggle="modal" href="#myModal">
                                            <i class="fa fa-plus-circle"> </i> <?php echo lang('add_new'); ?>
                                        </a>
                                    </div> -->
                                <?php } ?>

                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>


                                            <?php foreach ($appointments as $appointment) { ?>
                                            <tr class="">

                                                <th style="background:#f1f1f1;">
                                                    <?php echo date('d-m-Y', $appointment->date); ?></th>
                                                <th style="background:#f1f1f1;"><?php echo $appointment->time_slot; ?>
                                                </th>
                                                <th style="background:#f1f1f1;">
                                                    <?php
                                                        $doctor_details = $this->doctor_model->getDoctorById($appointment->doctor);
                                                        $doctor_details = $this->doctor_model->getDoctorByOnlinecenter($appointment->doctor);
                                                        if (!empty($doctor_details)) {
                                                            $appointment_doctor = $doctor_details->name;
                                                        } else {
                                                            $appointment_doctor = '';
                                                        }
                                                        echo $appointment_doctor;
                                                        ?>
                                                </th>
                                                <th style="background:#f1f1f1;"><?php
                                                                                    if ($appointment->status == 'Pending Confirmation') {
                                                                                        $appointment_status = lang('pending_confirmation');
                                                                                    } elseif ($appointment->status == 'Confirmed') {
                                                                                        $appointment_status = lang('confirmed');
                                                                                    } elseif ($appointment->status == 'Treated') {
                                                                                        $appointment_status = lang('treated');
                                                                                    } elseif ($appointment->status == 'Cancelled') {
                                                                                        $appointment_status = lang('cancelled');
                                                                                    } elseif ($appointment->status == 'Requested') {
                                                                                        $appointment_status = lang('requested');
                                                                                    }
                                                                                    echo $appointment_status;
                                                                                    ?></th>
                                                <?php $timestamp = $appointment->date;
                                                    $current_timestamp = time();
                                                    $diff = date_diff(date_create('@' . $timestamp), date_create('@' . $current_timestamp));
                                                    $days_diff = $diff->format('%a'); ?>
                                                <th style="background:#f1f1f1;">
                                                    <?php if ($days_diff >= 15) { ?>
                                                    <?php } else { ?>
                                                    <a class="btn btn-info btn_width btn-xs addCase casee"
                                                        data-id="<?php echo $appointment->id; ?>" data-toggle="modal"
                                                        href="#myModal">
                                                        <i class="fa fa-plus-circle"> </i>
                                                        <?php echo lang('add_case'); ?>
                                                    </a>
                                                    <?php } ?>
                                                </th>
                                            </tr>

                                            <?php $histories = $this->patient_model->getMedicalHistoryByAppointmentId($appointment->id); ?>




                                        <tbody>
                                            <?php foreach ($histories as $medical_history) { ?>
                                            <tr class="">
                                            <tr>
                                                <?php $user_info = $this->patient_model->getUserById($medical_history->user);
                                                    $user_group_id = $this->patient_model->getGroupByUserId($user_info->id)->group_id;
                                                    $user_group_info = $this->patient_model->getGroupNameById($user_group_id);
                                                    ?>

                                                <?php if ($user_group_info->name == 'Doctor') {
                                                        $doctor_info = $this->doctor_model->getDoctorByIonUserId($medical_history->user);
                                                        $hospital_info = $this->hospital_model->getHospitalById($doctor_info->hospital_id);
                                                        $hospital_category_info = $this->hospital_model->getHospitalCategoryById($hospital_info->category)
                                                    ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $hospital_category_info->name; ?> |
                                                    <?php echo $hospital_info->name; ?> |
                                                    <?php echo $doctor_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'onlinecenter') {
                                                        $onlinecenter_info = $this->onlinecenter_model->getOnlinecenterByIonUserId($medical_history->user);
                                                    ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $onlinecenter_info->name; ?> |
                                                    <?php echo $onlinecenter_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'casetaker') {
                                                        $casetaker_info = $this->casetaker_model->getCasetakerByIonUserId($medical_history->user);
                                                        $onlinecenter_info = $this->onlinecenter_model->getOnlinecenterById($casetaker_info->onlinecenter_id);
                                                    ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $onlinecenter_info->name; ?> |
                                                    <?php echo $casetaker_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'Patient') {
                                                        $patient_info = $this->patient_model->getPatientByIonUserId($medical_history->user);
                                                    ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $patient_info->name; ?> |
                                                    <?php echo $patient_info->phone; ?></td>
                                                <?php } else { ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><?php echo $medical_history->date_string; ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><?php echo $medical_history->title; ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><?php echo $medical_history->description; ?></td>
                                            </tr>
                                            <tr><?php if (!$this->ion_auth->in_group(array(''))) { ?>
                                                <td colspan="5" class="no-print medical_history_button">
                                                    <?php
                                                           if($user_ion_id == $medical_history->user) { ?>
                                                    <button type="button"
                                                        class="btn btn-info btn-xs btn_width editbutton"
                                                        title="<?php echo lang('edit'); ?>" data-toggle="modal"
                                                        data-id="<?php echo $medical_history->id; ?>"><i
                                                            class="fa fa-edit"></i> </button>
                                                    <?php } ?>
                                                    <?php if (!$this->ion_auth->in_group(array('', 'onlinecenter', 'casetaker', ''))) { 
                                                                 if($user_ion_id == $medical_history->user) { ?>
                                                    <a class="btn btn-info btn-xs btn_width delete_button"
                                                        title="<?php echo lang('delete'); ?>"
                                                        href="patient/deleteCaseHistory?id=<?php echo $medical_history->id; ?>"><i
                                                            class="fa fa-trash"></i> </a>
                                                    <?php } }?>
                                                </td>
                                                <?php } ?>
                                            </tr>



                                            </tr>
                                            <?php } ?>
                                        </tbody>





                                        <?php } ?>
                                        </thead>
                                    </table>
                                </div>

                                <div class="adv-table editable-table ">


                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('date'); ?></th>
                                                <th><?php echo lang('title'); ?></th>
                                                <th><?php echo lang('description'); ?></th>
                                                <?php if (!$this->ion_auth->in_group(array(''))) { ?>
                                                <th class="no-print"><?php echo lang('options'); ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($medical_histories as $medical_history) {
                                                if (empty($medical_history->appointment_id)) {
                                            ?>
                                            <tr class="">

                                                <td><?php echo $medical_history->date_string; ?></td>
                                                <td><?php echo $medical_history->title; ?></td>
                                                <td><?php echo $medical_history->description; ?></td>
                                                <?php if (!$this->ion_auth->in_group(array(''))) { ?>
                                                <td class="no-print medical_history_button">

                                                    <button type="button"
                                                        class="btn btn-info btn-xs btn_width editbutton"
                                                        title="<?php echo lang('edit'); ?>" data-toggle="modal"
                                                        data-id="<?php echo $medical_history->id; ?>"><i
                                                            class="fa fa-edit"></i> </button>

                                                    <?php if (!$this->ion_auth->in_group(array('', 'onlinecenter', 'casetaker', ''))) {
                                                                    ?>
                                                    <a class="btn btn-info btn-xs btn_width delete_button"
                                                        title="<?php echo lang('delete'); ?>"
                                                        href="patient/deleteCaseHistory?id=<?php echo $medical_history->id; ?>"><i
                                                            class="fa fa-trash"></i> </a>
                                                    <?php  }?>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div id="about" class="tab-pane">
                            <div class="">
                                <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                <!-- <div class=" no-print">
                                        <a class="btn btn-info btn_width btn-xs" href="prescription/addPrescriptionViewByDoctor?id=<?php echo $patient->id; ?>">
                                            <i class="fa fa-plus-circle"> </i> <?php echo lang('add_new'); ?>
                                        </a>
                                    </div> -->
                                <?php } ?>
                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>


                                            <?php foreach ($appointments as $appointment) { ?>
                                            <tr class="">

                                                <th style="background:#f1f1f1;" colspan="2">
                                                    <?php echo date('d-m-Y', $appointment->date); ?></th>
                                                <th style="background:#f1f1f1;"><?php echo $appointment->time_slot; ?>
                                                </th>
                                                <th style="background:#f1f1f1;">
                                                    <?php
                                                        $doctor_details = $this->doctor_model->getDoctorById($appointment->doctor);
                                                        $doctor_details = $this->doctor_model->getDoctorByOnlinecenter($appointment->doctor);
                                                        if (!empty($doctor_details)) {
                                                            $appointment_doctor = $doctor_details->name;
                                                        } else {
                                                            $appointment_doctor = '';
                                                        }
                                                        echo $appointment_doctor;
                                                        ?>
                                                </th>
                                                <th style="background:#f1f1f1;" colspan="2"><?php
                                                                                                if ($appointment->status == 'Pending Confirmation') {
                                                                                                    $appointment_status = lang('pending_confirmation');
                                                                                                } elseif ($appointment->status == 'Confirmed') {
                                                                                                    $appointment_status = lang('confirmed');
                                                                                                } elseif ($appointment->status == 'Treated') {
                                                                                                    $appointment_status = lang('treated');
                                                                                                } elseif ($appointment->status == 'Cancelled') {
                                                                                                    $appointment_status = lang('cancelled');
                                                                                                } elseif ($appointment->status == 'Requested') {
                                                                                                    $appointment_status = lang('requested');
                                                                                                }
                                                                                                echo $appointment_status;
                                                                                                ?></th>
                                                <?php $timestamp = $appointment->date;
                                                    $current_timestamp = time();
                                                    $diff = date_diff(date_create('@' . $timestamp), date_create('@' . $current_timestamp));
                                                    $days_diff = $diff->format('%a'); ?>
                                                <th style="background:#f1f1f1;">
                                                    <?php if ($days_diff >= 15) { ?>
                                                    <?php } else { ?>
                                                    <?php if (!$this->ion_auth->in_group(array('casetaker', 'onlinecenter'))) { ?>
                                                    <a class="btn btn-info btn_width btn-xs addPrescription"
                                                        href="prescription/addPrescriptionViewByDoctor?id=<?php echo $appointment->patient; ?>&appointment=<?php echo $appointment->id; ?>">

                                                        <i class="fa fa-plus-circle"> </i>
                                                        <?php echo lang('add_prescription'); ?>
                                                    </a>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </th>

                                            </tr>

                                            <?php $app_prescriptions = $this->prescription_model->getPrescriptionByAppointmentId($appointment->id); ?>




                                        <tbody>
                                            <?php foreach ($app_prescriptions as $prescription) { ?>
                                            <tr class="">
                                            <tr>
                                                <?php $user_info = $this->patient_model->getUserById($prescription->user);
                                                    $user_group_id = $this->patient_model->getGroupByUserId($user_info->id)->group_id;
                                                    $user_group_info = $this->patient_model->getGroupNameById($user_group_id);
                                                    ?>

                                                <?php if ($user_group_info->name == 'Doctor') {
                                                        $doctor_ion_id = $this->ion_auth->get_user_id();
                                                        $doctorId = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;
                                                        $appointment_info = $this->appointment_model->getAppointmentById($prescription->appointment_id);
                                                        $board_doctors = $appointment_info->doctor . ',' . $appointment_info->board_doctor;
                                                        $board_doctor = explode(',', $board_doctors);
                                                        $treated_status = $prescription->treated_status;
                                                        $treated_status = explode(',', $treated_status);
                                                        $doctor_info = $this->doctor_model->getDoctorByIonUserId($prescription->user);
                                                        $hospital_info = $this->hospital_model->getHospitalById($doctor_info->hospital_id);
                                                        $hospital_category_info = $this->hospital_model->getHospitalCategoryById($hospital_info->category)
                                                    ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $hospital_category_info->name; ?> |
                                                    <?php echo $hospital_info->name; ?> |
                                                    <?php echo $doctor_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'onlinecenter') {
                                                        $onlinecenter_info = $this->onlinecenter_model->getOnlinecenterByIonUserId($prescription->user);
                                                    ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $onlinecenter_info->name; ?> |
                                                    <?php echo $onlinecenter_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'casetaker') {
                                                        $casetaker_info = $this->casetaker_model->getCasetakerByIonUserId($prescription->user);
                                                        $onlinecenter_info = $this->onlinecenter_model->getOnlinecenterById($casetaker_info->onlinecenter_id);
                                                    ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $onlinecenter_info->name; ?> |
                                                    <?php echo $casetaker_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'Patient') {
                                                        $patient_info = $this->patient_model->getPatientByIonUserId($prescription->user);
                                                    ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $patient_info->name; ?> |
                                                    <?php echo $patient_info->phone; ?></td>

                                                <?php } else { ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?></td>
                                                <?php } ?>
                                            </tr>

                                            <tr>
                                                <td colspan="6"><?php echo date('m/d/Y', $prescription->date); ?> </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"><?php
                                                                    $doctor_details = $this->doctor_model->getDoctorById($prescription->doctor);
                                                                    //                                                        $doctor_details = $this->doctor_model->getDoctorByOnlinecenter($prescription->doctor);
                                                                    if (!empty($doctor_details)) {
                                                                        $prescription_doctor = $doctor_details->name;
                                                                    } else {
                                                                        $prescription_doctor = '';
                                                                    }
                                                                    echo $prescription_doctor;
                                                                    ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"><?php
                                                                    if (!empty($prescription->medicine)) {
                                                                        $medicine = explode('###', $prescription->medicine);

                                                                        foreach ($medicine as $key => $value) {
                                                                            $medicine_id = explode('***', $value);
                                                                            if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                                                                                $medicine_details = $this->medicine_model->getMedicineByIdByOnlinecenter($medicine_id[0]);
                                                                            } else {
                                                                                $medicine_details = $this->medicine_model->getMedicineById($medicine_id[0]);
                                                                            }

                                                                            // if (!empty($medicine_details)) {
                                                                            // $medicine_name_with_dosage = $medicine_details->name . ' -' . $medicine_id[1];
                                                                            $medicine_name_with_dosage = $medicine_id[1] . ' -' . $medicine_id[2]  . ' | ' . $medicine_id[3] . '<br>';
                                                                            rtrim($medicine_name_with_dosage, ',');
                                                                            echo '<p>' . $medicine_name_with_dosage . '</p>';
                                                                            // }
                                                                        }
                                                                    }
                                                                    ?></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6">
                                                    <?php if ($appointment_info->medical_board_type != 'Single Doctor') { 
                                                             if (in_array($doctorId, $treated_status)) {?>
                                                    <strong class="success"> Done </strong>
                                                    <?php }} ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <?php echo $prescription->medicine_collection_type_name; ?></td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" class="no-print prescription_button">
                                                    <a class="btn btn-info btn-xs btn_width"
                                                        href="prescription/viewPrescription?id=<?php echo $prescription->id; ?>"><i
                                                            class="fa fa-eye"> <?php echo lang('view'); ?> </i></a>

                                                    <?php if ($this->ion_auth->in_group(array('Doctor', 'admin'))) { ?>
                                                    <?php  if($user_ion_id == $prescription->user) { ?>
                                                    <a type="button" class="btn btn-info btn-xs btn_width"
                                                        data-toggle="modal"
                                                        href="prescription/editPrescriptionByDoctor?id=<?php echo $prescription->id; ?>"><i
                                                            class="fa fa-edit"></i> <?php echo lang('edit'); ?></a>
                                                    <?php } ?>
                                                    <a type="" class="btn btn-info btn-xs btn_width" title="Copy"
                                                        href="prescription/copyPrescription?id=<?php echo $prescription->id ?>&redirect_link=<?php echo "text"; ?>"><i
                                                            class="fa fa-copy"> Copy</i></a>
                                                    <?php } ?>
                                                    <a class="btn btn-info btn-xs btn_width"
                                                        title="<?php echo lang('print'); ?>"
                                                        href="prescription/viewPrescriptionPrint?id=<?php echo $prescription->id; ?>"
                                                        target="_blank"> <i class="fa fa-print"></i>
                                                        <?php echo lang('print'); ?></a>
                                                    <?php
                                                        if ($this->ion_auth->in_group('Doctor')) {
                                                            $current_user = $this->ion_auth->get_user_id();
                                                            $doctor_table_id = $this->doctor_model->getDoctorByIonUserId($current_user)->id;
                                                            if ($prescription->doctor == $doctor_table_id) {
                                                        ?>

                                                    <a class="btn btn-info btn-xs btn_width delete_button"
                                                        href="prescription/deleteByDoctor?id=<?php echo $prescription->id; ?>"><i
                                                            class="fa fa-trash"></i> </a>
                                                    <?php
                                                            }
                                                        }
                                                        ?>

                                                    <a style="margin-left: 2px;"
                                                        class="btn btn-warning btn-xs btn_width download"
                                                        title="download"
                                                        href="prescription/download?id=<?php echo $prescription->id; ?>"
                                                        target="_blank" id="download"><i
                                                            class="fa fa-file-download"></i> download
                                                    </a>
                                                    <?php if ($appointment_info->medical_board_type != 'Single Doctor') { 
                                                             if (in_array($doctorId, $board_doctor)) {?>
                                                    <a style="margin-top: -15px;"
                                                        href="prescription/changeStatus?id=<?php echo $prescription->id; ?>"
                                                        class="btn btn-info btn-xs detailsbutton inffo" title="Done"
                                                        data-id="<?php echo $prescription->id; ?>">Done</a>
                                                    <?php }} ?>
                                                </td>
                                            </tr>



                                            </tr>
                                            <?php } ?>
                                        </tbody>





                                        <?php } ?>
                                        </thead>
                                    </table>
                                </div>
                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered">
                                        <!-- <thead>
                                            <tr>

                                                <th><?php echo lang('date'); ?></th>
                                                <th><?php echo lang('doctor'); ?></th>
                                                <th><?php echo lang('medicine'); ?></th>
                                                <th> <?php echo lang('status'); ?> </th>
                                                <th> <?php echo lang('shipping'); ?> <?php echo lang('method'); ?></th>
                                                <th class="no-print"><?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead> -->
                                        <tbody>
                                            <?php foreach ($prescriptions as $prescription) {
                                                if (empty($prescription->appointment_id)) { ?>
                                            <tr class="">
                                            <tr>
                                                <th style="background:#f1f1f1;" colspan="5">
                                                    <?php echo 'Prescription Without Appointment'; ?></th>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><?php echo date('m/d/Y', $prescription->date); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><?php
                                                                        $doctor_details = $this->doctor_model->getDoctorById($prescription->doctor);
                                                                        //                                                        $doctor_details = $this->doctor_model->getDoctorByOnlinecenter($prescription->doctor);
                                                                        if (!empty($doctor_details)) {
                                                                            $prescription_doctor = $doctor_details->name;
                                                                        } else {
                                                                            $prescription_doctor = '';
                                                                        }
                                                                        echo $prescription_doctor;
                                                                        ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><?php
                                                                        if (!empty($prescription->medicine)) {
                                                                            $medicine = explode('###', $prescription->medicine);

                                                                            foreach ($medicine as $key => $value) {
                                                                                $medicine_id = explode('***', $value);
                                                                                if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                                                                                    $medicine_details = $this->medicine_model->getMedicineByIdByOnlinecenter($medicine_id[0]);
                                                                                } else {
                                                                                    $medicine_details = $this->medicine_model->getMedicineById($medicine_id[0]);
                                                                                }

                                                                                if (!empty($medicine_details)) {
                                                                                    $medicine_name_with_dosage = $medicine_details->name . ' -' . $medicine_id[1];
                                                                                    $medicine_name_with_dosage = $medicine_name_with_dosage . ' | ' . $medicine_id[3] . '<br>';
                                                                                    rtrim($medicine_name_with_dosage, ',');
                                                                                    echo '<p>' . $medicine_name_with_dosage . '</p>';
                                                                                }
                                                                            }
                                                                        }
                                                                        ?></td>
                                            </tr>

                                            <tr>
                                                <td colspan="5"><?php echo $prescription->shipping_status; ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <?php echo $prescription->medicine_collection_type_name; ?></td>
                                            </tr>

                                            <tr>
                                                <td colspan="5" class="no-print prescription_button">
                                                    <a class="btn btn-info btn-xs btn_width"
                                                        href="prescription/viewPrescription?id=<?php echo $prescription->id; ?>"><i
                                                            class="fa fa-eye"> <?php echo lang('view'); ?> </i></a>

                                                    <?php if ($this->ion_auth->in_group(array('Doctor', 'admin'))) { ?>
                                                    <a type="button" class="btn btn-info btn-xs btn_width"
                                                        data-toggle="modal"
                                                        href="prescription/editPrescriptionByDoctor?id=<?php echo $prescription->id; ?>"><i
                                                            class="fa fa-edit"></i> <?php echo lang('edit'); ?></a>
                                                    <a type="" class="btn btn-info btn-xs btn_width" title="Copy"
                                                        href="prescription/copyPrescription?id=<?php echo $prescription->id ?>&redirect_link=<?php echo "text"; ?>"><i
                                                            class="fa fa-copy"> Copy</i></a>
                                                    <?php } ?>
                                                    <a class="btn btn-info btn-xs btn_width"
                                                        title="<?php echo lang('print'); ?>"
                                                        href="prescription/viewPrescriptionPrint?id=<?php echo $prescription->id; ?>"
                                                        target="_blank"> <i class="fa fa-print"></i>
                                                        <?php echo lang('print'); ?></a>
                                                    <?php
                                                            if ($this->ion_auth->in_group('Doctor')) {
                                                                $current_user = $this->ion_auth->get_user_id();
                                                                $doctor_table_id = $this->doctor_model->getDoctorByIonUserId($current_user)->id;
                                                                if ($prescription->doctor == $doctor_table_id) {
                                                            ?>

                                                    <a class="btn btn-info btn-xs btn_width delete_button"
                                                        href="prescription/deleteByDoctor?id=<?php echo $prescription->id; ?>"><i
                                                            class="fa fa-trash"></i> </a>
                                                    <?php
                                                                }
                                                            }
                                                            ?>

                                                    <a style="margin-left: 2px;"
                                                        class="btn btn-warning btn-xs btn_width download"
                                                        title="download"
                                                        href="prescription/download?id=<?php echo $prescription->id; ?>"
                                                        id="download"><i class="fa fa-file-download"></i> download
                                                    </a>
                                                    <?php if ($appointment_info->medical_board_type != 'Single Doctor') { 
                                                                 if (in_array($doctorId, $board_doctor)) { ?>
                                                    <a style="margin-top: -15px;"
                                                        href="prescription/changeStatus?id=<?php echo $prescription->id; ?>"
                                                        onclick="return confirm('Are you sure you want to change status');"
                                                        class="btn btn-info btn-xs detailsbutton inffo" title="Done"
                                                        data-id="<?php echo $prescription->id; ?>">Done</a>
                                                    <?php }} ?>
                                                </td>
                                            </tr>
                                            </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="lab" class="tab-pane">
                            <div class="">
                                <?php if ($this->ion_auth->in_group(array('admin', 'Doctor', 'casetaker', 'onlinecenter'))) { ?>
                                <div class=" no-print">
                                    <a class="btn btn-info btn_width btn-xs"
                                        href="lab/addLabViewByDoctor?id=<?php echo $patient->id; ?>">
                                        <i class="fa fa-plus-circle"> </i> <?php echo lang('add_new'); ?>
                                    </a>
                                </div>
                                <?php } ?>
                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('id'); ?></th>
                                                <th><?php echo lang('date'); ?></th>
                                                <th><?php echo lang('doctor'); ?></th>
                                                <th class="no-print"><?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($labs as $lab) { ?>
                                            <tr class="">
                                                <td><?php echo $lab->id; ?></td>
                                                <td><?php echo date('m/d/Y', $lab->date); ?></td>
                                                <td>
                                                    <?php
                                                        $doctor_details = $this->doctor_model->getDoctorById($lab->doctor);
                                                        $doctor_details = $this->doctor_model->getDoctorByOnlinecenter($lab->doctor);
                                                        if (!empty($doctor_details)) {
                                                            $lab_doctor = $doctor_details->name;
                                                        } else {
                                                            $lab_doctor = '';
                                                        }
                                                        echo $lab_doctor;
                                                        ?>
                                                </td>
                                                <td class="no-print">
                                                    <?php if ($this->ion_auth->in_group(array('admin', 'Doctor', 'onlinecenter', 'casetaker'))) { ?>
                                                    <a class="btn btn-info btn-xs btn_width"
                                                        href="lab/editLabByDoctor?id=<?php echo $lab->id; ?>"><i
                                                            class="fa fa-edit"></i> <?php echo lang('edit'); ?></a>
                                                    <?php } ?>
                                                    <a class="btn btn-info btn-xs btn_width"
                                                        href="lab/invoice?id=<?php echo $lab->id; ?>"><i
                                                            class="fa fa-eye"> <?php echo lang('report'); ?> </i></a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>



                        <div id="profile" class="tab-pane">
                            <div class="">
                                <?php if (!$this->ion_auth->in_group(array(''))) { ?>
                                <div class=" no-print">
                                    <!-- <div class="btn-group pull-left">
                                            <a class="btn btn-info btn_width btn-xs" data-toggle="modal" href="#myModal1">
                                                <i class="fa fa-plus-circle"> </i> <?php echo lang('add_file'); ?>
                                            </a>
                                        </div> -->
                                </div>

                                <?php } ?>

                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>


                                            <?php foreach ($appointments as $appointment) { ?>
                                            <tr class="">

                                                <th style="background:#f1f1f1;">
                                                    <?php echo date('d-m-Y', $appointment->date); ?></th>
                                                <th style="background:#f1f1f1;"><?php echo $appointment->time_slot; ?>
                                                </th>
                                                <th style="background:#f1f1f1;">
                                                    <?php
                                                        $doctor_details = $this->doctor_model->getDoctorById($appointment->doctor);
                                                        $doctor_details = $this->doctor_model->getDoctorByOnlinecenter($appointment->doctor);
                                                        if (!empty($doctor_details)) {
                                                            $appointment_doctor = $doctor_details->name;
                                                        } else {
                                                            $appointment_doctor = '';
                                                        }
                                                        echo $appointment_doctor;
                                                        ?>
                                                </th>
                                                <th style="background:#f1f1f1;"><?php
                                                                                    if ($appointment->status == 'Pending Confirmation') {
                                                                                        $appointment_status = lang('pending_confirmation');
                                                                                    } elseif ($appointment->status == 'Confirmed') {
                                                                                        $appointment_status = lang('confirmed');
                                                                                    } elseif ($appointment->status == 'Treated') {
                                                                                        $appointment_status = lang('treated');
                                                                                    } elseif ($appointment->status == 'Cancelled') {
                                                                                        $appointment_status = lang('cancelled');
                                                                                    } elseif ($appointment->status == 'Requested') {
                                                                                        $appointment_status = lang('requested');
                                                                                    }
                                                                                    echo $appointment_status;
                                                                                    ?></th>
                                                <?php $timestamp = $appointment->date;
                                                    $current_timestamp = time();
                                                    $diff = date_diff(date_create('@' . $timestamp), date_create('@' . $current_timestamp));
                                                    $days_diff = $diff->format('%a'); ?>
                                                <th style="background:#f1f1f1;">
                                                    <?php if ($days_diff >= 15) { ?>
                                                    <?php } else { ?>
                                                    <a class="btn btn-info btn_width btn-xs addFile filee"
                                                        data-id="<?php echo $appointment->id; ?>" data-toggle="modal"
                                                        href="#myModal1">
                                                        <i class="fa fa-plus-circle"> </i>
                                                        <?php echo lang('add_file'); ?>
                                                    </a>
                                                    <?php } ?>
                                                </th>
                                            </tr>

                                            <?php $documents = $this->patient_model->getDocumentByAppointmentId($appointment->id); ?>


                                        <tbody>
                                            <tr>

                                                <?php
                                                foreach ($documents as $patient_material) {
                                                    if ($patient_material->folder == '') {
                                                ?>
                                            <tr>
                                                <?php $user_info = $this->patient_model->getUserById($patient_material->user);
                                                        $user_group_id = $this->patient_model->getGroupByUserId($user_info->id)->group_id;
                                                        $user_group_info = $this->patient_model->getGroupNameById($user_group_id);
                                                ?>

                                                <?php if ($user_group_info->name == 'Doctor') {
                                                            $doctor_info = $this->doctor_model->getDoctorByIonUserId($patient_material->user);
                                                            $hospital_info = $this->hospital_model->getHospitalById($doctor_info->hospital_id);
                                                            $hospital_category_info = $this->hospital_model->getHospitalCategoryById($hospital_info->category)
                                                ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $hospital_category_info->name; ?> |
                                                    <?php echo $hospital_info->name; ?> |
                                                    <?php echo $doctor_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'onlinecenter') {
                                                            $onlinecenter_info = $this->onlinecenter_model->getOnlinecenterByIonUserId($patient_material->user);
                                                ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $onlinecenter_info->name; ?> |
                                                    <?php echo $onlinecenter_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'casetaker') {
                                                            $casetaker_info = $this->casetaker_model->getCasetakerByIonUserId($patient_material->user);
                                                            $onlinecenter_info = $this->onlinecenter_model->getOnlinecenterById($casetaker_info->onlinecenter_id);
                                                ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $onlinecenter_info->name; ?> |
                                                    <?php echo $casetaker_info->phone; ?></td>
                                                <?php } elseif ($user_group_info->name == 'Patient') {
                                                            $patient_info = $this->patient_model->getPatientByIonUserId($patient_material->user);
                                                ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?>|
                                                    <?php echo $patient_info->name; ?> |
                                                    <?php echo $patient_info->phone; ?></td>

                                                <?php } else { ?>
                                                <td colspan="5"><?php echo $user_info->username; ?> |
                                                    <?php echo $user_group_info->name; ?></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="panel col-md-3 patient_material_class1">
                                                        <div class="col-md-5 pull-left">
                                                            <div class="post-info">
                                                                <a class="example-image-link"
                                                                    href="<?php echo $patient_material->url; ?>"
                                                                    data-lightbox="example-1">
                                                                    <img class="example-image"
                                                                        src="<?php echo $patient_material->url; ?>"
                                                                        alt="image-1" height="90" width="90" /></a>

                                                            </div>

                                                        </div>
                                                        <div class="col-md-7 pull-right patient_material_url">
                                                            <div class="post-info patient_material_title">
                                                                <?php
                                                                if (!empty($patient_material->title)) {
                                                                    echo $patient_material->title;
                                                                }
                                                                ?>

                                                            </div>
                                                            <div class="post-info">
                                                                <a class="btn btn-blue btn-xs btn_width"
                                                                    href="<?php echo $patient_material->url; ?>"
                                                                    download><i class="fa fa-download"></i> </a>
                                                                <?php if (!$this->ion_auth->in_group(array('', '', 'onlinecenter', 'casetaker'))) { 
                                                                     if($user_ion_id == $patient_material->user) { ?>
                                                                <a class="btn btn-info btn-xs btn_width delete_button "
                                                                    title="<?php echo lang('delete'); ?>"
                                                                    href="patient/deletePatientMaterial?id=<?php echo $patient_material->id; ?>">
                                                                    <i class="fa fa-trash"></i> </a>
                                                                <?php } }?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                                    }
                                                }
                                    ?>


                                            </tr>
                                        </tbody>




                                        <?php } ?>
                                        </thead>
                                    </table>
                                </div>
                                <div class="adv-table editable-table ">
                                    <div class="col-md-12">
                                        <?php
                                        foreach ($patient_materials as $patient_material) {
                                            if ($patient_material->folder == '') {
                                                if (empty($patient_material->appointment_id)) {
                                        ?>
                                        <div class="panel col-md-3 patient_material_class1">
                                            <div class="col-md-5 pull-left">
                                                <div class="post-info">
                                                    <a class="example-image-link"
                                                        href="<?php echo $patient_material->url; ?>"
                                                        data-lightbox="example-1">
                                                        <img class="example-image"
                                                            src="<?php echo $patient_material->url; ?>" alt="image-1"
                                                            height="90" width="90" /></a>

                                                </div>

                                            </div>
                                            <div class="col-md-7 pull-right patient_material_url">
                                                <div class="post-info patient_material_title">
                                                    <?php
                                                                if (!empty($patient_material->title)) {
                                                                    echo $patient_material->title;
                                                                }
                                                                ?>

                                                </div>
                                                <div class="post-info">
                                                    <a class="btn btn-blue btn-xs btn_width"
                                                        href="<?php echo $patient_material->url; ?>" download><i
                                                            class="fa fa-download"></i> </a>
                                                    <?php if (!$this->ion_auth->in_group(array('', '', 'onlinecenter', 'casetaker'))) { ?>
                                                    <a class="btn btn-info btn-xs btn_width delete_button "
                                                        title="<?php echo lang('delete'); ?>"
                                                        href="patient/deletePatientMaterial?id=<?php echo $patient_material->id; ?>">
                                                        <i class="fa fa-trash"></i> </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>

                                        <?php foreach ($folders as $folder) {
                                        ?>
                                        <div class="panel col-md-3 patient_material_class1">
                                            <div class="col-md-6 pull-left">


                                                <a href="patient/medicalHistoryByFolder?id=<?php echo $folder->id; ?>">
                                                    <div class="post-info">

                                                        <img class="example-image" src="uploads/folder1.png"
                                                            alt="image-1" height="100" width="100" />

                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-6 pull-right">
                                                <div class="post-info patient_material_title">
                                                    <?php
                                                        if (!empty($folder->folder_name)) {
                                                            echo $folder->folder_name;
                                                        }
                                                        ?>

                                                </div>




                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        <span class="">Action</span>

                                                    </button>
                                                    <ul class="dropdown-menu folder_div" role="menu">
                                                        <li> <a type="" class="edittbutton" data-toggle="modal"
                                                                data-id="<?php echo $folder->id; ?>"><?php echo lang('edit'); ?></a>
                                                        </li>
                                                        <li><a class=""
                                                                href="patient/deleteFolder?id=<?php echo $folder->id; ?>"
                                                                onclick="return confirm('Are you sure you want to delete this item?');"><?php echo lang('delete'); ?></a>
                                                        </li>
                                                        <li><a class="uploadbutton" data-toggle="modal"
                                                                data-id="<?php echo $folder->id; ?>">
                                                                <?php echo lang('upload'); ?> </a></li>
                                                        <li><a class=""
                                                                href="patient/medicalHistoryByFolder?id=<?php echo $folder->id; ?>"><?php echo lang('view_files'); ?></a>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>

                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="contact" class="tab-pane">
                            <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                            <div class=" no-print">
                                <a class="btn btn-info btn_width btn-xs"
                                    href="bed/addBedViewByDoctor?id=<?php echo $patient->id; ?>">
                                    <i class="fa fa-plus-circle"> </i> <?php echo lang('add_new'); ?>
                                </a>
                            </div>
                            <?php } ?>
                            <div class="">

                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('bed_id'); ?></th>
                                                <th><?php echo lang('alloted_time'); ?></th>
                                                <th><?php echo lang('discharge_time'); ?></th>
                                                <th class="no-print"><?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>



                                            <?php foreach ($beds as $bed) { ?>
                                            <tr class="">
                                                <td><?php echo $bed->bed_id; ?></td>
                                                <td><?php echo $bed->a_time; ?></td>
                                                <td><?php echo $bed->d_time; ?></td>
                                                <td class="no-print">
                                                    <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                                    <a class="btn btn-info btn-xs btn_width"
                                                        href="bed/editAllotmentByDoctor?id=<?php echo $bed->id; ?>"><i
                                                            class="fa fa-edit"></i> <?php echo lang('edit'); ?></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="timeline" class="tab-pane">
                            <div class="">
                                <div class="">
                                    <section class="panel ">
                                        <header class="panel-heading">
                                            Timeline
                                        </header>


                                        <?php
                                        if (!empty($timeline)) {
                                            krsort($timeline);
                                            foreach ($timeline as $key => $value) {
                                                echo $value;
                                            }
                                        }
                                        ?>

                                    </section>
                                </div>
                            </div>
                        </div>


                        <div id="score" class="tab-pane active">
                            <div class="">

                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th> Objective </th>
                                                <th> Subjective</th>
                                                <th> Investigation</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="addScoring">Blood Pressure </td>
                                                <td class="addScoring">Pain</td>
                                                <td class="addScoring">Blood</td>
                                            </tr>
                                            <tr>
                                                <td class="addScoring">Blood Pulse</td>
                                                <td class="addScoring">Burning </td>
                                                <td class="addScoring">Urine</td>
                                            </tr>
                                            <tr>
                                                <td class="addScoring">Size</td>
                                                <td class="addScoring">Itching</td>
                                                <td class="addScoring">USG</td>
                                            </tr>
                                            <tr>
                                                <td class="addScoring">Color</td>
                                                <td class="addScoring">General Wellness</td>
                                                <td class="addScoring">Radiology</td>
                                            </tr>
                                            <tr>
                                                <td class="addScoring">Others (Editable) </td>
                                                <td class="addScoring">Others (Editable) </td>
                                                <td class="addScoring">Others (Editable) </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6 payroll_details">
                                    <label><?php echo lang('scoring'); ?></label>
                                    <div id="scoring_div">
                                        <?php for($i = 0; $i< count($scoring); $i++) { ?>
                                        <div id="scoring-<?php echo $i; ?>">
                                            <input name="scoringName[]" class="form-control mb-1"
                                                value="<?php echo $scoring[$i]['name']; ?>"
                                                <?php if($i == 0) { ?>readonly<?php } ?>>
                                            <div class="mb-1 number_div">
                                                <input type="number" placeholder="Enter Amount" name="scoringValue[]"
                                                    class="form-control" value="<?php echo $scoring[$i]['value']; ?>">
                                                <?php if($i > 0) { ?>
                                                <button class="btn btn-danger scoring_remove"
                                                    data-id='<?php echo $id; ?>'><i class="fas fa-minus"></i></button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <button type="button"
                                        class="btn btn-success addScoring mb-1"><?php echo lang('add') ." ". lang('scoring')?></button>
                                    <input type="hidden" id="scoringCount" value="<?php count($scoring); ?>">
                                </div>

                            </div>
                        </div>




                    </div>
                </div>
            </section>

        </section>

        <section class="col-md-3">
            <header class="panel-heading clearfix">
                <div class="">
                    <?php echo lang('patient'); ?> <?php echo lang('info'); ?>
                </div>

            </header>
            <style>
            .casee {
                margin-bottom: -5px;
                margin-top: -5px;
            }

            .filee {
                margin-bottom: -5px;
                margin-top: -5px;

            }

            .addPrescription {
                margin-bottom: -5px;
                margin-top: -5px;
            }
            </style>
            <aside class="profile-nav">
                <section class="">
                    <div class="user-heading round edit_patient_div">
                        <?php if (!empty($patient->img_url)) { ?>
                        <a href="#">
                            <img src="<?php echo $patient->img_url; ?>" alt="">
                        </a>
                        <?php } ?>
                        <h1> <?php echo $patient->name; ?> </h1>
                        <p> <?php echo $patient->email; ?> </p>
                        <?php if (!$this->ion_auth->in_group(array('Patient', 'onlinecenter', 'casetaker'))) { ?>
                        <button type="button" class="btn btn-info btn-xs btn_width editPatient"
                            title="<?php echo lang('edit'); ?>" data-toggle="modal"
                            data-id="<?php echo $patient->id; ?>"><i class="fa fa-edit"> </i>
                            <?php echo lang('edit'); ?></button>
                        <?php } ?>
                    </div>

                    <ul class="nav nav-pills nav-stacked">

                        <li> <?php echo lang('patient_id'); ?> <span
                                class="label pull-right r-activity"><?php echo $patient->patient_id; ?></span></li>
                        <li> <?php echo lang('gender'); ?><span
                                class="label pull-right r-activity"><?php echo $patient->sex; ?></span></li>
                        <li> <?php echo lang('birth_date'); ?><span
                                class="label pull-right r-activity"><?php echo $patient->birthdate; ?></span></li>
                        <li> <?php echo lang('age'); ?> <span class="label pull-right r-activity">
                                <?php
                                if (!empty($patient->birthdate)) {
                                    if (!empty($patient)) {
                                        $bday = new DateTime($patient->birthdate); // Your date of birth
                                        $today = new Datetime(date('m.d.y'));
                                        $diff = $today->diff($bday);
                                        printf(' %d years, %d months, %d days', $diff->y, $diff->m, $diff->d);
                                    }
                                } else {
                                    $age = explode('-', $patient->age);
                                    echo $age[0] . ' Years ' . $age[1] . ' Months ' . $age[2] . ' Days';
                                }
                                ?>
                            </span></li>
                        <li> <?php echo lang('phone'); ?><span
                                class="label pull-right r-activity"><?php echo $patient->phone; ?></span></li>
                        <li class=""> <?php echo lang('email'); ?><span
                                class="label pull-right r-activity"><?php echo $patient->email; ?></span></li>
                        <li class="address_bar"> <?php echo lang('address'); ?><span
                                class="pull-right address_bar"><?php echo $patient->address; ?></span></li>

                    </ul>

                </section>
            </aside>


        </section>

    </section>
    <!-- page end-->
</section>
</section>
<!--main content end-->
<!--footer start-->


<?php
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

<!-- Add Patient Material Modal-->
<div class="modal fade" id="myModalf" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('add'); ?> <?php echo lang('folder'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" action="patient/addNewFolder" class="clearfix" method="post"
                    enctype="multipart/form-data">


                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('name'); ?></label>
                        <input type="text" class="form-control" name="folder_name" placeholder="">
                    </div>
                    <input type="hidden" name="patient" value='<?php echo $patient->id; ?>'>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-info pull-right">
                            <?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="myModalfe" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('edit'); ?> <?php echo lang('folder'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="editFolderForm" action="patient/addNewFolder" class="clearfix" method="post"
                    enctype="multipart/form-data">


                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('name'); ?></label>
                        <input type="text" class="form-control" name="folder_name" placeholder="">
                    </div>
                    <input type="hidden" name="patient" value='<?php echo $patient->id; ?>'>
                    <input type="hidden" name="id" value='<?php echo $folder->id; ?>'>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-info pull-right">
                            <?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Add Patient Material Modal-->
<div class="modal fade" id="myModal1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('add'); ?> <?php echo lang('files'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" action="patient/addPatientMaterial" class="clearfix row" method="post"
                    enctype="multipart/form-data">
                    <?php if ($this->ion_auth->in_group(array('casetaker', 'onlinecenter'))) { ?>
                    <input type="hidden" name="hospital_id" value='<?php echo $patient->hospital_id; ?>'>
                    <input type="hidden" name="onlinecenter_id" value="<?php echo $onlinecenter_id ?>">
                    <input type="hidden" name="casetaker_id" value="<?php echo $casetaker_id ?>">
                    <?php } ?>
                    <!-- <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('appointment'); ?></label>
                        <select class="form-control js-example-basic-single" id="" name="appointment_id" value=''>
                            <option>Select......</option>
                            <?php foreach ($appointments as $appointment) { ?>
                                <option value="<?php echo $appointment->id; ?>" <?php

                                                                                if ($medical_history->appointment == $appointment->id) {
                                                                                    echo 'selected';
                                                                                }

                                                                                ?>> <?php echo date('d-m-Y', $appointment->date); ?> - <?php echo $appointment->time_slot; ?> </option>
                            <?php } ?>
                        </select>
                    </div> -->

                    <input type="hidden" id="file_appointment_id" name="appointment_id" value="">
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"> <?php echo lang('title'); ?></label>
                        <input type="text" class="form-control" name="title" placeholder="">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"> <?php echo lang('file'); ?></label>
                        <input type="file" name="img_url">
                    </div>

                    <input type="hidden" name="patient" value='<?php echo $patient->id; ?>'>

                    <div class="form-group col-md-12">
                        <button type="submit" name="submit" class="btn btn-info pull-right">
                            <?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="myModalff" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('add'); ?> <?php echo lang('files'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="uploadFileForm" action="patient/addPatientMaterial" class="clearfix" method="post"
                    enctype="multipart/form-data">


                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"> <?php echo lang('title'); ?></label>
                        <input type="text" class="form-control" name="title" placeholder="">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"> <?php echo lang('file'); ?></label>
                        <input type="file" name="img_url">
                    </div>
                    <input type="hidden" name="hidden_folder_name" value="<?php echo $folder->folder_name; ?>" />
                    <input type="hidden" name="patient" value='<?php echo $patient->id; ?>'>
                    <input type="hidden" name="folder" value='<?php echo $folder->id; ?>'>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-info pull-right">
                            <?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Add Patient Modal-->


<!-- Add Medical History Modal-->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('add_case'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" action="patient/addMedicalHistory" class="clearfix row" method="post"
                    enctype="multipart/form-data">
                    <!-- <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('appointment'); ?></label>
                        <select class="form-control js-example-basic-single" id="" name="appointment_id" value=''>
                            <option>Select......</option>
                            <?php foreach ($appointments as $appointment) { ?>
                                <option value="<?php echo $appointment->id; ?>" <?php

                                                                                if ($medical_history->appointment == $appointment->id) {
                                                                                    echo 'selected';
                                                                                }

                                                                                ?>> <?php echo date('d-m-Y', $appointment->date); ?> - <?php echo $appointment->time_slot; ?> </option>
                            <?php } ?>
                        </select>
                    </div> -->

                    <input type="hidden" id="case_appointment_id" name="appointment_id" value="">
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?></label>
                        <input type="text" class="form-control form-control-inline input-medium default-date-picker"
                            name="date" value='<?php echo $currentDate; ?>' placeholder="" readonly="">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('title'); ?></label>
                        <input type="text" class="form-control form-control-inline input-medium" name="title" value=''
                            placeholder="">
                    </div>
                    <div class="form-group col-md-12">
                        <label class=""><?php echo lang('description'); ?></label>
                        <div class="">
                            <textarea class="ckeditor form-control" id="editor" name="description" value=""
                                rows="10"></textarea>
                        </div>
                    </div>
                    <?php if ($this->ion_auth->in_group(array('casetaker', 'onlinecenter'))) { ?>
                    <input type="hidden" name="hospital_id" value='<?php echo $patient->hospital_id; ?>'>
                    <input type="hidden" name="onlinecenter_id" value="<?php echo $onlinecenter_id ?>">
                    <input type="hidden" name="casetaker_id" value="<?php echo $casetaker_id ?>">
                    <?php } ?>
                    <input type="hidden" name="patient_id" value='<?php echo $patient->id; ?>'>
                    <input type="hidden" name="id" value=''>
                    <div class="form-group col-md-12">
                        <button type="submit" name="submit"
                            class="btn btn-info submit_button pull-right">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="myModal2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('edit_case'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="medical_historyEditForm" class="clearfix row" action="patient/addMedicalHistory"
                    method="post" enctype="multipart/form-data">
                    <!-- <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('appointment'); ?></label>
                        <select class="form-control js-example-basic-single" id="" name="appointment_id" value=''>
                            <option>Select......</option>
                            <?php foreach ($appointments as $appointment) { ?>
                                <option value="<?php echo $appointment->id; ?>" <?php

                                                                                if ($medical_history->appointment == $appointment->id) {
                                                                                    echo 'selected';
                                                                                }

                                                                                ?>> <?php echo date('d-m-Y', $appointment->date); ?> - <?php echo $appointment->time_slot; ?> </option>
                            <?php } ?>
                        </select>
                    </div> -->
                    <input type="hidden" name="appointment_id" value="">
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?></label>
                        <input type="text" class="form-control form-control-inline input-medium default-date-picker"
                            name="date" value='' placeholder="" readonly="">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('title'); ?></label>
                        <input type="text" class="form-control form-control-inline input-medium" name="title" value=''
                            placeholder="">
                    </div>
                    <div class="form-group col-md-12">
                        <label class=""><?php echo lang('description'); ?></label>
                        <div class="">
                            <textarea class="ckeditor form-control editor" id="editor1" name="description" value=""
                                rows="10"></textarea>
                        </div>
                    </div>
                    <?php if ($this->ion_auth->in_group(array('casetaker', 'onlinecenter'))) { ?>
                    <input type="hidden" name="hospital_id" value='<?php echo $patient->hospital_id; ?>'>
                    <input type="hidden" name="onlinecenter_id" value="<?php echo $onlinecenter_id ?>">
                    <input type="hidden" name="casetaker_id" value="<?php echo $casetaker_id ?>">
                    <?php } ?>
                    <input type="hidden" name="patient_id" value='<?php echo $patient->id; ?>'>
                    <input type="hidden" name="id" value=''>
                    <div class="form-group col-md-12">
                        <button type="submit" name="submit"
                            class="btn btn-info submit_button pull-right">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<?php
$current_user = $this->ion_auth->get_user_id();
if ($this->ion_auth->in_group('Doctor')) {
    $doctor_id = $this->db->get_where('doctor', array('ion_user_id' => $current_user))->row()->id;
}
?>


<!-- Add Appointment Modal-->
<div class="modal fade" id="addAppointmentModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('add_appointment'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="addAppointmentForm" action="appointment/addNew" class="clearfix row" method="post"
                    enctype="multipart/form-data">



                    <?php if ($this->ion_auth->in_group(array('casetaker', 'onlinecenter'))) { ?>
                    <input type="hidden" name="superadmin" value="1">
                    <input type="hidden" name="hospital_id" value='<?php echo $patient->hospital_id; ?>'>
                    <input type="hidden" name="onlinecenter_id" value="<?php echo $onlinecenter_id ?>">
                    <input type="hidden" name="casetaker_id" value="<?php echo $casetaker_id ?>">
                    <?php } ?>
                    <div class="col-md-4 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>
                        <select class="form-control m-bot15 js-example-basic-single pos_select" name="patient" value=''>
                            <option value="">Select .....</option>
                            <option value="<?php echo $patient->id; ?>" <?php
                                                                        if (!empty($patient->id)) {
                                                                            echo 'selected';
                                                                        }
                                                                        ?>><?php echo $patient->name; ?> </option>
                        </select>
                    </div>

                    <div class="col-md-4 panel doctor_div">
                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>
                        <select class="form-control js-example-basic-single" id="adoctors" name="doctor" value=''>
                            <option>Select......</option>
                            <?php foreach ($doctors as $doctor) { ?>
                            <option value="<?php echo $doctor->id; ?>" <?php
                                                                            if (!empty($doctor_id)) {
                                                                                if ($doctor_id == $doctor->id) {
                                                                                    echo 'selected';
                                                                                }
                                                                            }
                                                                            ?>> <?php echo $doctor->name; ?> </option>
                            <?php } ?>
                        </select>
                    </div>


                    <div class="col-md-4 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>
                        <input type="text" class="form-control default-date-picker" id="date" readonly="" name="date"
                            value='' placeholder="">
                    </div>
                    <div class="col-md-12 panel" id="doctor_name">

                    </div>

                    <div class="col-md-6 panel">
                        <label class=""><?php echo lang('available_slots'); ?></label>
                        <select class="form-control m-bot15" name="time_slot" id="aslots" value=''>

                        </select>
                    </div>

                    <input type="hidden" name="redirectlink" value="med_his">

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?>
                            <?php echo lang('status'); ?></label>
                        <select class="form-control m-bot15" name="status" value=''>

                            <?php if (!$this->ion_auth->in_group('Patient')) { ?>
                            <option value="Pending Confirmation" <?php ?>> <?php echo lang('pending_confirmation'); ?>
                            </option>
                            <option value="Confirmed" <?php ?>> <?php echo lang('confirmed'); ?> </option>
                            <option value="Treated" <?php
                                                        ?>> <?php echo lang('treated'); ?> </option>
                            <option value="Cancelled" <?php ?>> <?php echo lang('cancelled'); ?> </option>
                            <?php } else { ?>
                            <option value="Requested" <?php ?>> <?php echo lang('requested'); ?> </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- <div class="col-md-6 panel">

                        <label class=""><?php echo lang('visit'); ?> <?php echo lang('description'); ?></label>

                        <select class="form-control js-example-basic-single" name="visit_description"
                            id="visit_description" value='' required="">
                            <option value="" disabled selected hidden><?php echo lang('select'); ?>
                                <?php echo lang('visit'); ?> <?php echo lang('type'); ?></option>
                            <option value="new_visit"><?php echo lang('new_visit'); ?></option>
                            <option value="old_visit"><?php echo lang('old_visit'); ?></option>
                            <option value="new_visit_with_medicine"><?php echo lang('new_visit_with_medicine'); ?>
                            </option>
                            <option value="old_visit_with_medicine"><?php echo lang('old_visit_with_medicine'); ?>
                            </option>
                        </select>
                    </div> -->
                    <div class="col-md-6 panel">

                        <label class=""><?php echo lang('visit'); ?> <?php echo lang('description'); ?> &#42;</label>

                        <select class="form-control m-bot15" name="visit_description" id="visit_description" value=''
                            required>

                        </select>

                    </div>
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>
                        <input type="text" class="form-control" name="remarks" value='' placeholder="">
                    </div>
                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('visit_type'); ?></label>
                        <div class="" id="visiting_place_list"></div>


                    </div>
                    <input type="hidden" name="total_charges" id="visit_charges" value="">
                    <input type="hidden" name="total_fee" id="total_fee" value="">
                    <input type="hidden" name="casetaker_fee" id="casetaker_fee" value="">
                    <input type="hidden" name="onlinecenter_fee" id="onlinecenter_fee" value="">
                    <input type="hidden" name="developer_fee" id="developer_fee" value="">
                    <input type="hidden" name="superadmin_fee" id="superadmin_fee" value="">
                    <input type="hidden" name="medicine_fee" id="medicine_fee" value="">
                    <input type="hidden" name="courier_fee" id="courier_fee" value="">
                    <div class="form-group col-md-12 pay_for_courier" style="margin-top: 20px; margin-bottom: 0px;">
                        <input type="checkbox" checked id="pay_for_courier" name="courier" value="pay_for_courier">
                        <label for=""> <?php echo lang('courier'); ?></label><br>
                    </div>
                    <div class="form-group col-md-12 visit_description_div">
                        <label for="exampleInputEmail1"><?php echo lang('visit'); ?>
                            <?php echo lang('charges'); ?></label>
                        <input type="number" class="form-control" name="visit_charges" id="total_charges" value=''
                            placeholder="" readonly="">
                    </div>

                    <input type="hidden" name="redirect" value='patient/medicalHistory?id=<?php echo $patient->id; ?>'>

                    <input type="hidden" name="request" value='<?php
                                                                if ($this->ion_auth->in_group(array('Patient'))) {
                                                                    echo 'Yes';
                                                                }
                                                                ?>'>

                    <!-- <div class="form-group col-md-12 visit_description_div">
                        <label for="exampleInputEmail1"><?php echo lang('visit'); ?>
                            <?php echo lang('charges'); ?></label>
                        <input type="number" class="form-control" name="visit_charges" id="visit_charges" value=''
                            placeholder="" readonly="">
                    </div> -->
                    <?php if (!$this->ion_auth->in_group(array('Nurse'))) { ?>
                    <div class="col-md-12  pay_now_div">
                        <input type="checkbox" id="pay_now_appointment" name="pay_now_appointment"
                            value="pay_now_appointment">
                        <label for=""> <?php echo lang('pay_now'); ?></label><br>
                        <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                        <span
                            class="text_paynow"><?php echo lang('if_pay_now_checked_please_select_status_to_confirmed') ?></span>
                        <?php } ?>
                    </div>
                    <?php if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Doctor', 'Receptionist'))) { ?>
                    <div class="payment_label col-md-12 hidden deposit_type">
                        <label for="exampleInputEmail1"><?php echo lang('deposit_type'); ?></label>

                        <div class="">
                            <select class="form-control m-bot15 js-example-basic-single selecttype" id="selecttype"
                                name="deposit_type" value=''>
                                <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                <option value="Cash"> <?php echo lang('cash'); ?> </option>
                                <?php } ?>
                                <option value="Aamarpay"> Card/Mobile </option>
                                <!-- <option value="Card"> <?php echo lang('card'); ?> </option> -->
                                <option value="Paytm"> Paytm/Gpay/Phonepe </option>
                            </select>
                        </div>

                    </div>
                    <?php }
                        if ($this->ion_auth->in_group(array('Patient'))) { ?>
                    <input type="hidden" name="deposit_type" value="Card">
                    <?php } ?>
                    <div class="col-md-12">
                        <?php
                            $payment_gateway = $settings->payment_gateway;
                            ?>

                        <div class="paytm">
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1">
                                    <p style="font-size: 15px;">Total amount you need to Payments made to this QR, or on
                                        <strong>9733263889</strong> number.
                                        Then fill the form below and submit.
                                    </p>
                                </label>

                            </div>
                            <div class="col-md-12 payment pad_bot">
                                <div class="payment pad_bot col-md-4">
                                    <a class="example-image-link" href="uploads/Paytm.jpg" data-lightbox="example-1">
                                        <img class="example-image" src="uploads/Paytm.jpg" alt="image-1" height="90"
                                            width="90" /></a>

                                </div>
                                <div class="payment pad_bot col-md-4">
                                    <a class="example-image-link" href="uploads/Gpay.jpg" data-lightbox="example-1">
                                        <img class="example-image" src="uploads/Gpay.jpg" alt="image-1" height="90"
                                            width="90" /></a>

                                </div>
                                <div class="payment pad_bot col-md-4">
                                    <a class="example-image-link" href="uploads/PhonePe.jpg" data-lightbox="example-1">
                                        <img class="example-image" src="uploads/PhonePe.jpg" alt="image-1" height="90"
                                            width="90" /></a>

                                </div>
                            </div>
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> Account number</label>
                                <input type="text" id="cardholder" class="form-control pay_in" name="account_number"
                                    value='' placeholder="">
                            </div>
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> Last 6 digits of Transaction ID</label>
                                <input type="text" id="cardholder" class="form-control pay_in" name="transaction_id"
                                    value='' placeholder="">
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
                                if ($payment_gateway == 'PayPal') {
                                ?>
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('card'); ?>
                                    <?php echo lang('type'); ?></label>
                                <select class="form-control m-bot15" name="card_type" value=''>

                                    <option value="Mastercard"> <?php echo lang('mastercard'); ?> </option>
                                    <option value="Visa"> <?php echo lang('visa'); ?> </option>
                                    <option value="American Express"> <?php echo lang('american_express'); ?> </option>
                                </select>
                            </div>
                            <?php } ?>
                            <?php if ($payment_gateway == 'PayPal') {
                                ?>
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('cardholder'); ?>
                                    <?php echo lang('name'); ?></label>
                                <input type="text" id="cardholder" class="form-control pay_in" name="cardholder"
                                    value='' placeholder="">
                            </div>
                            <?php } ?>
                            <?php if ($payment_gateway != 'Pay U Money' && $payment_gateway != 'Paystack') { ?>
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('card'); ?>
                                    <?php echo lang('number'); ?></label>
                                <input type="text" id="card" class="form-control pay_in" name="card_number" value=''
                                    placeholder="">
                            </div>



                            <div class="col-md-8 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('expire'); ?>
                                    <?php echo lang('date'); ?></label>
                                <input type="text" class="form-control pay_in" id="expire" data-date=""
                                    data-date-format="MM YY" placeholder="Expiry (MM/YY)" name="expire_date"
                                    maxlength="7" aria-describedby="basic-addon1" value='' placeholder="">
                            </div>
                            <div class="col-md-4 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('cvv'); ?> </label>
                                <input type="text" class="form-control pay_in" id="cvv" maxlength="3" name="cvv"
                                    value='' placeholder="">
                            </div>
                            <?php
                                }
                                ?>
                        </div>


                    </div>
                    <div class="col-md-12 panel">
                        <div class="col-md-3 payment_label">
                        </div>
                        <?php // if (!$this->ion_auth->in_group(array('Patient'))) { 
                            ?>
                        <div class="col-md-9">

                            <div class="form-group cashsubmit payment  right-six col-md-12">
                                <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right">
                                    <?php echo lang('submit'); ?></button>
                            </div>

                            <div class="form-group cardsubmit  right-six col-md-12 hidden">
                                <button type="submit" name="pay_now" id="submit-btn" class="btn btn-info row pull-right"
                                    <?php if ($settings->payment_gateway == 'Stripe') {
                                                                                                                                ?>onClick="stripePay(event);"
                                    <?php }
                                                                                                                                                                ?>>
                                    <?php echo lang('submit'); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php // } else { 
                        ?>
                    <!--                            <div class="form-group cashsubmit payment  right-six col-md-12">
                                                        <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right"> <?php echo lang('submit'); ?></button>
                                                    </div>
                                                    <div class="form-group cardsubmit  right-six col-md-12">
                                                        <button type="submit" name="pay_now" id="submit-btn" class="btn btn-info row pull-right" <?php if ($settings->payment_gateway == 'Stripe') {
                                                                                                                                                    ?>onClick="stripePay(event);" <?php }
                                                                                                                                                                                    ?>> <?php echo lang('submit'); ?></button>
                                                    </div>-->
                    <?php // } 
                        ?>
                    <?php } else { ?>
                    <div class="form-group  payment  right-six col-md-12">
                        <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right">
                            <?php echo lang('submit'); ?></button>
                    </div>
                    <?php } ?>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="editAppointmentModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('edit_appointment'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="editAppointmentForm" class="clearfix row" action="appointment/addNew"
                    method="post" enctype="multipart/form-data">

                    <input type="hidden" name="hospital_id" value=''>
                    <input type="hidden" name="onlinecenter_id" value="">
                    <input type="hidden" name="casetaker_id" value="">
                    <input type="hidden" name="superadmin" value="">
                    <div class="col-md-4 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>

                        <select class="form-control m-bot15 js-example-basic-single pos_select patient" name="patient"
                            value=''>
                            <option value="">Select .....</option>
                            <option value="<?php echo $patient->id; ?>"><?php echo $patient->name; ?> </option>
                        </select>
                    </div>

                    <div class="col-md-4 panel doctor_div1">
                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>
                        <select class="form-control m-bot15 doctor" id="adoctors1" name="doctor" value=''>
                            <option>Select......</option>
                            <?php foreach ($doctors as $doctor) { ?>
                            <option value="<?php echo $doctor->id; ?>" <?php
                                                                            if (!empty($appointment->doctor)) {
                                                                                if ($appointment->doctor == $doctor->id) {
                                                                                    echo 'selected';
                                                                                }
                                                                            }
                                                                            ?>> <?php echo $doctor->name; ?> </option>
                            <?php } ?>
                        </select>
                        </select>
                    </div>


                    <div class="col-md-4 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>
                        <input type="text" class="form-control default-date-picker" readonly="" id="date1" name="date"
                            value='' placeholder="">
                    </div>
                    <div class="col-md-12 panel" id="doctor_name1">

                    </div>

                    <div class="col-md-6 panel">
                        <label class=""><?php echo lang('available_slots'); ?></label>
                        <select class="form-control m-bot15" name="time_slot" id="aslots1" value=''>

                        </select>
                    </div>
                    <input type="hidden" name="redirectlink" value="med_his">
                    <input type="hidden" name="doctor_idd" id="doctor_idd" value="">
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?>
                            <?php echo lang('status'); ?></label>
                        <select class="form-control m-bot15" name="status" value=''>
                            <?php if (!$this->ion_auth->in_group('Patient')) { ?>
                            <option value="Pending Confirmation" <?php ?>> <?php echo lang('pending_confirmation'); ?>
                            </option>
                            <option value="Confirmed" <?php
                                                            ?>> <?php echo lang('confirmed'); ?> </option>
                            <option value="Treated" <?php
                                                        ?>> <?php echo lang('treated'); ?> </option>
                            <option value="Cancelled" <?php ?>> <?php echo lang('cancelled'); ?> </option>
                            <?php } else { ?>
                            <option value="Requested" <?php ?>> <?php echo lang('requested'); ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6 panel visit_description_div_div">

                        <label class=""><?php echo lang('visit'); ?> <?php echo lang('description'); ?></label>

                        <select class="form-control js-example-basic-single" name="visit_description"
                            id="visit_description1" value='' required="">
                            <!-- <option value="" disabled selected hidden><?php echo lang('select'); ?>
                                <?php echo lang('visit'); ?> <?php echo lang('type'); ?></option>
                            <option value="new_visit"><?php echo lang('new_visit'); ?></option>
                            <option value="old_visit"><?php echo lang('old_visit'); ?></option>
                            <option value="new_visit_with_medicine"><?php echo lang('new_visit_with_medicine'); ?>
                            </option>
                            <option value="old_visit_with_medicine"><?php echo lang('old_visit_with_medicine'); ?>
                            </option> -->
                        </select>

                    </div>
                    <div class="col-md-6 panel">
                        <label for=""> <?php echo lang('currency'); ?> </label>

                        <select class="form-control m-bot15" id="currency1" name="currency" value=''>
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
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>
                        <input type="text" class="form-control" name="remarks" value='' placeholder="">
                    </div>

                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('visit_type'); ?></label>
                        <div class="" id="visiting_place_list1"></div>
                        <!-- <div class="" id="visiting_place_list2"></div> -->


                    </div>

                    <input type="hidden" name="previous_charges" id="previous_charges1" value="">
                    <input type="hidden" name="doctor_amount" id="doctor_amount1" value="">
                    <input type="hidden" name="total_charges" id="visit_charges1" value="">
                    <input type="hidden" name="additional_fee" id="total_fee1" value="">
                    <input type="hidden" name="casetaker_fee" id="casetaker_fee1" value="">
                    <input type="hidden" name="onlinecenter_fee" id="onlinecenter_fee1" value="">
                    <input type="hidden" name="developer_fee" id="developer_fee1" value="">
                    <input type="hidden" name="superadmin_fee" id="superadmin_fee1" value="">
                    <input type="hidden" name="medicine_fee" id="medicine_fee1" value="">
                    <input type="hidden" name="courier_fee" id="courier_fee1" value="">
                    <input type="hidden" id="new_subtotal_fee2" name="appointment_subtotal2" value="">
                    <input type="hidden" name="visit_description_id" id="visit_description_id" value="">
                    <!-- <div class="form-group col-md-12 pay_for_courier1" style="margin-top: 20px; margin-bottom: 0px;"> -->
                    <!-- <input type="checkbox" checked id="pay_for_courier"  name="courier" value="pay_for_courier"> -->
                    <!-- <label for=""> <?php echo lang('courier'); ?></label><br> -->
                    <!-- </div>  -->




                    <input type="hidden" name="redirect" value='patient/medicalHistory?id=<?php echo $patient->id; ?>'>>
                    <input type="hidden" name="id" id="appointment_id" value=''>


                    <!-- <div class="form-group col-md-12 visit_description_div1">
                        <label for="exampleInputEmail1"><?php echo lang('visit'); ?>
                            <?php echo lang('charges'); ?></label>
                        <input type="number" class="form-control" name="visit_charges" id="total_charges1" value=''
                            placeholder="" readonly="">
                    </div> -->
                    <table style="width: 100%;">
                        <tr>
                            <td class="tdd">
                                <div class="col-md-12 td"> <label for="">Subtotal</label> </div>
                            </td>
                            <td class="tdd">
                                <div class="col-md-12 td" id="">
                                    <input style="border:none" type="number" class="form-control"
                                        name="appointment_subtotal" id="new_subtotal_fee1" value='' placeholder=""
                                        readonly="">
                                </div><input type="hidden" id="subtotal_fee1" name="subtotal_fee">
                            </td>
                        </tr>
                        <tr>
                            <td class="tdd">
                                <div class="col-md-12 td"><label for="">Payment gateway fee</label></div>
                            </td>
                            <td class="tdd">
                                <div class="col-md-12 td" id="gateway_fee1" style="margin-left:15px;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tdd">
                                <div class="form-group col-md-12 pay_for_courier1" style="margin-bottom: 0px;">
                            </td>
                            <td class="tdd">
                                <div class="col-md-12 td" id="shipping_fee1" style="margin-left:15px;"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tdd">
                                <div class="col-md-12 td"><label>আপনি যদি কুরিয়ারে ঔষধ না নেন তাহলে টিক চিহ্ন উঠিয়ে দিন
                                    </label> <label>If you do not take medicine in courier then remove the tick
                                        mark</label></div>
                            </td>
                            <td class="tdd">
                                <div class="col-md-12">
                            </td>
                        </tr>
                        <tr>
                            <td class="tdd">
                                <div class="col-md-1"> <input type="checkbox" checked id="terms" name="terms"
                                        value="terms" required></div>
                                <div class="col-md-11"> <label for=""> I have read and agree to the Appointment terms
                                        and conditions</label><br></div>
                            </td>
                            <td class="tdd"></td>
                        </tr>
                        <tr>
                            <td class="tdd">
                                <div class="col-md-12 td"><label for="">Total</label></div>
                            </td>
                            <td class="tdd">
                                <div class="col-md-12 td"><input style="border:none" type="number" class="form-control"
                                        name="visit_charges" id="total_charges1" value='' placeholder="" readonly="">
                                </div>
                            </td>
                        </tr>
                    </table>
                    <?php if (!$this->ion_auth->in_group(array('Nurse'))) { ?>
                    <div class="col-md-12 pay_now_div1">
                        <input type="checkbox" id="pay_now_appointment" name="pay_now_appointment"
                            value="pay_now_appointment">
                        <label for=""> <?php echo lang('pay_now'); ?></label><br>
                        <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                        <span
                            class="text_paynow"><?php echo lang('if_pay_now_checked_please_select_status_to_confirmed') ?></span>
                        <?php } ?>
                    </div>
                    <div class="col-md-12 hidden payment_status form-group">
                        <label for=""> <?php echo lang('payment'); ?> <?php echo lang('status'); ?></label><br>
                        <input type="text" class="form-control" id="pay_now_appointment"
                            name="payment_status_appointment" value="paid" readonly="">


                    </div>
                    <div class="payment_label col-md-12 hidden deposit_type1">
                        <label for="exampleInputEmail1"><?php echo lang('deposit_type'); ?></label>

                        <div class="">
                            <select class="form-control m-bot15 js-example-basic-single selecttype1" id="selecttype1"
                                name="deposit_type" value=''>
                                <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                <option value="Cash"> <?php echo lang('cash'); ?> </option>
                                <?php } ?>
                                <option value="Aamarpay"> Card/Mobile </option>
                                <!-- <option value="Card"> <?php echo lang('card'); ?> </option> -->
                                <option value="Paytm"> Paytm/Gpay/Phonepe </option>
                            </select>
                        </div>

                    </div>
                    <div class="col-md-12">
                        <?php
                            $payment_gateway = $settings->payment_gateway;
                            ?>

                        <div class="paytm1">
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1">
                                    <p style="font-size: 15px;">Total amount you need to Payments made to this QR, or on
                                        <strong>9733263889</strong> number.
                                        Then fill the form below and submit.
                                    </p>
                                </label>

                            </div>
                            <div class="col-md-12 payment pad_bot">
                                <div class="payment pad_bot col-md-4">
                                    <a class="example-image-link" href="uploads/Paytm.jpg" data-lightbox="example-1">
                                        <img class="example-image" src="uploads/Paytm.jpg" alt="image-1" height="90"
                                            width="90" /></a>

                                </div>
                                <div class="payment pad_bot col-md-4">
                                    <a class="example-image-link" href="uploads/Gpay.jpg" data-lightbox="example-1">
                                        <img class="example-image" src="uploads/Gpay.jpg" alt="image-1" height="90"
                                            width="90" /></a>

                                </div>
                                <div class="payment pad_bot col-md-4">
                                    <a class="example-image-link" href="uploads/PhonePe.jpg" data-lightbox="example-1">
                                        <img class="example-image" src="uploads/PhonePe.jpg" alt="image-1" height="90"
                                            width="90" /></a>

                                </div>
                            </div>
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> Account number</label>
                                <input type="text" id="cardholder" class="form-control pay_in" name="account_number"
                                    value='' placeholder="">
                            </div>
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> Last 6 digits of Transaction ID</label>
                                <input type="text" id="cardholder" class="form-control pay_in" name="transaction_id"
                                    value='' placeholder="">
                            </div>

                        </div>

                        <div class="card1">

                            <hr>

                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('accepted'); ?>
                                    <?php echo lang('cards'); ?></label>
                                <div class="payment pad_bot">
                                    <img src="uploads/card.png" width="100%">
                                </div>
                            </div>


                            <?php
                                if ($payment_gateway == 'PayPal') {
                                ?>
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('card'); ?>
                                    <?php echo lang('type'); ?></label>
                                <select class="form-control m-bot15" name="card_type" value=''>

                                    <option value="Mastercard"> <?php echo lang('mastercard'); ?> </option>
                                    <option value="Visa"> <?php echo lang('visa'); ?> </option>
                                    <option value="American Express"> <?php echo lang('american_express'); ?> </option>
                                </select>
                            </div>
                            <?php } ?>
                            <?php if ($payment_gateway == 'PayPal') {
                                ?>
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('cardholder'); ?>
                                    <?php echo lang('name'); ?></label>
                                <input type="text" id="cardholder1" class="form-control pay_in" name="cardholder"
                                    value='' placeholder="">
                            </div>
                            <?php } ?>
                            <?php if ($payment_gateway != 'Pay U Money' && $payment_gateway != 'Paystack') { ?>
                            <div class="col-md-12 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('card'); ?>
                                    <?php echo lang('number'); ?></label>
                                <input type="text" id="card1" class="form-control pay_in" name="card_number" value=''
                                    placeholder="">
                            </div>



                            <div class="col-md-8 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('expire'); ?>
                                    <?php echo lang('date'); ?></label>
                                <input type="text" class="form-control pay_in" id="expire1" data-date=""
                                    data-date-format="MM YY" placeholder="Expiry (MM/YY)" name="expire_date"
                                    maxlength="7" aria-describedby="basic-addon1" value='' placeholder="">
                            </div>
                            <div class="col-md-4 payment pad_bot">
                                <label for="exampleInputEmail1"> <?php echo lang('cvv'); ?> </label>
                                <input type="text" class="form-control pay_in" id="cvv1" maxlength="3" name="cvv"
                                    value='' placeholder="">
                            </div>
                            <?php
                                }
                                ?>
                        </div>


                    </div>
                    <div class="col-md-12 panel">
                        <div class="col-md-3 payment_label">
                        </div>
                        <div class="col-md-9">

                            <div class="form-group cashsubmit1 payment  right-six col-md-12">
                                <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right">
                                    <?php echo lang('submit'); ?></button>
                            </div>

                            <div class="form-group cardsubmit1  right-six col-md-12 hidden">
                                <button type="submit" name="pay_now" id="submit-btn1"
                                    class="btn btn-info row pull-right"
                                    <?php if ($settings->payment_gateway == 'Stripe') {
                                                                                                                                ?>onClick="stripePay1(event);"
                                    <?php }
                                                                                                                                                                ?>> <?php echo lang('submit'); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="form-group  payment  right-six col-md-12">
                        <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right">
                            <?php echo lang('submit'); ?></button>
                    </div>
                    <?php } ?>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Edit Event Modal-->




<!-- Edit Patient Modal-->
<div class="modal fade" id="infoModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('edit_patient'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" id="editPatientForm" action="patient/addNew" class="clearfix" method="post"
                    enctype="multipart/form-data">

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?></label>
                        <input type="text" class="form-control" name="name" value='' placeholder="">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>
                        <input type="text" class="form-control" name="email" value='' placeholder="">
                    </div>

                    <div class="form-group col-md-6">
                        <label
                            for="exampleInputEmail1"><?php echo lang('change'); ?><?php echo lang('password'); ?></label>
                        <input type="password" class="form-control" name="password" placeholder="" autocomplete="on">
                    </div>



                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>
                        <input type="text" class="form-control" name="address" value='' placeholder="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>
                        <input type="text" class="form-control" name="phone" value='' placeholder="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('sex'); ?></label>
                        <select class="form-control m-bot15" name="sex" value=''>

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

                    <div class="form-group col-md-6">
                        <label><?php echo lang('birth_date'); ?></label>
                        <input class="form-control form-control-inline input-medium default-date-picker" type="text"
                            name="birthdate" value="" placeholder="" readonly="">
                    </div>
                    <div class="form-group col-md-6">
                        <div class="">
                            <label><?php echo lang('age'); ?></label>

                        </div>
                        <div class="">
                            <div class="input-group m-bot15">
                                <input type="number" min="0" max="150" class="form-control" name="years" value=''
                                    placeholder="<?php echo lang('years'); ?>">
                                <span class="input-group-addon"><?php echo lang('y'); ?></span>
                                <input type="number" class="form-control input-group-addon" min="0" max="12"
                                    name="months" value='' placeholder="<?php echo lang('months'); ?>">
                                <span class="input-group-addon"><?php echo lang('m'); ?></span>
                                <input type="number" class="form-control input-group-addon" name="days" min="0" max="29"
                                    value='' placeholder="<?php echo lang('days'); ?>">
                                <span class="input-group-addon"><?php echo lang('d'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('blood_group'); ?></label>
                        <select class="form-control m-bot15" name="bloodgroup" value=''>
                            <?php foreach ($groups as $group) { ?>
                            <option value="<?php echo $group->group; ?>" <?php
                                                                                if (!empty($patient->bloodgroup)) {
                                                                                    if ($group->group == $patient->bloodgroup) {
                                                                                        echo 'selected';
                                                                                    }
                                                                                }
                                                                                ?>> <?php echo $group->group; ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo lang('country'); ?></label>
                        <select class="form-control selectpicker countrypicker m-bot15" data-live-search="true" value=''
                            required data-flag="true"
                            <?php if (!empty($patient->country)) { ?>data-default="<?php echo $patient->country; ?>"
                            <?php } else { ?> data-default="United States" <?php } ?> name="country"></select>
                        <!-- <input name="country" value=""> -->


                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('doctor'); ?></label>
                        <select class="form-control js-example-basic-single doctor" name="doctor" value=''>
                            <option value=""> </option>
                            <?php foreach ($doctors as $doctor) { ?>
                            <option value="<?php echo $doctor->id; ?>"><?php echo $doctor->name; ?> </option>
                            <?php } ?>
                        </select>
                    </div>



                    <div class="form-group last col-md-6">
                        <label class="control-label">Image Upload</label>
                        <div class="">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail img_class">
                                    <img src="" id="img" alt="" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail img_thumb"></div>
                                <div>
                                    <span class="btn btn-white btn-file">
                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select
                                            image</span>
                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                        <input type="file" class="default" name="img_url" />
                                    </span>
                                    <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i
                                            class="fa fa-trash"></i> Remove</a>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="form-group col-md-6">
                        <input type="checkbox" name="sms" value="sms"> <?php echo lang('send_sms') ?><br>
                    </div>

                    <input type="hidden" name="redirect" value='patient/medicalHistory?id=<?php echo $patient->id; ?>'>>

                    <input type="hidden" name="id" value=''>
                    <input type="hidden" name="p_id" value='<?php
                                                            if (!empty($patient->patient_id)) {
                                                                echo $patient->patient_id;
                                                            }
                                                            ?>'>







                    <section class="col-md-12">
                        <button type="submit" name="submit"
                            class="btn btn-info pull-right"><?php echo lang('submit'); ?></button>
                    </section>

                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>
<!-- Edit Patient Modal-->
<div class="modal fade" id="myStatusModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> Change Status<?php echo lang('status'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" id="editAppointmentStatusForm" action="appointment/updateStatus" class="clearfix"
                    method="post" enctype="multipart/form-data">

                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?>
                            <?php echo lang('status'); ?></label>
                        <select class="form-control m-bot15" name="status" value=''>
                            <option value="Pending Confirmation" <?php ?>> <?php echo lang('pending_confirmation'); ?>
                            </option>
                            <option value="Confirmed" <?php ?>> <?php echo lang('confirmed'); ?> </option>
                            <option value="Treated" <?php ?>> <?php echo lang('treated'); ?> </option>
                            <option value="Cancelled" <?php ?>> <?php echo lang('cancelled'); ?> </option>
                        </select>
                    </div>
                    <input type="hidden" name="redirect" value='patient/medicalHistory?id=<?php echo $patient->id; ?>'>
                    <input type="hidden" name="id" id="appointment_id" value=''>
                    <div class="form-group  payment  right-six col-md-12">
                        <button type="submit" name="submit" id="submit" class="btn btn-info row pull-right">
                            <?php echo lang('submit'); ?></button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<?php
if (!empty($gateway->publish)) {
    $gateway_stripe = $gateway->publish;
} else {
    $gateway_stripe = '';
}
?>


<script src="common/js/codearistos.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
var publish = "<?php echo $gateway_stripe; ?>";
</script>
<script type="text/javascript">
var select_doctor = "<?php echo lang('select_doctor'); ?>";
</script>
<script type="text/javascript">
var select_patient = "<?php echo lang('select_patient'); ?>";
</script>
<script type="text/javascript">
var language = "<?php echo $this->language; ?>";
</script>
<?php if ($this->ion_auth->in_group(array('Patient'))) { ?>
<script type="text/javascript">
var from_patient = "<?php echo 'yes'; ?>";
</script>
<?php } else { ?>
<script type="text/javascript">
var from_patient = "<?php echo 'no'; ?>";
</script>
<?php } ?>
<script src="common/extranal/js/patient/medical_history.js"></script>
<script src="common/extranal/js/appointment/appointment.js"></script>
<script src="common/extranal/js/appointment/appointment_select2.js"></script>
<script>
$(document).ready(function() {
    $("#visit_description1").change(function() {
        var id = $(this).val();
        $("#new_subtotal_fee1").empty();
        $("#gateway_fee1").empty();
        $("#visit_charges1").val(" ");
        $("#total_charges1").val(" ");
        var total_fee = $("#total_fee1").val();
        var previous_charges = $("#previous_charges1").val();
        var previous_doctor_amount = $("#doctor_amount1").val();
        var courier_fee = $("#courier_fee1").val();
        var currency = $("#currency1").val();
        var subtotal = $("#new_subtotal_fee2").val();
        $.ajax({
            url: "doctor/getDoctorVisitCharges?id=" + id,
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

                if ($("#pay_for_courier1").prop("checked") == true) {
                    var courier = courier_fee;
                } else {
                    var courier = 0;
                }
                var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
                var gateway_fee = new_subtotal * 2.5 / 100;
                $("#visit_charges1").val(visit).end();

                $("#total_charges1")
                    .val(parseFloat(visit) + parseFloat(subtotal) + parseFloat(
                        gateway_fee) + parseFloat(courier))
                    .end();
                $("#new_subtotal_fee1")
                    .val(parseFloat(new_subtotal))
                    .end();
                //  $("#gateway_fee1")
                //                 .append(parseFloat(gateway_fee))
                //                 .end();
                if (doctor_id.trim() != "") {
                    var total_doctor_amount =
                        parseFloat(visit) +
                        parseFloat(casetaker_fee) +
                        parseFloat(onlinecenter_fee);
                } else {
                    var total_doctor_amount = visit;
                }
                $("#doctor_amount1").val(total_doctor_amount).end();
            },
        });
    });
});


// edit ---------

$(document).ready(function() {
    $("#adoctors1").change(function() {
        var onlinecenter_id = $("#onlinecenter_id").val();
        var casetaker_id = $("#casetaker_id").val();
        var doctor_id = $(this).val();
        var currency = $("#currency1").val();
        $("#shipping_fee1").empty();
        $("#new_subtotal_fee2").empty();

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
                if ($("#pay_for_courier1").prop("checked") == true) {
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
                var gateway_fee = total_fee_without_courier * 2.5 / 100;
                var total_fee =
                    parseFloat(total_fee_without_courier) +
                    parseFloat(courier);

                $("#casetaker_fee1").val(casetaker_fee).end();
                $("#onlinecenter_fee1").val(onlinecenter_fee).end();
                $("#developer_fee1").val(developer_fee).end();
                $("#hospital_fee1").val(hospital_fee).end();
                $("#superadmin_fee1").val(superadmin_fee).end();
                $("#medicine_fee1").val(medicine_fee).end();
                $("#courier_fee1").val(courier_fee).end();
                $("#shipping_fee1").append(courier_fee).end();
                $("#total_fee1").val(total_fee).end();
                $('#new_subtotal_fee2').val(total_fee_without_courier).end();
                var visit_description = $("#visit_description_id").val();
                $.ajax({
                    url: "doctor/getDoctorVisitCharges?id=" + visit_description,
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

                        if (doctor_id.trim() != "") {
                            var total_doctor_amount =
                                parseFloat(visit) +
                                parseFloat(casetaker_fee) +
                                parseFloat(onlinecenter_fee);
                        } else {
                            var total_doctor_amount = visit;
                        }

                        $("#visit_charges1").val(visit).end();
                        // $("#total_charges1")
                        //   .val(parseFloat(visit) + parseFloat(total_fee))
                        //   .end();
                        $("#doctor_amount1").val(total_doctor_amount).end();
                    },
                });
            },
        });
    });
});

$(document).ready(function() {
    $("#currency1").change(function() {
        var onlinecenter_id = $("#onlinecenter_id1").val();
        var casetaker_id = $("#casetaker_id1").val();
        var doctor_id = $("#adoctors1").val();
        var currency = $("#currency1").val();
        var visit_description = $("#visit_description1").val();
        $("#shipping_fee1").empty();
        $("#new_subtotal_fee2").empty();
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
                    // if ($("#pay_for_courier").prop("checked") == true) {
                    // var courier_fee = 0;
                    // }else{
                    //   var courier_fee = response.commission.courier_fee;
                    // }
                }
                if (currency == "INR") {
                    var casetaker_fee = response.commission.casetaker_fee_rupee;
                    var onlinecenter_fee = response.commission.onlinecenter_fee_rupee;
                    var developer_fee = response.commission.developer_fee_rupee;
                    var hospital_fee = response.commission.current_hospital_rupee;
                    var superadmin_fee = response.commission.superadmin_fee_rupee;
                    var medicine_fee = response.commission.medicine_fee_rupee;
                    var courier_fee = response.commission.courier_fee_rupee;
                    // if ($("#pay_for_courier").prop("checked") == true) {
                    //   var courier_fee = 0;
                    //   }else{
                    //     var courier_fee = response.commission.courier_fee_rupee;
                    //   }
                }
                if (currency == "USD") {
                    var casetaker_fee = response.commission.casetaker_fee_dollar;
                    var onlinecenter_fee = response.commission.onlinecenter_fee_dollar;
                    var developer_fee = response.commission.developer_fee_dollar;
                    var hospital_fee = response.commission.current_hospital_dollar;
                    var superadmin_fee = response.commission.superadmin_fee_dollar;
                    var medicine_fee = response.commission.medicine_fee_dollar;
                    var courier_fee = response.commission.courier_fee_dollar;
                    // if ($("#pay_for_courier").prop("checked") == true) {
                    //   var courier_fee = 0;
                    //   }else{
                    //     var courier_fee = response.commission.courier_fee_dollar;
                    //   }
                }
                if ($("#pay_for_courier1").prop("checked") == true) {
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
                var gateway_fee = total_fee_without_courier * 2.5 / 100;
                var total_fee =
                    parseFloat(total_fee_without_courier) +
                    parseFloat(courier);

                $("#casetaker_fee1").val(casetaker_fee).end();
                $("#onlinecenter_fee1").val(onlinecenter_fee).end();
                $("#developer_fee1").val(developer_fee).end();
                $("#hospital_fee1").val(hospital_fee).end();
                $("#superadmin_fee1").val(superadmin_fee).end();
                $("#medicine_fee1").val(medicine_fee).end();
                $("#courier_fee1").val(courier_fee).end();
                $("#total_fee1").val(total_fee).end();
                $("#shipping_fee1").append(courier_fee).end();
                $('#new_subtotal_fee2').val(total_fee_without_courier).end();

                $("#new_subtotal_fee1").empty();
                $("#gateway_fee1").empty();
                var courier_fee = $("#courier_fee1").val();
                // var currency = $("#currency1").val();
                var subtotal = $("#new_subtotal_fee2").val();
                $.ajax({
                    url: "doctor/getDoctorVisitCharges?id=" + visit_description,
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
                        if ($("#pay_for_courier1").prop("checked") == true) {
                            var courier = courier_fee;
                        } else {
                            var courier = 0;
                        }
                        var new_subtotal = parseFloat(visit) + parseFloat(
                            subtotal);
                        var gateway_fee = new_subtotal * 2.5 / 100;
                        if (doctor_id.trim() != "") {
                            var total_doctor_amount =
                                parseFloat(visit) +
                                parseFloat(casetaker_fee) +
                                parseFloat(onlinecenter_fee);
                        } else {
                            var total_doctor_amount = visit;
                        }

                        $("#visit_charges1").val(visit).end();
                        $("#total_charges1")
                            .val(parseFloat(visit) + parseFloat(subtotal) +
                                parseFloat(gateway_fee) + parseFloat(courier))
                            .end();
                        $("#new_subtotal_fee1")
                            .val(parseFloat(new_subtotal))
                            .end();
                        $("#gateway_fee1")
                            .append(parseFloat(gateway_fee))
                            .end();
                        $("#doctor_amount1").val(total_doctor_amount).end();
                    },
                });
            },
        });
    });
});



$(document).ready(function() {
    $(".pay_for_courier1").on("change", "#pay_for_courier1", function() {
        var doctor_id = $("#doctor_idd").val();
        var currency = $("#currency1").val();
        var courier_fee = $("#courier_fee1").val();
        if ($(this).prop("checked") == true) {
            $.ajax({
                url: "appointment/getDoctorCommissionSettings?id=" + doctor_id,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    //   if (currency == "BDT") {
                    //     var courier_fee = response.commission.courier_fee;
                    //   }
                    //   if (currency == "INR") {
                    //     var courier_fee = response.commission.courier_fee_rupee;
                    //   }
                    //   if (currency == "USD") {
                    //     var courier_fee = response.commission.courier_fee_dollar;
                    //   }
                    var total_charge = $("#total_charges1").val();
                    var total_fee = $("#total_fee1").val();
                    var total_additional_fee =
                        parseFloat(total_fee) + parseFloat(courier_fee);
                    var new_total_fee =
                        parseFloat(total_charge) + parseFloat(courier_fee);
                    $("#total_charges1").val(new_total_fee).end();
                    $("#total_fee1").val(total_additional_fee).end();
                },
            });
        } else {
            $.ajax({
                url: "appointment/getDoctorCommissionSettings?id=" + doctor_id,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    //   if (currency == "BDT") {
                    //     var courier_fee = response.commission.courier_fee;
                    //   }
                    //   if (currency == "INR") {
                    //     var courier_fee = response.commission.courier_fee_rupee;
                    //   }
                    //   if (currency == "USD") {
                    //     var courier_fee = response.commission.courier_fee_dollar;
                    //   }
                    var total_charge = $("#total_charges1").val();
                    var total_fee = $("#total_fee1").val();
                    var total_additional_fee =
                        parseFloat(total_fee) - parseFloat(courier_fee);
                    var new_total_fee =
                        parseFloat(total_charge) - parseFloat(courier_fee);
                    $("#total_charges1").val(new_total_fee).end();
                    $("#total_fee1").val(total_additional_fee).end();
                },
            });
        }
    });
});
</script>

<script>
$(".addCase").click(function() {
    // alert('jj');
    var id = $(this).attr('data-id');
    $("#case_appointment_id").val(id).end();
});

$(".addFile").click(function() {
    // alert('jj');
    var id = $(this).attr('data-id');
    $("#file_appointment_id").val(id).end();
});
</script>
<script>
$(document).ready(function() {
    $(".table").on("click", ".statusbutton", function() {

        var iid = $(this).attr("data-id");
        $("#editAppointmentStatusForm").trigger("reset");
        $("#myStatusModal").modal("show");
        $.ajax({
            url: "appointment/editAppointmentByJason?id=" + iid,
            method: "GET",
            data: "",
            dataType: "json",
            success: function(response) {

                // Populate the form fields with the data returned from server
                $("#editAppointmentStatusForm")
                    .find('[name="id"]')
                    .val(response.appointment.id)
                    .end();

                $("#editAppointmentStatusForm")
                    .find('[name="status"]')
                    .val(response.appointment.status)
                    .end();


            },
        });
    });
});
</script>
<script>
$(document).ready(function() {
    "use strict";

    $(".flashmessage").delay(3000).fadeOut(100);
});
</script>

<script>
$(document).ready(function () {
    "use strict";


    $('.addScoring').on('click', function () {
        let count = $('#scoringCount').val();
        $('#scoring_div').append('<div id="scoring_div"><div id="scoring-' + (Number(count + 1)) + '"><input name="scoringName[]" placeholder="Enter Earning Title" class="form-control mb-1"><div class="mb-1 number_div"><input type="number" placeholder="Enter Amount" name="scoringValue[]" class="form-control"><button type="button" class="btn btn-danger scoring_remove" data-id=' + (Number(count + 1)) + '><i class="fas fa-minus"></i></button></div></div>');
        $('#scoringCount').val((Number(count + 1)));
    })

    $(document).on('click', '.scoring_remove', function () {
        let id = $(this).data('id');
        $('#scoring-' + id + '').remove();
    })



});
</script>
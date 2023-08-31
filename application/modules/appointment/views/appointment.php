<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <link href="common/extranal/css/appointment/appointment.css" rel="stylesheet">
        <style>
            .btn-default {
                background-color: #fff;
                color: #000 !important;
                margin-top: 0;
                height: 37px !important;

            }

            .tdd {
                border: 1px solid #ccc;
                width: 75%;
            }

            .td {

                padding-top: 5px;
                padding-bottom: 5px;
            }
        </style>
        <section class="panel">
            <header class="panel-heading">
                <?php echo lang('appointment'); ?>
                <?php if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) { ?>
                    <div class="clearfix no-print col-md-8 pull-right">
                        <a href="appointment/addNewView">
                            <div class="btn-group pull-right">
                                <button id="" class="btn green btn-xs">
                                    <i class="fa fa-plus-circle"></i> <?php echo lang('add_appointment'); ?>
                                </button>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="clearfix no-print col-md-8 pull-right">
                        <a href="appointment/addNewView">
                            <div class="btn-group pull-right">
                                <button id="" class="btn green btn-xs">
                                    <i class="fa fa-plus-circle"></i> <?php echo lang('add_appointment'); ?>
                                </button>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </header>

            <div class="col-md-12">
                <header class="panel-heading tab-bg-dark-navy-blueee row">
                    <ul class="nav nav-tabs col-md-8">
                        <li class="active">
                            <a data-toggle="tab" href="#all"><?php echo lang('all'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#pending"><?php echo lang('pending_confirmation'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#confirmed"><?php echo lang('confirmed'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#treated"><?php echo lang('treated'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#cancelled"><?php echo lang('cancelled'); ?></a>
                        </li>

                        <li class="">
                            <a data-toggle="tab" href="#requested"><?php echo lang('requested'); ?></a>
                        </li>

                    </ul>

                    <div class="pull-right col-md-4">
                        <div class="pull-right custom_buttonss"></div>
                    </div>

                </header>
            </div>


            <div class="">
                <div class="tab-content">
                    <div id="pending" class="tab-pane">
                        <div class="">
                            <div class="panel-body">
                                <div class="adv-table editable-table ">
                                    <div class="space15"></div>
                                    <table class="table table-striped table-hover table-bordered" id="editable-sample1">
                                        <thead>
                                            <tr>
                                                <th> <?php echo lang('id'); ?></th>
                                                <th> <?php echo lang('patient'); ?></th>
                                                <th> <?php echo lang('patient_id'); ?></th>
                                                <th> <?php echo lang('phone'); ?></th>
                                                <th> <?php echo lang('doctor'); ?></th>
                                                <th> <?php echo lang('date-time'); ?></th>
                                                <th> <?php echo lang('visit'); ?> <?php echo lang('type'); ?></th>
                                                <th> <?php echo lang('grand_total'); ?> </th>
                                                <th> <?php echo lang('paid'); ?> <?php echo lang('amount'); ?> </th>
                                                <th> <?php echo lang('due'); ?> <?php echo lang('amount'); ?> </th>
                                                <th> Bill Status</th>
                                                <th> <?php echo lang('remarks'); ?></th>
                                                <th> <?php echo lang('status'); ?></th>
                                                <th> Account number</th>
                                                <th> Transaction ID</th>
                                                <th> <?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="confirmed" class="tab-pane">
                        <div class="">
                            <div class="panel-body">
                                <div class="adv-table editable-table ">
                                    <div class="space15"></div>
                                    <table class="table table-striped table-hover table-bordered" id="editable-sample2">
                                        <thead>
                                            <tr>
                                                <th> <?php echo lang('id'); ?></th>
                                                <th> <?php echo lang('patient'); ?></th>
                                                <th> <?php echo lang('patient_id'); ?></th>
                                                 <th> Live Button</th>
                                                <th> <?php echo lang('phone'); ?></th>
                                                <th> <?php echo lang('doctor'); ?></th>
                                                <th> <?php echo lang('date-time'); ?></th>
                                                <th> <?php echo lang('visit'); ?> <?php echo lang('type'); ?></th>
                                                <th> <?php echo lang('payment'); ?> <?php echo lang('status'); ?></th>
                                                <th> <?php echo lang('remarks'); ?></th>
                                                <th> <?php echo lang('status'); ?></th>
                                                <th> Account number</th>
                                                <th> Transaction ID</th>
                                                <th> <?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>




                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="treated" class="tab-pane">
                        <div class="">
                            <div class="panel-body">
                                <div class="adv-table editable-table ">
                                    <div class="space15"></div>
                                    <table class="table table-striped table-hover table-bordered" id="editable-sample3">
                                        <thead>
                                            <tr>
                                                <th> <?php echo lang('id'); ?></th>
                                                <th> <?php echo lang('patient'); ?></th>
                                                <th> <?php echo lang('patient_id'); ?></th>
                                                <th> <?php echo lang('phone'); ?></th>
                                                <th> <?php echo lang('doctor'); ?></th>
                                                <th> <?php echo lang('date-time'); ?></th>
                                                <th> <?php echo lang('visit'); ?> <?php echo lang('type'); ?></th>
                                                <th> <?php echo lang('payment'); ?> <?php echo lang('status'); ?></th>
                                                <th> <?php echo lang('remarks'); ?></th>
                                                <th> <?php echo lang('status'); ?></th>
                                                <th> Account number</th>
                                                <th> Transaction ID</th>
                                                <th> <?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>





                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="cancelled" class="tab-pane">
                        <div class="">
                            <div class="panel-body">
                                <div class="adv-table editable-table ">
                                    <div class="space15"></div>
                                    <table class="table table-striped table-hover table-bordered" id="editable-sample4">
                                        <thead>
                                            <tr>
                                                <th> <?php echo lang('id'); ?></th>
                                                <th> <?php echo lang('patient'); ?></th>
                                                <th> <?php echo lang('patient_id'); ?></th>
                                                <th> <?php echo lang('phone'); ?></th>
                                                <th> <?php echo lang('doctor'); ?></th>
                                                <th> <?php echo lang('date-time'); ?></th>
                                                <th> <?php echo lang('visit'); ?> <?php echo lang('type'); ?></th>
                                                <th> <?php echo lang('payment'); ?> <?php echo lang('status'); ?></th>
                                                <th> <?php echo lang('remarks'); ?></th>
                                                <th> <?php echo lang('status'); ?></th>
                                                <th> Account number</th>
                                                <th> Transaction ID</th>
                                                <th> <?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="all" class="tab-pane active">
                        <div class="">
                            <div class="panel-body">
                                <div class="adv-table editable-table ">

                                    <div class="space15"></div>
                                    <table class="table table-striped table-hover table-bordered" id="editable-sample5">
                                        <thead>
                                            <tr>
                                                <th> <?php echo lang('id'); ?></th>
                                                <th> <?php echo lang('patient'); ?></th>
                                                <th> <?php echo lang('patient_id'); ?></th>
                                                <th> Live Button</th>
                                                <th> <?php echo lang('phone'); ?></th>
                                                <th> <?php echo lang('doctor'); ?></th>
                                                <th> <?php echo lang('date-time'); ?></th>
                                                <th> <?php echo lang('visit'); ?> <?php echo lang('type'); ?></th>
                                                <th> <?php echo lang('grand_total'); ?> </th>
                                                <th> <?php echo lang('paid'); ?> <?php echo lang('amount'); ?> </th>
                                                <th> <?php echo lang('due'); ?> <?php echo lang('amount'); ?> </th>
                                                <th> Bill Status</th>
                                                <th> <?php echo lang('remarks'); ?></th>
                                                <th> <?php echo lang('status'); ?></th>
                                                <th> Account number</th>
                                                <th> Transaction ID</th>
                                                <th> <?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="requested" class="tab-pane">
                        <div class="">
                            <div class="panel-body">
                                <div class="adv-table editable-table ">
                                    <div class="space15"></div>
                                    <table class="table table-striped table-hover table-bordered" id="editable-sample6">
                                        <thead>
                                            <tr>
                                                <th> <?php echo lang('id'); ?></th>
                                                <th> <?php echo lang('patient'); ?></th>
                                                <th> <?php echo lang('patient_id'); ?></th>
                                                <th> <?php echo lang('phone'); ?></th>
                                                <th> <?php echo lang('doctor'); ?></th>
                                                <th> <?php echo lang('date-time'); ?></th>
                                                <th> <?php echo lang('visit'); ?> <?php echo lang('type'); ?></th>
                                                <th> <?php echo lang('payment'); ?> <?php echo lang('status'); ?></th>
                                                <th> <?php echo lang('remarks'); ?></th>
                                                <th> <?php echo lang('status'); ?></th>
                                                <th> Account number</th>
                                                <th> Transaction ID</th>
                                                <th> <?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </section>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->




<!-- Add Appointment Modal-->
<!-- <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('add_appointment'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" id="addAppointmentForm" action="appointment/addNew" method="post" class="clearfix" enctype="multipart/form-data">
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>
                        <select class="form-control  pos_select" id="pos_select" name="patient" value='' required>


                        </select>
                    </div>
                    <div class="pos_client clearfix col-md-6">
                        <div class="payment pad_bot ">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('name'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_name" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot ">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('email'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_email" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot ">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('phone'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_phone" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot ">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('address'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_address" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot ">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('birth_date'); ?></label>
                            <input type="date" class="form-control" name="p_birthdate" value=''>
                        </div>

                        <div class="payment pad_bot ">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('age'); ?></label>
                            <div class="input-group m-bot15">

                                <input type="number" min="0" max="150" class="form-control" name="years" value='' placeholder="years">
                                <span class="input-group-addon"><?php echo lang('y'); ?></span>
                                <input type="number" class="form-control input-group-addon" min="0" max="12" name="months" value='' placeholder="<?php echo lang('months'); ?>">
                                <span class="input-group-addon"><?php echo lang('m'); ?></span>
                                <input type="number" class="form-control input-group-addon" name="days" min="0" max="29" value='' placeholder="<?php echo lang('days'); ?>">
                                <span class="input-group-addon"><?php echo lang('d'); ?></span>
                            </div>
                        </div>

                        <div class="payment pad_bot">
                            <label for="exampleInputEmail1"> <?php echo lang('country'); ?></label>
                            <select class="form-control selectpicker countrypicker m-bot15" data-live-search="true" data-flag="true" <?php if (!empty($patient->id)) { ?>data-default="<?php echo $patient->country; ?>" <?php } else { ?> data-default="United States" <?php } ?> name="country"></select>
                        </div>
                        <div class="payment pad_bot">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('gender'); ?></label>
                            <select class="form-control pay_in" name="p_gender" value=''>

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
                    <div class="col-md-6 panel doctor_div">
                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>
                        <select class="form-control" id="adoctors" name="doctor" value=''>

                        </select>
                        <?php if ($this->ion_auth->in_group(array('Doctor'))) {
                            $current_user = $this->ion_auth->get_user_id();
                            $doctor_id = $this->db->get_where('doctor', array('ion_user_id' => $current_user))->row()->id;
                        } ?>
                        <input type="hidden" name="doctor_id" id="doctor_id" value="<?php echo $doctor_id ?>">
                    </div>
                    <div class="col-md-12 panel" id="doctor_name">

                    </div>

                    <div class="col-md-12 panel">

                        <label for="exampleInputEmail1"> Board <?php echo lang('doctor'); ?></label>




                        <select class="form-control js-example-basic-single" multiple="multiple" id="" name="board_doctor[]" value=''>

                            <option value=""><?php echo lang('select_doctor'); ?></option>
                            <?php foreach ($b_doctors as $doctor) { ?>
                                <option value="<?php echo $doctor->id; ?>" <?php
                                                                            if (!empty($appointment->id)) {
                                                                                $board_doctor = explode(',', $appointment->board_doctor);
                                                                                foreach ($board_doctor as $key => $value) {
                                                                                    if ($doctor->id == $value) {
                                                                                        echo 'selected';
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>>

                                    <?php $hospital_details = $this->hospital_model->getHospitalById($doctor->hospital_id);
                                    $hospital_category = $this->hospital_model->getHospitalCategoryById($hospital_details->category);
                                    ?>
                                    <?php echo $doctor->name; ?> / <?php echo $hospital_category->name; ?> / <?php echo $hospital_details->name; ?>
                                </option>
                            <?php } ?>
                        </select>


                    </div>
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>
                        <input type="text" class="form-control default-date-picker" id="date" name="date" value='' placeholder="" onkeypress="return false;" autocomplete="off" required="">
                    </div>
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1">Available Slots</label>
                        <select class="form-control m-bot15" name="time_slot" id="aslots" value=''>

                        </select>
                    </div>
                    <input type="hidden" name="redirectlink" value="10">
                    <div class="col-md-6 panel">

                        <label class=""><?php echo lang('visit'); ?> <?php echo lang('description'); ?> &#42;</label>

                        <select class="form-control m-bot15" name="visit_description" id="visit_description" value='' required>

                        </select>

                    </div>

                   

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?></label>
                        <select class="form-control m-bot15" name="status" value=''>
                            <option value="Pending Confirmation" <?php
                                                                    ?>> <?php echo lang('pending_confirmation'); ?> </option>
                            <option value="Confirmed" <?php
                                                        ?>> <?php echo lang('confirmed'); ?> </option>
                            <option value="Treated" <?php
                                                    ?>> <?php echo lang('treated'); ?> </option>
                            <option value="Cancelled" <?php
                                                        ?>> <?php echo lang('cancelled'); ?> </option>
                        </select>
                    </div>
                    <div class="col-md-6 panel">
                        <label for=""> <?php echo lang('currency'); ?> </label>

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
                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>
                        <input type="text" class="form-control" name="remarks" value='' placeholder="">
                    </div>
                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('visit_type'); ?></label>
                        <div class="" id="visiting_place_list"></div>


                    </div>
                    <input type="hidden" name="doctor_amount" id="doctor_amount" value="">
                    <input type="hidden" name="total_charges" id="visit_charges" value="">
                    <input type="hidden" name="additional_fee" id="total_fee" value="">

                    <input type="hidden" name="casetaker_fee" id="casetaker_fee" value="">
                    <input type="hidden" name="onlinecenter_fee" id="onlinecenter_fee" value="">

                    <input type="hidden" name="hospital_fee" id="hospital_fee" value="">
                    <input type="hidden" name="developer_fee" id="developer_fee" value="">
                    <input type="hidden" name="superadmin_fee" id="superadmin_fee" value="">
                    <input type="hidden" name="medicine_fee" id="medicine_fee" value="">
                    <input type="hidden" name="courier_fee" id="courier_fee" value="">
                    <div class="form-group col-md-12 pay_for_courier" style="margin-top: 20px; margin-bottom: 0px;">
                         <table style="width: 100%;">
                            <tr>
                                <td class="tdd">
                                    <div class="col-md-12 td"> <label for="">Subtotal</label> </div>
                                </td>
                                <td class="tdd">
                                    <div class="col-md-12 td" id="">
                                        <input style="border:none" type="number" class="form-control" name="appointment_subtotal" id="new_subtotal_fee" value='' placeholder="" readonly="">
                                    </div><input type="hidden" id="subtotal_fee" name="subtotal_fee">
                                </td>
                            </tr>
                            <tr>
                                <td class="tdd">
                                    <div class="col-md-12 td"><label for="">Payment gateway fee</label></div>
                                </td>
                                <td class="tdd">
                                    <div class="col-md-12 td" id="gateway_fee" style="margin-left:15px;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tdd">
                                    <div class="col-md-1"> <input type="checkbox" checked id="pay_for_courier" name="pay_for_courier" value="pay_for_courier"></div>
                                    <div class="col-md-11"> <label for=""> <?php echo lang('courier'); ?></label><br></div>
                                </td>
                                <td class="tdd">
                                    <div class="col-md-12 td" id="shipping_fee" style="margin-left:15px;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tdd">
                                    <div class="col-md-12 td"><label>আপনি যদি কুরিয়ারে ঔষধ না নেন তাহলে টিক চিহ্ন উঠিয়ে দিন </label> <label>If you do not take medicine in courier then remove the tick mark</label></div>
                                </td>
                                <td class="tdd">
                                    <div class="col-md-12">
                                </td>
                            </tr>
                            <tr>
                                <td class="tdd">
                                    <div class="col-md-1"> <input type="checkbox" id="terms" name="terms" value="terms" required></div>
                                    <div class="col-md-11"> <label for=""> I have read and agree to the Appointment <a href="frontend/privacyPolicy?id=1" target="_blank">terms and conditions</a></label><br></div>
                                </td>
                                <td class="tdd"></td>
                            </tr>
                            <tr>
                                <td class="tdd">
                                    <div class="col-md-12 td"><label for="">Total</label></div>
                                </td>
                                <td class="tdd">
                                    <div class="col-md-12 td"><input style="border:none" type="number" class="form-control" name="visit_charges" id="total_charges" value='' placeholder="" readonly=""></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php if (!$this->ion_auth->in_group(array('Nurse'))) { ?>
                        <div class="col-md-12  pay_now_div">
                            <input type="checkbox" id="pay_now_appointment" name="pay_now_appointment" value="pay_now_appointment">
                            <label for=""> <?php echo lang('pay_now'); ?></label><br>
                            <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                                <span class="text_paynow"><?php echo lang('if_pay_now_checked_please_select_status_to_confirmed') ?></span>
                            <?php } ?>
                        </div>

                        <div class="payment_label col-md-12 hidden deposit_type">
                            <label for="exampleInputEmail1"><?php echo lang('deposit_type'); ?></label>

                            <div class="">
                                <select class="form-control m-bot15 js-example-basic-single selecttype" id="selecttype" name="deposit_type" value=''>
                                    <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                        <option value="Cash"> <?php echo lang('cash'); ?> </option>
                                    <?php } ?>
                                    <option value="Aamarpay"> Card/Mobile </option>
                                    <option value="Paytm"> Paytm/Gpay/Phonepe </option>
                                </select>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <?php
                            $payment_gateway = $settings->payment_gateway;
                            ?>

                            <div class="paytm">
                                <div class="col-md-12 payment pad_bot">
                                    <label for="exampleInputEmail1">
                                        <p style="font-size: 15px;">Total amount you need to Payments made to this QR, or on <strong>9733263889</strong> number.
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
                                    <label for="exampleInputEmail1"> <?php echo lang('accepted'); ?> <?php echo lang('cards'); ?></label>
                                    <div class="payment pad_bot">
                                        <img src="uploads/card.png" width="100%">
                                    </div>
                                </div>


                                <?php
                                if ($payment_gateway == 'PayPal') {
                                ?>
                                    <div class="col-md-12 payment pad_bot">
                                        <label for="exampleInputEmail1"> <?php echo lang('card'); ?> <?php echo lang('type'); ?></label>
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
                                        <label for="exampleInputEmail1"> <?php echo lang('cardholder'); ?> <?php echo lang('name'); ?></label>
                                        <input type="text" id="cardholder" class="form-control pay_in" name="cardholder" value='' placeholder="">
                                    </div>
                                <?php } ?>
                                <?php if ($payment_gateway != 'Pay U Money' && $payment_gateway != 'Paystack') { ?>
                                    <div class="col-md-12 payment pad_bot">
                                        <label for="exampleInputEmail1"> <?php echo lang('card'); ?> <?php echo lang('number'); ?></label>
                                        <input type="text" id="card" class="form-control pay_in" name="card_number" value='' placeholder="">
                                    </div>



                                    <div class="col-md-8 payment pad_bot">
                                        <label for="exampleInputEmail1"> <?php echo lang('expire'); ?> <?php echo lang('date'); ?></label>
                                        <input type="text" class="form-control pay_in" id="expire" data-date="" data-date-format="MM YY" placeholder="Expiry (MM/YY)" name="expire_date" maxlength="7" aria-describedby="basic-addon1" value='' placeholder="">
                                    </div>
                                    <div class="col-md-4 payment pad_bot">
                                        <label for="exampleInputEmail1"> <?php echo lang('cvv'); ?> </label>
                                        <input type="text" class="form-control pay_in" id="cvv" maxlength="3" name="cvv" value='' placeholder="">
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

                                <div class="form-group cashsubmit payment  right-six col-md-12">
                                    <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right"> <?php echo lang('submit'); ?></button>
                                </div>

                                <div class="form-group cardsubmit  right-six col-md-12 hidden">
                                    <button type="submit" name="pay_now" id="submit-btn" class="btn btn-info row pull-right" <?php if ($settings->payment_gateway == 'Stripe') {
                                                                                                                                ?>onClick="stripePay(event);" <?php }
                                                                                                                                                                ?>> <?php echo lang('submit'); ?></button>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="form-group  payment  right-six col-md-12">
                            <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right"> <?php echo lang('submit'); ?></button>
                        </div>
                    <?php } ?>

                </form>
            </div>
        </div>
    </div>
</div> -->


<div class="modal fade" role="dialog" id="cmodal">
    <div class="modal-dialog modal-lg med_his" role="document">
        <div class="modal-content">

            <div id='medical_history'>
                <div class="col-md-12">

                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- <div class="modal fade" id="myModal2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('edit_appointment'); ?> </h4>
            </div>
            <div class="modal-body row">
                <form role="form" id="editAppointmentForm" action="appointment/addNew" class="clearfix" method="post" enctype="multipart/form-data">
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

                        <input type="hidden" id="count" value="1">
                        <div class="form-group col-md-6 hospital_category_div1">

                            <label for="exampleInputEmail1"><?php echo lang('hospital'); ?> <?php echo lang('category'); ?></label>



                            <select class="form-control js-example-basic-single" id="hospital_category1" name="hospital_category" value='' required="">

                                <option value="" disabled selected hidden><?php echo lang('select_a_hospital_cateogry'); ?></option>
                                <?php foreach ($categories as $category) { ?>
                                    <option value="<?php echo $category->id; ?>" <?php
                                                                                    if (!empty($hospital->id)) {
                                                                                        if ($hospital->category == $category->id) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                    }
                                                                                    ?>><?php echo $category->name; ?>
                                    </option>
                                <?php } ?>
                            </select>

                        </div>




                        <?php if ($this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) { ?>
                            <input type="hidden" name="casetaker_id" value="<?php echo $casetaker_id ?>">
                            <input type="hidden" name="onlinecenter_id" value="<?php echo $onlinecenter_id ?>">
                        <?php } ?>
                        <div class="form-group col-md-6 hospital_div1">

                            <label for="exampleInputEmail1"><?php echo lang('hospital'); ?></label>

                            <select class="form-control m-bot15" id="hospitalchoose" name="hospital_id" value=''>

                            </select>



                        </div>
                    <?php }
                    ?>

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>
                        <select class="form-control m-bot15  pos_select1 patient" id="pos_select1" name="patient" value='' required>

                        </select>
                    </div>
                    <div class="pos_client1 clearfix col-md-6">
                        <div class="payment pad_bot">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('name'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_name" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('email'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_email" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('phone'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_phone" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot ">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('address'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_address" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('birth_date'); ?></label>
                            <input type="date" class="form-control" name="p_birthdate" value=''>
                        </div>
                        <div class="payment pad_bot">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('age'); ?></label>
                            <div class="input-group m-bot15">

                                <input type="number" min="0" max="150" class="form-control" name="years" value='' placeholder="years">
                                <span class="input-group-addon"><?php echo lang('y'); ?></span>
                                <input type="number" class="form-control input-group-addon" min="0" max="12" name="months" value='' placeholder="<?php echo lang('months'); ?>">
                                <span class="input-group-addon"><?php echo lang('m'); ?></span>
                                <input type="number" class="form-control input-group-addon" name="days" min="0" max="29" value='' placeholder="<?php echo lang('days'); ?>">
                                <span class="input-group-addon"><?php echo lang('d'); ?></span>
                            </div>
                        </div>
                        <div class="payment pad_bot">
                            <label for="exampleInputEmail1"> <?php echo lang('country'); ?></label>
                            <select class="form-control selectpicker countrypicker m-bot15" data-live-search="true" data-flag="true" <?php if (!empty($patient->id)) { ?>data-default="<?php echo $patient->country; ?>" <?php } else { ?> data-default="United States" <?php } ?> name="country"></select>
                        </div>
                        <div class="payment pad_bot">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('gender'); ?></label>
                            <select class="form-control" name="p_gender" value=''>

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
                    <div class="col-md-6 panel doctor_div1">
                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>
                        <select class="form-control m-bot15 doctor" id="adoctors1" name="doctor" value=''>

                        </select>

                    </div>
                    <div class="col-md-12 panel" id="doctor_name1">

                    </div>
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>
                        <input type="text" class="form-control default-date-picker" id="date1" name="date" value='' placeholder="" onkeypress="return false;" autocomplete="off" required="">
                    </div>
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1">Available Slots</label>
                        <select class="form-control m-bot15" name="time_slot" id="aslots1" value=''>

                        </select>
                    </div>
                    <input type="hidden" name="doctor_idd" id="doctor_idd" value="">
                    <input type="hidden" name="redirectlink" value="10">
                    <div class="col-md-6 panel visit_description_div_div">

                        <label class=""><?php echo lang('visit'); ?> <?php echo lang('description'); ?></label>

                        <select class="form-control js-example-basic-single" name="visit_description" id="visit_description1" value='' required="">
                           
                        </select>

                    </div>
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?></label>
                        <select class="form-control m-bot15" name="status" value=''>
                            <option value="Pending Confirmation" <?php
                                                                    ?>> <?php echo lang('pending_confirmation'); ?> </option>
                            <option value="Confirmed" <?php
                                                        ?>> <?php echo lang('confirmed'); ?> </option>
                            <option value="Treated" <?php
                                                    ?>> <?php echo lang('treated'); ?> </option>
                            <option value="Cancelled" <?php
                                                        ?>> <?php echo lang('cancelled'); ?> </option>
                            <option value="Requested" <?php
                                                        ?>> <?php echo lang('requested'); ?> </option>
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
                  
                    <input type="hidden" name="superadmin" value="">
                  
                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>
                        <input type="text" class="form-control" name="remarks" value='' placeholder="">
                    </div>
                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('visit_type'); ?></label>
                        <div class="" id="visiting_place_list1"></div>
                       


                    </div>
                    <input type="hidden" name="previous_charges" id="previous_charges1" value="">
                    <input type="hidden" name="doctor_amount" id="doctor_amount1" value="">
                    <input type="hidden" name="total_charges" id="visit_charges1" value="">
                    <input type="hidden" name="additional_fee" id="total_fee1" value="">
                    <input type="hidden" name="casetaker_fee" id="casetaker_fee1" value="">
                    <input type="hidden" name="onlinecenter_fee" id="onlinecenter_fee1" value="">
                    <input type="hidden" name="developer_fee" id="developer_fee1" value="">
                    <input type="hidden" name="hospital_fee" id="hospital_fee1" value="">
                    <input type="hidden" name="superadmin_fee" id="superadmin_fee1" value="">
                    <input type="hidden" name="medicine_fee" id="medicine_fee1" value="">
                    <input type="hidden" name="courier_fee" id="courier_fee1" value="">
                    <input type="hidden" name="visit_description_id" id="visit_description_id" value="">
                    <input type="hidden" id="new_subtotal_fee2" name="appointment_subtotal2" value="">
                   
                    <input type="hidden" name="id" id="appointment_id" value=''>
                   

                    <table style="width: 100%;">
                        <tr>
                            <td class="tdd">
                                <div class="col-md-12 td"> <label for="">Subtotal</label> </div>
                            </td>
                            <td class="tdd">
                                <div class="col-md-12 td" id="">
                                    <input style="border:none" type="number" class="form-control" name="appointment_subtotal" id="new_subtotal_fee1" value='' placeholder="" readonly="">
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
                                <div class="col-md-12 td"><label>আপনি যদি কুরিয়ারে ঔষধ না নেন তাহলে টিক চিহ্ন উঠিয়ে দিন </label> <label>If you do not take medicine in courier then remove the tick mark</label></div>
                            </td>
                            <td class="tdd">
                                <div class="col-md-12">
                            </td>
                        </tr>
                        <tr>
                            <td class="tdd">
                                <div class="col-md-1"> <input type="checkbox" checked id="terms" name="terms" value="terms" required></div>
                                <div class="col-md-11"> <label for=""> I have read and agree to the Appointment terms and conditions</label><br></div>
                            </td>
                            <td class="tdd"></td>
                        </tr>
                        <tr>
                            <td class="tdd">
                                <div class="col-md-12 td"><label for="">Total</label></div>
                            </td>
                            <td class="tdd">
                                <div class="col-md-12 td"><input style="border:none" type="number" class="form-control" name="visit_charges" id="total_charges1" value='' placeholder="" readonly=""></div>
                            </td>
                        </tr>
                    </table>
                    <?php if (!$this->ion_auth->in_group(array('Nurse'))) { ?>
                        <div class="col-md-12 pay_now_div1">
                            <input type="checkbox" id="pay_now_appointment" name="pay_now_appointment" value="pay_now_appointment">
                            <label for=""> <?php echo lang('pay_now'); ?></label><br>
                            <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                                <span class="text_paynow"><?php echo lang('if_pay_now_checked_please_select_status_to_confirmed') ?></span>
                            <?php } ?>
                        </div>
                        <div class="col-md-12 hidden payment_status form-group">
                            <label for=""> <?php echo lang('payment'); ?> <?php echo lang('status'); ?></label><br>
                            <input type="text" class="form-control" id="pay_now_appointment" name="payment_status_appointment" value="paid" readonly="">


                        </div>
                        <div class="payment_label col-md-12 hidden deposit_type1">
                            <label for="exampleInputEmail1"><?php echo lang('deposit_type'); ?></label>

                            <div class="">
                                <select class="form-control m-bot15 js-example-basic-single selecttype1" id="selecttype1" name="deposit_type" value=''>
                                    <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                        <option value="Cash"> <?php echo lang('cash'); ?> </option>
                                    <?php } ?>
                                    <option value="Aamarpay"> Card/Mobile </option>
                                   
                                    <option value="Paytm"> Paytm/Gpay/Phonepe </option>
                                </select>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <?php
                            if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                                $payment_gateway = $settings->payment_gateway;
                            }
                            ?>

                            <div class="paytm1">
                                <div class="col-md-12 payment pad_bot">
                                    <label for="exampleInputEmail1">
                                        <p style="font-size: 15px;">Total amount you need to Payments made to this QR, or on <strong>9733263889</strong> number.
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

                            <div class="card1">

                                <hr>

                                <div class="col-md-12 payment pad_bot">
                                    <label for="exampleInputEmail1"> <?php echo lang('accepted');
                                                                        ?> <?php echo lang('cards'); ?></label>
                                    <div class="payment pad_bot">
                                        <img src="uploads/card.png" width="100%">
                                    </div>
                                </div>


                                <?php
                                if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                                    if ($payment_gateway == 'PayPal') {
                                ?>
                                        <div class="col-md-12 payment pad_bot">
                                            <label for="exampleInputEmail1"> <?php echo lang('card'); ?> <?php echo lang('type'); ?></label>
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
                                            <label for="exampleInputEmail1"> <?php echo lang('cardholder'); ?> <?php echo lang('name'); ?></label>
                                            <input type="text" id="cardholder1" class="form-control pay_in" name="cardholder" value='' placeholder="">
                                        </div>
                                    <?php } ?>
                                    <?php if ($payment_gateway != 'Pay U Money' && $payment_gateway != 'Paystack') { ?>
                                        <div class="col-md-12 payment pad_bot">
                                            <label for="exampleInputEmail1"> <?php echo lang('card'); ?> <?php echo lang('number'); ?></label>
                                            <input type="text" id="card1" class="form-control pay_in" name="card_number" value='' placeholder="">
                                        </div>



                                        <div class="col-md-8 payment pad_bot">
                                            <label for="exampleInputEmail1"> <?php echo lang('expire'); ?> <?php echo lang('date'); ?></label>
                                            <input type="text" class="form-control pay_in" id="expire1" data-date="" data-date-format="MM YY" placeholder="Expiry (MM/YY)" name="expire_date" maxlength="7" aria-describedby="basic-addon1" value='' placeholder="">
                                        </div>
                                        <div class="col-md-4 payment pad_bot">
                                            <label for="exampleInputEmail1"> <?php echo lang('cvv'); ?> </label>
                                            <input type="text" class="form-control pay_in" id="cvv1" maxlength="3" name="cvv" value='' placeholder="">
                                        </div>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <input type="hidden" name="stripe_publish" value="">


                                    <div class="col-md-12 payment pad_bot card_type">
                                        <label for="exampleInputEmail1"> <?php echo lang('card'); ?> <?php echo lang('type'); ?></label>
                                        <select class="form-control m-bot15" name="card_type" value=''>

                                            <option value="Mastercard"> <?php echo lang('mastercard'); ?> </option>
                                            <option value="Visa"> <?php echo lang('visa'); ?> </option>
                                            <option value="American Express"> <?php echo lang('american_express'); ?> </option>
                                        </select>
                                    </div>


                                    <div class="col-md-12 payment pad_bot cardholder_name">
                                        <label for="exampleInputEmail1"> <?php echo lang('cardholder'); ?> <?php echo lang('name'); ?></label>
                                        <input type="text" id="cardholder1" class="form-control pay_in" name="cardholder" value='' placeholder="">
                                    </div>


                                    <div class="col-md-12 payment pad_bot cardNumber">
                                        <label for="exampleInputEmail1"> <?php echo lang('card'); ?> <?php echo lang('number'); ?></label>
                                        <input type="text" id="card" class="form-control pay_in" name="card_number1" value='' placeholder="">
                                    </div>



                                    <div class="col-md-8 payment pad_bot expireNumber">
                                        <label for="exampleInputEmail1"> <?php echo lang('expire'); ?> <?php echo lang('date'); ?></label>
                                        <input type="text" class="form-control pay_in" id="expire1" data-date="" data-date-format="MM YY" placeholder="Expiry (MM/YY)" name="expire_date" maxlength="7" aria-describedby="basic-addon1" value='' placeholder="">
                                    </div>
                                    <div class="col-md-4 payment pad_bot cvvNumber">
                                        <label for="exampleInputEmail1"> <?php echo lang('cvv'); ?> </label>
                                        <input type="text" class="form-control pay_in" id="cvv1" maxlength="3" name="cvv" value='' placeholder="">
                                    </div>
                                <?php }
                                ?>
                            </div>


                        </div>
                        <div class="col-md-12 panel">
                            <div class="col-md-3 payment_label">
                            </div>
                            <div class="col-md-9">

                                <div class="form-group cashsubmit1 payment  right-six col-md-12">
                                    <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right"> <?php echo lang('submit'); ?></button>
                                </div>

                                <div class="form-group cardsubmit1  right-six col-md-12 hidden">
                                    <button type="submit" name="pay_now" id="submit-btn1" class="btn btn-info row pull-right" <?php
                                                                                                                                if (!$this->ion_auth->in_group(array('onlinecenter', 'casetaker'))) {
                                                                                                                                    if ($settings->payment_gateway == 'Stripe') {
                                                                                                                                ?>onClick="stripePay1(event);" <?php }
                                                                                                                                                        }
                                                                                                                                                                ?>> <?php echo lang('submit'); ?></button>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="form-group  payment  right-six col-md-12">
                            <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right"> <?php echo lang('submit'); ?></button>
                        </div>
                    <?php } ?>


                </form>

            </div>
        </div>
    </div>
</div>  -->


<div class="modal fade" id="mydepositModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('add_deposit'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" action="finance/deposit" id="deposit-form" class="clearfix" method="post" enctype="multipart/form-data">
                    <div class="form-group col-md-4">
                        <label for="exampleInputEmail1"><?php echo lang('patient'); ?> <?php echo lang('name'); ?> &ast; </label>
                        <input type="text" class="form-control" name="name" id="name" value='' placeholder="" readonly="">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="exampleInputEmail1">Invoice No &ast; </label>
                        <input type="text" class="form-control" name="invoice_no" id="invoice_no" value='' placeholder="" readonly="">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?> &ast; </label>
                        <input type="text" class="form-control default-date-picker" name="date" id="date" value='' placeholder="" readonly="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('due'); ?> <?php echo lang('amount'); ?></label>
                        <input type="text" class="form-control" id="due_amount" name="due" value='' placeholder="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo lang('deposit_amount'); ?></label>
                        <input type="text" class="form-control" name="deposited_amount" id="deposited_amount" value='' placeholder="" required>
                    </div>



                    <div class="form-group">
                        <div class="">
                            <label for="exampleInputEmail1"><?php echo lang('deposit_type'); ?></label>
                        </div>
                        <div class="">
                            <select class="form-control m-bot15 js-example-basic-single selecttype" id="selecttype" name="deposit_type" value=''>
                                <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                    <option value="Cash"> <?php echo lang('cash'); ?> </option>
                                <?php } ?>
                                <option value="Aamarpay"> Card/Mobile </option>
                                <!-- <option value="Card"> <?php echo lang('card'); ?> </option> -->
                                <option value="Paytm"> Paytm/Gpay/Phonepe </option>

                            </select>
                        </div>

                        <?php
                        $payment_gateway = $settings->payment_gateway;
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
                                <label for="exampleInputEmail1"> <?php echo lang('accepted'); ?> <?php echo lang('cards'); ?></label>
                                <div class="payment pad_bot">
                                    <img src="uploads/card.png" width="100%">
                                </div>
                            </div>
                            <?php
                            if ($payment_gateway == 'PayPal') {
                            ?>

                                <div class="col-md-12 payment pad_bot">
                                    <label for="exampleInputEmail1"> <?php echo lang('card'); ?> <?php echo lang('type'); ?></label>
                                    <select class="form-control m-bot15" name="card_type" value=''>

                                        <option value="Mastercard"> <?php echo lang('mastercard'); ?> </option>
                                        <option value="Visa"> <?php echo lang('visa'); ?> </option>
                                        <option value="American Express"> <?php echo lang('american_express'); ?> </option>
                                    </select>
                                </div>
                            <?php } ?>
                            <?php if ($payment_gateway == '2Checkout' || $payment_gateway == 'PayPal') {
                            ?>
                                <div class="col-md-12 payment pad_bot">
                                    <label for="exampleInputEmail1"> <?php echo lang('cardholder'); ?> <?php echo lang('name'); ?></label>
                                    <input type="text" id="cardholder" class="form-control pay_in" name="cardholder" value='' placeholder="">
                                </div>
                            <?php } ?>
                            <?php if ($payment_gateway != 'Pay U Money' && $payment_gateway != 'Paystack' && $payment_gateway != 'SSLCOMMERZ' && $payment_gateway != 'Paytm') { ?>
                                <div class="col-md-12 payment pad_bot">
                                    <label for="exampleInputEmail1"> <?php echo lang('card'); ?> <?php echo lang('number'); ?></label>
                                    <input type="text" class="form-control pay_in" id="card" name="card_number" value='' placeholder="">
                                </div>



                                <div class="col-md-8 payment pad_bot">
                                    <label for="exampleInputEmail1"> <?php echo lang('expire'); ?> <?php echo lang('date'); ?></label>
                                    <input type="text" class="form-control pay_in" id="expire" data-date="" data-date-format="MM YY" placeholder="Expiry (MM/YY)" name="expire_date" maxlength="7" aria-describedby="basic-addon1" value='' placeholder="">
                                </div>
                                <div class="col-md-4 payment pad_bot">
                                    <label for="exampleInputEmail1"> <?php echo lang('cvv'); ?> </label>
                                    <input type="text" class="form-control pay_in" id="cvv" maxlength="3" name="cvv" value='' placeholder="">
                                </div>

                        </div>

                    <?php
                            }
                    ?>

                    </div>


                    <input type="hidden" name="redirect" value="due">
                    <input type="hidden" name="id" id="id" value=''>
                    <input type="hidden" name="patient" id="patient_id" value=''>
                    <input type="hidden" name="payment_id" id="payment_id" value=''>
                    <div class="form-group cashsubmit payment  right-six col-md-12">
                        <button type="submit" name="submit2" id="submit1" class="btn btn-info row pull-right"> <?php echo lang('submit'); ?></button>
                    </div>
                    <div class="form-group cardsubmit  right-six col-md-12 hidden">
                        <?php $twocheckout = $this->db->get_where('paymentGateway', array('name =' => '2Checkout'))->row(); ?>
                        <button type="submit" name="pay_now" id="submit-btn" class="btn btn-info row pull-right" <?php if ($settings->payment_gateway == 'Stripe') {
                                                                                                                    ?>onClick="stripePay(event);" <?php }
                                                                                                                                                    ?><?php if ($settings->payment_gateway == '2Checkout' && $twocheckout->status == 'live') {
                                                                ?>onClick="twoCheckoutPay(event);" <?php }
                                                                ?>> <?php echo lang('submit'); ?></button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
</div>


<div class="modal fade" id="myStatusModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> Change Status<?php echo lang('status'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" id="editAppointmentStatusForm" action="appointment/updateStatus" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?></label>
                        <select class="form-control m-bot15" name="status" value=''>
                            <option value="Pending Confirmation" <?php ?>> <?php echo lang('pending_confirmation'); ?> </option>
                            <option value="Confirmed" <?php ?>> <?php echo lang('confirmed'); ?> </option>
                            <option value="Treated" <?php ?>> <?php echo lang('treated'); ?> </option>
                            <option value="Cancelled" <?php ?>> <?php echo lang('cancelled'); ?> </option>
                        </select>
                    </div>
                    <input type="hidden" name="redirect" value="appointment">
                    <input type="hidden" name="id" id="appointment_id" value=''>
                    <div class="form-group  payment  right-six col-md-12">
                        <button type="submit" name="submit" id="submit" class="btn btn-info row pull-right"> <?php echo lang('submit'); ?></button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>














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
        var hospital = " ";
    </script>
    <script type="text/javascript">
        var case_taker_online = "<?php echo 'yes'; ?>";
    </script>
<?php }
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
<script src="common/extranal/js/appointment/appointment_select2.js"></script>
<script src="common/extranal/js/appointment/appointment.js"></script>
<script>
    $(document).ready(function() {
        "use strict";
        $(".table").on("click", ".depositButton", function() {
            "use strict";
            var iid = $(this).attr("data-id");
            var from = $(this).attr("data-from");
            $("#due_amount").val("");
            $("#payment_id").val("");
            $("#patient_id").val("");
            $("#name").val("");
            $("#invoice_no").val("");
            $("#date").val("");
            $("#editDepositform").trigger("reset");
            $("#mydepositModal").modal("show");
            $.ajax({
                url: "finance/getDepositByInvoiceIdForDeposit?id=" + iid,
                method: "GET",
                data: "",
                dataType: "json",
                success: function(response) {
                    "use strict";
                    var d = new Date();
                    var strDate =
                        d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
                    $("#due_amount").val(response.response);
                    if (from == 'appointment') {
                        $("#deposited_amount").val(response.response);
                        //   $('#deposited_amount').attr('readonly', true);
                    }
                    $("#payment_id").val(iid);
                    $("#patient_id").val(response.patient.id);
                    $("#name").val(response.patient.name);
                    $("#invoice_no").val(iid);
                    $("#date").val(strDate);
                },
            });
        });
    });

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
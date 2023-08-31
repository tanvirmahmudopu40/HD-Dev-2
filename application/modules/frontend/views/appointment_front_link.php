<!DOCTYPE html>
<html data-wf-domain="" data-wf-page="6058c8b0dca9d968fd5d4922" data-wf-site="5f16ec24d8bd7908a5d24625">

<?php
$settings = $this->frontend_model->getSettings();
$title = explode(' ', $settings->title);
?>

<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="utf-8" />
    <title><?php echo $settings->title; ?></title>
    <meta name="description" content="">
    <meta name="author" content="Rizvi">
    <meta name="keyword" content="Php, Hospital, Clinic, Management, Software, Php, CodeIgniter, Hms, Accounting">
    <meta property="og:type" content="website" />
    <link href="common/css/style.css" rel="stylesheet">
    <link href="common/css/style-responsive.css" rel="stylesheet" />
    <link href="common/css/bootstrap.min.css" rel="stylesheet">
    <link href="common/css/bootstrap-reset.css" rel="stylesheet">
    <link href="common/css/package.css" rel="stylesheet">
    <link href="common/assets/fontawesome5pro/css/all.min.css" rel="stylesheet" />
    <meta content="summary_large_image" name="twitter:card" />
    <!--<link rel="stylesheet" href="common/css/bootstrap-select.min.css">-->
    <!--<link rel="stylesheet" href="common/css/bootstrap-select.min.css">-->
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="front-end/assets/5f16ec24d8bd7908a5d24625/css/partnerstack-1-9.b8e0fab07.min.css" rel="stylesheet" type="text/css" />

    <script src="front-end/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
    <script type="text/javascript">
        WebFont.load({
            google: {
                families: ["Inter:100,200,300,regular,500,700"]
            }
        });
    </script>


    <link href="uploads/favicon.png" rel="shortcut icon" type="image/x-icon" />
    <link href="front-end/assets/5f16ec24d8bd7908a5d24625/5f4d6fbdb8bef3160c2dc270_256.png" rel="apple-touch-icon" />

    <!-- Facebook Domain Verification - Installed by Forrest Herlick Jan 2021 -->
    <meta name="facebook-domain-verification" content="vo8fpsyay28w712wmb69pkoa5462wf" />
    <link rel="stylesheet" href="common/css/bootstrap-select-country.min.css">
    <link href="common/extranal/css/frontend/front_end.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="common/assets/select2/css/select2.min.css" />

</head>

<body class="">


    <style>
        body {
            font-family: sans-serif;
        }


        .doc {
            background-color: #fff;
            border: 1px #ccc;
            color: #333;
            height: 80px;
            width: 100px;
        }

        td {
            border: 1px solid #ccc;
            width: 75%;
        }

        .td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .green {
            background-color: #fff;
            border: 1px solid #ccc;
            display: inline-block;
            font-size: 17px;
            font-weight: bold;
            /*                margin: 6px;*/
            color: #333;
            height: 120px;
            width: 270px;
            cursor: pointer;
        }

        .boxborder {
            border: 1px solid #ccc;
            /*margin: 5px;*/
            background: #fff;
            color: #000;
            border-radius: 3px;
        }

        .activeboxborder {
            border: 1px solid blue;
            /*margin: 5px;*/
            background: #fdfdfd;
            color: #000;
            border-radius: 3px;
        }

        .image {
            height: 90px;
            width: 90px;
            margin-top: 15px;
            margin-left: 10px;
            display: inline-block;
        }

        p {
            font-size: 13px !important;
            line-height: 1;
            margin-top: 5px;
        }

        .text-1 {
            margin-top: 10px;
            font-size: 15px;
            line-height: 1;
        }

        .text {
            margin-top: 30px;
        }

        .tx {
            margin-top: -10px !important;
        }

        .green:hover {
            color: #000;
        }

        .txt {
            color: blue;
            font-size: 15px;
        }

        .slot {
            /*background: #fdfdfd;*/

            width: 100%;
            height: 250px;
            border: 1px solid #ccc;
            overflow: overlay;
        }

        .category {
            /*background: #fdfdfd;*/

            width: 100%;
            height: auto;
            padding: 20px;
            overflow: hidden;
            box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15) !important;
        }

        .btnn {
            background: #fff;
            width: 165px;
            margin: 5px;
            padding: 5px;
            border: 1px solid green;
            font-size: 13px;
        }

        .c-heading-2 {
            margin-bottom: 15px !important;
        }

        .green:hover {
            border-radius: 5px !important;
        }

        .slot {
            border: none !important;
        }

        .selected {
            background: greenyellow !important;
            color: #333 !important;
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .details {
            /*width:965px;*/
            margin: 20px 0px;
            margin-left: -15px;
            margin-right: 5px;
            /*height:325px;*/
            overflow: scroll;
            box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15) !important;
        }

        .apmnt-box {
            width: 85% !important;
            padding: 0px !important;
        }

        @media only screen and (max-width: 600px) {
            .activeboxborder {
                border: 1px solid blue !important;
                /*margin: 5px;*/
                background: #fdfdfd;
                color: #000;
                border-radius: 3px;
            }

            .apmnt-box {
                margin-left: -30px !important;
                width: 115% !important;
                padding: 0px !important;
            }

            .inputt {
                padding: 0px;
                width: 65px;
                border: none;
                margin: -5px -5px;
            }

            .category {
                background: #fdfdfd;
                width: 390px;
                height: auto;
                padding: 10px;
                overflow: hidden;
                box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15) !important;
                margin-left: -56px;
            }

            .green {
                background-color: #fff;
                border: 1px solid #ccc;
                display: inline-block;
                font-size: 12px;
                font-weight: bold;
                margin: 6px;
                color: #333;
                height: 100px;
                width: 215px;
                cursor: pointer;
            }

            .image {
                height: 26px;
                width: 49px;
                margin-top: 15px;
                margin-left: 65px;
                display: inline-block;
                margin-bottom: -11px;
            }

            .text {
                margin-top: 20px;
                text-align: center;
            }

            .details {
                width: 390px;
                margin: 20px 0px;
                margin-right: 0px;
                margin-left: 0px;
                margin-left: -54px;
                margin-right: 5px;
                /* height: 325px; */
                overflow: scroll;
                box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15) !important;
            }

            .calendar {
                width: 280px;
            }
        }

        .title {
            font-size: 30px !important;
            margin-top: 15px;
        }

        .description {
            font-size: 20px !important;
        }

        .icon {
            width: 50px;
            height: 50px;
            background-color: red;
        }

        .video {
            width: 420px;
            height: 240px;
        }

        .view {
            display: none;
        }

        @media only screen and (max-width: 600px) {
            .video {
                width: 390px;
                height: 220px;
            }

            .view1 {
                display: none;
            }

            .video-div {
                margin-left: -55px;
            }

            .description {
                font-size: 17px !important;
            }

            .view {
                display: inline-block !important;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <div class="l-section is--purple" id="appointmentt" style="margin-top:0px;">
        <div class="c-card has--form apmnt-box">
            <div style="background: #434de1;">
                <h3 class="c-heading-2 is--center" style="padding: 13px 0px; color: white !important;"> <?php echo lang('appointment_booking'); ?></h3>
            </div>
            <div id="msg" style="padding: 0px 56px; padding-bottom: 56px;">
                <div class="video-div">
                    <div class="col-md-5">

                        <iframe class="video" src="https://www.youtube.com/embed/<?php echo $video->video_link ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <!-- <video class="video" src="https://www.youtube.com/watch?v=Ha00SM7KAEI" controls> -->
                    <!-- <source src="https://www.youtube.com/watch?v=Ha00SM7KAEI" type="video/mp4"> -->
                    <!-- <source src="https://www.youtube.com/watch?v=Ha00SM7KAEI" type="video/ogg"> -->
                    <!-- Your browser does not support the video tag. -->
                    <!-- </video> -->

                    <div class="col-md-7">

                        <p class="title"> <span class="icon"></span> <?php echo $video->title ?></p>
                        <p class="description"> <?php echo $video->sub_title ?></p>
                    </div>
                </div>


                <div class="w-embed w-script">
                    <div class="hbspt-form" id="hbspt-form-1623741844431-9103338542">
                        <form action="frontend/addNewAppointment" id="sendEmail" class="addAppointmentForm" enctype="multipart/form-data" method="POST">
                            <div class="col-md-12" style="padding: 0px 10px; margin-buttom:200px;"><?php
                                                                                                    $message = $this->session->flashdata('feedback');
                                                                                                    if (!empty($message)) {
                                                                                                    ?>
                                    <h2 class="c-heading-2 is--center h2_heading f-message" style="color: green;"> <?php echo $message; ?></h2>
                                <?php } ?>
                            </div>

                            <input type="hidden" name="redirect" value="frontend/appointmentLink?id=<?php echo $doctor->id; ?><?php if (!empty($onlinecenter_id)) {
                                                                                                                                    echo '&onlinecenter_id=' . $onlinecenter_id;
                                                                                                                                } ?><?php if (!empty($casetaker_id)) {
                                                                                                                                        echo '&casetaker_id=' . $casetaker_id;
                                                                                                                                    } ?>">
                            <div class="col-md-12 mb-4 hidden">
                                <div class="">
                                    <label id="label-lastname-93389280-7d5f-4a00-a4a2-6b177ad9e886" style="font-weight: bold; margin-bottom: 15px; margin-left: -15px;">
                                        <span> <?php echo lang('hospital'); ?></span>
                                        <span class="hs-form-required">*</span></label>
                                    <div class="row category">
                                        <div class="col-md-4">
                                            <div id="hospital" data-id="<?php echo $hospital->id; ?>" class=" hospital green hosp appHospital boxborder">
                                                <div class="col-sm-4"><img src="<?php echo $doctor->img_url; ?>" class="image"></div>
                                                <div class="col-sm-8 text"> <span><?php echo $hospital->name; ?> </span><span class="txt"><?php echo $hospital->name; ?> </span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="selectedHospital" name="hospital">

                            </div>

                            <div class="col-md-12 mb-4 hidden">
                                <div class="" id="adoctors">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                        <label id="label-lastname-93389280-7d5f-4a00-a4a2-6b177ad9e886" style="font-weight: bold; margin-bottom: 15px; margin-left: -15px;">
                                            <span> <?php echo lang('doctor'); ?></span>

                                            <span class="hs-form-required">*</span></label>
                                        <input type="text" class="form-control" id="docSearch" style="width: 30%">
                                    </div>
                                    <div class="row category">

                                        <div class="col-md-4">
                                            <div id="hospital" data-id="<?php echo $doctor->id; ?>" class="click hospital green hosp appDoc boxborder">
                                                <div class="col-sm-4"><img src="<?php echo $doctor->img_url; ?>" class="image"></div>
                                                <div class="col-sm-8 text"> <span></span><span class="txt"> <?php echo $doctor->name; ?></span></div>
                                            </div>
                                        </div>

                                    </div>
                                    <input type="text" id="selectedDoctor" name="doctor">
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <div class="row details">
                                    <div class="col-md-4">
                                        <label id="label-lastname-93389280-7d5f-4a00-a4a2-6b177ad9e886" style="font-weight: bold; margin: 20px 0px;">
                                            <span> Doctor Details </span>
                                            <input type="hidden" id="doctor_id" name="doctor_id" value="<?php echo $doctor->id; ?>">
                                            <input type="hidden" id="hospital_id" name="hospital_id" value="<?php echo $doctor->hospital_id; ?>">
                                            <span class="hs-form-required"></span></label>
                                        <div class="">
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                                <div class="fileupload-new thumbnail img_class">
                                                    <img src="" id="img2" alt="" />
                                                    <div style="margin: 20px;" id="doctor_name1"></div>
                                                </div>
                                            </div>

                                        </div>


                                    </div>
                                    <div class="col-md-8" style="padding-bottom:20px;">
                                        <label id="label-lastname-93389280-7d5f-4a00-a4a2-6b177ad9e886" style="font-weight: bold; margin: 20px 0px;">
                                            <span style="color:green;">
                                                <bold> Fill Up The Information For Appointment</bold>
                                            </span>
                                            <span class="hs-form-required"> : </span></label>
                                        <label id="label-lastname-93389280-7d5f-4a00-a4a2-6b177ad9e886" style="font-weight: bold; margin: 20px 0px;">
                                            <span> <?php echo lang('patient'); ?></span>
                                            <span class="hs-form-required">*</span></label>
                                        <!--<div style="border:1px solid #e2e2e4;">-->
                                        <!--<div style="padding: 20px;">-->
                                        <div class="pat_div">
                                            <legend class="hs-field-desc hs_field_desc"></legend>
                                            <div class="input  mb-1">
                                                <select class="form-control js-example-basic-single  pos_select" id="pos_select" name="patient" value='' style="margin-bottom: 15px;">
                                                    <option value="add_new">Add New</option>
                                                    <option value="Registered">Registered</option>
                                                    <!--<?php if (!empty($appointment)) { ?>
                                                                    <option value="<?php echo $patients->id; ?>" selected="selected"><?php echo $patients->name; ?> - <?php echo $patients->id; ?></option>
                                            <?php } ?>-->
                                                </select>
                                            </div>
                                            <!-- <form action="" id="create_patient"> -->
                                            <div class="pos_client clearfix">
                                                <div class="payment pad_bot mb-1">
                                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('name'); ?></label>
                                                    <input type="text" class="form-control pay_in" id="p_name" name="p_name" value='<?php
                                                                                                                                    if (!empty($payment->p_name)) {
                                                                                                                                        echo $payment->p_name;
                                                                                                                                    }
                                                                                                                                    ?>' placeholder="">
                                                </div>
                                                <!-- <div class="payment pad_bot mb-1">
                                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                                        <?php echo lang('birth_date'); ?></label>
                                                    <input type="date" class="form-control pay_in form-control-inline input-medium default-date-picker" id="p_birthdate" name="p_birthdate" value='' placeholder="">
                                                </div> -->
                                                <div class="payment pad_bot mb-1">
                                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                                        <?php echo lang('age'); ?></label>
                                                    <div class="input-group m-bot15">

                                                        <input type="number" min="0" max="150" class="form-control" name="years" id="years" value='' placeholder="years">
                                                        <span class="input-group-addon">Y</span>
                                                        <input type="number" class="form-control input-group-addon" min="0" max="12" name="months" id="months" value='0' placeholder="<?php echo lang('months'); ?>">
                                                        <span class="input-group-addon">M</span>
                                                        <input type="number" class="form-control input-group-addon" name="days" id="days" min="0" max="29" value='0' placeholder="<?php echo lang('days'); ?>">
                                                        <span class="input-group-addon">D</span>
                                                    </div>
                                                </div>
                                                <div class="payment pad_bot mb-1">
                                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                                        <?php echo lang('gender'); ?></label>
                                                    <select class="form-control m-bot15" name="p_gender" id="p_gender" value=''>

                                                        <option value="Male" <?php
                                                                                if (!empty($payment->p_gender)) {
                                                                                    if ($payment->p_gender == 'Male') {
                                                                                        echo 'selected';
                                                                                    }
                                                                                }
                                                                                ?>> Male </option>
                                                        <option value="Female" <?php
                                                                                if (!empty($payment->p_gender)) {
                                                                                    if ($payment->p_gender == 'Female') {
                                                                                        echo 'selected';
                                                                                    }
                                                                                }
                                                                                ?>> Female </option>
                                                        <option value="Others" <?php
                                                                                if (!empty($payment->p_gender)) {
                                                                                    if ($payment->p_gender == 'Others') {
                                                                                        echo 'selected';
                                                                                    }
                                                                                }
                                                                                ?>> Others </option>
                                                    </select>
                                                </div>
                                                <!-- <div class="payment pad_bot mb-1">
                                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('email'); ?></label>
                                                    <input type="text" class="form-control pay_in" name="p_email" id="p_email" value='<?php
                                                                                                                                        if (!empty($payment->p_email)) {
                                                                                                                                            echo $payment->p_email;
                                                                                                                                        }
                                                                                                                                        ?>' placeholder="">
                                                </div> -->
                                                <div class="payment pad_bot mb-1">
                                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('phone'); ?></label>
                                                    <input type="text" class="form-control pay_in" name="p_phone" id="p_phone" value='<?php
                                                                                                                                        if (!empty($payment->p_phone)) {
                                                                                                                                            echo $payment->p_phone;
                                                                                                                                        }
                                                                                                                                        ?>' placeholder="">
                                                </div>
                                                <div class="payment pad_bot mb-1">
                                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>
                                                        <?php echo lang('address'); ?></label>
                                                    <input type="text" class="form-control pay_in" name="p_address" id="p_address" value='<?php
                                                                                                                                            if (!empty($payment->p_address)) {
                                                                                                                                                echo $payment->p_address;
                                                                                                                                            }
                                                                                                                                            ?>' placeholder="">
                                                </div>
                                                <div class="payment pad_bot mb-1">
                                                    <label for="exampleInputEmail1"> <?php echo lang('country'); ?> *</label>
                                                    <select class="form-control countrypicker" data-live-search="true" data-flag="true" id="country" name="country"></select>
                                                </div>
                                                <div class="col-md-12" style="padding:0px;">
                                                <button type="button" id="create_patient" class="btn btn-info btn-group pull-center" style="margin-left: 0% !important;"> Create Patient Account</button>
                                            </div>
                                            </div>
                                           
                                        </div>
                                        <div class="patid_div">

                                            <div class=" mb-1">
                                                <!-- <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> </label> -->
                                                <input type="hidden" class="form-control" name="patientt" value="" id="patientt">
                                                <span class="form-control" id="patientname"></span>
                                            </div>
                                        </div>
                                        <span id="add_message"></span>
                                        <!-- </form> -->
                                        <div class="patient_registered mb-1">
                                            <select class="form-control m-bot15  pos_select1" id="pos_select1" name="patient" value=''>

                                            </select>

                                        </div>
                                        <input type="hidden" id="selectedDate2" name="date" value="">
                                        <input type="hidden" name="status" value="Requested">
                                        <input type="hidden" name="onlinecenter_id" value="<?php echo $onlinecenter_id; ?>">
                                        <input type="hidden" name="casetaker_id" value="<?php echo $casetaker_id; ?>">
                                        <div class="col-md-5">
                                            <label id="label-lastname-93389280-7d5f-4a00-a4a2-6b177ad9e886" style="font-weight: bold; margin: 20px 0px;">
                                                <span> <?php echo lang('date'); ?> </span>
                                                <span class="hs-form-required">*</span></label>
                                            <!--                                    <div style="border:1px solid #e2e2e4;">
                                                                            <div style="padding-top: 20px; padding-bottom: 20px;">-->
                                            <legend class="hs-field-desc hs_field_desc"></legend>
                                            <!--                                        <div class="input">
                                                                                <input type="text" class="form-control" id="date2" readonly="" name="date" id="exampleInputEmail1" value='' placeholder="">
                                                                            </div>-->
                                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
                                            <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
                                            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
                                            <div id="datepicker" onclick="dateChanged()"></div>
                                        </div>
                                        <div class="col-md-7">
                                            <label id="label-lastname-93389280-7d5f-4a00-a4a2-6b177ad9e886" style="font-weight: bold; margin: 20px 0px;">
                                                <span> Available Slots </span>
                                                <span class="hs-form-required">*</span></label>
                                            <!--<div style="border:1px solid #e2e2e4;">-->

                                            <!--<div style="padding-top: 20px; padding-bottom: 20px;">-->

                                            <div class="slot">

                                            </div>
                                            <input type="hidden" id="selectedSlot" name="time_slot">

                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <div class="row">
                                                <div class="col-md-12 panel" style="margin-top: 20px; margin-left: -12px;">


                                                    <label for="exampleInputEmail1"> <?php echo lang('currency'); ?> </label>

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







                                                    <!-- </div>
                                    <div class="col-md-12 panel" style="margin-top: 20px;"> -->

                                                    <label style="margin-bottom: 20px; margin-top:20px;"><?php echo lang('visit'); ?> <?php echo lang('description'); ?> :</label>

                                                    <select class="form-control js-example-basic-single" name="visit_description" id="visit_description" value='' required="">
                                                        <!-- <option value="" disabled selected hidden><?php echo lang('select'); ?> <?php echo lang('visit'); ?> <?php echo lang('type'); ?></option>
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
                                                                                ?>><?php echo lang('old_visit_with_medicine'); ?></option> -->
                                                    </select>
                                                    <input type="hidden" class="visit_id" name="visit_id" id="visit_id" value="">
                                                    <label style="margin-top: 20px;" class=""><?php echo lang('visit_type'); ?></label>

                                                    <div id="visiting_place_list">

                                                    </div>

                                                    <!-- </div> -->
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
                                                    <!-- <div class="col-md-12 clearfix visit_div" style="margin-top: 20px;"> -->

                                                    <!-- <div class="form-group col-md-12 visit_description_div">
                                            <label for="exampleInputEmail1" style="margin-bottom: 20px;"><?php echo lang('visit'); ?> <?php echo lang('charges'); ?> :</label>

                                        </div> -->

                                                    <div class="form-group pay_for_courier" style="margin-top: 40px; margin-bottom: 0px;">
                                                        <table style="width: 100%;">
                                                            <tr>
                                                                <td>
                                                                    <div class="col-md-12 td"> <label for="">Subtotal</label> </div>
                                                                </td>
                                                                <td>
                                                                    <div class="col-md-12 td" id="">
                                                                        <input type="number" style="border: none;" class="form-control inputt" name="appointment_subtotal" id="new_subtotal_fee" value='<?php
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
                                                                    <div class="col-md-12 td" id="gateway_fee" style="margin-left:15px;"></div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="col-md-1"> <input type="checkbox" checked id="pay_for_courier" name="pay_for_courier" value="pay_for_courier"></div>
                                                                    <div class="col-md-11"> <label for=""> <?php echo lang('courier'); ?></label><br></div>
                                                                </td>
                                                                <td>
                                                                    <div class="col-md-12 td" id="shipping_fee" style="margin-left:15px;"></div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="col-md-12 td"><label>আপনি যদি কুরিয়ারে ঔষধ না নেন তাহলে টিক চিহ্ন উঠিয়ে দিন </label> <label>If you do not take medicine in courier then remove the tick mark</label></div>
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
                                                                    <div class="col-md-12 td"><input style="border: none;" type="number" class="form-control inputt" name="visit_charges" id="total_charges" value='<?php
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
                                                                    <div class="col-md-12 td"><input style="border:none" type="text" class="form-control inputt" name="deposited_amount" id="deposited_amount" value='<?php
                                                                                                                                                                                                                        if (!empty($appointment->id)) {
                                                                                                                                                                                                                            echo $appointment->deposited_amount;
                                                                                                                                                                                                                        }
                                                                                                                                                                                                                        ?>' placeholder="200"></div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="col-md-12 td"><label for="">Due Amount</label></div>
                                                                </td>
                                                                <td>
                                                                    <div class="col-md-12 td"><input style="border:none" type="number" class="form-control inputt" name="due_amount" id="due_amount" value='<?php
                                                                                                                                                                                                            if (!empty($appointment->id)) {
                                                                                                                                                                                                                echo $appointment->due_amount;
                                                                                                                                                                                                            }
                                                                                                                                                                                                            ?>' placeholder="" readonly=""></div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="col-md-1"> <input type="checkbox" id="terms" name="terms" value="terms" required></div>
                                                                    <div class="col-md-11"> <label for=""> I have read and agree to the Appointment <a href="frontend/privacyPolicy?id=1" target="_blank">terms and conditions</a></label><br></div>
                                                                </td>
                                                                <td></td>
                                                            </tr>
                                                        </table>


                                                    </div>
                                                    <div class="col-md-12 pay_now_div" style="margin-top: 20px;">
                                                        <input type="checkbox" checked id="pay_now_appointment" name="pay_now_appointment" value="pay_now_appointment">
                                                        <label for=""> <?php echo lang('pay_now'); ?></label><br>
                                                        <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                                                            <span class="text_paynow"><?php echo lang('if_pay_now_checked_please_select_status_to_confirmed') ?></span>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="payment_label col-md-12  deposit_type">
                                                        <label for="exampleInputEmail1"><?php echo lang('deposit_type'); ?></label>

                                                        <div class="">
                                                            <select class="form-control m-bot15 js-example-basic-single selecttype" id="selecttype" name="deposit_type" value=''>
                                                                <!-- <option value="Cash"> <?php echo lang('cash'); ?> </option> -->
                                                                <!-- <option value="Card"> <?php echo lang('card'); ?> </option> -->
                                                                <option value="Aamarpay"> Dollar & Taka: Card/Mobile Banking </option>
                                                                <option value="Paytm"> Indian Rupee </option>

                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-12">

                                                        <!-- <input type="hidden" name="link" value="doctorlink"> -->
                                                        <div class="paytm hidden">
                                                            <div class="col-md-12 payment pad_bot">
                                                                <label for="exampleInputEmail1">
                                                                    <p style="font-size: 15px;">Total amount you need to Payments made to this QR, or on <strong>9733263889</strong> number.
                                                                        Then fill the form below and submit.</p>
                                                                </label>

                                                            </div>
                                                            <div class="col-md-12 payment pad_bot">
                                                                <div class="payment pad_bot col-md-4">
                                                                    <a class="example-image-link" target="_blank" href="uploads/Paytm.jpg" data-lightbox="example-1">
                                                                        <img class="example-image" src="uploads/Paytm.jpg" alt="image-1" height="90" width="90" /></a>

                                                                </div>
                                                                <div class="payment pad_bot col-md-4">
                                                                    <a class="example-image-link" target="_blank" href="uploads/Gpay.jpg" data-lightbox="example-1">
                                                                        <img class="example-image" src="uploads/Gpay.jpg" alt="image-1" height="90" width="90" /></a>

                                                                </div>
                                                                <div class="payment pad_bot col-md-4">
                                                                    <a class="example-image-link" target="_blank" href="uploads/PhonePe.jpg" data-lightbox="example-1">
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
                                                        <div class="cardd hidden">

                                                            <hr>

                                                            <div class="col-md-12 payment pad_bot">
                                                                <label for="exampleInputEmail1"> <?php echo lang('accepted'); ?> <?php echo lang('cards'); ?></label>
                                                                <div class="payment pad_bot">
                                                                    <img src="uploads/card.png" width="100%">
                                                                </div>
                                                            </div>




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
                                                                <input type="text" id="cardholder" class="form-control pay_in" name="cardholder" value='' placeholder="">
                                                            </div>


                                                            <div class="col-md-12 payment pad_bot cardNumber">
                                                                <label for="exampleInputEmail1"> <?php echo lang('card'); ?> <?php echo lang('number'); ?></label>
                                                                <input type="text" id="card" class="form-control pay_in" name="card_number" value='' placeholder="">
                                                            </div>



                                                            <div class="col-md-8 payment pad_bot expireNumber">
                                                                <label for="exampleInputEmail1"> <?php echo lang('expire'); ?> <?php echo lang('date'); ?></label>
                                                                <input type="text" class="form-control pay_in" id="expire" data-date="" data-date-format="MM YY" placeholder="Expiry (MM/YY)" name="expire_date" maxlength="7" aria-describedby="basic-addon1" value='' placeholder="">
                                                            </div>
                                                            <div class="col-md-4 payment pad_bot cvvNumber">
                                                                <label for="exampleInputEmail1"> <?php echo lang('cvv'); ?> </label>
                                                                <input type="text" class="form-control pay_in" id="cvv" maxlength="3" name="cvv" value='' placeholder="">
                                                            </div>

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
                                                                <button type="submit" name="pay_now" id="submit-btn" class="btn btn-info row pull-right"> <?php echo lang('submit'); ?></button>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <div class="" id="">
                                    <label for="exampleInputEmail1" style="margin-left: -15px; margin-bottom: 20px;"> <?php echo lang('doctor'); ?> <?php echo lang('description'); ?></label>
                                    <div class="row category">

                                        <div id="doctor_description"></div>

                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <!--<button type="submit" name="submit" class="btn btn-info btn-group pull-center sub submit_button" style="margin-left: 0% !important;"> <?php echo lang('submit'); ?></button>-->
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>


        <script src="front-end/assets/js/jquery-3.5.1.min.dc5e7f18c85de7.js?site=5f16ec24d8bd7908a5d24625" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="front-end/assets/5f16ec24d8bd7908a5d24625/js/partnerstack-1-9.6849d928e.js" type="text/javascript"></script>

        <script src="common/js/bootstrap.min.js"></script>
        <!--<script src="common/js/bootstrap-select.min.js"></script>-->

        <script src="common/js/bootstrap-select-country.min.js"></script>

</body>

</html>


<input type="hidden" id="selectedDate" value="">


<!--<script src="common/js/codearistos.min.js"></script>-->
<!--<script type="text/javascript" src="https://js.stripe.com/v2/"></script>-->
<script type="text/javascript" src="common/assets/ckeditor/ckeditor.js"></script>
<script src="common/js/codearistos.min.js"></script>
<script type="text/javascript" src="common/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="common/extranal/js/appointment/appointment_frontend.js"></script>
<script src="common/extranal/js/appointment/appointment_select2_frontend.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    var hospital = " ";
</script>
<script type="text/javascript">
    var payment_gateway = "<?php echo $settings1->payment_gateway; ?>";
</script>
<!--<script type="text/javascript">var publish = "<?php echo $gateway->publish; ?>";</script>-->
<script src="common/extranal/js/frontend/front_end.js"></script>
<script type="text/javascript" src="common/assets/select2/js/select2.min.js"></script>
<!--<script type="text/javascript" src="common/assets/select2/js/select2.min.js"></script>-->

<script src="common/js/bootstrap-select.min.js"></script>

<script type="text/javascript">
    var select_hospital = "<?php echo lang('select_hospital'); ?>";
</script>
<script type="text/javascript">
    var select_doctor = "<?php echo lang('select_doctor'); ?>";
</script>
<script type="text/javascript">
    var select_patient = "<?php echo lang('select_patient'); ?>";
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#hospital").trigger('click');
        $(".click").trigger('click');
    });
</script>
<!--    <script>
$(".hospital").click(function(){
var id = $(this).data('id');
alert(id);
});
</script>-->
<script>
    $(document).ready(function() {
        "use strict";
        $(".f-message").delay(5000).fadeOut(1000);
    });
</script>
<script>
    $(document).ready(function() {

        var onlinecenter_id = $("#onlinecenter_id").val();
        var casetaker_id = $("#casetaker_id").val();
        var doctor_id = $("#doctor_id").val();
        var currency = $("#currency").val();

        $.ajax({
            url: "frontend/getDoctorCommissionSettingsByDoctor?id=" + doctor_id,
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

            },
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#currency").change(function() {
            var onlinecenter_id = $("#onlinecenter_id").val();
            var casetaker_id = $("#casetaker_id").val();
            var doctor_id = $("#doctor_id").val();
            var currency = $("#currency").val();
            var visit_description = $("#visit_description").val();
            $("#new_subtotal_fee").empty();
            $("#gateway_fee").empty();
            $("#shipping_fee").empty();
            var subtotal = $("#subtotal_fee").val();
            $.ajax({
                url: "frontend/getDoctorCommissionSettingsByDoctor?id=" + doctor_id,
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
                        // parseFloat(gateway_fee) +
                        parseFloat(courier);

                    $("#casetaker_fee").val(casetaker_fee).end();
                    $("#onlinecenter_fee").val(onlinecenter_fee).end();
                    $("#developer_fee").val(developer_fee).end();
                    $("#hospital_fee").val(hospital_fee).end();
                    $("#superadmin_fee").val(superadmin_fee).end();
                    $("#medicine_fee").val(medicine_fee).end();
                    $("#courier_fee").val(courier_fee).end();
                    $('#shipping_fee').append(courier_fee).end();
                    $("#total_fee").val(total_fee).end();
                    $('#new_subtotal_fee').val(total_fee_without_courier).end();
                    $('#gateway_fee').append(gateway_fee).end();
                    $("#subtotal_fee").val(total_fee_without_courier).end();
                    $("#gateway_fee").empty();
                    $("#subtotal_fee").empty();
                    $("#new_subtotal_fee").empty();
                    var subtotal = $("#subtotal_fee").val();
                    $.ajax({
                        url: "frontend/getDoctorVisitChargess?id=" + visit_description,
                        method: "GET",
                        dataType: "json",
                        success: function(response) {

                            if (currency == 'BDT') {
                                var visit = response.response.visit_charges;
                            }
                            if (currency == 'INR') {
                                var visit = response.response.visit_charges_rupi;
                            }
                            if (currency == 'USD') {
                                var visit = response.response.visit_charges_doller;
                            }
                            if ($("#pay_for_courier").prop("checked") == true) {
                                var courier = courier_fee;
                            } else {
                                var courier = 0;
                            }
                            var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
                            var gateway_fee = new_subtotal * 2.5 / 100;
                            var total_doctor_amount =
                                parseFloat(visit) +



                                $("#visit_charges").val(visit).end();
                            $("#total_charges")
                                .val(parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier))
                                .end();
                            $("#doctor_amount").val(total_doctor_amount).end();
                            $('#new_subtotal_fee').val(new_subtotal).end();
                            $('#gateway_fee').append(gateway_fee).end();
                        },
                    });

                },
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#visit_description").change(function() {
            var id = $(this).val();
            $("#new_subtotal_fee").empty();
            $("#gateway_fee").empty();
            $("#visit_charges").val(" ");
            $("#total_charges").val(" ");
            var doctor_id = $("#doctor_id").val();
            var total_fee = $("#total_fee").val();
            var casetaker_fee = $("#casetaker_fee").val();
            var courier_fee = $("#courier_fee").val();
            var onlinecenter_fee = $("#onlinecenter_fee").val();
            var currency = $("#currency").val();
            var subtotal = $("#subtotal_fee").val();
            $.ajax({
                url: "frontend/getDoctorVisitChargess?id=" + id,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (currency == 'BDT') {
                        var visit = response.response.visit_charges;
                    }
                    if (currency == 'INR') {
                        var visit = response.response.visit_charges_rupi;
                    }
                    if (currency == 'USD') {
                        var visit = response.response.visit_charges_doller;
                    }
                    if ($("#pay_for_courier").prop("checked") == true) {
                        var courier = courier_fee;
                    } else {
                        var courier = 0;
                    }
                    var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
                    var gateway_fee = new_subtotal * 2.5 / 100;
                    var total_doctor_amount =
                        parseFloat(visit) +



                        $("#visit_charges").val(visit).end();
                    $("#total_charges")
                        .val(parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier))
                        .end();
                    $("#doctor_amount").val(total_doctor_amount).end();
                    $('#new_subtotal_fee').val(new_subtotal).end();
                    $('#gateway_fee').append(gateway_fee).end();

                    var total = parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier)
                        
                        var deposited_amount = $("#deposited_amount").val();
                        $("#due_amount").val(total - deposited_amount).end();
                },
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        // if ($('#pay_for_courier').prop("checked") == true) {
        //     // $("#total_fee").val(" ");
        //     var total_fee = $('#total_fee').val();
        //     $("#test").val(total_fee).end();
        // }
        $(".pay_for_courier").on("change", "#pay_for_courier", function() {
            if ($(this).prop("checked") == true) {
                var total_charge = $("#total_charges").val();
                var courier_fee = $("#courier_fee").val();
                var total_fee = $("#total_fee").val();
                var total_additional_fee =
                    parseFloat(total_fee) + parseFloat(courier_fee);
                var new_total_fee = parseFloat(total_charge) + parseFloat(courier_fee);
                $("#total_charges").val(new_total_fee).end();
                $("#total_fee").val(total_additional_fee).end();
            } else {
                var total_charge = $("#total_charges").val();
                var courier_fee = $("#courier_fee").val();
                var total_fee = $("#total_fee").val();
                var total_additional_fee =
                    parseFloat(total_fee) - parseFloat(courier_fee);
                var new_total_fee = parseFloat(total_charge) - parseFloat(courier_fee);
                $("#total_charges").val(new_total_fee).end();
                $("#total_fee").val(total_additional_fee).end();
            }
        });
    });
</script>
<script>
    $(document).ready(function() {

        var id = $("#doctor_id").val();
        $("#doctor_name").html("").end();
        $("#doctor_name1").html("").end();
        $('#doctor_description').html("").end();

        $.ajax({
            url: "frontend/getDoctorDetails?id=" + id,
            method: "GET",
            dataType: "json",
            success: function(response) {
                var designation = response.response.profile
                var description = response.response.description
                var visiting_place = response.response.visiting_place;
                var country = response.response.country
                var chamber = response.response.chamber_address
                if (visiting_place === null) {
                    var visit_type = '';
                } else {
                    var visit_type = response.response.visiting_place;
                }
                $('#doctor_name1').append(response.response.name + ' ' + designation + ' <br> ' + 'Country :' +
                    "<span style='color:green;font-weight:600;'> " +
                    country +
                    "</span>" + " <br> " +
                    "Visit Type : " +
                    "<span style='color:green;font-weight:600;font-size:15px'> " +
                    visit_type +
                    "</span>" + '<br> ' + 'Chamber Address :' + ' <br>' + chamber).end();
                $('#doctor_name').append(response.response.name + ' ' + designation + ' <br> ' + 'Chamber Address :' + ' <br>' + chamber + ', ' + country).end();
                $('#doctor_description').append(response.response.description).end();
                $("#img1").attr("src", "uploads/doctor_avater1.jpg");
                $("#img2").attr("src", "uploads/doctor_avater1.jpg");
                if (typeof response.response.img_url !== 'undefined' && response.response.img_url !== '') {
                    $("#img1").attr("src", response.response.img_url);
                }
                if (typeof response.response.img_url !== 'undefined' && response.response.img_url !== '') {
                    $("#img2").attr("src", response.response.img_url);
                }
            },
        });

    });
</script>
<script>
    $(document).ready(function() {

        "use strict";

        var doctor_id = $("#doctor_id").val();


        $.ajax({
            url: "frontend/getDoctorVisit?id=" + doctor_id,
            method: "GET",
            data: "",
            dataType: "json",
            success: function(response1) {
                $("#visit_description").html(response1.response).end();
                $(".addAppointmentForm").find('[name="visit_id"]').val(response1.responsee).end();
            },
        });
        setTimeout(function() {
            $("#new_subtotal_fee").empty();
            $("#gateway_fee").empty();
            $("#visit_charges").val(" ");
            $("#total_charges").val(" ");
            // var doctor_id = $("#doctor_id").val();
            var total_fee = $("#total_fee").val();
            var casetaker_fee = $("#casetaker_fee").val();
            var courier_fee = $("#courier_fee").val();
            var onlinecenter_fee = $("#onlinecenter_fee").val();
            var currency = $("#currency").val();
            var subtotal = $("#subtotal_fee").val();
            var visit_id = $("#visit_id").val();
            $.ajax({
                url: "frontend/getDoctorVisitChargess?id=" + id,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    if (currency == 'BDT') {
                        var visit = response.response.visit_charges;
                    }
                    if (currency == 'INR') {
                        var visit = response.response.visit_charges_rupi;
                    }
                    if (currency == 'USD') {
                        var visit = response.response.visit_charges_doller;
                    }
                    if ($("#pay_for_courier").prop("checked") == true) {
                        var courier = courier_fee;
                    } else {
                        var courier = 0;
                    }
                    var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
                    var gateway_fee = new_subtotal * 2.5 / 100;
                    var total_doctor_amount =
                        parseFloat(visit) +



                        $("#visit_charges").val(visit).end();
                    $("#total_charges")
                        .val(parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier))
                        .end();
                    $("#doctor_amount").val(total_doctor_amount).end();
                    $('#new_subtotal_fee').val(new_subtotal).end();
                    $('#gateway_fee').append(gateway_fee).end();
                    var total = parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier)
                        
                        var deposited_amount = $("#deposited_amount").val();
                        $("#due_amount").val(total - deposited_amount).end();
                },
            });
        }, 3000);


    });
</script>

<script>
    $(document).ready(function() {
        $(".patid_div").hide();
        $('#create_patient').on('click', function() {
            var doctor = $('#doctor_id').val();
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
            var hospital_id = $('#hospital_id').val();
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
                url: 'frontend/createPatient',
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
                    $(".addAppointmentForm").find('[name="patientt"]').val(response.option.patientt).end();
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
        $("#deposited_amount").keyup(function() {
        var deposit = $(this).val();
        var currency = $("#currency").val();
        var total = $("#total_charges").val(); 
        $("#due_amount").val(total - deposit).end();

    });
</script>

<script>
function validateForm() {
  var quantityInput = document.getElementById("deposited_amount");
  var quantityValue = quantityInput.value;
  
  if (quantityValue < 200) {
    alert("Advance Payment (Minimum 200).");
    return false; // prevent form submission
  }
  else {
    return true; // allow form submission
  }
}
</script>
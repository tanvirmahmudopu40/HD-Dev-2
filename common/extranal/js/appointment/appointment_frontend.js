"use strict";
$(document).ready(function () {});

$(document).on("click", ".appDoc", function () {
  "use strict";
  //let date = $('#date').val();
  let dateTemp = $("#datepicker").datepicker("getDate");
  console.log(dateTemp);
  let date = "";
  if (dateTemp) {
    let day =
      dateTemp.getDay() < 9
        ? "0" + Number(dateTemp.getDay())
        : Number(dateTemp.getDay());
    let month =
      dateTemp.getMonth() < 9
        ? "0" + (Number(1) + Number(dateTemp.getMonth()))
        : Number(1) + Number(dateTemp.getMonth());
    let year = dateTemp.getFullYear();
    date = month + "/" + day + "/" + year;
  }
  let doctor = $(this).data("id");
  $("#selectedDoctor").val(doctor);
  $("#selectedSlot").val("");
  if (date != "") {
    $.ajax({
      url:
        "frontend/getAvailableSlotByDoctorByDateByJason?date=" +
        date +
        "&doctor=" +
        doctor,
      method: "GET",
      data: "",
      dataType: "json",
      success: function (response) {
        "use strict";

        var slots = response.aslots;
        $(".slot").empty();
        if (slots.length == 0) {
          $(".slot")
            .append(
              '<div class="col-md-12 text-center">No Available Slot Found!</div>'
            )
            .end();
        } else {
          $.each(slots, function (key, value) {
            $(".slot")
              .append(
                '<button type="button" data-id="' +
                  value +
                  '" class="btnn appSlot boxborder hosp">' +
                  value +
                  "</button>"
              )
              .end();
          });
          //$('.slot').append('<div class="col-md-12 text-center">No Available Slot Found!</div>').end();
        }
      },
    });
  }
});

$(function () {
  let date = $("#selectedDate").val();
  $("#datepicker").datepicker();
  setInterval(function () {
    console.log($("#datepicker").datepicker("getDate"));
    console.log($("#selectedDate").val());
    if (
      $("#datepicker").datepicker("getDate") != $("#selectedDate").val() &&
      $("#datepicker").datepicker("getDate")
    ) {
      $("#selectedDate").val($("#datepicker").datepicker("getDate"));
      dateChanged();
    } else {
    }
  }, 1000);
});
$(document).ready(function () {
  $(document).on("click", ".appDoc", function () {
    "use strict";
    var doctorr = $(this).data("id");
    var visit_description = $("#visit_description").val();
    $("#aslots").find("option").remove();

    //   $('#doctor_name').html("").end();
    //   $('#doctor_description').html("").end();

    //   $.ajax({
    //     url: "frontend/getDoctorDetails?id=" + doctorr,
    //     method: "GET",
    //     dataType: "json",
    //     success: function(response) {
    //         var designation = response.response.profile
    //         var description = response.response.description
    //         var country = response.response.country
    //         var chamber = response.response.chamber_address
    //         $('#doctor_name').append(response.response.name + ' ' + designation + ' <br> ' + 'Chamber Address :' + ' <br>' + chamber + ', ' + country).end();
    //         $('#doctor_description').append(response.response.description).end();
    //       $("#img1").attr("src", "uploads/doctor_avater1.jpg");
    //       if (typeof response.response.img_url !== 'undefined' && response.response.img_url !== '') {
    //         $("#img1").attr("src", response.response.img_url);
    //     }
    //     },
    //   });

    $("#visit_description").html(" ");
    $("#visit_charges").val(" ");
    $.ajax({
      url: "frontend/getDoctorVisit?id=" + doctorr,
      method: "GET",
      data: "",
      dataType: "json",
      success: function (response1) {
        $("#visit_description").html(response1.response).end();
        $(".addAppointmentForm")
          .find('[name="visit_id"]')
          .val(response1.responsee)
          .end();
      },
    });

    $("#visiting_place_list").html("");
    if (doctorr !== null) {
      $.ajax({
        url: "frontend/getDoctorVisitingPlace?id=" + doctorr,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          // $('#visiting_place_list').html('<input type="radio" id="+ permited_modules +" name="fav_language" value="HTML"><br>' + response.option);
          $("#visiting_place_list").html(
            '<label for="exampleInputEmail1"> </label><br>' + response.option
          );
        },
      });
    }
    setTimeout(function () {
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
      $.ajax({
        url: "frontend/getDoctorVisitChargess?id=" + visit_id,
        method: "GET",
        dataType: "json",
        success: function (response) {
          if (currency == "BDT") {
            var visit = response.response.visit_charges;
          }
          if (currency == "INR") {
            var visit = response.response.visit_charges_rupi;
          }
          if (currency == "USD") {
            var visit = response.response.visit_charges_doller;
          }

          var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
          var gateway_fee = (new_subtotal * 2.5) / 100;
          var total_doctor_amount =
            parseFloat(visit) +
            parseFloat(casetaker_fee) +
            parseFloat(onlinecenter_fee);

          if ($("#pay_for_courier").prop("checked") == true) {
            var courier = courier_fee;
          } else {
            var courier = 0;
          }
          $("#visit_charges").val(visit).end();
          $("#total_charges")
            .val(
              parseFloat(visit) +
                parseFloat(subtotal) +
                parseFloat(gateway_fee) +
                parseFloat(courier)
            )
            .end();
            $("#hidden_total_charges")
            .val(
              parseFloat(visit) +
                parseFloat(subtotal) +
                parseFloat(gateway_fee) +
                parseFloat(courier)
            )
            .end();  
          $("#doctor_amount").val(total_doctor_amount).end();
          $("#new_subtotal_fee").val(new_subtotal).end();
          $("#gateway_fee").append(gateway_fee).end();


        var total = parseFloat(visit) + parseFloat(subtotal) + parseFloat(gateway_fee) + parseFloat(courier)
                        
        var deposited_amount = $("#deposited_amount").val();
                        $("#due_amount").val(total - deposited_amount).end();



        },
      });
    }, 3000);
  });
});
$(document).ready(function () {
  $(document).on("click", ".appHospital", function () {
    "use strict";
    var id = $(this).data("id");
    $("#selectedSlot").val("");
    $("#selectedDoctor").val("");
    //        alert(id);
    //        var id = $('#appointment_id').val();
    //        var date = $('#date').val();
    //        var hospital = $('#hospital').val();
    //$('#adoctors').find('button').remove();

    $.ajax({
      url: "frontend/getAvailableDoctor?hospital_id=" + id,
      method: "GET",
      data: "",
      dataType: "json",
      success: function (response) {
        "use strict";
        $("#selectedHospital").val(id);
        var slots = response.adoctors;
        $(".hospitalDocs").empty();
        let html = "";
        if (slots.length == 0) {
          $(".hospitalDocs")
            .append('<div class="col-md-12 text-center">No Doctor Found!</div>')
            .end();
        } else {
          $.each(slots, function (key, value) {
            if (value.chamber_address === null) {
              var chamber = "";
            } else {
              var chamber = value.chamber_address;
            }
            if (value.visiting_place === null) {
              var place = "";
            } else {
              var place = value.visiting_place;
            }
            html +=
              '<div class="col-md-4"><div id="hospital" data-id="' +
              value.id +
              '" class="hospital green-1 hosp appDoc boxborder"><div class="col-sm-4 image2"><img src="' +
              value.img_url +
              '" class="image" ><p >Country : <span class="txtt">' +
              value.country +
              '</span></p><p>Visit Type : <span class="txtt">' +
              place +
              '</span></p></div><div class="col-sm-8 text-1"> <span>' +
              value.name +
              '</span><span class="txt">' +
              value.profile +
              " </span></div></div></div>";
          });
          $(".hospitalDocs").append(html).end();
        }
      },
    });

    // var hospital_id = $("#hospital_id").val();
    $("#my_select1_team_disabled").select2({
      // placeholder: '<?php echo lang('select_team'); ?>',
      allowClear: true,
      ajax: {
        url: "frontend/getTeamNamelist?id=" + id,
        type: "post",
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            searchTerm: params.term, // search term
          };
        },
        processResults: function (response) {
          return {
            results: response,
          };
        },
        cache: true,
      },
    });
  });

  $(document).on("click", ".appHospital", function () {
    "use strict";
    //        var id = $(this).data('id');
    $("#pos_select1").select2({
      placeholder: select_patient,
      closeOnSelect: true,
      ajax: {
        url: "frontend/getPatientInfo",
        dataType: "json",
        type: "post",
        delay: 250,
        data: function (params) {
          return {
            searchTerm: params.term, // search term
            catchange: $("#selectedHospital").val(),
            medid: $(this).val(),
            page: params.page,
          };
        },
        processResults: function (data, params) {
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
  });

  $(".appCategory").on("click", function () {
    "use strict";
    var id = $(this).data("id");

    $.ajax({
      url: "frontend/getHospitalByCategory?category=" + id,
      method: "GET",
      data: "",
      dataType: "json",
      success: function (response) {
        "use strict";
        $("#selectedCategory").val(id);
        var slots = response.hospitals;
        $(".hospitalOptions").empty();
        $(".hospitalDocs").empty();
        $("#selectedDoctor").val("");
        $("#selectedSlot").val("");
        $("#selectedHospital").val("");
        let html = "";
        if (slots.length == 0) {
          $(".hospitalOptions")
            .append(
              '<div class="col-md-12 text-center">No Hospital Found!</div>'
            )
            .end();
        } else {
          $.each(slots, function (key, value) {
            html +=
              '<div class="col-md-4"><div id="hospital" data-id="' +
              value.id +
              '" class=" hospital green hosp appHospital boxborder"><div class="col-sm-4"><img src="uploads/hospital-building.png" class="image" ></div><div class="col-sm-8 text"> <span>' +
              value.name +
              ' </span><span class="txt">' +
              value.phone +
              " </span></div></div></div>";
            //html += '<div id="hospital" data-id="'+value.id+'" class="hospital green hosp appDoc"><div class="col-sm-4"><img src="uploads/hospital-building.png" class="image" ></div><div class="col-sm-8 text"> <span>'+value.name+'</span><span class="txt"> </span></div></div>';
          });
          $(".hospitalOptions").append(html).end();
        }
      },
    });
  });
});
$(document).ready(function () {
  "use strict";
  var id = $("#appointment_id").val();
  var date = $("#date").val();
  var doctorr = $("#adoctors").val();
  $("#aslots").find("button").remove();

  $.ajax({
    url:
      "frontend/getAvailableSlotByDoctorByDateByJason?date=" +
      date +
      "&doctor=" +
      doctorr,
    method: "GET",
    data: "",
    dataType: "json",
    success: function (response) {
      "use strict";
      var slots = response.aslots;
      $.each(slots, function (key, value) {
        $("#aslots").append($("<button>").text(value).val(value)).end();
      });

      $("#aslots")
        .val(response.current_value)
        .find("button[value=" + response.current_value + "]")
        .attr("selected", true);

      if ($("#aslots").has("button").length == 0) {
        //if it is blank.
        $("#aslots")
          .append(
            $("<button>").text("No Further Time Slots").val("Not Selected")
          )
          .end();
      }
    },
  });
});

$(document).ready(function () {
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

  let dateTemp = $("#datepicker").datepicker("getDate");
  console.log(dateTemp);
  let date = "";
  if (dateTemp) {
    let day =
      dateTemp.getDate() < 9
        ? "0" + Number(dateTemp.getDate())
        : Number(dateTemp.getDate());
    let month =
      dateTemp.getMonth() < 9
        ? "0" + (Number(1) + Number(dateTemp.getMonth()))
        : Number(1) + Number(dateTemp.getMonth());
    let year = dateTemp.getFullYear();
    console.log(day);
    console.log(month);
    console.log(year);
    date = month + "/" + day + "/" + year;
    // date = day + "-" + month + "-" + year;
    $("#selectedDate2").val(date);
  }
  //var date = $('#datepicker').val();
  //$('#selectedDate2').val(date);
  
  var doctor = $("#selectedDoctor").val();
  if(doctor == null || doctor == " " ){
    var doctorr = $("#board_leader_id").val();
  }else{
    var doctorr = $("#selectedDoctor").val();
  }
  $("#selectedSlot").val("");
  $("#aslots").find("button").remove();

  if (date != "") {
    $.ajax({
      url:
        "frontend/getAvailableSlotByDoctorByDateByJason?date=" +
        date +
        "&doctor=" +
        doctorr,
      method: "GET",
      data: "",
      dataType: "json",
      success: function (response) {
        "use strict";

        var slots = response.aslots;
        $(".slot").empty();
        if (slots.length == 0) {
          $(".slot")
            .append(
              '<div class="col-md-12 text-center">No Available Slot Found!</div>'
            )
            .end();
        } else {
          $.each(slots, function (key, value) {
            $(".slot")
              .append(
                '<button type="button" data-id="' +
                  value +
                  '" class="btnn appSlot boxborder hosp">' +
                  value +
                  "</button>"
              )
              .end();
          });
        }
      },
    });
  }
}

$("#datepicker")
  .datepicker()
  .on("change", function () {
    alert("Hello");
  });

$("body").on("click", ".aslots", function () {
  var aslots = $(this).attr("data-item");
  $("#aslots").val(aslots);
  $(".aslots")
    .removeClass("btn-danger")
    .addClass("btn-success")
    .not(".disabled");
  $(this).removeClass("btn-success").addClass("btn-danger").not(".disabled");
});

$("body").on("click", ".hosp", function () {
  var hosp = $(this).attr("data-item");
  $("#hosp").val(hosp);
  $(".hosp")
    .removeClass("activeboxborder")
    .addClass("boxborder")
    .not(".disabled");
  $(this).removeClass("boxborder").addClass("activeboxborder").not(".disabled");
});

$(document).on("click", ".appSlot", function () {
  let val = $(this).data("id");
  $(".appSlot").removeClass("selected");
  $(this).addClass("selected");
  $("#selectedSlot").val(val);
  console.log(val);
});

$("#docSearch").on("keyup", function () {
  let id = $("#selectedHospital").val();
  let search = $("#docSearch").val();
  $.ajax({
    url:
      "frontend/searchAvailableDoctor?hospital_id=" + id + "&search=" + search,
    method: "GET",
    data: "",
    dataType: "json",
    success: function (response) {
      "use strict";
      var slots = response.adoctors;
      $(".hospitalDocs").empty();
      let html = "";
      if (slots.length == 0) {
        $(".hospitalDocs")
          .append('<div class="col-md-12 text-center">No Doctor Found!</div>')
          .end();
      } else {
        $.each(slots, function (key, value) {
          if (value.visiting_place === null) {
            var place = "";
          } else {
            var place = value.visiting_place;
          }
          html +=
            '<div class="col-md-4"><div id="hospital" data-id="' +
            value.id +
            '" class="hospital green-1 hosp appDoc boxborderr"><div class="col-sm-4 image2"><img src="' +
            value.img_url +
            '" class="image" ><p >Country : <span class="txtt">' +
            value.country +
            '</span></p><p>Visit Type : <span class="txtt">' +
            place +
            '</span></p></div><div class="col-sm-8 text-1"> <span>' +
            value.name +
            '</span><span class="txt">' +
            value.profile +
            " </span></div></div></div>";
        });
        $(".hospitalDocs").append(html).end();
      }
    },
  });
});

$(document).ready(function () {
  $(".pay_now_div").on("change", "#pay_now_appointment", function () {
    if ($(this).prop("checked") == true) {
      $(".deposit_type").removeClass("hidden");
      $(".addAppointmentForm").find('[name="deposit_type"]').trigger("reset");
      // $('.card').show();
    } else {
      $(".addAppointmentForm").find('[name="deposit_type"]').val("").end();
      $(".deposit_type").addClass("hidden");

      // $('.card').hide();
    }
  });
});

$(document).ready(function () {
  $(".pay_now_div").on("change", "#pay_now_appointment", function () {
    if ($(this).prop("checked") == true) {
      //            $('.deposit_type').removeClass('hidden');
      $(".addAppointmentForm").find('[name="deposit_type"]').trigger("reset");
      $(".card").show();
    } else {
      $(".addAppointmentForm").find('[name="deposit_type"]').val("").end();
      //            $('.deposit_type').addClass('hidden');

      $(".card").hide();
    }
  });
});
$(document.body).on("change", "#visit_description", function () {
  // Get the record's ID via attribute
  var id = $(this).val();

  var doctor = $("#selectedDoctor").val();
  $("#visit_charges").val(" ");

  $.ajax({
    url: "frontend/getDoctorVisitChargess?id=" + id + "&doctor=" + doctor,
    method: "GET",
    data: "",
    dataType: "json",
    success: function (response) {
      $("#visit_charges").val(response.response.visit_charges).end();
      // $("#visit_charges1").val(response.response.visit_charges).end();
    },
  });
});
$(document).ready(function () {
  "use strict";
  $(".hospital_category_div").on("change", ".hospital_category", function () {
    $(".card").hide();
    $(".addAppointmentForm").find('[name="stripe_publish"]').val(" ").end();
    var hospital_category = $(this).val();

    if ($(this).val().length === 0) {
      $("#selectedHospital").html(" ");
      $("#selectedHospital").val(null).trigger("change");
      $("<option/>", {
        value: "",
        text: "Choose Hospital",
      }).appendTo("#selectedHospital");
    } else {
      $.ajax({
        url: "frontend/getHospitalByCategoryy?category=" + hospital_category,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          if (response.hospital != null) {
            $("#selectedHospital").html(" ");
            $("<option/>", {
              value: "",
              text: "Choose Hospital",
              disabled: true,
              selected: true,
              hidden: true,
            }).appendTo("#selectedHospital");
            $.each(response.hospital, function () {
              if (hospital !== null) {
                if (hospital == this.id) {
                  $("<option/>", {
                    value: this.id,
                    text: this.name,
                    locked: "true",
                  }).appendTo("#selectedHospital");
                } else {
                  $("<option/>", {
                    value: this.id,
                    text: this.name,
                  }).appendTo("#selectedHospital");
                }
              } else {
                $("<option/>", {
                  value: this.id,
                  text: this.name,
                }).appendTo("#selectedHospital");
              }
            });
          } else {
            $("#selectedHospital").html(" ");
            $("#selectedHospital").val(null).trigger("change");
            $("<option/>", {
              value: "",
              text: "Choose Hospital",
            }).appendTo("#selectedHospital");
          }
        },
      });
    }
  });
  $(".hospitalOptions").on("change", "#selectedHospital", function () {
    //    $('#hospitalchoose1').on('change', function () {
    var v = $("select.selecttype option:selected").val();
    //var v = $('#selecttype').val();
    $(".addAppointmentForm").find('[name="stripe_publish"]').val();
    if (v == "Card") {
      $(".cardsubmit").removeClass("hidden");
      $(".cashsubmit").addClass("hidden");

      $(".card").show();
      $.ajax({
        url: "frontend/getHospitalSettings?id=" + $(this).val(),
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response1) {
          if (response1.settings.payment_gateway === "Stripe") {
            $("#submit-btn").attr("onClick", "stripePay(event)");
            Stripe.setPublishableKey(response1.gateway_details.publish);
            $(".addAppointmentForm")
              .find('[name="stripe_publish"]')
              .val(response1.gateway_details.publish)
              .end();
          } else {
            $(".addAppointmentForm")
              .find('[name="stripe_publish"]')
              .val(" ")
              .end();
            $("#submit-btn").removeAttr("onClick");
          }
          if (response1.settings.payment_gateway === "PayPal") {
            $(".card_type").show();
            $(".cardholder_name").show();
            $(".cardNumber").show();
            $(".expireNumber").show();
            $(".cvvNumber").show();
          } else if (response1.settings.payment_gateway === "Stripe") {
            $(".cardNumber").show();
            $(".expireNumber").show();
            $(".cvvNumber").show();
            $(".card_type").hide();
            $(".cardholder_name").hide();
          } else {
            $(".card_type").hide();
            $(".cardholder_name").hide();
            $(".cardNumber").hide();
            $(".expireNumber").hide();
            $(".cvvNumber").hide();
          }
        },
      });
    } else {
      $(".card").hide();
      $(".cashsubmit").removeClass("hidden");
      $(".cardsubmit").addClass("hidden");
      // $("#amount_received").prop('required', false);
      //$('#amount_received').removeAttr('required');
    }
    if ($("#count").val() != "1") {
      $("#pos_select option:selected").removeAttr("selected");
      $("#pos_select").html("");
      $("#adoctors option:selected").removeAttr("selected");
      $("#adoctors").html("");
    }

    $("#count").val("2");
  });
});

$(document).ready(function () {
  "use strict";
  $(".card").hide();
  $(document.body).on("change", "#selecttype", function () {
    //var v = $('#selecttype').val();
    var v = $("select.selecttype option:selected").val();
    if (v == "Card") {
      $(".cardsubmit").removeClass("hidden");
      $(".cashsubmit").addClass("hidden");
      $(".paytm").addClass("hidden");
      $(".card").show();
      $.ajax({
        url: "frontend/getHospitalSettings?id=" + $("#selectedHospital").val(),
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response1) {
          if (response1.settings.payment_gateway === "Stripe") {
            $("#submit-btn").attr("onClick", "stripePay(event)");
            Stripe.setPublishableKey(response1.gateway_details.publish);
            $(".addAppointmentForm")
              .find('[name="stripe_publish"]')
              .val(response1.gateway_details.publish)
              .end();
          } else {
            $("#submit-btn").removeAttr("onClick");
            $(".addAppointmentForm")
              .find('[name="stripe_publish"]')
              .val(" ")
              .end();
          }
          if (response1.settings.payment_gateway === "PayPal") {
            $(".card_type").show();
            $(".cardholder_name").show();
            $(".cardNumber").show();
            $(".expireNumber").show();
            $(".cvvNumber").show();
          } else if (response1.settings.payment_gateway === "Stripe") {
            $(".cardNumber").show();
            $(".expireNumber").show();
            $(".cvvNumber").show();
            $(".card_type").hide();
            $(".cardholder_name").hide();
          } else {
            $(".card_type").hide();
            $(".cardholder_name").hide();
            $(".cardNumber").hide();
            $(".expireNumber").hide();
            $(".cvvNumber").hide();
          }
        },
      });
    }
    if (v == "Paytm") {
      $(".card").hide();

      $(".paytm").removeClass("hidden");
      $(".cashsubmit").removeClass("hidden");
      $(".cardsubmit").addClass("hidden");
    }
    if (v == "Cash") {
      $(".card").hide();
      $(".paytm").addClass("hidden");
      $(".cashsubmit").removeClass("hidden");
      $(".cardsubmit").addClass("hidden");
    }
    if (v == "Aamarpay") {
      $(".card").hide();
      $(".paytm").addClass("hidden");
      $(".cashsubmit").removeClass("hidden");
      $(".cardsubmit").addClass("hidden");
    }
  });
});

function cardValidation() {
  var valid = true;
  var cardNumber = $("#card").val();
  var expire = $("#expire").val();
  var cvc = $("#cvv").val();

  $("#error-message").html("").hide();

  if (cardNumber.trim() == "") {
    valid = false;
  }

  if (expire.trim() == "") {
    valid = false;
  }
  if (cvc.trim() == "") {
    valid = false;
  }

  if (valid == false) {
    $("#error-message").html("All Fields are required").show();
  }

  return valid;
}
//set your publishable key

//callback to handle the response from stripe
function stripeResponseHandler(status, response) {
  if (response.error) {
    //enable the submit button
    $("#submit-btn").show();
    $("#loader").css("display", "none");
    //display the errors on the form

    $("#error-message").html(response.error.message).show();
  } else {
    //get token id
    var token = response["id"];

    $("#token").val(token);
    $(".addAppointmentForm").append(
      "<input type='hidden' name='token' value='" + token + "' />"
    );
    //submit form to the server
    $(".addAppointmentForm").submit();
  }
}

function stripePay(e) {
  e.preventDefault();
  var valid = cardValidation();

  if (valid == true) {
    $("#submit-btn").attr("disabled", true);
    $("#loader").css("display", "inline-block");
    var expire = $("#expire").val();
    var arr = expire.split("/");
    Stripe.createToken(
      {
        number: $("#card").val(),
        cvc: $("#cvv").val(),
        exp_month: arr[0],
        exp_year: arr[1],
      },
      stripeResponseHandler
    );

    //submit from callback
    return false;
  }
}
$(document).ready(function () {
  $("#adoctors").change(function () {
    var id = $(this).val();
    $("#doctor_name").html("").end();

    $.ajax({
      url: "doctor/getDoctorDetails?id=" + id,
      method: "GET",
      dataType: "json",
      success: function (response) {
        var designation = response.response.profile;
        var description = response.response.description;
        var country = response.response.country;
        var chamber = response.response.chamber_address;
        $("#doctor_name")
          .append(
            response.response.name +
              " " +
              designation +
              " " +
              description +
              " " +
              chamber +
              " <br>" +
              country
          )
          .end();
      },
    });
  });
});
//         $('.appHospital').on("click", function() {
//            alert($(this).data('id'));
//        })
$(document).ready(function () {
  $(".leader_title").hide();
  $(".custom_board").hide();
  $(".board_appointment").hide();
  $(".custom_appointment").hide();
  $(".app_type_s").addClass("activeboxborder").not(".disabled");
  $("body").on("click", "#app_type", function () {
    var v = $(this).attr("data-id");
    console.log(v);
    if (v === "Single Doctor") {
      $("#board_iid").val(" ");
      $("#selectedDoctor").val(" ");
      $(".doc_title").show();
      $(".leader_title").hide();
      $(".board_appointment").hide();
      $(".single_appointment").show();
      $(".custom_appointment").hide();
      $(".visit_div").show();
      $(".app_type_s").addClass("activeboxborder").not(".disabled");
      $(".app_type_m").removeClass("activeboxborder").not(".disabled");
      $(".app_type_c").removeClass("activeboxborder").not(".disabled");
      $("#total_charges").val(" ");
     
      $(".slot").empty();
      
    }
    if (v === "Medical Board") {
      $("#board_iid").val(" ");
      $("#selectedDoctor").val(" ");
      $(".single_appointment").hide();
      $(".board_appointment").show();
      $(".custom_appointment").hide();
      $(".visit_div").hide();
      $(".hospital").removeClass("activeboxborder").not(".disabled");
      $(".app_type_m").addClass("activeboxborder").not(".disabled");
      $(".app_type_s").removeClass("activeboxborder").not(".disabled");
      $(".app_type_c").removeClass("activeboxborder").not(".disabled");
      $("#total_charges").val(" ");
    
      $(".slot").empty();
    }
    if (v === "Custom Board") {
      $("#board_iid").val(" ");
      $("#selectedDoctor").val(" ");
      $(".doc_title").hide();
      $(".leader_title").show();
      $(".single_appointment").show();
      $(".custom_appointment").show();
      $(".board_appointment").hide();
      $(".visit_div").hide();
      $(".app_type_c").addClass("activeboxborder").not(".disabled");
      $(".app_type_m").removeClass("activeboxborder").not(".disabled");
      $(".app_type_s").removeClass("activeboxborder").not(".disabled");
      $("#total_charges").val(" ");
     
      $(".slot").empty();
    }
  });
});

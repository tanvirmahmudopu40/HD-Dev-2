"use strict";
$(document).ready(function () {
  "use strict";
  $(".pos_client").hide();
  $(document.body).on("change", "#pos_select", function () {
    "use strict";
    var v = $("select.pos_select option:selected").val();
    if (v === "add_new") {
      $(".pos_client").show();
    } else {
      $(".pos_client").hide();
    }
  });
  $(".pos_client1").hide();
  $(document.body).on("change", "#pos_select1", function () {
    "use strict";
    var v = $("select.pos_select1 option:selected").val();
    if (v === "add_new") {
      $(".pos_client1").show();
    } else {
      $(".pos_client1").hide();
    }
  });
});

$(document).ready(function () {
  $("#visit_description").change(function () {
    var id = $(this).val();
    $("#new_subtotal_fee").empty();
    $("#gateway_fee").empty();
    $("#visit_charges").val(" ");
    $("#total_charges").val(" ");
    var doctor_id = $("#doctor_id").val();
    var total_fee = $("#total_fee").val();
    var courier_fee = $("#courier_fee").val();
    var casetaker_fee = $("#casetaker_fee").val();
    var onlinecenter_fee = $("#onlinecenter_fee").val();
    var currency = $("#currency").val();
    var subtotal = $("#subtotal_fee").val();
    $.ajax({
      url: "doctor/getDoctorVisitCharges?id=" + id,
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
        if ($("#pay_for_courier").prop("checked") == true) {
          var courier = courier_fee;
        } else {
          var courier = 0;
        }
        if (doctor_id.trim() != "") {
          var total_doctor_amount =
            parseFloat(visit) +
            parseFloat(casetaker_fee) +
            parseFloat(onlinecenter_fee);
        } else {
          var total_doctor_amount = visit;
        }
        var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
        var gateway_fee = (new_subtotal * 2.5) / 100;

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
        var total =
          parseFloat(visit) +
          parseFloat(subtotal) +
          parseFloat(gateway_fee) +
          parseFloat(courier);

        var deposited_amount = $("#deposited_amount").val();
        $("#due_amount")
          .val(total - deposited_amount)
          .end();
      },
    });
  });
});

$(document).ready(function () {
  $("#visit_description1").change(function () {
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
        if ($("#pay_for_courier1").prop("checked") == true) {
          var courier = courier_fee;
        } else {
          var courier = 0;
        }
        var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
        var gateway_fee = (new_subtotal * 2.5) / 100;
        $("#visit_charges1").val(visit).end();

        $("#total_charges1")
          .val(
            parseFloat(visit) +
              parseFloat(subtotal) +
              parseFloat(gateway_fee) +
              parseFloat(courier)
          )
          .end();
        $("#hidden_total_charges1")
          .val(
            parseFloat(visit) +
              parseFloat(subtotal) +
              parseFloat(gateway_fee) +
              parseFloat(courier)
          )
          .end();
        $("#new_subtotal_fee1").val(parseFloat(new_subtotal)).end();
        $("#gateway_fee1").append(parseFloat(gateway_fee)).end();
        if (doctor_id.trim() != "") {
          var total_doctor_amount =
            parseFloat(visit) +
            parseFloat(casetaker_fee) +
            parseFloat(onlinecenter_fee);
        } else {
          var total_doctor_amount = visit;
        }
        $("#doctor_amount1").val(total_doctor_amount).end();
        // $("#new_subtotal_fee1").val(100).end();
        // $("#gateway_fee1").append(gateway_fee).end();
      },
    });
  });
});

$(document).ready(function () {
  $("#adoctors").change(function () {
    var id = $(this).val();
    $("#doctor_name").html("").end();
    //   $('#chamber_address').html("").end();

    $.ajax({
      url: "doctor/getDoctorDetails?id=" + id,
      method: "GET",
      dataType: "json",
      success: function (response) {
        var designation = response.response.profile;
        var visiting_place = response.response.visiting_place;
        var country = response.response.country;
        var chamber = response.response.chamber_address;
        if (visiting_place === null) {
          var visit_type = "";
        } else {
          var visit_type = response.response.visiting_place;
        }
        //   $("#doctor_name").val(response.response.name).end();
        $("#doctor_name")
          .append(
            response.response.name +
              " " +
              designation +
              " <br> " +
              "Chamber Address : " +
              " <br>" +
              chamber +
              " <br> " +
              "Country : " +
              "<span style='color:green;font-weight:600;'> " +
              country +
              "</span>" +
              " <br> " +
              "Visit Type : " +
              "<span style='color:green;font-weight:600;font-size:15px'> " +
              visit_type +
              "</span>"
          )
          .end();
        $("#chamber_address").append("Chamber Address").end();
        //   $('#doctor_name').append(response.response.name).end();
        //   $('#doctor_name').append(response.response.name).end();
        // var discount = $("#discount1").val();
        // $("#grand_total1")
        //   .val(parseFloat(response.response.visit_charges - discount))
        //   .end();
      },
    });
  });
});

$(document).ready(function () {
  $("#adoctors1").change(function () {
    var id = $(this).val();
    $("#doctor_name1").html("").end();

    $.ajax({
      url: "doctor/getDoctorDetails?id=" + id,
      method: "GET",
      dataType: "json",
      success: function (response) {
        var designation = response.response.profile;
        var description = response.response.description;
        var country = response.response.country;
        var chamber = response.response.chamber_address;
        //   $("#doctor_name").val(response.response.name).end();
        $("#doctor_name1")
          .append(
            response.response.name +
              " " +
              designation +
              " <br> " +
              "Chamber Address :" +
              " <br>" +
              chamber +
              " <br>" +
              "Country : " +
              "<span style='color:green;font-weight:600;'> " +
              country +
              "</span>" +
              " <br> " +
              "Visit Type : " +
              "<span style='color:green;font-weight:600;font-size:15px'> " +
              visit_type +
              "</span>"
          )
          .end();
        //   $('#doctor_name').append(response.response.name).end();
        //   $('#doctor_name').append(response.response.name).end();
        //   $('#doctor_name').append(response.response.name).end();
        // var discount = $("#discount1").val();
        // $("#grand_total1")
        //   .val(parseFloat(response.response.visit_charges - discount))
        //   .end();
      },
    });
  });
});

$(document).ready(function () {
  $(".pay_now_div").on("change", "#pay_now_appointment", function () {
    if ($(this).prop("checked") == true) {
      $(".deposit_type").removeClass("hidden");
      $("#addAppointmentForm").find('[name="deposit_type"]').trigger("reset");
    } else {
      $("#addAppointmentForm").find('[name="deposit_type"]').val("").end();
      $(".deposit_type").addClass("hidden");

      $(".card").hide();
    }
  });
  $(".pay_now_div1").on("change", "#pay_now_appointment", function () {
    if ($(this).prop("checked") == true) {
      $(".deposit_type1").removeClass("hidden");
      $("#editAppointmentForm").find('[name="deposit_type"]').trigger("reset");
    } else {
      $("#editAppointmentForm").find('[name="deposit_type"]').val("").end();
      $(".deposit_type1").addClass("hidden");

      $(".card1").hide();
    }
  });
});

$(document).ready(function () {
  "use strict";
  $(".card").hide();
  $(".paytm").hide();
  $(document.body).on("change", "#selecttype", function () {
    $("#gateway_fee").empty();
    var v = $("select.selecttype option:selected").val();
    var custom_doc_fee = $("#custom_doc_fee").val();
    var hidden_total_charges = $("#hidden_total_charges").val();
    // var gateway_fee = $("#gateway_fee").text();
    var gateway_fee = $("#gateway_amount").val();
    var total =
      parseFloat(hidden_total_charges) +
      parseFloat(gateway_fee) +
      parseFloat(custom_doc_fee);

    var deposited_amount = $("#deposited_amount").val();
    if (v == "Paytm") {
      $(".card").hide();
      $(".cashsubmit").removeClass("hidden");
      $(".cardsubmit").addClass("hidden");
      $(".paytm").show();
      $("#total_charges").val(total);
      $("#due_amount")
        .val(total - deposited_amount)
        .end();
        $('#gateway_fee').append(gateway_fee).end();
    }
    if (v == "Aamarpay") {
      $(".card").hide();
      $(".paytm").hide();
      $(".cashsubmit").removeClass("hidden");
      $(".cardsubmit").addClass("hidden");
      $("#total_charges").val(total);
      $("#due_amount")
        .val(total - deposited_amount)
        .end();
        $('#gateway_fee').append(gateway_fee).end();
    }
    if (v == "Card") {
      $(".paytm").hide();
      $(".cardsubmit").removeClass("hidden");
      $(".cashsubmit").addClass("hidden");

      $(".card").show();
      $("#total_charges").val(total);
      $("#due_amount")
        .val(total - deposited_amount)
        .end();
        $('#gateway_fee').append(gateway_fee).end();
    }
    if (v == "Cash") {
      $(".card").hide();
      $(".paytm").hide();
      $(".cashsubmit").removeClass("hidden");
      $(".cardsubmit").addClass("hidden");
      $("#total_charges").val(
        parseFloat(hidden_total_charges) + parseFloat(custom_doc_fee)
      );
      $("#due_amount")
        .val(
          parseFloat(hidden_total_charges) +
            parseFloat(custom_doc_fee) -
            deposited_amount
        )
        .end();
        $('#gateway_fee').append(0).end();
    }
  });
  $(".card1").hide();
  $(".paytm1").hide();
  $(document.body).on("change", "#selecttype1", function () {
    var v = $("select.selecttype1 option:selected").val();
    if (v == "Paytm") {
      $(".card1").hide();
      $(".paytm1").show();
      $(".cashsubmit1").removeClass("hidden");
      $(".cardsubmit1").addClass("hidden");
    }
    if (v == "Aamarpay") {
      $(".card1").hide();
      $(".paytm1").hide();
      $(".cashsubmit1").removeClass("hidden");
      $(".cardsubmit1").addClass("hidden");
    }
    if (v == "Card") {
      $(".paytm1").hide();
      $(".cardsubmit1").removeClass("hidden");
      $(".cashsubmit1").addClass("hidden");

      $(".card1").show();
    }
    if (v == "Cash") {
      $(".card1").hide();
      $(".paytm1").hide();
      $(".cashsubmit1").removeClass("hidden");
      $(".cardsubmit1").addClass("hidden");
    }
  });
});

if (case_taker_online === "no") {
  $(document).ready(function () {
    "use strict";
    $("#pos_select").select2({
      placeholder: select_patient,
      allowClear: true,
      ajax: {
        url: "patient/getPatientinfoWithAddNewOption",
        type: "post",
        dataType: "json",
        delay: 250,
        data: function (params) {
          "use strict";
          return {
            searchTerm: params.term, // search term
          };
        },
        processResults: function (response) {
          "use strict";
          return {
            results: response,
          };
        },
        cache: true,
      },
    });
    $("#pos_select1").select2({
      placeholder: select_patient,
      allowClear: true,
      ajax: {
        url: "patient/getPatientinfoWithAddNewOption",
        type: "post",
        dataType: "json",
        delay: 250,
        data: function (params) {
          "use strict";
          return {
            searchTerm: params.term, // search term
          };
        },
        processResults: function (response) {
          "use strict";
          return {
            results: response,
          };
        },
        cache: true,
      },
    });

    $("#adoctors").select2({
      placeholder: select_doctor,
      allowClear: true,
      ajax: {
        url: "doctor/getDoctorInfo",
        type: "post",
        dataType: "json",
        delay: 250,
        data: function (params) {
          "use strict";
          return {
            searchTerm: params.term, // search term
          };
        },
        processResults: function (response) {
          "use strict";
          return {
            results: response,
          };
        },
        cache: true,
      },
    });
    $("#adoctors1").select2({
      placeholder: select_doctor,
      allowClear: true,
      ajax: {
        url: "doctor/getDoctorInfo",
        type: "post",
        dataType: "json",
        delay: 250,
        data: function (params) {
          "use strict";
          return {
            searchTerm: params.term, // search term
          };
        },
        processResults: function (response) {
          "use strict";
          return {
            results: response,
          };
        },
        cache: true,
      },
    });
  });

  // $(document).ready(function () {

  //   var onlinecenter_id = $("#onlinecenter_id").val();
  //   var casetaker_id = $("#casetaker_id").val();
  //   var doctor_id = $("#doctor_id").val();

  //   $.ajax({
  //     url: "appointment/getHospitalCommissionSettings",
  //     method: "GET",
  //     dataType: "json",
  //     success: function (response) {
  //       var casetaker_fee = response.commission.casetaker_fee;
  //       var onlinecenter_fee = response.commission.onlinecenter_fee;
  //       var developer_fee = response.commission.developer_fee;
  //       var hospital_fee = response.commission.current_hospital;
  //       var superadmin_fee = response.commission.superadmin_fee;
  //       var medicine_fee = response.commission.medicine_fee;

  //       var courier_fee = response.commission.courier_fee;

  //       if (doctor_id.trim() != "") {
  //         var total_fee =
  //           parseFloat(casetaker_fee) +
  //           parseFloat(onlinecenter_fee) +
  //           parseFloat(developer_fee) +
  //           parseFloat(hospital_fee) +
  //           parseFloat(superadmin_fee) +
  //           parseFloat(medicine_fee) +
  //           parseFloat(courier_fee);

  //       } else {
  //         var total_fee =
  //           parseFloat(casetaker_fee) +
  //           parseFloat(onlinecenter_fee) +
  //           parseFloat(developer_fee) +
  //           parseFloat(hospital_fee) +
  //           parseFloat(superadmin_fee) +
  //           parseFloat(medicine_fee) +
  //           parseFloat(courier_fee);

  //       }

  //       $("#casetaker_fee").val(casetaker_fee).end();
  //       $("#onlinecenter_fee").val(onlinecenter_fee).end();
  //       $("#developer_fee").val(developer_fee).end();
  //       $("#hospital_fee").val(hospital_fee).end();
  //       $("#superadmin_fee").val(superadmin_fee).end();
  //       $("#medicine_fee").val(medicine_fee).end();
  //       $("#courier_fee").val(courier_fee).end();
  //       $("#total_fee").val(total_fee).end();

  //     },
  //   });
  // });

  Stripe.setPublishableKey(publish);
} else {
  $(document).ready(function () {
    "use strict";
    $(".hospital_category_div").on("change", "#hospital_category", function () {
      $(".card").hide();
      $("#addAppointmentForm").find('[name="stripe_publish"]').val(" ").end();
      var hospital_category = $(this).val();

      if ($(this).val().length === 0) {
        $("#hospitalchoose1").html(" ");
        $("#hospitalchoose1").val(null).trigger("change");
        $("<option/>", {
          value: "",
          text: "Choose Hospital",
        }).appendTo("#hospitalchoose1");
      } else {
        $.ajax({
          url:
            "appointment/getHospitalByCategory?category=" + hospital_category,
          method: "GET",
          data: "",
          dataType: "json",
          success: function (response) {
            if (response.hospital != null) {
              $("#hospitalchoose1").html(" ");
              $("<option/>", {
                value: "",
                text: "Choose Hospital",
                disabled: true,
                selected: true,
                hidden: true,
              }).appendTo("#hospitalchoose1");
              $.each(response.hospital, function () {
                if (hospital !== null) {
                  if (hospital == this.id) {
                    $("<option/>", {
                      value: this.id,
                      text: this.name,
                      locked: "true",
                    }).appendTo("#hospitalchoose1");
                  } else {
                    $("<option/>", {
                      value: this.id,
                      text: this.name,
                    }).appendTo("#hospitalchoose1");
                  }
                } else {
                  $("<option/>", {
                    value: this.id,
                    text: this.name,
                  }).appendTo("#hospitalchoose1");
                }
              });
            } else {
              $("#hospitalchoose1").html(" ");
              $("#hospitalchoose1").val(null).trigger("change");
              $("<option/>", {
                value: "",
                text: "Choose Hospital",
              }).appendTo("#hospitalchoose1");
            }
          },
        });
      }
    });
    $(".hospital_div").on("change", "#hospitalchoose1", function () {
      //    $('#hospitalchoose1').on('change', function () {
      var v = $("select.selecttype option:selected").val();
      $("#addAppointmentForm").find('[name="stripe_publish"]').val();
      if (v == "Card") {
        $(".cardsubmit").removeClass("hidden");
        $(".cashsubmit").addClass("hidden");

        $(".card").show();
        $.ajax({
          url: "appointment/getHospitalSettings?id=" + $(this).val(),
          method: "GET",
          data: "",
          dataType: "json",
          success: function (response1) {
            if (response1.settings.payment_gateway === "Stripe") {
              $("#submit-btn").attr("onClick", "stripePay(event)");
              Stripe.setPublishableKey(response1.gateway_details.publish);
              $("#addAppointmentForm")
                .find('[name="stripe_publish"]')
                .val(response1.gateway_details.publish)
                .end();
            } else {
              $("#addAppointmentForm")
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

      $("#pos_select").select2({
        placeholder: select_patient,
        closeOnSelect: true,
        ajax: {
          url: "onlinecenter/getPatientInfo",
          dataType: "json",
          type: "post",
          delay: 250,
          data: function (params) {
            return {
              searchTerm: params.term, // search term
              catchange: $("#hospitalchoose1").val(),
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

      $("#adoctors").select2({
        placeholder: select_doctor,
        closeOnSelect: true,
        ajax: {
          url: "onlinecenter/getDoctorInfo",
          dataType: "json",
          type: "post",
          delay: 250,
          data: function (params) {
            return {
              searchTerm: params.term, // search term
              catchange: $("#hospitalchoose1").val(),
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
      $("#count").val("2");
    });

    $("#adoctors").select2({
      placeholder: select_doctor,
      closeOnSelect: true,
      ajax: {
        url: "onlinecenter/getDoctorInfo",
        dataType: "json",
        type: "post",
        delay: 250,
        data: function (params) {
          return {
            searchTerm: params.term, // search term
            catchange: $("#hospitalchoose1").val(),
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
    $("#pos_select").select2({
      placeholder: select_patient,
      closeOnSelect: true,
      ajax: {
        url: "onlinecenter/getPatientInfo",
        dataType: "json",
        type: "post",
        delay: 250,
        data: function (params) {
          return {
            searchTerm: params.term, // search term
            catchange: $("#hospitalchoose1").val(),
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

  $(document).ready(function () {
    "use strict";
    $(".card").hide();
    $(document.body).on("change", "#selecttype", function () {
      var v = $("select.selecttype option:selected").val();
      if (v == "Card") {
        $(".cardsubmit").removeClass("hidden");
        $(".cashsubmit").addClass("hidden");

        $(".card").show();
        $.ajax({
          url:
            "appointment/getHospitalSettings?id=" + $("#hospitalchoose1").val(),
          method: "GET",
          data: "",
          dataType: "json",
          success: function (response1) {
            if (response1.settings.payment_gateway === "Stripe") {
              $("#submit-btn").attr("onClick", "stripePay(event)");
              Stripe.setPublishableKey(response1.gateway_details.publish);
              $("#addAppointmentForm")
                .find('[name="stripe_publish"]')
                .val(response1.gateway_details.publish)
                .end();
            } else {
              $("#submit-btn").removeAttr("onClick");
              $("#addAppointmentForm")
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
      } else {
        $(".card").hide();
        $(".cashsubmit").removeClass("hidden");
        $(".cardsubmit").addClass("hidden");
      }
    });
  });
  ///editpurpose

  $(document).ready(function () {
    "use strict";
    $(".hospital_category_div1").on(
      "change",
      "#hospital_category1",
      function () {
        $(".card1").hide();
        $("#editAppointmentForm")
          .find('[name="stripe_publish"]')
          .val(" ")
          .end();
        var hospital_category = $(this).val();

        if ($(this).val().length === 0) {
          $("#hospitalchoose").html(" ");
          $("#hospitalchoose").val(null).trigger("change");
          $("<option/>", {
            value: "",
            text: "Choose Hospital",
          }).appendTo("#hospitalchoose");
        } else {
          $.ajax({
            url:
              "appointment/getHospitalByCategory?category=" + hospital_category,
            method: "GET",
            data: "",
            dataType: "json",
            success: function (response) {
              if (response.hospital != null) {
                $("#hospitalchoose1").html(" ");
                $("<option/>", {
                  value: "",
                  text: "Choose Hospital",
                  disabled: true,
                  selected: true,
                  hidden: true,
                }).appendTo("#hospitalchoose");
                $.each(response.hospital, function () {
                  if (hospital !== null) {
                    if (hospital == this.id) {
                      $("<option/>", {
                        value: this.id,
                        text: this.name,
                        locked: "true",
                      }).appendTo("#hospitalchoose");
                    } else {
                      $("<option/>", {
                        value: this.id,
                        text: this.name,
                      }).appendTo("#hospitalchoose");
                    }
                  } else {
                    $("<option/>", {
                      value: this.id,
                      text: this.name,
                    }).appendTo("#hospitalchoose");
                  }
                });
              } else {
                $("#hospitalchoose").html(" ");
                $("#hospitalchoose").val(null).trigger("change");
                $("<option/>", {
                  value: "",
                  text: "Choose Hospital",
                }).appendTo("#hospitalchoose");
              }
            },
          });
        }
      }
    );
    $(".hospital_div1").on("change", "#hospitalchoose", function () {
      //    $('#hospitalchoose1').on('change', function () {
      var v = $("select.selecttype1 option:selected").val();
      $("#editAppointmentForm").find('[name="stripe_publish"]').val();
      if (v == "Card") {
        $(".cardsubmit1").removeClass("hidden");
        $(".cashsubmit1").addClass("hidden");

        $(".card1").show();
        $.ajax({
          url:
            "appointment/getHospitalSettings?id=" + $("#hospitalchoose").val(),
          method: "GET",
          data: "",
          dataType: "json",
          success: function (response1) {
            if (response1.settings.payment_gateway === "Stripe") {
              $("#submit-btn1").attr("onClick", "stripePay1(event)");
              Stripe.setPublishableKey(response1.gateway_details.publish);
              $("#editAppointmentForm")
                .find('[name="stripe_publish"]')
                .val(response1.gateway_details.publish)
                .end();
            } else {
              $("#editAppointmentForm")
                .find('[name="stripe_publish"]')
                .val(" ")
                .end();
              $("#submit-btn1").removeAttr("onClick");
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
        $(".card1").hide();
        $(".cashsubmit1").removeClass("hidden");
        $(".cardsubmit1").addClass("hidden");
        // $("#amount_received").prop('required', false);
        //$('#amount_received').removeAttr('required');
      }
      if ($("#count").val() != "1") {
        $("#pos_select1 option:selected").removeAttr("selected");
        $("#pos_select1").html("");
        $("#adoctors1 option:selected").removeAttr("selected");
        $("#adoctors1").html("");
      }

      $("#pos_select1").select2({
        placeholder: select_patient,
        closeOnSelect: true,
        ajax: {
          url: "onlinecenter/getPatientInfo",
          dataType: "json",
          type: "post",
          delay: 250,
          data: function (params) {
            return {
              searchTerm: params.term, // search term
              catchange: $("#hospitalchoose").val(),
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

      $("#adoctors1").select2({
        placeholder: select_doctor,
        closeOnSelect: true,
        ajax: {
          url: "onlinecenter/getDoctorInfo",
          dataType: "json",
          type: "post",
          delay: 250,
          data: function (params) {
            return {
              searchTerm: params.term, // search term
              catchange: $("#hospitalchoose").val(),
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
      $("#count").val("2");
    });

    $("#adoctors1").select2({
      placeholder: select_doctor,
      closeOnSelect: true,
      ajax: {
        url: "onlinecenter/getDoctorInfo",
        dataType: "json",
        type: "post",
        delay: 250,
        data: function (params) {
          return {
            searchTerm: params.term, // search term
            catchange: $("#hospitalchoose").val(),
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
    $("#pos_select1").select2({
      placeholder: select_patient,
      closeOnSelect: true,
      ajax: {
        url: "onlinecenter/getPatientInfo",
        dataType: "json",
        type: "post",
        delay: 250,
        data: function (params) {
          return {
            searchTerm: params.term, // search term
            catchange: $("#hospitalchoose").val(),
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

  $(document).ready(function () {
    "use strict";
    $(".card1").hide();
    $(document.body).on("change", "#selecttype1", function () {
      var v = $("select.selecttype1 option:selected").val();
      if (v == "Card") {
        $(".cardsubmit1").removeClass("hidden");
        $(".cashsubmit1").addClass("hidden");

        $(".card1").show();
        $.ajax({
          url:
            "appointment/getHospitalSettings?id=" + $("#hospitalchoose").val(),
          method: "GET",
          data: "",
          dataType: "json",
          success: function (response1) {
            if (response1.settings.payment_gateway === "Stripe") {
              $("#submit-btn1").attr("onClick", "stripePay1(event)");
              Stripe.setPublishableKey(response1.gateway_details.publish);
              $("#editAppointmentForm")
                .find('[name="stripe_publish"]')
                .val(response1.gateway_details.publish)
                .end();
            } else {
              $("#submit-btn1").removeAttr("onClick");
              $("#editAppointmentForm")
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
      } else {
        $(".card1").hide();
        $(".cashsubmit1").removeClass("hidden");
        $(".cardsubmit1").addClass("hidden");
      }
    });
  });
  //--------------------------
}

$(document).ready(function () {
  $("#adoctors").change(function () {
    var onlinecenter_id = $("#onlinecenter_id").val();
    var casetaker_id = $("#casetaker_id").val();
    var doctor_id = $(this).val();
    var currency = $("#currency").val();
    $("#new_subtotal_fee").empty();
    $("#gateway_fee").empty();
    $("#shipping_fee").empty();

    $.ajax({
      url: "appointment/getDoctorCommissionSettings?id=" + doctor_id,
      method: "GET",
      dataType: "json",
      success: function (response) {
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
  });
});

$(document).ready(function () {
  $("#currency").change(function () {
    var onlinecenter_id = $("#onlinecenter_id").val();
    var casetaker_id = $("#casetaker_id").val();
    var doctor_id = $("#adoctors").val();
    var currency = $("#currency").val();
    var visit_description = $("#visit_description").val();
    $("#new_subtotal_fee").empty();
    $("#gateway_fee").empty();
    $("#shipping_fee").empty();
    var subtotal = $("#subtotal_fee").val();
    $.ajax({
      url: "appointment/getDoctorCommissionSettings?id=" + doctor_id,
      method: "GET",
      dataType: "json",
      success: function (response) {
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
        $("#shipping_fee").append(courier_fee).end();
        $("#total_fee").val(total_fee).end();
        $("#new_subtotal_fee").val(total_fee_without_courier).end();
        $("#gateway_fee").append(gateway_fee).end();
        $("#subtotal_fee").val(total_fee_without_courier).end();
        $("#gateway_fee").empty();
        $("#subtotal_fee").empty();
        $("#new_subtotal_fee").empty();
        var subtotal = $("#subtotal_fee").val();

        $.ajax({
          url: "doctor/getDoctorVisitCharges?id=" + visit_description,
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
            if ($("#pay_for_courier").prop("checked") == true) {
              var courier = courier_fee;
            } else {
              var courier = 0;
            }
            var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
            var gateway_fee = (new_subtotal * 2.5) / 100;
            if (doctor_id.trim() != "") {
              var total_doctor_amount =
                parseFloat(visit) +
                parseFloat(casetaker_fee) +
                parseFloat(onlinecenter_fee);
            } else {
              var total_doctor_amount = visit;
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
          },
        });
      },
    });
  });
});

// edit ---------

$(document).ready(function () {
  $("#adoctors1").change(function () {
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
      success: function (response) {
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
        var gateway_fee = (total_fee_without_courier * 2.5) / 100;
        var total_fee =
          parseFloat(total_fee_without_courier) + parseFloat(courier);
        $("#casetaker_fee1").val(casetaker_fee).end();
        $("#onlinecenter_fee1").val(onlinecenter_fee).end();
        $("#developer_fee1").val(developer_fee).end();
        $("#hospital_fee1").val(hospital_fee).end();
        $("#superadmin_fee1").val(superadmin_fee).end();
        $("#medicine_fee1").val(medicine_fee).end();
        $("#courier_fee1").val(courier_fee).end();
        $("#shipping_fee1").append(courier_fee).end();
        $("#total_fee1").val(total_fee).end();
        $("#new_subtotal_fee2").val(total_fee_without_courier).end();
        // $('#gateway_fee').append(gateway_fee).end();
        // $("#subtotal_fee1").val(total_fee_without_courier).end();
        var visit_description = $("#visit_description_id").val();
        $.ajax({
          url: "doctor/getDoctorVisitCharges?id=" + visit_description,
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

$(document).ready(function () {
  $("#currency1").change(function () {
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
      success: function (response) {
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
        var gateway_fee = (total_fee_without_courier * 2.5) / 100;
        var total_fee =
          parseFloat(total_fee_without_courier) + parseFloat(courier);

        $("#casetaker_fee1").val(casetaker_fee).end();
        $("#onlinecenter_fee1").val(onlinecenter_fee).end();
        $("#developer_fee1").val(developer_fee).end();
        $("#hospital_fee1").val(hospital_fee).end();
        $("#superadmin_fee1").val(superadmin_fee).end();
        $("#medicine_fee1").val(medicine_fee).end();
        $("#courier_fee1").val(courier_fee).end();
        // $("#total_fee1").val(total_fee).end();
        $("#shipping_fee1").append(courier_fee).end();
        $("#total_fee1").val(total_fee).end();
        $("#new_subtotal_fee2").val(total_fee_without_courier).end();

        $("#new_subtotal_fee1").empty();
        $("#gateway_fee1").empty();
        var courier_fee = $("#courier_fee1").val();
        // var currency = $("#currency1").val();
        var subtotal = $("#new_subtotal_fee2").val();
        $.ajax({
          url: "doctor/getDoctorVisitCharges?id=" + visit_description,
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
            if ($("#pay_for_courier1").prop("checked") == true) {
              var courier = courier_fee;
            } else {
              var courier = 0;
            }
            var new_subtotal = parseFloat(visit) + parseFloat(subtotal);
            var gateway_fee = (new_subtotal * 2.5) / 100;
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
              .val(
                parseFloat(visit) +
                  parseFloat(subtotal) +
                  parseFloat(gateway_fee) +
                  parseFloat(courier)
              )
              .end();
            $("#new_subtotal_fee1").val(parseFloat(new_subtotal)).end();
            $("#gateway_fee1").append(parseFloat(gateway_fee)).end();
            $("#doctor_amount1").val(total_doctor_amount).end();
          },
        });
      },
    });
  });
});

$(document).ready(function () {
  // if ($('#pay_for_courier').prop("checked") == true) {
  //     // $("#total_fee").val(" ");
  //     var total_fee = $('#total_fee').val();
  //     $("#test").val(total_fee).end();
  // }
  $(".pay_for_courier").on("change", "#pay_for_courier", function () {
    if ($(this).prop("checked") == true) {
      var total_charge = $("#total_charges").val();
      var courier_fee = $("#courier_fee").val();
      var total_fee = $("#total_fee").val();
      var total_additional_fee =
        parseFloat(total_fee) + parseFloat(courier_fee);
      var new_total_fee = parseFloat(total_charge) + parseFloat(courier_fee);
      $("#total_charges").val(new_total_fee).end();
      $("#hidden_total_charges").val(new_total_fee).end();
      $("#total_fee").val(total_additional_fee).end();
    } else {
      var total_charge = $("#total_charges").val();
      var courier_fee = $("#courier_fee").val();
      var total_fee = $("#total_fee").val();
      var total_additional_fee =
        parseFloat(total_fee) - parseFloat(courier_fee);
      var new_total_fee = parseFloat(total_charge) - parseFloat(courier_fee);
      $("#total_charges").val(new_total_fee).end();
      $("#hidden_total_charges").val(new_total_fee).end();
      $("#total_fee").val(total_additional_fee).end();
    }
  });
});

$(document).ready(function () {
  $(".pay_for_courier1").on("change", "#pay_for_courier1", function () {
    var doctor_id = $("#doctor_idd").val();
    var currency = $("#currency1").val();
    var courier_fee = $("#courier_fee1").val();
    if ($(this).prop("checked") == true) {
      $.ajax({
        url: "appointment/getDoctorCommissionSettings?id=" + doctor_id,
        method: "GET",
        dataType: "json",
        success: function (response) {
          // if (currency == "BDT") {
          //   var courier_fee = response.commission.courier_fee;
          // }
          // if (currency == "INR") {
          //   var courier_fee = response.commission.courier_fee_rupee;
          // }
          // if (currency == "USD") {
          //   var courier_fee = response.commission.courier_fee_dollar;
          // }
          var total_charge = $("#total_charges1").val();
          var total_fee = $("#total_fee1").val();
          var total_additional_fee =
            parseFloat(total_fee) + parseFloat(courier_fee);
          var new_total_fee =
            parseFloat(total_charge) + parseFloat(courier_fee);
          $("#total_charges1").val(new_total_fee).end();
          $("#total_fee1").val(total_additional_fee).end();
          // $("#shipping_fee1").append(courier_fee).end();
        },
      });
    } else {
      $.ajax({
        url: "appointment/getDoctorCommissionSettings?id=" + doctor_id,
        method: "GET",
        dataType: "json",
        success: function (response) {
          // if (currency == "BDT") {
          //   var courier_fee = response.commission.courier_fee;
          // }
          // if (currency == "INR") {
          //   var courier_fee = response.commission.courier_fee_rupee;
          // }
          // if (currency == "USD") {
          //   var courier_fee = response.commission.courier_fee_dollar;
          // }
          var total_charge = $("#total_charges1").val();
          var total_fee = $("#total_fee1").val();
          var total_additional_fee =
            parseFloat(total_fee) - parseFloat(courier_fee);
          var new_total_fee =
            parseFloat(total_charge) - parseFloat(courier_fee);
          $("#total_charges1").val(new_total_fee).end();
          $("#total_fee1").val(total_additional_fee).end();
          // $("#shipping_fee1").append(courier_fee).end();
        },
      });
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
    $("#addAppointmentForm").append(
      "<input type='hidden' name='token' value='" + token + "' />"
    );
    //submit form to the server
    $("#addAppointmentForm").submit();
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

function cardValidation1() {
  var valid = true;
  var cardNumber = $("#card1").val();
  var expire = $("#expire1").val();
  var cvc = $("#cvv1").val();

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
function stripeResponseHandler1(status, response) {
  if (response.error) {
    //enable the submit button
    $("#submit-btn1").show();
    $("#loader").css("display", "none");
    //display the errors on the form
    $("#error-message").html(response.error.message).show();
  } else {
    //get token id
    var token = response["id"];
    //insert the token into the form
    $("#token").val(token);

    $("#editAppointmentForm").append(
      "<input type='hidden' name='token' value='" + token + "' />"
    );
    //submit form to the server
    $("#editAppointmentForm").submit();
  }
}

function stripePay1(e) {
  e.preventDefault();
  var valid = cardValidation1();

  if (valid == true) {
    $("#submit-btn1").attr("disabled", true);
    $("#loader").css("display", "inline-block");
    var expire = $("#expire1").val();
    var arr = expire.split("/");
    Stripe.createToken(
      {
        number: $("#card1").val(),
        cvc: $("#cvv1").val(),
        exp_month: arr[0],
        exp_year: arr[1],
      },
      stripeResponseHandler1
    );

    //submit from callback
    return false;
  }
}

$(document).ready(function () {
  "use strict";
  // $(".custom_board").hide();
  $(document.body).on("change", "#select_board", function () {
    var v = $("select.select_board option:selected").val();
    if (v == "Single Doctor") {
      $("#custom_doc_fee").val(0);
      $("#team_id").val(" ");
      //   $("#doctor_id").val(" ");
      $("#board_leader_id").val(" ");
      $("#total_charges").val(" ");
      $(".medical_team").addClass("hidden");
      $(".custom_board").addClass("hidden");
      $(".leader_title").addClass("hidden");
      $(".doc_title").removeClass("hidden");
      $(".custom_board").hide();
      $(".medical_team").hide();
      $(".doctor_div").show();
      $(".visitt").show();
      $("#aslots").find("option").remove();
      $("#adoctors").find("option").remove();
      $(".board_doc").find("option").remove();
      $("#date").val(" ");

      var onlinecenter_id = $("#onlinecenter_id").val();
      var casetaker_id = $("#casetaker_id").val();
      var doctor_id = $("#doctor_id").val();
      var currency = $("#currency").val();
      $("#new_subtotal_fee").empty();
      $("#gateway_fee").empty();
      $("#shipping_fee").empty();

      $.ajax({
        url: "appointment/getDoctorCommissionSettings?id=" + doctor_id,
        method: "GET",
        dataType: "json",
        success: function (response) {
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

      var iid = $("#date").val();
      var doctorr = $("#doctor_id").val();
      $("#aslots").find("option").remove();

      $.ajax({
        url:
          "schedule/getAvailableSlotByDoctorByDateByJason?date=" +
          iid +
          "&doctor=" +
          doctorr,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          "use strict";
          $("#doctor_id").val(doctorr).end();
          var slots = response.aslots;
          $.each(slots, function (key, value) {
            "use strict";
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
      $("#visit_description").html(" ");
      $("#visit_charges").val(" ");
      $.ajax({
        url: "doctor/getDoctorVisit?id=" + doctorr,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response1) {
          $("#visit_description").html(response1.response).end();
          // $("#visit_id").html(response1.responsee).end();
          $("#addAppointmentForm")
            .find('[name="visit_id"]')
            .val(response1.responsee)
            .end();
        },
      });

      $("#visiting_place_list").html("");
      if (doctorr !== null) {
        $.ajax({
          url: "doctor/getDoctorVisitingPlace?id=" + doctorr,
          method: "GET",
          data: "",
          dataType: "json",
          success: function (response) {
            //   $('#visiting_place_list').html('<input type="radio" id="+ permited_modules +" name="fav_language" value="HTML"><br>' + response.option);
            $("#visiting_place_list").html(
              '<label for="exampleInputEmail1"> </label><br>' + response.option
            );
          },
        });
      }

      // var id = $(this).val();
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

        console.log(subtotal);
        $.ajax({
          url: "doctor/getDoctorVisitCharges?id=" + visit_id,
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
            var gateway_fee = (new_subtotal * 2.5) / 100;

            $("#visit_charges").val(visit).end();
            var total =
              parseFloat(visit) +
              parseFloat(subtotal) +
              parseFloat(gateway_fee) +
              parseFloat(courier);
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
            $("#due_amount")
              .val(total - deposited_amount)
              .end();
          },
        });
      }, 3000);
    }
    if (v == "Medical Board") {
      $("#custom_doc_fee").val(0);
      $("#team_id").val(" ");
      //   $("#doctor_id").val(" ");
      $("#board_leader_id").val(" ");
      $("#total_charges").val(" ");
      $(".medical_team").removeClass("hidden");
      $(".custom_board").addClass("hidden");
      $(".custom_board").hide();
      $(".doctor_div").hide();
      $(".medical_team").show();
      $(".visitt").hide();
      $("#aslots").find("option").remove();
      $("#adoctors").find("option").remove();
      $(".board_doc").find("option").remove();
      $("#date").val(" ");
    }
    if (v == "Custom Board") {
      $("#team_id").val(" ");
      //   $("#doctor_id").val(" ");
      $("#board_leader_id").val(" ");
      $("#total_charges").val(" ");
      $(".custom_board").removeClass("hidden");
      $(".medical_team").addClass("hidden");
      $(".leader_title").removeClass("hidden");
      $(".doc_title").addClass("hidden");
      $(".doctor_div").show();
      $(".custom_board").show();
      $(".medical_team").hide();
      $(".visitt").show();
      $("#aslots").find("option").remove();
      $("#adoctors").find("option").remove();
      $(".board_doc").find("option").remove();
      $("#date").val(" ");

      var onlinecenter_id = $("#onlinecenter_id").val();
      var casetaker_id = $("#casetaker_id").val();
      var doctor_id = $("#doctor_id").val();
      var currency = $("#currency").val();
      $("#new_subtotal_fee").empty();
      $("#gateway_fee").empty();
      $("#shipping_fee").empty();

      $.ajax({
        url: "appointment/getDoctorCommissionSettings?id=" + doctor_id,
        method: "GET",
        dataType: "json",
        success: function (response) {
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

      var iid = $("#date").val();
      var doctorr = $("#doctor_id").val();
      $("#aslots").find("option").remove();

      $.ajax({
        url:
          "schedule/getAvailableSlotByDoctorByDateByJason?date=" +
          iid +
          "&doctor=" +
          doctorr,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          "use strict";
          $("#doctor_id").val(doctorr).end();
          var slots = response.aslots;
          $.each(slots, function (key, value) {
            "use strict";
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
      $("#visit_description").html(" ");
      $("#visit_charges").val(" ");
      $.ajax({
        url: "doctor/getDoctorVisit?id=" + doctorr,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response1) {
          $("#visit_description").html(response1.response).end();
          // $("#visit_id").html(response1.responsee).end();
          $("#addAppointmentForm")
            .find('[name="visit_id"]')
            .val(response1.responsee)
            .end();
        },
      });

      $("#visiting_place_list").html("");
      if (doctorr !== null) {
        $.ajax({
          url: "doctor/getDoctorVisitingPlace?id=" + doctorr,
          method: "GET",
          data: "",
          dataType: "json",
          success: function (response) {
            //   $('#visiting_place_list').html('<input type="radio" id="+ permited_modules +" name="fav_language" value="HTML"><br>' + response.option);
            $("#visiting_place_list").html(
              '<label for="exampleInputEmail1"> </label><br>' + response.option
            );
          },
        });
      }

      // var id = $(this).val();
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

        console.log(subtotal);
        $.ajax({
          url: "doctor/getDoctorVisitCharges?id=" + visit_id,
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
            var gateway_fee = (new_subtotal * 2.5) / 100;

            $("#visit_charges").val(visit).end();
            var total =
              parseFloat(visit) +
              parseFloat(subtotal) +
              parseFloat(gateway_fee) +
              parseFloat(courier);
            $("#total_charges")
              .val(
                parseFloat(visit) +
                  parseFloat(subtotal) +
                  parseFloat(gateway_fee) +
                  parseFloat(courier)
              )
              .end();
            $("#charge_without_courier")
              .val(parseFloat(visit) + parseFloat(subtotal))
              .end();
            $("#hidden_total_charges")
              .val(
                parseFloat(visit) +
                  parseFloat(subtotal) +
                  parseFloat(courier)
              )
              .end();
            $("#doctor_amount").val(total_doctor_amount).end();
            $("#new_subtotal_fee").val(new_subtotal).end();
            $("#gateway_fee").append(gateway_fee).end();

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
            $("#due_amount")
              .val(total - deposited_amount)
              .end();
          },
        });
      }, 3000);
    }
  });
});

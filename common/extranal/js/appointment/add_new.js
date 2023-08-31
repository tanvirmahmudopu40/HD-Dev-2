"use strict";
$(document).ready(function () {
    "use strict";
$(".doctor_div").on("change", "#adoctors", function () {
    "use strict";
    var id = $("#appointment_id").val();
    var date = $("#date").val();
    var doctorr = $(this).data('id');
    var visit_description = $("#visit_description").val();
    $("#aslots").find("option").remove();

    $.ajax({
      url:
        "schedule/getAvailableSlotByDoctorByDateByJason?date=" +
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
          },
        });

        $('#visiting_place_list').html("");
        if (doctorr !== null) {
            $.ajax({
                url: 'doctor/getDoctorVisitingPlace?id=' + doctorr,
                method: 'GET',
                data: '',
                dataType: 'json',
                success: function (response) {
                    
                //   $('#visiting_place_list').html('<input type="radio" id="+ permited_modules +" name="fav_language" value="HTML"><br>' + response.option);
                    $('#visiting_place_list').html('<label for="exampleInputEmail1"> </label><br>' + response.option);
                }
            })
    
        }
    // $("#adoctors").html(" ");
    // $("#doctor_details").val(" ");
    // $.ajax({
    //   url: "doctor/getDoctorVisit?id=" + doctorr,
    //   method: "GET",
    //   data: "",
    //   dataType: "json",
    //   success: function (response1) {
    //     $("#visit_description").html(response1.response).end();
    //   },
    // });

  });
});

$(document).ready(function () {
  "use strict";
  var id = $("#appointment_id").val();
  var date = $("#date").val();
  var doctorr = $("#adoctors").val();
  $("#aslots").find("option").remove();

  $.ajax({
    url:
      "schedule/getAvailableSlotByDoctorByDateByJason?date=" +
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
        $("#aslots").append($("<option>").text(value).val(value)).end();
      });

      $("#aslots")
        .val(response.current_value)
        .find("option[value=" + response.current_value + "]")
        .attr("selected", true);

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

  var id = $("#appointment_id").val();
  var date = $("#date").val();
  var doctorr = $("#adoctors").val();
  $("#aslots").find("option").remove();

  $.ajax({
    url:
      "schedule/getAvailableSlotByDoctorByDateByJason?date=" +
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

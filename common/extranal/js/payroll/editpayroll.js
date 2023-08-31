"use strict";
$(document).ready(function () {
    "use strict";
    $('.single_date_picker').datepicker({
        format: 'dd-mm-yyyy',
    });

    $('.addEarning').on('click', function () {
        let count = $('#earningCount').val();
        $('#earning_div').append('<div id="earning_div"><div id="earning-' + (Number(count + 1)) + '"><input name="earningName[]" placeholder="Enter Earning Title" class="form-control mb-1"><div class="mb-1 number_div"><input type="number" placeholder="Enter Amount" name="earningValue[]" class="form-control"><button type="button" class="btn btn-danger earning_remove" data-id=' + (Number(count + 1)) + '><i class="fas fa-minus"></i></button></div></div>');
        $('#earningCount').val((Number(count + 1)));
    })

    $(document).on('click', '.earning_remove', function () {
        let id = $(this).data('id');
        $('#earning-' + id + '').remove();
    })

    $('.addDeduction').on('click', function () {
        let count = $('#deductionCount').val();
        $('#deduction_div').append('<div id="deduction_div"><div id="deduction-' + (Number(count + 1)) + '"><input name="deductionName[]" placeholder="Enter Deduction Title" class="form-control mb-1"><div class="mb-1 number_div"><input type="number" placeholder="Enter Amount" name="deductionValue[]" class="form-control"><button type="button" class="btn btn-danger deduction_remove" data-id=' + (Number(count + 1)) + '><i class="fas fa-minus"></i></button></div></div>');
        $('#deductionCount').val((Number(count + 1)));
    })

    $(document).on('click', '.deduction_remove', function () {
        let id = $(this).data('id');
        $('#deduction-' + id + '').remove();
    })

});

$(document).ready(function () {
    "use strict";
    $(".table").on("click", ".inffo", function () {

        var iid = $(this).attr('data-id');

        $("#img1").attr("src", "uploads/cardiology-patient-icon-vector-6244713.jpg");
        $('.nameClass').html("").end();
        $('.emailClass').html("").end();
        $('.addressClass').html("").end();
        $('.phoneClass').html("").end();
        $('.departmentClass').html("").end();
        $('.profileClass').html("").end();
        $.ajax({
            url: 'doctor/editDoctorByJason?id=' + iid,
            method: 'GET',
            data: '',
            dataType: 'json',
            success: function (response) {
                "use strict";
                $('#editDoctorForm').find('[name="id"]').val(response.doctor.id).end();
                $('.nameClass').append(response.doctor.name).end();
                $('.emailClass').append(response.doctor.email).end();
                $('.addressClass').append(response.doctor.address).end();
                $('.phoneClass').append(response.doctor.phone).end();
                $('.departmentClass').append(response.doctor.department).end();
                $('.profileClass').append(response.doctor.profile).end();

                if (typeof response.doctor.img_url !== 'undefined' && response.doctor.img_url !== '') {
                    $("#img1").attr("src", response.doctor.img_url);
                }

                $('#infoModal').modal('show');
            }
        });
    });
});

$(document).ready(function () {
    "use strict";
    var table2 = $('#salary-sample').DataTable({
        responsive: true,
        "processing": true,
        "searchable": true,
        scroller: {
            loadingIndicator: true
        },

        dom: "<'row'<'col-sm-3'l><'col-sm-5 text-center'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1],
                }
            },
        ],
        aLengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        iDisplayLength: 10,
        "order": [[0, "desc"]],

        "language": {
            "lengthMenu": "_MENU_",
            search: "_INPUT_",
            "url": "common/assets/DataTables/languages/" + language + ".json"
        }
    });
    table2.buttons().container().appendTo('.custom_buttons');

    var table = $('#editable-sample').DataTable({

        responsive: true,
        "processing": true,
        "serverSide": true,
        "searchable": true,
        "ajax": {
            url: "leave/getLeaveType",
            type: 'POST',
        },
        scroller: {
            loadingIndicator: true
        },

        dom: "<'row'<'col-sm-3'l><'col-sm-5 text-center'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6],
                }
            },
        ],
        "columnDefs": [
            {"width": "50%", "targets": 0}
        ],
        aLengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        iDisplayLength: 100,
        "order": [[0, "desc"]],

        "language": {
            "lengthMenu": "_MENU_",
            search: "_INPUT_",
            "url": "common/assets/DataTables/languages/" + language + ".json"
        }
    });
    table.buttons().container().appendTo('.custom_buttons');
});

$(document).ready(function () {
    "use strict";
    $(".flashmessage").delay(3000).fadeOut(100);
});



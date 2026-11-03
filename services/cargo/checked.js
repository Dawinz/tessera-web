$(document).ready(function () {



    var vehicle = $("#manifest_data").data("vehicle");
    var date = $("#manifest_data").data("trip_date");
    $("#send").click(function () {

        var code = $("#code").val();


        luggage_authentication(code, vehicle, date);
    });
    $('#code').on('keyup keypress', function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode == 13)  // the enter key code
        {
            var code = $("#code").val();

            luggage_authentication(code, vehicle, date);
            return false;
        }
    });

    $("#code").on("input", function () {
        $(this).removeClass("is-invalid");
        $(this).siblings(".invalid-feedback").remove();
    });

    $("#back_btn").click(function () {
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to go back",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, go back!"
        }).then((result) => {
            transitionTo($('.upload_manifest'), $('.luggage_check'), 'back');
        });
    });
    function luggage_authentication(code, vehicle, date) {
        if (code != "") {
            $("#send").prop('disabled', true);
            $(".search_loader").show();
            $.ajax({
                url: "check_insert.php",
                method: "POST",
                dataType: "json",
                data: {
                    code: code,
                    vehicle: vehicle,
                    date: date
                },

                success: function (response) {


                    if (response.success) {

                        if (response.data.length > 0) {
                            const jsonText = JSON.stringify(response.data);
                            var json_text = jsonText;
                            sessionStorage.setItem("parcel_info", JSON.stringify(response.data));

                            $("#parcel_data").attr('data-jsontext', json_text);
                            $("#parcel_data").text(json_text);
                            $("#server_response").html(jsonText);


                            if (response.data[0].status == "paid") {
                                $("#to_be_panel").hide();
                            } else if (response.data[0].status == "To be Payed") {
                                $("#paid_panel").hide();
                            } else if (response.data[0].status == "Due payment") {
                                $(".table-wrap").css("height", "525px");
                                $("#to_be_panel").show();
                                $("#paid_panel").show();
                            }

                            append_value(response.data[0].question, response.data[0].paid, response.data[0].to_be, response.data[0].amount, response.data[0].paid_remain, response.data[0].to_be_remain, response.data[0].scanned, response.data[0].status, response.data[0].code, response.data[0].id, response.data[0].store);

                        }

                        transitionTo($('.luggage_check'), $('.upload_manifest'), 'forward');

                    } else {
                       
                       //$("#server_response").html(response.message);
                        Swal.fire({
                            title: "Fully dispatched",
                            html: response.message,
                            icon: "error"
                        });
                    }

                    $("#send").prop("disabled", false);
                    $(".search_loader").hide();
                },
                error: function (xhr, status, error) {
                    //alert(xhr.responseText);

                    $("#server_response").html("Server error");
                    $("#send").prop("disabled", false);
                    $(".search_loader").hide();
                }
            });



        } else {
            $("#code").addClass("is-invalid");
            $("#code").siblings(".invalid-feedback").remove();
            $("#code").after('<div class="invalid-feedback">Please add code number</div>');
        }


    }

});
function append_value(qty, paid, to_be, amount, paid_remain, to_be_remain, scanned, status, code, id, store) {

    $("#actual_qty").text(qty);
    $("#status_holder").text(status);
    $("#actual_to_be").text(to_be.toLocaleString());
    $("#actual_paid").text(paid.toLocaleString());
    $("#actual_amount").text(amount.toLocaleString());
    $(".qty_remain_holder").text(scanned);
    $(".code_no_holder").text(code);
    $('.more-detail').attr('href', 'view.php?u=' + id);
    $("#qty").val(scanned);
    $("#to_be").val(to_be_remain.toLocaleString());
    $("#paid").val(paid_remain.toLocaleString());

    if (status == "Due payment") {

        $(".status_badge").css({
            'border': '1px solid white',
            'color': 'white',
            'background': '#dc3545',

        });

    } else if (status == "To be Payed") {
        $(".status_badge").css({
            'border': '1px solid #333333',
            'color': 'black',
            'background': 'white',
            'boxShadow': '1px 1px 2px 0px rgba(0, 0, 0, 0.3)'
        });
        $(".status_badge").css("color", "black");
    } else if (status == "paid") {

        $(".status_badge").css({
            'border': '1px solid black',
            'color': 'white',
            'background': '#198754',
            'boxShadow': '1px 1px 2px 0px rgba(0, 0, 0, 0.3)'
        });
        $("#status_holder").text("Paid");
    }

    if (store == "half_out") {
        Swal.fire({
            icon: 'warning',
            title: 'Partially loaded',
            text: 'Some luggage is still in the store and some has already been loaded.',
            confirmButtonText: 'OK'
        });
    }



}

const DURATION = 300;

function transitionTo($out, $in, direction = 'forward') {
    const outClass = direction === 'forward' ? 'slide-out-left' : 'slide-out-right';
    const inClass = direction === 'forward' ? 'slide-in-right' : 'slide-in-left';

    $out.addClass(outClass);

    setTimeout(() => {
        $out.hide().removeClass(outClass);
        $in.css({ display: 'block', opacity: 0 }).addClass(inClass);

        setTimeout(() => {
            $in.css('opacity', '');
            $in.removeClass(inClass);
        }, DURATION);

    }, DURATION);
}
function showManifestAlert(message, type = "success") {
    let section = $(".upload_manifest")[0];

    if (!section) return;

    let rect = section.getBoundingClientRect();

    let centerTop = rect.top + (rect.height / 2);
    let centerLeft = rect.left + (rect.width / 2);

    alertify.set("notifier", "position", "top-center");

    $(".alertify-notifier").css({
        "top": centerTop + "px",
        "left": centerLeft + "px",
        "transform": "translate(-50%, -50%)",
        "position": "fixed"
    });

    alertify[type](message);
}
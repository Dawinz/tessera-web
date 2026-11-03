$(document).on('click', '.remove_luggage', function () {
    id = $(this).data("id");
    default_currency = $("#manifest_data").data("currency");
    currency_json = $("#currency_string").text();
    $("#dtext"+id).text("Removing...");
    $.ajax({
        url: "delete_manifest.php",
        method: "POST",
        data: { id: id,currency_json:currency_json,default_currency:default_currency },
        success: function (response) {
//alert(response);
            if ($.trim(response) == "previous") {
                alertify.error("Manifest is locked");
                alertify.set('notifier', 'position', 'top-right');
                $("#dtext"+id).text("Remove");
            } else {
                alertify.success("Deleted Succesfull");
                alertify.set('notifier', 'position', 'top-right');
                $("#empTable").DataTable().ajax.reload();
                            $("#empTable").DataTable().ajax.reload();

            }

        }

    });

});

$(document).ready(function () {

    trip_date = $("#manifest_data").data("trip_date");
    vehicle = $("#manifest_data").data("vehicle");
    currency_value = $("#currency_string").text();
    currency = $("#manifest_data").data("currency");


    fetch_data(trip_date, vehicle, currency_value, currency);
    function fetch_data(trip_date, vehicle, currency_value, currency) {

        const overlay = document.querySelector(".table-overlay");
        const closeButton = document.querySelector(".close-button");
        const tableFrame = document.querySelector(".table-frame");
        const pagination = document.querySelector(".pagination");

        function hideTableLoading() {
            tableFrame.classList.remove("is-loading");
            tableFrame.setAttribute("aria-busy", "false");
        }
        var cargoManifestLang = (window.TesseraStates && TesseraStates.listLanguage)
            ? Object.assign(
                TesseraStates.listLanguage('No cargo on this manifest', 'No cargo matches your search'),
                { searchPlaceholder: "Search...", search: "" }
            )
            : { searchPlaceholder: "Search...", search: "" };
        $('#empTable').DataTable({

            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            info: false,
            "language": cargoManifestLang,

            'ajax': {
                'url': 'fetch_manifest.php',
                type: "POST", "data": {
                    vehicle: vehicle,
                    trip_date: trip_date,
                    currency_value: currency_value,
                    currency: currency
                },
                dataSrc: function (json) {
                    $('#totalToBePaid').html(json.total_to_be_paid);
                    $('#totalPayed').html(json.total_paid);
                    $('#totalAmount').html(json.total_amount);


                    return json.aaData;
                },
                error: (window.TesseraStates && TesseraStates.loadError)
                    ? TesseraStates.loadError('Could not load cargo manifest')
                    : undefined
            },

            'columns': [
                { data: 'sno' },
                { data: 'code' },
                { data: 'qty' },
                { data: 'to_be' },
                { data: 'paid' },

                { data: 'remove' },

            ],
            "lengthMenu": [[400]],
            dom: 'Bfrtip',

            columnDefs: [
                {
                    targets: 0,
                    createdCell: function (td, cellData, rowData, row, col) {
                        $(td).css('font-weight', 'bold');

                    }
                }
            ],
            drawCallback: function () {

                tableFrame.classList.remove("is-loading");
                tableFrame.setAttribute("aria-busy", "false");
            },
            rowCallback: function (row, data, index) {

                if (data.status == "Due payment") {

                    $(row).css('background-color', '#ffe8f0');

                }
                if (data.status == "To be Payed") {
                    $(row).css('background-color', '#ffffff');

                } if (data.status == "paid") {
                    $(row).css('background-color', '#99ff9c');



                }
    if (data.sno == "1") {
        blink = $("#blink").text();
       
        if(blink!=""){
 $(row).addClass("tr-blink-border ");
        }
                  

    }

            },
        });
    }



    oTable = $('#empTable').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
    $('#search').keyup(function () {
        oTable.search($(this).val()).draw();
    });



    $(document).on('click', '#empTable_paginate .paginate_button:not(.disabled)', function () {
        const overlay = document.querySelector(".table-overlay");
        const closeButton = document.querySelector(".close-button");
        const tableFrame = document.querySelector(".table-frame");

        tableFrame.classList.add("is-loading");
        tableFrame.setAttribute("aria-busy", "true");
    });

});
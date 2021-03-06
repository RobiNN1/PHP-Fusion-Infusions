$(function () {
    $("#check-connection").on("click", function () {
        $.ajax({
            url: SITE_URL + "infusions/server_status_panel/includes/ajax/check_connection.php" + AID,
            method: "get",
            data: $.param({
                "ip": $("#server_ip").val(),
                "port": $("#server_port").val(),
                "qport": $("#server_qport").val(),
                "type": $("#server_type").val()
            }),
            dataType: "json",
            beforeSend: function () {
                $("#check-connection > i").show();
            },
            success: function (e) {
                $("#connection_result").text(e.result).show();
            },
            complete: function () {
                $("#check-connection > i").hide();
            }
        });
    });
});

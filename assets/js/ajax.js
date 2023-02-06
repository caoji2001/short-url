$(document).ready(function() {
    $("#show_url").click(function() {
        const input_url = $("#input_url").val();

        $.ajax({
            method: "POST",
            url: "/api/worker.php",
            data: { 'input_url': input_url },
            dataType:"json",
        })
        .done(function(msg) {
            if (msg["ok"]) {
                $("#input_url").addClass("is-valid");
                $("#input_url").removeClass("is-invalid");
                $("#input_url").val(msg["short_url"]);
                $('#input_url').prop("readonly", true);

                $("#feedback").addClass("valid-feedback");
                $("#feedback").removeClass("invalid-feedback");
                $("#feedback").html("长链接已缩短！");

                $("#show_url").addClass("btn-success");
                $("#show_url").removeClass("btn-primary");
                $("#show_url").html("已生成");
                $('#show_url').prop("disabled", true);


            } else {
                $("#input_url").addClass("is-invalid");

                $("#feedback").addClass("invalid-feedback");
                $("#feedback").html(msg["error_msg"]);
            }
        });
    });
});
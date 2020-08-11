$(document).ready(function() {
    $(".btnEditUser").click(function (e) {
        e.preventDefault();
        var user = $(this).data("user");

        // Set the action attribute for the form
        $("#frmEditUser").attr("action", "/configuration/user/edit/" + user);

        // Get the user details
        var jqxhr = $.ajax({
            url: "/configuration/user/" + user,
            type: "GET",
            dataType: "json"
        });

        // Insert retrieved details into form inputs
        jqxhr.done(function (data, textStatus, jqXHR) {
            $("#txtName").val(data.user.name);
            $("#txtUsername").val(data.user.username);
            $("#chkIsAdmin").prop("checked", data.user.is_superadmin == 1);
            $("#chkIsStaff").prop("checked", data.user.is_staff == 1);

            $("#modalEdit").modal("show");
        });
    });

    $("#txtChangePassword").click(function (e) {
        e.preventDefault();
        $("#txtPassword").removeAttr("disabled");
        $("#txtPassword").prop("disabled", null);

        $(this).hide();
        $("#txtCancelPassword").show();
    });

    $("#txtCancelPassword").click(function (e) {
        e.preventDefault();
        $("#txtPassword").attr("disabled", "disabled");
        $("#txtPassword").prop("disabled", "disabled");

        $("#txtPassword").val("");
        $(this).hide();
        $("#txtChangePassword").show();
    });

    $(".btnDeleteUser").click(function (e) {
        e.preventDefault();


        var user = $(this).data("user");

        // Set the action attribute for the form
        $("#frmDeleteUser").attr("action", "/configuration/user/delete/" + user);

        // Get the user details
        var jqxhr = $.ajax({
            url: "/configuration/user/" + user,
            type: "GET",
            dataType: "json"
        });

        // Insert retrieved details into form inputs
        jqxhr.done(function (data, textStatus, jqXHR) {
            $("#lblUserName").text(data.user.name);

            // Show modal window
            $("#modalDelete").modal("show");
        });
    });
});
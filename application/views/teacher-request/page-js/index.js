
$("document").ready(function () {
    $("#show-password,#hide-password").click(function () {
        var fld = $('input[name="user_password"]');
        if ($(fld).attr('type') == 'password') {
            $(fld).attr('type', 'text');
            $('#show-password').show();
            $('#hide-password').hide();
        } else {
            $(fld).attr('type', 'password');
            $('#hide-password').show();
            $('#show-password').hide();
        }
    });
    $('.applytoteach').click(function () {
        $("html,body").animate({scrollTop: 0}, 700);
        return false;
    });
});

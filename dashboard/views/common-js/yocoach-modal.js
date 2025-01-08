var dv;
var ele = 'yocoachModal';
var options = {size: 'modal-md', backdrop : 'static', addClass : '' };
(function ($) {
    $.yocoachmodal = function (data, opts = {}) {
        options = $.extend(options, opts);
        init();
        $.yocoachmodal.reveal(data);
    };
    function init() {
        $.yocoachmodal.close();
        
        dv = document.createElement('div');
        $(dv).addClass('modal fade show ' + options.size +' '+ options.addClass).attr({ 'id': ele, 'tabindex': "-1", 'role': "dialog", 'data-bs-backdrop': options.backdrop, 'aria-modal':"true" });
        $('body').append(dv);
    }
    $.extend($.yocoachmodal, {
        reveal: function (content) {
            $(dv).html('<div class="modal-dialog modal-dialog-centered modal-dialog-vertical modal-dialog-scrollable " role="document"><div class="modal-content contentBodyJs">' + content + '</div></div>');
            $.yocoachmodal.show();
        },
        close: function () {
            $("#" + ele).modal("hide");
            $.yocoachmodal.clear();
            return;
        },
        show: function () {
            $("#" + ele).modal("show");
            return;
        },
        clear: function () {
            if ($('.modal').length > 0) {
                $('.modal, .modal-backdrop').remove();
            }
        },
    });
    $(document).on("hide.bs.modal", "#" + ele, function () {
        $.yocoachmodal.clear();
    });
})(jQuery);
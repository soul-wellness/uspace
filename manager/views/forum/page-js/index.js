/* global fcom, langLbl */
$(document).ready(function () {
    search(document.srchForm);
});
(function () {
    var active = 1;
    var inActive = 0;
    var dv = '#listing';
    search = function (form) {
        fcom.ajax(fcom.makeUrl('Forum', 'search'), fcom.frmData(form), function (res) {
            $(dv).html(res);
        });
    };

    reloadList = function () {
        search(document.srchForm);
    };

    goToSearchPage = function (pageno) {
        var frm = document.srchForm;
        $(frm.pageno).val(pageno);
        search(frm);
    };

    clearSearch = function () {
        document.srchForm.reset();
        search(document.srchForm);
    };


    view = function (quesId) {
        fcom.ajax(fcom.makeUrl('Forum', 'view', [quesId]), '', function (t) {
            $.yocoachmodal(t);
        });
    };

    deleteRecord = function (id) {
        if (!confirm(langLbl.confirmDelete)) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Forum', 'deleteRecord'), {id: id}, function (res) {
            reloadList();
        });
    };
})();

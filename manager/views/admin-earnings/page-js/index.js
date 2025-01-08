/* global langLbl, fcom */
$(function () {
    goToSearchPage = function (pageno) {
        var frm = document.frmSearchPaging;
        $(frm.pageno).val(pageno);
        search(frm);
    };
    search = function (frm) {
        fcom.ajax(fcom.makeUrl('AdminEarnings', 'search'), fcom.frmData(frm), function (response) {
            $("#listing").html(response);
        });
    };
    clearSearch = function () {
        document.srchForm.reset();
        search(document.srchForm);
    };
    closeForm = function () {
        $.yocoachmodal.close();
    }
    viewLesson = function (ordlesId) {
        fcom.ajax(fcom.makeUrl('Lessons', 'view'), {ordlesId: ordlesId}, function (res) {
            $.yocoachmodal(res);
        });
    };
    viewClass = function (ordclsId) {
        fcom.ajax(fcom.makeUrl('Classes', 'view'), {ordclsId: ordclsId}, function (res) {
            $.yocoachmodal(res);
        });
    };
    search();
});
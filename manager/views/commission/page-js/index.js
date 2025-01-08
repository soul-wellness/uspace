/* global fcom, langLbl */
$(document).ready(function () {
    search(document.commSearch);
});
(function () {
    goToSearchPage = function (page) {
        var frm = document.commSearch;
        $(frm.page).val(page);
        search(frm);
    };
    search = function (form) {
        fcom.ajax(fcom.makeUrl('Commission', 'search'), fcom.frmData(form), function (response) {
            $('#listing').html(response);
        });
    };
    commissionForm = function (commissionId) {
        fcom.ajax(fcom.makeUrl('Commission', 'form', [commissionId]), '', function (response) {
            $.yocoachmodal(response);
        });
    };
    setup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('Commission', 'setup'), fcom.frmData(frm), function (res) {
            $.yocoachmodal.close();
            search(document.commSearch);
        });
    };
    clearSearch = function () {
        document.commSearch.reset();
        search(document.commSearch);
    };
})();	
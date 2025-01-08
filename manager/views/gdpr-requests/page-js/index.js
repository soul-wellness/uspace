/* global fcom */
$(document).ready(function () {
    searchGdprRequests(document.srchForm);
});
(function () {
    searchGdprRequests = function (frm) {
        fcom.ajax(fcom.makeUrl('GdprRequests', 'search'), fcom.frmData(frm), function (t) {
            $('#listItems').html(t);
        });
    };
    reloadList = function () {
        searchGdprRequests(document.srchForm);
    };
    view = function (requestId) {
        fcom.ajax(fcom.makeUrl('GdprRequests', 'view'), {id: requestId}, function (t) {
            $.yocoachmodal(t);
        });
    };
    updateStatus = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('GdprRequests', 'updateStatus'), fcom.frmData(frm), function (t) {
            $.yocoachmodal.close();
            searchGdprRequests(document.srchForm);
        });
    };
    clearSearch = function () {
        document.srchForm.reset();
        searchGdprRequests(document.srchForm);
    };
    goToSearchPage = function (page) {
        var frm = document.srchForm;
        $(frm.page).val(page);
        searchGdprRequests(frm);
    };
})();
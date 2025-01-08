/* global fcom, langLbl */
$(document).ready(function () {
    search(document.srchForm);
});
(function () {
    var active = 1;
    var inActive = 0;
    var dv = '#listing';
    search = function (form) {
        fcom.ajax(fcom.makeUrl('ForumTags', 'search'), fcom.frmData(form), function (res) {
            $(dv).html(res);
        });
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
    form = function (id) {
        fcom.ajax(fcom.makeUrl('ForumTags', 'form', [id]), '', function (response) {
            $.yocoachmodal(response);
        });
    };
    setup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('ForumTags', 'setup'), fcom.frmData(frm), function (res) {
            search(document.srchForm);
            let element = $('.tabs-nav a.active').parent().next('li');
            $.yocoachmodal.close();
        });
    };

    deleteRecord = function (id) {
        if (!confirm(langLbl.confirmDelete)) {
            return;
        }
        var data = 'ftag_id=' + id;
        fcom.updateWithAjax(fcom.makeUrl('ForumTags', 'deleteRecord'), data, function (res) {
            search(document.srchForm);
        });
    };
    restoreTag = function (id) {
        if (!confirm(langLbl.confirmRestore)) {
            return;
        }
        var data = 'ftag_id=' + id;
        fcom.updateWithAjax(fcom.makeUrl('ForumTags', 'restoreTag'), data, function (res) {
            search(document.srchForm);
        });
    };
    activeStatus = function (obj) {
        if (!confirm(langLbl.confirmUpdateStatus)) {
            e.preventDefault();
            return;
        }
        var ftagId = parseInt(obj.id);
        var data = 'ftag_id=' + ftagId + "&status=" + active;
        fcom.ajax(fcom.makeUrl('ForumTags', 'changeStatus'), data, function (res) {
            search(document.srchForm);
        });
    };
    inactiveStatus = function (obj) {
        if (!confirm(langLbl.confirmUpdateStatus)) {
            e.preventDefault();
            return;
        }
        var ftagId = parseInt(obj.id);
        var data = 'ftag_id=' + ftagId + "&status=" + inActive;
        fcom.ajax(fcom.makeUrl('ForumTags', 'changeStatus'), data, function (res) {
            search(document.srchForm);
        });
    };
})();

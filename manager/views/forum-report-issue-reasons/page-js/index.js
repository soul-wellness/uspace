/* global fcom, langLbl */
$(document).ready(function () {
    search(document.srchFrmfrireasons);
});
(function () {
    var active = 1;
    var inActive = 0;
    var dv = '#listing';
    goToSearchPage = function (page) {
        var frm = document.frmReasonsPaging;
        $(frm.page).val(page);
        search(frm);
    }
    search = function (form) {
        fcom.ajax(fcom.makeUrl('ForumReportIssueReasons', 'search'), fcom.frmData(form), function (res) {
            $(dv).html(res);
        });
    };
    clearSearch = function () {
        document.srchFrmfrireasons.reset();
        search(document.srchFrmfrireasons);
    };
    form = function (id) {
        fcom.ajax(fcom.makeUrl('ForumReportIssueReasons', 'form', [id]), '', function (response) {
            $.yocoachmodal(response);
        });
    };
    setup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        fcom.updateWithAjax(fcom.makeUrl('ForumReportIssueReasons', 'setup'), fcom.frmData(frm), function (res) {
            search(document.srchFrmfrireasons);
            let element = $('.tabs-nav a.active').parent().next('li');
            if (element.length > 0) {
                let langId = element.find('a').attr('data-id');
                langForm(res.id, langId);
                return;
            }
            $.yocoachmodal.close();
        });
    }
    langForm = function (id, langId) {
        fcom.ajax(fcom.makeUrl('ForumReportIssueReasons', 'langForm', [id, langId]), '', function (response) {
            $.yocoachmodal(response);
        });
    };
    langSetup = function (frm) {
        if (!$(frm).validate()) {
            return;
        }
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('ForumReportIssueReasons', 'langSetup'), data, function (res) {
            search(document.srchFrmfrireasons);
            let element = $('.tabs-nav a.active').parent().next('li');
            if (element.length > 0) {
                let langId = element.find('a').attr('data-id');
                langForm(res.id, langId);
                return;
            }
            $.yocoachmodal.close();
        });
    };
    deleteRecord = function (id) {
        if (!confirm(langLbl.confirmDelete)) {
            return;
        }
        var data = 'id=' + id;
        fcom.updateWithAjax(fcom.makeUrl('ForumReportIssueReasons', 'deleteRecord'), data, function (res) {
            search(document.srchFrmfrireasons);
        });
    };
    activeStatus = function (obj) {
        if (!confirm(langLbl.confirmUpdateStatus)) {
            e.preventDefault();
            return;
        }
        var id = parseInt(obj.id);
        var data = 'id=' + id + "&status=" + active;
        fcom.ajax(fcom.makeUrl('ForumReportIssueReasons', 'changeStatus'), data, function (res) {
            search(document.srchFrmfrireasons);
        });
    };
    inactiveStatus = function (obj) {
        if (!confirm(langLbl.confirmUpdateStatus)) {
            e.preventDefault();
            return;
        }
        var id = parseInt(obj.id);
        var data = 'id=' + id + "&status=" + inActive;
        fcom.ajax(fcom.makeUrl('ForumReportIssueReasons', 'changeStatus'), data, function (res) {
            search(document.srchFrmfrireasons);
        });
    };
})();
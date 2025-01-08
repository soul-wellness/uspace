(function ($) {
    var displayInPopup = false;
    $.yocoachmodal = function (data, popupView = false, dialogClassParm = "", modalClassParm = "", bodyClass = "") {
        modalClass = 'fixed-right ' + modalClassParm;
        var dialogClass = 'modal-dialog-vertical ' + dialogClassParm;
        var bodyClass = 'p-0' + bodyClass;

        /* !! is used to convert variable type in to bool. */
        displayInPopup = !!popupView;
        if (true == popupView) {
            modalClass = modalClassParm;
            dialogClass = 'modal-dialog-centered ' + dialogClassParm;
        }

        init(modalClass, dialogClass);
        if (data.ajax) {
            fillYKModalFromAjax(data.ajax);
        } else if (data.image) {
            fillYKModalFromImage(data.image);
        } else if (data.div) {
            fillYKModalFromHref(data.div);
        } else if ($.isFunction(data)) {
            data.call($);
        } else {
            $.yocoachmodal.reveal(data, bodyClass);
    }
    };

    $.extend($.yocoachmodal, {
        element: Date.now(),
        reveal: function (data, bodyClass) {
            if ($(data).hasClass("loaderJs") && 0 < $("." + $.yocoachmodal.element + " .loaderContainerJs").length) {
                $("." + $.yocoachmodal.element + " .loaderContainerJs").prepend(data);
                return;
            }

            if (0 == $(data).find(".modal-body").length && false === $(data).hasClass("modal-body")) {
                data = '<div class="modal-body">' + data + "</div>";
            }

            var contentBody = "." + $.yocoachmodal.element + " .contentBodyJs";
            $(contentBody).html(data);
            var headerHtm = '<div class="modal-header">';
            var closeBtnHtm = '<button type="button" class="btn-close yocoachmodalJs" data-bs-dismiss="modal" aria-label="' + langLbl.close + '"></button>';
            
            if (1 > $(contentBody).find(".modal-header").length && 1 <= $(contentBody).find(".card-head-title").length) {
                headerHtm = headerHtm + '<h5>' + $(contentBody).find(".card-head-title").text() + '</h5>';
                $(contentBody).find(".card-head").remove();
                $(contentBody).prepend(headerHtm + closeBtnHtm + "</div>");
            } else if (1 > $(contentBody).find(".modal-header").length) {
                $(contentBody).prepend(headerHtm + closeBtnHtm + "</div>");
            } else if (0 < $(contentBody).find(".modal-header").length && 1 > $("body ." + $.yocoachmodal.element + " .contentBodyJs .modal-header").find(".close").length) {
                $("body ." + $.yocoachmodal.element + " .contentBodyJs .modal-header").append(closeBtnHtm);
            }

            if ("undefined" != typeof bodyClass && 0 == $(data).find(bodyClass).length) {
                $(contentBody + " .modal-body").addClass(bodyClass);
            }

            $.yocoachmodal.show();
        },
        setEditorLayout: function (lang_id) {
            var editors = oUtil.arrEditor;
            layout = langLbl['language' + lang_id];
            for (x in editors) {
                $('#idContent' + editors[x]).contents().find("body").css('direction', layout);
            }
            $('table').find(".istoolbar_container").attr('dir', layout);
        },
        close: function () {
            $("." + $.yocoachmodal.element).modal("hide");
            return;
        },
        show: function () {
            $("." + $.yocoachmodal.element).modal("show");
            return;
        },
        isAdded: function () {
            return (0 < $("." + $.yocoachmodal.element).length);
        },
        remove: function () {
            $("." + $.yocoachmodal.element + ', .modal-backdrop').remove();
        },
        isSideBarView: function () {
            return !!$(".fixed-right." + $.yocoachmodal.element).length;
        }
    });

    function init(modalClass, dialogClass) {
        if (1 > $("body").find("." + $.yocoachmodal.element).length) {
            var content = '<div class="modal-dialog ' + dialogClass + ' " role="document"><div class="modal-content contentBodyJs"></div></div>';
            var htm = '<div class="modal ' + modalClass + ' fade ' + $.yocoachmodal.element + '" tabindex="-1" role="dialog">' + content + "</div>";
            $("body").append(htm);
        } else if (true === displayInPopup && true === $("." + $.yocoachmodal.element).hasClass('fixed-right')) {
            $("." + $.yocoachmodal.element).removeClass('fixed-right');
        } else if (false === displayInPopup && false === $("." + $.yocoachmodal.element).hasClass('fixed-right')) {
            $("." + $.yocoachmodal.element).addClass('fixed-right');
        }

        if (dialogClass != '' && !$("body ." + $.yocoachmodal.element + " .modal-dialog").hasClass(dialogClass)) {
            $("body ." + $.yocoachmodal.element + " .modal-dialog").removeClass( "modal-dialog-vertical-sm  modal-dialog-vertical-md modal-dialog-vertical-lg" ).addClass(dialogClass);
        }
    }

    function fillYKModalFromHref(href) {
        if (href.match(/#/)) {
            var url = window.location.href.split("#")[0];
            var target = href.replace(url, "");
            if (target === "#") {
                return;
            }
            $.yocoachmodal.reveal($(target).html());
        } else if (href.match($.yocoachmodal.settings.imageTypesRegexp)) {
            fillYKModalFromImage(href);
        } else {
            fillYKModalFromAjax(href);
        }
    }

    function fillYKModalFromImage(href) {
        var image = new Image();
        image.onload = function () {
            $.yocoachmodal.reveal('<div class="image"><img src="' + image.src + '" /></div>');
        };
        image.src = href;
    }

    function fillYKModalFromAjax(href) {
        $.yocoachmodal.jqxhr = $.get(href, function (data) {
            $.yocoachmodal.reveal(data);
        });
    }

    $(document).bind("close.yocoachmodal", function () {
        $.yocoachmodal.close();
    });

    $(document).on("hidden.bs.modal", "." + $.yocoachmodal.element, function () {
        $.yocoachmodal.close();
    });
})(jQuery);
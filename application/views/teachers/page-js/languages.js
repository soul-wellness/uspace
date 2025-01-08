$(document).ready(function () {
    search = function (frmSearch) {
        fcom.process();
        var data = fcom.frmData(frmSearch);
        fcom.ajax(fcom.makeUrl('Teachers', 'search', [true]), data, function (response) {
            $('#listing').html(response);
            $(".gototop").trigger('click');
        });
    };

    onkeyupLanguage = function() {
        $('.categOptParentJS').hide();
        var keyword = $('input[name="teach_language"]').val();
        $('.categorySelectOptJs:contains(' + keyword + ')').each(function () {
            $(this).parents('.categOptParentJS').show();
        });
    };
    /* overwrite contains function to match any letter case */
    jQuery.expr[':'].contains = function(a, i, m) {
        return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };
    $(document).on('click', '.panel-action', function () {
        $(this).parents('.panel-box').find('.panel-content').hide();
        var section = $(this).attr('content');
        if (section === 'video') {
            var iframe = $(this).parents('.panel-box').find('.' + section).find('iframe');
            if (typeof iframe.attr('src') == 'undefined') {
                iframe.attr('src', iframe.parent().attr('data-src'));
            }
        }
        $(this).parents('.panel-box').find('.' + section).show();
        $(this).parent().siblings().removeClass('is--active');
        $(this).parent().addClass('is--active');

    });
    searchLanguage = function (reset = false) {
        var language = [];
        $('input[name="teachs[]"]:checked').each(function () {
            language.push($(this).parent().find('.select-option__item').text());
        });
        var placeholder = '<div class="selected-filters"><span class="selected-filters__item">' + language.join(', ') +
        '</span></div>';
        $('.teachlang-placeholder-js').html(placeholder);
        if (reset === true) {
            return;
        }
        search(document.frmSearch);
        $("body").trigger('click');
    };

    $('.filter-item__trigger-js').click(function (event) {
        if ($(event.target).hasClass('selected-filters__action')) {
            return;
        }
        let isFilterMore = $(this).hasClass('filter-more-js');
        let magaFilter = $('.filters-more');
        let isParMegaBody = $(this).parents('.maga-body-js').length;
        if ($(this).hasClass("is-active")) {
            if (isParMegaBody == 0) {
                $(this).removeClass("is-active").siblings('.filter-item__target-js').slideUp();
                $('body').removeClass('filter-active');
            }
            if (isFilterMore) {
                $('.filters-more .filter-item__trigger-js').removeClass('is-active');
                $('.filters-more .filter-item__target-js').hide();
            }
            return;
        }
        if (isParMegaBody) {
            $('.filters-more .filter-item__trigger-js').removeClass('is-active');
            $('.filters-more .filter-item__target-js').hide();
            $(this).addClass("is-active").siblings('.filter-item__target-js').show();
            if ($(document).width() <= 767) {
                $('.filter-item__trigger-js').removeClass('is-active');
                $('.filter-item__target-js').hide();
                $(this).addClass("is-active").siblings('.filter-item__target-js').slideDown();
            }
        } else {
            $('.filter-item__trigger-js').removeClass('is-active');
            $('.filter-item__target-js').hide();
            $(this).addClass("is-active").siblings('.filter-item__target-js').slideDown();
        }
        $('body').addClass('filter-active');
        if (isFilterMore) {
            let megaBodyItem = magaFilter.find('.filter-item__trigger-js:first');
            megaBodyItem.addClass('is-active').siblings('.filter-item__target-js').show();
        }
    });

    $('body').click(function (e) {
        if ($(e.target).parents('.filter-item').length == 0) {
            $('.filter-item__trigger-js').siblings('.filter-item__target-js').slideUp();
            $('.filter-item__trigger-js').removeClass('is-active');
            $('body').removeClass('filter-active');
        }
    });

    gotoPage = function (pageno) {
        var frm = document.frmSearchPaging;
        $(frm.pageno).val(pageno);
        search(frm);
    };

    selectLanguage = function(slug) {
        window.location = fcom.makeUrl('Teachers', 'languages', [slug]);
    };

    searchLanguage(true);
    //shrinkFilters();
    search(document.frmSearch);
    document.frmSearch.pageno.value = 1;
});
// shrinkFilters = function () {
//     var shrink = 40;
//     $(window).scroll(function () {
//         var scroll = window.pageYOffset || document.documentElement.scrollTop;
//         if (scroll >= shrink) {
//             $('.section--listing').addClass('is-filter-fixed');
//         } else {
//             $('.section--listing').removeClass('is-filter-fixed');
//         }
//     });
// };

$(window).scroll(function() {
    var filterPanelOffset = $('.filter-panel').offset().top;
    var scrollPosition = $(window).scrollTop();

    if (scrollPosition >= filterPanelOffset) {
        $('.section--listing').addClass('is-filter-fixed');
    } else {
        $('.section--listing').removeClass('is-filter-fixed');
    }
});  

viewCalendar = function (teacherId) {
    fcom.ajax(fcom.makeUrl('Teachers', 'viewCalendar'), { teacherId: teacherId }, function (response) {
        $.yocoachmodal(response, { 'size': 'modal-xl middle-popup' });
    });
};
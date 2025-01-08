/* global fcom, langLbl, range, LABELS */
const priceRangeMin = 1;
$(document).ready(function () {

    $('input[name="keyword"]').on('keyup', function (event) {
        $(".filter-item__search-submit").show();
        $(".filter-item__search-reset").hide();
        if (event.keyCode == 13 && $(this).val() != '') {
            search(document.frmSearch);
            $(".filter-item__search-submit").hide();
            $(".filter-item__search-reset").show();
        }

    });

    searchKeyword = function () {
        var keyword = document.frmSearch.keyword.value;
        if (keyword.trim() != '') {
            $(".filter-item__search-submit").hide();
            $(".filter-item__search-reset").show();
        }
        search(document.frmSearch);
    };

    clearKeyword = function () {
        document.frmSearch.keyword.value = '';
        $(".filter-item__search-submit").show();
        $(".filter-item__search-reset").hide();
        search(document.frmSearch);
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
    }

    searchLanguage = function (reset = false) {
        var language = [];
        $('input[name="teachs[]"]:checked').each(function () {
            language.push($(this).parent().find('.select-option__item').text());
        });
        if (language.length > 0) {
            var placeholder = '<div class="selected-filters"><span class="selected-filters__item">' + language.join(', ') +
                '</span><span class="selected-filters__action" onclick="clearLanguage();"></span></div>';
            $('input[name="teach_language"]').val('').trigger('keyup');
            $('.teachlang-placeholder-js').html(placeholder);
        } else {
            $('.teachlang-placeholder-js').html(LABELS.allLanguages);
        }
        if (reset === true) {
            return;
        }
        search(document.frmSearch);
        $("body").trigger('click');
    };

    clearLanguage = function () {
        $('input[name="teach_language"]').val('').trigger('keyup');
        $('.teachlang-placeholder-js').html(LABELS.allLanguages);
        $('input[name="teachs[]"]').prop('checked', false);
        search(document.frmSearch);
        $("body").trigger('click');
    };

    searchPrice = function (reset = false) {
        var price = [];
        if (!isNaN(parseInt($('input[name="price_from"]').val()))) {
            price.push($('input[name="price_from"]').val());
        }
        if (!isNaN(parseInt($('input[name="price_till"]').val()))) {
            price.push($('input[name="price_till"]').val());
        }
        $('input[name="price[]"]:checked').each(function () {
            price.push($(this).parent().find('.select-option__item').text());
        });
        if (price.length > 0) {
            var placeholder = '<div class="selected-filters"><span class="selected-filters__item">' + price.join(', ') +
                '</span><span class="selected-filters__action" onclick="clearPrice();"></span></div>';
            $('.price-placeholder-js').html(placeholder);
        } else {
            $('.price-placeholder-js').html(LABELS.allPrices);
        }
        if (reset === true) {
            return;
        }
        search(document.frmSearch);
        $("body").trigger('click');
    };

    clearPrice = function () {
        $('.price-placeholder-js').html(LABELS.allPrices);
        $('input[name="price[]"]').prop('checked', false);
        $('input[name="price_from"]').val('');
        $('input[name="price_till"]').val('');
        search(document.frmSearch);
        resetSlider();
        $("body").trigger('click');
       
    };

    searchAvailbility = function (reset = false) {
        var avaialbility = [];
        $('input[name="days[]"]:checked, input[name="slots[]"]:checked').each(function () {
            avaialbility.push($(this).parent().find('.select-option__item').text());
        });
        if (avaialbility.length > 0) {
            var placeholder = '<div class="selected-filters"><span class="selected-filters__item">' + avaialbility.join(', ') +
                '</span><span class="selected-filters__action" onclick="clearAvailbility();"></span></div>';
            $('.availbility-placeholder-js').html(placeholder);
        } else {
            $('.availbility-placeholder-js').html(LABELS.selectTiming);
        }
        if (reset === true) {
            return;
        }
        search(document.frmSearch);
        $("body").trigger('click');
    };

    clearAvailbility = function () {
        $('.availbility-placeholder-js').html(LABELS.selectTiming);
        $('input[name="days[]"]').prop('checked', false);
        $('input[name="slots[]"]').prop('checked', false);
        search(document.frmSearch);
        $("body").trigger('click');
    };

    onkeyupLocation = function () {
        $('.select-location-js').parent().parent().hide();
        var keyword = ($('input[name="location_search"]').val()).toLowerCase();
        $('.select-location-js:contains("' + $.trim(keyword) + '")').parent().parent().show();
    };

    searchMore = function () {
        $('input[name="location_search"]').val('').trigger('onkeyup');
        search(document.frmSearch);
        $("body").trigger('click');
        countSelectedFilters();
    };

    clearMore = function (field) {
        $('input[name="' + field + '"]').prop('checked', false);
        $('input[name="location_search"]').val('').trigger('onkeyup');
        countSelectedFilters();
    };

    clearAllDesktop = function () {
        $('input[name="locations[]"]').prop('checked', false);
        $('input[name="gender[]"]').prop('checked', false);
        $('input[name="speaks[]"]').prop('checked', false);
        $('input[name="accents[]"]').prop('checked', false);
        $('input[name="levels[]"]').prop('checked', false);
        $('input[name="subjects[]"]').prop('checked', false);
        $('input[name="lesson_type[]"]').prop('checked', false);
        $('input[name="tests[]"]').prop('checked', false);
        $('input[name="age_group[]"]').prop('checked', false);
        countSelectedFilters();
        search(document.frmSearch);
        $("body").trigger('click');
    };

    clearAllMobile = function () {
        $(".filter-item__search-submit").show();
        $(".filter-item__search-reset").hide();
        $('.teachlang-placeholder-js').html(LABELS.allLanguages);
        $('.price-placeholder-js').html(LABELS.allPrices);
        $('.availbility-placeholder-js').html(LABELS.selectTiming);
        $('input[name="keyword"]').prop('value', '');
        $('input[name="teachs[]"]').prop('checked', false);
        $('input[name="price[]"]').prop('checked', false);
        $('input[name="days[]"]').prop('checked', false);
        $('input[name="slots[]"]').prop('checked', false);
        clearAllDesktop();
    };

    clearLocation = function () {
        $("#btnCloseJs").hide();
        $("input[name='user_lat'], input[name='user_lng'], input[name='address'],  input[name='formatted_address']").val('');
        search(document.frmSearch);
    };

    toggleSort = function () {
        $('body').toggleClass('sort-active');
        $('.sort-trigger-js').toggleClass('is-active');
        $('.sort-trigger-js').siblings('.sort-target-js').slideToggle();
    };

    sortsearch = function (sorting) {
        document.frmSearch.sorting.value = sorting;
        $("body").removeClass('sort-active');
        search(document.frmSearch);
    };

    search = function (frmSearch) {
        closeFilter();
        fcom.process();
        var data = fcom.frmData(frmSearch);
        fcom.ajax(fcom.makeUrl('Teachers', 'search'), data, function (response) {
            $('#listing').html(response);
            $(".gototop").trigger('click');
        });
    };

    gotoPage = function (pageno) {
        var frm = document.frmSearchPaging;
        $(frm.pageno).val(pageno);
        search(frm);
    };

    activeFilter = function () {
        $("body").addClass('filter-active');
    };

    inactiveFilter = function () {
        $("body").removeClass('filter-active');
    };

    openFilter = function () {
        $("body").addClass('is-filter-show');
        $("#filter-panel").addClass('is-filter-visible');
        setTimeout(function () {
            $('.filters-layout__item-second .filter-item__target').show();
            $('.filters-layout__item-second .filter-item__trigger').addClass('is-active');
        }, 500);
    };

    closeFilter = function () {
        $("body").removeClass('is-filter-show');
        $("#filter-panel").removeClass('is-filter-visible');
    };

    countSelectedFilters = function () {
        var language = $(".language-filter-js:checked").length;
        var price = $(".price-filter-js:checked").length;
        var availbility = $(".availbility-filter-js:checked").length;
        var country = $(".country-filter-js:checked").length;
        var gender = $(".gender-filter-js:checked").length;
        var speak = $(".speak-filter-js:checked").length;
        var accent = $(".accent-filter-js:checked").length;
        var level = $(".level-filter-js:checked").length;
        var subject = $(".subject-filter-js:checked").length;
        var include = $(".include-filter-js:checked").length;
        var test = $(".test-filter-js:checked").length;
        var agegroup = $(".age-group-filter-js:checked").length;
        var morecount = country + gender + speak + accent + level + subject + include + test + agegroup;
        (language > 0) ? $(".language-count-js").text(language).show() : $(".language-count-js").text('').hide();
        (price > 0) ? $(".price-count-js").text(price).show() : $(".price-count-js").text('').hide();
        (availbility > 0) ? $(".availbility-count-js").text(availbility).show() : $(".availbility-count-js").text('').hide();
        (country > 0) ? $(".country-count-js").text(country).show() : $(".country-count-js").text('').hide();
        (gender > 0) ? $(".gender-count-js").text(gender).show() : $(".gender-count-js").text('').hide();
        (speak > 0) ? $(".speak-count-js").text(speak).show() : $(".speak-count-js").text('').hide();
        (accent > 0) ? $(".accent-count-js").text(accent).show() : $(".accent-count-js").text('').hide();
        (level > 0) ? $(".level-count-js").text(level).show() : $(".level-count-js").text('').hide();
        (subject > 0) ? $(".subject-count-js").text(subject).show() : $(".subject-count-js").text('').hide();
        (include > 0) ? $(".include-count-js").text(include).show() : $(".include-count-js").text('').hide();
        (test > 0) ? $(".test-count-js").text(test).show() : $(".test-count-js").text('').hide();
        (agegroup > 0) ? $(".age-group-count-js").text(agegroup).show() : $(".age-group-count-js").text('').hide();
        (morecount > 0) ? $(".more-count-js").text(morecount).show() : $(".more-count-js").text('').hide();
    }

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


    showOffers = function (obj) {
        $(obj).parent('.toggle-dropdown').addClass("is-active");
    };

    hideOffers = function (obj) {
        $(obj).parent('.toggle-dropdown').removeClass("is-active");
    };

    viewCalendar = function (teacherId) {
        fcom.ajax(fcom.makeUrl('Teachers', 'viewCalendar'), { teacherId: teacherId }, function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl middle-popup' });
        });
    };

    searchOnline = function (online) {
        document.frmSearch.user_lastseen.value = online ? 1 : 0;
        search(document.frmSearch);
    };
    searchOfflineSessions = function (offline) {
        if(!offline){
            document.frmSearch.user_lat.value = 0;
            document.frmSearch.user_lng.value = 0;
            document.frmSearch.formatted_address.value = '';
        }
        document.frmSearch.user_offline_sessions.value = offline ? 1 : 0;
        search(document.frmSearch);
    };

    toggleAdressSrch = function (offline) {
        if (offline) {
            $('.geo-location_body').show();
        } else {
            $('.geo-location_body').hide();
        }
    }

    searchFeatured = function (featured) {
        document.frmSearch.user_featured.value = featured ? 1 : 0;
        search(document.frmSearch);
    };

    searchRadius = function (val) {
        document.frmSearch.user_radius.value = val;
        search(document.frmSearch);
    };
    autoCompleteGoogle = function () {
        const input = document.getElementById("google-autocomplete");
        const options = {
            fields: ["formatted_address", "geometry", "name", "place_id", "address_components"],
            strictBounds: false,
        };
        const autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.setTypes(['establishment']);
        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            setLatLng(place);
        });
    }

    setLatLng = function (place) {
        document.frmSearch.user_lat.value = place.geometry.location.lat();
        document.frmSearch.user_lng.value = place.geometry.location.lng();
        document.frmSearch.formatted_address.value = place.formatted_address;
        search(document.frmSearch);
        document.getElementById("google-autocomplete").value = place.formatted_address;
    }

    getLocation = function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(geocodePosition);
        } else {
            alert('location not detected');
        }
    }

    geocodePosition = function (pos) {
        var latlng = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            latLng: latlng
        }, function (responses) {
            if (responses && responses.length > 0) {
                setLatLng(responses[0]);
            } else {
                console.log('Cannot determine address at this location.');
            }
        });
    }

    function initSlider() {
        $("#priceslider").slider({
            min: parseInt($('input[name="minPrice"]').val()),
            max: parseInt($('input[name="maxPrice"]').val()),
            step: 1,
            range :true,
            isRTL: (layoutDirection == 'ltr') ? false : true,
            values: [1, $('input[name="maxPrice"]').val()],
            slide: function(event, ui) {
                for (var i = 0; i < ui.values.length; ++i) {
                    if (ui.values['0']> ui.values['1']) {
                        $("input.priceSliderValue[data-index=0]").val(ui.values['1']);
                        $("input.priceSliderValue[data-index=1]").val(ui.values['0']);
                        $('.priceslider-ranges').html(ui.values['1'] +' - '+ui.values['0']);
                    } else {
                        $("input.priceSliderValue[data-index=" + i + "]").val(ui.values[i]);
                        $('.priceslider-ranges').html(ui.values['0'] +' - '+ui.values['1']);
                    }
                }
            }
    
        });
    }
    
   

    function resetSlider() {
         $("#priceslider").slider('destroy');
         initSlider();
      }
      

    //shrinkFilters();
    searchLanguage(true);
    searchPrice(true);
    searchAvailbility(true);
    countSelectedFilters();
    initSlider();
    search(document.frmSearch);
    document.frmSearch.pageno.value = 1;
});
/* Search Fileter Sticky */
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
/* global fcom, langLbl, range, LABELS */
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

    searchClasstype = function (reset = false) {
        var classtype = [];
        $('input[name="classtype[]"]:checked').each(function () {
            classtype.push($(this).parent().find('.select-option__item').text());
        });
        if (classtype.length > 0) {
            var placeholder = '<div class="selected-filters"><span class="selected-filters__item">' + classtype.join(', ') +
                '</span><span class="selected-filters__action" onclick="clearClasstype();"></span></div>';
            $('.classtype-placeholder-js').html(placeholder);
        } else {
            $('.classtype-placeholder-js').html(LABELS.allClassTypes);
        }
        if (reset === true) {
            return;
        }
        search(document.frmSearch);
        $("body").trigger('click');
    };

    searchOfflineClasses = function (offline) {
        if(!offline){
            document.frmSearch.user_lat.value = 0;
            document.frmSearch.user_lng.value = 0;
            document.frmSearch.formatted_address.value = '';
        }
        document.frmSearch.grpcls_offline.value = offline ? 1 : 0;
        search(document.frmSearch);
    };

    toggleAdressSrch = function (offline) {
        if (offline) {
            $('.geo-location_body').show();
        } else {
            $('.geo-location_body').hide();
        }
    }

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

    clearClasstype = function () {
        $('.classtype-placeholder-js').html(LABELS.allClassTypes);
        $('input[name="classtype[]"]').prop('checked', false);
        search(document.frmSearch);
        $("body").trigger('click');
    };


    searchDuration = function (reset = false) {
        var duration = [];
        $('input[name="duration[]"]:checked').each(function () {
            duration.push($(this).parent().find('.select-option__item').text());
        });
        if (duration.length > 0) {
            var placeholder = '<div class="selected-filters"><span class="selected-filters__item">' + duration.join(', ') +
                '</span><span class="selected-filters__action" onclick="clearDuration();"></span></div>';
            $('.duration-placeholder-js').html(placeholder);
        } else {
            $('.duration-placeholder-js').html(LABELS.allDurations);
        }
        if (reset === true) {
            return;
        }
        search(document.frmSearch);
        $("body").trigger('click');
    };

    clearDuration = function () {
        $('.duration-placeholder-js').html(LABELS.allDurations);
        $('input[name="duration[]"]').prop('checked', false);
        search(document.frmSearch);
        $("body").trigger('click');
    };

    clearMore = function (field) {
        $('input[name="' + field + '"]').prop('checked', false);
    };

    clearAllMobile = function () {
        $(".filter-item__search-submit").show();
        $(".filter-item__search-reset").hide();
        $('.teachlang-placeholder-js').html(LABELS.allLanguages);
        $('.classtype-placeholder-js').html(LABELS.allClassTypes);
        $('.duration-placeholder-js').html(LABELS.allDurations);
        $('input[name="keyword"]').prop('value', '');
        $('input[name="teachs[]"]').prop('checked', false);
        $('input[name="classtype[]"]').prop('checked', false);
        $('input[name="duration[]"]').prop('checked', false);
        search(document.frmSearch);
        $("body").trigger('click');
    };
    
    clearLocation = function () {
        $("#btnCloseJs").hide();
        $("input[name='user_lat'], input[name='user_lng'], input[name='address'],  input[name='formatted_address']").val('');
        search(document.frmSearch);
    };

    search = function (frmSearch) {
        closeFilter();
        fcom.process();
        var data = fcom.frmData(frmSearch);
        fcom.ajax(fcom.makeUrl('GroupClasses', 'search'), data, function (response) {
            $('#listing').html(response);
            $(".gototop").trigger('click');
        });
    };

    goToSearchPage = function (pageno) {
        var frm = document.frmSearchPaging;
        $(frm.pageno).val(pageno);
        search(frm);
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


    showOffers = function (obj) {
        $(obj).parent('.toggle-dropdown').addClass("is-active");
    };

    hideOffers = function (obj) {
        $(obj).parent('.toggle-dropdown').removeClass("is-active");
    };

    toggleShare = function (element) {
        $(element).parent('.toggle-dropdown').toggleClass("is-active");
    };


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

    searchLanguage(true);
    searchClasstype(true);
    searchDuration(true);
    search(document.frmSearch);
    //shrinkFilters();
});

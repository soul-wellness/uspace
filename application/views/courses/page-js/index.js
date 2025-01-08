var filtersCount = '';
const priceRangeMin = 1;
$(document).ready(function() {
    search = function(frmSearch) {
        fcom.process();
        var data = fcom.frmData(frmSearch);
        fcom.ajax(fcom.makeUrl('Courses', 'search'), data, function(response) {
            $('#listing').html(response);
            (filtersCount > 0) ? $('.mobMoreCountJs').text(filtersCount).show(): $('.mobMoreCountJs').text(filtersCount).hide();
            $(".gototop").trigger('click');
        });
    };
    gotoPage = function(pageno) {
        var frm = document.frmSearchPaging;
        $(frm.pageno).val(pageno);
        search(frm);
    };

    clearFieldFilter = function(name) {
        $('select[name="' + name + '"]').val('');
        $('select[name="' + name + '"]').val('').trigger("change");
        $('input[name="' + name + '"], input[name="record_id"], input[name="type"]').val('');
        $('.submit-' + name + '-js').show();
        $('.reset-' + name + '-js').hide();
        $('.select2-selection__arrow').show();
        searchByFilters();
    };
    applyFilters = function(name, reset = false) {
        if ($('select[name="' + name + '"]').val().trim() != '') {
            $('.submit-' + name + '-js').hide();
            $('.reset-' + name + '-js').show();
            $('.select2-selection__arrow').hide();
        }
        if (reset === true) {
            return;
        }
        searchByFilters();
    };
    searchByFilters = function(close = 0) {
        if (close == 0) {
            search(document.frmSearch);
            closeFilter();
        }
    };

    /* Category [ */
    searchByCategory = function(reset = false) {
        var categories = '';
        $('.categorySelectJs input[type="checkbox"]:checked').each(function() {
            var text = $(this).parent().find('.select-option__item').text();
            categories += (categories == '') ? text : ', ' + text;
        });
        if (categories != '') {
            var placeholder = '<div class="selected-filters"><span class="selected-filters__item">' + categories +
                '</span><span class="selected-filters__action" onclick="clearCategorySearch();"></span></div>';
            $('.catgPlaceholderJs').html(placeholder);
        } else {
            $('.catgPlaceholderJs').html(categoryLbl);
        }
        if (reset === true) {
            return;
        }
        searchByFilters();
    };
    onkeyupCategory = function() {
        $('.categOptParentJS').hide();
        var keyword = ($('input[name="category"]').val()).toLowerCase();
        $('.categorySelectJs li').each(function() {
            $(this).find('.categorySelectOptJs:contains("' + keyword + '")').parents('.categOptParentJS').show();
        });
    };
    clearCategorySearch = function(close = 0) {
        $('input[name="course_cate_id[]"]').prop('checked', false);
        $('input[name="category"]').val('');
        $('.categOptParentJS, .categOptParentJS li').show();
        $('.catgPlaceholderJs').html(categoryLbl);
        searchByFilters(close);
        countCatgFilters();
    };
    $('.categorySelectJs input[type="checkbox"]').change(function() {
        countCatgFilters();
    });
    countCatgFilters = function() {
        var catg = $('.categorySelectJs').find('input[type="checkbox"]:checked').length;
        (catg > 0) ? $('.catgCountJs').text(catg).show(): $('.catgCountJs').text(catg).hide();
    };
    /* ] */

    /* Price [ */
    searchByPrice = function(reset = false) {
        var price = [];
        if (!isNaN(parseInt($('input[name="price_from"]').val()))) {
            price.push($('input[name="price_from"]').val());
        }
        if (!isNaN(parseInt($('input[name="price_till"]').val()))) {
            price.push($('input[name="price_till"]').val());
        }
        $('input[name="price[]"]:checked').each(function() {
            price.push($(this).parent().find('.select-option__item').text());
        });
        if (price.length > 0) {
            var placeholder = '<div class="selected-filters"><span class="selected-filters__item">' + price.join(', ') +
                '</span><span class="selected-filters__action" onclick="clearPrice();"></span></div>';
            $('.pricePlaceholderJs').html(placeholder);
        } else {
            $('.pricePlaceholderJs').html(priceLbl);
        }
        if (reset === true) {
            return;
        }
        searchByFilters();
    };
    $('.priceSelectJs input[type="checkbox"]').change(function() {
        countPriceFilters();
    });
    countPriceFilters = function() {
        var price = $('.priceSelectJs').find('input[type="checkbox"]:checked').length;
        (price > 0) ? $('.priceCountJs').text(price).show(): $('.priceCountJs').text(price).hide();
    };
    clearPrice = function(close = 0) {
        $('input[name="price[]"]').prop('checked', false);
        $('input[name="price_from"], input[name="price_till"]').val('');
        $('.pricePlaceholderJs').html(priceLbl);
        searchByFilters(close);
        countPriceFilters();
        resetSlider();
    };
    /* ] */

    /* Rating [ */
    searchByRating = function(reset = false) {
        var rating = $('.ratingSelectJs input[type="radio"]:checked').parent().find('.select-option__item').text();
        if (rating != '') {
            var placeholder = '<div class="selected-filters"><span class="selected-filters__item">' + rating +
                '</span><span class="selected-filters__action" onclick="clearRating();"></span></div>';
            $('.ratingPlaceholderJs').html(placeholder);
        } else {
            $('.ratingPlaceholderJs').html(ratingLbl);
        }
        if (reset === true) {
            return;
        }
        searchByFilters();
    };
    clearRating = function(close = 0) {
        $('input[name="course_ratings"]').prop('checked', false);
        $('.ratingPlaceholderJs').html(ratingLbl);
        searchByFilters(close);
        countRatingFilters();
    };
    $('.ratingSelectJs input[type="radio"]').change(function() {
        countRatingFilters();
    });
    countRatingFilters = function() {
        var rating = $('.ratingSelectJs').find('input[type="radio"]:checked').length;
        (rating > 0) ? $('.ratingCountJs').text(rating).show(): $('.ratingCountJs').text(rating).hide();
    };
    /* ] */

    /* Course Level [ */
    clearLevelFilters = function() {
        $('.levelFiltersJs').find('input[type="checkbox"]').prop('checked', false);
        $('input[name="course_levels"]').val('');
        $('.levelFiltersJs li').show();
        countLevelFilters();
    };
    countLevelFilters = function() {
        var levels = $('.levelFiltersJs').find('input[type="checkbox"]:checked').length;
        (levels > 0) ? $('.levelCountJs').text(levels).show(): $('.levelCountJs').text(levels).hide();
    };
    $('.levelFiltersJs input[type="checkbox"]').change(function() {
        countLevelFilters();
    });
    onkeyupLevels = function() {
        $('.levelFiltersJs li').hide();
        var keyword = ($('input[name="course_levels"]').val()).toLowerCase();
        $('.levelFiltersJs li .levelSelectOptJs:contains("' + keyword + '")').parent().parent().show();
    };
    /* ] */

    /* Languages [ */
    $('.langFiltersJs input[type="checkbox"]').change(function() {
        countLangFilters();
    });
    countLangFilters = function() {
        var languages = $('.langFiltersJs').find('input[type="checkbox"]:checked').length;
        (languages > 0) ? $('.langCountJs').text(languages).show(): $('.langCountJs').text(languages).hide();
    };
    clearLangSearch = function() {
        $('.langFiltersJs').find('input[type="checkbox"]').prop('checked', false);
        $('input[name="course_languages"]').val('');
        $('.langFiltersJs li').show();
        countLangFilters();
    };
    onkeyupLangs = function() {
        $('.langFiltersJs li').hide();
        var keyword = ($('input[name="course_languages"]').val()).toLowerCase();
        $('.langFiltersJs li .langSelectOptJs:contains("' + keyword + '")').parent().parent().show();
    };
    /* ] */

    /* Sorting */
    toggleSort = function(obj) {
        $('body').toggleClass('sort-active');
        $(obj).toggleClass('is-active');
        $(obj).siblings('.sort-target-js').slideToggle();
    };
    priceSortSearch = function(sorting) {
        document.frmSearch.price_sorting.value = sorting;
        $("body").removeClass('sort-active');
        search(document.frmSearch);
    };
    /* ] */

    /* More Filters [ */
    applyMoreFilters = function() {
        searchByFilters();
        countSelectedFilters();
    };
    clearAllFiltersWeb = function() {
        $('.moreFiltersJs').find('input[type="checkbox"]').prop('checked', false);
        $('.categOptParentJS, .categOptParentJS li, .levelFiltersJs li, .langFiltersJs li').show();
        searchByFilters();
        countSelectedFilters();
    };
    clearAllFiltersMobile = function() {
        $('.basicFiltersJs input[type="radio"], .basicFiltersJs input[type="checkbox"]').prop('checked', false);
        $('.moreFiltersJs input[type="text"], .basicFiltersJs input[type="text"]').val('');
        filtersCount = '';
        clearAllFiltersWeb();
    };
    countSelectedFilters = function() {
        var levels = $('.levelFiltersJs').find('input[type="checkbox"]:checked').length;
        var languages = $('.langFiltersJs').find('input[type="checkbox"]:checked').length;
        var count = levels + languages;
        (levels > 0) ? $('.levelCountJs').text(levels).show(): $('.levelCountJs').text(levels).hide();
        (languages > 0) ? $('.langCountJs').text(languages).show(): $('.langCountJs').text(languages).hide();
        (count > 0) ? $('.moreCountJs').text(count).show(): $('.moreCountJs').text(count).hide();

        var categories = $('.categorySelectJs').find('input[type="checkbox"]:checked').length;
        var prices = $('.priceSelectJs').find('input[type="checkbox"]:checked').length;
        var ratings = $('.ratingSelectJs').find('input[type="radio"]:checked').length;
        (categories > 0) ? $('.catgCountJs').text(categories).show(): $('.catgCountJs').text(categories).hide();
        (prices > 0) ? $('.priceCountJs').text(prices).show(): $('.priceCountJs').text(prices).hide();
        (ratings > 0) ? $('.ratingCountJs').text(ratings).show(): $('.ratingCountJs').text(ratings).hide();
        filtersCount = count + categories + prices + ratings;
    };
    closeFilter = function() {
        $("body").removeClass('filter-active is-filter-show');
        $("#filter-panel").removeClass('is-filter-visible');
        $("body").trigger('click');
    };
    /* ] */

    showPreviewVideo = function (courseId) {
        fcom.process();
        fcom.ajax(fcom.makeUrl('Courses', 'previewVideo', [courseId]), '', function (resp) {
            $.yocoachmodal(resp,{ 'size': 'modal-lg' });
            fcom.close();
        });
    };
    let options = [];
    $('select[name="keyword"]').select2({
        placeholder: langLbl.courseSrchPlaceholder,
        language: {
            searching: function() {
                return langLbl.searching;
            }
        },
        ajax: {
            url: fcom.makeUrl('Courses', 'autoComplete'),
            type: 'post',
            dataType: 'json',
            delay: 1000,
            data: function (params) {
                var query = {
                  term: params.term,
                  fIsAjax:1
                }
                return query;
            },
            processResults: function (data) {
                if (data.length > 0) {
                    $.each(data, function(key, value) {
                        $.each(value.children, function(key1, value1) {
                            options[value1.id] = {
                                type: value.type,
                                text: value1.text
                            };
                        });
                    });
                }
                return {
                    results: data
                }
            }
        }
    }).on("select2:select", function(e) {
        var group = options[e.params.data.id].type;
        var text = options[e.params.data.id].text;
        var reset = false;
        if (e.params.data.reset) {
            reset = e.params.data.reset;
        }
        $('input[name="record_id"]').val(e.params.data.id);
        $('input[name="type"]').val(group);
        $('input[name="search_keyword"]').val(text);
        applyFilters('keyword', reset);
    });

    /* filters */
    $('.filter-item__trigger-js').click(function(event) {
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

    $('body').click(function(e) {
        if ($(e.target).parents('.filter-item').length == 0) {
            $('.filter-item__trigger-js').siblings('.filter-item__target-js').slideUp();
            $('.filter-item__trigger-js').removeClass('is-active');
            $('body').removeClass('filter-active');
        }
    });

    if ($(window).width() < 576) {
        $('.filters-layout__item-second .filter-item__trigger').addClass('is-active');
        $('.filters-layout__item-second .filter-item__target').show();
    }
    toggleCourseFavorite = function(courseId, el) {
        var status = $(el).data('status');
        var data = 'course_id= ' + courseId + '&status=' + status;
        fcom.updateWithAjax(fcom.makeUrl('Courses', 'toggleFavorite', [], confWebDashUrl), data, function(resp) {
            if (status == 0) {
                $(el).data("status", 1).addClass("is-active");
            } else {
                $(el).data("status", 0).removeClass("is-active");
            }
        });
    };
    searchByKeyword = function() {
        var keywordData = {
            id: $('input[name="record_id"]').val(),
            text: $('input[name="search_keyword"]').val(),
        };
        if (keywordData.id != '') {
            options[keywordData.id] = {
                type: $('input[name="type"]').val(),
                text: $('input[name="search_keyword"]').val()
            }
            var newOption = new Option(keywordData.text, keywordData.id, false, false);
            $('select[name="keyword"]').append(newOption).trigger('change');
            $('select[name="keyword"]').trigger({
                type: 'select2:select',
                params: {
                    data: {
                        id: keywordData.id,
                        text: keywordData.text,
                        selected: true,
                        reset: true,
                    }
                }
            });
        }
    };

    /* Search Fileter Sticky */
    // shrinkFilters = function() {
    //     var shrink = 40;
    //     $(window).scroll(function() {
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
    


    function initSlider() {
        $("#priceslider").slider({
            min: parseInt($('input[name="minPrice"]').val()),
            max: parseInt($('input[name="maxPrice"]').val()),
            step: 1,
            range :true,
            isRTL: (layoutDirection == 'ltr') ? false : true,
            values: [parseInt($('input[name="minPrice"]').val()), $('input[name="maxPrice"]').val()],
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

    searchByCategory(true);
    searchByPrice(true);
    searchByRating(true);
    countSelectedFilters();
    searchByKeyword();
    searchByFilters();
    //shrinkFilters();
    initSlider();
});
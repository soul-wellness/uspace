/* global langLbl, LANGUAGES, fcom */
$("document").ready(function () {
    $('.caraousel--single-js').slick({
        autoplay: true,
        arrows: false,
        dots: true,
        fade: true,
        rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
        responsive: [{breakpoint: 767, settings: {arrows: false, dots: true}}]
    });
    /* Common Carousel */
    var _carousel = $('.js-carousel');
    _carousel.each(function () {
        var _this = $(this),
                _slidesToShow = (_this.data("slides")).toString().split(',');
        /* slick common carousel init */
        _this.slick({
            slidesToShow: parseInt(_slidesToShow.length > 0 ? _slidesToShow[0] : "3"),
            slidesToScroll: 1,
            rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
            arrows: _this.data("arrows"),
            dots: _this.data("dots"),
            infinite: true,
            autoplay: true,
            pauseOnHover: true,
            responsive: [
                {breakpoint: 1200, settings: {slidesToShow: parseInt(parseInt(_slidesToShow.length > 1 ? _slidesToShow[1] : "2"))}},
                {breakpoint: 768, settings: {slidesToShow: parseInt(parseInt(_slidesToShow.length > 2 ? _slidesToShow[2] : "1"))}},
                {breakpoint: 576, settings: {slidesToShow: parseInt(parseInt(_slidesToShow.length > 3 ? _slidesToShow[3] : "1"))}}
            ]
        });
    });
    /* End of Common Carousel */
    $('.vert-carousel').slick({
        slidesToShow: 3,
        rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        arrow: true,
        vertical: true
    });
    $('.slideshow-js').slick({
        dots: false,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        autoplay: true,
        arrows: false,
        rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
        responsive: [{ breakpoint: 576, settings: { slidesToShow: 1, arrows: false, dots: true } }]
    });
    $('.slider-onethird-js').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        adaptiveHeight: true,
        dots: false,
        rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
        prevArrow: '<button class="slick-prev cursor-hide" aria-label="Previous" type="button">Previous</button>',
        nextArrow: '<button class="slick-next cursor-hide" aria-label="Next" type="button">Next</button>',
        responsive: [
            {breakpoint: 1199, settings: {slidesToShow: 2, arrows: false, dots: true}},
            {breakpoint: 1023, settings: {slidesToShow: 2, arrows: false, dots: true}},
            {breakpoint: 767, settings: {slidesToShow: 2, arrows: false, dots: true}},
            {breakpoint: 576, settings: {slidesToShow: 1, arrows: false, dots: true}}
        ]
    });
    $('.step-slider-js').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
        arrows: false,
        dots: true,
        asNavFor: '.slider-tabs--js'
    });
    $('.slider-tabs--js').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.step-slider-js',
        rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
        dots: true,
        centerMode: true,
        focusOnSelect: true
    });
    $('.slider-quote-js').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
        autoplay: false,
        adaptiveHeight: true,
        dots: false,
        prevArrow: '<button class="slick-prev cursor-hide" aria-label="Previous" type="button">Previous</button>',
        nextArrow: '<button class="slick-next cursor-hide" aria-label="Next" type="button">Next</button>',
        responsive: [
            {breakpoint: 1199, settings: {fade: false, infinite: true, centerMode: true, centerPadding: '15%', arrows: false, dots: true}},
            {breakpoint: 1023, settings: {fade: false, infinite: true, centerMode: true, centerPadding: '15%', arrows: false, dots: true}},
            {breakpoint: 767, settings: {fade: false, infinite: true, centerMode: true, centerPadding: '15%', arrows: false, dots: true}},
            {breakpoint: 576, settings: {fade: false, infinite: true, centerMode: true, centerPadding: '5%', arrows: false, dots: true}}
        ]
    });
    $('.slider-onehalf-js').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: false,
        rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
        arrows: true,
        adaptiveHeight: true,
        dots: false,
        pauseOnHover:false,
        responsive: [
            {breakpoint: 1199, settings: {slidesToShow: 2, dots: true, arrows: false}},
            {breakpoint: 1023, settings: {slidesToShow: 2, dots: true, arrows: false}},
            {breakpoint: 767, settings: {slidesToShow: 1, dots: true, arrows: false}},
        ]
    });

    toggleCourseFavorite = function (courseId, el) {
        var status = $(el).data('status');
        var data = 'course_id= ' + courseId + '&status=' + status;
        fcom.updateWithAjax(fcom.makeUrl('Courses', 'toggleFavorite', [], confWebDashUrl), data, function (resp) {
            if (status == 0) {
                $(el).data("status", 1).addClass("is-active");
            } else {
                $(el).data("status", 0).removeClass("is-active");
            }
        });
    };
    $(document).mouseup(function (e) {
        var container = $(".autoSuggestJs").parent();
        if ((!container.is(e.target) && container.has(e.target).length === 0)) {
            container.hide();
        }
        var container2 = $(".filterTypeJs").parent().parent();
        if (!container2.is(e.target) && container2.has(e.target).length === 0) {
            container2.hide();
        }
    });
    $('body').on('focus', '#homeSearchFld', function() {
        $('html, body').animate({ scrollTop: $('.site-search').offset().top - 100 }, 1000);
    });
    
    function keywordSrch() {
        var keyword = $('#homeSearchFld').val();
        var type = $('input[name="type"]').val();
        if (keyword.length >= 3) {
            fcom.ajax(fcom.makeUrl('Home', 'autoComplete'), { keyword, type }, function (resp) {
                if (resp == '') {
                    $('.autoSuggestJs').html(resp).parent().hide();
                } else {
                    $('.autoSuggestJs').html(resp).parent().show();
                }
            });
        } else {
            $('.autoSuggestJs').html('').parent().hide();
        }
    }
    const debounceSearch = fatDebounce(keywordSrch, 500);
    $('body').on('input', '#homeSearchFld', function () {
        debounceSearch();
    });

    if( $(window).width() > 576 ){
        $(".expand-trigger-js").click(function () {
            $('.search-dropdown').toggleClass("is-active");
            $(".expand-target-js").slideToggle();
        });
    }

    $('.filterTypeJs a').click(function () {
        $('.filterTypeJs a').removeClass('is-active');
        $(this).addClass('is-active');
        $('.selectedFilterJs').text($(this).text());
        $('input[name="type"]').val($(this).data('filter'));
        $('.expand-trigger-js').click();
        $('#homeSearchFld').trigger('input');
    });

    var _tab = $('.js-inline-tabs');
    _tab.each(function () {

        var _this = $(this),
            _tabTrigger = _this.find('a'),
            _tabTarget = [];

        _tabTrigger.each(function () {

            var _this = $(this),
                _target = $(_this.attr('href'));

            _tabTarget.push(_target);

            _this.on('click', function (e) {
                e.preventDefault();
                _tabTrigger.removeClass('is-active');

                $.each(_tabTarget, function (index, _thisTarget) {
                    _thisTarget.removeClass('visible');
                });

                _this.addClass('is-active');
                _target.addClass('visible');
                $('.slider-oneforth-js').slick('refresh');
            });
        });
    });

    $('.slider-oneforth-js').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        adaptiveHeight: true,
        dots: false,
        rtl: (langLbl.layoutDirection == 'rtl') ? true : false,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                    dots: true,
                    arrows: false,
                }
            },
            {
                breakpoint: 1023,
                settings: {
                    slidesToShow: 2,
                    dots: true,
                    arrows: false,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    dots: true,
                    arrows: false,
                }
            },

        ]
    });
});

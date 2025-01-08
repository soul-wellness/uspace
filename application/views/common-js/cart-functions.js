/* global fcom, moment, props, langLbl, parseFloat, minValue, LESSON_TYPE_SUBCRIP, LESSON_TYPE_REGULAR, maxValue, FTRAIL_TYPE */
var cart = {
    prop: {
        ordles_teacher_id: 0,
        ordles_tlang_id: 0,
        ordles_duration: 0,
        ordles_quantity: 1,
        ordles_type: 0,
        ordles_starttime: "",
        ordles_endtime: "",
        ordles_offline: 0,
        add_and_pay: 0,
        ordles_address_id: 0,
        slots: []
    },
    updateQuantity: function (operation) {
        var oldQty = parseInt($("input[name=ordles_quantity]").val());
        if (operation == "+" && oldQty < maxValue) {
            cart.prop.ordles_quantity += 1;
        } else if (operation == "-" && oldQty > minValue) {
            cart.prop.ordles_quantity -= 1;
        }
        $("input[name=ordles_quantity]").val(cart.prop.ordles_quantity);
        if (cart.prop.ordles_quantity != oldQty) {
            var qty = cart.prop.ordles_quantity;
            if (qty <= parseInt(maxValue) && qty >= parseInt(minValue)) {
                $('#price-js').text(formatMoney(parseFloat(qty * price).toFixed(2)));
                return;
            }
            $('#price-js').text(formatMoney(0));
            fcom.error(langLbl.lessonNotAvailable);
        }
    },
    langSlots: function (teacherId, tlangId, duration) {
        cart.prop.ordles_teacher_id = teacherId;
        cart.prop.ordles_tlang_id = (tlangId != '') ? tlangId : parseInt($('input[name="ordles_tlang_id"]:checked').val());
        cart.prop.ordles_duration = (duration != '') ? duration : parseInt($('input[name="ordles_duration[' + cart.prop.ordles_tlang_id + ']"]:checked').val());
        fcom.process();
        fcom.ajax(fcom.makeUrl("Cart", "langSlots"), cart.prop, function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
            cart.selectLanguage(cart.prop.ordles_tlang_id);
            callAnalyticsEvent('book_lesson', {});
        });
    },
    priceSlabs: function (teacherId, tlangId, duration, quantity, type, offline) {
        cart.prop.ordles_teacher_id = teacherId;
        cart.prop.ordles_tlang_id = tlangId;
        cart.prop.ordles_duration = duration;
        cart.prop.ordles_quantity = (quantity != '') ? quantity : parseInt($('input[name="ordles_quantity"]').val());
        cart.prop.ordles_type = (type != '') ? type : parseInt($('input[name="ordles_type"]:checked').val());
        cart.prop.ordles_offline = (offline != '') ? offline : parseInt($('input[name="ordles_offline"]:checked'));
        cart.prop.ordles_address_id = (cart.prop.ordles_offline == 1) ? cart.prop.ordles_address_id : 0;
        fcom.process();
        fcom.ajax(fcom.makeUrl("Cart", "priceSlabs"), cart.prop, function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
            cart.prop.slots = [];
        });
    },
    viewCalendar: function (teacherId, tlangId, duration, quantity, type) {
        cart.prop.ordles_teacher_id = teacherId;
        cart.prop.ordles_tlang_id = tlangId;
        cart.prop.ordles_duration = duration;
        cart.prop.ordles_quantity = quantity;
        cart.prop.ordles_type = type;
        fcom.process();
        freeTrial = 0;
        fcom.ajax(fcom.makeUrl("Cart", "viewCalendar"), cart.prop, function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
        });
    },
    trailCalendar(teacherId) {
        fcom.process();
        cart.prop.ordles_type = FTRAIL_TYPE;
        freeTrial = FTRAIL_TYPE;
        fcom.ajax(fcom.makeUrl("Cart", "trailCalendar"), { ordles_type: cart.prop.ordles_type, teacherId: teacherId }, function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
            callAnalyticsEvent('book_trial_lesson', {});
        });
    },
    addLesson: function () {
        fcom.process();
        cart.prop.startTime = [];
        cart.prop.endTime = [];
        for (let elements in cart.prop.slots) {
            cart.prop.startTime.push(cart.prop.slots[elements].ordles_starttime);
            cart.prop.endTime.push(cart.prop.slots[elements].ordles_endtime);
        }
        fcom.ajax(fcom.makeUrl("Cart", "addLesson"), cart.prop, function (response) {
            if (isJson(response)) {
                var res = JSON.parse(response);
                if (res.status == 1 && cart.prop.ordles_type == FTRAIL_TYPE) {
                    cart.confirmOrder(document.checkoutForm);
                }
            } else {
                $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
            }
        });
    },
    addSubscriptionLesson: function () {
        fcom.process();
        cart.prop.startTime = [];
        cart.prop.endTime = [];
        for (let elements in cart.prop.slots) {
            cart.prop.startTime.push(cart.prop.slots[elements].ordles_starttime);
            cart.prop.endTime.push(cart.prop.slots[elements].ordles_endtime);
        }
        fcom.ajax(fcom.makeUrl("Cart", "addLesson"), cart.prop, function (response) {
            if (isJson(response)) {
                var res = JSON.parse(response);
                if (res.status == 1) {
                    cart.confirmOrder(document.checkoutForm);
                }
            } else {
                $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
            }
        });
    },
    addSubscription: function () {
        if (Object.keys(cart.prop.slots).length != cart.prop.ordles_quantity) {
            return false;
        }
        fcom.process();
        cart.prop.startTime = [];
        cart.prop.endTime = [];
        for (let elements in cart.prop.slots) {
            cart.prop.startTime.push(cart.prop.slots[elements].ordles_starttime);
            cart.prop.endTime.push(cart.prop.slots[elements].ordles_endtime);
        }
        fcom.ajax(fcom.makeUrl("Cart", "addSubscription"), cart.prop, function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
        });
    },
    addClass: function (classId) {
        fcom.process();
        fcom.ajax(fcom.makeUrl("Cart", "addClass"), { grpcls_id: classId }, function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
            callAnalyticsEvent('book_class', {});
        });
    },
    addPackage: function (packageId) {
        fcom.process();
        fcom.ajax(fcom.makeUrl("Cart", "addPackage"), { packageId: packageId }, function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
            callAnalyticsEvent('book_class', {});
        });
    },
    addCourse: function (courseId) {
        fcom.process();
        fcom.ajax(fcom.makeUrl("Cart", "addCourse"), { course_id: courseId }, function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
            callAnalyticsEvent('book_course', {});
        });
    },
    addFreeCourse: function (courseId) {
        fcom.process();
        fcom.ajax(fcom.makeUrl("Cart", "addCourse"), { course_id: courseId }, function (response) {
            callAnalyticsEvent('book_course', {});
            cart.confirmOrder(document.frmCheckout);
        });
    },
    selectWallet: function (checked) {
        document.checkoutForm.add_and_pay.value = checked ? 1 : 0;
        if (!$(document.checkoutForm).validate()) {
            return;
        }
        fcom.process();
        var orderType = document.checkoutForm.order_type.value;
        fcom.ajax(fcom.makeUrl("Cart", "paymentSummary", [orderType]), fcom.frmData(document.checkoutForm), function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
        });
    },
    confirmOrder: function (form) {
        if (!$(form).validate()) {
            return;
        }
        if (typeof freeTrial !== 'undefined') {
            form.ordles_type.value = freeTrial;
        }
        fcom.process();
        form.submit.disabled = true;
        fcom.updateWithAjax(fcom.makeUrl("Cart", "confirmOrder"), fcom.frmData(form), function (response) {
            callAnalyticsEvent('confirm_order', {});
            setTimeout(function () {
                form.submit.disabled = false;
            }, 1000);
            if (response.redirectUrl) {
                window.location.href = response.redirectUrl;
            }
            if (response.status != 1) {
                form.submit.disabled = false;
            }
        }, { failed: true });
    },
    applyCoupon: function (code) {
        if (code) {
            document.checkoutForm.coupon_code.value = code;
        }
        if (!$(document.checkoutForm).validate()) {
            return;
        }
        fcom.process();
        fcom.ajax(fcom.makeUrl("Cart", "applyCoupon"), fcom.frmData(document.checkoutForm), function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
        });
    },
    removeCoupon: function () {
        fcom.process();
        fcom.ajax(fcom.makeUrl("Cart", "removeCoupon"), fcom.frmData(document.checkoutForm), function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
        });
    },
    applyRewards: function (cb) {
        var status = cb.checked ? 1 : 0;
        document.checkoutForm.apply_reward.value = status;
        if (!$(document.checkoutForm).validate()) {
            return;
        }
        fcom.process();
        fcom.ajax(fcom.makeUrl("Cart", "applyRewards"), fcom.frmData(document.checkoutForm), function (response) {
            if (isJson(response)) {
                var res = JSON.parse(response);
                if (res.status == 0) {
                    $('input[name="apply_reward"]').attr('checked', false);
                    return;
                }
            } else {
                $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
            }
        }, { 'failed': true });
    },
    toggleLanguage: function () {
        $('.select-slot-target-js').slideUp();
        $('.select-slot-trigger-js').removeClass('is-active');
        var trigger = $('.select-tlang-trigger-js');
        if (trigger.hasClass('is-active')) {
            trigger.removeClass('is-active');
            trigger.siblings('.select-tlang-target-js').slideUp();
            return false;
        }
        $('.select-tlang-trigger-js').removeClass('is-active');
        trigger.addClass("is-active");
        $('.select-tlang-target-js').slideUp();
        if ($('input[name="ordles_tlang_id"]').length > 1) {
            trigger.siblings('.select-tlang-target-js').slideDown();
        }
    },
    selectLanguage: function (ordles_tlang_id) {
        $('.select-tlang-target-js').slideUp();
        $('.select-tlang-trigger-js').removeClass('is-active');
        var selectedLang = $('input[name="ordles_tlang_id"]:checked')
            .parent().find('.select-option__title span').text();
        $('.selected-tlang-target-js').text(selectedLang);
        cart.prop.ordles_tlang_id = ordles_tlang_id;
        $('.timeslot-js').removeClass('is-active').hide();
        $('.timeslot-js-' + ordles_tlang_id).addClass('is-active').show();
        var checked = $('input[name="ordles_duration[' + ordles_tlang_id + ']"]:checked');
        checked.parent().click();
        cart.selectDuration(checked.val());
    },
    toggleDuration: function () {
        $('.select-tlang-target-js').slideUp();
        $('.select-tlang-trigger-js').removeClass('is-active');
        var trigger = $('.select-slot-trigger-js');
        if (trigger.hasClass('is-active')) {
            trigger.removeClass('is-active');
            trigger.siblings('.select-slot-target-js').slideUp();
            return false;
        }
        $('.select-slot-trigger-js').removeClass('is-active');
        trigger.addClass("is-active");
        $('.select-slot-target-js').slideUp();
        if ($('input[name="ordles_duration[' + cart.prop.ordles_tlang_id + ']"]').length > 1) {
            trigger.siblings('.select-slot-target-js').slideDown();
        }
    },
    selectDuration: function (ordles_duration) {
        $('.select-slot-target-js').slideUp();
        $('.select-slot-trigger-js').removeClass('is-active');
        var selectedSlot = $('input[name="ordles_duration[' + cart.prop.ordles_tlang_id + ']"]:checked')
            .parent().find('.select-option__title span').text();
        if (selectedSlot) {
            $('.selected-slot-target-js').text(selectedSlot).show();
            cart.prop.ordles_duration = ordles_duration;
        } else {
            $('.selected-slot-target-js').text(langLbl.noSlotAvailable).show();
        }
    },
    selectSubscription: function (type = '') {
        if (type != '') {
            cart.prop.ordles_type = type;
            $('input[name="ordles_type"]').click();
            return;
        }
        if ($('input[name="ordles_type"]:checked').length > 0) {
            cart.prop.ordles_type = LESSON_TYPE_SUBCRIP;
        } else {
            cart.prop.ordles_type = LESSON_TYPE_REGULAR;
        }
    },
    selectOfflineSession: function (addressId = 0) {
        if ($('input[name="ordles_offline"]:checked').length > 0) {
            cart.prop.ordles_offline = 1;
            cart.prop.ordles_address_id = addressId;
        } else {
            cart.prop.ordles_offline = 0;
            cart.prop.ordles_address_id = 0;
        }
    },
    disableEnter: function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            if (document.checkoutForm.coupon_code.value != '') {
                cart.applyCoupon(document.checkoutForm.coupon_code.value);
                return;
            }
        }
    },
    addSubscriptionPlan: function (planId) {
        fcom.process();
        fcom.ajax(fcom.makeUrl("Cart", "addSubscriptionPlan"), { planId: planId }, function (response) {
            $.yocoachmodal(response, { 'size': 'modal-xl checkout--modal' });
            callAnalyticsEvent('book_subscription_plan', {});
        });
    }
};
$(document).on("hidden.bs.modal", "#yocoachModal", function () {
    cart.prop.ordles_teacher_id = 0;
    cart.prop.ordles_tlang_id = 0;
    cart.prop.ordles_duration = 0;
    cart.prop.ordles_quantity = 1;
    cart.prop.ordles_type = 0;
    cart.prop.ordles_starttime = "";
    cart.prop.ordles_endtime = "";
    cart.prop.add_and_pay = 0;
    cart.prop.ordles_offline = 0;
    cart.prop.ordles_address_id = 0;
});
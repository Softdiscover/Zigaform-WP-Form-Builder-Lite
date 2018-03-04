/**
 * Created by umeshkumar on 12/05/16.
 */
jQuery(function () {
    var el_notice = jQuery(".zgfm-ext-notice"),
        btn_act = el_notice.find(".zgfm-ext-notice-act"),
        btn_dismiss = el_notice.find(".zgfm-dismiss-welcome");
    el_notice.fadeIn(500);

    function zgfm_remove_notice() {
        el_notice.fadeTo(100, 0, function () {
            el_notice.slideUp(100, function () {
                el_notice.remove();
            });
        });
    }

    btn_act.click(function (ev) {
        zgfm_remove_notice();
        zgfm_notify_wordpress(btn_act.data("msg"),'zgfm_dismiss_upgrade_notice');
    });
    btn_dismiss.click(function (ev) {
        zgfm_remove_notice();
        zgfm_notify_wordpress(btn_act.data("msg"),'zgfm_dismiss_upgrade_notice');
    });

    
    function zgfm_notify_wordpress(message,action_name) {
        el_notice.attr("data-message", message);
        el_notice.addClass("loading");

        var param = {
            action: action_name
        };
        jQuery.post(ajaxurl, param);
    }
    
    //notice 2
    
    var btn_act2 = el_notice.find(".zgfm-ext-notice2-act"),
        btn_dismiss2 = el_notice.find(".zgfm-ext-notice2-act2"),
        btn_dismiss3 = el_notice.find(".zgfm-dismiss-rated");
        
    btn_act2.click(function (ev) {
        zgfm_remove_notice();
        zgfm_notify_wordpress(btn_act2.data("msg"),'zgfm_c_notice_dismiss');
    });
    btn_dismiss2.click(function (ev) {
        zgfm_remove_notice();
        zgfm_notify_wordpress(btn_act2.data("msg"),'zgfm_c_notice_dismiss');
    });   
     btn_dismiss3.click(function (ev) {
        zgfm_remove_notice();
        zgfm_notify_wordpress(btn_act2.data("msg"),'zgfm_c_notice_rated');
    }); 
    

});
let role = get_cplat_vars.current_user_roles;
let user_type = get_cplat_vars.user_type;

roles_arr = role.split(',');
(function ($) {
    'use strict';
    //RSPL Task#83
    var s_platform_result_response = '';
    /*-----------------------------------------------------------------------------------*/
    /*	Save vendor data
    /*-----------------------------------------------------------------------------------*/
    $('body').on('change', 'input[name="planning_invest"]', function () {
        let planning_invest = $('input[name="planning_invest"]:checked').val();
        console.log(planning_invest);
        if (planning_invest == 'no' || planning_invest == '') {
            $('span.show_hide_plng').hide();            
            $('div.show_hide_plng').hide();
        } else {
            $('span.show_hide_plng').show();            
            $('div.show_hide_plng').show();
        }
    });
    /*Begin: Get Each version charges  Ticket#289*/
    if (roles_arr.includes("adviser")) {
        jQuery('body').on('click', '.linked-accordian-title', function () {
            var current_ID = jQuery(this).attr('id'); //version_id
            var platform_id = jQuery(this).data('pid'); //platform_id
            // jQuery('.results-details-accordian').slideUp("slow");
            // jQuery('span.arrow-minus-plus').removeClass('active-arrow');
            //jQuery('.linked-version-loop-'+current_ID+platform_id+' .results-details-accordian').removeClass('active-accordian');
            var target_Class = jQuery(this).closest('.linked-version-main').find('div[accordian-id=' + current_ID + ']');
            //jQuery(this).closest('.linked-version-main').find('.results-details-accordian').hide();
            var statusHtml = '<div class="platform-loading" style="display: none;"><h3>Loading results</h3><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div><input type="hidden" name="version_' + current_ID + platform_id + '" value="true">';
            var is_true = jQuery('input[name="version_' + current_ID + platform_id + '"]').length;
            if (is_true > 0 && is_true == '1') {
                if (jQuery('.linked-version-loop-' + current_ID + platform_id + ' .results-details-accordian').hasClass('active-accordian')) {
                    jQuery('.linked-version-loop-' + current_ID + platform_id + ' .results-details-accordian').slideUp("slow");
                    jQuery('.linked-version-loop-' + current_ID + platform_id + ' .results-details-accordian').removeClass('active-accordian');
                } else {
                    jQuery('.linked-version-loop-' + current_ID + platform_id + ' .results-details-accordian').slideDown("slow");
                    jQuery('.linked-version-loop-' + current_ID + platform_id + ' .results-details-accordian').addClass('active-accordian');
                }
                jQuery('.linked-version-loop-' + current_ID + platform_id + ' .linked-accordian-title span').toggleClass('active-arrow');
                jQuery(this).animate({ scrollTop: 50 }, 1000);
                return false;
            }
            //ajax
            jQuery.ajax({
                type: "POST",
                url: get_cplat_vars.ajaxurl,
                data: {
                    action: "get_linked_version_charges_action",
                    version: current_ID,
                    platform_id: platform_id,
                    user_type: user_type
                },
                dataType: 'json',
                beforeSend: function () {
                    jQuery('.linked-version-loop-' + current_ID + platform_id + ' .results-details-accordian').slideDown("slow");
                    jQuery('.linked-version-loop-' + current_ID + platform_id + ' .results-details-accordian').toggleClass("active-accordian");
                    jQuery('.linked-version-loop-' + current_ID + platform_id + ' .platform-loading').show();
                    jQuery('.linked-version-loop-' + current_ID + platform_id + ' .linked-accordian-title span').toggleClass('active-arrow');
                    jQuery(this).animate({ scrollTop: 0 }, 1000);
                },
                success: function (result) {
                    var final_res = result.result_data + statusHtml;
                    jQuery('div[accordian-id=' + current_ID + platform_id + ']').html(final_res);
                    jQuery('.linked-version-loop-' + current_ID + platform_id + ' .platform-loading').hide();
                }
            });
            //end AJAX
        });
    }
    /*End: Ticket#289*/
    $('body').on('change', 'input[name="investments_today"]', function () {
        let investments_today = $('input[name="investments_today"]:checked').val();
        console.log(investments_today);
        if (investments_today == 'today' || investments_today == '') {
            $('.growth_rate_toggle').hide();
        } else {
            $('.growth_rate_toggle').show();
        }
    });
    jQuery(document).on('change', '[name="is_growth"]', function () {
        if (jQuery(this).val() == 'no') {
            jQuery("#growth-rate").prop("disabled", false);
        } else {
            jQuery("#growth-rate").val(4);
            jQuery("#growth-rate").prop("disabled", true);
        }
    });
    //RSPL #280
    jQuery(document).on('change', '[name="is_adviser_charges"]', function () {
        if (jQuery(this).val() == 'no') {
            jQuery('div#adviser-charges-main').hide();
        } else {
            jQuery('div#adviser-charges-main').show();
        }
        renumeringQuestions();
    })
    jQuery(document).on('change', '[name="link_portfolio"]', function () {
        var is_adviser_que = jQuery('input[name="is_adviser_charges"]:checked').val();
        if (jQuery(this).val() == 'no') {
            jQuery('.is_adviser_que').show();
            jQuery('.result-view-dis').show();
            if (is_adviser_que == 'yes') {
                jQuery('div#adviser-charges-main').show();
            }
        } else {
            jQuery('.result-view-dis').hide();
            jQuery('.is_adviser_que').hide();
            jQuery('div#adviser-charges-main').hide();
        }
        renumeringQuestions();
    })
    $(document).on('change', 'select', cplat_form_changed);
    $(document).on('change keypress', 'input', cplat_form_changed);
    $('.cplat-submit-form-container form input').on('change', function () {
        $("input[name='update']").val(1);
    });

    function cplat_form_changed() {
        $('.save-vendor-data').addClass('save-btn');
        $('.save-vendor-data').removeClass('saved-btn');
        $('.save-vendor-data span').text("Save");
    }

    //$(document).on('change', '.platform-data select', function (e) {
    jQuery(document).on('change', '.platform-data select[name*="[type]"] ', function (e) {
        var optionSelected = $("option:selected", this);
        var symb = $(this).parent().parent().find('.number-value .symbol');

        if (optionSelected.val() === 'ad_valorem' || optionSelected.val() === '1') {
            //if (optionSelected.val() === 'ad_valorem' || optionSelected.val() === '1' || optionSelected.val() === '4') { //Ticket#303
            $.each(symb, function () {
                $(this).text("%");
            });
        } else {
            $.each(symb, function () {
                $(this).text("£");
            });
        }
    });

    $('.cash_interest_rate_field').on('change keypress', function () {
        let interest_val = $(this).val();
        interest_val = parseFloat(interest_val.replace(/\D/g, '').replace(' ', ''));
        $(this).val(interest_val);
        if (!jQuery.isNumeric(interest_val)) {
            $(this).val();
            console.log('hello');
        } else {
            if (interest_val > 100) {
                $(this).val();
                console.log('hii');
            }
        }
    });

    $(document).on('click', '.save-vendor-data', save_vendor_data);

    function save_vendor_data(e) {
        e.preventDefault();
        var $this = $(this);

        $.ajax({
            beforeSend: function () {
                $this.addClass('saving-btn');
                $this.find('span').text("Saving...");
                $this.removeClass('save-btn');
            },
            complete: function () {
                $this.removeClass('saving-btn');
                $this.addClass('saved-btn');
                $this.find('span').text("Saved");
            },
            type: "POST",
            url: get_cplat_vars.ajaxurl,
            data: {
                action: "cplat_save_platform_data",
                data: $('#platform-data').serialize(),
                get_cplat_nonce: get_cplat_vars.get_cplat_nonce
            },
            dataType: 'json',
            success: function (result) {
                if (result.updated === true) {
                    $('#version_id').val(result.new_version_id);
                    $('#msg').html('');
                } else {
                    $('#msg').html('<b>' + result.message + '</b>');
                }
                //console.log(result);
            }
        });
    }


    $(document).on('click', 'div.results-button-container a.cplat-show-results', update_results);
    $(document).on('change', '#order_resluts_by', update_results);

    function update_results(e) {
        e.preventDefault();
        //RSPL Task#83
        jQuery('.results-email-pf').css('pointer-events', 'none');
        jQuery('.results-email-pf').css('cursor', 'not-allowed');
        jQuery('.results-print-pf').css('pointer-events', 'none');
        jQuery('.results-print-pf').css('cursor', 'not-allowed');
        jQuery('.cplat-show-results').css('pointer-events', 'none');
        jQuery('.cplat-show-results').css('cursor', 'not-allowed');
        var order_by = jQuery('#order_resluts_by').val();
        var version = jQuery('.platform-list-queue').data('version');
        var htb_data = jQuery('.platform-list-queue').data('serialized-inputs');
        $.ajax({
            beforeSend: function () {
                jQuery('.platform-loading-update').show();
            },
            complete: function () {
                jQuery('.platform-loading-update').hide();
                //RSPL Task#83
                jQuery('.results-email-pf').css('pointer-events', 'auto');
                jQuery('.results-email-pf').css('cursor', 'pointer');
                jQuery('.results-print-pf').css('pointer-events', 'auto');
                jQuery('.results-print-pf').css('cursor', 'pointer');
                jQuery('.cplat-show-results').css('pointer-events', 'auto');
                jQuery('.cplat-show-results').css('cursor', 'pointer');
            },
            type: "POST",
            url: get_cplat_vars.ajaxurl,
            data: {
                action: "cplat_update_results",
                get_cplat_nonce: get_cplat_vars.get_cplat_nonce,
                version: version,
                order_by: order_by,
                roles_commas: get_cplat_vars.current_user_roles,
                updated: 1,
                dual_response: 1, //RSPL Task#83
                htb_data: htb_data
            },
            dataType: 'html', //RSPL Task#237
            //dataType: 'json', //RSPL Task#83
            success: function (result) {
                //RSPL Task#83
                // jQuery('.platform-list-queue').html(result);
                // s_platform_result_response = result.popup_content;
                // Important code for printing start
                /*s_platform_result_response = "<div class='print-platform-results-container'>"+result.popup_content+"</div>";
                jQuery('.print-platform-results-container').remove();*/
                // Important code for printing End
                //jQuery('.platform-list-queue').html(result.listing_content); //RSPL Task#83
                jQuery('.platform-list-queue').html(result); //RSPL Task#237
                update_print_popup();
                // Important code for printing start
                /*jQuery('.post-content').append(s_platform_result_response);
                s_platform_result_response = jQuery('#custom-print-results-id').html();*/
                // Important code for printing end
                jQuery('.results-email-pf').css('pointer-events', 'auto');
                jQuery('.results-email-pf').css('cursor', 'pointer');
                /*jQuery('.results-print-pf').css('pointer-events','auto');
                jQuery('.results-print-pf').css('cursor','pointer');*/
                jQuery('.cplat-show-results').css('pointer-events', 'auto');
                jQuery('.cplat-show-results').css('cursor', 'pointer');
                // $(".platform-list-queue div.platform-result-item").slice(0, 6).show();
                // location.reload();
                //console.log(result);
            }
        });

    }

    // RSPL Task#162 start
    function update_print_popup() {
        var version = jQuery('.platform-list-queue').data('version');
        var order_by = jQuery('#order_resluts_by').val();
        $.ajax({
            type: "POST",
            url: get_cplat_vars.ajaxurl,
            data: {
                action: "cplat_update_results_for_print",
                get_cplat_nonce: get_cplat_vars.get_cplat_nonce,
                version: version,
                order_by: order_by,
                roles_commas: get_cplat_vars.current_user_roles,
                updated: 1
            },
            dataType: 'json',
            success: function (result) {
                // Important code for printing start
                s_platform_result_response = "<div class='print-platform-results-container'>" + result.popup_content + "</div>";
                jQuery('.print-platform-results-container').remove();
                jQuery('.post-content').append(s_platform_result_response);
                s_platform_result_response = jQuery('#custom-print-results-id').html();
                jQuery('.results-print-pf').css('pointer-events', 'auto');
                jQuery('.results-print-pf').css('cursor', 'pointer');
                // Important code for printing end
            }
        });
    }
    // RSPL Task#162 end


    function list_results(e) {
        var version = jQuery('.platform-list-queue').data('version');
        var update = jQuery("input[name='update']").val();
        var htb_data = jQuery('.platform-list-queue').data('serialized-inputs');
        $.ajax({
            beforeSend: function () {
                jQuery('.platform-loading').show();
            },
            complete: function () {
                jQuery('.platform-loading').hide();
            },
            type: "POST",
            url: get_cplat_vars.ajaxurl,
            data: {
                action: "cplat_get_results",
                get_cplat_nonce: get_cplat_vars.get_cplat_nonce,
                roles_commas: get_cplat_vars.current_user_roles,
                version: version,
                htb_data: htb_data,
                dual_response: 1 //RSPL Task#83
            },
            //dataType: 'html', //RSPL Task#237
            dataType: 'json', //RSPL Task#83
            beforeSend: function() {
                jQuery('.results-email-pf,a.results-print.results-print-pf').css('pointer-events', 'none');
                jQuery('.results-email-pf,a.results-print.results-print-pf').css('cursor', 'not-allowed');
            },
            success: function (result) {
                //RSPL Task#83
                // s_platform_result_response = result.popup_content;
                // Important code for printing start
                /*s_platform_result_response = "<div class='print-platform-results-container'>"+result.popup_content+"</div>";*/
                // Important code for printing end
                // jQuery('.platform-list-queue').html(result);
                //jQuery('.platform-list-queue').html(result.listing_content); //RSPL Task#83
                jQuery('.platform-list-queue').html(result.listing_content); //RSPL Task#237
                if( result.is_user_logged_in == 'true' ){
                    setup_print_popup();
                }
                jQuery('.results-email-pf,a.results-print.results-print-pf').css('pointer-events', 'auto');
                jQuery('.results-email-pf,a.results-print.results-print-pf').css('cursor', 'pointer');
                // Important code for printing start
                /*jQuery('.post-content').append(s_platform_result_response);
                s_platform_result_response = jQuery('#custom-print-results-id').html();*/
                // Important code for printing end
                /*jQuery('.results-print-pf').css('pointer-events','auto');
                jQuery('.results-print-pf').css('cursor','pointer');*/
                //$(".platform-list-queue div.platform-result-item").slice(0, 6).show();
                //console.log(result);
            }
        });
    }

    //RSPL Task#162
    function setup_print_popup() {
        var version = jQuery('.platform-list-queue').data('version');
        var update = jQuery("input[name='update']").val();
        var current_URL = window.location.href;
        $.ajax({
            type: "POST",
            url: get_cplat_vars.ajaxurl,
            data: {
                action: "cplat_get_results_for_print",
                get_cplat_nonce: get_cplat_vars.get_cplat_nonce,
                roles_commas: get_cplat_vars.current_user_roles,
                version: version,
                user_type: user_type,
                current_URL: current_URL
            },
            dataType: 'json',
            beforeSend: function() {
                jQuery('.results-email-pf,a.results-print.results-print-pf').css('pointer-events', 'none');
                jQuery('.results-email-pf,a.results-print.results-print-pf').css('cursor', 'not-allowed');
            },
            success: function (result) {
                if (result.is_login === false) {
                    window.location.href = result.login_url;
                }else{
                    s_platform_result_response = "<div class='print-platform-results-container'>" + result.popup_content + "</div>";
                    jQuery('.post-content').append(s_platform_result_response);
                    s_platform_result_response = jQuery('#custom-print-results-id').html();
                    jQuery('.results-email-pf,a.results-print.results-print-pf').css('pointer-events', 'auto');
                    jQuery('.results-email-pf,a.results-print.results-print-pf').css('cursor', 'pointer');
                }
            }
        });
    }

    //RSPL Task#83
    $(document).on('click', '.results-print-pf', function () {
        if( get_cplat_vars.is_user_logged_in == false ){
            setup_print_popup();
        }
        if( get_cplat_vars.is_user_logged_in == true ){
            printDiv('custom-print-results-id');
            console.log('in Print');
        }else{
            
        }
    });
    //RSPL Task#83
    function printDiv(divName) {
        var content = document.getElementById(divName).innerHTML;
        var mywindow = window.open('', 'Print', 'height=' + screen.height + ',width=' + screen.width);
        mywindow.document.write('<html><head><title>Platform Calculator - Results</title>');
        //RSPL Task#85 - Do not remove this code/style as it is added to adjust the content in print and print-preview
        mywindow.document.write('<style>html, body { page-break-after: avoid; page-break-before: avoid; }table.invoice-items { page-break-inside:auto } .invoice-items tr { page-break-inside:avoid; page-break-after:auto } .invoice-items thead { display:table-header-group } .invoice-items tfoot { display:table-footer-group }table.invoice-items-main { page-break-inside:auto } .invoice-items-main tr { page-break-inside:avoid; page-break-after:auto } .invoice-items-main thead { display:table-header-group } .invoice-items-main tfoot { display:table-footer-group }</style></head><body>');
        mywindow.document.write(content);
        //mywindow.document.write(s_platform_result_response);
        mywindow.document.write('</body></html>');
        mywindow.document.close();
        mywindow.focus();
        mywindow.print();
        //mywindow.close();
        return true;
    }
    if (jQuery('.platform-list-queue').length) {
        list_results();
    }

    //RSPL Task#25
    $(document).on('click', '.portfolio_redirection_cls', function () {
        var user_type = get_cplat_vars.user_type;
        //Select Adviser as an investment type
        $('#inv_management_type_advisor').click();
        if (get_cplat_vars.allowed_linked_portfolios == 1) {
            $('.portfolio_redirection_cls').addClass('portfolio_no_redirection_cls').removeClass('portfolio_redirection_cls');
            $('.portfolio_no_redirection_cls').html('<i class="fa fa-circle-o-notch fa-spin-animate"></i>');
            var i_age = $('#age').val();
            i_age = parseFloat(i_age.replace(/\D/g, '').replace(' ', ''));
            if (i_age <= 0 || isNaN(i_age)) {
                alert("Whoops, you've missed to share your age!");
                $('.portfolio_no_redirection_cls').addClass('portfolio_redirection_cls').removeClass('portfolio_no_redirection_cls');
                $('.portfolio_redirection_cls').html('+');
                jQuery('input#link_portfolio_no').trigger('click');
            } else if (i_age < 18) {
                alert("Whoops, Age should be at least 18 years!");
                $('.portfolio_no_redirection_cls').addClass('portfolio_redirection_cls').removeClass('portfolio_no_redirection_cls');
                $('.portfolio_redirection_cls').html('+');
                jQuery('input#link_portfolio_no').trigger('click');
            } else {
                var post_url;
                if( user_type == 'advisor' ){
                    post_url = '/platform-calculator/?portfolio_setup=1';
                }else{
                    post_url = '/platform-calculator-consumer/?portfolio_setup=1';
                }
                var main_version = '';
                var version = '';
                if ($('.main_version_cls').val() > 0) {
                    main_version = $('.main_version_cls').val();
                    version = get_cplat_vars.current_version;
                    // post_url = '/platform-calculator/?portfolio_setup=1&step=2&main_version=' + main_version + '&old_version='+version;

                } else {
                    main_version = get_cplat_vars.main_version;
                    version = main_version;
                    $('.main_version_cls').val(main_version);
                    // post_url = '/platform-calculator/?portfolio_setup=1&step=2&main_version=' + main_version;
                }
                //post_url = '/platform-calculator/?portfolio_setup=1&version=' + version + '&main_version=' + main_version;
                if( user_type == 'advisor' ){
                    post_url = '/platform-calculator/?portfolio_setup=1&version=' + version + '&main_version=' + main_version;
                }else{
                    post_url = '/platform-calculator-consumer/?portfolio_setup=1&version=' + version + '&main_version=' + main_version;
                }
                console.log(post_url);
                //var user_type = $('#user_type').val();
                $.ajax({
                    type: "POST",
                    url: get_cplat_vars.ajaxurl,
                    data: {
                        action: "cplat_store_linked_portfolios",
                        version: version,
                        main_version: main_version,
                        user_type: user_type
                    },
                    dataType: 'json',
                    success:
                        function (data) {
                            // console.log(data);
                            // console.log(data.status);
                            if (data.status == 1) {
                                $('form.stepsform3_container').attr('action', get_cplat_vars.home_url + post_url).submit();
                            } else {
                                alert(data.msg);
                            }
                        },
                    error:
                        function (data) {
                            $('.portfolio_no_redirection_cls').addClass('portfolio_redirection_cls').removeClass('portfolio_no_redirection_cls');
                            $('.portfolio_redirection_cls').html('+');
                        },
                    complete:
                        function (data) {
                            // console.log(data);
                            // console.log(data.status);
                            //$('form.stepsform3_container').attr('action', get_cplat_vars.home_url + post_url).submit();
                        }
                });
            }
        } else if (get_cplat_vars.allowed_linked_portfolios == 2) {
            alert('Whoops, you cannot link anymore portfolio. You have already linked 9 portfolios!');
        }
    });

    // RSPL Task#25
    $("input[name='inv_management_type']").on('change', function () {
        if ($("#inv_management_type_myself").is(':checked')) {
            //alert('myself');
            $('.linked_portfolio_row').hide();
        } else if ($("#inv_management_type_advisor").is(':checked')) {
            // alert('advisor');
            $('.linked_portfolio_row').show();
        }
    });

    function load_more_updated_data_platforms(e) {
        e.preventDefault();

        var version = jQuery('.platform-list-queue').data('version');
        var total_funds = jQuery('#total-funds').val();
        var total_stocks = jQuery('#total-stocks').val();
        var frequency = jQuery('#trading-frequency').val();
        var point_in_time = jQuery('#point-in-time').val();
        var total_all = jQuery('#total-all').val();

        var investments_today = jQuery('input[name=investments_today]:checked').val();
        var investments_over = jQuery('#investments-over').val();
        var investments_in_x_years = jQuery('#investments-in-x-years').val();
        var total_fund = document.getElementById('total-funds').value;
        var total_ex_traded = document.getElementById('total-stocks').value;
        var total_investments = document.getElementById('total-all').value;
        var yearly_trades_funds = document.getElementById('trading-freq-funds').value;
        var yearly_trades_ex = document.getElementById('trading-freq-ex').value;
        var avg_trade_funds = document.getElementById('avg-trade-funds').value;
        var avg_trade_ex = document.getElementById('avg-trade-ex').value;
        var results = '';
        if (document.getElementById('investments_today_today').checked) {
            results = document.getElementById('investments_today_today').value;
        }
        if (document.getElementById('investments_today_over_years').checked) {
            results = document.getElementById('investments_today_over_years').value;
        }
        if (document.getElementById('investments_today_in_x_years').checked) {
            results = document.getElementById('investments_today_in_x_years').value;
        }
        var over_years = document.getElementById('investments-over').value;
        var point_future = document.getElementById('investments-in-x-years').value;
        //document.getElementById('order_by').value = document.step4.order_results_by.value;
        var updated = 1;


        var order_by = jQuery('#order_resluts_by').val();

        $.ajax({
            beforeSend: function () {
                jQuery('.platform-loading').show();
            },
            complete: function () {
                jQuery('.platform-loading').hide();
            },
            type: "POST",
            url: get_cplat_vars.ajaxurl,
            data: {
                action: "cplat_update_results",
                get_cplat_nonce: get_cplat_vars.get_cplat_nonce,
                version: version,
                total_funds: total_funds,
                total_shares: total_stocks,
                total_all: total_all,
                frequency: frequency,
                investments_today: investments_today,
                investments_over: investments_over,
                investments_in_x_years: investments_in_x_years,
                offset: offset,
                order_by: order_by,
                over_years: over_years,
                yearly_trades_funds: yearly_trades_funds,
                yearly_trades_ex: yearly_trades_ex,
                total_investments: total_investments,
                results: results,
                avg_trade_funds: avg_trade_funds,
                avg_trade_ex: avg_trade_ex,
                point_in_time: point_in_time,
                point_future: point_future
            },
            success: function (result) {

                jQuery('.platform-list-queue').html(result);

            }
        });
    }

    $(document).ready(function () {

        //RSPL Task#83
        $('.results-email-pf').on('click', function (e) {
            e.preventDefault();
            jQuery('.results-email-pf').css('pointer-events', 'none');
            jQuery('.results-email-pf').css('cursor', 'not-allowed');
            var version = jQuery('.platform-list-queue').data('version');
            var current_URL = window.location.href;
            var calculator_type = jQuery('input[name="calculator_type"]').val();
            calculator_type = ( calculator_type ? calculator_type : 'regular' );
            $.ajax({
                type: "POST",
                url: get_cplat_vars.ajaxurl,
                data: {
                    action: "cplat_email_result_ajax",
                    //version_name: version_name,
                    version: version,
                    get_cplat_nonce: get_cplat_vars.get_cplat_nonce,
                    user_type: user_type,
                    current_URL: current_URL,
                    calculator_type : calculator_type
                },
                dataType: 'json',
                success:
                    function (result) {
                        if (result !== null && result.msg == 'ok') {
                            // $('#msg').html('Your results are saved')
                            $('.platform-save-result-msg').html('<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"><span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span> ' + result.email_msg + '</div>');
                        }else if (result.is_login === false) {
                            window.location.href = result.login_url;
                        }else {
                            $('.platform-save-result-msg').html('<div class="fusion-alert alert danger alert-dismissable alert-danger alert-shadow"><span class="alert-icon"><i class="fa-lg  fa fa-exclamation-triangle"></i></span> ' + result.email_msg + '</div>');
                        }
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    },
                complete:
                    function (result) {
                        jQuery('.results-email-pf').css('pointer-events', 'auto');
                        jQuery('.results-email-pf').css('cursor', 'pointer');
                    }
            })
                ;
        });

        //RSPL Task#83
        $('.results-save-pf').on('click', function (e) {
            e.preventDefault();
            //Current Save Results button should be disabled
            $(this).attr('disabled', 'disabled');
            let version_name = $('#platform-version-name').val().trim();
            if (version_name === undefined || version_name === "") {
                alert("Please enter a unique version name");
                $('.results-save-pf').removeAttr('disabled', 'disabled');
                return false;
            }
            var version = jQuery('.platform-list-queue').data('version');
            var current_URL = window.location.href;
            var calculator_type = jQuery('input[name="calculator_type"]').val();
            calculator_type = ( calculator_type ? calculator_type : 'regular' );
            $.ajax({
                type: "POST",
                url: get_cplat_vars.ajaxurl,
                data: {
                    action: "save_search_results_ajax",
                    version_name: version_name,
                    version: version,
                    get_cplat_nonce: get_cplat_vars.get_cplat_nonce,
                    user_type: user_type,
                    current_URL: current_URL,
                    calculator_type : calculator_type
                },
                dataType: 'json',
                success:
                    function (result) {
                        console.log(result);
                        if (result !== null && result.msg == 'ok') {
                            // $('#msg').html('Your results are saved')
                            $('.platform-save-result-msg').html('<div class="fusion-alert alert success alert-dismissable alert-success alert-shadow"> <span class="alert-icon"><i class="fa fa-lg fa-check-circle"></i></span> Your results have been saved!</div>');
                        } else if (result.is_login === false) {
                            window.location.href = result.login_url;
                        } else {
                            $('.platform-save-result-msg').html('<div class="fusion-alert alert danger alert-dismissable alert-danger alert-shadow"> <span class="alert-icon"><i class="fa-lg  fa fa-exclamation-triangle"></i></span> Error occured while storing your results!</div>');
                        }
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    },
                complete:
                    function () {
                        //Current Save Results button should be enable again
                        $('.results-save-pf').removeAttr('disabled', 'disabled');
                    }
            })
                ;
        });

        $(".ctp-nav").keypress(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                let textboxes = $(".cplat-submit-form-container input.ctp-nav:not(:disabled)");
                let currentBoxNumber = textboxes.index(this);
                let focusinput = textboxes[currentBoxNumber + 1];

                if (focusinput != null) {
                    focusinput.focus();
                    focusinput.select();
                    event.preventDefault();
                    return false
                }
            }
        });
        $("input[name='investment_products']").on('change', function (e) {
            if ($(this).val() === 'yes') {
                show1();
            }
            if ($(this).val() === 'no') {
                hide1();
            }
        });
        $("input[name='investment_stocks_shares']").on('change', function (e) {
            if ($(this).val() === 'yes') {
                show2();
            }
            if ($(this).val() === 'no') {
                hide2();
            }
        });
        $("input[name='planning_invest']").on('change', function (e) {
            if ($(this).val() === 'yes') {
                show_planning_div();
            }
            if ($(this).val() === 'no') {
                hide_planning_div();
            }
        });
        $("input[name='planning_stocks_shares']").on('change', function (e) {
            if ($(this).val() === 'yes') {
                show_planning_ex_div();
            }
            if ($(this).val() === 'no') {
                hide_plannining_ex_div();
            }
        });

        $('.result-product-col').on('load',function () {
            let inv_today = $('input[name="investments_today"]').val();
            if (inv_today === 'over_years') {
                $('.result-product-col').css('width', '66');
            } else {
                $('.result-product-col').css('width', '99');
            }
        });
        checkInvestments();
        checkplanning();

        function show1() {
            document.querySelector('div#investment-products').style.opacity = '1';
            if(document.querySelector('div.q4')) {
                document.querySelector('div.q4').style.opacity = '1';
            }
            // HTB310523 - Calculator RESKIN
            document.querySelector('div#investment-products').classList.remove('hide');
            if(document.querySelector('div.q4')) { document.querySelector('div.q4').closest('div.calculator-form__question').classList.remove('hide'); }
            // HTB310523 - /Calculator RESKIN
            if(document.getElementById('funds_isa')) {
              document.getElementById('funds_isa').disabled = false;
            }
            if(document.getElementById('junior-isa')) {
              document.getElementById('junior-isa').disabled = false;
            }
            if(document.getElementById('funds_sipp')) {
              document.getElementById('funds_sipp').disabled = false;
            }
            if(document.getElementById('junior-sipp')) {
              document.getElementById('junior-sipp').disabled = false;
            }
            if(document.getElementById('general-investments')) {
              document.getElementById('general-investments').disabled = false;
            }
            if(document.getElementById('funds_onshore_bond')) {
              document.getElementById('funds_onshore_bond').disabled = false;
            }
            if(document.getElementById('funds_offshore_bond')) {
              document.getElementById('funds_offshore_bond').disabled = false;
            }
            if(document.getElementById('funds_isa_cash')) {
              document.getElementById('funds_isa_cash').disabled = false;
            }
            if(document.getElementById('junior-isa-cash')) {
              document.getElementById('junior-isa-cash').disabled = false;
            }
            if(document.getElementById('funds_sipp_cash')) {
              document.getElementById('funds_sipp_cash').disabled = false;
            }
            if(document.getElementById('junior-sipp_cash')) {
              document.getElementById('junior-sipp_cash').disabled = false;
            }
            if(document.getElementById('general-investments_cash')) {
              document.getElementById('general-investments_cash').disabled = false;
            }
            if(document.getElementById('funds_onshore_bond_cash')) {
              document.getElementById('funds_onshore_bond_cash').disabled = false;
            }
            if(document.getElementById('funds_offshore_bond_cash')) {
              document.getElementById('funds_offshore_bond_cash').disabled = false;
            }
            checkShares();
        }

        function hide1() {
            if(document.getElementById('investment_stocks_shares_no')) { document.getElementById('investment_stocks_shares_no').checked = true; }
            if(document.querySelector('div#investment-products')) { document.querySelector('div#investment-products').style.opacity = '0.5'; }
            if(document.querySelector('div.q4')) {
                document.querySelector('div.q4').style.opacity = '0.5';
            }
            // HTB310523 - Calculator RESKIN
            if(document.querySelector('div#investment-products')) { document.querySelector('div#investment-products').classList.add('hide'); }
            if(document.querySelector('div.q4')) {
                document.querySelector('div.q4').closest('div.calculator-form__question').classList.add('hide');
            }
            // HTB310523 - /Calculator RESKIN
            if(document.getElementById('funds_isa')) {
              document.getElementById('funds_isa').disabled = true;
            }
            if(document.getElementById('junior-isa')) {
              document.getElementById('junior-isa').disabled = true;
            }
            if(document.getElementById('funds_sipp')) {
              document.getElementById('funds_sipp').disabled = true;
            }
            if(document.getElementById('junior-sipp')) {
              document.getElementById('junior-sipp').disabled = true;
            }
            if(document.getElementById('general-investments')) {
              document.getElementById('general-investments').disabled = true;
            }
            if(document.getElementById('funds_onshore_bond')) {
              document.getElementById('funds_onshore_bond').disabled = true;
            }
            if(document.getElementById('funds_offshore_bond')) {
              document.getElementById('funds_offshore_bond').disabled = true;
            }
            if(document.getElementById('funds_isa_cash')) {
              document.getElementById('funds_isa_cash').disabled = true;
            }
            if(document.getElementById('junior-isa-cash')) {
              document.getElementById('junior-isa-cash').disabled = true;
            }
            if(document.getElementById('funds_sipp_cash')) {
              document.getElementById('funds_sipp_cash').disabled = true;
            }
            if(document.getElementById('junior-sipp_cash')) {
              document.getElementById('junior-sipp_cash').disabled = true;
            }
            if(document.getElementById('general-investments_cash')) {
              document.getElementById('general-investments_cash').disabled = true;
            }
            if(document.getElementById('funds_onshore_bond_cash')) {
              document.getElementById('funds_onshore_bond_cash').disabled = true;
            }
            if(document.getElementById('funds_offshore_bond_cash')) {
              document.getElementById('funds_offshore_bond_cash').disabled = true;
            }
            hide2();
        }

        function show2() {
            document.querySelector('div#investment-shares').style.opacity = '1';
            // HTB310523 - Calculator RESKIN
            document.querySelector('div#investment-shares').classList.remove('hide');
            // HTB310523 - /Calculator RESKIN
            if(document.getElementById('ex_instruments_isa')) {
              document.getElementById('ex_instruments_isa').disabled = false;
            }
            if(document.getElementById('ex_instruments_jisa')) {
              document.getElementById('ex_instruments_jisa').disabled = false;
            }
            if(document.getElementById('ex_instruments_sipp')) {
              document.getElementById('ex_instruments_sipp').disabled = false;
            }
            if(document.getElementById('ex_instruments_jsipp')) {
              document.getElementById('ex_instruments_jsipp').disabled = false;
            }
            if(document.getElementById('ex_instruments_gia')) {
              document.getElementById('ex_instruments_gia').disabled = false;
            }
        }

        function hide2() {
            if(document.querySelector('div#investment-shares')) { document.querySelector('div#investment-shares').style.opacity = '0.5'; }
            // HTB310523 - Calculator RESKIN
            if(document.querySelector('div#investment-shares')) { document.querySelector('div#investment-shares').classList.add('hide'); }
            // HTB310523 - /Calculator RESKIN
            if(document.getElementById('ex_instruments_isa')) {
              document.getElementById('ex_instruments_isa').disabled = true;
            }
            if(document.getElementById('ex_instruments_jisa')) {
              document.getElementById('ex_instruments_jisa').disabled = true;
            }
            if(document.getElementById('ex_instruments_sipp')) {
              document.getElementById('ex_instruments_sipp').disabled = true;
            }
            if(document.getElementById('ex_instruments_jsipp')) {
              document.getElementById('ex_instruments_jsipp').disabled = true;
            }
            if(document.getElementById('ex_instruments_gia')) {
              document.getElementById('ex_instruments_gia').disabled = true;
            }
        }

        function show_planning_div() {
            if(document.querySelector('div#planning-invest')) { document.querySelector('div#planning-invest').style.opacity = '1'; }
            if(document.querySelector('div.q7')) { document.querySelector('div.q7').style.opacity = '1'; }
            // HTB310523 - Calculator RESKIN
            if(document.querySelector('div#planning-invest')) { document.querySelector('div#planning-invest').classList.remove('hide'); }
            if(document.querySelector('div.q7')) { document.querySelector('div.q7').closest('div.calculator-form__question').classList.remove('hide'); }
            // HTB310523 - /Calculator RESKIN
            if(document.getElementById('planning_isa')) {
              document.getElementById('planning_isa').disabled = false;
            }
            if(document.getElementById('planning-junior-isa')) {
              document.getElementById('planning-junior-isa').disabled = false;
            }
            if(document.getElementById('planning-sipp')) {
              document.getElementById('planning-sipp').disabled = false;
            }
            if(document.getElementById('planning-junior-sipp')) {
            document.getElementById('planning-junior-sipp').disabled = false;
          }
            if(document.getElementById('planning-general-investments')) {
            document.getElementById('planning-general-investments').disabled = false;
          }
            checkExPlanning();
        }

        function hide_planning_div() {
            if(document.querySelector('div#planning-invest')) { document.querySelector('div#planning-invest').style.opacity = '0.5'; }
            if(document.querySelector('div.q7')) { document.querySelector('div.q7').style.opacity = '0.5'; }
            // HTB310523 - Calculator RESKIN
            if(document.querySelector('div#planning-invest')) { document.querySelector('div#planning-invest').classList.add('hide'); }
            if(document.querySelector('div.q7')) { document.querySelector('div.q7').closest('div.calculator-form__question').classList.add('hide'); }
            // HTB310523 - /Calculator RESKIN
            if(document.getElementById('planning_isa')) {
            document.getElementById('planning_isa').disabled = true;
          }
            if(document.getElementById('planning-junior-isa')) {
            document.getElementById('planning-junior-isa').disabled = true;
          }
            if(document.getElementById('planning-sipp')) {
            document.getElementById('planning-sipp').disabled = true;
          }
            if(document.getElementById('planning-junior-sipp')) {
            document.getElementById('planning-junior-sipp').disabled = true;
          }
            if(document.getElementById('planning-general-investments')) {
            document.getElementById('planning-general-investments').disabled = true;
          }
            hide_plannining_ex_div();
        }

        function show_planning_ex_div() {
            document.querySelector('div#planning-stocks-shares').style.opacity = '1';
            // HTB310523 - Calculator RESKIN
            document.querySelector('div#planning-stocks-shares').classList.remove('hide');
            // HTB310523 - /Calculator RESKIN
            if(document.getElementById('planning_ex_instruments_isa')) {
            document.getElementById('planning_ex_instruments_isa').disabled = false;
          }
            if(document.getElementById('planning_ex_instruments-junior-isa')) {
            document.getElementById('planning_ex_instruments-junior-isa').disabled = false;
          }
            if(document.getElementById('planning_ex_instruments-sipp')) {
            document.getElementById('planning_ex_instruments-sipp').disabled = false;
          }
            if(document.getElementById('planning_ex_instruments-junior-sipp')) {
            document.getElementById('planning_ex_instruments-junior-sipp').disabled = false;
          }
            if(document.getElementById('planning-ex-instruments-general-investments')) {
            document.getElementById('planning-ex-instruments-general-investments').disabled = false;
          }
        }

        function hide_plannining_ex_div() {
            document.querySelector('div#planning-stocks-shares').style.opacity = '0.5';
            // HTB310523 - Calculator RESKIN
            document.querySelector('div#planning-stocks-shares').classList.add('hide');
            // HTB310523 - /Calculator RESKIN
            if(document.getElementById('planning_ex_instruments_isa')) {
            document.getElementById('planning_ex_instruments_isa').disabled = true;
          }
            if(document.getElementById('planning_ex_instruments-junior-isa')) {
            document.getElementById('planning_ex_instruments-junior-isa').disabled = true;
          }
            if(document.getElementById('planning_ex_instruments-sipp')) {
            document.getElementById('planning_ex_instruments-sipp').disabled = true;
          }
            if(document.getElementById('planning_ex_instruments-junior-sipp')) {
            document.getElementById('planning_ex_instruments-junior-sipp').disabled = true;
          }
            if(document.getElementById('planning-ex-instruments-general-investments')) {
            document.getElementById('planning-ex-instruments-general-investments').disabled = true;
          }
        }


        function checkInvestments() {
            if ($('#investment_products_no').length) {
                if ($('#investment_products_no').prop('checked')) {
                    hide1();
                } else {
                    show1();
                    checkShares();
                }
            } else {
                // RSPL Task#175
                checkShares();
            }
        }

        function checkShares() {
            //alert(document.getElementById('investment_products_no').checked);
            if ($('#investment_stocks_shares_no').length) {
                if ($('#investment_stocks_shares_no').prop('checked')) {
                    hide2();
                } else {
                    show2();
                }
            }
        }

        function checkplanning() {
            if ($('#planning_invest_no').length) {
                if ($('#planning_invest_no').prop('checked')) {
                    hide_planning_div();
                } else {
                    show_planning_div();
                    checkExPlanning();
                }
            }
        }

        function checkExPlanning() {
            if ($('#planning_stocks_shares_no').length) {
                //alert(document.getElementById('investment_products_no').checked);
                if ($('#planning_stocks_shares_no').prop('checked')) {
                    hide_plannining_ex_div();
                } else {
                    show_planning_ex_div();
                }
            }

        }
        if ($('.results-button-container select').length) {
            $('.results-button-container select').selectric();
        }
    });


    $("#update-button").on('click',

        function (e) {
            e.preventDefault();
            //alert(document.getElementById('investments-over').value);
            document.getElementById('total-fund').value = document.getElementById('total-funds').value;
            document.getElementById('total-ex-traded').value = document.getElementById('total-stocks').value;
            document.getElementById('total-investments').value = document.getElementById('total-all').value;
            document.getElementById('yearly_trades_funds').value = document.getElementById('trading-freq-funds').value;
            document.getElementById('yearly_trades_ex').value = document.getElementById('trading-freq-ex').value;
            document.getElementById('avg_trade_funds').value = document.getElementById('avg-trade-funds').value;
            document.getElementById('avg_trade_ex').value = document.getElementById('avg-trade-ex').value;
            if (document.getElementById('investments_today_today').prop('checked')) {
                document.getElementById('results').value = document.getElementById('investments_today_today').value;
            }
            if (document.getElementById('investments_today_over_years').prop('checked')) {
                document.getElementById('results').value = document.getElementById('investments_today_over_years').value;
            }
            if (document.getElementById('investments_today_in_x_years').prop('checked')) {
                document.getElementById('results').value = document.getElementById('investments_today_in_x_years').value;
            }
            document.getElementById('over_years').value = document.getElementById('investments-over').value;
            document.getElementById('point_future').value = document.getElementById('investments-in-x-years').value;
            //document.getElementById('order_by').value = document.step4.order_results_by.value;
            document.getElementById('updated-s').value = 1;

            //document.forms.hiddenform.submit;
            //alert(document.getElementById('total-ex-traded').value);
            // return true;
        });
    jQuery("input[name='investments_today']").on('change', function (e) {
        renumeringQuestions();
    });
    jQuery(document).on('click', '.check-for-age', function (e) {
        if (jQuery('#age').length > 0) {
            var age = Number(jQuery('#age').val());
            if (age === "" || age === 0) {
                jQuery('.age-row .col-lg-6').addClass('error');
                alert("Whoops, you’ve missed this question!");
                jQuery('input#link_portfolio_no').trigger('click');
                return false;
            }
            if (age < 18) {
                jQuery('.age-row .col-lg-6').addClass('error');
                alert("Whoops, Age should be at least 18 years!");
                jQuery('input#link_portfolio_no').trigger('click');
                return false;
            }
        }
    });


})(jQuery);


/*Ticket#218 Start */
//let role = get_cplat_vars.current_user_roles;
// if( role == 'subscriber' ){
//Reorder Question numbering function 
function renumeringQuestions() {
    console.log(jQuery('[name="investment_products_in_3"]').val());
    var is_investment_product = jQuery('[name="investment_products_in_3"]').val();
    var total_in_que = 4;
    if (is_investment_product == 'no') {
        total_in_que = 2;
    }
    if (roles_arr.includes("adviser") || user_type == 'advisor') {
        total_in_que = 3;
    }
    console.log('total_in_que : ' + total_in_que);
    jQuery("[class*='question-label-']:visible").each(function (index) {
        jQuery(this).attr('id', '');
        jQuery(this).attr('id', 'numbering-' + parseInt(index + 1 + total_in_que));
    });
}

function calci_trigger_events(){
    var current_URL = window.location.href;
    var steps_URL = new URL(current_URL);
    var getStep = steps_URL.searchParams.get("step");
    var save_result_arg = steps_URL.searchParams.get("save_result");
    var save_result_ecc = steps_URL.searchParams.get("save_result_ecc");
    var send_email_result = steps_URL.searchParams.get("send_email_result");
    var send_print_ecc_result = steps_URL.searchParams.get("send_print_ecc_result");
    var send_email_ecc = steps_URL.searchParams.get("send_email_ecc");
    if (getStep == 3) {
        renumeringQuestions();
    }
    if (save_result_arg == 'true') {
        jQuery('.results-save-pf').trigger('click');
    }
    if (save_result_ecc == 'true') {
        jQuery('form#ecc-save-search input#ecc-result-save').trigger('click');
    }
    if( send_email_result == 'true' ){
        jQuery('a.results-email.results-email-pf').trigger('click');
    }
    if( send_email_ecc == 'true' ){
        jQuery('form#ecc-save-search a.results-email').trigger('click');
    }
    if( send_print_ecc_result == 'true' ){
        jQuery('form#ecc-save-search a.results-print').trigger('click');
    }
}
// }
jQuery(window).on('load',function () {
    var is_growth_val = jQuery('[name="is_growth"]:checked').val();
    if (is_growth_val == 'yes') {
        jQuery("#growth-rate").val(4);
    }
    /*Ticket#218*/
    let role = get_cplat_vars.current_user_roles;
    // if( role == 'subscriber' ){
    var is_investment_shares = jQuery('[name="investment_stocks_shares"]:checked').val();
    if (is_investment_shares == 'yes') {
        jQuery("div#investment-shares").show();
    } else {
        jQuery("div#investment-shares").hide();
    }

    var is_planning_invest = jQuery('[name="planning_invest"]:checked').val();
    if (is_planning_invest == 'yes') {
        jQuery('div#planning-stocks-shares').show();
        jQuery('.row.q7').show();
    } else {
        jQuery('div#planning-invest').hide();
        jQuery('div#planning-stocks-shares').hide();
        jQuery('.row.q7').hide();
    }

    var is_planning_stocks_shares = jQuery('[name="planning_stocks_shares"]:checked').val();
    if (is_planning_stocks_shares == 'yes') {
        jQuery('div#planning-stocks-shares').show();
    } else {
        jQuery('div#planning-stocks-shares').hide();
    }

    jQuery("input[name='investment_products']").on('change', function (e) {
        if (jQuery(this).val() === 'yes') {
            jQuery("div#investment-products, div#investment-shares, .row.q4").show();
        }
        if (jQuery(this).val() === 'no') {
            jQuery("div#investment-products, div#investment-shares, .row.q4").hide();
        }
        jQuery("input[name='investment_stocks_shares']").trigger('change');
    });
    jQuery("input[name='investment_stocks_shares']").on('change', function (e) {
        if (jQuery(this).val() === 'yes') {
            jQuery("div#investment-shares").show();
        }
        if (jQuery(this).val() === 'no') {
            jQuery("div#investment-shares").hide();
        }
    });
    //Yearly planning for inverstment
    jQuery("input[name='planning_invest']").on('change', function (e) {
        if (jQuery(this).val() === 'yes') {
            jQuery('div#planning-invest').show();
            jQuery('.row.q7').show();
        }
        if (jQuery(this).val() === 'no') {
            jQuery('div#planning-invest').hide();
            jQuery('.row.q7').hide();
        }
        jQuery("#planning_stocks_shares_no").trigger('click');
        renumeringQuestions();
    });
    jQuery("input[name='planning_stocks_shares']").on('change', function (e) {
        if (jQuery(this).val() === 'yes') {
            jQuery('div#planning-stocks-shares').show();
        }

        jQuery("input[name='investment_products']").on('change', function (e) {
            if (jQuery(this).val() === 'yes') {
                jQuery("div#investment-products, div#investment-shares, .row.q4").show();
            }
            if (jQuery(this).val() === 'no') {
                jQuery("div#investment-products, div#investment-shares, .row.q4").hide();
            }
            jQuery("input[name='investment_stocks_shares']").trigger('change');
        });
        jQuery("input[name='investment_stocks_shares']").on('change', function (e) {
            if (jQuery(this).val() === 'yes') {
                jQuery("div#investment-shares").show();
            }
            if (jQuery(this).val() === 'no') {
                jQuery("div#investment-shares").hide();
            }
        });
        //Yearly planning for inverstment
        jQuery("input[name='planning_invest']").on('change', function (e) {
            if (jQuery(this).val() === 'yes') {
                jQuery('div#planning-invest').show();
                jQuery('.row.q7').show();
            }
            if (jQuery(this).val() === 'no') {
                jQuery('div#planning-invest').hide();
                jQuery('.row.q7').hide();
            }
            jQuery("#planning_stocks_shares_no").trigger('click');
            renumeringQuestions();
        });
        jQuery("input[name='planning_stocks_shares']").on('change', function (e) {
            if (jQuery(this).val() === 'yes') {
                jQuery('div#planning-stocks-shares').show();
            }
            if (jQuery(this).val() === 'no') {
                jQuery('div#planning-stocks-shares').hide();
            }
        });
        jQuery("input[name='investments_today']").on('change', function (e) {
            renumeringQuestions();
        });
        calci_trigger_events();
    });
});
jQuery(function(){
    calci_trigger_events();
});
// jQuery(document).on('click','body.platform-vendor-account #result-accordion a.collapsed',function(){
//     var thisID = jQuery(this).data('target');
//     jQuery(thisID).toggle();
// });
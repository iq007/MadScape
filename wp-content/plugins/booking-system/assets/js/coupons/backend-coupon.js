
/*
* Title                   : Pinpoint Booking System WordPress Plugin
* Version                 : 2.1.2
* File                    : assets/js/coupons/backend-coupon.js
* File Version            : 1.0.6
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end coupon JavaScript class.
*/


var DOPBSPBackEndCoupon = new function(){
    'use strict';
    
    /*
     * Private variables.
     */
    var $ = jQuery.noConflict();

    /*
     * Public variables
     */
    this.ajaxRequestInProgress;
    this.ajaxRequestTimeout;
    
    /*
     * Constructor
     */
    this.__construct = function(){
    };
    
    /*
     * Display coupon.
     * 
     * @param language (String): coupon current editing language
     * @param clearCoupon (Boolean): clear coupon extra data diplay
     */
    this.display = function(language,
                            clearCoupon){
        var HTML = new Array();
        
        language = language === undefined ? ($('#DOPBSP-coupon-language').val() === undefined ? '':$('#DOPBSP-coupon-language').val()):language;
        clearCoupon = clearCoupon === undefined ? true:false;
        language = clearCoupon ? '':language;
        
        if (clearCoupon){
            DOPBSPBackEnd.clearColumns(2);
        }
        DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('MESSAGES_LOADING'));
        
        $('#DOPBSP-column1 .dopbsp-column-content li').removeClass('dopbsp-selected');
        $('#DOPBSP-coupon-ID-1').addClass('dopbsp-selected');
        $('#DOPBSP-coupon-ID').val(1);
        
        $.post(ajaxurl, {action: 'dopbsp_coupon_display', 
                         language: language}, function(data){
            
            $('#DOPBSP-column2 .dopbsp-column-header').html(HTML.join(''));
            $('#DOPBSP-column2 .dopbsp-column-content').html(data);
            
            $('#DOPBSP-coupon-start_date').datepicker();
            $('#DOPBSP-coupon-end_date').datepicker();
            
            DOPBSPBackEndCoupon.init();
            DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('COUPONS_COUPON_LOADED'));
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };
    
    /*
     * Initialize events and validations.
     */
    this.init = function(){
        /*
         * Price validation.
         */
        var dayNames = [DOPBSPBackEnd.text('DAY_SUNDAY'),
                        DOPBSPBackEnd.text('DAY_MONDAY'),
                        DOPBSPBackEnd.text('DAY_TUESDAY'),
                        DOPBSPBackEnd.text('DAY_WEDNESDAY'),
                        DOPBSPBackEnd.text('DAY_THURSDAY'),
                        DOPBSPBackEnd.text('DAY_FRIDAY'),
                        DOPBSPBackEnd.text('DAY_SATURDAY')],
        dayShortNames = [DOPBSPBackEnd.text('SHORT_DAY_SUNDAY'),
                         DOPBSPBackEnd.text('SHORT_DAY_MONDAY'),
                         DOPBSPBackEnd.text('SHORT_DAY_TUESDAY'),
                         DOPBSPBackEnd.text('SHORT_DAY_WEDNESDAY'),
                         DOPBSPBackEnd.text('SHORT_DAY_THURSDAY'),
                         DOPBSPBackEnd.text('SHORT_DAY_FRIDAY'),
                         DOPBSPBackEnd.text('SHORT_DAY_SATURDAY')],
        monthNames = [DOPBSPBackEnd.text('MONTH_JANUARY'),
                      DOPBSPBackEnd.text('MONTH_FEBRUARY'),
                      DOPBSPBackEnd.text('MONTH_MARCH'),
                      DOPBSPBackEnd.text('MONTH_APRIL'),
                      DOPBSPBackEnd.text('MONTH_MAY'),
                      DOPBSPBackEnd.text('MONTH_JUNE'),
                      DOPBSPBackEnd.text('MONTH_JULY'),
                      DOPBSPBackEnd.text('MONTH_AUGUST'),
                      DOPBSPBackEnd.text('MONTH_SEPTEMBER'),
                      DOPBSPBackEnd.text('MONTH_OCTOBER'),
                      DOPBSPBackEnd.text('MONTH_NOVEMBER'),
                      DOPBSPBackEnd.text('MONTH_DECEMBER')],
        monthShortNames = [DOPBSPBackEnd.text('SHORT_MONTH_JANUARY'),
                           DOPBSPBackEnd.text('SHORT_MONTH_FEBRUARY'),
                           DOPBSPBackEnd.text('SHORT_MONTH_MARCH'),
                           DOPBSPBackEnd.text('SHORT_MONTH_APRIL'),
                           DOPBSPBackEnd.text('SHORT_MONTH_MAY'),
                           DOPBSPBackEnd.text('SHORT_MONTH_JUNE'),
                           DOPBSPBackEnd.text('SHORT_MONTH_JULY'),
                           DOPBSPBackEnd.text('SHORT_MONTH_AUGUST'),
                           DOPBSPBackEnd.text('SHORT_MONTH_SEPTEMBER'),
                           DOPBSPBackEnd.text('SHORT_MONTH_OCTOBER'),
                           DOPBSPBackEnd.text('SHORT_MONTH_NOVEMBER'),
                           DOPBSPBackEnd.text('SHORT_MONTH_DECEMBER')],
        startDate,
        minDate;
        
        /*
         * Start date.
         */
        $('#DOPBSP-coupon-start_date').datepicker('destroy');                      
        $('#DOPBSP-coupon-start_date').datepicker({beforeShow: function(input, inst){
                                                        $('#ui-datepicker-div').removeClass('DOPBSP-admin-datepicker')
                                                                               .addClass('DOPBSP-admin-datepicker');
                                                  },
                                                  dateFormat: 'yy-mm-dd',
                                                  dayNames: dayNames,
                                                  dayNamesMin: dayShortNames,
                                                  minDate: 0,
                                                  monthNames: monthNames,
                                                  monthNamesMin: monthShortNames,
                                                  nextText: '',
                                                  prevText: ''});
                           
        $('#DOPBSP-coupon-start_date').unbind('change');
        $('#DOPBSP-coupon-start_date').bind('change', function(){
            $('#DOPBSP-coupon-end_date').val('');
            DOPBSPBackEndCoupon.init();
        });
        
        /*
         * End date.
         */
        startDate = $('#DOPBSP-coupon-start_date'); 
        minDate = startDate.val() === '' ? 0:DOPPrototypes.getDatesDifference(DOPPrototypes.getToday(), startDate.val(), 'days', 'integer');
            
        $('#DOPBSP-coupon-end_date').datepicker('destroy');                      
        $('#DOPBSP-coupon-end_date').datepicker({beforeShow: function(input, inst){
                                                    $('#ui-datepicker-div').removeClass('DOPBSP-admin-datepicker')
                                                                           .addClass('DOPBSP-admin-datepicker');
                                                },
                                                dateFormat: 'yy-mm-dd',
                                                dayNames: dayNames,
                                                dayNamesMin: dayShortNames,
                                                minDate: minDate,
                                                monthNames: monthNames,
                                                monthNamesMin: monthShortNames,
                                                nextText: '',
                                                prevText: ''});
        
        $('.ui-datepicker').removeClass('notranslate').addClass('notranslate');

        /*
         * Number of coupons.
         */
        $('#DOPBSP-coupon-no_coupons').unbind('input propertychange');
        $('#DOPBSP-coupon-no_coupons').bind('input propertychange', function(){
            DOPPrototypes.cleanInput($(this), '0123456789', '', '');
        });
        
        /*
         * Price
         */
        $('#DOPBSP-coupon-price').unbind('input propertychange');
        $('#DOPBSP-coupon-price').bind('input propertychange', function(){
            DOPPrototypes.cleanInput($(this), '0123456789.', '', '0');
        });
    };
    
    /*
     * Edit coupon.
     * 
     * @param type (String): field type
     * @param field (String): coupon field
     * @param value (String): coupon field value
     * @param onBlur (Boolean): true if function has been called on blur event
     */
    this.edit = function(type, 
                         field,
                         value, 
                         onBlur){
        onBlur = onBlur === undefined ? false:true;
        
        this.ajaxRequestInProgress !== undefined && !onBlur ? this.ajaxRequestInProgress.abort():'';
        this.ajaxRequestTimeout !== undefined ? clearTimeout(this.ajaxRequestTimeout):'';
        
        switch (field){
            case 'name':
                $('#DOPBSP-coupon-ID-1 .dopbsp-name').html(value === '' ? '&nbsp;':value);
                break;
        }
        
        if (onBlur 
                || type === 'select' 
                || type === 'switch'){
            if (!onBlur){
                DOPBSPBackEnd.toggleMessages('active-info', DOPBSPBackEnd.text('MESSAGES_SAVING'));
            }
            
            $.post(ajaxurl, {action: 'dopbsp_coupon_edit',
                             field: field,
                             value: value,
                             language: $('#DOPBSP-coupon-language').val()}, function(data){
                if (!onBlur){
                    DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('MESSAGES_SAVING_SUCCESS'));
                }
            }).fail(function(data){
                DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
            });
        }
        else{
            DOPBSPBackEnd.toggleMessages('active-info', DOPBSPBackEnd.text('MESSAGES_SAVING'));

            this.ajaxRequestTimeout = setTimeout(function(){
                clearTimeout(this.ajaxRequestTimeout);

                this.ajaxRequestInProgress = $.post(ajaxurl, {action: 'dopbsp_coupon_edit',
                                                              field: field,
                                                              value: value,
                                                              language: $('#DOPBSP-coupon-language').val()}, function(data){
                    DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('MESSAGES_SAVING_SUCCESS'));
                }).fail(function(data){
                    DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
                });
            }, 600);
        }
    };
    
    /*
     * Generate coupon code.
     * 
     */
    this.generateCode = function(){
        var code = DOPPrototypes.getRandomString(16);
        
        $('#DOPBSP-coupon-code').val(code);
        DOPBSPBackEndCoupon.edit('text',
                          'code',
                          code);
    };

    return this.__construct();
};
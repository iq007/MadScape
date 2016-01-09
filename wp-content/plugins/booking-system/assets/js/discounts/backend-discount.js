
/*
* Title                   : Pinpoint Booking System WordPress Plugin
* Version                 : 2.1.2
* File                    : assets/js/discounts/backend-discount.js
* File Version            : 1.0.7
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end discount JavaScript class.
*/


var DOPBSPBackEndDiscount = new function(){
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
     * Display discount.
     * 
     * @param language (String): discount current editing language
     * @param clearDiscount (Boolean): clear current discount data diplay
     */
    this.display = function(language,
                            clearDiscount){
        var HTML = new Array();
        
        language = language === undefined ? ($('#DOPBSP-discount-language').val() === undefined ? '':$('#DOPBSP-discount-language').val()):language;
        clearDiscount = clearDiscount === undefined ? true:false;
        language = clearDiscount ? '':language;
        
        if (clearDiscount){
            DOPBSPBackEnd.clearColumns(2);
        }
        DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('MESSAGES_LOADING'));
        
        $('#DOPBSP-column1 .dopbsp-column-content li').removeClass('dopbsp-selected');
        $('#DOPBSP-discount-ID-1').addClass('dopbsp-selected');
        $('#DOPBSP-discount-ID').val(1);
        
        $.post(ajaxurl, {action: 'dopbsp_discount_display', 
                         language: language}, function(data){
            HTML.push('<a href="'+DOPBSP_CONFIG_HELP_DOCUMENTATION_URL+'" target="_blank" class="dopbsp-button dopbsp-help">');
            HTML.push(' <span class="dopbsp-info dopbsp-help">');
            HTML.push(DOPBSPBackEnd.text('DISCOUNTS_DISCOUNT_ADD_ITEM_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('DISCOUNTS_DISCOUNT_EDIT_ITEM_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('DISCOUNTS_DISCOUNT_DELETE_ITEM_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('DISCOUNTS_DISCOUNT_SORT_ITEM_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('HELP_VIEW_DOCUMENTATION'));
            HTML.push(' </span>');
            HTML.push('</a>');
            
            $('#DOPBSP-column2 .dopbsp-column-header').html(HTML.join(''));
            $('#DOPBSP-column2 .dopbsp-column-content').html(data);
            
            DOPBSPBackEndDiscountItems.init();
            DOPBSPBackEndDiscountItem.init();
            DOPBSPBackEndDiscountItemRules.init();
            DOPBSPBackEndDiscountItemRule.init();
            DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('DISCOUNTS_DISCOUNT_LOADED'));
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };

    /*
     * Edit discount.
     * 
     * @param type (String): field type
     * @param field (String): item field
     * @param value (String): item value
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
                $('#DOPBSP-discount-ID-1 .dopbsp-name').html(value === '' ? '&nbsp;':value);
                break;
        }
        
        switch (type){
            case 'switch':
                value = $('#DOPBSP-discount-'+field+'-1').is(':checked') ? 'true':'false';
                break;
        }
        
        if (onBlur 
                || type === 'select' 
                || type === 'switch'){
            if (!onBlur){
                DOPBSPBackEnd.toggleMessages('active-info', DOPBSPBackEnd.text('MESSAGES_SAVING'));
            }
            
            $.post(ajaxurl, {action: 'dopbsp_discount_edit',
                             field: field,
                             value: value}, function(data){
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

                this.ajaxRequestInProgress = $.post(ajaxurl, {action: 'dopbsp_discount_edit',
                                                              field: field,
                                                              value: value}, function(data){
                    DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('MESSAGES_SAVING_SUCCESS'));
                }).fail(function(data){
                    DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
                });
            }, 600);
        }
    };

    return this.__construct();
};
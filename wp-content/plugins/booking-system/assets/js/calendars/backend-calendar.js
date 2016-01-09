
/*
* Title                   : Pinpoint Booking System WordPress Plugin
* Version                 : 2.1.2
* File                    : assets/js/calendars/backend-calendar.js
* File Version            : 1.0.8
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end calendar JavaScript class.
*/

var DOPBSPBackEndCalendar = new function(){
    'use strict';
    
    /*
     * Private variables
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
     * Iitialize calendar.
     * 
     * @param userId (Number): user ID
     */
    this.init = function(userId){
        var headerHTML = new Array(),
        helpHTML = new Array();
    
        DOPBSPBackEnd.clearColumns(2);
        
        $('#DOPBSP-column1 .dopbsp-column-content li').removeClass('dopbsp-selected');
        $('#DOPBSP-calendar-ID-1').addClass('dopbsp-selected');
        $('#DOPBSP-calendar-ID').val(1);
        $('#DOPBSP-admin-reservations').css('display', 'block');   
        
        headerHTML.push('<a href="javascript:DOPBSPBackEndCalendar.display(1)" class="dopbsp-button dopbsp-calendar dopbsp-selected"><span class="dopbsp-info">'+DOPBSPBackEnd.text('CALENDARS_EDIT_CALENDAR')+'</span></a>');
        headerHTML.push('<a href="javascript:DOPBSPBackEndSettingsCalendar.display(1)" class="dopbsp-button dopbsp-settings"><span class="dopbsp-info">'+DOPBSPBackEnd.text('CALENDARS_EDIT_CALENDAR_SETTINGS')+'</span></a>');
        headerHTML.push('<a href="javascript:DOPBSPBackEndSettingsNotifications.display(1)" class="dopbsp-button dopbsp-notifications"><span class="dopbsp-info">'+DOPBSPBackEnd.text('CALENDARS_EDIT_CALENDAR_NOTIFICATIONS')+'</span></a>');
        headerHTML.push('<a href="javascript:DOPBSPBackEndSettingsPaymentGateways.display(1)" class="dopbsp-button dopbsp-payments"><span class="dopbsp-info">'+DOPBSPBackEnd.text('CALENDARS_EDIT_CALENDAR_PAYMENT_GATEWAYS')+'</span></a>');
            
        helpHTML.push(DOPBSPBackEnd.text('CALENDARS_EDIT_CALENDAR_HELP')+'<br /><br />');
        helpHTML.push(DOPBSPBackEnd.text('CALENDARS_EDIT_CALENDAR_SETTINGS_HELP')+'<br /><br />');
        helpHTML.push(DOPBSPBackEnd.text('CALENDARS_EDIT_CALENDAR_EMAILS_HELP')+'<br /><br />');
        helpHTML.push(DOPBSPBackEnd.text('CALENDARS_EDIT_CALENDAR_PAYMENT_GATEWAYS_HELP')+'<br /><br />');
        helpHTML.push(DOPBSPBackEnd.text('CALENDARS_CALENDAR_NOTIFICATIONS_HELP')+'<br /><br />');
        helpHTML.push(DOPBSPBackEnd.text('HELP_VIEW_DOCUMENTATION'));
        headerHTML.push('<a href="'+DOPBSP_CONFIG_HELP_DOCUMENTATION_URL+'" target="_blank" class="dopbsp-button dopbsp-help"><span class="dopbsp-info dopbsp-help">'+helpHTML.join('')+'</span></a>');

        $('#DOPBSP-col-column2').addClass('dopbsp-calendar');
        $('#DOPBSP-column2 .dopbsp-column-header').html(headerHTML.join(''));
        
        this.display();
    };

    /*
     * Display calendar.
     * 
     * @param id (Number): calendar ID
     */
    this.display = function(id){
        DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('MESSAGES_LOADING'));
        DOPBSPBackEndSettings.toggle(id, 'calendar');

        $.post(ajaxurl, {action: 'dopbsp_calendar_get_options'}, function(data){
            $('#DOPBSP-column2 .dopbsp-column-content').html('<div id="DOPBSP-calendar"></div>');
            $('#DOPBSP-calendar').DOPBSPCalendar($.parseJSON(data));

            $.post(ajaxurl, {action: 'dopbsp_get_new_reservations'}, function(data){
                if (parseInt(data) !== 0){
                    $('#DOPBSP-new-reservations').addClass('dopbsp-new');
                    $('#DOPBSP-new-reservations span').html(data);
                }
            });
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };

    /*
     * Edit calendar.
     * 
     * @param id (Number): calendar ID
     * @param type (String): field type
     * @param field (String): field name
     * @param value (String): field value
     * @param onBlur (Boolean): true if function has been called on blur event
     */
    this.edit = function(id, 
                         type,
                         field,
                         value, 
                         onBlur){
        onBlur = onBlur === undefined ? false:true;
        
        this.ajaxRequestInProgress !== undefined && !onBlur ? this.ajaxRequestInProgress.abort():'';
        this.ajaxRequestTimeout !== undefined ? clearTimeout(this.ajaxRequestTimeout):'';
        
        switch (field){
            case 'name':
                $('#DOPBSP-calendar-ID-'+id+' .dopbsp-name').html(value === '' ? '&nbsp;':value);
                break;
        }
        
        if (onBlur){
            $.post(ajaxurl, {action: 'dopbsp_calendar_edit',
                             id: id,
                             field: field,
                             value: value}, function(data){
            }).fail(function(data){
                DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
            });
        }
        else{
            DOPBSPBackEnd.toggleMessages('active-info', DOPBSPBackEnd.text('MESSAGES_SAVING'));

            this.ajaxRequestTimeout = setTimeout(function(){
                clearTimeout(this.ajaxRequestTimeout);

                this.ajaxRequestInProgress = $.post(ajaxurl, {action: 'dopbsp_calendar_edit',
                                                              id: id,
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
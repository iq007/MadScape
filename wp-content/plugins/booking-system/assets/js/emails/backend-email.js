
/*
* Title                   : Pinpoint Booking System WordPress Plugin
* Version                 : 2.1.2
* File                    : assets/js/emails/backend-email.js
* File Version            : 1.0.6
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end email JavaScript class.
*/


var DOPBSPBackEndEmail = new function(){
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
     * Display email.
     * 
     * @param language (String): email current editing language
     * @param template (String): email current editing template
     * @param clearEmail (Boolean): clear email extra data diplay
     */
    this.display = function(language,
                            template,
                            clearEmail){
        var HTML = new Array();
        
        language = language === undefined ? ($('#DOPBSP-email-language').val() === undefined ? '':$('#DOPBSP-email-language').val()):language;
        template = template === undefined ? ($('#DOPBSP-email-select-template').val() === undefined ? 'book_admin':$('#DOPBSP-email-select-template').val()):template;
        clearEmail = clearEmail === undefined ? true:false;
        language = clearEmail ? '':language;
        
        if (clearEmail){
            DOPBSPBackEnd.clearColumns(2);
        }
        DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('MESSAGES_LOADING'));
        
        $('#DOPBSP-column1 .dopbsp-column-content li').removeClass('dopbsp-selected');
        $('#DOPBSP-email-ID-1').addClass('dopbsp-selected');
        $('#DOPBSP-email-ID').val(1);
        
        $.post(ajaxurl, {action: 'dopbsp_email_display', 
                         language: language,
                         template: template}, function(data){
            
            $('#DOPBSP-column2 .dopbsp-column-header').html(HTML.join(''));
            $('#DOPBSP-column2 .dopbsp-column-content').html(data);
            
            $('#DOPBSP-email-start_date').datepicker();
            $('#DOPBSP-email-end_date').datepicker();
            
            DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('EMAILS_EMAIL_LOADED'));
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };

    /*
     * Edit email.
     * 
     * @param template (String): email template
     * @param type (String): email field type
     * @param field (String): email field
     * @param value (String): email field value
     * @param onBlur (Boolean): true if function has been called on blur event
     */
    this.edit = function(template,
                         type, 
                         field,
                         value, 
                         onBlur){
        onBlur = onBlur === undefined ? false:true;
        
        this.ajaxRequestInProgress !== undefined && !onBlur ? this.ajaxRequestInProgress.abort():'';
        this.ajaxRequestTimeout !== undefined ? clearTimeout(this.ajaxRequestTimeout):'';
        
        switch (field){
            case 'name':
                $('#DOPBSP-email-ID-1 .dopbsp-name').html(value === '' ? '&nbsp;':value);
                break;
        }
        
        if (onBlur){
            $.post(ajaxurl, {action: 'dopbsp_email_edit',
                             template: template,
                             field: field,
                             value: value,
                             language: $('#DOPBSP-email-language').val()}, function(data){
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

                this.ajaxRequestInProgress = $.post(ajaxurl, {action: 'dopbsp_email_edit',
                                                              template: template,
                                                              field: field,
                                                              value: value,
                                                              language: $('#DOPBSP-email-language').val()}, function(data){
                    DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('MESSAGES_SAVING_SUCCESS'));
                }).fail(function(data){
                    DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
                });
            }, 600);
        }
    };

    return this.__construct();
};
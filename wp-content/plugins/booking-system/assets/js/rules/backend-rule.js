
/*
* Title                   : Pinpoint Booking System WordPress Plugin
* Version                 : 2.1.2
* File                    : assets/js/rules/backend-rule.js
* File Version            : 1.0.6
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end rule JavaScript class.
*/


var DOPBSPBackEndRule = new function(){
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
     * Display rule.
     * 
     * @param language (String): rule current editing language
     * @param clearRule (Boolean): clear rule extra data diplay
     */
    this.display = function(language,
                            clearRule){
        var HTML = new Array();
        
        language = language === undefined ? ($('#DOPBSP-rule-language').val() === undefined ? '':$('#DOPBSP-rule-language').val()):language;
        clearRule = clearRule === undefined ? true:false;
        language = clearRule ? '':language;
        
        if (clearRule){
            DOPBSPBackEnd.clearColumns(2);
        }
        DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('MESSAGES_LOADING'));
        
        $('#DOPBSP-column1 .dopbsp-column-content li').removeClass('dopbsp-selected');
        $('#DOPBSP-rule-ID-1').addClass('dopbsp-selected');
        $('#DOPBSP-rule-ID').val();
        
        $.post(ajaxurl, {action: 'dopbsp_rule_display', 
                         language: language}, function(data){
            HTML.push('<a href="'+DOPBSP_CONFIG_HELP_DOCUMENTATION_URL+'" target="_blank" class="dopbsp-button dopbsp-help">');
            HTML.push(' <span class="dopbsp-info dopbsp-help">');
            HTML.push(DOPBSPBackEnd.text('HELP_VIEW_DOCUMENTATION'));
            HTML.push(' </span>');
            HTML.push('</a>');
            
            $('#DOPBSP-column2 .dopbsp-column-header').html(HTML.join(''));
            $('#DOPBSP-column2 .dopbsp-column-content').html(data);
            
            $('#DOPBSP-rule-start_date').datepicker();
            $('#DOPBSP-rule-end_date').datepicker();
            
            DOPBSPBackEndRule.init();
            DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('RULES_RULE_LOADED'));
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };
    
    /*
     * Initialize events and validations.
     */
    this.init = function(){
        /*
         * Number of rules.
         */
        $('#DOPBSP-rule-time_lapse_min').unbind('input propertychange');
        $('#DOPBSP-rule-time_lapse_min').bind('input propertychange', function(){
            DOPPrototypes.cleanInput($(this), '0123456789.', '', '0');
        });
        
        /*
         * Price
         */
        $('#DOPBSP-rule-time_lapse_max').unbind('input propertychange');
        $('#DOPBSP-rule-time_lapse_max').bind('input propertychange', function(){
            DOPPrototypes.cleanInput($(this), '0123456789.', '', '0');
        });
    };

    /*
     * Edit rule.
     * 
     * @param type (String): field type
     * @param field (String): rule field
     * @param value (String): rule field value
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
                $('#DOPBSP-rule-ID-1 .dopbsp-name').html(value === '' ? '&nbsp;':value);
                break;
        }
        
        if (onBlur 
                || type === 'select' 
                || type === 'switch'){
            if (!onBlur){
                DOPBSPBackEnd.toggleMessages('active-info', DOPBSPBackEnd.text('MESSAGES_SAVING'));
            }
            
            $.post(ajaxurl, {action: 'dopbsp_rule_edit',
                             field: field,
                             value: value,
                             language: $('#DOPBSP-rule-language').val()}, function(data){
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

                this.ajaxRequestInProgress = $.post(ajaxurl, {action: 'dopbsp_rule_edit',
                                                              field: field,
                                                              value: value,
                                                              language: $('#DOPBSP-rule-language').val()}, function(data){
                    DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('MESSAGES_SAVING_SUCCESS'));
                }).fail(function(data){
                    DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
                });
            }, 600);
        }
    };

    return this.__construct();
};
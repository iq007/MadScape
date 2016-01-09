
/*
* Title                   : Pinpoint Booking System WordPress Plugin
* Version                 : 2.1.2
* File                    : assets/js/extras/backend-extra.js
* File Version            : 1.0.6
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end extra JavaScript class.
*/


var DOPBSPBackEndExtra = new function(){
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
     * Display extra.
     * 
     * @param language (String): extra current editing language
     * @param clearExtra (Boolean): clear current extra data diplay
     */
    this.display = function(language,
                            clearExtra){
        var HTML = new Array();
        
        language = language === undefined ? ($('#DOPBSP-extra-language').val() === undefined ? '':$('#DOPBSP-extra-language').val()):language;
        clearExtra = clearExtra === undefined ? true:false;
        language = clearExtra ? '':language;
        
        if (clearExtra){
            DOPBSPBackEnd.clearColumns(2);
        }
        DOPBSPBackEnd.toggleMessages('active', DOPBSPBackEnd.text('MESSAGES_LOADING'));
        
        $('#DOPBSP-column1 .dopbsp-column-content li').removeClass('dopbsp-selected');
        $('#DOPBSP-extra-ID-1').addClass('dopbsp-selected');
        $('#DOPBSP-extra-ID').val(1);
        
        $.post(ajaxurl, {action: 'dopbsp_extra_display', 
                         language: language}, function(data){
            HTML.push('<a href="'+DOPBSP_CONFIG_HELP_DOCUMENTATION_URL+'" target="_blank" class="dopbsp-button dopbsp-help">');
            HTML.push(' <span class="dopbsp-info dopbsp-help">');
            HTML.push(DOPBSPBackEnd.text('EXTRAS_EXTRA_ADD_GROUP_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('EXTRAS_EXTRA_EDIT_GROUP_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('EXTRAS_EXTRA_SORT_GROUP_HELP')+'<br /><br />');
            HTML.push(DOPBSPBackEnd.text('HELP_VIEW_DOCUMENTATION'));
            HTML.push(' </span>');
            HTML.push('</a>');
            
            $('#DOPBSP-column2 .dopbsp-column-header').html(HTML.join(''));
            $('#DOPBSP-column2 .dopbsp-column-content').html(data);
            
            DOPBSPBackEndExtraGroups.init();
            DOPBSPBackEndExtraGroupItems.init();
            DOPBSPBackEndExtraGroupItem.init();
            DOPBSPBackEnd.toggleMessages('success', DOPBSPBackEnd.text('EXTRAS_EXTRA_LOADED'));
        }).fail(function(data){
            DOPBSPBackEnd.toggleMessages('error', data.status+': '+data.statusText);
        });
    };

    /*
     * Edit extra.
     * 
     * @param type (String): field type
     * @param field (String): group field
     * @param value (String): group value
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
                $('#DOPBSP-extra-ID-1 .dopbsp-name').html(value === '' ? '&nbsp;':value);
                break;
        }
        
        if (onBlur 
                || type === 'select' 
                || type === 'switch'){
            if (!onBlur){
                DOPBSPBackEnd.toggleMessages('active-info', DOPBSPBackEnd.text('MESSAGES_SAVING'));
            }
            
            $.post(ajaxurl, {action: 'dopbsp_extra_edit',
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

                this.ajaxRequestInProgress = $.post(ajaxurl, {action: 'dopbsp_extra_edit',
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
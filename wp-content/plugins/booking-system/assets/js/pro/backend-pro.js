
/*
* Title                   : Pinpoint Booking System WordPress Plugin
* Version                 : 2.1.2
* File                    : assets/js/pro/backend-pro.js
* File Version            : 1.0.4
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end pro JavaScript class.
*/

var DOPBSPBackEndPRO = new function(){
    'use strict';
    
    /*
     * Private variables.
     */
    var $ = jQuery.noConflict();
        
    /*
     * Constructor
     */
    this.__construct = function(){
    };
    
    /*
     * Display PRO.
     */
    this.display = function(){
        $('.DOPBSP-admin .dopbsp-main').css('display', 'block');
    };
    
    return this.__construct();
};
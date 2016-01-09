<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin
* Version                 : 2.1.1
* File                    : views/emails/views-backend-emails.php
* File Version            : 1.0.5
* Created / Last Modified : 26 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end emails views class.
*/

    if (!class_exists('DOPBSPViewsBackEndEmails')){
        class DOPBSPViewsBackEndEmails extends DOPBSPViewsBackEnd{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Returns emails template.
             * 
             * @param args (array): function arguments
             * 
             * @return emails HTML page
             */
            function template($args = array()){
                global $DOPBSP;
                
                $this->getTranslation();
?>            
    <div class="wrap DOPBSP-admin">
        
<!--
    Header
-->
        <?php $this->displayHeader($DOPBSP->text('TITLE'), $DOPBSP->text('EMAILS_TITLE')); ?>
        <input type="hidden" name="DOPBSP-email-ID" id="DOPBSP-email-ID" value="" />
        
<!--
    Content
-->
        <div class="dopbsp-main dopbsp-hidden">
            <table class="dopbsp-content-wrapper">
                <colgroup>
                    <col id="DOPBSP-col-column1" class="dopbsp-column1" />
                    <col id="DOPBSP-col-column-separator1" class="dopbsp-separator" />
                    <col id="DOPBSP-col-column2" class="dopbsp-column2" />
                </colgroup>
                <tbody>
                    <tr>
                        <td id="DOPBSP-column1" class="dopbsp-column">
                            <div class="dopbsp-column-header">
<?php 
                if (isset($_GET['page']) && $DOPBSP->vars->pro_tips){ 
?>                  
                                <a href="?page=dopbsp-pro" class="dopbsp-button dopbsp-add"><span class="dopbsp-info dopbsp-info-blue dopbsp-help"><?php echo $DOPBSP->text('EMAILS_ADD_EMAIL_SUBMIT').' - '.$DOPBSP->text('ONLY_IN_PRO_MESSAGE_ONLY'); ?> <span class="dopbsp-pro"><?php echo $DOPBSP->text('ONLY_IN_PRO_MESSAGE_PRO'); ?></span></span></a>
<?php
                }
?>                           
                                <br class="dopbsp-clear" />
                            </div>
                            <div class="dopbsp-column-content">&nbsp;</div>
                        </td>
                        <td id="DOPBSP-column-separator1" class="dopbsp-separator"></td>
                        <td id="DOPBSP-column2" class="dopbsp-column">
                            <div class="dopbsp-column-header">&nbsp;</div>
                            <div class="dopbsp-column-content">&nbsp;</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>       
<?php
            }
        }
    }
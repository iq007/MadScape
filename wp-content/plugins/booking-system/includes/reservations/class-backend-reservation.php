<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin
* Version                 : 2.1.6
* File                    : includes/reservations/class-backend-reservation.php
* File Version            : 1.0.9
* Created / Last Modified : 15 February 2016
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end reservations PHP class.
*/

    if (!class_exists('DOPBSPBackEndReservation')){
        class DOPBSPBackEndReservation extends DOPBSPBackEndReservations{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Add reservation.
             * 
             * @param calendar_id (integer): calendar ID
             * @param language (string): selected language
             * @param currency (string): currency sign
             * @param currency_code (string): currency code
             * @param reservation (array): reservation details
             * @param form (object): form details
             * @param address_billing (object): billing address details
             * @param address_shipping (object): shipping address details
             * @param payment_method (string): payment method
             * @param token (string): payment token
             * @param transaction_id (string): transaction ID
             * @param status (string): reservation status
             * 
             * @return reservation ID
             */
            function add($calendar_id,
                         $language,
                         $currency,
                         $currency_code,
                         $reservation,
                         $form,
                         $address_billing,
                         $address_shipping,
                         $payment_method,
                         $token,
                         $transaction_id = '',
                         $status = ''){
                global $wpdb;
                global $DOPBSP;
                
                $settings_payment = $DOPBSP->classes->backend_settings->values($calendar_id,  
                                                                               'payment');
                
                /*
                 * Set status
                 */
                $status = $status != '' ? $status:
                                          ((($payment_method == 'none' || $payment_method == 'default') && $settings_payment->arrival_with_approval_enabled == 'false') || ($payment_method != 'none' && $payment_method != 'default' && $payment_method != 'woocommerce') ? 'pending':'approved');
                
                $wpdb->insert($DOPBSP->tables->reservations, array('calendar_id' => $calendar_id,
                                                                   'language' => $language,
                                                                   'currency' => $currency,
                                                                   'currency_code' => $currency_code,
                                                                   'check_in' => $reservation['check_in'],
                                                                   'check_out' => $reservation['check_out'],
                                                                   'start_hour' => $reservation['start_hour'],
                                                                   'end_hour' => $reservation['end_hour'],
                                                                   'no_items' => $reservation['no_items'],
                                                                   'price' => $reservation['price'],
                                                                   'price_total' => $reservation['price_total'],
                                                                   'extras' => isset($reservation['extras']) ? json_encode($reservation['extras']):'',
                                                                   'extras_price' => $reservation['extras_price'],
                                                                   'discount' => isset($reservation['discount']) ? json_encode($reservation['discount']):'',
                                                                   'discount_price' => $reservation['discount_price'],
                                                                   'coupon' => isset($reservation['coupon']) ? json_encode($reservation['coupon']):'',
                                                                   'coupon_price' => $reservation['coupon_price'],
                                                                   'fees' => isset($reservation['fees']) && $reservation['fees'] != '' ? json_encode($reservation['fees']):'',
                                                                   'fees_price' => $reservation['fees_price'],
                                                                   'deposit' => isset($reservation['deposit']) ? json_encode($reservation['deposit']):'',
                                                                   'deposit_price' => $reservation['deposit_price'],
                                                                   'days_hours_history' => isset($reservation['days_hours_history']) ? json_encode($reservation['days_hours_history']):'',
                                                                   'form' => isset($form) && $form != '' ? json_encode($form):'',
                                                                   'address_billing' => isset($address_billing) && $address_billing != '' ? json_encode($address_billing):'',
                                                                   'address_shipping' => isset($address_shipping) && $address_shipping != '' ? ($address_shipping == 'billing_address' ? $address_shipping:json_encode($address_shipping)):'',
                                                                   'email' => $this->getEmail($form),
                                                                   'status' => $status,
                                                                   'payment_method' => $payment_method,
                                                                   'token' => $token,
                                                                   'transaction_id' => $transaction_id != '' ? $transaction_id:''));
                $reservation_id = $wpdb->insert_id;
                
                /*
                 * Update schedule.
                 */
                if ($status == 'approved'
                        || ($status == '' 
                                && ($settings_payment->arrival_with_approval_enabled == 'true'
                                        || ($payment_method != 'none'
                                                && $payment_method != 'default')))){
                    $DOPBSP->classes->backend_calendar_schedule->setApproved($reservation_id);
                    
                    /*
                     * Update coupons.
                     */
                    $coupon = $reservation['coupon'];
                        
                    if ($coupon['id'] != 0){
                        $DOPBSP->classes->backend_coupon->update($coupon['id'],
                                                                 'use');
                    }
                }
                
                return $reservation_id;
            }
            
            /*
             * Approve reservation.
             * 
             * @param reservation_id (integer): reservation ID
             * @post reservation_id (integer): reservation ID
             */
            function approve($reservation_id = 0){
                global $wpdb;
                global $DOPBSP;
                
                $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id']:$reservation_id;
                
                $reservation = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->reservations.' WHERE id=%d',
                                                             $reservation_id));
                
                /*
                 * Stop approval if status is already approved.
                 */
                if ($reservation->status == 'approved'){
                    if (isset($_POST['reservation_id'])){
                        die();
                    }
                    else{
                        return '';
                    }
                }
                
                /*
                 * Verify reservations availability.
                 */
                if ($reservation->start_hour == ''){
                    if (!$DOPBSP->classes->backend_calendar_schedule->validateDays($reservation->calendar_id, $reservation->check_in, $reservation->check_out, $reservation->no_items)){
                        echo 'unavailable';
                        die();
                    }
                }
                else{
                    if (!$DOPBSP->classes->backend_calendar_schedule->validateHours($reservation->calendar_id, $reservation->check_in, $reservation->start_hour, $reservation->end_hour, $reservation->no_items)){
                        echo 'unavailable';
                        die();
                    }
                }
                
                /*
                 * Verify coupon.
                 */
                $coupon = json_decode($reservation->coupon);

                if ($coupon->id != 0){
                    if (!$DOPBSP->classes->backend_coupon->validate($coupon->id)){
                        echo 'unavailable-coupon';
                        die();
                    }
                    else{
                        /*
                         * If coupon is valid update.
                         */
                        $DOPBSP->classes->backend_coupon->update($coupon->id,
                                                                 'use');
                    }
                }
                
                /*
                 * Update if period is available.
                 */
                $wpdb->update($DOPBSP->tables->reservations, array('status' => 'approved'), 
                                                             array('id' => $reservation_id));
                
                $DOPBSP->classes->backend_calendar_schedule->setApproved($reservation_id);
                
                $DOPBSP->classes->backend_reservation_notifications->send($reservation_id,
                                                                          'approved');

                isset($_POST['reservation_id']) ? die():'';
            }
            
            /*
             * Cancel reservation.
             * 
             * @param reservation_id (integer): reservation ID
             * @post reservation_id (integer): reservation ID
             */
            function cancel($reservation_id = 0){
                global $wpdb;
                global $DOPBSP;
                
                $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id']:$reservation_id;
                
                $reservation = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->reservations.' WHERE id=%d',
                                                             $reservation_id));
                
                /*
                 * Stop cancellation if status is already canceled.
                 */
                if ($reservation->status == 'canceled'){
                    if (isset($_POST['reservation_id'])){
                        die();
                    }
                    else{
                        return '';
                    }
                }

                /*
                 * Begin reservation cancellation.
                 */
                $wpdb->update($DOPBSP->tables->reservations, array('status' => 'canceled'), 
                                                             array('id' => $reservation_id));
                $coupon = json_decode($reservation->coupon);
                
                if ($coupon->id != 0){
                    $DOPBSP->classes->backend_coupon->update($coupon->id,
                                                             'restore');
                }

                $DOPBSP->classes->backend_calendar_schedule->setCanceled($reservation_id);
                    
                $DOPBSP->classes->backend_reservation_notifications->send($reservation_id,
                                                                          'canceled');
             
/*
 * HOOK (dopbsp_action_cancel_payment) *************************************** Add action for payment gateways refunds. 
 * @param reservation (object): reservation data                
 */
                do_action('dopbsp_action_cancel_payment', $reservation);
                
                isset($_POST['reservation_id']) ? die():'';
            }
            
            /*
             * Delete reservation.
             * 
             * @param reservation_id (integer): reservation ID
             * @post reservation_id (integer): reservation ID
             */
            function delete($reservation_id = 0){
                global $wpdb;
                global $DOPBSP;
                
                $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id']:$reservation_id;
                
                $wpdb->delete($DOPBSP->tables->reservations, array('id' => $reservation_id));
                
                die();
            }
            
            /*
             * Reject reservation.
             * 
             * @param reservation_id (integer): reservation ID
             * @post reservation_id (integer): reservation ID
             */
            function reject($reservation_id = 0){
                global $wpdb;
                global $DOPBSP;
                
                $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id']:$reservation_id;
                
                $reservation = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->reservations.' WHERE id=%d',
                                                             $reservation_id));
                
                /*
                 * Stop rejection if status is already rejected.
                 */
                if ($reservation->status == 'rejected'){
                    if (isset($_POST['reservation_id'])){
                        die();
                    }
                    else{
                        return '';
                    }
                }
                
                /*
                 * Begin reservation rejection.
                 */
                $wpdb->update($DOPBSP->tables->reservations, array('status' => 'rejected'), 
                                                             array('id' => $reservation_id));
                
                $DOPBSP->classes->backend_reservation_notifications->send($reservation_id,
                                                                          'rejected');
                
                die();
            }
            
            /*
             * Get user email for the reservation.
             * 
             * @param form (array): booking form data
             * 
             * @return user email
             */
            function getEmail($form){
                $email = '';
                
                if (isset($form)
                        && $form != ''){
                    foreach ($form as $field){
                        if ($field['is_email'] == 'true'){
                            $email = $field['value'];
                            break;
                        }
                    }
                }
                
                return $email;
            }
        }
    }
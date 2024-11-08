<?php
use TotalProcessing\WC_TotalProcessing_Constants AS TP_CONSTANTS;

trait TotalProcessingGatewaySubscriptionTrait{
    public function scheduled_subscription_payment( $amount, $order ){
        $subsOrderID              = $order->get_meta('_subscription_renewal');
        $subsOrder                = wc_get_order( $subsOrderID );
        $subsParentOrderID        = $subsOrder->get_parent_id();
        $subsParentOrder          = wc_get_order( $subsParentOrderID );
        $subsParentOrderRegID     = $subsParentOrder->get_meta('_registration_id');
        if( $order->has_status( 'completed' ) || $order->has_status( 'processing' ) ){
            return;
        }
        if ( 0 == $amount ) {
            $order->payment_complete();
            return;
        }
        $responseData             = $this->requestScheduledPayment( $subsParentOrderRegID, $amount, $subsParentOrderID );
        // translators: placeholder is a transaction ID.
        if( isset( $responseData->id ) && isset( $responseData->result->code ) && in_array( (string)$responseData->result->code, ['000.100.110', '000.000.000'] ) ){
            $order->add_order_note( sprintf( __( 'Payment approved (ID: %s)', 'woocommerce-subscriptions' ), $responseData->id ) );
            $order->payment_complete( $responseData->id );
        }elseif( isset( $responseData->result->code ) ){
            $order->update_status( 'failed', sprintf( __( 'Transaction error: (%1$d) %2$s', 'woocommerce-subscriptions' ), (string)$responseData->result->code, (string)$responseData->result->description ) );
        }else{
            $order->update_status( 'failed', sprintf( __( 'Transaction error: (%1$d) %2$s', 'woocommerce-subscriptions' ), '000', 'Unknown Error' ) );
        }
        $this->writeLog( '----- scheduled_subscription_payment [subscription payment process]-----', [$amount, $subsOrderID, $subsParentOrderID, $subsParentOrderRegID, $responseData], 'debug', 12344 );
    }

    public function fn_change_subscription_payment_method( $subscription, $renewal_order ){
        $subsOrderID              = $subscription->get_id();
        $RegID                    = $subscription->get_registration_id();
        $subsParentOrderID        = $subscription->get_parent_id();
        $subsParentOrder          = wc_get_order($subsParentOrderID);
        $subsParentOrder->update_meta_data('_registration_id', $RegID );
        $subsParentOrder->save();

        $this->writeLog( '----- fn_change_subscription_payment_method [change payment method change process]-----', [$subsOrderID, $RegID, $subsParentOrderID], 'debug', 12344 );
    }

    function requestScheduledPayment( $regID, $amount, $orderID ) {
        if( empty( $regID ) ){
            return "empty registrationID";
        }
        $paymentType      = 'DB';
        if( $amount <= 0 ){
            $paymentType  = 'PA';
        }
        $url = "https://".$this->derivePlatformBase()."/v1/registrations/{$regID}/payments";
        $data = "entityId=" . $this->getEntityID() .
                    "&amount=" . number_format( $amount, 2, '.', '') . 
                    "&currency=" . get_woocommerce_currency() .
                    "&paymentType=" . $paymentType .
                    "&standingInstruction.mode=REPEATED" .
                    "&standingInstruction.type=RECURRING" .
                    "&standingInstruction.source=MIT";
        $this->writeLog( '----- scheduled_subscription_payment [subscription payment process data]-----', [$url, $data, $this->getAccessToken()], 'debug', 12344 );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Authorization:Bearer ' . $this->getAccessToken()));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            $responseData = curl_error($ch);
        }
        curl_close($ch);
        return json_decode($responseData);
    }
}


<?php
$tabsFieldsArray =  array(
    'general' => array(
        'title' => 'Total Processing Pay By Bank - Woocommerce',
        'description'=>'<p>General settings</p>',
        'fields'=> array(
            'enabled' => array(
                'title' => 'Enable/Disable',
                'label' => 'Enable Pay By Bank Payments',
                'type' => 'checkbox',
                'description' => '',
                'default' => 'no',
                'class' => 'wppd-ui-toggle',
            ),
            'title' => array(
                'title' => 'Title',
                'type' => 'text',
                'class' => '',
                'description' => 'This controls title text during checkout.',
                'default' => 'Pay by bank',
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => 'Description',
                'type' => 'text',
                'class' => '',
                'description' => 'This controls the description seen at checkout.',
                'default' => TP_OPENBANKING_GATEWAY_DESCRIPTION,
                'desc_tip' => true
            ),
        ),
    ),
    'gateway Settings' => array(
        'title' => 'Gateway Settings',
        'description'=>'<p>Gateway Settings</p>',
        'fields'=> array(
            'gatewayLiveOrTestStatus' => array(
                'title' => 'Current Endpoint mode',
                'type' => 'select',
                'class' => 'wc-enhanced-select-nostd',
                'default' => 'live',
                'options' => array(
                    'live' => 'Live',
                    'test' => 'Test'
                )
            ),
            'entityId_test' => array(
                'title' => 'Entity Id (TEST)',
                'type' => 'text',
                'class' => '',
                'description' => 'Enabled channel for Card Payments.',
                'default' => '',
                'desc_tip' => true,
            ),
            'accessToken_test' => array(
                'title' => 'Access Token (TEST)',
                'type' => 'text',
                'class' => '',
                'description' => 'Provided by Total Processing',
                'default' => '',
                'desc_tip' => true,
            ),
            'entityId' => array(
                'title' => 'Entity Id (LIVE)',
                'type' => 'text',
                'class' => '',
                'description' => 'Enabled channel for Card Payments.',
                'default' => '',
                'desc_tip' => true,
            ),
            'accessToken' => array(
                'title' => 'Access Token (LIVE)',
                'type' => 'text',
                'class' => '',
                'description' => 'Provided by Total Processing',
                'default' => '',
                'desc_tip' => true,
            ),
            'includeCartData' => array(
                'title' => 'Enable cart/order items data? <small><em>Default:Off</em></small>',
                'label' => 'Cart items data?',
                'type' => 'checkbox',
                'class' => 'wppd-ui-toggle',
                'description' => 'Embed cart item data in transaction data?',
                'default' => 'no',
                'desc_tip' => true,
            ),
        ),
    ),
    'configuration' => array(
        'title' => 'Gateway Configurations',
        'description' => '<p>Gateway Configurations</p>',
        'fields'=> array(
            'jsLogging' => array(
                'title' => 'Enable console log? <small><em>Default:Off</em></small>',
                'label' => 'Console.log events',
                'type' => 'checkbox',
                'class' => 'wppd-ui-toggle',
                'description' => 'Only if requested to be activated by Total Processing and private access is enabled should this be checked.',
                'default' => 'no',
                'desc_tip' => true,
            ),
            'serversidedebug' => array(
                'title' => 'Enable server side log? <small><em>Default:Off</em></small>',
                'label' => 'Debug log',
                'type' => 'checkbox',
                'class' => 'wppd-ui-toggle',
                'description' => 'Only if requested to be activated by Total Processing and private access is enabled should this be checked.',
                'default' => 'no',
                'desc_tip' => true,
            ),
            'logLevels' => array(
                'title' => 'Logging level inclusion',
                'type' => 'multiselect',
                'class' => 'wc-enhanced-select-nostd',
                'default' => array(
                    'emergency',
                    'critical',
                    'error',
                    'warning',
                ),
                'options' => array(
                    'critical'=> 'Critical',
                    'debug' => 'Debugging',
                    'emergency' => 'Emergency',
                    'error' => 'Error',
                    'info' => 'Information',
                    'warning' => 'Warning'
                )
            ),
        )
    ),
    'Logs' => array(
        'title'=>'Debugging Logs',
        'description' => '<p>To view details of debugging. '. (!($this->serversidedebug ?? false) ? 'Logging is currently disabled, please enable it <a href="'.admin_url( "/admin.php?page=wc-settings&tab=checkout&section=".$this->id."&opt=configuration#woocommerce_".$this->id."_serversidedebug" ).'">here</a>.' : '').'</p>',
        'body' => 'showLogs'
    ),
);

return $tabsFieldsArray;

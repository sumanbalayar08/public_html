<?php
use TotalProcessing\WC_TotalProcessing_Constants AS TP_CONSTANTS;
$tabsFieldsArray =  array(
    'general' => array(
        'title' => 'Card Payments &amp; Total Processing - WooCommerce v3',
        'description'=>'<p>General settings</p>',
        'fields'=> array(
            'enabled' => array(
                'title' => 'Enable/Disable',
                'label' => 'Enable Card Payments',
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
                'default' => 'Pay with card',
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => 'Description',
                'type' => 'text',
                'class' => '',
                'description' => 'This controls the description seen at checkout1.',
                'default' => 'Checkout with Card',
                'desc_tip' => true
            ),
        ),
    ),
    'gateway Settings' => array(
        'title' => 'Gateway Settings',
        'description'=>'<p>Gateway Settings</p>',
        'fields'=> array(
            'platformBase' => array(
                'title' => 'Current Endpoint mode',
                'type' => 'select',
                'class' => 'wc-enhanced-select-nostd',
                'default' => 'oppwa.com',
                'options' => array(
                    'oppwa.com' => 'Live',
                    'test.oppwa.com' => 'Test'
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
            'paymentType' => array(
                'title' => 'Authorisation Type',
                'type' => 'select',
                'class' => 'wc-enhanced-select-nostd',
                'description' => 'Reccomend using DB as (default) direct capture, some aquirers are not compatible with PA.',
                'default' => "DB",
                'desc_tip' => true,
                'options' => array(
                    "DB" => "Debit (DB) transaction immediately captures payment",
                    "PA" => "Pre-Auth (PA) transaction only (NOT supported)",
                )
            ),
            'createRegistration' => array(
                'title' => 'Enable tokenisation? <small><em>Default:Off</em></small>',
                'label' => 'Allow stored payment methods',
                'type' => 'checkbox',
                'class' => 'wppd-ui-toggle',
                'description' => 'Create secure payment token from initial payment or allow new payment method creation.',
                'default' => 'no',
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
            'dupePaymentCheck' => array(
                'title' => 'Enable Dupe Payment check system? <small><em>Default:Off</em></small>',
                'label' => 'Enable Dupe check',
                'type' => 'checkbox',
                'class' => 'wppd-ui-toggle',
                'description' => 'If enebaled, will check periodically if payment is successfull and set order successfull',
                'default' => 'no',
                'desc_tip' => true,
            ),
        ),
    ),
    'configuration' => array(
        'title'=>'Card Payments form',
        'description'=>'<p>When payment form is loaded, specific display can be configured</p>',
        'fields'=> array(
            'paymentBrands' => array(
                'title' => 'Card schemes enabled',
                'type' => 'multiselect',
                'class' => 'wc-enhanced-select-nostd',
                'default' => array(
                    'VISA',
                    'MASTER'
                ),
                'options' => array(
                    'VISA'=> 'Visa',
                    'MASTER' => 'Mastercard',
                    'AMEX' => 'American Express',
                    'MAESTRO' => 'Maestro'
                )
            ),
            'threeDv2' => array(
                'title' => '3D Secure 2.x data',
                'label' => 'Send additional 3d Secure version 2 data <small><em>Default:On</em></small>',
                'type' => 'checkbox',
                'class' => 'wppd-ui-toggle',
                'description' => 'Use 3d Secure version 2.x field parameters',
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'threeDv2Params' => array(
                'title' => '3Dv2 params enabled',
                'type' => 'multiselect',
                'class' => 'wc-enhanced-select-nostd',
                'default' => array(
                    'ReqAuthMethod'
                ),
                'options' => array(
                    'ReqAuthMethod' => 'ReqAuthMethod',
                    'ReqAuthTimestamp' => 'ReqAuthTimestamp',
                    'AccountAgeIndicator' => 'AccountAgeIndicator',
                    'AccountDate' => 'AccountDate',
                    'AccountPurchaseCount' => 'AccountPurchaseCount',
                    'TransactionType' => 'TransactionType',
                    'DeliveryTimeframe' => 'DeliveryTimeframe',
                    'DeliveryEmailAddress' => 'DeliveryEmailAddress',
                ),
            ),
            'transactionType3d' => array(
                'title' => 'Nature of payments for 3dv2 data',
                'type' => 'select',
                'class' => 'wc-enhanced-select-nostd',
                'description' => 'Reccomend Goods / Service Purchase (default).',
                'default' => '01',
                'desc_tip' => true,
                'options' => array(
                    '01' => 'Goods / Service Purchase',
                    '03' => 'Check Acceptance',
                    '10' => 'Account Funding',
                    '11' => 'Quasi-Cash Transaction',
                    '28' => 'Prepaid Activation and Load',
                ),
            ),
            'useModalPayFrames' => array(
                'title' => 'Payment Modal',
                'label' => 'Use modal popup on checkout? <small><em>Default:Off</em></small>',
                'type' => 'checkbox',
                'class' => 'wppd-ui-toggle',
                'description' => 'Use modal popup on checkout.',
                'default' => 'no',
                'desc_tip' => true,
            ),
            'autoFocusFrameCcNo' => array(
                'title' => 'Autofocus CC number?',
                'label' => 'Autofocus CC number',
                'type' => 'checkbox',
                'class' => 'wppd-ui-toggle',
                'default' => 'no',
                'desc_tip' => false,
            ),
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
    'Design' => array(
        'title'=>'Card Payments Design',
        'description'=>'<p>Design setting to match your theme.</p>',
        'fields'=> array(
            'framePrimaryColor' => array(
                'title' => 'Background colour (Hex)',
                'type' => 'color',
                'class' => '',
                'default' => '#fcfcfc',
                'desc_tip' => false,
            ),
            'frameAccentColor' => array(
                'title' => 'Card Storage Text Colour (Hex)',
                'type' => 'color',
                'class' => '',
                'default' => '#000000',
                'desc_tip' => false,
            ),
            'framewpwlControlBackground' => array(
                'title' => 'Form Field Background Colour (Hex)',
                'type' => 'color',
                'class' => '',
                'default' => '#ffffff',
                'desc_tip' => false,
            ),
            'framewpwlControlFontColor' => array(
                'title' => 'Form Field Text Colour (Hex)',
                'type' => 'color',
                'class' => '',
                'default' => '#000000',
                'desc_tip' => false,
            ),
            'framewpwlControlBorderRadius' => array(
                'title' => 'Form Field Border Radius (PX)',
                'type' => 'number',
                'class' => '',
                'default' => '3',
                'desc_tip' => false,
            ),
            'framewpwlControlBorderColor' => array(
                'title' => 'Form Field Border Colour (Hex)',
                'type' => 'color',
                'class' => '',
                'default' => '#e5e5e5',
                'desc_tip' => false,
            ),
            'framewpwlControlBorderWidth' => array(
                'title' => 'Form Field Border Width (PX)',
                'type' => 'number',
                'class' => '',
                'default' => '1',
                'desc_tip' => false,
            ),
            'framewpwlControlMarginRight' => array(
                'title' => 'Form Field Margin Right (PX)',
                'type' => 'number',
                'class' => '',
                'default' => '4',
                'desc_tip' => false,
            ),
            'frameButtonBGColor' => array(
                'title' => 'Button Background Color',
                'type' => 'color',
                'class' => '',
                'default' => '#d8d8d8',
                'desc_tip' => false,
            ),
            'frameButtonTextColor' => array(
                'title' => 'Button Text Color',
                'type' => 'color',
                'class' => '',
                'default' => '#000',
                'desc_tip' => false,
            ),
            'containerCardsHeight' => array(
                'title' => 'iFrame Container height',
                'type' => 'number',
                'class' => '',
                'default' => '150',
                'desc_tip' => true,
                'description' => 'To set height for card container.',
            ),
            'containerRgsHeight' => array(
                'title' => 'iFrame Container with save card checkbox height',
                'type' => 'number',
                'class' => '',
                'default' => '150',
                'desc_tip' => true,
                'description' => 'To set height for card container when save card checkbox is enabled.',
            ),
            'frameContainerHeightOffset' => array(
                'title' => 'iFrame Container height offset',
                'type' => 'number',
                'class' => '',
                'default' => '50',
                'desc_tip' => true,
                'description' => 'To set height offset for bottom space.',
            ),
        )
    ),
    'Modal Design' => array(
        'title'=>'Modal View Card Payments Design',
        'description'=>'<p>Design setting to match your theme.</p>',
        'fields'=> array(
            'modalFramePrimaryColor' => array(
                'title' => 'Background colour (Hex)',
                'type' => 'color',
                'class' => '',
                'default' => '#fcfcfc',
                'desc_tip' => false,
            ),
            'modalFrameAccentColor' => array(
                'title' => 'Card Storage Text Colour (Hex)',
                'type' => 'color',
                'class' => '',
                'default' => '#000000',
                'desc_tip' => false,
            ),
            'modalFramewpwlControlBackground' => array(
                'title' => 'Form Field Background Colour (Hex)',
                'type' => 'color',
                'class' => '',
                'default' => '#ffffff',
                'desc_tip' => false,
            ),
            'modalFramewpwlControlFontColor' => array(
                'title' => 'Form Field Text Colour (Hex)',
                'type' => 'color',
                'class' => '',
                'default' => '#000000',
                'desc_tip' => false,
            ),
            'modalFramewpwlControlBorderRadius' => array(
                'title' => 'Form Field Border Radius (PX)',
                'type' => 'number',
                'class' => '',
                'default' => '3',
                'desc_tip' => false,
            ),
            'modalFramewpwlControlBorderColor' => array(
                'title' => 'Form Field Border Colour (Hex)',
                'type' => 'color',
                'class' => '',
                'default' => '#e5e5e5',
                'desc_tip' => false,
            ),
            'modalFramewpwlControlBorderWidth' => array(
                'title' => 'Form Field Border Width (PX)',
                'type' => 'number',
                'class' => '',
                'default' => '1',
                'desc_tip' => false,
            ),
            'modalFramewpwlControlMarginRight' => array(
                'title' => 'Form Field Margin Right (PX)',
                'type' => 'number',
                'class' => '',
                'default' => '4',
                'desc_tip' => false,
            ),
            'modalFrameButtonBGColor' => array(
                'title' => 'Button Background Color',
                'type' => 'color',
                'class' => '',
                'default' => '#d8d8d8',
                'desc_tip' => false,
            ),
            'modalFrameButtonTextColor' => array(
                'title' => 'Button Text Color',
                'type' => 'color',
                'class' => '',
                'default' => '#000',
                'desc_tip' => false,
            ),
            'modalFrameProcessButtonBGColor' => array(
                'title' => 'Process Button Background Color',
                'type' => 'color',
                'class' => '',
                'default' => '#d8d8d8',
                'desc_tip' => false,
            ),
            'modalFrameProcessButtonTextColor' => array(
                'title' => 'Process Button Text Color',
                'type' => 'color',
                'class' => '',
                'default' => '#000',
                'desc_tip' => false,
            ),
            'modalFrameCancelButtonBGColor' => array(
                'title' => 'Cancel Button Background Color',
                'type' => 'color',
                'class' => '',
                'default' => '#d8d8d8',
                'desc_tip' => false,
            ),
            'modalFrameCancelButtonTextColor' => array(
                'title' => 'Cancel Button Text Color',
                'type' => 'color',
                'class' => '',
                'default' => '#000',
                'desc_tip' => false,
            ),
        )
    ),
    'FAQs' => array(
        'title'=>'Card Payments Plugin FAQs',
        'description'=>'<p>Frequently Asked Questions.</p>',
        'body' => 'showFaqs'
    ),
    'Logs' => array(
        'title'=>'Debugging Logs',
        'description' => '<p>To view details of debugging. '. (!($this->serversidedebug ?? false) ? 'Logging is currently disabled, please enable it <a href="'.admin_url( "/admin.php?page=wc-settings&tab=checkout&section=".TP_CONSTANTS::GATEWAY_ID."&opt=configuration#woocommerce_".TP_CONSTANTS::GATEWAY_ID."_serversidedebug" ).'">here</a>.' : '').'</p>',
        'body' => 'showLogs'
    ),
    'status' => array(
        'title'=>'Card Payments Plugin Status',
        'description'=>'<p>Debugging or sense check there are no <strong>critical</strong> <span style="color:#a00;"><span class="dashicons dashicons-warning"></span> Error(s)</span> before allowing for public processing.</p><p><strong>Minor</strong> <span style="color:#ffaf20;"><span class="dashicons dashicons-warning"></span> Warning(s)</span> will allow payment processing to continue.</p>',
        'body' => 'showStatus'
    )
);

return $tabsFieldsArray;

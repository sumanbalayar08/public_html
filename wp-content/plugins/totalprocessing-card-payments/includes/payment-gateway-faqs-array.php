<?php
use TotalProcessing\WC_TotalProcessing_Constants AS TP_CONSTANTS;
return [
    [
        'question' => "Raising and Issue with our support team.",
        'answer'   => [
            [
                'question' => "What should I do if the plugin doesn't work properly with my WooCommerce site?",
                'answer'   => "<p>Our plugin is designed to be compatible with a wide variety of sites right upon installation. However, due to the complex nature of WooCommerce integrations, conflicts may arise due to other plugins or unique site configurations. If you encounter any issues, please contact our support team for assistance.</p>",
            ],
            [
                'question' => "How do you handle troubleshooting for these issues?",
                'answer'   => "<p>In most cases, we will request access to a staging site—an exact copy of your production site—to safely investigate and address the issue. This approach protects your live site from any potential disruptions that might affect your visitors during troubleshooting.</p>",
            ],
            [
                'question' => "What if I cannot provide access to a staging site?",
                'answer'   => "<p>If providing a staging site isn’t feasible, we will still assist you to the best of our abilities. However, please be aware that without access to a staging site, our ability to thoroughly investigate and resolve the issue may be significantly limited.</p>",
            ],
        ],
    ],
    [
        'question' => "Can caching affect the gateway processing?",
        'answer'   => "<p>Sometimes extreme or unoptimized caching setups can affect the gateway and cause issues with processing. For best practices, and to avoid any potential issue with the Total Processing Payment plugin, we highly recommend that you exclude our plugin from all JS (Javascript) deferrals or delaying systems, and also set the checkout page to be uncacheable. If you require support doing this, we would suggest contacting your caching plugins support.</p>",
    ],
    [
        'question' => "How do I get my ID & Access Token?",
        'answer'   => "<p>In order to use the Total Processing Woocommerce plugin, you need to have an active account with Total Processing. Apply for an account please go to: www.totalprocessing.com</p><p>If you already have an account, this information can be found within your customer merchant backend.</p>",
    ],
    [
        'question' => "I can’t refund a user from the Woocommerece order page after upgrading?",
        'answer'   => "<p>Due to changes in our latest major update to our plugin, there may be a chance that previous plugins (or those installed manually) use a different structure to our newest iteration. Because of this change, the new plugin cannot issue refunds for those orders processed via an older, manually installed plugin.</p><p>In these circumstances, we would recommend leaving the old plugin installed alongside the new one for the duration of your store's personal refund period. After the last order on the old gateway has left that timeframe, you can safely remove the old plugin, and issue refunds via the new one as normal.</p>",
    ],
    [
        'question' => "My card checkout section isn’t showing, or showing a broken iframe?",
        'answer'   => "<p>If you’re seeing a broken iframe on your checkout page, then please log into your admin backend. You should see a notification at the top stating:</p><p>‘We've detected an issue with the Total Processing iframe page - please [Click here] to check the status page and regenerate’.</p><p>If you follow the link in the notification you will be taken to our plugins status page. Alongside the error in table for ‘iFrame Page’ you should see a button stating ‘Generate iFrame Page’. Clicking that will regenerate a new iFrame page and fix your issue.</p>",
    ],
    [
        'question' => "A customer is saying they paid, but no order has come through?",
        'answer'   => "<p>On an extremely rare occasion that you may find a customer stating they’ve paid, but you can’t find an order in your order dashboard - we would suggest that you log into the Total Processing backend, and search for a payment that matches their order number or billing name.</p>",
    ],
    [
        'question' => "How do I transfer saved cards to the new plugin?",
        'answer'   => "<p>If you have enables the saved cards functionality in previous plugins, you will need to transfer these over to this new plugin version. To do this, please visit <a href=\"".admin_url( "/admin.php?page=wc-settings&tab=checkout&section=".TP_CONSTANTS::GATEWAY_ID."&opt=status" )."\">here</a> and press <b>Sync</b> button - this will begin the quick migration, that will allow your users to continue to use their tokenized cards on your store. Please remember, that this process only works if you continue to use the same production credentials, it cannot be used to transfer card details between stores.</p>",
    ],
    [
        'question' => "Have more questions, or require additional support?",
        'answer'   => "<p>Mail us to <a href=\"mailto:support@totalprocessing.com\">support@totalprocessing.com</a></p>",
    ],
];

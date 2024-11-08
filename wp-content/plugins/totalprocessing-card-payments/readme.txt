=== Total processing card payments for WooCommerce ===
Contributors: BuiltByGo
Tags: woocommerce gateway, payments, payment gateway, apple pay, open banking
Requires at least: 5.2.0
Tested up to: 6.5.4
Stable tag: 7.0.3
Requires PHP: 7.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Accept Credit Cards and Debit Cards on your WooCommerce store.

== Description ==

Take Payments via Total Processing's Payment Gateway.
Accept Payments with the aid of 300 acquiring connectors based globally via our Gateway infrastructure.
Access extended oversight and transaction monitoring via the Total Processing plug-in for WooCommerce with our bespoke, data rich merchant services and people-centric support resources.

'Pioneering Payments'

= PAYMENT METHODS =

Credit/Debit cards:

* VISA
* MasterCard
* American Express

= FEATURES =

* All Major Credit/Cebit Card brands accredited with the PCI DSS standard.
* Tokenisation and 'Remember Me' options at the check-out.
* One-click Payments.
* iFrame for PCI DSS Compliance.
* Payment in Modal popup (can turn on/off from settings)
* Tech and Customer Support for Full and Partial Payment Refunds.
* 24/7 Support from Teams based in the UK.
* Worldwide Support from our Connectors and Partners.
* Support for all Currencies and Alternative Payment Methods across 196 Countries.

== Installation ==

1. Install the Plug-in via Plug-ins -> New Plug-in (Search for 'Totalprocessing_Card_Payments_And_Gateway_Woocommerce').
2. You Will be redirected to setup wizard.
3. Enter your Credentials as provided by Total Processing (Default Entity ID and Access token) and configure any settings needed.
4. If you are using Test Credentials use the 'Test' endpoint, otherwise, if you have a live MID with us, use the 'Live' endpoint.

== Changelog ==

= 7.0.3 - 02-09-2024 18:25 =

* Bug fix to resolve double payments via the subscription solution

= 7.0.2 - 21-08-2024 19:25 =

* Recurring parameter adjustment

= 7.0.1 - 02-07-2024 15:45 =

* Bug Fix - excludeFramePostId() function has been tweaked, to avoid order IDs being erroneously included in query results

= 7.0.0 - 20-06-2024 19:48 =

* Added compatibility support for the latest WooCommerce version.
* Included detailed instructions for raising issues in the FAQs section.
* Improved design compatibility with WooCommerce block features.
* Implemented compatibility fixes for HPOS.
* Enhanced the log system to save logs when an iframe loads.
* Improved error handling during final transaction validation.
* Fixed a query hook that was causing issues.
* Updated the FAQs section to display sub-items without affecting the main items.
* Added styling to display a loader in WooCommerce block features.

= 6.0.3 - 16-02-2024 19:48 =

* Bug Fix - Issue with Terms & Conditions field causing problems with card fields loading correctly

= 6.0.2 - 05-02-2024 13:36 =

* Bug Fix - Automatic refund is fixed
* Improvement - Input field is loading smoothly now

= 6.0.1 - 18-01-2024 22:30 =

* Bug fix - checking if woocommerce is added and active before validating payment on load of checkout page

= 6.0.0 - 17-01-2024 15:00 =

* Break plugin in to modules to allow for additional payment methods
* Open Banking solution added - TP direct
* Place order in hold if payment has not been confirmed by the bank,
* Add compatibility with Woocommerce's subscription plugin
* Standing Instructions Sequence implemented
* zero value checkout created
* Update card during subscription
* Option to use saved card with subscription product or add new card
* One Click payments - Standing instructions update 
* Add standing instruction parameters to one click payment transaction (when customer uses saved card to purchase product)
* Load in alteration
* Payment page font end updates
* Loading screen updates
* Style change for payment processing screen.

= 5.3.11 - 26-06-2023 18:20 =

* Bug Fix - optimise date range search for secondary payment check

= 5.3.10 - 23-06-2023 16:00 =

* Additional parameters for date range is added for payment 'Failsafe' implementation.

= 5.3.9 - 16-06-2023 17:10 =

* Minor Bug fix for payment 'Failsafe' implementation.

= 5.3.8 - 14-06-2023 21:42 =

* Resolved conflict with editing tooling.

= 5.3.7 – 02-06-2023 21:38 =

* Additional payment validation implementation to counteract payment response issues experiences on particular WP sites. Change creates a secondary failsafe on the page refresh to catch any session or cacheing issues

= 5.3.6 - 23-05-2023 17:30 =

* Pay Order page is working with TP modal design and default design

= 5.3.5 - 04-05-2023 18:30 =

* Optimized modal design
* More customization options for background/fonts/Buttons

= 5.3.4 - 24-04-2023 19:30 =

* Fixed static function issue for deactivation method

= 5.3.3 - 18-04-2023 19:30 =

* Optimized log system
* removed unwanted validations

= 5.3.2 - 29-03-2023 14:00 =

* Firefox autofill compatibility fixed

= 5.3.1 - 13-03-2023 18:36 =

* Placeholder for expiry format has been changed from MMYY to MM/YY
* Autocomplete attributes have been updated to the correct values

= 5.3.0 - 06-03-2023 11:30 =

* Payment Flow has been optimised, with new status check speeds on redirection
* Billing state has been removed from payload

= 5.2.36 - 22-02-2023 19:24 =

* Payment Status flow improvements - Status dynamically changed to Failed - Pending - success
* Status notes enhancements

= 5.2.29 - 22-06-2022 19:16 =

* Reduced timeout time for API request

= 5.2.28 - 14-06-2022 15:28 =

* Added more data to debug log
* Added alert message for enduser in case transaction lookup timeout or fails

= 5.2.27 - 12-06-2022 11:28 =

* Added filter to exclude tp js assets from siteground minimification

= 5.2.26 - 12-06-2022 10:50 =

* Fixes for js script for minification compatibility

= 5.2.25 - 12-06-2022 07:50 =

* Fixes for jQuery Conflict while loading inputs and js version

= 5.2.24 - 12-06-2022 07:40 =

* Fixes for jQuery Conflict while loading inputs.

= 5.2.23 - 10-06-2022 15:42 =

* Fixes for saved cards syncs.

= 5.2.22 - 10-06-2022 14:42 =

* Fixes for event updated_checkout.

= 5.2.21 - 04-06-2022 10:26 =

* Added more debug log to get more info.
* Added Option to choose modal for TP Payment, default will be embed.

= 5.2.20 - 02-06-2022 10:26 =

* Added more debug log to get more info.
* Added Payment dupe cronjob to verify timedout payment responses

= 5.2.19 - 01-06-2022 12:22 =

* Added more debug log to get more info.
* Sending correct plugin version as Shooper_plugin for better tracking
* Added more notices if check fails but still payment done.

= 5.2.18 - 30-05-2022 17:50 =

* Added more debug log to get more info.

= 5.2.17 - 30-05-2022 12:50 =

* Added back count of last six month order count for better payment risk evaluation.
* Added debug lines to evaluate processing time parsing.

= 5.2.16 - 24-05-2022 06:11 =

* Improved UI for smaller view port.
* Added new control in design tab to manage fields margin-right

= 5.2.15 - 05-05-2022 16:40 =

* Clean Dashboard.
* Added new tab for deign elements

= 5.2.14 - 15-04-2022 17:18 =

* Enhanced syncing saved cards with better http calling.

= 5.2.13 - 14-04-2022 14:18 =

* Enhanced syncing saved cards.

= 5.2.12 - 12-04-2022 11:16 =

* Enhanced log system - with a backend tab.

= 5.2.11 - 08-04-2022 14:40 =

* fix for js missing function scroll_to_notices.

= 5.2.10 - 06-04-2022 14:40 =

* fix for saved card sync.

= 5.2.9 - 30-03-2022 14:40 =

* fix for layout for mobile.

= 5.2.8 - 23-03-2022 18:15 =

* fix for amount number format.

= 5.2.7 - 16-03-2022 13:38 =

* removed param AccountPurchaseCount for speed optimization while processing payment.

= 5.2.6 - 16-03-2022 13:38 =

* JS optimization.

= 5.2.5 - 16-03-2022 13:38 =

* Calling delayed js to properly propogate.

= 5.2.4 - 16-03-2022 13:38 =

* Server side debug log toggle.
* js varibale name change.

= 5.2.3 - 15-03-2022 15:03 =

* Plugin id to new version.
* Iframe autogeneration if missing.
* Setting data sync.

= 5.2.1 - 15-03-2022 =

* Plugin id back to previous version for better sync.
* Iframe autogeneration if missing.

= 5.2 - 10-03-2022 =

* Plugin updated to improve stability and performance.
* Updated ready for 3DSv2.
* Fully restructured to avoid conflicts with old versions.
* Improved error & response messages, as well as global notifications.
* Improved error messaging when no response detected.
* New saved card migration system integrated
* Improved iframe detection & regeneration system implemented.
* New FAQ tab added.

= 5.1 - 17-11-2021 =

* Updated stored cards to drop cardholder name as length changes affect css positioning.
* Updated admin validation of accessToken to adopt single and double equals.

= 5.0 - 07-10-2021 =

* Full restructure
* Useage of window.postMessage() to avoid the need for redirect

= 4.5.1 - 05-06-2021 =

* Removal of payment modal
* Stock control (holds stock for 5 minutes - if no successful payment, releases stock using wp_cron)
* Correct order statuses (pending payment (transaction started), processing (payment complete), failed (failed validation or timed out and released stock))
* Bug fixes
* Prevention of blank API calls and reload on WooCommerce failed validation

= 4.4 - 04-14-2021 =

* Updated Payment popup

= 4.3 - 03-22-2021 =

* Added Payment recheck function in case of empty response from API.
* Updated Setup WIzard page

= 4.2 - 02-25-2021 =

* Updated Tags.


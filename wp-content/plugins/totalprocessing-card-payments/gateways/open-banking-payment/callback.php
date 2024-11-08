<?php
/** Make sure that the WordPress bootstrap has run before continuing. */
require __DIR__ . '/../../../../../wp-load.php';
$resourcePath      = $_GET['resourcePath'] ?? "";
$paymentID         = $_GET['id'] ?? "";
$orderID           = $_GET['order_id'] ?? 0;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta name="robots" content="noindex,nofollow">
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <link href="https://fonts.googleapis.com/css?family=Roboto:200,300,400,500,600,700" rel="stylesheet">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Content-Security-Policy" content="default-src * self blob: data: gap:; style-src * self 'unsafe-inline' blob: data: gap:; script-src * 'self' 'unsafe-eval' 'unsafe-inline' blob: data: gap:; object-src * 'self' blob: data: gap:; img-src * self 'unsafe-inline' blob: data: gap:; connect-src self * 'unsafe-inline' blob: data: gap:; frame-src * self blob: data: gap:;">

        <script src='<?php echo home_url('/wp-includes/js/jquery/jquery.min.js?ver=3.6.1');?>' id='jquery-core-js'></script>
        <script src='<?php echo home_url('/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.3.2');?>' id='jquery-migrate-js'></script>
        <title>Transaction confirmation</title>
        <style>
        html, body {
            height: 100%;
        }
        body {
            margin: 0;
        }
        .dot-bricks {
          position: relative;
          top: 8px;
          left: -9999px;
          width: 12px;
          height: 12px;
          border-radius: 2px;
          background-color: #BDBDBD;
          color: #BDBDBD;
          box-shadow: 9991px -16px 0 0 #BDBDBD, 9991px 0 0 0 #BDBDBD, 10007px 0 0 0 #BDBDBD;
          animation: dot-bricks 2s infinite ease;
        }
        @keyframes dot-bricks {
          0% {
            box-shadow: 9991px -16px 0 0 #BDBDBD, 9991px 0 0 0 #BDBDBD, 10007px 0 0 0 #BDBDBD;
          }
          8.333% {
            box-shadow: 10007px -16px 0 0 #BDBDBD, 9991px 0 0 0 #BDBDBD, 10007px 0 0 0 #BDBDBD;
          }
          16.667% {
            box-shadow: 10007px -16px 0 0 #BDBDBD, 9991px -16px 0 0 #BDBDBD, 10007px 0 0 0 #BDBDBD;
          }
          25% {
            box-shadow: 10007px -16px 0 0 #BDBDBD, 9991px -16px 0 0 #BDBDBD, 9991px 0 0 0 #BDBDBD;
          }
          33.333% {
            box-shadow: 10007px 0 0 0 #BDBDBD, 9991px -16px 0 0 #BDBDBD, 9991px 0 0 0 #BDBDBD;
          }
          41.667% {
            box-shadow: 10007px 0 0 0 #BDBDBD, 10007px -16px 0 0 #BDBDBD, 9991px 0 0 0 #BDBDBD;
          }
          50% {
            box-shadow: 10007px 0 0 0 #BDBDBD, 10007px -16px 0 0 #BDBDBD, 9991px -16px 0 0 #BDBDBD;
          }
          58.333% {
            box-shadow: 9991px 0 0 0 #BDBDBD, 10007px -16px 0 0 #BDBDBD, 9991px -16px 0 0 #BDBDBD;
          }
          66.666% {
            box-shadow: 9991px 0 0 0 #BDBDBD, 10007px 0 0 0 #BDBDBD, 9991px -16px 0 0 #BDBDBD;
          }
          75% {
            box-shadow: 9991px 0 0 0 #BDBDBD, 10007px 0 0 0 #BDBDBD, 10007px -16px 0 0 #BDBDBD;
          }
          83.333% {
            box-shadow: 9991px -16px 0 0 #BDBDBD, 10007px 0 0 0 #BDBDBD, 10007px -16px 0 0 #BDBDBD;
          }
          91.667% {
            box-shadow: 9991px -16px 0 0 #BDBDBD, 9991px 0 0 0 #BDBDBD, 10007px -16px 0 0 #BDBDBD;
          }
          100% {
            box-shadow: 9991px -16px 0 0 #BDBDBD, 9991px 0 0 0 #BDBDBD, 10007px 0 0 0 #BDBDBD;
          }
        }
        .flex-container {
            height: 100%;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .row {
            width: auto;
        }
        .flex-item {
            padding: 5px;
            width: 100%;
            height: auto;
            text-align: center;
        }
        </style>
    </head>
    <body>
        <div class="flex-container">
            <div class="row"> 
                <div class="flex-item">
                    <h3 style="font-family:Roboto;color:#b2a7a7;">Processing, Please Wait.<br />Do not refresh your browser.</h3>
                </div>
                <div class="flex-item">
                    <div style="text-align:center;display:flex;justify-content:center;padding-bottom:20px;">
                        <div class="dot-bricks"></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
        jQuery(document).ready(function($){
            var resourcePathVal    = "<?php echo $resourcePath; ?>";
            var resourceIDVal      = "<?php echo $paymentID; ?>";
            var order_id_val       = "<?php echo $orderID; ?>";
            var generalAlertMsg    = 'Uncertain Response. Please report this to the merchant before reattempting payment. They will need to verify if this transaction is successful.';
            postMessageToParent({funcs:[{"name":"validate_tp_openbanking_checkout","args": [resourceIDVal, resourcePathVal, order_id_val] }]});
        });
        //!!iFrame communication functions!!
        function postMessageToParent(obj){
            if(typeof window.CustomEvent === "function") {
                var event = new CustomEvent('parentLogForTPOpenBanking', {detail:obj});
            } else {
                var event = document.createEvent('Event');
                event.initEvent('parentLogForTPOpenBanking', true, true);
                event.detail = obj;
            }
            window.parent.document.dispatchEvent(event);
        }
        
        </script>
    </body>
</html>
<?php
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
if(isset($_GET['resourcePath'])){ global $post;?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="robots" content="noindex,nofollow">
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script src='<?php echo site_url('/wp-includes/js/jquery/jquery.min.js?ver=3.6.1');?>' id='jquery-core-js'></script>
    <script src='<?php echo site_url('/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.3.2');?>' id='jquery-migrate-js'></script>
    <title>Transaction confirmation</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
    .waiting-text{
      font-family: 'Roboto', sans-serif;
      text-align: center;
      color: #bdbdbd;
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
    </style>
</head>
<body>
<h3 class="waiting-text">Please wait while payment is processing.<br />Do not refresh or close the page.</h3>
<div style="text-align:center;display:flex;justify-content:center;padding-top:40px;padding-bottom:20px;"><div class="dot-bricks"></div></div>
<script>
jQuery(document).ready(function($){
    var resourcePathVal    = "<?php echo $_GET['resourcePath']; ?>";
    var order_id_val       = "<?php echo ($_GET['order_id'] ?? 0); ?>";
    var generalAlertMsg    = 'Error #if100: Uncertain Response. Please report this to the merchant before reattempting payment. They will need to verify if this transaction is successful.';
    Promise.resolve(
        $.ajax({
            type: 'POST',
            url: "<?php echo  admin_url('admin-ajax.php');?>",
            data: { action: 'tpcpv3_check_transaction_status', resourcePath: resourcePathVal, order_id: order_id_val},
            success: function(response) {
                console.log(response);
                if(response.hasOwnProperty("data")){
                    if( response.data.hasOwnProperty("valid") ){
                        console.log("response recieved");
                        postMessageToParent({funcs:[{"name":"validate_tp_cardsv2_checkout","args":[resourcePathVal, order_id_val]}]});
                    } else {
                        alert( "Error(#1):" + generalAlertMsg + "\n" + 'resource:' + resourcePathVal );
                        postMessageToParent({funcs:[{"name":"do_log_errors","args":[generalAlertMsg, order_id_val]}]});
                        $('body').trigger("update_checkout");
                        $('#tpCardsBtnReplace').remove();
                        $('#place_order').show();
                    }
                } else {
                    alert( "Error(#2):" + generalAlertMsg + "\n" + 'resource:' + resourcePathVal );
                    postMessageToParent({funcs:[{"name":"do_log_errors","args":[generalAlertMsg, order_id_val]}]});
                    $('body').trigger("update_checkout");
                    $('#tpCardsBtnReplace').remove();
                    $('#place_order').show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert( "Error(#3):" + generalAlertMsg + "\n" + 'Message::' + textStatus + '->' + errorThrown + "\n" + 'resource:' + resourcePathVal );
                postMessageToParent({funcs:[{"name":"do_log_errors","args":[generalAlertMsg, order_id_val]}]});
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            },
        })
    ).then(function(){
        //postMessageToParent({funcs:[{"name":"validate_tp_cardsv2_checkout","args":[resourcePathVal, order_id_val]}]});
    }).catch(function(e) {
        alert( "Error(#3):" + generalAlertMsg + "\n" + 'resource:' + resourcePathVal );
        console.log(e); 
    });
});
//!!iFrame communication functions!!
function postMessageToParent(obj){
    if(typeof window.CustomEvent === "function") {
        var event = new CustomEvent('parentLogV52', {detail:obj});
    } else {
        var event = document.createEvent('Event');
        event.initEvent('parentLogV52', true, true);
        event.detail = obj;
    }
    window.parent.document.dispatchEvent(event);
}

</script>
</body>
</html>
<?php } else { ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="robots" content="noindex,nofollow">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Transaction processing</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
    .waiting-text{
      font-family: 'Roboto', sans-serif;
      text-align: center;
      color: #bdbdbd;
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
    </style>
    <style id="tprootcss" type="text/css">:root {--main-primary-color: #2372ce;--main-accent-color: #7f54b3; --wpwl-control-background: transparent!important; --wpwl-control-border-radius: 0px!important; --wpwl-control-margin-right:4%;--wpwl-switch-buttons-background:#d8d8d8!important;--wpwl-switch-buttons-text-color:#000000!important;}</style>
    <style type="text/css">
    html, body, fieldset {margin:0!important; padding:0!important; background: var(--main-primary-color); font-family:'Helvetica Neue',Helvetica,Arial,sans-serif; color: var(--main-accent-color);}
    #cnpNav {display: flex;align-items: center;justify-content: center;margin-bottom: 20px;}
    #cnpNav button {font-size: small;margin: 5px;border-radius: 0.25em;font-weight: lighter;margin: 0.3125em;padding: 0.625em 1.1em;box-shadow: none;font-weight: 500;}
    #cnpNav button.cnpTarget{
        background: var(--wpwl-switch-buttons-background);
        border:1px solid var(--wpwl-switch-buttons-background);
        color: var(--wpwl-switch-buttons-text-color);
    }
    #wpwlDynBrand {width:30px; padding:11px; position: absolute; right: 0px; top: 0px;}
    #wpwlDynBrandImg {border-radius: unset; height: -webkit-fill-available; margin:0!important; float: right; max-height: 12px;}
    .form-row-first{float:left; width:48%!important;}
    .form-row-last{float:right; width:48%!important;}
    .form-row-wide{margin-bottom: 1.75rem; width:100%!important;}
    .wpwl-form input::placeholder{color: #CCC; font-size: 14px; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;}
    
            form.wpwl-form .form-row-first{float:left; width:48%!important;margin-right: var(--wpwl-control-margin-right)}
            form.wpwl-form .form-row-last{float:left; width:48%!important;margin-right:0!important;}
            form.wpwl-form .form-row-wide{margin-bottom: 1.75rem; width:100%!important;margin-right: 5px;float:left;}
            form.wpwl-form .form-row input::placeholder{color: #CCC; font-size: 14px; font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;}
    .wpwl-control-expiry {/*height: 2.125em;*/ height: 34px!important; border-radius:0px; box-shadow: none;-webkit-appearance: none; -moz-appearance: none; appearance: none;}
    .wpwl-group-brand, .wpwl-group-cardHolder, .wpwl-group-submit {display:none;}
    .wpwl-wrapper-registration-brand .wpwl-brand-inline {width:34px; height:34px; background:none;}
    .wpwl-wrapper-cardNumber, .wpwl-wrapper-expiry, .wpwl-wrapper-cvv {position:unset!important;}
    .wpwl-control-cardNumber {font-size: inherit; height: 34px; line-height: 34px;}
    .frameContainer {margin-top:2px; padding: 1rem 1.4rem 0;}
    .wpwl-form {max-width: 480px!important; margin-bottom:0!important;}
    div.wpwl-hint {font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size:x-small;color:var(--wpwl-control-font-color)!important;padding: 0 0 7px 7px!important;}
    input.wpwl-control-expiry:-webkit-input-placeholder {color:#CCC!important; font-size: 14px!important;}
    input.wpwl-control-expiry:-ms-input-placeholder {color:#CCC!important; font-size: 14px!important;}
    input.wpwl-control-expiry::placeholder {color:#CCC!important;}
    label.tpFrameLabel {color:#333; font-size: 11px; display: block; text-align: left; line-height: 140%; margin-bottom: 3px; max-width: 100%; font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;}
    span.tp-required {color:#FF0000;}
	.wpwl-form-has-inputs {
        padding: 0px!important;
        border: none!important;
        background-color: none!important;
        border-radius: 0px!important;
        -webkit-box-shadow: none!important;
        box-shadow: none!important;
	}
    #tpIframeRg {display: flex; align-items: center; margin:0; padding:0; border:none;}
    #tpIframeRg span {color:var(--main-accent-color)!important; font-size: smaller;}
    .wpwl-group-registration {
        margin: 0;
        padding: 0;
        border: none;
    }
    .wpwl-wrapper-registration{
	float:none!important;
    }

    .wpwl-wrapper-registration-brand{width: 20%!important;}
    
    label.wpwl-registration {
        display: flex;
        align-items: center;
    }
    .wpwl-wrapper-registration-number, .wpwl-wrapper-registration-expiry {
        padding-right: 10px;
    }
    .wpwl-wrapper-registration-details {
        margin-bottom: 0;
        font-size: small;
        display: flex;
        align-items: center;
        width: 70%;
    }
    div.wpwl-wrapper-registration-cvv {
        width: 34%!important;
    }
    .wpwl-group-registration.wpwl-selected {
        margin: 0;
        padding: 0;
        border: none;
    }
    .wpwl-group-registration.wpwl-selected div.wpwl-wrapper-registration-details {
		color: var(--main-accent-color);
	}
    div.wpwl-group {width:unset;background:var(--wpwl-control-background);color: var(--main-accent-color);border-radius:var(--wpwl-control-border-radius)!important;}
    .wpwl-container-registration div.wpwl-group{border:none!important;}
    div.wpwl-group.wpwl-group-registration {width:unset;background:var(--wpwl-primary-background);color: var(--main-accent-color);}
    .wpwl-control, .wpwl-control-expiry, .IFRAME_STYLE_1{
        width: 100%!important;
        text-align:left;
        background:var(--wpwl-control-background);
        color: var(--main-accent-color);
        border: var(--wpwl-control-border-width) solid var(--wpwl-control-border-color)!important;
        border-radius:var(--wpwl-control-border-radius)!important;
    }
    .wpwl-wrapper {
        width: 100%!important;
        border: none!important;
    }
    button#tpcards-container-change {
	/*display: block;*/
        width: 68%;
        max-width: 480px;
        border-width: 1px;
        border-style: solid;
        border-color: var(--main-accent-color);
        color: var(--main-accent-color);
        background-color: transparent;
        padding: 8px;
        font-size: small;
        font-weight: lighter;
        margin-bottom: 1rem;
    }
    @media only screen and (max-width: 480px) {
	.wpwl-control-cardNumber {font-size: inherit;}
	.wpwl-wrapper-registration-brand{display:none;}
        .wpwl-label-brand, .wpwl-wrapper-brand, .wpwl-wrapper-registration-registrationId, .wpwl-wrapper-registration-brand, .wpwl-wrapper-registration-number, .wpwl-wrapper-registration-expiry{padding-right: 6px;}
        div.wpwl-group-cardNumber {
            width:95%!important;
            float:left;
	}
        div.wpwl-wrapper-registration-cvv {
            width: 20%!important;
        }
        div.wpwl-group-expiry {
            width:44%!important;
            float:left;
        }
        div.wpwl-group-cvv {
            width:48%!important;
            float:left;
        }
    }
    .wpwl-control.wpwl-control-iframe.wpwl-control {
        padding-bottom: 0 !important;
        padding-top: 0 !important;
    }
    ::placeholder{
        color: var(--wpwl-control-font-color)!important;
    }
    ::-webkit-input-placeholder{
        color: var(--wpwl-control-font-color)!important;
    }
    :-ms-input-placeholder{
        color: var(--wpwl-control-font-color)!important;
    }
    </style>
</head>
<body id="frameBody">
<div id="cnpf" class="frameContainer"></div>
<div id="mcif" class="frameContainer" style="display:none;">
    <h3 class="waiting-text">Please wait while payment is processing.<br />Do not refresh or close the page.</h3>
    <div style="text-align:center;display:flex;justify-content:center;padding-top:40px;padding-bottom:20px;"><div class="dot-bricks"></div></div>        
</div>
<script>
var paymentContainer                     = 'wpwl-container-card';
var paymentContainerHeightOffset         = 50;
var cssConfigs                           = {};
var cartHasSubscription                  = 0;
//init statics
var containerCardsHeight                 = 150;
var containerRgsHeight                   = 150;
function getInternetExplorerVersion()
{
  var rv = -1;
  if (navigator.appName == 'Microsoft Internet Explorer')
  {
    var ua = navigator.userAgent;
    var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
      rv = parseFloat( RegExp.$1 );
  }
  else if (navigator.appName == 'Netscape')
  {
    var ua = navigator.userAgent;
    var re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
      rv = parseFloat( RegExp.$1 );
  }
  return rv;
}
//!!iFrame communication functions!!
function postMessageToParent(obj){
    if(typeof window.CustomEvent === "function") {
        var event = new CustomEvent('parentLogV52', {detail:obj});
    } else {
        var event = document.createEvent('Event');
        event.initEvent('parentLogV52', true, true);
        event.detail = obj;
    }
    window.parent.document.dispatchEvent(event);
}
function parentWindowComms(e){
    if(e.detail.hasOwnProperty('funcs')){
        for (var i = 0, len = e.detail.funcs.length; i < len; i++) {
            if(typeof window[e.detail.funcs[i].name] === 'function'){
                window[e.detail.funcs[i].name](e.detail.funcs[i].args);
            }
        }
    }
}
//!!end iFrame communication functions!!
function childFrameInit(){
    postMessageToParent({funcs:[{"name":"sendTpVarsObject","args":[true]}]});
}
function checkWpwlContainers(){
    if(jQuery(".wpwl-group-registration").length){
        console.log('reg found');
        jQuery(document).find('#cnpNav').append('<button type="button" class="cnpTarget" data-target="wpwl-container-registration"><i class="fa fa-shopping-bag"></i>&nbsp; Use saved card</button>');
        jQuery(document).find('#cnpNav').append('<button type="button" class="cnpTarget" data-target="wpwl-container-card"><i class="fa fa-credit-card"></i>&nbsp; New card</button>');
        jQuery('.wpwl-container-card').hide();
        $('input[name="standingInstruction.mode"]').val('REPEATED');
        $('input[name="standingInstruction.source"]').val('CIT');
        $('input[name="standingInstruction.type"]').val( ( cartHasSubscription === 1 ? 'RECURRING' : 'UNSCHEDULED' ) );
    } else {
        console.log('reg not found');
        jQuery(document).find('#cnpNav').empty();
        jQuery('.wpwl-container-card').show();
    }
}
function drawFormElementToPage(brands){
    console.log('drawing form.paymentWidgets element! =>' + brands);
    var savedCardsOptionsButtonsHTML = '<div id="cnpNav"></div>';
    jQuery('#cnpf').html( savedCardsOptionsButtonsHTML + '<form class="paymentWidgets" data-brands="' + brands + '"></form>' );
    
    jQuery('body').on('click','.cnpTarget', function(e) {
        e.preventDefault();
        paymentContainer = jQuery(this).data('target');
        jQuery('.wpwl-container').each(function() {
            if(jQuery(this).hasClass(paymentContainer)){
                jQuery(this).show();
                var doSaveCard     = $('#createRegistration').is(':checked');
                if( doSaveCard ){
                    $('input[name="standingInstruction.mode"]').val('INITIAL');
                    $('input[name="standingInstruction.source"]').val('CIT');
                    $('input[name="standingInstruction.type"]').val(  ( cartHasSubscription === 1 ? 'RECURRING' : 'UNSCHEDULED' ) );
                }else{
                    $('input[name="standingInstruction.mode"]').val('');
                    $('input[name="standingInstruction.source"]').val('');
                    $('input[name="standingInstruction.type"]').val('');
                }
            } else {
                jQuery(this).hide();
                $('input[name="standingInstruction.mode"]').val('REPEATED');
                $('input[name="standingInstruction.source"]').val('CIT');
                $('input[name="standingInstruction.type"]').val( ( cartHasSubscription === 1 ? 'RECURRING' : 'UNSCHEDULED' ) );
            }
        });
    }); 
}
function drawOppwaScriptToPage(platform_base,checkout_id){
    console.log('Loading: https://' + platform_base + '/v1/paymentWidgets.js?checkoutId=' + checkout_id + ' =>');
    var scriptElement = document.createElement( "script" );
 	scriptElement.onload = function() {
		console.log('Successfully loaded https://' + platform_base + '/v1/paymentWidgets.js?checkoutId=' + checkout_id + ' using (onload).');
	};
 	scriptElement.src = "https://" + platform_base + "/v1/paymentWidgets.js?checkoutId=" + checkout_id;
	document.body.appendChild( scriptElement );
}
function drawCssBlockToPage(cssText){
    //console.log('drawing css block to head');
    var tprootcss = document.getElementById("tprootcss");
    //console.log(tprootcss);
    tprootcss.innerHTML = cssText;
    //console.log('completed');
}
function genElemHight(delayms){
    setTimeout(function(){
        console.log('running genElemHight('+delayms+') =>');
        var elmnt = document.getElementById("frameBody");
        var height = (elmnt.offsetHeight + 16);
        postMessageToParent({funcs:[{"name":"tpSetFrameHeight","args":[height]}]});
    }, delayms);
}
function postNewFrameHeight(enforceHeight){
    if(enforceHeight === false){
        var frameContainer = document.querySelector('#frameBody');
        var ie  = getInternetExplorerVersion();
        var frameHeight   = ie == -1 || ie >= 11 ? frameContainer.offsetHeight : frameContainer.scrollHeight;
        console.log('calc new: ' + frameHeight);
        frameHeight = Math.round(frameHeight);
        if(frameHeight < 180){
            frameHeight = 250;
        } else {
            frameHeight = frameHeight + 28;
        }
        postMessageToParent({funcs:[{"name":"tpSetFrameHeight","args":[frameHeight]}]});
    } else {
        postMessageToParent({funcs:[{"name":"tpSetFrameHeight","args":[enforceHeight]}]});
    }
}
function initialiseCnp(args){
    console.log('initialiseCnp =>');
    console.log(args);
    if(typeof args === 'object'){
        if(args.length === 2){
            parentArgs = args[0];
            if(args[0]['autoFocusFrameCcNo'] === "1"){
                wpwlOptions.autofocus = 'card.number';
            }
            if(args[0]['createRegistration'] !== "1" || args[0]['loggedIn'] !== "1"){
                createRegistrationHtml = '';
            }
            cssConfigs = {'mainbgcolor': args[0]['frameCss']['framePrimaryColor'], 'fontcolor': args[0]['frameCss']['framewpwlControlFontColor']};
            var rootCss = ':root {--main-primary-color: '+args[0]['frameCss']['framePrimaryColor']+'; --main-accent-color: '+args[0]['frameCss']['frameAccentColor']+';--wpwl-control-background:'+args[0]['frameCss']['framewpwlControlBackground']+';--wpwl-control-font-color:'+args[0]['frameCss']['framewpwlControlFontColor']+';--wpwl-control-border-radius:'+args[0]['frameCss']['framewpwlControlBorderRadius']+'px;--wpwl-control-border-color:'+args[0]['frameCss']['framewpwlControlBorderColor']+';--wpwl-control-border-width:'+args[0]['frameCss']['framewpwlControlBorderWidth']+'px;--wpwl-control-margin-right:'+args[0]['frameCss']['framewpwlControlMarginRight']+'%!important;--wpwl-switch-buttons-background:'+args[0]['frameCss']['frameButtonBGColor']+'!important;--wpwl-switch-buttons-text-color:'+args[0]['frameCss']['frameButtonTextColor']+'!important;}';
            drawCssBlockToPage(rootCss);
            drawFormElementToPage(args[0]['brands']);
            drawOppwaScriptToPage(args[0]['platformBase'],args[1]);
            if(args[0]['cartHasSubscription'] === "1"){
                cartHasSubscription = 1;
                console.log("Cart has subscription");
            }
            if( args[0]['iframeContainerHeightOffset'] ){
                paymentContainerHeightOffset = args[0]['iframeContainerHeightOffset'];
                console.log("Cart has paymentContainerHeightOffset");
            }
            if( args[0]['containerCardsHeight'] ){
                containerCardsHeight = args[0]['containerCardsHeight'];
            }
            if( args[0]['containerRgsHeight'] ){
                containerRgsHeight = args[0]['containerRgsHeight'];
            }
        }
    }
}
function switchContainer(selectedContainer){
    var nodeList = document.querySelectorAll(".wpwl-container");
    for (var i = 0, len = nodeList.length; i < len; i++) {
        if(nodeList[i].classList.contains(selectedContainer) === true){
            nodeList[i].style.display = "block";
            console.log('switchContainerCalc');
            postNewFrameHeight(false);
        } else {
            nodeList[i].style.display = "none";
            console.log('switchContainerCalc');
            postNewFrameHeight(false);
        }
    }
    paymentContainer = selectedContainer;
}
function adjRgState(argArray){
    var rgText = document.getElementById('tpIframeRg');
    var rgCheckbox = document.getElementById('createRegistration');
    if(typeof(rgText) !== 'undefined' && rgText !== null){
        if(typeof(rgCheckbox) !== 'undefined' && rgCheckbox !== null){
            if(argArray[0] === true){
                rgText.style.display = "block";
                rgCheckbox.disabled = false;
                console.log('adjRgStateCalc');
                postNewFrameHeight(false);
            } else {
                rgText.style.display = "none";
                rgCheckbox.disabled = true;
                console.log('adjRgStateCalc');
                postNewFrameHeight(false);
            }
        }
    }
}
function addRemClassSelector(elemSelector,classAdd,classRemove){
    var elem = document.querySelector(elemSelector);
    for (var i = 0, len = classAdd.length; i < len; i++) {
        elem.classList.add(classAdd[i]);
    }
}
function genHeightCalculations(){
    if(parentArgs.createRegistration === '1'){
        containerCardsHeight  = parseInt( containerCardsHeight );
        paymentContainerHeightOffset  = parseInt( paymentContainerHeightOffset );
        containerCardsHeight += paymentContainerHeightOffset;
        if(jQuery('form.wpwl-form-registrations').length > 0){
            containerCardsHeight += paymentContainerHeightOffset;
            containerRgsHeight  = parseInt( containerRgsHeight );
            containerRgsHeight += parseInt(jQuery('.wpwl-group-registration').length * paymentContainerHeightOffset);
        }
    }
    console.log('iframe height offset =>' + paymentContainerHeightOffset);
    console.log('iframe height =>' + containerCardsHeight);
    if(jQuery('.wpwl-group-registration').length){
        console.log('iframe with reg height offset =>' + containerRgsHeight);
        postMessageToParent({funcs:[{"name":"tpSetFrameHeight","args":[parseInt(containerRgsHeight)]}]});
    } else {
        console.log('iframe without reg height offset =>' + containerCardsHeight);
        postMessageToParent({funcs:[{"name":"tpSetFrameHeight","args":[parseInt(containerCardsHeight)]}]});
    }
}
//wpwl functions
function executePayment(argArray){
    console.log('exec now->');
    console.log(jQuery('input[name="card.holder"].wpwl-control-cardHolder').val());
    jQuery('input[name="card.holder"].wpwl-control-cardHolder').val('');
    console.log(jQuery('input[name="card.holder"].wpwl-control-cardHolder').val());
    wpwl.executePayment(paymentContainer);
}
function addInlineImgPerBrand(brand){
	//console.log('adding brand image for: ' + brand);
	var selectorString = 'div.wpwl-brand.wpwl-brand-'+brand+'.wpwl-brand-inline';
	var elems = jQuery(selectorString);
	if(elems.length){
		jQuery.each( elems, function(i,c) {
			if(jQuery(c).children().length === 0) {
				jQuery(c).html('<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ).'assets/img/'; ?>' + brand + '-inline.svg" width="34" height="34">');
			}
		});
	}
}
//init dynamics
var parentArgs = {};
var rgBranding = true;
var standingInstructionsInputs = '<input type="hidden" name="standingInstruction.type" value="" /><input type="hidden" name="standingInstruction.mode" value="" /><input type="hidden" name="standingInstruction.source" value="" />';
var createRegistrationHtml = `<br style="clear:both;" /><p id="tpIframeRg">
<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createRegistration" name="createRegistration" value="true"><span>&nbsp;&nbsp;Securely store this card?</span></p>
${standingInstructionsInputs}<br style="clear:both;" />`;
var wpwlOptions = {
    registrations: {
        requireCvv: true,
        hideInitialPaymentForms: false
    },
    iframeStyles: {
        'card-number-placeholder': {
            'color': '#CCC',
            'font-size': '14px',
            'font-family': 'Helvetica'
        },
        'cvv-placeholder': {
            'color': '#CCC',
            'font-size': '14px',
            'font-family': 'Helvetica'
        }
    },
    labels: {
        cardNumber: 'Card Number',
        mmyy: 'MM/YY',
        cvv: 'CVV'
    },
    errorMessages: {
        cardNumberError: 'Invalid card number',
        expiryMonthError: 'Invalid exp.',
        expiryYearError: 'Invalid exp.',
        cvvError: 'Invalid cvv'
    },
    style: 'plain',
    paymentTarget: 'myCustomIframe',
    shopperResultTarget: 'myCustomIframe',
    disableSubmitOnEnter: true,
    showLabels: false,
    brandDetection: true,
    brandDetectionType: 'binlist',
    requireCvv: true,
    showCVVHint: false,
    onReady: function() {
        addRemClassSelector('.wpwl-group-cardNumber',["form-row","form-row-wide"],[]);
        addRemClassSelector('.wpwl-group-expiry',["form-row","form-row-first"],[]);
        addRemClassSelector('.wpwl-group-cvv',["form-row","form-row-last"],[]);
        genHeightCalculations();
        jQuery('.wpwl-control-expiry').attr('autocomplete', 'cc-exp');
        
        if( cartHasSubscription === 1 ){
            createRegistrationHtml = `<br style="clear:both;" /><p id="tpIframeRg">
                <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createRegistration" checked="checked" name="createRegistration" value="true" style="display:none">
                <span><b>Important Notice:</b> You're purchasing a subscription item. By proceeding, you agree to have your payment method saved for future recurring charges related to this subscription.</span></p>
                <input type="hidden" name="standingInstruction.type" value="RECURRING" /><input type="hidden" name="standingInstruction.mode" value="INITIAL" /><input type="hidden" name="standingInstruction.source" value="CIT" /><br style="clear:both;" />`;
        }
        jQuery('form.wpwl-form-card').find('.wpwl-group-cvv').after(createRegistrationHtml);
        jQuery('form.wpwl-form-registrations').append(standingInstructionsInputs);
        jQuery('#createRegistration').on('click', function(e) {
            var doSaveCard     = $(this).is(':checked');
            if( doSaveCard ){
                $('input[name="standingInstruction.mode"]').val('INITIAL');
                $('input[name="standingInstruction.source"]').val('CIT');
                $('input[name="standingInstruction.type"]').val( ( cartHasSubscription === 1 ? 'RECURRING' : 'UNSCHEDULED' ) );
            }else{
                $('input[name="standingInstruction.mode"]').val('');
                $('input[name="standingInstruction.source"]').val('');
                $('input[name="standingInstruction.type"]').val('');
            }
            //postMessageToParent({funcs:[{"name":"tpSetSaveCardStatus","args":[doSaveCard]}]});
        });
        checkWpwlContainers();
        jQuery('#tpcards-container-change').on('click', function(e) {
            e.preventDefault();
        });
        console.log('wpwl onReady');
        window.wpwl.checkout.config.customRedirectPageConfig.backgroundColor = cssConfigs.mainbgcolor;
        window.wpwl.checkout.config.customRedirectPageConfig.hyperlinkFontColor = cssConfigs.fontcolor;
        window.wpwl.checkout.config.customRedirectPageConfig.messageFontColor = cssConfigs.fontcolor;
        //console.log(window.wpwl.checkout.config.customRedirectPageConfig);
    },
    onReadyIframeCommunication: function(){
        console.log('wpwl iframe communication started');
        postMessageToParent({funcs:[{"name":"showCardContaner","args":['dohideloader']}]});
        var iframeField = this.$iframe[0];
        if(iframeField.parentNode.classList.contains('wpwl-wrapper-registration') === true){
            jQuery('.wpwl-container-card').hide();
            jQuery('#tpcards-form-controller').show();
            paymentContainer = 'wpwl-container-registration';
            if(rgBranding === true){
                //console.log('branding req.');
                var brandsArray = (parentArgs.brands).split(" ");
                jQuery.each( brandsArray, function(i,b) {
                    addInlineImgPerBrand(b);
                });
                rgBranding = false;
            }
        }
        if(iframeField.classList.contains('wpwl-control-cardNumber') === true){
            var ccNoContainer = iframeField.parentNode;
            var brandContainer = document.createElement('div');
            brandContainer.id="wpwlDynBrand";
            var dynBrandImg = document.createElement('img');
            dynBrandImg.id="wpwlDynBrandImg";
            dynBrandImg.src='<?php echo plugin_dir_url( dirname( __FILE__ ) ).'assets/img/'; ?>' + 'default.svg';
            brandContainer.appendChild(dynBrandImg);
            ccNoContainer.appendChild(brandContainer);
        }
        console.log('wpwl iframe communication ended');
    },
    onFocusIframeCommunication: function(){
        //var parentEl = this.$iframe[0].parentNode;
        //genElemHight(750);
    },
    onBlurIframeCommunication: function(){
        //var parentEl = this.$iframe[0].parentNode;
    },
    onBlurCardNumber: function(isValid){ 
        //console.log(isValid);
    },
    onDetectBrand: function(brands){
        var dynBrandImgSrc;
        if(brands.length > 0){
            dynBrandImgSrc = '<?php echo plugin_dir_url( dirname( __FILE__ ) ).'assets/img/'; ?>' + brands[0] + '.svg';
        } else {
            dynBrandImgSrc = '<?php echo plugin_dir_url( dirname( __FILE__ ) ).'assets/img/'; ?>' + 'default.svg';
        }
        document.getElementById('wpwlDynBrandImg').src = dynBrandImgSrc;
    },
    onBeforeSubmitCard: function(event){
        console.log('onBeforeSubmitCard');
        postMessageToParent({funcs:[{"name":"tpSetFrameHeight","args":[containerCardsHeight]}]});
        //postMessageToParent( { funcs: [ { "name": "tpSetParentBlockUI", "args": [300] } ] } );
        return true;
    },
    onAfterSubmit: function(){
        //document.getElementById('payment').style.display = "none";
        console.log('onAfterSubmit');
        postMessageToParent({funcs:[{"name":"progress_tp_cardsv2","args":[true]}]});
        if(Object(window.parent.document.getElementById('tp_cardsv2_checkout_id')) === window.parent.document.getElementById('tp_cardsv2_checkout_id')){
            console.log('clearing checkout_id');
            window.parent.document.getElementById('tp_cardsv2_checkout_id').value = "";
        }
        jQuery('#cnpf').hide();
        jQuery('#mcif').show();
        postMessageToParent({funcs:[{"name":"tpSetFrameHeight","args":[580]}]});
        return true;
    },
    onLoadThreeDIframe: function(){
        console.log('onLoadThreeDIframe');
        postMessageToParent({funcs:[{"name":"tpSetFrameHeight","args":[580]}]});
    },
    onError: function(error){
        console.log('error::'+error);
        if (error.name === "InvalidCheckoutIdError") {
            console.log('refresh child frame');
            childFrameInit();
        } else if (error.name === "WidgetError") {
            console.log("here we have extra properties: ");
            console.log(error.brand + " and " + error.event);
            childFrameInit();
        }
        // read the error message
		//postMessageToParent({funcs:[{"name":"tpUnSetParentBlockUI","args":[300]}]});
		postMessageToParent({funcs:[{"name":"tpSetFrameHeight","args":[300]}]});
    }
};
//init event listeners
window.document.addEventListener('frameLogV52', parentWindowComms, false);
window.addEventListener('message', function(e) {
    var decoded = false;
    if(e.origin === "<?php echo ( is_ssl() ? 'https' : 'http' );?>://<?php echo $_SERVER['HTTP_HOST']; ?>"){
        try {
            decoded = JSON.parse(e.data);
        } catch(e) {
            decoded = false;
        }
        console.log(decoded);
        if(decoded !== false){
            if(decoded.hasOwnProperty("channel")){
                if(decoded.channel === 'resourcePath'){
                    if(decoded.hasOwnProperty("respath")){
                        //console.log(decoded.data);
                        postMessageToParent({funcs:[{"name":"validate_tp_cardsv2_checkout","args":[decoded.respath, decoded.orderid]}]});
                    }
                }
            }
        }
    }
});
jQuery(document).ready(function() {
    console.log('child frame doc.ready');
    //console.log('domain: ' + '<?php echo ( is_ssl() ? 'https' : 'http' );?>://<?php echo $_SERVER['HTTP_HOST']; ?>');
    childFrameInit();
});
</script>
</body>
</html>
<?php } ?>

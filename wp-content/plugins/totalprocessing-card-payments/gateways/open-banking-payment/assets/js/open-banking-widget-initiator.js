( function ( tp, $ ) {
    'use strict';

    var tpopenbankVars                      = {};
    var tpOpenbankPaymentInProgress         = false;
    var globalPrefix                        = '';
    var iframeContainerID                   = globalPrefix + 'iframe_container';
    var cardIframeID                        = globalPrefix + 'cnpFrame';
    var generalAlertMsg                     = 'Uncertain Response. Please report this to the merchant before reattempting payment. They will need to verify if this transaction is successful.';
								            
    const wc_cardsv2Fe_callback             = function(mutationsList, observer) {
    	for (var i = 0, len = mutationsList.length; i < len; i++) {
    		if(mutationsList[i].type === 'childList') {
    			if(jQuery('#cnpWpwl .wpwl-message').length > 0) {
    				var msg = jQuery('#cnpWpwl .wpwl-message').html();
    				msg = msg.split('<br>');
    				msg = msg.join(' ');
    				cardsv2FeLog(msg,'critical');
    			} else if(jQuery('.wpwl-container .wpwl-message').length > 0) {
    				var msg = jQuery('.wpwl-message').html();
    				msg = msg.split('<br>');
    				msg = msg.join(' ');
    				cardsv2FeLog(msg,'critical');
    			}
            }
        }
    };
    const wc_cardsv2Fe_observer   = new MutationObserver(wc_cardsv2Fe_callback);

    function cardsv2Log(obj){
        if( parseInt( tpopenbankVars.jsLogging ) === 1 ){
	        var e = new Error(obj);
            console.log(obj);
            console.log(e.stack);
        }
    }

    function logToParentWindow( e ){
        if( e.detail.hasOwnProperty( 'funcs' ) ){
            for ( var i = 0, len = e.detail.funcs.length; i < len; i++ ) {
                try{
                    let tempfunc    = eval( e.detail.funcs[ i ].name );
                    tempfunc(e.detail.funcs[i].args); 
                }catch( err ){
                    cardsv2Log( err );
                }
            }
        }
    }

    function validate_tp_openbanking_checkout( args ){
        Promise.resolve(
            $.ajax({
                type: 'POST',
                url: wc_checkout_params.ajax_url,
                data: { action: 'wc_tp_openbanking_check_transaction_status', paymentid: args[0], resourcePath: args[1], order_id: args[2]},
                success: function(response) {
                    console.log(response);
                    if(response.hasOwnProperty("data")){
                        if( response.data.hasOwnProperty("valid") ){
                            console.log("response recieved");
                            console.log(response);
                            if(response.data.valid === false){
                                location.reload();
                            }else if( response.data.hasOwnProperty('responseResult') && response.data.responseResult.hasOwnProperty('redirect') ){
                                location.href = response.data.responseResult.redirect;
                            }
                        } else {
                            alert( "Error(#1):" + generalAlertMsg + "\n" + 'resource:' + args[1] );
                            $('body').trigger("update_checkout");
                            $('#tpCardsBtnReplace').remove();
                            $('#place_order').show();
                        }
                    } else {
                        alert( "Error(#2):" + generalAlertMsg + "\n" + 'resource:' + args[1] );
                        $('body').trigger("update_checkout");
                        $('#tpCardsBtnReplace').remove();
                        $('#place_order').show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert( "Error(#3):" + generalAlertMsg + "\n" + 'Message::' + textStatus + '->' + errorThrown + "\n" + 'resource:' + args[1] );  
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
            })
        ).then(function(){
        }).catch(function(e) {
            alert( "Error(#3):" + generalAlertMsg + "\n" + 'resource:' + args[1] );
            console.log(e); 
        });
    }

    function fetchOrderTpCardsModalVersion(endpoint,checkoutData){
        cardsv2Log('start the fetch && modal up');
    	cardsv2Log(checkoutData);
        jQuery('#place_order').after('<p id="tpCardsBtnReplace" style="color:#CCC; text-align:center;">Processing, please wait...</p>');
        jQuery('#place_order').hide();
        fetch(endpoint , {
            method:'POST', 
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            cache: 'no-cache',
            body: checkoutData
        }).then(function(response){
            cardsv2Log('stage 1: POST');
            return response.json();
        }).then(function(json){
            cardsv2Log('stage 2: json parsing');
            cardsv2Log(json);
            if(json.hasOwnProperty('result')){
                if(json.result === 'success'){
                    if(json.redirect !== false){
                        if(-1 === (json.redirect).indexOf('https://') || -1 === (json.redirect).indexOf('http://') ) {
                            window.location = decodeURI(json.redirect);
                        } else {
                            window.location = (json.redirect);
                        }
                    }
                }
            }
            return json;
        }).then(function(json){
            cardsv2Log('stage 3: clear notices');
            jQuery('.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message').remove();
            return json;
        }).then(function(json){
            cardsv2Log('stage 4: if messages add');
            if(json.hasOwnProperty('messages')){
                jQuery('form.checkout').prepend('<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + json.messages + '</div>');
            }
            return json;
        }).then(function(json){
            cardsv2Log('stage 5: refresh/reload stage if true');
            document.querySelector('form.woocommerce-checkout').classList.remove('processing');
            if(json.reload !== false){
                window.location.reload();
            } else if(json.refresh !== false){
                jQuery('body').trigger("update_checkout");
                jQuery('#tpCardsBtnReplace').remove();
                jQuery('#place_order').show();
    			json.result = 'failure';
    			return json;
            }
            return json;
        }).then(function(json){
            cardsv2Log('stage 6: decision logic');
            if(json.result === 'failure'){
                var err = jQuery(".woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout");
                if(err.length > 0 || (err = jQuery(".form.checkout"))){
                    jQuery.scroll_to_notices(err);
                }
                jQuery('form.checkout').find(".input-text, select, input:checkbox").trigger("validate").blur();
                jQuery('body').trigger("checkout_error");
                jQuery('#tpCardsBtnReplace').remove();
                jQuery('#place_order').show();
            } else if(json.hasOwnProperty('pending')){
                return json;
            }
            return false;
        }).then(function(json){
            if(json === false) return false;
            cardsv2Log('stage 7: unload if found');
            if(window.wpwl !== undefined && window.wpwl.unload !== undefined) {
                window.wpwl.unload();
                jQuery('script').each(function () {
                    if (this.src.indexOf('static.min.js') !== -1) {
                        jQuery(this).remove();
                    }
                });
            }
            return json;
        }).then(function(res){
            if(res === false) return;
            cardsv2Log('stage 9: swal');
            Swal.fire({
                //padding: '1px 0 1px 0',
                titleText: '',
                html: '<iframe id="'+iframeContainerID+'" class="tpOpenbankPaymentFrame" src="'+res.frameurl+'"></iframe>',
                allowOutsideClick: true,
                allowEscapeKey: true,
                allowEnterKey: false,
                showConfirmButton: false,
                showCancelButton: false,
                showCloseButton: true,
                focusConfirm: false,
                //cancelButtonText: 'Cancel',
                /*customClass: {
                    header:'padBottom',
                    footer:'flexFootTp'
                },*/
                didOpen: function(){
                    cardsv2Log('didOpen');
    				var wc_cardsv2Fe_cnpSwal = document.getElementById(iframeContainerID);
    				wc_cardsv2Fe_observer.observe(wc_cardsv2Fe_cnpSwal, {childList: true, subtree: true});
    				jQuery('.swal2-popup').show();
                    //Swal.showLoading(Swal.getConfirmButton());
                },
    			didRender: function(){
    				cardsv2Log('didRender');
    			},
                preConfirm: function(){
                    return true;
                }
            }).then(function(result){
                if(result.isDismissed === true) {
    				wc_cardsv2Fe_observer.disconnect();
                    jQuery('#tpCardsBtnReplace').remove();
                    jQuery('#place_order').show();
                }
            });
        });
    }

    tp.tpCardsHandoff = function() {
        console.log('handsoff');
        if(jQuery('form.woocommerce-checkout').find('input[name^="payment_method"]:checked').val() === tpopenbankVars.pluginId){
            var checkoutData = jQuery("form.woocommerce-checkout").serialize();
    		if(tpopenbankVars.slickOneClick === '1'){
    			if( jQuery('form.woocommerce-checkout').find('input[name^="payment_method"]:checked').data("registrationid") !== undefined ) {
    				checkoutData += '&registrationId=' + jQuery('form.woocommerce-checkout').find('input[name^="payment_method"]:checked').data("registrationid");
    			}
    		}
        	document.querySelector('form.woocommerce-checkout').classList.add('processing');
        	fetchOrderTpCardsModalVersion(wc_checkout_params.checkout_url,checkoutData);
    		return false;
        }
    	return;
    };
    
    tp.init = function( options ){
        tpopenbankVars              = options;
        globalPrefix                = options.pluginPrefix;
        iframeContainerID           = globalPrefix + 'iframe_container';
        cardIframeID                = globalPrefix + 'cnpFrame';
        window.document.addEventListener('parentLogForTPOpenBanking', logToParentWindow, false);
        cardsv2Log('checkout endpoint docReady!');
        cardsv2Log(tpopenbankVars.pluginId + ' v.' + tpopenbankVars.pluginVer);
    }
} )( window.wc_gateway_tpopenbank = window.wc_gateway_tpopenbank || {}, jQuery );

jQuery( function(){
    var tpGlobalVars    = gettpopenbankGlobalVariable();
    wc_gateway_tpopenbank.init( tpGlobalVars );
    var checkout_form = jQuery( 'form.woocommerce-checkout' );
    checkout_form.on( 'checkout_place_order', wc_gateway_tpopenbank.tpCardsHandoff );
    jQuery('body').on('click','.cnpTarget', function(e) {
        e.preventDefault();
        var targetContainer = jQuery(this).data('target');
        jQuery('.wpwl-container').each(function() {
            if(jQuery(this).hasClass(targetContainer)){
                jQuery(this).show();
            } else {
                jQuery(this).hide();
            }
        });
    }); 
});

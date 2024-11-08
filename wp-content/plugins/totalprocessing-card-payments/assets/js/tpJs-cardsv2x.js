( function ( tp, $ ) {
    'use strict';

    tp.tpCardVars                 = {};
    var tpCardsInProgress         = false;
    var globalPrefix              = '';
    var cardIframeContainerID     = '';
    var cardLoaderContainerID     = '';
    var cardIframeID              = '';

    function scroll_to_notices( scrollElement ) {
        var offset = 300;
        if ( scrollElement.length ) {
            $( 'html, body' ).animate( {
                scrollTop: ( scrollElement.offset().top-offset )
            }, 1000 );
        }
    }

    function cardsv2Log(obj){
        if( parseInt( tp.tpCardVars.jsLogging ) === 1 ){
	        var e = new Error(obj);
            console.log(obj);
            console.log(e.stack);
        }
    }

    function childFramePost( iFrameId, obj ){
        var iFrame            = document.getElementById( iFrameId );
        var iFrameDoc         = (iFrame.contentWindow || iFrame.contentDocument);
        if( iFrameDoc.document ){
            iFrameDoc         = iFrameDoc.document;
        }

        var event;
        if( typeof window.CustomEvent === "function" ) {
            event             = new CustomEvent( 'frameLogV52', { detail: obj } );
        } else {
            event             = document.createEvent('Event');
            event.initEvent('frameLogV52', true, true);
            event.detail = obj;
        }
        iFrameDoc.dispatchEvent(event);
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
                /*if( typeof tp.{e.detail.funcs[ i ].name} === 'function' ){
                    tp.{e.detail.funcs[ i ].name}( e.detail.funcs[i].args );
                } else {
                    cardsv2Log( e.detail.funcs[ i ].name );
                }*/
            }
        }
    }

    function showCardContaner( args ){
        $( '#' + cardLoaderContainerID ).hide();
        $( '#' + cardIframeContainerID ).show();
    }

    function sendTpVarsObject(args){
        cardsv2Log('sendTpVarsObject => args:');
        var obj = {funcs:[{name:"initialiseCnp", args:[tp.tpCardVars,$('#'+globalPrefix+'checkout_id').val()]}]};
        cardsv2Log(obj);
        childFramePost( cardIframeID, obj );
    }

    function chkCreateAccField(args){
        var nodeList = document.getElementsByName("createaccount");
        if(nodeList.length > 0){
            var obj = {funcs:[
                {name:"adjRgState",args:[$('input[name^="createaccount"]').is(":checked")]}
            ]};
            childFramePost( cardIframeID, obj );
        }
    }

    function tpSetFrameHeight(args){
        if(typeof args[0] === 'number'){
            if(args[0] > 0) {
                //cardsv2Log(args[0] + 'px');
                document.getElementById( cardIframeID ).style.height = args[0] + 'px';
            }
        }
    }

    function tpSetParentBlockUI(args){
        $('body').block({
            message: '<p class="text-align:center">Please wait till payment is processing.<br />It may take some time to process.<br />Don\'t refresh or close or hit back.</p>',
            overlayCSS: {
                background:  '#fff',
                opacity:     1
            },
            css: {
                width:       '50%',
                border:      'none',
                //cursor:      'wait',
                opacity:     1
            }
        });
    }

    function tpUnSetParentBlockUI(args){
        $.unblockUI;
    }

    function pciFormSubmit(iFrameId,paymentContainer){
        cardsv2Log('executing wpwl! =>');
        var iFrame = document.getElementById(iFrameId);
        var iFrameDoc = (iFrame.contentWindow || iFrame.contentDocument);
        if (iFrameDoc.document) iFrameDoc = iFrameDoc.document;
        var obj = {funcs:[
            {name:"executePayment",args:[paymentContainer]}
        ]};
        var event;
        if(typeof window.CustomEvent === "function") {
            event = new CustomEvent('frameLogV52', {detail:obj});
        } else {
            event = document.createEvent('Event');
            event.initEvent('frameLogV52', true, true);
            event.detail = obj;
        }
        iFrameDoc.dispatchEvent(event);
    }

    function unloadWpwlTpCardsv2(){
        if (window.wpwl !== undefined && window.wpwl.unload !== undefined) {
            window.wpwl.unload();
            $('script').each(function () {
                if (this.src.indexOf('static.min.js') !== -1) {
                    $(this).remove();
                }
            });
        }
        $('#tp_alt_cnp_container').empty();
    }

    function instantiateCheckoutIdOrder(){
        cardsv2Log('instantiateCheckoutIdOrder => running!');
        tpCardsInProgress = false;
        $.ajax({
            type: 'post',
            dataType : 'json',
            url: tp.tpCardVars.adminUrl,
            data : {action: globalPrefix + "requestOrderCheckoutId"},
            success: function(response){
                cardsv2Log(response);
                if(response.success === true) {
                    $( '#' + cardIframeContainerID ).empty();
                    $( '#' + globalPrefix + 'checkout_id').val( response.data.uuid );
                    cardsv2Log('set the uuid to: ' + response.data.uuid);
                    $( '#' + cardIframeContainerID ).html('<iframe id="' + cardIframeID + '" name="myCustomIframe" src="'+response.data.frameurl+'?v='+Date.now()+'" style="width: 100%; height:80px; border: none;"></iframe>');
                }
            }
        });
    }

    tp.finalOrderPaymentProcess = function( response ){
        if(tpCardsInProgress === true){
            cardsv2Log('form in 3d progress!!');
            return;
        }
        tpCardsInProgress = false;
        cardsv2Log('final payment process for order:');
        cardsv2Log(response);
        pciFormSubmit(cardIframeID,"wpwl-container-card");
    }

    tp.initCheckoutIdOrder = function(){
	    if($('iframe#' + cardIframeID).length === 0){
            cardsv2Log('Checkout Iframe is initiated');
            instantiateCheckoutIdOrder();
        }
    }

    function completeOrderTpCards(endpoint,checkoutData){
        if(tpCardsInProgress === true){
            cardsv2Log('form in 3d progress!!');
            return;
        }
        tpCardsInProgress = false;
        cardsv2Log('start the post.');
        $('#place_order').after('<p id="tpCardsBtnReplace" style="color:#CCC; text-align:center;">Processing, please wait...</p>');
        $('#place_order').hide();
        $('.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message').remove();
        $.ajax({
            type: 'POST',
            url: endpoint,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            enctype: "multipart/form-data",
            data: checkoutData,
            success: function(response){
                cardsv2Log(response);
                document.querySelector('form.woocommerce-checkout').classList.remove('processing');
                if(response.hasOwnProperty('result')){
                    if(response.hasOwnProperty('messages')){
                        $('form.checkout').prepend('<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">' + response.messages + '</div>');
                    }
                    if(response.result === 'success'){
                        if(response.redirect !== false){
                            if(-1 === (response.redirect).indexOf('https://') || -1 === (response.redirect).indexOf('http://') ) {
                                window.location = decodeURI(response.redirect);
                            } else {
                                window.location = response.redirect;
                            }
                        } else if(response.reload !== false){
                            window.location.reload();
                        } else if(response.refresh !== false){
                            $('#'+globalPrefix+'checkout_id').val('');
                            $('body').trigger("update_checkout");
                            $('#tpCardsBtnReplace').remove();
                            $('#place_order').show();
                        } else if(response.hasOwnProperty('pending')){
                            cardsv2Log('end trigger');
                            $('#tpCardsBtnReplace').remove();
                            $('#place_order').show();
                            if(response.hasOwnProperty("frameurl")){
                                $('#payment > ul > li.wc_payment_method.payment_method_'+ tp.tpCardVars.pluginId +' > div').show();
                                $('#'+globalPrefix+'container').empty();
                                $('#'+globalPrefix+'checkout_id').val(response.uuid);
                                cardsv2Log('set the uuid to: ' + response.uuid);
                                $('#'+globalPrefix+'container').html('<iframe id="' + cardIframeID + '" src="'+response.frameurl+'" style="width: 100%; height:80px; border: none;"></iframe>');
                                scroll_to_notices($('#'+globalPrefix+'container'));
                            }
                            if(response.hasOwnProperty("execute")){
                                cardsv2Log('ready to executePayment');
                                if($('iframe#' + cardIframeID).length){
                                    pciFormSubmit(cardIframeID,"wpwl-container-card");
                                } else {
                                    $('#'+globalPrefix+'checkout_id').val('');
                                    tpCardsInProgress = false;
                                    tp.tpCardsHandoff();
                                }
                            }
                        }
                    } else {
                        var err = $(".woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout");
                        if(err.length > 0 || (err = $(".form.checkout"))){
                            scroll_to_notices(err);
                        }
                        $('form.checkout').find(".input-text, select, input:checkbox").trigger("validate").blur();
                        $('body').trigger("checkout_error");
                        $('#tpCardsBtnReplace').remove();
                        $('#place_order').show();
                    }
                }
            },
            error: function(error){
                cardsv2Log(error);
            }
        });
    }

    function validate_tp_cardsv2_checkout(args){
        $('#tp_cardsv2_container').empty();
        $('#place_order').after('<p id="tpCardsBtnReplace" style="color:#CCC; text-align:center;">Processing, please wait...</p>');
        $('#place_order').hide();
        var generalAlertMsg     = 'Uncertain Response. Please report this to the merchant before reattempting payment. They will need to verify if this transaction is successful.';
        Promise.resolve(
            $.ajax({
                type: 'POST',
                url: tp.tpCardVars.adminUrl,
                data: { action: globalPrefix + 'validate_tp_cardsv2_checkout', resourcePath: args[0], order_id: args[1]},
                success: function(response) {
                    cardsv2Log(response);
                    if(response.hasOwnProperty("data")){
                        if(response.data.hasOwnProperty("url")){
                            window.location = (response.data.url);
                        } else {
                            alert( "Error(#1):" + generalAlertMsg + "\n" + 'resource:' + args[0] );
                            $('body').trigger("update_checkout");
                            $('#tpCardsBtnReplace').remove();
                            $('#place_order').show();
                        }
                    } else {
                        alert( "Error(#2):" + generalAlertMsg + "\n" + 'resource:' + args[0] );
                        $('body').trigger("update_checkout");
                        $('#tpCardsBtnReplace').remove();
                        $('#place_order').show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert( "Error(#3):" + generalAlertMsg + "\n" + 'Message::' + textStatus + '->' + errorThrown + "\n" + 'resource:' + args[0] );  
                    cardsv2Log(jqXHR);
                    cardsv2Log(textStatus);
                    cardsv2Log(errorThrown);
                },
            })
        ).then(function(){
            //do something
        }).catch(function(e) {
            alert( "Error(#3):" + generalAlertMsg + "\n" + 'resource:' + args[0] );
            cardsv2Log(e); 
        });
    }

    function do_log_errors(args){
        Promise.resolve(
            $.ajax({
                type: 'POST',
                url: tp.tpCardVars.adminUrl,
                data: { action: globalPrefix + 'do_log_errors', errors: args[0], order_id: args[1]},
                success: function(response) {
                    cardsv2Log(args[0]);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert( "Error(#3):" + "\n" + 'Message::' + textStatus + '->' + errorThrown + "\n" + 'resource:' + args[0] );  
                    cardsv2Log(jqXHR);
                    cardsv2Log(textStatus);
                    cardsv2Log(errorThrown);
                },
            })
        ).then(function(){
            //do something
        }).catch(function(e) {
            alert( "Error(#3):" + "\n" + 'resource:' + args[0] );
            cardsv2Log(e); 
        });
    }

    function progress_tp_cardsv2(args){
        if(args.length === 1){
            cardsv2Log('progress_tp_cardsv2: ' + args[0]);
            tpCardsInProgress = args[0];
        }
    }

    tp.tpCardsHandoff = function() {
        if($('form.woocommerce-checkout').find('input[name^="payment_method"]:checked').val() !== tp.tpCardVars.pluginId){
            return;
        }
        var checkoutData = $("form.woocommerce-checkout").serialize();
        document.querySelector('form.woocommerce-checkout').classList.add('processing');
        completeOrderTpCards(wc_checkout_params.checkout_url,checkoutData);
        return false;
    };

    tp.init = function( options ){
        tp.tpCardVars               = options;
        globalPrefix                = options.pluginPrefix;
        cardIframeContainerID       = globalPrefix + 'iframe_container';
        cardLoaderContainerID       = globalPrefix + 'inline-loading';
        cardIframeID                = globalPrefix + 'cnpFrame';

        cardsv2Log('checkout endpoint docReady!');
        cardsv2Log(tp.tpCardVars.pluginId + ' v.' + tp.tpCardVars.pluginVer);
        window.document.addEventListener('parentLogV52', logToParentWindow, false);
    }
} )( window.wc_gateway_tp = window.wc_gateway_tp || {}, jQuery );

jQuery( function(){
    var tpGlobalVars    = getTPGlobalVariable();
    wc_gateway_tp.init( tpGlobalVars );
    jQuery(document.body).on('updated_checkout', function() {
        window.wc_gateway_tp.initCheckoutIdOrder();
    });
    
    var checkout_form = jQuery( 'form.woocommerce-checkout' );
    checkout_form.on( 'checkout_place_order', wc_gateway_tp.tpCardsHandoff );
});

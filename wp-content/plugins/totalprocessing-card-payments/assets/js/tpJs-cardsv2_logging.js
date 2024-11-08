( function ( tp, $ ) {
    'use strict';

    var tpCardVars                = {};
    var tpCardsInProgress         = false;
    var globalPrefix              = '';
    var cardIframeContainerID     = globalPrefix + 'iframe_container';
    var cardIframeID              = globalPrefix + 'cnpFrame';
    
    const wc_cardsv2Fe_callback   = function(mutationsList, observer) {
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
        if( parseInt( tpCardVars.jsLogging ) === 1 ){
	        var e = new Error(obj);
            console.log(obj);
            console.log(e.stack);
        }
    }

    function cardsv2FeLog(msg,level){
    	cardsv2Log('cardsv2FeLog initiated.');
    	cardsv2Log(msg);
    	cardsv2Log(level);
    	jQuery.ajax({
    		type : "post",
    		dataType : "json",
    		url : tpCardVars.adminUrl,
    		data : {action: "feLogger", fe_nonce: tpCardVars.feNonce , msg: msg, level: level},
    		success: function(response) {
    			cardsv2Log(response);
    		}
    	});
    }
    function addRemClassSelector(elemSelector,classAdd,classRemove){
        var elem = document.querySelector(elemSelector);
        for (var i = 0, len = classAdd.length; i < len; i++) {
            elem.classList.add(classAdd[i]);
        }
    }
    function checkWpwlContainers(){
        if(jQuery(".wpwl-group-registration").length){
            console.log('reg found');
            jQuery('#cnpNav').append('<button type="button" class="cnpTarget" data-target="wpwl-container-registration"><i class="fa fa-shopping-bag"></i>&nbsp; Use saved card</button>');
            jQuery('#cnpNav').append('<button type="button" class="cnpTarget" data-target="wpwl-container-card"><i class="fa fa-credit-card"></i>&nbsp; New card</button>');
            jQuery('.wpwl-container-card').hide();
        } else {
            console.log('reg not found');
            jQuery('#cnpNav').empty();
            jQuery('.wpwl-container-card').show();
        }

    	if(jQuery('form.woocommerce-checkout').find('input[name^="payment_method"]:checked').data("registrationid") !== undefined){
    		jQuery('#cnpNav').empty();   
    	} else if(tpCardVars.slickOneClick === '1') {
    		jQuery('#cnpNav').empty();
    		jQuery('.wpwl-container-registration').hide();
            jQuery('.wpwl-container-card').show();
    	}
    }
    function displayImgFooterTp(brands){
        var brandArr = brands.split(" ");
        jQuery(brandArr).each(function(i,brand) {
            jQuery('#footerBrandsTp').append('<img src="'+tpCardVars.assetsDir + '/img/'+brand+'-3d.svg" style="height:18px;display:inline;margin-left:0.35rem;padding-top:5px;border-radius:unset;" alt="'+brand+'">');
        });
    }
    tp.genSlickOneClick = function(rgArray){
    	jQuery(rgArray).each(function(i,rgId) {
    		var appendElem = document.querySelector('ul.wc_payment_methods');
    		var liElem = document.createElement('li');
    		liElem.classList.add('wc_payment_method');
    		liElem.classList.add('payment_method_'+ tpCardVars.pluginId);
    		var inputElem = document.createElement('input');
    		inputElem.setAttribute('id', 'payment_method_' + tpCardVars.pluginId + '_' + rgId['registrationId']);
    		inputElem.setAttribute('data-registrationid', rgId['registrationId']);
    		inputElem.setAttribute('type', 'radio');
    		inputElem.setAttribute('name', 'payment_method');
    		inputElem.setAttribute('value', tpCardVars.pluginId);
    		inputElem.classList.add('input-radio');
    		var labelElem = document.createElement('label');
    		labelElem.setAttribute('for', 'payment_method_' + tpCardVars.pluginId + '_' + rgId['registrationId']);
    		var textNode = document.createTextNode(rgId['holder'] + ' *** ' + rgId['last4']);
    		var imgNode = document.createElement('img');
    		imgNode.setAttribute('src', tpCardVars.assetsDir + '/img/' + rgId['paymentBrand'] + '.svg');
    		imgNode.setAttribute('alt', rgId['paymentBrand']);
    		imgNode.setAttribute('style', 'height:20px;display:inline;border-radius:unset;');
    		labelElem.appendChild(textNode);
    		labelElem.appendChild(imgNode);
    		liElem.appendChild(inputElem);
    		liElem.appendChild(labelElem);
    		appendElem.appendChild(liElem);
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
        }).then(function(json){
            if(json === false) return false;
            cardsv2Log('stage 8: oppwa');
            var oppwajs = document.createElement('script');
            oppwajs.setAttribute('src', 'https://' + json.platformbase + '/v1/paymentWidgets.js?checkoutId=' + json.uuid);
            document.body.appendChild(oppwajs);
            return true;
        }).then(function(res){
            if(res === false) return;
            cardsv2Log('stage 9: swal');
            Swal.fire({
                padding: '2rem',
                titleText: 'Please enter Card details here',
                //imageUrl: tpCardVars.assetsDir + '/img/tp-logo.png',
                imageHeight: 32,
                html: '<div id="cnpNav"></div><div id="cnpSwal"></div>',
                allowOutsideClick: true,
                allowEscapeKey: true,
                allowEnterKey: false,
                reverseButtons: true,
                showConfirmButton: true,
                showCancelButton: true,
                focusConfirm: true,
                confirmButtonText: 'Process',
                cancelButtonText: 'Cancel',
                //header: '',
                //footer: '<p><small>Payments powered by Total Processing</small></p><div id="footerBrandsTp"></div>',
                customClass: {
                    header:'padBottom',
                    footer:'flexFootTp'
                },
                didOpen: function(){
                    cardsv2Log('didOpen');
    				var wc_cardsv2Fe_cnpWpwl = document.getElementById('cnpWpwl');
    				wc_cardsv2Fe_observer.observe(wc_cardsv2Fe_cnpWpwl, {childList: true});
    				var wc_cardsv2Fe_cnpSwal = document.getElementById('cnpSwal');
    				wc_cardsv2Fe_observer.observe(wc_cardsv2Fe_cnpSwal, {childList: true, subtree: true});
    				jQuery('.swal2-popup').show();
                    displayImgFooterTp(tpCardVars.brands);
                    Swal.showLoading(Swal.getConfirmButton());
                },
    			didRender: function(){
    				cardsv2Log('didRender');
    				var cnpSwal = document.getElementById('cnpSwal');
    				var cnpForm = document.createElement('form');
    				cnpForm.setAttribute('id', 'cnpWpwl');
    				cnpForm.classList.add('paymentWidgets');
    				cnpForm.setAttribute('data-brands', tpCardVars.brands);
            		cnpSwal.appendChild(cnpForm);
    			},
                preConfirm: function(){
                    if(jQuery('.wpwl-container-card').is(":visible")){
                        wpwl.executePayment('wpwl-container-card');
                    } else if(jQuery('.wpwl-container-registration').is(":visible")){
                        wpwl.executePayment('wpwl-container-registration');
                    } else {
                        //cardsv2Log('no containers visible');
                    }
                    return false;
                }
            }).then(function(result){
                if(result.isDismissed === true) {
    				wc_cardsv2Fe_observer.disconnect();
                    jQuery('#tpCardsBtnReplace').remove();
                    jQuery('#place_order').show();
                    if(window.wpwl !== undefined && window.wpwl.unload !== undefined) {
                        window.wpwl.unload();
                        jQuery('script').each(function () {
                            if (this.src.indexOf('static.min.js') !== -1) {
                                jQuery(this).remove();
                            }
                        });
                    }
                }
            });
        });
    }

    function initWPWLOptions(){
        var createRegistrationHtml = '<br style="clear:both;" />';
        createRegistrationHtml    += '<p id="tpIframeRg" class="form-row form-row-wide" style="float:left!important; text-align:left;">';
        createRegistrationHtml    += '<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">';
        createRegistrationHtml    += '<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="checkbox" id="createRegistration" name="createRegistration" value="true"> <span>Securely store this card?</span>';
        createRegistrationHtml    += '</label>';
        createRegistrationHtml    += '</p>';
        window.wpwlOptions = {
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
            style:'plain',
            disableSubmitOnEnter:true,
            showLabels:false,
            brandDetection:true,
            brandDetectionType:'regex',
            requireCvv:true,
            showCVVHint:false,
            spinner:{
                color: '#2095ee',
                lines: 18,
                length: 5,
                width: 2,
                radius: 7,
                scale: 3.6,
                corners: 1,
                speed: 2.2
            },
            onReady: function() {
                addRemClassSelector('.wpwl-group-cardNumber',["form-row","form-row-wide"],[]);
                addRemClassSelector('.wpwl-group-expiry',["form-row","form-row-first"],[]);
                addRemClassSelector('.wpwl-group-cvv',["form-row","form-row-last"],[]);
                jQuery('.wpwl-control-expiry').attr('autocomplete', 'cc-exp');
                //jQuery('form.wpwl-form-card').find('.wpwl-control-cardNumber').before('<label id="tp-cc-label" class="tpFrameLabel">Card Number&nbsp;<span class="tp-required">*</span></label>');
                //jQuery('form.wpwl-form-card').find('.wpwl-control-expiry').before('<label class="tpFrameLabel">Expiry Date&nbsp;<span class="tp-required">*</span></label>');
                //jQuery('form.wpwl-form-card').find('.wpwl-control-cvv').before('<label class="tpFrameLabel">Card Code (CVC)&nbsp;<span class="tp-required">*</span></label>');
                if(parseInt(tpCardVars.createRegistration) === 1 && parseInt(tpCardVars.loggedIn) === 1){
                    jQuery('form.wpwl-form-card').find('.wpwl-group-cvv').after(createRegistrationHtml);
                }
                checkWpwlContainers();
                Swal.hideLoading();
                cardsv2Log('wpwlReady1');
            },
            onReadyIframeCommunication: function(){
                var iframeField = this.$iframe[0];
                if(iframeField.parentNode.classList.contains('wpwl-registration') === false){
                    if(iframeField.classList.contains('wpwl-control-cardNumber') === true){
                        var ccNoContainer = iframeField.parentNode;
                        var brandContainer = document.createElement('div');
                        brandContainer.id= "wpwlDynBrand";
                        var dynBrandImg = document.createElement('img');
                        dynBrandImg.id = "wpwlDynBrandImg";
                        dynBrandImg.src = tpCardVars.assetsDir + '/img/' + 'default.svg';
                        brandContainer.appendChild(dynBrandImg);
                        ccNoContainer.appendChild(brandContainer);
                    }
                }
            },
            onDetectBrand: function(brands){
                var dynBrandImgSrc;
                if(brands.length > 0){
                    dynBrandImgSrc = tpCardVars.assetsDir + '/img/' + brands[0] + '.svg';
                } else {
                    dynBrandImgSrc = tpCardVars.assetsDir + '/img/' + 'default.svg';
                }
                document.getElementById('wpwlDynBrandImg').src = dynBrandImgSrc;
            },
            onAfterSubmit: function(){
                cardsv2Log('onAfterSubmit');
                jQuery('#cnpNav').empty();
                Swal.showLoading(Swal.getConfirmButton());
                return true;
            },
            onLoadThreeDIframe: function(){
                cardsv2Log('onLoadThreeDIframe');
                Swal.hideLoading();
                jQuery(Swal.getConfirmButton()).hide();
            },
            onError: function(error){
        		cardsv2Log('error triggered!');
                cardsv2Log(error);
        		cardsv2FeLog(JSON.stringify(error),'error');
            }
        };
    }

    tp.tpCardsHandoff = function() {
        console.log('handsoff');
        if(jQuery('form.woocommerce-checkout').find('input[name^="payment_method"]:checked').val() === tpCardVars.pluginId){
            var checkoutData = jQuery("form.woocommerce-checkout").serialize();
    		if(tpCardVars.slickOneClick === '1'){
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
        tpCardVars               = options;
        globalPrefix                = options.pluginPrefix;
        cardIframeContainerID       = globalPrefix + 'iframe_container';
        cardIframeID                = globalPrefix + 'cnpFrame';
        initWPWLOptions();
        cardsv2Log('checkout endpoint docReady!');
        cardsv2Log(tpCardVars.pluginId + ' v.' + tpCardVars.pluginVer);
    }
} )( window.wc_gateway_tp = window.wc_gateway_tp || {}, jQuery );

jQuery( function(){
    var tpGlobalVars    = getTPGlobalVariable();
    wc_gateway_tp.init( tpGlobalVars );
    var checkout_form = jQuery( 'form.woocommerce-checkout' );
    checkout_form.on( 'checkout_place_order', wc_gateway_tp.tpCardsHandoff );
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

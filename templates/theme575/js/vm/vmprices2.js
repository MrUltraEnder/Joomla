if (typeof Virtuemart === "undefined")
	var Virtuemart = {};
			Virtuemart.setproducttype = function(form, id) {
				form.view = null;
				var datas = form.serialize();
				var prices = form.parent(".productdetails").find(".product-price");
				if (0 == prices.length) {
					prices = jQuery("#productPrice" + id);
				}
				datas = datas.replace("&view=cart", "");
				//datas = decodeURIComponent(datas);
				//var decoded = jQuery('<textarea/>').html(datas).val();
			
				prices.fadeTo("fast", 0.75);
				jQuery.ajax({
					type: "POST",
					cache: false,
					dataType: "json",
					url: window.vmSiteurl + "index.php?&option=com_virtuemart&view=productdetails&task=recalculate&format=json&nosef=1" + window.vmLang,
					data: datas
				}).done(
					function (data, textStatus) {
						prices.fadeTo("fast", 1);
						console.log('my datas '+data);
						// refresh price
						for (var key in data) {
							var value = data[key];
							//console.log('my datas',key,value);
							if (value!=0) prices.find("span.Price"+key).show().html(value);
							else prices.find(".Price"+key).html(0).hide();
						}
					}
				);
			
				return false; // prevent reload
			},
			Virtuemart.productUpdate = function() {
				mod=jQuery(".vmCartModule");
				var $ = jQuery ;
				$.ajaxSetup({ cache: false })
				$.getJSON(window.vmSiteurl+"index.php?option=com_virtuemart&nosef=1&view=cart&task=viewJS&format=json"+window.vmLang,
					function(datas, textStatus) {
						if (datas.totalProduct >0) {
							mod.find(".vm_cart_products").html("");
							datas.products.reverse();
							$.each(datas.products, function(key, val) {
								if (key<4){								
									$("#hiddencontainer .container").clone().appendTo(".vmCartModule .vm_cart_products");
									$.each(val, function(key, val) {
										if ($("#hiddencontainer .container ."+key)) mod.find(".vm_cart_products ."+key+":last").html(val) ;
									});
								}
							});
							mod.find(".text-cart").html(datas.cart_recent_text);
							mod.find("#cart_list .total").html(datas.billTotal).show();
							mod.find(".show_cart").html(datas.cart_show).show();
							mod.find(".vm_cart_products").show();
						}
						mod.find(".total_products").html(datas.totalProductTxt);
					}
				);
			},
			Virtuemart.sendtocart = function (form){
				if (Virtuemart.addtocart_popup ==1) {
					Virtuemart.cartEffect(form) ;
				} else {
					form.append('<input type="hidden" name="task" value="add" />');
					form.submit();
				}
			},

			Virtuemart.cartEffect = function(form) {
				var $ = jQuery ;
				jQuery.ajaxSetup({ cache: false });
				var dat = form.serialize();
			
				if(usefancy){
					jQuery.fancybox.showActivity();
				}
			
				jQuery.getJSON(vmSiteurl+'index.php?option=com_virtuemart&nosef=1&view=cart&task=addJS&format=json'+vmLang, dat,
				function(datas, textStatus) {
			
					if(datas.stat ==1){
			
						var txt = datas.msg;
					} else if(datas.stat ==2){
						var txt = datas.msg +"<H4>"+form.find(".pname").val()+"</H4>";
					} else {
						var txt = "<H4>"+vmCartError+"</H4>"+datas.msg;
					}
					if(usefancy){
						jQuery.fancybox({
								"titlePosition" : 	"inside",
								"transitionIn"	:	"fade",
								"transitionOut"	:	"fade",
								"changeFade"    :   "fast",
								"type"			:	"html",
								"autoCenter"    :   true,
								"closeBtn"      :   false,
								"closeClick"    :   false,
								"content"       :   txt
							}
						);
					} else {
						jQuery.facebox.settings.closeImage = closeImage;
						jQuery.facebox.settings.loadingImage = loadingImage;
						//$.facebox.settings.faceboxHtml = faceboxHtml;
						jQuery.facebox({ text: txt }, 'my-groovy-style');
					}
			
			
					Virtuemart.productUpdate();
				});
			
				jQuery.ajaxSetup({ cache: true });
			}
Virtuemart.product = function(carts) {
	carts.each(function(){
		var cart = jQuery(this),
		step=cart.find('input[name="quantity"]'),
		addtocart = cart.find('input.addtocart-button'),
		plus   = cart.find('.quantity-plus'),
		minus  = cart.find('.quantity-minus'),
		select = cart.find('select:not(.no-vm-bind)'),
		radio = cart.find('input:radio:not(.no-vm-bind)'),
		virtuemart_product_id = cart.find('input[name="virtuemart_product_id[]"]').val(),
		quantity = cart.find('.quantity-input');

		var Ste = parseInt(step.val());
		//Fallback for layouts lower than 2.0.18b
		if(isNaN(Ste)){
			Ste = 1;
		}
		addtocart.unbind("click");
		addtocart.click(function(e) {
			Virtuemart.sendtocart(cart);
			return false;
		});
		plus.unbind("click");
		plus.click(function() {
			var Qtt = parseInt(quantity.val());
			if (!isNaN(Qtt)) {
				quantity.val(Qtt + Ste);
				Virtuemart.setproducttype(cart,virtuemart_product_id);
			}
			
		});
		minus.unbind("click");
		minus.click(function() {
			var Qtt = parseInt(quantity.val());
			if (!isNaN(Qtt) && Qtt>Ste) {
				quantity.val(Qtt - Ste);
			} else quantity.val(Ste);
			Virtuemart.setproducttype(cart,virtuemart_product_id);
		});
		select.change(function() {
			Virtuemart.setproducttype(cart,virtuemart_product_id);
		});
		radio.change(function() {
			Virtuemart.setproducttype(cart,virtuemart_product_id);
		});
		quantity.keyup(function() {
			Virtuemart.setproducttype(cart,virtuemart_product_id);
		});
	});
},
Virtuemart.checkQuantity = function (obj,step,myStr) {
    // use the modulus operator "%" to see if there is a reminder
    reminder=obj.value % step;
    quantity=obj.value;
    if (reminder  != 0) {
        //myStr = "'.vmText::_ ('COM_VIRTUEMART_WRONG_AMOUNT_ADDED').'";
        alert(myStr.replace("%s",step));
        if(quantity!=reminder && quantity>reminder){
            obj.value = quantity-reminder;
        } else {
            obj.value = step;
        }
        return false;
    }
    return true;
}

jQuery.noConflict();
jQuery(document).ready(function($) {
	Virtuemart.product(jQuery("form.product"));

	/*$("form.js-recalculate").each(function(){
		if ($(this).find(".product-fields").length && !$(this).find(".no-vm-bind").length) {
			var id= $(this).find('input[name="virtuemart_product_id[]"]').val();
			Virtuemart.setproducttype($(this),id);

		}
	});*/
});

function init(){var e={zoom:15,center:new google.maps.LatLng(55.711354,37.654629),scrollwheel:!1,styles:[{featureType:"water",elementType:"geometry",stylers:[{color:"#e9e9e9"},{lightness:17}]},{featureType:"landscape",elementType:"geometry",stylers:[{color:"#f5f5f5"},{lightness:20}]},{featureType:"road.highway",elementType:"geometry.fill",stylers:[{color:"#ffffff"},{lightness:17}]},{featureType:"road.highway",elementType:"geometry.stroke",stylers:[{color:"#ffffff"},{lightness:29},{weight:.2}]},{featureType:"road.arterial",elementType:"geometry",stylers:[{color:"#ffffff"},{lightness:18}]},{featureType:"road.local",elementType:"geometry",stylers:[{color:"#ffffff"},{lightness:16}]},{featureType:"poi",elementType:"geometry",stylers:[{color:"#f5f5f5"},{lightness:21}]},{featureType:"poi.park",elementType:"geometry",stylers:[{color:"#dedede"},{lightness:21}]},{elementType:"labels.text.stroke",stylers:[{visibility:"on"},{color:"#ffffff"},{lightness:16}]},{elementType:"labels.text.fill",stylers:[{saturation:36},{color:"#333333"},{lightness:40}]},{elementType:"labels.icon",stylers:[{visibility:"off"}]},{featureType:"transit",elementType:"geometry",stylers:[{color:"#f2f2f2"},{lightness:19}]},{featureType:"administrative",elementType:"geometry.fill",stylers:[{color:"#fefefe"},{lightness:20}]},{featureType:"administrative",elementType:"geometry.stroke",stylers:[{color:"#fefefe"},{lightness:17},{weight:1.2}]}]},t=document.getElementById("map"),n=new google.maps.Map(t,e);new google.maps.Marker({position:new google.maps.LatLng(55.710074,37.654759),map:n,title:"Магазин",icon:"/img/map_point-min.png"});google.maps.event.addDomListener(window,"resize",function(){var e=n.getCenter();google.maps.event.trigger(n,"resize"),n.setCenter(e)})}function scrollToEl(e){var t=e.offset().top-100;$(document).scrollTop()>t&&$("html, body").animate({scrollTop:t},300)}function updateCart(e){if("undefined"!=typeof e.count&&$(".js-cart-quantity").text(e.count),"undefined"!=typeof e.modal&&openModal(e),"undefined"!=typeof e.removed){var t=$("[data-id="+e.removed+"]"),n=$(".js-total-amount");n.data("total",e.amount),n.text(number_format(e.amount,0,"."," ")+"₽"),t.remove()}}function nextStep(e){var t=$.isNumeric(e)?e:e.data("next_step");"undefined"!=typeof t&&($(".js-step").removeClass("active"),$(".js-step_"+t).addClass("active"))}function orderSuccess(e){$("#order-success").append(e.html),nextStep(3),scrollToEl($("#js-order-success"))}function openModal(e){"undefined"!=typeof e.modal&&($.fancybox.close(),$.fancybox.open([{src:e.modal,type:"inline"}],{closeBtn:!1,beforeClose:function(t,n,a){var i=n.$slide.find("form");"undefined"!=typeof i.data("submit-on-close")&&i.submit(),"undefined"!=typeof e.modalAction&&"refresh-on-close"==e.modalAction&&(window.location="/")}}))}function number_format(e,t,n,a){var i,s,o,r,l;return isNaN(t=Math.abs(t))&&(t=2),void 0==n&&(n=","),void 0==a&&(a="."),i=parseInt(e=(+e||0).toFixed(t))+"",(s=i.length)>3?s%=3:s=0,l=s?i.substr(0,s)+a:"",o=i.substr(s).replace(/(\d{3})(?=\d)/g,"$1"+a),r=t?n+Math.abs(e-i).toFixed(t).replace(/-/,0).slice(2):"",l+o+r}function commentSuccess(e){"undefined"!=typeof e.html&&$(".js-comment-success").html(e.html)}function updateCounter(e){"undefined"!=typeof e.selector&&"undefined"!=typeof e.count&&$(e.selector).text(e.count)}function paginationReplace(e){"undefined"!=typeof e.html&&"undefined"!=typeof e.model&&($(".js-container-"+e.model).html(e.html),$(".js-pagination-"+e.model).addClass("hidden"))}function paginationAppend(e){if("undefined"!=typeof e.html&&"undefined"!=typeof e.model){var t=$(".js-pagination-"+e.model),n=t.find("button:first-child");$(".js-container-"+e.model).append(e.html),"undefined"!=typeof e.count&&e.count>0?(n.data("page",e.nextPage),n.find(".js-items-count").text("("+e.count+")")):t.addClass("hidden")}}$(function(){var e=$("#js-filters"),t=$(".js-sort"),n=e.find("input[name=page]"),a=$(".js-goods-count"),i=$("#js-goods"),s=$(".js-pagination"),o=["page","_token","category_id","brand_id","tag_id","price_from","price_to"],r="",l=function(t){"undefined"==typeof t&&(t=!1),n.val(1),t&&e.trigger("submit")},c=function(){e.find("input[type=checkbox]","input[type=radio]").attr("checked",!1),e.find("input[name^=attribute]").attr("disabled",!0),$(".js-square").removeClass("active"),f.noUiSlider.set([rRange[0],rRange[1]]),l(!0)};for(var d in o)r+="[name!="+o[d]+"]";""==n.val()&&s.hide();var f=document.getElementById("js-range-slider");if(f){var u=$(f);rStart=u.data("start"),rRange=u.data("range"),noUiSlider.create(f,{start:[rStart[0],rStart[1]],connect:!0,range:{min:rRange[0],max:rRange[1]}});var p=document.getElementById("js-price-min"),m=document.getElementById("js-price-max");f.noUiSlider.on("update",function(e,t){var n=e[t];t?m.value=Math.round(n):p.value=Math.round(n)}),f.noUiSlider.on("set",function(){l()}),p.addEventListener("change",function(){f.noUiSlider.set([this.value,null])}),m.addEventListener("change",function(){f.noUiSlider.set([null,this.value])})}e.find("input[name^=attribute],select[name^=attribute]").on("change",function(e){e.preventDefault(),l()}),e.find(".js-square").on("click",function(e){l()}),e.on("submit",function(e){e.preventDefault();var t=$(this).serialize();$.post($(this).attr("action"),t,function(e){e.clear?(n.val(2),i.html($(e.items))):"all"==n.val()?i.html($(e.items)):i.append($(e.items)),null===e.next_page?s.hide():(s.show(),n.val(e.next_page)),a.html(e.count)})}),t.on("click",function(n){n.preventDefault();var a=$(this).data("sort");e.find("input[name=sort]").val(a),$(this).hasClass("active")?$(this).removeClass("active"):(t.removeClass("active"),$(this).addClass("active")),l(!0)}),s.on("click",function(t){t.preventDefault();var a=$(this).data("all");"undefined"!=typeof a&&a&&n.val("all"),e.trigger("submit")}),$(".js-filters-reset").on("click",function(e){return e.preventDefault(),c(),$(".js-toggle-sidebar.active").trigger("click"),!1}),$(".js-close-filters").on("click",function(){$(".js-toggle-sidebar.active").trigger("click")})});var mapDiv=document.getElementById("map"),mapLoad=function(e){mapDiv.removeEventListener("click",mapLoad),$.getScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyBIc5obn1ArfkEzXhkgZiMyyHPRQmjNx5M",function(){init()})};mapDiv.addEventListener("click",mapLoad),$(function(){function e(e){e.find(".active").each(function(){$(this).removeClass("active"),$(this).find("input").attr("disabled",!0)})}function t(e){var t=e.data("target"),n=e.data("switch"),a=e.data("reset");"undefined"!=typeof n?$("[data-switch="+n+"]").toggleClass("active"):"undefined"!=typeof a?$(a).removeClass("active"):e.toggleClass("active"),$(t).toggleClass("active")}function n(){var e=$(".js-dropdown-women"),t=$(".js-dropdown-men"),n=$(".js-pages"),i=$(".sidebar-filter"),s=a();n.detach(),e.detach(),t.detach(),i.detach(),s?(n.insertAfter(".nav-catalog"),e.prependTo(".js-women-desktop"),t.prependTo(".js-men-desktop"),i.prependTo(".sidebar")):(e.appendTo(".js-women-mobile"),t.appendTo(".js-men-mobile"),n.insertAfter(".js-pages-mobile"),i.appendTo("#sidebar-filters"))}function a(){var e=window.getComputedStyle(document.querySelector("header"),"::before").getPropertyValue("content").replace(/"/g,"").replace(/'/g,"");return"mobile"!=e}function i(){$(".js-sidebar-open").removeClass("active"),$(".js-nav-visible").removeClass("active"),$(".js-filter-visible").removeClass("active"),setTimeout(function(){$(".js-toggle-sidebar").removeClass("active")},300),f=!1}function s(e,t){setTimeout(function(){e.addClass("active")},300),$(t).addClass("active"),$(".js-sidebar-open").addClass("active"),f=!0}function o(e){var t=e.attr("action"),n=e.serialize(),a=e.find(".js-required-fields");r(a,!0)&&$.post(t,n,function(e){if("undefined"!=typeof e.error,"undefined"!=typeof e.action){var t=window[e.action];"function"==typeof t&&t(e)}},"json")}function r(e,t){if("undefined"==typeof e)return!1;"undefined"==typeof t&&(t=!1);var n=0;return e.each(function(e,a){"phone"==a.name?a.value.length>14?(n++,$(a).removeClass("error")):t&&$(a).addClass("error"):a.value&&a.value.length>1?(n++,$(a).removeClass("error")):t&&$(a).addClass("error")}),n==e.length}function l(e){var t=e.closest(".js-product"),n=parseInt(t.find(".js-price").data("price")),a=parseInt(t.find(".js-quantity-input").val()),i=t.find(".js-amount"),s=$(".js-total-amount"),o=n*a,r=o-parseInt(i.data("amount")),l=parseInt(s.data("total")+r);i.data("amount",o),i.text(number_format(o,0,"."," ")+"₽"),s.data("total",l),s.text(number_format(l,0,"."," ")+"₽")}var c=$("body");c.on("click",".js-toggle-active",function(e){var t=$(this),n=t.data("reset");t.hasClass(".js-prevent-default")&&e.preventDefault(),"undefined"!=typeof n&&$(n).removeClass("active"),t.toggleClass("active")}),c.on("click",".js-prevent",function(e){return e.preventDefault(),!1});var d=$(".js-square-check-filter");d&&c.on("click",".js-square",function(t){t.preventDefault();var n=$(this),a=n.parent(),i=!1;a.hasClass("js-square-check-single")&&(i=!0),n.hasClass("active")?i?e(a):(n.removeClass("active"),n.find("input").attr("disabled",!0)):(i&&e(a),n.addClass("active"),n.find("input").attr("disabled",null))}),c.on("click",".js-show-more",function(e){e.preventDefault();var t=$(this);t.toggleClass("active"),t.parent().toggleClass("active")}),c.on("click",".js-select",function(){var e=$(this);e.find(".js-option").click(function(){e.find(".js-selected").text($(this).text())})}),c.on("click",".js-toggle-active-target",function(e){e.preventDefault(),t($(this))}),c.on("mouseenter",".js-toggle-active-target_over",function(){$(this).hasClass("active")||t($(this))}),c.on("mouseover",".js-hover-notice",function(){$(this).find(".popup-notice").addClass("active")}),c.on("mouseout",".js-hover-notice",function(){$(this).find(".popup-notice").removeClass("active")}),c.on("click",".js-click-notice",function(){var e,t=$(this).data("target");e="undefined"!=typeof t?$(this).closest(".popup-notice_"+t):$(this).find(".popup-notice"),e.addClass("active"),setTimeout(function(){e.removeClass("active")},6e3)}),c.on("click",".js-add-to-cart",function(e){e.preventDefault();var t=$(this),n=t.closest("form"),a=n.serializeFormJSON();if("undefined"==typeof a.size){var i=n.find(".js-popup-size");scrollToEl(i),i.addClass("active"),setTimeout(function(){i.removeClass("active")},3e3)}else n.submit(),t.addClass("active")}),$.fn.serializeFormJSON=function(){var e={},t=this.serializeArray();return $.each(t,function(){e[this.name]?(e[this.name].push||(e[this.name]=[e[this.name]]),e[this.name].push(this.value||"")):e[this.name]=this.value||""}),e},$("header").on("webkitTransitionEnd transitionend oTransitionEnd",function(e){"min-height"==e.originalEvent.propertyName&&n()}),n();var f=!1;$(".js-toggle-sidebar").click(function(){var e=$(this),t=e.data("target"),n=e.hasClass("active");f?(i(),n||setTimeout(function(){s(e,t)},500)):s(e,t)}),$("#js-product-gallery-nav").carousel({vertical:!0,margin:0,responsive:{1492:{items:5,options:{margin:0,vertical:!0}},1203:{items:5,options:{margin:0,vertical:!0}},840:{items:5,options:{margin:0,vertical:!0}},576:{items:5,options:{margin:0,vertical:!1}},320:{items:3,options:{margin:0,vertical:!1}}}}),$("#js-product-set").carousel(),$(".js-product-carousel").carousel(),$(".js-single-banner").carousel({margin:0,pagination:!0,responsive:{1492:1,1203:1,840:1,576:1,320:1}}),$(".js-vk-comments-widget").on("click.vk",function(){$(this).off("click.vk"),$.getScript("//vk.com/js/api/openapi.js?145",function(){VK.init({apiId:4411901,onlyWidgets:!0}),VK.Widgets.Comments("js-vk_comments",{limit:5,attach:!1})})}),c.on("click",".js-step-next",function(e){e.preventDefault(),nextStep($(this))}),c.on("click",".js-delivery",function(e){var t=$(this),n=$(".js-total"),a=parseInt(t.find(".js-price").data("price")),i=parseInt(n.data("amount"))+a;n.text(number_format(i,0,"."," ")+" ₽")}),c.on("click","#js-order-submit",function(e){e.preventDefault();var t=$(this).closest("form");o(t)}),c.on("click",".js-play-video",function(e){e.preventDefault();var t=$(this),n=t.parent(),a=n.find("iframe");n.addClass("active"),a.attr("src",a.data("src"))}),c.on("submit",".js-form-ajax",function(e){e.preventDefault(),o($(this))}),c.on("click",".js-cart-submit",function(e){var t=$(this),n=t.data("is_fast");document.getElementById("is_fast").value=n,n&&(e.preventDefault(),o(t.closest("form")))}),c.on("click",".js-quantity",function(){var e=$(this),t=parseInt(e.data("num")),n=e.parent().find(".js-quantity-input"),a=parseInt(n.val()),i=a+t;i>0&&n.val(i),"undefined"!=typeof e.data("submit")?e.closest("form").submit():l(e)});var u;c.on("keyup",".js-quantity-input",function(){clearTimeout(u);var e=$(this);u=setTimeout(function(){"undefined"!=typeof e.data("submit")?e.closest("form").submit():l(e)},1e3)}),c.on("click",".js-action-link",function(e){e.preventDefault();var t=$(this),n=t.data("url"),a=t.data();delete a.url,$.get(n,a,function(e){if("undefined"!=typeof e.error,"undefined"!=typeof e.action){var t=window[e.action];"function"==typeof t&&t(e)}})}),$(".js-phone").mask("+7 000 000 00 000",{placeholder:"+7 ___ ___ __ __"}),c.on("input",".js-required-fields",function(e){var t=$(".js-required-fields");r(t)?$(".js-step-next").attr("disabled",!1):$(".js-step-next").attr("disabled",!0)}),c.on("click",".js-gallery-thumb",function(e){var t=$(this),n=$(".js-gallery-big");$(".js-gallery-thumb").removeClass("active"),t.addClass("active"),n.removeClass("active"),n.eq(t.index()).addClass("active")})});
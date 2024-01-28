jQuery.noConflict();
(function($){
    $(function(){
        //PDA BUTTON
        // let navBar = document.getElementById("leftNav");

        let mainNav = document.getElementById("mainNav");
        if(document.getElementById("mobNavBtn")){
            document.getElementById("mobNavBtn").onclick = function() {
                this.classList.toggle('active');
                // navBar.classList.toggle('active');
                mainNav.classList.toggle('active');
            };
        }


        //CART COUNT
        $('#cartCount').text($('#header-basket-amount').text());

        //CUSTOMER CARE
        let $customerCareTitle = $('.customer_care_title');
        if ($customerCareTitle.length) {
            $customerCareTitle.click(function () {
                if ($(this).parent().find('.customer_care_html').hasClass('active')) {
                    $('.customer_care_block .customer_care_html').removeClass('active');
                }else {
                    $('.customer_care_block .customer_care_html').removeClass('active');
                    $(this).parent().find('.customer_care_html').toggleClass('active');
                }
            })
        }

        //PRODUCT PAGE
        let $sizeGuide = $('#sizeGuide');
        let $sizeGuideBlock = $('#sizeGuideBlock');
        let $sizeGuideClose = $('#sizeGuideBlock .close');
        let $itemPropRadio = $('#addtocartform-modification .radio');
        let $itemPropMod = $('#item-prop-mod');
        let $productImg = $('#productSlider .slide img');

        $sizeGuide.click(function () {
            $sizeGuideBlock.addClass('active')
        });
        $sizeGuideClose.click(function () {
            $sizeGuideBlock.removeClass('active')
        });
        $itemPropRadio.click(function () {
            $itemPropMod.text($(this).text());
        });
        $productImg.click(function () {
            let slide = parseInt($(this).attr('data-slide'));
           $('#fullPageBlock').addClass('active');
            $('#fullpageSlider').slick('slickGoTo', slide);
        });
        $('.fullPageClose').click(function () {
            $('#fullPageBlock').removeClass('active')
        });

        //PRODUCT SLIDER
        let $prod_slider = $('#productSlider');
        if ($prod_slider.length) {
            $prod_slider.slick({
                infinite: true,
                speed: 600,
                centerMode: true,
                fade: true,
                asNavFor: '#productSliderNav',
                slidesToShow: 1,
                slidesToScroll: 1,
                lazyLoad: 'ondemand',
                prevArrow: $('.slider_nav .prev'),
                nextArrow: $('.slider_nav .next')
            });
            $('#productSliderNav').slick({
                infinite: true,
                // centerMode: true,
                asNavFor: '#productSlider',
                slidesToShow: 6,
                //slidesToScroll: 3,
                arrows: false,
                lazyLoad: 'ondemand',
                focusOnSelect: true,
                variableWidth: true
            });
            $('#fullpageSlider').slick({
                infinite: true,
                speed: 600,
                centerMode: true,
                fade: true,
                asNavFor: '#fullpageSliderNav',
                slidesToShow: 1,
                slidesToScroll: 1,
                lazyLoad: 'ondemand',
                prevArrow: $('.fullpage_slider_nav .fp_prev'),
                nextArrow: $('.fullpage_slider_nav .fp_next')
            });
            $('#fullpageSliderNav').slick({
                infinite: false,
                // centerMode: true,
                asNavFor: '#fullpageSlider',
                slidesToShow: 3,
                slidesToScroll: 3,
                arrows: false,
                // loop: false,
                lazyLoad: 'ondemand',
                focusOnSelect: true,
                variableWidth: true
            });

            let evt = new Event(),
                m = new Magnifier(evt);

            m.attach({
                thumb: '.thumb',
                // large: 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/94/Starry_Night_Over_the_Rhone.jpg/1200px-Starry_Night_Over_the_Rhone.jpg',
                mode: 'inside',
                zoom: 2.2,
                zoomable: false
            });

        }

        $('.currency-drop .current').click(function () {
            $(this).toggleClass('active');
            $('.currency-drop ul').slideToggle();
        });


        if ($('#contentBlock.catalog').length) {
            let ias = jQuery.ias({
                container:  '#contentBlock.catalog',
                item:       '.galItem',
                pagination: '.pagination',
                // negativeMargin: 250,
                delay: 3000,
                next: '.next a'
            });

            // let text = $('.load-more-helper').data('text');
            //
            // ias.extension(new IASTriggerExtension({
            //     html: '<div class="load-more"><span class="load-more-btn">' + text + '</span></div>' // optionally
            // }));


            if ($('#contentBlock.catalog.ru').length) {
                ias.extension(new IASSpinnerExtension({
                    src: '/files/front/loader-ru.gif'
                }));
            } else {
                ias.extension(new IASSpinnerExtension({
                    src: '/files/front/loader.gif'
                }));
            }

            ias.on('load', function(event) {
                setTimeout(function () {
                    let scroll_pos = $(window).scrollTop();
                    $('html, body').animate({
                        scrollTop: scroll_pos - 1
                    }, 60);
                }, 2950);
            })

            // ias.on('rendered', function(items) {
            //     let $items = $(items);
            //     $items.addClass('loaded');
            //     hide_tags();
            // })

        }

        $('#mainNavMenu>li').click(function (event) {
            let w_w = $(window).width();

            if (w_w <= 960 && !$(this).hasClass('active')) {
                event.preventDefault();
                $(this).addClass('active');
            }

        });

        $('.item_prop_button').click(function () {
            if (!$(this).hasClass('disabled')) {
                let index = $(this).index();
                $('.item_prop_hidden').find('.radio').eq(index).find('input').click();
            }
        });

        let i_prop_btn = document.querySelectorAll('.item_prop_btn');
        if(i_prop_btn.length){
            i_prop_btn.forEach(function (element,index){
                if (element.checked){
                    $('.item_prop_hidden').find('.radio').eq(index).find('input').click();
                }
            });

        }

        function promo_pop() {
            if ($('.checkout-promo-popup').length) {
                let $popup = $('.checkout-promo-popup');
                let $btn = $('.promo-pop-btn');
                let $f_parent = $('#checkout');
                let f_parent_top = $f_parent.offset().top;
                let f_parent_left = $f_parent.offset().left;
                let btn_top = $btn.offset().top;
                let btn_left = $btn.offset().left;
                let btn_w = $btn.width();
                let btn_h = $btn.height();

                $popup.css({
                    'top' : btn_top + btn_h - f_parent_top,
                    'left' : btn_left - f_parent_left,
                    'width' : btn_w
                });
            }
        }

        let cost = '';
        let disc = $('.hidden-promo-result').data('discount');
        if (!disc) {disc = 0;}
        let lang = $('.language-choosing .active').index() == 0 ? '' : 'en/';

        function check_delivery() {
            let country = $('#orderform-country').val();

            let url = window.location.protocol + '//' + window.location.hostname + '/'+ lang +'order/radio-buttons?country=' + country;

            $.ajax({
                url: url,
                // context: $('#ajax_container'),
                context: $('body'),
                type:'GET',
                success: function(data){
                    // let feedbackContent = $(data).find('#ajax_content');
                    $('#delivery_radio_cont').html(data);
                },
                complete: function() {
                    promo_pop();

                    cost = $('#delivery_radio_cont .delivery_method_radio:checked').val();


                    if(cost) {

                        delivery_cost(cost, disc);
                    }

                    $('#delivery_radio_cont input').change(function () {
                        cost = $(this).val();
                        delivery_cost(cost, disc);
                    });



                }
            });
            return false;

        }


        function delivery_cost(cost, disc) {
            let total_url;
            console.log(typeof cost);
            if(typeof(cost) === 'undefined'){
                total_url = window.location.protocol + '//' + window.location.hostname + '/'+ lang +'order/get-values?discount=' + disc;
            }else{
                $('input#orderform-delivery_method').val(cost);
                total_url = window.location.protocol + '//' + window.location.hostname + '/'+ lang +'order/get-values?delivery=' +cost + '&discount=' + disc;
            }

            let total_arr;

            let del_cost;
            let discoutn;
            let total;


            $.ajax({
                url: total_url,
                // context: $('#ajax_container'),
                context: $('body'),
                type:'GET',
                success: function(data){
                    // let feedbackContent = $(data).find('#ajax_content');
                    // $('#delivery_radio_cont').html(data);
                    delivery_arr = JSON.parse(data);
                    del_cost = delivery_arr['shipment'];
                    discoutn = delivery_arr['discount'];
                    total = delivery_arr['total'];
                },
                complete: function() {

                    if (del_cost && del_cost!== 0) {
                        console.log(del_cost);
                        $('.checkout_shipping_cost .txtRight').text(del_cost);
                    }

                    if (discoutn && discoutn!== 0) {
                        $('.checkout_discount').removeClass('hidden');
                        $('.checkout_discount .txtRight').text(discoutn);
                    } else {
                        $('.checkout_discount .txtRight').text('0%');
                        $('.checkout_discount').addClass('hidden');
                    }

                    $('.checkout_order_total .txtRight').text(total);


                }
            });

        }



        if ($('#checkout').length) {
            check_delivery();

            $('.promo-pop-btn').click(function () {
                $(this).toggleClass('active');
                $('.checkout-promo-popup').slideToggle();
            });
            if ($('#promocodeform-promocode').val()) {
                $('.promo-pop-btn').click();
            }

            let p_json = $('.check-json-arr').text();
            let prices_arr = JSON.parse(p_json);

            // promo_pop();

            // $(window).resize(function () {
            //     promo_pop();
            // });

        }

        $('#orderform-country').change(function () {
            check_delivery();
        });


        $('.promo-btn').click(function () {
            let promo_val = $('#promo_test').val();
            let url_2 = window.location.protocol + '//' + window.location.hostname + '/'+ lang +'order/promo-code?code=' + promo_val;

            $.ajax({
                url: url_2,
                // context: $('#ajax_container'),
                context: $('body'),
                type:'GET',
                success: function(data){
                    disc = data;
                },
                complete: function() {

                    if (Math.floor(disc) == disc && disc!==0) {
                        $('.field-orderform-promocode input').val(promo_val);
                        $('.promo-message.succes').addClass('active');
                        $('.promo-message.succes .counter').text(disc);
                    } else {
                        disc = $('.hidden-promo-result').data('discount');
                        if (!disc) {disc = 0;}
                        console.log(disc);
                        $('.field-orderform-promocode input').val('');
                        if (disc === 'exists'){
                            $('.promo-message.error.exists').addClass('active');
                        }
                        else{
                            $('.promo-message.error.invalid').addClass('active');
                        }

                    }

                    delivery_cost(cost, disc);
                }
            });
        });

        $('.message-close-btn').click(function () {
            $(this).parent().removeClass('active');
        });

        if($('.main-mobile').length){
            $('.dropdownList').on('click', function (){
                //if(e.target !== e.currentTarget) return;
                $(this).toggleClass('opened');
            }).on('click', '.mobileNavSub', function(e) {
                // clicked on descendant div
                e.stopPropagation();
            }).on('click', '.dropdownList a', function(e) {
                // clicked on descendant div
                e.stopPropagation();
            });
        }
        if($('.customerCare').length){
            if(window.location.hash) {
                var hash = window.location.hash.substring(1);
                console.log(hash);
                $(`.customer_care_block[data-id='${hash}']`).find('.customer_care_html').addClass('active');
                // Fragment exists
            } else {
                // Fragment doesn't exist
            }
            window.addEventListener("hashchange", function (){
                var hash = window.location.hash.substring(1);
                $('.customer_care_html').removeClass('active');
                mainNav.classList.remove('active');
                $(`.customer_care_block[data-id='${hash}']`).find('.customer_care_html').addClass('active');
            }, false);
        }
        if($('#indexVideo').length){
            $('.videoControls.mute').click(function (){
                $(this).find('.icon').toggleClass('icon-mute icon-unmute');
                let vid = document.getElementById('indexVideoPlayer');

                if(vid.muted){
                    vid.muted = false;
                } else {
                    vid.muted = true;
                }
            });

        }

    });
})(jQuery);

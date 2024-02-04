jQuery.noConflict();
(function($){
    $(function(){
        $('.cartClose').click(function () {
            $('.header_basket_block').removeClass('active')
        })
    });
})(jQuery);

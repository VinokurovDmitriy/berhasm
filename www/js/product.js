jQuery.noConflict();
(function($){
    $(function(){
        let zoomBlocks = document.querySelectorAll('.zoomBlock');
        if(zoomBlocks.length > 0) {
            //const prodSlider = document.getElementById('productSlider');
            console.log('pinchZoom Activated!');
            zoomBlocks.forEach(function (el, index) {
                new PinchZoom.default(el, {
                    draggableUnzoomed: false,
                    minZoom: 1,
                    onZoomStart: function (object, event) {
                        // Do something on zoom start
                        // You can use any Pinchzoom method by calling object.method()
                    },
                    onZoomEnd: function (object, event) {
                        // Do something on zoom end
                    }
                });
            });
        }
    });
})(jQuery);

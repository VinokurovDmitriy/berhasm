/**
 * Created by Mr Awesome on 20.06.2016.
 */
$(function(){

    function readURL(block,input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                block.parents(".img_input_block").find("img").attr('src', e.target.result);

            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    if($(".img-input").length){
        $(".img-input").change(function(){
            readURL($(this),this);
        });
    }


    $(".bulkAction").click(function (event){
        event.preventDefault();
        let $url = $(this).attr('href');
        let $arr = [];
        $(".bulkCheckbox:checked").each(function (){
            $arr.push($(this).attr('name'));
        });
        if ($arr.length){
            $.ajax({
                url: $url,
                type:'GET',
                data: {ids:JSON.stringify($arr)},
                success: function(data){
                    console.log(data);
                },
                complete: function(data) {
                    console.log(data);
                }
            });
        }

    });
});

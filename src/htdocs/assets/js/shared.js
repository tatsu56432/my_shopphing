$(function () {

    $(window).on('load resize', function(){
        trimming('.js-thumbnail');
    });

    function trimming ($thumbnailBox) {
        $($thumbnailBox).each(function(){
            var box = $(this);
            var i = $('img',this);
            var box_w =$(box).width();
            var box_h =$(box).height();
            var i_w =$(i).width();
            var i_h =$(i).height();
            var i_par =i_w / i_h;
            var box_par =box_w / box_h;
            if (i_par > box_par) {
                $(i).css({
                    "width": "auto",
                    "height":"100%"
                });
            }else{
                $(i).css({
                    "width": "100%",
                    "height":"auto"
                });
            }
        });
    }

    // $js_productsItems = $(".js-productsItems");
    // $js_productsItem = $(".js-productsItems > li");
    // $js_productsItemTarget =




});
LP.use('jquery' , function(){

    $(function(){
        // set bar max width
        var maxWidth = 320;
        // find each theme list
        $(".J_theme-list").each(function(){
            var maxCount = 0;
            var $bars = $(this).find("[data-count]").each(function(){
                maxCount = Math.max( $(this).data('count') , maxCount );
            });

            $bars.each(function(){
                $(this).animate({
                    width: $(this).data('count') / maxCount * maxWidth
                } , 400 );
            });
        });
    });
});
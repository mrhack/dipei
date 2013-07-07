LP.use("jquery" , function( $ ){
    // init image margin-left style
    var $imgLis = $('.top-slider-imgs').find('li');

    $imgLis.find('img').css('margin-left' , - Math.max( $(window).width() / 2 , 800 ) );
    $('#J_slider-btns').find('.slider-left')
        .click(function(){
            slideRun( currIndex - 1 );
        })
        .end()
        .find('.slider-right')
        .click(function(){
            slideRun( currIndex + 1 );
        });
    var $users = $('#J_sliders-users').find('li')
        .click(function(){
            slideRun( $(this).index() );
        });

    var currIndex = 0;
    var className = "selected";
    var time = 300;
    var width = 125;
    var slideRun = function( tarIndex ){
        tarIndex = ( tarIndex + $users.length ) % $users.length;
        var $t = $users.eq( tarIndex );
        if( $t.hasClass( className ) ) return;
        currIndex = tarIndex;

        var $curr = $t.siblings( '.' + className );
        var cIndex = $curr.index();
        $t.parent()
            .children()
            .removeClass( className )
            .each(function( i ){
                var $t = $(this)
                    .animate({
                        width: i == currIndex ? 460 : 85
                        , left: i <= currIndex ? i * width : 500 + ( i - 1) * width
                        //, paddingLeft: i == currIndex ? 20 : 27
                        //, paddingRight: i == currIndex ? 20 : 13
                    } , time );
                if( i == currIndex ){
                    $t.addClass( className );
                }
            });

        // slide images
        $imgLis.stop( true , true ).hide()
            .eq( currIndex )
            .show()
            /*.css( 'opacity' , 0.5 )
            .animate({
                opacity: 1
            })*/;
    }
});
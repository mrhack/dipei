/**
 * @desc: desc
 * @date:
 * @author: hdg1988@gmail.com
 */
LP.use(['jquery','util'] , function( $ , util ){
    var $expand = $('.p-expand').click(function(){
        $('.p-day-desc').slideDown();
        $shrink.show();
        $expand.hide();
    });
    var $shrink = $('.p-shrink').click(function(){
        $('.p-day-desc').slideUp();
        $expand.show();
        $shrink.hide();
    });

    util.photoHoverShow( $('#J_photo-preview') , $('#J_photo-wrap').find('img') );
});
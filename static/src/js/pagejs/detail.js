/**
 * @desc: desc
 * @date:
 * @author: hdg1988@gmail.com
 */
LP.use('jquery' , function( $ ){
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
});
/*
 * util for LP
 * include common js function and others
 */
define(function( require , exports , model ){
    var $ = require('jquery');
    model.exports = {
        btnLoading: function(){

        }
        , createLoading: function( $dom , text ){
            $dom.html('<span class="i-icon i-loading"></span>' + ( text ? '<span>' + text + '</span>' : '' ) );
            return {
                remove: function(){
                    $dom.html('');
                }
            }
        }
        , length: function( str , isByte ){
            isByte = isByte === undefined? true : isByte;
            if(isByte){
                var oLength = str.length , tmp = str.replace(/[\u0000-\u0080]/g , '**'),
                    singleLetter = tmp.length - oLength;
                return singleLetter + (oLength - singleLetter)*2;
            }else{
                return str.length;
            }
        }
        , isEmail: function( str ){
            return !!/^[0-9a-zA-Z_][0-9a-zA-Z_.-]*@[0-9a-zA-Z_][0-9a-zA-Z.]+[a-zA-Z]$/.test( str );
        }
        , tab: function( $list , fn , sClass , event ){
            event = event || 'click';
            sClass = sClass || 'selected';
            $list.bind( event , function(){
                $list.removeClass( sClass );

                $(this).addClass( sClass );

                fn && fn.call( this , $list.index( this ) );

                return false;
            });
        }
        , scrollIntoView: function( $dom , $scroll ){
            // scroll into view
            var position    = $dom.position()
            ,   domHeight   = $dom.height()
            ,   scrollTop   = $scroll.scrollTop()
            ,   height      = $scroll.parent().height();
            if ( position.top <= 0 ){
                $scroll.scrollTop( scrollTop + position.top );
            } else if ( position.top + domHeight >= height ) {
                $scroll.scrollTop( position.top - height + scrollTop + domHeight );
            }
        }



        , loop: function( array , time , process , callback ){

        }
        // for form element
        , error: function( $el ){
            var attr    = '_e_t_';
            if( $el.data( attr ) ) return;
            var colors = ['#FFF' ,'#FEE','#FDD','#FCC','#FBB','#FAA','#FBB','#FCC','#FDD','#FEE','#FFF',
                '#FEE','#FDD','#FCC','#FBB','#FAA','#FBB','#FCC','#FDD','#FEE']
            ,   runTimer = function( el , index ){
                    var cssText = el.style.cssText;
                    var _errorTimer = null;
                    var _index = 0;
                    // fix for textarea is hidden ,and focus error
                    _errorTimer = setInterval(function(){
                        if(_index >= colors.length){
                            clearInterval(_errorTimer);
                            _errorTimer = null;
                            if( index == 0 ){
                                $e.focus();
                            }
                            el.style.cssText = cssText;
                            $(el).removeData( attr );
                            return;
                        }
                        el.style.background = colors[_index];
                        _index ++;
                    } , 40 );
                };
            $el.data( attr , 1 );
            // scroll first element intoview
            var off = $el.eq(0).offset();
            var w_st = $(window).scrollTop();
            var w_height = $(window).height();
            if( off.top < w_st + 50 || off.top > w_st + w_height ){
                $(window).animate({
                    scrollTop: off.top + 50
                } , 500 , '' , function(){
                    $el.each(function( index ){
                        runTimer( this , index );
                    });
                } );
            } else {
                $el.each(function( index ){
                    runTimer( this , index );
                });
            }
        }
    }
});
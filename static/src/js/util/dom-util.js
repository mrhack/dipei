/*
 * util for LP
 * include common js function and others
 */
define(function( require , exports , model ){
    var $ = require('jquery');

    model.exports = {
        btnLoading: function(){

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
    }
});
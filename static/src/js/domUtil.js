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
    }
});
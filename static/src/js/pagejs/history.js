/*
 * index action
 */
LP.use('jquery' , function( $ ){
    // actions in index.html
    // delete a lepei in my history
    var _lpCookie = '_lp';
    LP.action('del-his-lp' , function( data ){
        var id = data.id;
        var lpids = ( LP.getCookie(_lpCookie) || '' ).split(',');
        LP.each(lpids , function( index , val ){
            if( val == id ){
                lpids.splice( index , 1 );
                return false;
            }
        });

        // reset cookie
        LP.setCookie( _lpCookie , lpids.join(',') , 30 * 24 * 60 * 60 );
        if( lpids.length == 0 ){
            $(this).closest('.widget-history')
                .slideUp();
        } else {
            // remove dom in document
            $(this).closest('.user-item')
                .slideUp();
        }
    });
    // delete all lepeis in my history
    LP.action('del-all-lp' , function( ){
        LP.removeCookie( _lpCookie );
        $(this).closest('.widget-history')
            .slideUp();
    });
});
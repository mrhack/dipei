/*
 * ajax login model
 */
LP.use( ['jquery', 'util'] , function( $ , util ){
    // init reply box
    $('.reply-box').find('form')
        .submit(function(){
            var $form = $(this);
            var $area = $form.find('textarea');
            var data = LP.query2json( $(this).serialize() );
            if( !data.content || data.content.length > 500 ){
                util.error( $area );
                return false;
            }
            LP.ajax('addReply' , data , function( r ){
                $area.val('');
                $('.reply-wrap').append( r.html );
            });
            util.plus( $('#G_reply-count') );
            return false;
        });
    
    LP.action('del-reply' , function( data ){
        var $dom = $(this);
        LP.ajax('delReply' , data , function(){
            $dom.closest('.reply-item')
                .fadeOut();
            // reduce count
            util.reduce( $('#G_reply-count') );
        });
    });
});
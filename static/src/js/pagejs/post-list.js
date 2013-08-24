// for post list javascript actions
LP.use(['jquery','util'] , function( $ , util ){
    // show replies for post or project in post list page
    function initReplyBox( $replyWrap , pid ){
        // init reply wrap 
        $replyWrap.find('.btn')
            .click(function(){
                var $area = $(this).prev();
                var val = $area.val();
                if( !val ){
                    util.error( $area );
                } else {
                    // send ajax to add reply
                    LP.ajax('addReply' , {
                        pid: pid,
                        content: val
                    } , function( r ){
                        $replyWrap.find('.comment-list')
                            .prepend( r.html );
                    });
                }
            });
    }
    LP.action('show-post-reply' , function( data ){
        var $dom = $(this);
        var $item = $dom.closest('.post-item');
        var $replyWrap = $item.find('.comments-wrap');
        if( $replyWrap.length ){
            $replyWrap.remove();
        } else {
            LP.ajax('getReply' , {pid: data.pid , pageSize: 10 , mode : 1} , function( r ){
                $replyWrap = $( r.html )
                    .insertAfter( $dom.closest('.metas') )
                initReplyBox( $replyWrap , data.pid );
            });
        }
    });
});
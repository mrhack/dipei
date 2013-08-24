// for post list javascript actions
LP.use(['jquery','util'] , function( $ , util ){
    // show replies for post or project in post list page
    function initReplyBox( $replyWrap , pid ,  $click){
        // init reply wrap 
        var $form = $replyWrap.find('form')
            .submit(function(){
                var $area = $(this).find('textarea');
                var val = $area.val();
                if( !val ){
                    util.error( $area );
                } else {
                    // send ajax to add reply
                    LP.ajax('addReply' , {
                        pid: pid,
                        content: val
                    } , function( r ){
                        // clear content
                        $area.val('')
                            .focus();
                        $replyWrap.find('.comment-list')
                            .prepend( r.html );

                        // plus 1
                        util.plus( $click );
                    });
                }
                return false;
            })
            .find('textarea')
            .focus();
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
                initReplyBox( $replyWrap , data.pid , $dom );
            });
        }
    });


    LP.action('del-reply' , function( data ){
        var $dom = $(this);
        LP.ajax('delReply' , data , function(){
            $dom.closest('.reply-item')
                .fadeOut();

            // reduce count
            util.reduce( $dom.closest('.post-item')
                .find('[data-a="show-post-reply"]') );
        });
    });
});
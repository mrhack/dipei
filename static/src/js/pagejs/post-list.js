// for post list javascript actions
LP.use(['jquery','util'] , function( $ , util ){
    // show replies for post or project in post list page
    function initReplyBox( $replyWrap , data ,  $click){
        // init reply wrap 
        var rid , ruid;
        var $form = $replyWrap.find('form')
            .submit(function(){
                var $area = $(this).find('textarea');
                var val = $area.val();
                if( !val ){
                    util.error( $area );
                } else {
                    // send ajax to add reply
                    LP.ajax('addReply' , {
                        type: data.type,
                        pid: data.pid,
                        content: val,
                        rid: rid,
                        ruid: ruid
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
            .focus()
            .keydown(function( ev ){
                if( ev.ctrlKey && ev.which == 13 ){
                    $form.submit();
                }
            })
            .end();
        // init reply 
        $replyWrap.on('click' , 'a[data-a="reply-it"]' , function(){
            var $reply = $(this).closest('li');
            var name = $(this).data('name');
            rid = $reply.data('rid');
            ruid = $reply.data('ruid');

            var $area = $replyWrap.find('textarea');
            var text = _e('回复 ') + name + ': ';
            var rText = $area.val();
            if( rText.indexOf( text ) < 0 ){
                $area.val( text + rText );
            }
            util.toTail($area);
        });


        // init auto height of textarea
        util.autoHeight($replyWrap.find('textarea')[0] , 1 , 10);
    }
    LP.action('show-post-reply' , function( data ){
        var $dom = $(this);
        var $item = $dom.closest('.post-item');
        var $replyWrap = $item.find('.comments-wrap');
        if( $replyWrap.length ){
            $replyWrap.remove();
        } else {
            LP.ajax('getReply' , {pid: data.pid , pageSize: 10 , mode : 1 , type: data.type} , function( r ){
                $replyWrap = $( r.html )
                    .insertAfter( $dom.closest('.metas') )
                initReplyBox( $replyWrap , data , $dom );
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
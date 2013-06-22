/**
 * @desc: lepei auth js
 * @date:
 * @author: hdg1988@gmail.com
 */
 LP.use('jquery' , function( $ ){
    // add language
    $('#J_add-lang')
        .click( function(){
            $( this ).prev()
                .clone()
                .insertBefore( this );
        } );


    //
    function initUEditor( id ){
        LP.use('ueditor' , function( UE ){
            var _editor = new UE.ui.Editor({
                initialContent          : "hahahaha"
//                , initialFrameWidth     : 553
//                , theme                 : 'gztheme'
//                , elementPathEnabled    : false
//                , maximumWords          : 5000
//                , minFrameHeight        : 176
                , compressSide          : 1    // 压缩图片基准，1按照宽度
                , maxImageSideLength    : 540
                , toolbars              : [["fullscreen","insertimage" ,"emotion","fontfamily","fontsize","bold", "italic", "underline", "forecolor", 'justifyleft', 'justifycenter', 'justifyright',"link","removeformat","undo","redo","autotypeset"]]
                , focus                 : true
            });

           _editor.render( id );
        });
    }

    initUEditor(' J_ueditor ')
 });

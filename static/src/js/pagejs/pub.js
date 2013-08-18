LP.use(['ueditor' , 'util'] , function( UE , util ){

	// 1. init publisher
    var _editor = new UE.ui.Editor({
        initialContent          : ''
        , initialFrameHeight    : 176
        , compressSide          : 1    // 压缩图片基准，1按照宽度
        , maxImageSideLength    : 540
        , toolbars              : [["fullscreen","insertimage" ,"emotion","fontfamily","fontsize","bold", "italic", "underline", "forecolor", 'justifyleft', 'justifycenter', 'justifyright',"link","removeformat","undo","redo","autotypeset"]]
    });

    var pubArea = $('#G_pub-area')[0];
    _editor.render( pubArea );
    $( pubArea ).data( 'editor' , _editor );

    // init select tag
    util.tab($('.pub-tabs li') );

});
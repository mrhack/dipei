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

    // add theme
    $('#J_add-theme , #J_add-service')
        .click(function(){
            // check if has blank input
            var $ul = $(this).prev();
            var blankInput = false;
            var $inputs = $ul.find('input[type="text"]')
                .each(function(){
                    if( !this.value ){
                        blankInput = this;
                        return false;
                    }
                });

            if( blankInput ){
                $(blankInput).focus();
            } else {
                $('<li><input type="text"/></li>')
                    .appendTo( $ul )
                    .find('input')
                    .focus() ;
            }
        });


    // validator for auth step1
    var valid = require('validator');
    if( $('#J_lp-form').length ){
        valid.formValidator()
            .add(
                valid.validator('lepei_type')
                    .setRequired( _e('乐陪类型必填') )
                )
            .add(
                valid.validator('desc')
                    .setRequired( _e('乐陪描述必填') )
                    .setLength( 10 , 100 , _e('乐陪描述必须小于100个字') )
                )
            .add(
                valid.validator('desc')
                    .setRequired( _e('乐陪描述必填') )
                    .setLength( 10 , 100 , _e('乐陪描述必须小于100个字') )
                )
    }

    function initUEditor( id ){
        LP.use('ueditor' , function( UE ){
            var _editor = new UE.ui.Editor({
                initialContent          : ""
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

    initUEditor('J_ueditor')
 });

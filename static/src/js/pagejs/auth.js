/**
 * @desc: lepei auth js
 * @date:
 * @author: hdg1988@gmail.com
 */
 LP.use(['jquery' , 'validator'] , function( $ , valid ){
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

    // for step1
    // validator for auth step1
    if( $('#J_lp-form').length ){
        var val1 = valid.formValidator()
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
                valid.validator('agreement')
                    .setTipDom('#J_agreement-tip')
                    .setRequired( _e('请同意乐陪服务条款') )
                );

        // btn click
        var $lpForm = $('#J_lp-form').submit(function(){
            val1.valid(function(){
                // get lang data
                var lang = {};
                $('.J_lang').each(function(){
                    var $sels = $(this).find('select');
                    lang[ $sels.eq(0).val() ] = $sels.eq(1).val();
                });
                // get contact
                var contact = {};
                $('.contact').find('input')
                    .each(function(){
                        contact[ this.name ] = this.value;
                    });
                // get desc
                // get lepei_type
                var data = {};
                data.step = 1;
                data.lang = lang;
                data.contact = contact;
                $.each( ['lepei_type' , 'desc'] , function( i , v ){
                    data[v] = $('[name="' + v + '"]').val();
                });
                LP.ajax('auth' , data , function(){
                    window.location.href = window.location.href.replace(/#.*/ , '');
                });
            });
            return false;
        });
    }

    else if( $('#J_p-form').length ){
        // init ueditor
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

           _editor.render( 'J_ueditor' );
        });

        // add form validator
        var val2 = valid.formValidator()
            .add(
                valid.validator('title')
                    .setRequired( _e('标题必填') )
                    .setTipDom('#J_title-tip')
                )
            .add(
                valid.validator('price')
                    .setRequired( _e('价格必填') )
                )
            .add(
                valid.validator('desc')
                    .setRequired( _e('乐陪描述必填') )
                    .setLength( 10 , 100 , _e('乐陪描述必须小于100个字') )
                );

        $('#J_p-form').submit(function(){
            val2.valid( function(){
                alert(1);
            });
            return false;
        });

    }
 });

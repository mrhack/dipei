/**
 * @desc: lepei auth js
 * @date:
 * @author: hdg1988@gmail.com
 */
 LP.use(['jquery' , 'validator'] , function( $ , valid ){
    var $project = $('.project');
    if( !$project.length ) return;
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

    // add day
    var tpl = ' <div class="p-meta p-day">\
        <p class="day-tit br4 clearfix">\
            <i class="i-icon i-delete fr"></i>DAY#[day_num]\
        </p>\
        <input type="text" style="width:701px;"/>\
        <div class="lp-ueditor J_ueditor"></div>\
    </div>';
    $('#J_add-day').click(function(){
        var days = $(this).parent()
            .prevAll('.p-day')
            .length;
        var $dom = $(LP.format(tpl , {
            day_num: days + 1
        })).insertBefore( $(this).parent() );

        renderUeditor( $dom.find('.J_ueditor')[0] );

        // show all the icon
        $project.find('.p-day .i-delete').show();
    });

    // delete day
    $project.delegate('.p-day .i-delete' , 'click' , function(){
        var length = $project.find('.p-day').length;
        if( length == 1 ){
            return false;
        } else {
            $(this).closest('.p-day')
                .slideUp(function(){
                    $(this).remove();
                });
            // do with the day number
            $project.find('.day-tit')
                .each( function( i ){
                    $(this).html('<i class="i-icon i-delete fr"></i>DAY' + ( i + 1 ) );
                });
            //hide last delete icon
            if( length == 2 ){
                $project.find('.p-day .i-delete').hide();
            }
        }
    });

    // init local search
    LP.use('autoComplete' , function( auto ){
        auto.autoComplete( $('input') , {
            getData: function( cb ){
                return cb( [ 1 , 2 , 3 , 4 , 5 , 6 ] );
            }
            , renderData: function( data ){
                var a = ['<ul>'];
                $.each( data , function( i , v ){
                    a.push('<li>' + v + '</li>');
                } );
                a.push('</ul>');
                return a.join('');
            }
        });
    });
    // init ueditor
    var renderUeditor = function( dom ){
        LP.use('ueditor' , function( UE ){
            var _editor = new UE.ui.Editor({
                initialContent          : ""
    //                , initialFrameWidth     : 553
    //                , theme                 : 'gztheme'
    //                , elementPathEnabled    : false
    //                , maximumWords          : 5000
                , initialFrameHeight    : 176
                , compressSide          : 1    // 压缩图片基准，1按照宽度
                , maxImageSideLength    : 540
                , toolbars              : [["fullscreen","insertimage" ,"emotion","fontfamily","fontsize","bold", "italic", "underline", "forecolor", 'justifyleft', 'justifycenter', 'justifyright',"link","removeformat","undo","redo","autotypeset"]]
                // , focus                 : true
            });

           _editor.render( dom );
        });
    }
    renderUeditor( $('.J_ueditor')[0] );

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

 });

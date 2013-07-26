/**
 * @desc: lepei auth js
 * @date:
 * @author: hdg1988@gmail.com
 */
 LP.use(['jquery' , 'validator' , 'autoComplete' , 'html2json' , 'util'] ,
    function( $ , valid , auto , html2json , util){
    var $project = $('.project');
    var $form = $project.closest('form');
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
            var name = this.id == 'J_add-theme' ? 'custom_themes[]' : 'custom_services[]';
            if( blankInput ){
                $(blankInput).focus();
            } else {
                $('<li><input type="text" name="' + name + '"/></li>')
                    .appendTo( $ul )
                    .find('input')
                    .focus() ;
            }
        });

    // add day
    var tpl = '<div class="p-meta p-day br4 ">\
        <p class="day-tit clearfix"><i class="i-icon i-delete fr" style="display:none;"></i>DAY#[day_num]</p>\
        <div class="J_day-tit mgt5 input-style" contenteditable="true"></div>\
        <div class="lp-ueditor J_ueditor" name="desc"></div>\
    </div>';
    $('#J_add-day').click(function(){
        var days = $(this).parent()
            .prevAll('.p-day')
            .length;
        var $dom = $(LP.format(tpl , {
            day_num: days + 1
        })).insertBefore( $(this).parent() );

        // init ueditor
        renderUeditor( $dom.find('.J_ueditor')[0] );
        // init day path select
        renderPathComplete( $dom.find('.J_day-tit') );
        // show all the icon
        $project.find('.p-day .i-delete').show();
    });

    // remove path item
    $project.delegate('.path-item em' , 'click' , function(){
        $(this).closest('.path-item')
            .remove();
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
    var renderPathComplete = function( $dom ){
        auto.autoComplete( $dom , {
            availableCssPath: 'li'
            , getKey: function(){
                return $.trim( $dom.contents()
                    .filter(function(){return this.nodeType == 3;})
                    .text() );
            }
            , renderData: function(data){
                var aHtml = ['<ul>'];
                var num = 10;
                var key =  this.key;
                $.each( data || [] , function( i , v ){
                    if( i == num ) return false;
                    aHtml.push('<li lid="' + v.id + '">' +
                        [ v.name.replace(key , '<em>' + key + '</em>') ,
                        '<span class="c999">' + v.parentName + '</span>' ].join(' , ') +
                        '</li>');
                } );

                aHtml.push('</ul>');
                return aHtml.join('');
            }
            , onSelect: function( $d , data ){
                // 1. remove text node
                $dom.contents()
                    .filter(function(){return this.nodeType == 3;})
                    .remove();
                // 2. create path item
                var $item = $('<span></span>')
                    .attr('contenteditable' , 'false')
                    .addClass('path-item')
                    .html(data.name + '<em>X</em>')
                    .data('lid' , data.id);
                $dom.append( $item )
                    .append('&nbsp;');
            }
            // how to get data
            , getData: function(cb){
                var key = this.key;
                LP.ajax( 'locsug' , {k: decodeURIComponent( key )} , function( r ){
                    cb( r.data );
                } );
            }
        });
    }
    // init ueditor
    //var ueditorDataName = '__ueditor__';
    var renderUeditor = function( dom  , con ){
        LP.use('ueditor' , function( UE ){
            var _editor = new UE.ui.Editor({
                initialContent          : con || ''
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
           //$(dom).data( ueditorDataName , _editor );
        });
    }
    $('.J_ueditor').each(function(){
        renderUeditor( this , $(this).next().val() );
    });
    renderPathComplete( $('.J_day-tit') );
    // add form validator
    var val2 = valid.formValidator()
        .add(
            valid.validator('title')
                .setRequired( _e('标题必填') )
                .setLength( 10 , 60 , _e("标题最短10个字符，最长60个字符，一个中文算2个") )
                .setLengthType( 'byte' )
                .setTipDom('#J_title-tip')
            )
        .add(
            valid.validator('price')
                .setTipDom('#J_price-tip')
                .setRequired( _e('价格必填') )
            )
        .add(
            valid.validator('travel_themes[]')
                .setTipDom('#J_themes-tip')
                .addCallBack( function( val ){
                    if( !val ){
                        var $cThemes = $('[name="custom_themes[]"]');
                        var vals = [];
                        $cThemes.each( function(){
                            vals.push( this.value );
                        });

                        return vals.length ? '' : _e('至少选择一个或者输入自定义主题');
                    }
                })
            )
        .add(
            valid.validator('travel_services[]')
                .setTipDom('#J_services-tip')
                .addCallBack( function( val ){
                    if( !val ){
                        var $cThemes = $('[name="custom_services[]"]');
                        var vals = [];
                        $cThemes.each( function(){
                            vals.push( this.value );
                        });

                        return vals.length ? '' : _e('至少选择一个或者输入自定义服务');
                    }
                })
            )
        // 告知条款
        .add(
            valid.validator('notice')
                .setTipDom('#J_notice-tip')
                .setMaxLength( 500 , _e('最多500字') )
            )
        ;

    var validError = function( msg , $dom ){

    }
    var validDays = function( days ){
        // TODO .....
        var isSucc = true;
        $.each( days , function( i , day ){
            if( !day.lines ){
                validError( _e("至少插入一个位置") , '' );
            }
            if( day.desc.length > 10000 || day.desc.length < 20 ){
                validError( _e("最少20个字，最多10000个字") , '' );
            }
        });
        return isSucc;
    }
    $form.submit(function(){
        val2.valid(function(){
            // replace '+'' to ' '
            // collect data
            var data = LP.query2json( $form.serialize().replace(/\+/g , ' ') );

            // deal with custom_themes and custom_services
            if( data.custom_themes && LP.isString( data.custom_themes ) ){
                data.custom_themes = [ data.custom_themes ];
            }
            if( data.custom_services && LP.isString( data.custom_services ) ){
                data.custom_services = [ data.custom_services ];
            }

            // collect lines
            data.days = [];
            var lines = [];
            $('.p-day').each(function(){
                var paths = [];
                $(this).find('.path-item')
                    .each(function(){
                        paths.push( $(this).data('lid') );
                    });
                lines.push( paths );
            });
            //data.lines = LP.isString( data.lines ) ? [ data.lines ] : data.lines;
            data.desc = LP.isString( data.desc ) ? [ data.desc ] : data.desc;
            $.each( lines, function( i , line ){
                data.days.push({
                    lines: line
                    , desc: util.stringify( html2json.html2json( data.desc && data.desc[i] ? data.desc[i] : '' ) )
                });
            });
            delete data.desc;
            if( !validDays( data.days ) ){
                return false;
            }
            // post ajax data , for different interface to post data. need to bind
            // submit function data to the from.
            var submitFun = $form.data( 'submit' );
            if( submitFun ){
                submitFun( data );
            }
        });
        return false;
    });
 });

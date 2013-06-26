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
            var name = this.id == 'J_add-theme' ? 'custom_themes' : 'custom_services';
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
    var tpl = '<div class="p-meta p-day">\
        <p class="day-tit br4 clearfix"><i class="i-icon i-delete fr" style="display:none;"></i>DAY1</p>\
        <input type="text" class="J_day-tit" name="lines" style="width:701px;">\
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
            , renderData: function(data){
                var aHtml = ['<ul>'];
                var num = 10;
                var key =  this.key;
                $.each( data || [] , function( i , v ){
                    if( i == num ) return false;
                    aHtml.push('<li lid="' + v.id + '">' +
                        [ v.name.replce(key , '<span style="color:#058f31;">' + key + '</span>') ,
                        '<span class="c999">' + v.parentName + '</span>' ].join(' , ') +
                        '</li>');
                } );

                aHtml.push('</ul>');
                return aHtml.join('');
            }
            , onSelect: function( $dom , data ){
                $sug.val( data.name );
                $('input[name="lid"]').val( data.id );
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
    var ueditorDataName = '__ueditor__';
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
           $(dom).data( ueditorDataName , _editor );
        });
    }
    renderUeditor( $('.J_ueditor')[0] );
    renderPathComplete( $('.J_day-tit') );
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
        ;

    $form.submit(function(){
        val2.valid(function(){
            // replace '+'' to ' '
            // collect data
            var data = LP.url2json( $form.serialize().replace('+' , ' ') );

            // deal with custom_themes and custom_services
            if( data.custom_themes && LP.isString( data.custom_themes ) ){
                data.custom_themes = [ data.custom_themes ];
            }
            if( data.custom_services && LP.isString( data.custom_services ) ){
                data.custom_services = [ data.custom_services ];
            }
            data.days = [];
            data.lines = LP.isString( data.lines ) ? [ data.lines ] : data.lines;
            data.desc = LP.isString( data.desc ) ? [ data.desc ] : data.desc;
            $.each( data.lines, function( i ){
                data.days.push({
                    lines: util.stringify( data.lines[i].split(',') )
                    , desc: util.stringify( html2json.html2json( data.desc[i] ) )
                });
            });
            delete data.lines;
            delete data.desc;
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
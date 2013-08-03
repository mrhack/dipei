/*
 * index action
 */
LP.use(['jquery' , 'util'] , function( $ , util ){
    // for setting
    if( $('.J_p-setting').length ){
        var settingInit = function(){
            // init country
            var $countryInput = $('#J_country-name');
            util.searchCountry( $countryInput , function( data ){
                $('input[name="country"]').val( data.id );
                $countryInput.val( data.name );
            });
            // init lepei loc search
            var $locInput = $('#J_lid');
            util.searchLoc( $locInput , function( data ){
                $('input[name="lid"]').val( data.id );
                $locInput.val( data.name );
            });
        }
        // editor btn
        $('#J_profile-edit').click(function(){
            // hide the p-setting-view , show p-setting-edit
            var $view = $(this).closest('.p-setting-view')
                .hide()
                .next()
                .fadeIn();
            settingInit && settingInit();
            settingInit = null;
        });

        // upload avatar
        var initAvatarEdit = function(){
            util.upload( $("#J_avatar-upload") , {
                onSuccess: function( data ){
                    initAvatarCrop( data.url );
                }
            });
            // init crop
            //头像裁剪
            var jcrop_api, boundx, boundy;
            function initAvatarCrop ( url ){
                $("#upFile").val( url );
                url = LP.getUrl( url , "img" , 560 , 0 );
                $("#target").attr("src", url );
                $(".J_preview").attr("src", url );

                LP.use('jcrop' , function(){
                    $('#target').Jcrop({
                        minSize: [50,50],
                        setSelect: [0,0,200,200],
                        onChange: updatePreview,
                        onSelect: updateCoords,
                        aspectRatio: 1
                    }, function(){
                        // Use the API to get the real image size
                        var bounds = this.getBounds();
                        boundx = bounds[0];
                        boundy = bounds[1];
                        // Store the API in the jcrop_api variable
                        jcrop_api = this;
                    });
                });

                $(".imgchoose").show(1000);
                $("#avatar_submit").show(1000);
            }
            function updateCoords( c ){
                $('#x').val(c.x);
                $('#y').val(c.y);
                $('#w').val(c.w);
                $('#h').val(c.h);
            };
            function updatePreview(c){
                if (parseInt(c.w) > 0){
                    var rx = 112 / c.w;
                    var ry = 112 / c.h;
                    $('#preview').css({
                        width: Math.round(rx * boundx) + 'px',
                        height: Math.round(ry * boundy) + 'px',
                        marginLeft: '-' + Math.round(rx * c.x) + 'px',
                        marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                }
                {
                    var rx = 130 / c.w;
                    var ry = 130 / c.h;
                    $('#preview2').css({
                        width: Math.round(rx * boundx) + 'px',
                        height: Math.round(ry * boundy) + 'px',
                        marginLeft: '-' + Math.round(rx * c.x) + 'px',
                        marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                }
                {
                    var rx = 200 / c.w;
                    var ry = 200 / c.h;
                    $('#preview3').css({
                        width: Math.round(rx * boundx) + 'px',
                        height: Math.round(ry * boundy) + 'px',
                        marginLeft: '-' + Math.round(rx * c.x) + 'px',
                        marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                }
            };

            $avatarForm.submit(function(){
                var data = $avatarForm.serialize();
                if( !$("#upFile").val() ){
                    LP.error(_e('请上传图片'));
                    return false;
                }
                LP.ajax("avatar" , data , function( r ){
                    // crop image success
                    // 1. change head image
                    $('img').each(function(){
                        if( this.src.indexOf('\/image\/head.png') > 0 ){
                            var width = this.width;
                            var height = this.height;
                            this.src = LP.getUrl( r.data.url , 'img' , width , height );
                        }
                    });
                    // 2. clear previews
                    $('.J_preview').removeAttr('src');
                    $('#target').removeAttr('src');

                    // 3. clear form
                    $("#upFile").val('');
                });
                return false;
            });
            $('#J_avatar-cancel').click(function(){
                $avatarForm.hide();
                $lastSetting.fadeIn();
                return false;
            });
        }
        var $avatarForm = $('#J_avatar-form');
        var $lastSetting = null;
        $('#J_profile-avatar-edit').click(function(){
            if( initAvatarEdit ){
                initAvatarEdit();
                initAvatarEdit = null;
            }
            $lastSetting = $('.J_p-setting:visible').hide();
            // show the section
            $avatarForm.fadeIn();
        });

        // save btn event init
        $('#J_profile-form').submit(function(){
            var data = LP.query2json( $(this).serialize() );

            LP.ajax('setting' , data , function(){
                // refresh page
                LP.reload();
            });
            return false;
        });

        $('#J_profile-cancel').click(function(){
            var $edit = $(this).closest('.p-setting-edit')
                .hide()
                .prev()
                .fadeIn();
            return false;
        });


        // for birthday setting
        var $births = $('select[name^="birth"]');
        util.datetime( $births.eq(0), $births.eq(1), $births.eq(2) );
    }

    // for profile project view    p-project-view
    if( $('.J_project-form').length ){
        $('.J_project-form').data( 'submit' , function( data ){
            LP.ajax( data.id ? 'updateProject': 'addProject' , data , function(){
                window.location.href = "/profile/host/service/";
            });
        });
    }
    // ==========================================================================
    // for p-metra
    if( $('.J_p-metra').length ){
        // edit btn
        $('.J_p-metra .J_opts').click(function(){
            $(this).hide()
                .closest('.p-meta')
                .find('.p')
                .hide()
                .end()
                .find('.edit-wrap')
                .fadeIn();
        });
        // cancel btn
        $('.edit-btns a').click(function(){
            $(this).closest('.p-meta')
                .find('.edit-wrap')
                .hide()
                .end()
                .find('.p')
                .fadeIn()
                .end()
                .find('.J_opts')
                .show();
        });

        // add language
        $('#J_add-lang')
            .click( function(){
                $( this ).prev()
                    .clone()
                    .insertBefore( this )
                    .find('select')
                    .each(function(){
                        $(this).find('option')
                            .eq(0)
                            .attr('selected' , 'selected' );
                    });
            } );
        // language delete btn
        $(document).on('click' , '.J_remove-lang' , function(){
            $(this).parent()
                .remove();
        });
        // form sublit event
        var formConfigs = {
            "lepei_type": {
                afterAjax: function( $form ){
                    // 1. get lepei type desc
                    var desc = $form.find('[name="lepei_type"]')
                        .children(':selected')
                        .text();
                    // 2. hide the form
                    $form.hide()
                        .prev()
                        .fadeIn()
                        .html( desc )
                        .end()
                        .next()
                        .fadeIn();
                }
            },
            "desc": {
                validator: function( $form ){
                    var $textarea = $form.find('textarea');
                    var val = $textarea.val();
                    if( val.length < 10 || val.length > 100 ){
                        util.error( $textarea );
                        $('#J_desc-tip').show().html(_e('乐陪描述只能在10到100个字之间'));
                        return false;
                    }
                    return true;
                },
                afterAjax: function( $form ){
                    // 1. get lepei type desc
                    var desc = $form.find('[name="desc"]')
                        .val();
                    // 2. hide the form
                    $form.hide()
                        .prev()
                        .show()
                        .html( desc )
                        .end()
                        .next()
                        .fadeIn();
                }
            },
            "langs": {
                getData: function( $form ){
                    var $langWraps = $form.find('.J_lang');
                    var data = {};
                    $langWraps.each(function(){
                        var $selects = $(this).find('select');
                        data[$selects.eq(0).val()] = $selects.eq(1).val();
                    });
                    return {langs: data}
                },
                afterAjax: function( $form ){
                    // 1. get lepei type desc
                    var $langWraps = $form.find('.J_lang');
                    var aHtml = [];
                    $langWraps.each(function(){
                        var $selects = $(this).find('select');

                        var key = $selects.eq(0)
                            .children(':selected')
                            .text();
                        var value = $selects.eq(1)
                            .children(':selected')
                            .text();
                        aHtml.push( key + " : ( " + value + ' )' );
                    });
                    // 2. hide the form
                    $form.hide()
                        .prev()
                        .show()
                        .html( aHtml.join('<br/>') )
                        .end()
                        .next()
                        .fadeIn();
                }
            },
            "contacts": {
                validator: function( $form ){
                    // TODO  phone number and emial is required
                    return true;
                },
                afterAjax: function( $form ){
                    var aHtml = [];
                    $form.find('input')
                        .each(function(){
                            aHtml.push( $(this).data('label') + ' : ' + this.value );
                        });
                    $form.hide()
                        .prev()
                        .fadeIn()
                        .html( aHtml.join('<br/>') )
                        .end()
                        .next()
                        .fadeIn();
                }
            }
        }
        $('.edit-wrap')
            .submit(function(){
                var $form = $(this);
                var data = LP.query2json( $form.serialize() );
                var config = formConfigs[ $form.data('name') ];
                if( config.validator && !config.validator( $form ) ){
                    return false;
                }

                if( config.getData ){
                    data = config.getData( $form );
                }
                LP.ajax('saveProfile' , data , function( r ){
                    config.afterAjax( $form );
                });
                
                return false;
            });

        // for photo upload , init upload button
        util.upload( $('#J_upload') ,{
            "multi"             : false,
            "uploadLimit"       : 8,
            "uploader"          : '/image/uploadUserPhoto/',
            "onSuccess"         : function( data ){
                // add image to the list
                $("<li></li>").append(
                    $('<img/>')
                    .attr('src' , LP.getUrl(data.url , 'img' , 60 , 0 ))
                    )
                    .appendTo('#J_photo-wrap');
            }
        });

        // for image delete
        $('#J_photo-wrap').on('click' , '.J_delete' , function( ){
            var $btn = $(this);
            var src = $btn.prev()
                .attr('src');
            // send ajax to delete the image of user
            LP.ajax('removeUserPhoto' , {src:src} , function( res ){
                $btn.parent()
                    .fadeOut();
            });
        });
    }


    //==========================================================
    // actions for profile page
    //==========================================================
    // remove a project
    LP.action( "p-remove" , function( data ){
        var $tr = $(this).closest('tr');
        LP.ajax('removeProject' , data , function(){
            $tr.fadeOut();
        });
    } );
    LP.action( "p-remove-fd" , function( data ){
        LP.ajax('removeProject' , data , function(){
            window.location.href = "/profile/service/";
        });
    } );
});
/*
 * index action
 */
LP.use(['jquery' , 'util'] , function( $ , util ){
    // for setting
    if( $('.J_p-setting').length ){
        var settingInit = function(){
            // init country
            var $countryInput = $('#J_country-name');
            util.searchLoc( $countryInput , function( data ){
                $('input[name="country"]').val( data.id );
                $countryInput.val( data.name );
            } , 'country');
            // init lepei loc search
            var $locInput = $('#J_lid');
            util.searchLoc( $locInput , function( data ){
                $('input[name="lid"]').val( data.id );
                $locInput.val( data.name );
            } , 'city');
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
            var $btn = $("#J_avatar-upload");
            util.upload( $btn , {
                onComplete: function( file , response ){
                    var data = response.data;
                    $btn.closest('.p-meta')
                        .next()
                        .show()
                        .next()
                        .show();
                    initAvatarCrop( data.url , data.width );

                    // show btn
                    $('.btns').show();
                }
            });
            // init crop
            //头像裁剪
            var jcrop_api, boundx, boundy , rate = 1;
            function initAvatarCrop ( url , width){
                if( jcrop_api )
                    jcrop_api.destroy();
                $("#upFile").val( url );
                url = LP.getUrl( url , "img" , 560 , 0 );
                $("#target").attr("src", url );
                $(".J_preview").attr("src", url );

                rate = width / 560;
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
                $('#x').val(Math.round(c.x * rate));
                $('#y').val(Math.round(c.y * rate));
                $('#w').val(Math.round(c.w * rate));
                $('#h').val(Math.round(c.h * rate));
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
                    $('.p-head img,.uhead').each(function(){
                        var width = this.width;
                        var height = this.height;
                        this.src = LP.getUrl( r.data.url , 'img' , width , height );
                    });

                    LP.reload();
                    // 2. clear previews
                    // $('.J_preview').removeAttr('src');
                    // $('#target').removeAttr('src');

                    // 3. clear form
                    //$("#upFile").val('');
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


        // reset password
        var $resetForm = $('#J_reset-pw-form');
        var initResetPassword = function(){
            // init password strength
            util.passwordStrength( $resetForm.find('[name="password"]') , function( score ){
                score *= 10;
                $resetForm.find('.pw-strength span')
                    .removeClass('s')
                    .filter(function(index){
                        return index < score;
                    })
                    .addClass('s');
            });
            // init button event
            $resetForm.find('.btn')
                .click(function(){
                    // reset verror
                    $resetForm.find('.v-error')
                        .hide()
                        .html('')
                        .end()
                        .find('.v-right')
                        .hide();
                    // validator password
                    var data = LP.query2json( $resetForm.serialize() );
                    if( data.password.length < 6 ){
                        $resetForm.find('input[name="password"]')
                            .next()
                            .show()
                            .html('<i class="i-icon i-v-error"></i>' + _e('密码至少要6个字符'));
                        return false;
                    }
                    else if( data.password != data['confirm-password'] ){
                        $resetForm.find('input[name="confirm-password"]')
                            .next()
                            .show()
                            .html('<i class="i-icon i-v-error"></i>' + _e('两次输入不一致'));
                        return false;
                    }
                    LP.ajax('resetPW' , data , function(e){
                        $resetForm.find('.v-right')
                            .show();

                        $resetForm.find('input')
                            .val('');
                    } , function( e , r ){
                        if( r.err == -2001 ){
                            $resetForm.find('input[name="opw"]')
                                .next()
                                .show()
                                .html('<i class="i-icon i-v-error"></i>' + e );
                        }
                    });

                    return false;
                });
        }
        $('#J_change-pw').click(function(){
            if( initResetPassword ){
                initResetPassword();
                initResetPassword = null;
            }
            $lastSetting = $('.J_p-setting:visible').hide();
            // show the section
            $resetForm.fadeIn();

            return false;
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
        LP.action('del-photo' , function( data ){
            var $dom = $(this);
            LP.ajax('removeUserPhoto' , data , function(){
                $dom.closest('li')
                    .fadeOut();
            });
        });
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
                        $('#J_desc-tip').show().html(_e('小鲜描述只能在10到100个字之间'));
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
            "action"          : '/image/uploadUserPhoto/',
            "onComplete"         : function( file , response ){
                var data = response.data;
                // add image to the list
                $("<li></li>").append(
                        $('<img/>')
                        .attr('src' , LP.getUrl(data.url , 'img' , 60 , 0 ))
                    )
                    .append('<i data-a="del-photo" data-d="pname=' + data.url + '" class="J_delete i-icon i-delete"></i>')
                    .appendTo('#J_photo-wrap');
            }
        });

        // init photo hover show component
        util.photoHoverShow( $('#J_photo-preview') , $('#J_photo-wrap').find('img') );

    }


    //==========================================================
    // actions for profile page
    //==========================================================
    // remove a project
    LP.action( "p-remove" , function( data ){
        var $tr = $(this).closest('tr');
        LP.confirm(_e('确定要删除这个鲜旅吗?') , function(){
            LP.ajax('removeProject' , data , function(){
                $tr.fadeOut();
            });
        });
    } );
    LP.action( "p-remove-fd" , function( data ){
        LP.confirm(_e('确定要删除这个鲜旅吗?') , function(){
            LP.ajax('removeProject' , data , function(){
                window.location.href = "/profile/host/service/";
            });
        });
    } );


    // =========================================================
    // for msg
    // =========================================================
    LP.action('msg-block' , function( data ){
        var path = LP.parseUrl(location.href).path;
        window.location.href = path + '?tid=' + data.uid;
    });

    LP.action('msg-del' , function( data ){
        var $t = $(this);
        LP.ajax('delMsg' , { id: data.id } , function(){
            $t.closest('.msg-item')
                .fadeOut();
        });
        return false;
    });

    LP.action('msg-user-del' , function( data ){
        LP.ajax('delUserMsg' , {tid: data.tid} , function(){
            var path = LP.parseUrl().path;
            window.location.href = path;
        });
    });

    // ======================================================
    // for reply
    // ======================================================
    LP.action('del-reply' , function( data ){
        var $dom = $(this);
        LP.ajax('delReply' , data , function(){
            $dom.closest('.reply-item')
                .fadeOut();
        });
    });

    var replyTpl = '<section class="comments-wrap">\
                        <form class="comment-right clearfix">\
                            <div class="tip"></div>\
                            <textarea class="normal-input" placeholder="' + _e('填写评论') + '" rows="1" auto-height="true" style="height: 20px; line-height: 20px; overflow-y: hidden;"></textarea>\
                            <button class="btn btn-green">' + _e('发布') + '</button>\
                        </form>\
                    </section>';
     LP.action('r-reply' , function( data ){
        var $dom = $(this);
        var $li = $dom.closest('li');
        if( $li.find('.comments-wrap').length ){
            $li.find('.comments-wrap')
                .toggle();
        } else {
            var uname = $li.find('.u-name').html();
            var text = _e('回复') + ' ' + data.uname + ' : ';
            $(replyTpl).insertAfter($dom.closest('.reply-metas'))
                .find('textarea')
                .val(text)
                .end()
                .find('form')
                .submit(function(){
                    var $area = $(this).find('textarea');
                    var val = $area.val();
                    if( !val ){
                        util.error( $area );
                        return false;
                    }
                    LP.ajax('addReply' , {
                        type: data.type,
                        pid: data.pid,
                        content: val,
                        rid: data.rid,
                        ruid: data.ruid
                    } , function( r ){
                        // clear content
                        util.toTail( $area.val(text) );
                        util.success({
                            content:_e('回复成功')
                            , fadeOutTime:4000
                            , close: true
                            , $wrap:$area.prev()});
                    });
                    return false;
                });
        }
        if( $li.find('.comments-wrap textarea').is(':visible') ){
            util.toTail($li.find('.comments-wrap textarea'));
        }
    });

    $('#G_msg-form').submit(function(){
        var $t = $(this);
        var data = LP.query2json( $t.serialize() );
        if( !data.content || data.content.length > 300 ){
            util.error( $t.find('textarea') );
            return false;
        }
        LP.ajax('addMsg' , data , function( r ){
            // clear content
            $t.find('textarea')
                .val('');
            $('.msg-list')
                .prepend( r.html );
        });

        return false;
    })
    .find('textarea')
    .keyup(function(){
        var val = this.value;
        $('#G_msg-count')
            .find('em')
            .html( 300 - val.length );
    });




});
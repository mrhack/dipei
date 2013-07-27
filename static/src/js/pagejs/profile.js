/*
 * index action
 */
LP.use(['jquery' , 'util'] , function( $ , util ){
    // for setting
    if( $('#J_profile-edit').length ){
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
                $(".preview").attr("src", url );

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
                    $('.preview').removeAttr('src');
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
            $lastSetting = $('.p-setting:visible').hide();
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

    // for profile project view
    if( $('.project-form').length ){
        $('.project-form').data( 'submit' , function( data ){
            LP.ajax('addProject' , data , function(){
                window.location.href = window.location.href.replace(/#.*/ , '');
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
/*
 * index action
 */
LP.use(['jquery' , 'util'] , function( $ , util ){
    // for secction
    if( $('#J_profile-edit').length ){
        // editor btn
        $('#J_profile-edit').click(function(){
            // hide the p-setting-view , show p-setting-edit
            var $view = $(this).closest('.p-setting-view')
                .hide()
                .next()
                .fadeIn();
        });

        // init country
        util.searchCountry( $('#J_country-name') , function( data ){
            $('input[name="country"]').val( data.id );
        });
        // init lepei loc search
        util.searchLoc( $('#J_lid') , function( data ){
            $('input[name="lid"]').val( data.id );
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
                $("#target").attr("src", LP.getUrl( url , "img" ) );
                $(".preview").attr("src", LP.getUrl( url , "img" ) );

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
                LP.ajax("corpimage" , data , function(){
                    // crop image success
                });
                return false;
            });
            $('#J_avatar-cancel').click(function(){
                $avatarForm.hide();
                $lastSetting.fadeIn();
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
            var data = $(this).serialize();
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

    // edit a project, load
    LP.action( "p-edit" , function( data ){
    });
});
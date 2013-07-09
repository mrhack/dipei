LP.use(['jquery'/*,'datepicker'*/] , function( $ ){
    /*
    $(".j-datepicker" ).datepicker({
        showOn: 'click',
        minDate: '2013-06-14',
        onSelect: function( date , context ){
            $(context.input).find('.input-val')
                .html( date );
        }
    });

    */

    // show lepei type
    $('.J_dropdown').click(function(){
        var $widget = $(this);
        // hide other menus
        $('.dropdown-menu').hide();
        var $menus = $widget.find('.dropdown-menu')
            .show();
        $menus.on('click' , 'li' , function(){
            $widget.find('.input-val')
                .html( $(this).text() );

            // hide menu board
            $menus.hide();
            return false;
        });

        return false;
    });
});
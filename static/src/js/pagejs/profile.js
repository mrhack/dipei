/*
 * index action
 */
LP.use('jquery' , function( $ ){
    // for secction
    if( $('#J_profile-edit').length ){
        $('#J_profile-edit').click(function(){
            // hide the p-setting-view , show p-setting-edit
            var $view = $(this).closest('.p-setting-view')
                .hide();
            var $edit = $view.next()
                .show();
        });

        $('')
    }
});
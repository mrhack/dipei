LP.use('jquery' , function( $ ){
	var $pubArea = $('#G_pub-area');
	var $btn = $('#G_publisher-btn').click(function(){
		var editor = $pubArea.data('editor');

		var top = $pubArea.offset().top;
		$(window).scrollTop( top - 100 );
		editor.focus();
	});
});
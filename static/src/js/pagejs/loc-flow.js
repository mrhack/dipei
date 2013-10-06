LP.use('jquery' , function( $ ){
	var $pubWrap = $('.pub-wrap');
	var $btn = $('#G_publisher-btn').click(function(){
		var top = $pubWrap.offset().top;
		$(window).scrollTop( top - 100 );
		$pubWrap.find('input[name="title"]')
			.focus();
	});

});
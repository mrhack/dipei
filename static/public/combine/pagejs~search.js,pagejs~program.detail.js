/*! depei 2013-07-09 */
LP.use(["jquery"],function($){$(".J_dropdown").click(function(){var $widget=$(this);$(".dropdown-menu").hide();var $menus=$widget.find(".dropdown-menu").show();return $menus.on("click","li",function(){return $widget.find(".input-val").html($(this).text()),$menus.hide(),!1}),!1})});
/*! depei 2013-07-06 */

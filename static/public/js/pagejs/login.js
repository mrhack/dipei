/*! depei 2013-07-11 */
LP.use(["util"],function(util){var $loginWrap=$("#login-register");$loginWrap.find("form:visible").find("input").eq(0).focus(),$loginWrap.find(".j-reg").click(function(){return $loginWrap.find(".tab").eq(1).trigger("click"),!1}),util.tab($loginWrap.find(".tab"),function(index){$loginWrap.find(".tab-con").children().hide().eq(index).fadeIn().find("input").eq(0).focus()});var $lTip=$("#J_l-tip");$loginWrap.find(".login form").submit(function(){$lTip.html("");var data=$(this).serialize();return LP.ajax("login",data,function(){location.href=location.href.replace(/#.*$/,"")},function(msg){$lTip.html(msg).css("color","red")}),!1});var $rTip=$("#J_r-tip"),$regForm=$loginWrap.find(".register form").submit(function(){$rTip.html("");var data=$(this).serialize();return LP.ajax("reg",data,function(){location.href=location.href.replace(/#.*$/,"")},function(msg){$rTip.html(msg)}),!1});util.passwordStrength($regForm.find('[name="password"]'),function(score){score*=10,$regForm.find(".pw-strength span").removeClass("s").filter(function(index){return score>index}).addClass("s")})});
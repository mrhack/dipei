/*! depei 2013-07-06 */
define(function(require,exports){var $=require("jquery"),util=require("util"),__query_timer=null,mix=LP.mix,panels=[];$(document).bind("scroll click resize",function(){$.each(panels,function(i,panel){panel.hide()})});var template=["<div>","</div>"].join(""),defaultConfig={cached:!0,enableEmpty:!1,zIndex:2e6,hoverClass:"hover",availableCssPath:"",wrapClass:"",autoSelect:!0,supportKeyEvent:!0,width:"",leftOff:0,topOff:0,onSelect:null,onHover:null,hideWhenBlank:!1,loadingContent:"",getCacheKey:function(key){return key},renderHead:function(){},renderData:function(){},renderFoot:function(){},getData:function(cb){cb([])}},BaseSelectPanel=function(handler,o){this.status=0,this.cache={},this.config=mix(defaultConfig,o||{});var t=this,o=this.config;t.$handler=$(handler),o.width||(o.width=t.$handler.outerWidth()),t.$wrap=$(template).addClass("__auto_wrap "+o.wrapClass).css({position:"absolute","z-index":o.zIndex,width:o.width-2}).appendTo(document.body).hide(),t.$wrap.delegate(o.availableCssPath,"mouseover",function(){t.hover($(this))}).delegate(o.availableCssPath,"click",function(){return t.select($(this)),!1}).click(function(ev){ev.stopPropagation()}),o.supportKeyEvent&&t.$handler.keydown(function(ev){if(!t.$wrap.is(":hidden")){switch(ev.keyCode){case 40:t.movehover(1);break;case 38:t.movehover(-1);break;case 13:t.select(t.$hoverDom);break;case 9:case 27:t.hide();default:return}return!1}}),panels.push(t)};BaseSelectPanel.prototype={__getBody:function(){return this.$wrap.find(".__auto_body")},__getIndex:function(){return this.$hoverDom?this.$wrap.find(this.config.availableCssPath).index(this.$hoverDom):-1},show:function(left,top,key){var t=this,o=t.config,callback=function(data){if(o.cached&&(t.cache[cacheKey]=data),t.key==key&&0!=t.status){if(o.hideWhenBlank&&(!data||!data.length))return t.hide(),void 0;var html=o.renderData.call(t,data)||"",hHtml=o.renderHead?o.renderHead.call(t,data):"",fHtml=o.renderFoot?o.renderFoot.call(t,data):"";t.data=data,hHtml=hHtml?'<div class="__auto_head">'+hHtml+"</div>":"",fHtml=fHtml?'<div class="__auto_foot">'+fHtml+"</div>":"",html='<div class="__auto_body">'+html+"</div>",t.$wrap.css("height","auto").html(hHtml+html+fHtml).show();var $dom=t.$wrap.find(o.availableCssPath).eq(0);$dom.length&&o.autoSelect&&t.hover($dom);var $body=t.__getBody();o.maxHeight&&$body.height()>o.maxHeight&&$body.height(o.maxHeight).css({"overflow-y":"auto","overflow-x":"hidden"})}};if(t.$hoverDom=null,!o.enableEmpty&&!key)return t.hide(),void 0;t.status=1,t.key=void 0===key?t.$handler.val():key;var off=t.$handler.offset();top=(top||off&&off.top+t.$handler.outerHeight())+o.topOff,left=(left||off&&off.left)+o.leftOff,t.$wrap.html(o.loadingContent||"").css({position:"absolute",top:~~top,left:~~left})[o.loadingContent?"show":"hide"]();var cacheKey=o.getCacheKey(key);o.cached&&void 0!==t.cache[cacheKey]?callback(t.cache[cacheKey]):(clearTimeout(__query_timer),__query_timer=setTimeout(function(){o.getData.call(t,function(data){callback(data)},function(){t.$wrap.html('<span style="padding-left:10px;color:#FFD991;">出错啦，请稍候重试...</span>').show()})},150))},hide:function(){this.$wrap.hide(),this.status=0},select:function($dom){var t=this,o=t.config;if(t.$hoverDom=$dom||t.$hoverDom,t.hide(),t.$hoverDom&&t.$hoverDom.length){var index=t.__getIndex();o.onSelect&&o.onSelect.call(t,t.$hoverDom,t.data[index])}},hover:function($dom){if($dom&&$dom.length){var t=this,o=t.config,hoverClass=o.hoverClass,$lastHoverDom=t.$hoverDom;if(t.$hoverDom&&t.$hoverDom.removeClass(hoverClass),$dom.addClass(hoverClass),t.$hoverDom=$dom,!$lastHoverDom||t.$hoverDom.get(0)!==$lastHoverDom.get(0)){o.onHover&&o.onHover.call(t,t.$hoverDom,$lastHoverDom);var $body=t.__getBody();util.scrollIntoView(t.$hoverDom,$body)}}},movehover:function(step){var $list=this.$wrap.find(this.config.availableCssPath),len=$list.length;if(len){var index=this.$hoverDom?$list.index(this.$hoverDom):step>0?-1:0;index=(index+step+len)%len,this.hover($list.eq(index))}}},exports.autoComplete=function(input,cfg){var o=mix({width:$(input).outerWidth(),onSelect:function($dom){$input.val($dom.text())}},cfg),__timer=null,__suggest=new BaseSelectPanel(input,o),__show=function(){var off=$input.offset();__suggest.show(off.left,off.top+$input.outerHeight(),$input.val())},$input=$(input),eventFn=function(){clearTimeout(__timer),__timer=setTimeout(function(){__show()},300)};return $input.keyup(function(ev){switch(ev.which){case 37:case 38:case 39:case 40:return}eventFn()}),$input.focus(eventFn),__suggest}});
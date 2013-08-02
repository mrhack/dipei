/*! depei 2013-07-11 */
LP.use(["jquery","validator","autoComplete"],function($,valid,auto){if($("#J_add-lang").click(function(){$(this).prev().clone().insertBefore(this)}),$("#J_lp-form").length){var $sug=$("#J_loc-sug");auto.autoComplete($sug,{availableCssPath:"li",renderData:function(data){var aHtml=["<ul>"],num=10,key=this.key;return $.each(data||[],function(i,v){return i==num?!1:(aHtml.push('<li lid="'+v.id+'">'+[v.name.replace(key,"<em>"+key+"</em>"),'<span class="c999">'+v.parentName+"</span>"].join(" , ")+"</li>"),void 0)}),aHtml.push("</ul>"),aHtml.join("")},onSelect:function($dom,data){$sug.val(data.name),$('input[name="lid"]').val(data.id)},getData:function(cb){var key=this.key;LP.ajax("locsug",{k:decodeURIComponent(key)},function(r){cb(r.data)})}});var val1=valid.formValidator().add(valid.validator("lepei_type").setRequired(_e("乐陪类型必填"))).add(valid.validator("desc").setRequired(_e("乐陪描述必填")).setLength(10,100,_e("乐陪描述必须小于100个字"))).add(valid.validator("agreement").setTipDom("#J_agreement-tip").setRequired(_e("请同意乐陪服务条款")));$("#J_lp-form").submit(function(){return val1.valid(function(){var lang={};$(".J_lang").each(function(){var $sels=$(this).find("select");lang[$sels.eq(0).val()]=$sels.eq(1).val()});var contact={};$(".contact").find("input").each(function(){contact[this.name]=this.value});var data={};data.step=1,data.langs=lang,data.contacts=contact,$.each(["lid","lepei_type","desc"],function(i,v){data[v]=$('[name="'+v+'"]').val()}),LP.ajax("auth",data,function(){window.location.href=window.location.href.replace(/#.*/,"")})}),!1})}else $("#J_p-form").length&&$("#J_p-form").data("submit",function(data){LP.ajax("auth",data,function(){window.location.href=window.location.href.replace(/#.*/,"")})})});
/*! depei 2013-07-11 */
LP.use(["jquery","validator","autoComplete","html2json","util"],function($,valid,auto,html2json,util){var $project=$(".project"),$form=$project.closest("form");if($project.length){$("#J_add-theme , #J_add-service").click(function(){var $ul=$(this).prev(),blankInput=!1;$ul.find('input[type="text"]').each(function(){return this.value?void 0:(blankInput=this,!1)});var name="J_add-theme"==this.id?"custom_themes":"custom_services";blankInput?$(blankInput).focus():$('<li><input type="text" name="'+name+'"/></li>').appendTo($ul).find("input").focus()});var tpl='<div class="p-meta p-day br4 ">        <p class="day-tit clearfix"><i class="i-icon i-delete fr" style="display:none;"></i>DAY1</p>        <input type="text" class="J_day-tit" style="width:720px;" />        <input type="hidden" name="lines" />        <div class="lp-ueditor J_ueditor" name="desc"></div>    </div>';$("#J_add-day").click(function(){var days=$(this).parent().prevAll(".p-day").length,$dom=$(LP.format(tpl,{day_num:days+1})).insertBefore($(this).parent());renderUeditor($dom.find(".J_ueditor")[0]),renderPathComplete($dom.find(".J_day-tit")),$project.find(".p-day .i-delete").show()}),$project.delegate(".p-day .i-delete","click",function(){var length=$project.find(".p-day").length;return 1==length?!1:($(this).closest(".p-day").slideUp(function(){$(this).remove()}),$project.find(".day-tit").each(function(i){$(this).html('<i class="i-icon i-delete fr"></i>DAY'+(i+1))}),2==length&&$project.find(".p-day .i-delete").hide(),void 0)});var renderPathComplete=function($dom){auto.autoComplete($dom,{availableCssPath:"li",renderData:function(data){var aHtml=["<ul>"],num=10,key=this.key;return $.each(data||[],function(i,v){return i==num?!1:(aHtml.push('<li lid="'+v.id+'">'+[v.name.replace(key,"<em>"+key+"</em>"),'<span class="c999">'+v.parentName+"</span>"].join(" , ")+"</li>"),void 0)}),aHtml.push("</ul>"),aHtml.join("")},onSelect:function($d,data){$dom.val(data.name);var v=$dom.next().val();$dom.next().val(v?v+","+data.id:data.id)},getData:function(cb){var key=this.key;LP.ajax("locsug",{k:decodeURIComponent(key)},function(r){cb(r.data)})}})},renderUeditor=function(dom){LP.use("ueditor",function(UE){var _editor=new UE.ui.Editor({initialContent:"",initialFrameHeight:176,compressSide:1,maxImageSideLength:540,toolbars:[["fullscreen","insertimage","emotion","fontfamily","fontsize","bold","italic","underline","forecolor","justifyleft","justifycenter","justifyright","link","removeformat","undo","redo","autotypeset"]]});_editor.render(dom)})};renderUeditor($(".J_ueditor")[0]),renderPathComplete($(".J_day-tit"));var val2=valid.formValidator().add(valid.validator("title").setRequired(_e("标题必填")).setTipDom("#J_title-tip")).add(valid.validator("price").setRequired(_e("价格必填")));$form.submit(function(){return val2.valid(function(){var data=LP.url2json($form.serialize().replace(/\+/g," "));data.custom_themes&&LP.isString(data.custom_themes)&&(data.custom_themes=[data.custom_themes]),data.custom_services&&LP.isString(data.custom_services)&&(data.custom_services=[data.custom_services]),data.days=[],data.lines=LP.isString(data.lines)?[data.lines]:data.lines,data.desc=LP.isString(data.desc)?[data.desc]:data.desc,$.each(data.lines,function(i){data.days.push({lines:data.lines[i].split(","),desc:util.stringify(html2json.html2json(data.desc[i]))})}),delete data.lines,delete data.desc;var submitFun=$form.data("submit");submitFun&&submitFun(data)}),!1})}});
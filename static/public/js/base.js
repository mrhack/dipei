/*
 * combine file:
 * data: June 5, 2013, 8:49 pm
 */
!!(function(b){if(b.LP){b._LP=b.LP}var c={};var a=b.LP={mix:function(){var g={};for(var f=0,d=arguments.length;f<d;f++){for(var e in arguments[f]){g[e]=arguments[f][e]}}return g}};c.pageVar={};a.mix(a,{setPageVar:function(d){c.pageVar=a.mix(c.pageVar,d)},getPageVar:function(d){return c.pageVar[d]}});c.actions={};_addAction=function(e,d){_actions[e]=d},_needActiveAction=["add-follow-loc","un-follow-loc","forward","edit-loc-desc","add-block","del-block","buss-correction"],_needLoginAction=_needActiveAction,_fireAction=function(e,f,h){var d=_actions[e],g=$(f).data("actionCallBack");if(!d){return}return d.apply(f,[h,g])};GJ.use("gzCmbBase",function(){GJ.waiter(function(){return !!document.body},function(){$(document.body).delegate("[action-type]","click",function(g){var h=$(this).attr("action-data"),f=$(this).attr("action-type"),e=$(this).data("actionData"),d=$.inArray(f,_needLoginAction)>=0&&!GZ.trigger("login");if(d){return}if(!($.inArray(f,_needActiveAction)>=0&&!GZ.trigger("active"))){if(_fireAction(f,this,GJ.mix(GZ.queryToJson(h),e,true))===false){return false}}g.preventDefault()})},50)})})(window);

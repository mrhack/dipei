/*! depei 2013-06-16 */
!function(b){var e=/\W/g,d=document,f=document.getElementsByTagName("head")[0]||document.documentElement;b.importStyle=function(b,a){if(!a||(a=a.replace(e,"-"),!d.getElementById(a))){var c=d.createElement("style");if(a&&(c.id=a),f.appendChild(c),c.styleSheet){if(31<d.getElementsByTagName("style").length)throw Error("Exceed the maximal count of style tags in IE");c.styleSheet.cssText=b}else c.appendChild(d.createTextNode(b))}},define(b.config.data.dir+"plugin-style",[],{})}(seajs);
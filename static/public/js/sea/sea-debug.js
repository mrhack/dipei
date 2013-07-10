/*! depei 2013-07-11 */
!function(global,undefined){function isType(type){return function(obj){return Object.prototype.toString.call(obj)==="[object "+type+"]"}}function hasOwn(obj,key){return obj&&obj.hasOwnProperty(key)}function dirname(path){return path.match(DIRNAME_RE)[0]}function realpath(path){for(path=path.replace(DOT_RE,"/"),path=path.replace(MULTIPLE_SLASH_RE,"$1/");path.match(DOUBLE_DOT_RE);)path=path.replace(DOUBLE_DOT_RE,"/");return path}function normalize(uri){return uri=realpath(uri),HASH_END_RE.test(uri)?uri=uri.slice(0,-1):URI_END_RE.test(uri)||(uri+=".js"),uri.replace(":80/","/")}function parseAlias(id){var alias=configData.alias;return hasOwn(alias,id)?alias[id]:id}function parsePaths(id){var m,paths=configData.paths;return paths&&(m=id.match(PATHS_RE))&&hasOwn(paths,m[1])&&(id=paths[m[1]]+m[2]),id}function parseVars(id){var vars=configData.vars;return vars&&id.indexOf("{")>-1&&(id=id.replace(VARS_RE,function(m,key){return hasOwn(vars,key)?vars[key]:m})),id}function parseMap(uri){var map=configData.map,ret=uri;if(map)for(var i=0;i<map.length;i++){var rule=map[i];if(ret=isFunction(rule)?rule(uri)||uri:uri.replace(rule[0],rule[1]),ret!==uri)break}return ret}function isAbsolute(id){return ABSOLUTE_RE.test(id)}function isRelative(id){return RELATIVE_RE.test(id)}function isRoot(id){return ROOT_RE.test(id)}function addBase(id,refUri){var ret;return ret=isAbsolute(id)?id:isRelative(id)?dirname(refUri)+id:isRoot(id)?(cwd.match(ROOT_DIR_RE)||["/"])[0]+id.substring(1):configData.base+id}function id2Uri(id,refUri){if(!id)return"";var isRel=isRelative(id);return id=parseAlias(id),id=parsePaths(id),id=parseVars(id),id=addBase(id,isRel?refUri:configData.base),id=normalize(id),id=parseMap(id)}function getScriptAbsoluteSrc(node){return node.hasAttribute?node.src:node.getAttribute("src",4)}function request(url,callback,charset){var isCSS=IS_CSS_RE.test(url),node=doc.createElement(isCSS?"link":"script");if(charset){var cs=isFunction(charset)?charset(url):charset;cs&&(node.charset=cs)}addOnload(node,callback,isCSS),isCSS?(node.rel="stylesheet",node.href=url):(url=parseVars(url),node.async=!0,node.src=url),currentlyAddingScript=node,baseElement?head.insertBefore(node,baseElement):head.appendChild(node),currentlyAddingScript=undefined}function addOnload(node,callback,isCSS){var missingOnload=isCSS&&(isOldWebKit||!("onload"in node));return missingOnload?(setTimeout(function(){pollCss(node,callback)},1),void 0):(node.onload=node.onerror=node.onreadystatechange=function(){READY_STATE_RE.test(node.readyState)&&(node.onload=node.onerror=node.onreadystatechange=null,isCSS||configData.debug||head.removeChild(node),node=undefined,callback())},void 0)}function pollCss(node,callback){var isLoaded,sheet=node.sheet;if(isOldWebKit)sheet&&(isLoaded=!0);else if(sheet)try{sheet.cssRules&&(isLoaded=!0)}catch(ex){"NS_ERROR_DOM_SECURITY_ERR"===ex.name&&(isLoaded=!0)}setTimeout(function(){isLoaded?callback():pollCss(node,callback)},20)}function getCurrentScript(){if(currentlyAddingScript)return currentlyAddingScript;if(interactiveScript&&"interactive"===interactiveScript.readyState)return interactiveScript;for(var scripts=head.getElementsByTagName("script"),i=scripts.length-1;i>=0;i--){var script=scripts[i];if("interactive"===script.readyState)return interactiveScript=script}}function parseDependencies(code){var ret=[];return code.replace(SLASH_RE,"").replace(REQUIRE_RE,function(m,m1,m2){m2&&ret.push(m2)}),ret}function Module(uri){this.uri=uri,this.dependencies=[],this.exports=null,this.status=0}function resolve(ids,refUri){if(isArray(ids)){for(var ret=[],i=0;i<ids.length;i++)ret[i]=resolve(ids[i],refUri);return ret}var data={id:ids,refUri:refUri};return emit("resolve",data),data.uri||id2Uri(data.id,refUri)}function use(uris,callback){isArray(uris)||(uris=[uris]),load(uris,function(){for(var exports=[],i=0;i<uris.length;i++)exports[i]=exec(cachedModules[uris[i]]);callback&&callback.apply(global,exports)})}function load(uris,callback){var unloadedUris=getUnloadedUris(uris);if(0===unloadedUris.length)return callback(),void 0;emit("load",unloadedUris);for(var len=unloadedUris.length,remain=len,i=0;len>i;i++)!function(uri){function loadWaitings(cb){cb||(cb=done);var waitings=getUnloadedUris(mod.dependencies);0===waitings.length?cb():isCircularWaiting(mod)?(printCircularLog(circularStack),circularStack.length=0,cb(!0)):(waitingsList[uri]=waitings,load(waitings,cb))}function done(circular){!circular&&mod.status<STATUS_LOADED&&(mod.status=STATUS_LOADED),0===--remain&&callback()}var mod=cachedModules[uri];mod.dependencies.length?loadWaitings(function(circular){function cb(){done(circular)}mod.status<STATUS_SAVED?fetch(uri,cb):cb()}):mod.status<STATUS_SAVED?fetch(uri,loadWaitings):done()}(unloadedUris[i])}function fetch(uri,callback){function onRequested(){delete fetchingList[requestUri],fetchedList[requestUri]=!0,anonymousModuleData&&(save(uri,anonymousModuleData),anonymousModuleData=undefined);var fn,fns=callbackList[requestUri];for(delete callbackList[requestUri];fn=fns.shift();)fn()}cachedModules[uri].status=STATUS_FETCHING;var data={uri:uri};emit("fetch",data);var requestUri=data.requestUri||uri;if(fetchedList[requestUri])return callback(),void 0;if(fetchingList[requestUri])return callbackList[requestUri].push(callback),void 0;fetchingList[requestUri]=!0,callbackList[requestUri]=[callback];var charset=configData.charset;emit("request",data={uri:uri,requestUri:requestUri,callback:onRequested,charset:charset}),data.requested||request(data.requestUri,onRequested,charset)}function define(id,deps,factory){1===arguments.length&&(factory=id,id=undefined),!isArray(deps)&&isFunction(factory)&&(deps=parseDependencies(factory.toString()));var data={id:id,uri:resolve(id),deps:deps,factory:factory};if(!data.uri&&doc.attachEvent){var script=getCurrentScript();script?data.uri=script.src:log("Failed to derive: "+factory)}emit("define",data),data.uri?save(data.uri,data):anonymousModuleData=data}function save(uri,meta){var mod=getModule(uri);mod.status<STATUS_SAVED&&(mod.id=meta.id||uri,mod.dependencies=resolve(meta.deps||[],uri),mod.factory=meta.factory,mod.factory!==undefined&&(mod.status=STATUS_SAVED))}function exec(mod){function resolveInThisContext(id){return resolve(id,mod.uri)}function require(id){return exec(cachedModules[resolveInThisContext(id)])}if(!mod)return null;if(mod.status>=STATUS_EXECUTING)return mod.exports;mod.status=STATUS_EXECUTING,require.resolve=resolveInThisContext,require.async=function(ids,callback){return use(resolveInThisContext(ids),callback),require};var factory=mod.factory,exports=isFunction(factory)?factory(require,mod.exports={},mod):factory;return mod.exports=exports===undefined?mod.exports:exports,mod.status=STATUS_EXECUTED,mod.exports}function getModule(uri){return cachedModules[uri]||(cachedModules[uri]=new Module(uri))}function getUnloadedUris(uris){for(var ret=[],i=0;i<uris.length;i++){var uri=uris[i];uri&&getModule(uri).status<STATUS_LOADED&&ret.push(uri)}return ret}function isCircularWaiting(mod){var waitings=waitingsList[mod.uri]||[];if(0===waitings.length)return!1;if(circularStack.push(mod.uri),isOverlap(waitings,circularStack))return cutWaitings(waitings),!0;for(var i=0;i<waitings.length;i++)if(isCircularWaiting(cachedModules[waitings[i]]))return!0;return circularStack.pop(),!1}function isOverlap(arrA,arrB){for(var i=0;i<arrA.length;i++)for(var j=0;j<arrB.length;j++)if(arrB[j]===arrA[i])return!0;return!1}function cutWaitings(waitings){for(var uri=circularStack[0],i=waitings.length-1;i>=0;i--)if(waitings[i]===uri){waitings.splice(i,1);break}}function printCircularLog(stack){stack.push(stack[0]),log("Circular dependencies: "+stack.join(" -> "))}function preload(callback){var preloadMods=configData.preload,len=preloadMods.length;len?use(resolve(preloadMods),function(){preloadMods.splice(0,len),preload(callback)}):callback()}function config(data){for(var key in data){var curr=data[key];curr&&"plugins"===key&&(key="preload",curr=plugin2preload(curr));var prev=configData[key];if(prev&&isObject(prev))for(var k in curr)prev[k]=curr[k];else isArray(prev)?curr=prev.concat(curr):"base"===key&&(curr=normalize(addBase(curr+"/"))),configData[key]=curr}return emit("config",data),seajs}function plugin2preload(arr){for(var name,ret=[];name=arr.shift();)ret.push(loaderDir+"plugin-"+name);return ret}var _seajs=global.seajs;if(!_seajs||!_seajs.version){var seajs=global.seajs={version:"2.0.0b3"},isObject=isType("Object"),isArray=Array.isArray||isType("Array"),isFunction=isType("Function"),log=seajs.log=function(msg,type){global.console&&(type||configData.debug)&&console[type||(type="log")]&&console[type](msg)},eventsCache=seajs.events={};seajs.on=function(event,callback){if(!callback)return seajs;var list=eventsCache[event]||(eventsCache[event]=[]);return list.push(callback),seajs},seajs.off=function(event,callback){if(!event&&!callback)return seajs.events=eventsCache={},seajs;var list=eventsCache[event];if(list)if(callback)for(var i=list.length-1;i>=0;i--)list[i]===callback&&list.splice(i,1);else delete eventsCache[event];return seajs};var emit=seajs.emit=function(event,data){var fn,list=eventsCache[event];if(list)for(list=list.slice();fn=list.shift();)fn(data);return seajs},DIRNAME_RE=/[^?#]*\//,DOT_RE=/\/\.\//g,MULTIPLE_SLASH_RE=/([^:\/])\/\/+/g,DOUBLE_DOT_RE=/\/[^/]+\/\.\.\//g,URI_END_RE=/\?|\.(?:css|js)$|\/$/,HASH_END_RE=/#$/,PATHS_RE=/^([^/:]+)(\/.+)$/,VARS_RE=/{([^{]+)}/g,ABSOLUTE_RE=/(?:^|:)\/\/./,RELATIVE_RE=/^\./,ROOT_RE=/^\//,ROOT_DIR_RE=/^.*?\/\/.*?\//,doc=document,loc=location,cwd=dirname(loc.href),scripts=doc.getElementsByTagName("script"),loaderScript=doc.getElementById("seajsnode")||scripts[scripts.length-1],loaderDir=dirname(getScriptAbsoluteSrc(loaderScript))||cwd;seajs.cwd=function(val){return val?cwd=realpath(val+"/"):cwd};var currentlyAddingScript,interactiveScript,anonymousModuleData,head=doc.getElementsByTagName("head")[0]||doc.documentElement,baseElement=head.getElementsByTagName("base")[0],IS_CSS_RE=/\.css(?:\?|$)/i,READY_STATE_RE=/^(?:loaded|complete|undefined)$/,isOldWebKit=1*navigator.userAgent.replace(/.*AppleWebKit\/(\d+)\..*/,"$1")<536,REQUIRE_RE=/"(?:\\"|[^"])*"|'(?:\\'|[^'])*'|\/\*[\S\s]*?\*\/|\/(?:\\\/|[^/\r\n])+\/(?=[^\/])|\/\/.*|\.\s*require|(?:^|[^$])\brequire\s*\(\s*(["'])(.+?)\1\s*\)/g,SLASH_RE=/\\\\/g,cachedModules=seajs.cache={},fetchingList={},fetchedList={},callbackList={},waitingsList={},STATUS_FETCHING=1,STATUS_SAVED=2,STATUS_LOADED=3,STATUS_EXECUTING=4,STATUS_EXECUTED=5;Module.prototype.destroy=function(){delete cachedModules[this.uri],delete fetchedList[this.uri]};var circularStack=[];seajs.use=function(ids,callback){return preload(function(){use(resolve(ids),callback)}),seajs},seajs.resolve=id2Uri,global.define=define,Module.load=use;var configData=config.data={base:function(){var ret=loaderDir,m=ret.match(/^(.+?\/)(?:seajs\/)+(?:\d[^/]+\/)?$/);return m&&(ret=m[1]),ret}(),charset:"utf-8",preload:[]};seajs.config=config,config({plugins:function(){var ret,str=loc.search.replace(/(seajs-\w+)(&|$)/g,"$1=1$2");return str+=" "+doc.cookie,str.replace(/seajs-(\w+)=1/g,function(m,name){(ret||(ret=[])).push(name)}),ret}()});var dataConfig=loaderScript.getAttribute("data-config"),dataMain=loaderScript.getAttribute("data-main");if(dataConfig&&configData.preload.push(dataConfig),dataMain&&seajs.use(dataMain),_seajs&&_seajs.args)for(var methods=["define","config","use"],args=_seajs.args,g=0;g<args.length;g+=2)seajs[methods[args[g]]].apply(seajs,args[g+1])}}(this);
define(function( require , exports , model ){
    "use strict";
    var $ = require('jquery');
    require('./panel.css');

    var isIE6 = $.browser.msie && $.browser.version < 7;
    var P = {
        zIndex      : 3000000
        , panels    : {}
        , showCount : 0
        , maskCount : 0
        , onWinResize : function (evt) {
            $.each(P.panels, function(id, panel) {

                if ( panel.isVisible === false )
                    return;

                panel.resize();
            });
        }
    };
    var _getFullUrl = function(url){
        if (url){
            return url + (url.indexOf('?') == -1 ? '?' : '&') + '_=' + (+new Date()+Math.random());
        }
        return '';
    };

    function PanelConfig( panelObj, o ) {
        var t = this;
        t.type    = 'panel';
        t.content = '';        // 弹出层的内容 ( text_string | html_string | htmlelement object )
        t.title   = _e('Message Tip');//'信息提示';// 弹出层的标题, 如果为空则不显示标题栏 ( string )
        t.url     = '';        // 在弹出层显示的远程页面的url，如果同时设置了content和url，url将无效
        t.mask    = true;      // 是否显示背景覆盖层
        t.style   = 'default'; // 弹出层的样式
        t.className = '';  // 给弹出层定义的className
        t.overClassName = '';  // 当鼠标经过触发元素时，该元素的className
        t.destroy     = false; // 如果为true，则在关闭窗口的时候将整个Element移去
        //
        //t.autoCloseTime = 0; // 自动关闭倒计时，如果为0 则不关闭

        t.useText      = false;  // 是否对控制关闭等按钮使用文字
        t.closeAble    = true;   // 是否可显示控制关闭的按钮或文字
        t.submitButton = false;  // 是否显示提交按钮
        t.cancelButton = false;  // 是否显示取消按钮
        t.closeText    = _e('close');//'关闭'; // 表示关闭的文字
        t.refreshText  = _e('refresh');//'刷新'; // 表示刷新的文字
        t.submitText   = _e('OK');//'确定'; // 表示提交按钮上面的文字
        t.cancelText   = _e('cancel');//'取消'; // 表示取消按钮上面的文字

        // Callbacks (在函数内部，this作为当前对象的引用)
        t.onInit   = null; // 初始化时触发的事件
        t.onBeforeShow = null; // 显示弹出层之前触发的事件 ,如果返回false，则不显示弹出层
        t.onLoad   = null; // 如果远程载入页面，载入完成之后触发的事件
        t.onShow   = null; // 显示弹出层之后触发的事件
        t.onFocus  = null; // 弹出层聚焦时触发的事件
        t.onSubmit = null; // 当点击确定按钮时触发的事件
        t.onCancel = null; // 当点击取消按钮时触发的事件
        t.onClose  = null; // 当弹出层关闭时触发的事件
        t.onReisze = null; // 当弹出层改变了大小以后会触发的事件
        t.onBeforeClose = null; // 当弹出层关闭之前时触发的事件

        /**
         * 弹出层的宽度
         * 当inline为true是为100%，如果为0表示自适应宽度。
         * 如果iframe为true，必须指定width的值
         * @property width
         * @type int
         * @default 0
         */
        t.width     = -1;
        t.height    = -1;

        /**
         * 如果使用iframe加载远程页面，是否允许iframe有滚动条
         * @property iframeScrolling
         * @type boolean
         * @default true
         */
        t.iframeScrolling = false;

        /**
         * 触发弹出层的页面元素的id或对象
         * @property handleElement
         * @type string|object
         * @default null
         */
        t.handleElement = null;

        /**
         * 是否使用iframe加载远程页面
         * @property iframe
         * @type boolean
         * @default false
         */
        t.iframe = false;

        /**
         * 给触发元素定义的className
         * 暂只对tooltip有效
         * @property handleElementClassName
         * @type string
         * @default ''
         */
        t.handleElementClassName = '';
        /**
         * 如果使用iframe载入远程页面，要传给iframe子窗口的参数
         * @property paramsForChild
         * @type object
         * @default {}
         */
        t.paramsForChild = {};


        LP.mix(this , o || {} , true);
        /**
         * 如果使用iframe载入远程页面，要给iframe子窗口调用的函数
         * 将自动添加如下几个函数
         * <ul>
         *     <li><strong>setTitle(title)</strong> &nbsp; &nbsp; 设置弹出层的标题</li>
         *     <li><strong>close()</strong> &nbsp; &nbsp; 关闭弹出层</li>
         *     <li><strong>resize(width, height)</strong> &nbsp; &nbsp; 修改弹出层的大小</li>
         *     <li><strong>resetPlace()</strong> &nbsp; &nbsp; 重置弹出层的位置</li>
         * </ul>
         * @property handlersForChild
         * @type object
         * @default {}
         */
        $.extend(t.handlersForChild , {
            setTitle : function(title){
                panelObj.setTitle(title);
            },
            //setContent : function(content){
            //    panelObj.setContent(content);
            //},
            close : function(seconds){
                panelObj.close(seconds);
            },
            resize : function(width, height){
                panelObj.resize(width, height);
            }
        });



        if (t.url == '#') t.url = '';

        t.width  = parseInt(t.width);
        t.height = parseInt(t.height);

        if (o.hideHead) { t.title = ''; }

        if (t.style.indexOf('text') != -1) t.useText =  true;
        if (t.type == 'alert' || t.type == 'confirm' || t.type == 'loading') t.mask = true;
        if (t.type == 'alert' || t.type == 'confirm') {
            t.submitButton = true;
            if (t.type == 'confirm') t.cancelButton = true;
        }
    };

    // Create a new panel object
    var Panel = function ( o ) {

        var t = this;
        var o = o || {};

        // 随机不重复的id
        t.id = o.id || LP.guid();

        // Return the created one.
        var old = P.panels[t.id];
        if ( old ) { return old; }

        P.panels[t.id] = t;

        o = t.config      = new PanelConfig( t, o );
        t._styleClassName = t.config.style ? 'lpn_panel_' + t.config.style : '';


        // Elements
        t.$panel    = null; // 创建的最高级的Element, 即Mask，它的大小跟viewport一样大。
        t.$wrapper  = null; // $panel的子el，用于存放Head, Content, Button等。
        t.$head     = null; // $wrapper的子el，用于显示Title。
        t.$content  = null; // $wrapper的子el，用于存放用户的实际内容。
        t.$foot     = null; // $wrapper的子el。
        t.$loader   = null; // $wrapper的子el，loading效果
        t.$btnGroup = null; // $foot的子el，用于放submit按钮和cancel按钮。
        t.$headerBtnGroup = null; // $wrapper的子el，用于放关闭按钮。
        t.$btnClose = null; // $headerBtnGroup的子el，关闭按钮。

        t.iframeObj = null; // 用于覆盖content的iframe

        // Other props
        t.isVisible     = false;
        t.loaded        = false;


        // =========== Creating
        // 创建Element
        t.$panel = $('<div><span class="lpn_ghost"></span></div>').data('id', t.id);
        t.setMask( o.mask );

        // 创建Wrapper
        var CName = 'lpn_panel';
        if ( o.type )            CName += ' lpn_panel_' + o.type;
        if ( t._styleClassName ) CName += ' ' + t._styleClassName;
        if ( o.className )       CName += ' ' + o.className;
        var topP   = $('<div class="' + CName +'"></div>')
                .appendTo(t.$panel)
                .data( 'panel' , t );
        t.$wrapper = $('<div class="lpn_wrapper" id="' + t.id + '"></div>').appendTo(topP);

        // 创建Content
        t.$content = $('<div class="lpn_canvas"></div>').appendTo(t.$wrapper);
        t.setContent( o.content );

        // 创建Head (必须在Content创建了之后创建)
        t.setTitle( o.title );

        // 创建其他东西 (必须在Content创建了之后创建)
        o.submitButton && t.addSubmitButton();
        o.cancelButton && t.addCancelButton();
        t.setCloseAble(o.closeAble);

        // Add to body
        t.$panel.hide().appendTo('body');

        // Everything is setup
        o.onInit && o.onInit.call(t);
    }

    Panel.prototype = {

        setMask : function ( showMask ) {

            var t = this;
            t.config.mask = showMask;

            if ( showMask ) {
                // add css fo fix css load problem
                t.$panel
                 .addClass('lpn_mask')
                 .css( isIE6 ? {position: 'absolute'} : {position:'fixed', top: 0, left: 0 });
            } else {
                t.$panel.removeClass('lpn_mask');
            }

        }

        // title可以是一个HTMLElement, jQuery Object或者string
        , setTitle : function(title)
        {
            var t = this;
            var o = t.config;

            o.title = title;

            if ( title ) {

                t.createHead();

                if ( typeof title == 'object' )
                {
                    t.$head.replaceWith(title);
                    t.$head = $(title);
                } else {
                    t.$head.html( title );
                }
            } else if (t.$head) {
                // Remove title if title is null or ''
                t.$head.remove();
                t.$head = null;
            }
            return this;
        }

        , createHead : function() { !this.$head && (this.$head = $('<div class="hd"></div>').insertBefore(this.$content)); }
        , createFoot : function() { !this.$foot && (this.$foot = $('<div class="ft"></div>').insertAfter(this.$content)); }
        , createBtnGroup : function() {
            if ( !this.$btnGroup )
            {
                this.createFoot();
                this.$btnGroup = $('<div class="lpn_button_group"></div>').appendTo(this.$foot);
            }
        }
        , addSubmitButton : function() {
            var t    = this;
            var o    = t.config;
            var handler = function() {
                if ( o.onSubmit && o.onSubmit.call(t) === false ) {
                        return false;
                }
                t.close(0, "Submmited");
            }
            t.createBtnGroup();
            $('<button class="lpn_submit" type="button"><span>' + o.submitText + '</span></button>')
                .appendTo(t.$btnGroup)
                .bind('click', handler)
                .bind('keypress', function(e) { if (e.keyCode == 13) handler(); });
            return this;
        }
        , addCancelButton : function() {
            var t = this;
            var o = t.config;

            t.createBtnGroup();
            $('<button type="button" class="lpn_cancel"><span>' + o.cancelText + '</span></button>')
                .appendTo(t.$btnGroup)
                .bind('click', function() {
                    if( o.onCancel && o.onCancel.call(t) === false ){
                        return false;
                    }
                    t.close("Canceled");
                });
            return this;
        }

        /**
         * 在顶部添加控制按钮
         *
         * @method addHeaderButton
         * @param {string|object} html 要添加的表示按钮的html
         * @param {Function} callback 点击该按钮的回调函数
         * @return {object} 返回该按钮的jquery对象
         */
        , addHeaderButton : function(html, callback)
        {
            var t = this;
            var h = t.$headerBtnGroup;

            if (!h) {
                t.$headerBtnGroup = h = $('<div class="lpn_ctrl_group"></div>').appendTo(t.$wrapper);
            }

            var $btn = $(html).appendTo(h)
                            .bind('focus', function() { this.blur(); });

            if ( callback ) {
                $btn.bind('click', function(e) { callback.call(t, e); return false; });
            }

            return $btn;
        }
        , setCloseAble : function(bool, text) {
            var t = this;
            var o = t.config;

            if ( typeof bool == 'boolean' ) {
               o.closeAble = bool;
            }
            if ( text ) {
                o.closeText = text;
            }

            if ( o.closeAble ) {
                t.$btnClose = t.$btnClose
                                || t.addHeaderButton('<a class="lpn_close" href="#">'
                                                        + ((o.useText && o.closeText) || '&nbsp;')
                                                        + '</a>', function() { t.close(); });
                t.$btnClose.show();
            } else if (t.$btnClose)
            {
                t.$btnClose.hide();
            }

            return this;
        }

        , show : function() {

            var t = this;
            var o = t.config;

            if ( t.isVisible ) { return t; }

            // BeforeShow CB
            if( o.onBeforeShow && o.onBeforeShow.call(t) === false ){
                return t;
            }

            t.isVisible = true;

            // Setup content
            if (o.url) t.loaded = false;
            t.loadContent();

            // Resize
            if ( o.width  != -1 ) {
                t.$wrapper.width(o.width);
                t.$content.width(o.width);
            }
            if ( o.height != -1 ) {
                // Height only applies to content
                t.$content.height(o.height);
            }

            // Hide other's background
            $('.lpn_mask').addClass('lpn_mask_tr');

            // Show
            t.resize( o.width  == -1 ? undefined : o.width,
                      o.height == -1 ? undefined : o.height )
             .$panel.css('z-index', P.zIndex)
                    .show()
                    .removeClass('lpn_mask_tr');

            ++P.zIndex;
            ++P.showCount;

            if ( o.mask ) {
                ++P.maskCount;
                //$('body').addClass('lpn_masked');
            }

            if ( P.showCount == 1 ) {
                $(window).bind('resize', P.onWinResize);
            }

            // t.focus();

            // Show CB
            o.onShow && o.onShow.call(t);

            return t;
        }

        , _closeTimer : null

        /**
         * 关闭弹出层
         *
         * @method close
         * @param {int} speed 延迟关闭的秒数。空表示渐关闭，0表示立即关闭
         * @return {object} 返回当前实例的引用
         */
        , close : function(seconds, status) {

            var t = this;

            if (t._closeTimer){
                t._closeTimer.cancel();
                t._closeTimer = null;
            }

            if ( !t.isVisible ) return t;

            var o  = t.config;
            status  = !status && typeof seconds === 'string' ? seconds : status;
            seconds = typeof seconds === 'number' ? parseInt(seconds) : 0;


            var hide = function () {
                if ( !t.isVisible ) return;

                if (o.onBeforeClose && o.onBeforeClose.call(t) !== false)
                    return;

                t.isVisible = false;
                t.$panel.hide();

                // Show the last panel's background
                var $panels = $('.lpn_mask');
                for ( var i = $panels.length; i >= 0; --i ) {
                    var p = $panels.eq(i);
                    if ( p.is(':visible') ) {
                        p.removeClass('lpn_mask_tr');
                        break;
                    }
                }

                --P.showCount;
                if ( P.showCount == 1 ) {
                    $(window).unbind('resize', P.onWinResize);
                }

                if ( o.mask ) {
                    --P.maskCount;
                    if ( P.maskCount == 0 )
                        $('body').remove('lpn_masked');
                }

                if(o.handleElement && o.overClassName)
                    o.handleElement.removeClass(o.overClassName);

                // ========== LEGACY
                /*
                if (o.url) {
                    if (t.iframeObj) {
                        t.iframeObj.destroy();
                        t.iframeObj = null;
                    } else if (t.$content) {
                        t.$content.html('');
                    }
                }*/
                // LEGACY ----------
                if (o.destroy) {
                    t.$panel.remove();
                }
                o.onClose && o.onClose.call(t, status);
            }

            if (seconds == 0) {
                hide();
            } else {
                t._closeTimer = setTimeout( hide, seconds * 1000 );
            }

            return t;
        }

        , resize : function(dw, dh){
            // All we need to do.
            // 1. Make sure mask is covering the full viewport
            // 2. NOT!!! Make sure the panel fits the viewport as much as possible
            // 3. NOT!!! Make sure the panel is at the center
            // 4. TODO : Change the iframe size.

            var t = this;
            var o = t.config;
            var width = $(window).width();
            var height = $(window).height();

            var w = isIE6 ? Math.min(width , $(document.body).width()) : width,
                h = isIE6 ? Math.min(height , $(document.body).height()) : height;

            // 1.
            t.$panel.css({
                width    : w,
                height   : h
            });

            // 2.
            if (dw) {
                o.width = dw;
                this.$wrapper.width(dw);
                this.$content.width(dw);
            }

            if (dh) {
                o.height = dh;
                this.$content.height(dh);
            }

            return t;
        }

        /**
         * 设置弹出层中显示的远程页面的url
         *
         * @method setUrl
         * @param {string} url 远程页面的url
         * @param {boolean} iframe 是否使用iframe，默认为null
         * @return {object} 返回当前实例的引用
         */
        , setUrl : function(url, iframe) {
            if (url) {
                var t = this;
                var o = t.config;
                o.url = url;
                o.content = '';
                t.loaded = false;
                if( typeof iframe === 'boolean' ) o.iframe = iframe;
                t.isVisible === true && t.loadContent();
            }
            return this;
        }

        , setContent : function(content) {
            var t = this;
            var o = t.config;

            if (content) {
                if (typeof content == 'string'){
                    o.content = content;
                } else {
                    var ct = $(content).get(0);
                    o.content = (typeof ct == 'object') ? ct : null;
                }
            }

            if (o.content) {
                o.url    = null;
                o.iframe = false;
                t.loaded = false;
                if (t.isVisible === true) t.loadContent();
            }

            return this;
        }

        , showLoading : function()
        {
            if (this.config.url) {
                this.$loader = this.$loader || $('<div class="lpn_load">&nbsp;</div>').prependTo(this.$content);
                this.$loader.show();
            }
            return this;
        }
        , hideLoading : function()
        {
            this.$loader && this.$loader.hide();
            return this;
        }

        , loadContent : function() {

            var t = this;
            var o = t.config;

            if ( t.loaded ) return this;
            t.loaded = true;


            var canvas;
            var bd_class;

            if ( (!o.url || o.url == '#') && !o.content && o.type != 'loading' ) {
                alert('No content or URL');
                return this;
            }
            // load width iframe
            if (o.url && o.iframe) {

                // TODO : Check this block
                // Deal with iframe

                t.resize( o.width || 320, o.height || 200 );
                t.showLoading();

                if ( !t.iframeObj ){
                    t.iframeObj = GJ.createIframe({
                        containerId : t.$content
                        , url       : o.url
                        , scrolling : o.iframeScrolling
                        , onLoad    : function() {
                            t.hideLoading();
                            // reset iframe css
                           // console.log('OUT--------------:',$(t.iframeObj.iframe).css());
                            if (!!t.iframeObj && !!t.iframeObj.iframe) {
                                $(t.iframeObj.iframe).css({
                                    'position': 'static',
                                    'width': '100%',
                                    'height': '100%'
                                }).show();
                            }
                            if ( o.type != 'alert' && o.type != 'confirm' && o.onLoad )
                                o.onLoad.call(t);
                        }
                        , autoSetHeight    : false
                        , useBrowseCache   : false
                        , paramsForChild   : o.paramsForChild
                        , handlersForChild : o.handlersForChild
                    });
                    $(t.iframeObj.iframe).hide();
                } else {
                    t.iframeObj.redirect(o.url);
                }

                return this;
            }

            // Plain content
            if ( t.iframeObj ) {
                t.iframeObj.destroy();
                t.iframeObj = null;
            }

            if ( o.url ) {
                // Load stuff
                t.showLoading();
                $.get( _getFullUrl(o.url) , function( r ){
                    t.hideLoading();
                    t.$content.html( r.html );
                } , 'json' );
                // t.$content.load( _getFullUrl(o.url), null, function() {
                //     t.hideLoading();
                // });

            } else {
                typeof o.content === 'string' ?
                    t.$content.html(o.content) :
                    $(o.content).show().appendTo(t.$content.empty());;
            }

            t.resize();
            o.onload && o.onload.call(t);
            return this;
        }
    };


    LP.mix( exports , {
        alert : function ( content, type, time, o ) {
            var config;
            var type;
            var gid = LP.guid();

            if ( typeof type === 'number') {
                o = time;
                time = type;
                type = undefined;
            } else if ( typeof type === "object" ) {
                o = type;
                type = undefined;
            } else if ( typeof time === "object" ) {
                o = time;
                time = undefined;
            }

            type = type ||  'infos_warn';
            time = time === undefined ? 4000 : parseInt(time);
            if ( typeof time !== 'number' ) time = 0;

            // Config
            var config = $.extend({
                content  : '<div class="systemInfos"><p class="infos ' + type + '">' + content + '</p></div>'
                ,width   : 400
                ,submitButton : true
                ,mask    : true
                ,destroy : true
            }, o || {});

            if ( time ) {
                if ( !config.title ) { config.title = _e('Message Tip');}//'信息提示'; }
                config.title += '<span style="margin-left:5px;color:#aaa;font-size:12px;">( ' +
                    _e('close after <em id="#[gid]">#[time]</em> seconds' , { gid: gid , time: time / 1000 }) + ' )</span>';
            }

            // Create the panel
            var p = new Panel(config).show();

            if( time ) {
                var temp = time;
                var _t   = setInterval(function(){

                    temp -= 1000;
                    $('#' + gid).html( Math.max(temp, 0) / 1000 );
                    if ( temp <= 0 )
                    {
                        clearInterval(_t);
                        p.$panel.animate( { opacity:0}, 200, '', function(){ p.close(); } );
                    }
                }, 1000);
            }
            return  p;

        }
        , warn  : function (content, time, o ) { this.alert(content, 'infos_warn',  time , o); }
        , error : function (content, time, o ) { this.alert(content, 'infos_error', time , o); }
        , right : function (content, time, o ) { this.alert(content, 'infos_correct', time , o ); }
        , confirm : function ( content, onOk, onCancel, o ) {

            var config  = {
                content : '<div class="systemInfos"><p class="infos infos_question">' + content + '</p></div>'
                , width : 300
                , submitButton : true
                , cancelButton : true
                , onSubmit     : onOk
                , mask         : true
                , destroy      : true
                , onCancel     : onCancel
            };
            return LP.panel( $.extend(config, o || {}) );
        }
        , panel : function ( options ) {
            options = LP.mix( {destroy: true} , options );
            return new Panel(options).show();
        }
    }, true );
});
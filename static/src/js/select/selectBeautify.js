 /*
 * @author: hdg1988@gmail.com
 * @version: v1.02
 * @desc: jQuery plugin for select beautify. As a plugin of jQuery , it on select element. It will replace original select width new div dom element
 *        and you can decide your style.
 * @feature:
 *        1.Supports all browsers eg. IE6 , IE7 , IE8 , chrome , opera , firefox
 *        2.Easy to use , you can set many configs as you like.
 *        3.Select element not replacement , origin select element will be hide ,and created element will be in the place of the select element
 *        4.If you change the value of plugin , this will trigger the change event of select element and select's value will be changed too.
 *        5.It supports keyboard event include 'tab' , 'up' , down' , 'enter' , just like real select element . You can use tab to focus the target element
 *        ,and when you press 'enter' , it will show the options. Also you can press 'enter' to choose the option. 'up' and 'down' can change the option.
 *        6.You can add your own class to the plugin , and you can change the style of all elements which plugin created.
 *        7.You can change all the
 */
!!(function(){
  var _getViewPort = function(){
         var doc=document, b = doc.body, de = doc.documentElement, mode = doc.compatMode,
         width = self.innerWidth, height = self.innerHeight
         if (mode || _isIe){ // IE, Gecko, Opera
             width = (mode == 'CSS1Compat') ? de.clientWidth : b.clientWidth;
             if (!_isOpera ) height = (mode == 'CSS1Compat') ? de.clientHeight : b.clientHeight;
         }
         return {
             left: Math.max(de.scrollLeft, b.scrollLeft),
             top: Math.max(de.scrollTop, b.scrollTop),
             width: width,
             height: height
         };
     },
     // ie
     _isIe = $.browser.msie,
     _isOpera = $.browser.opera,
     _isIe8 = _isIe && $.browser.version == 8,
     _isIe7 = _isIe && $.browser.version == 7,
     _isIe6 = _isIe && $.browser.version == 6,

     _enable_mouse_hover = true,
     _enable_mouse_timer = null,
     _obj_cache = [];

  var config = {
    className     : '' // the class name of wrap dom, so you can set your style to the plugin. There has original options
                    // "beauty-select-red","beauty-select-green","beauty-select-blue","beauty-select-triangle","beauty-select-v"
                    // "beauty-select-circle"
    , maxHeight     : 150 // max height of the list show. When content is less than maxHeight.
                      // it shows as its real height ,or max height with scroll bar.
    , align         : 'left' // where the list wrap shows
    , height        : '' // the height of the dom , which take place of select
    , width         : 'auto' // the width of the plugin
    , imageWidth    : 16 //the image width
    , imageHeight   : 16 //the image height
    , event         : 'click' // how to trigger select event , this support 'click' and 'hover'
    , withImage     : false // if the option with image before
    , groupClass    : 'beauty-select-group' // optgroup class
    , optGroupClass : 'beauty-select-optgroup' // option class in an optgroup
    , optClass      : 'beauty-select-opt' // option class
    , disabledClass : 'beauty-select-disabled' // disabled option class
    , optDisabledClass: 'beauty-select-opt-disabled' // disabled option class in an optgroup
    , optLabelClass : 'beauty-select-opt-head' // when this option has image ahead , this is class of image parent
    , hoverClass    : 'beauty-select-hover' // option hover class
    , openedClass   : 'beauty-select-opened' // when the list is show, add openedclass to the wrap of plugin
    , optHoverClass : 'hover' // option hover class
  };

  var _eventHook = {
    // click evetn. You must click element to show list , and when you click other place , the list will hiden.
      'click': function(){
          (function(t){
              t.$dom.click(function(e){
                  e.preventDefault();
                  t.toggle();
                  //@desc @2013/3/12 remove event prevent, so that parent element can listen the click event
                  //return false;
              });
              //docuemnt click
              $(document).click(function(ev){
                  var tar = ev.target;
                  if(!$.contains(t.$wrap.get(0) , tar)){
                      t.hideList();
                  };
              });
          })(this);
      },
      // hover evetn. hover in and hover out to toggle list.
      'hover': function(){
          (function(t){
              var timer;
              t.$wrap.hover(function(){
                  clearTimeout(timer);
                  timer = setTimeout(function(){
                     t.showList();
                  }, 150);
              } , function(){
                  clearTimeout(timer);
                  timer = setTimeout(function(){
                     t.hideList();
                  }, 150);
              });
          })(this);
      }
  }
   /*
    * SelectBeautify class create
    */
  var SelectBeautify = function( dom , o ){
      this.version = 1.02;
      this.$select = $(dom);
      // extend config
      this.config = $.extend( config , o );
      this._create();
  }
  SelectBeautify.prototype = {
      _destroy : function(){
         // remove created dom
         this.$wrap.remove();
         // TODO  remove event listener
      },
      _create: function(){
          var t         = this
              , o       = t.config
              , $select = t.$select
              , dataName = "__beauty_plugin__"
              // receive config of height
              // or get select element height.
              // To fix the select height
              // you shoud reduce border top width and border bottom width
              , outerHeight  = parseInt(o.height) || $select.outerHeight()
              , outerWidth   = $select.outerWidth() + 10 + (o.withImage? o.imageWidth : 0);
          if($select.data( dataName )){
              $select.data( dataName )._destroy();
          }
          $select.data( dataName , t);
          // Fix ie6 and ie7 bug ,when o.width = 'auto'
          t.width     = o.width;// && (!(_isIe7 || _isIe6) || o.width != 'auto') ? o.width : outerWidth;
          t.height    = outerHeight - 2; //
          t.disabled  = !!$select.attr('disabled');
          $select.hide();

          ///////////////////////
          // plugin dom create
          ///////////////////////
          t.$wrap = $('<div class="beauty-select-plugin"></div>')
            .addClass(o.className)
            .css({
                verticalAlign : 'middle'
                // for ie6 and ie7 not support inline-table and inline-block
               , display      : _isIe6 || _isIe7? 'inline'
                                :'inline-block'
                                //o.width && !(_isIe7 || _isIe6) || o.width != 'auto' ? o.width : outerWidth,
               , width        : t.width
               , lineHeight   : t.height + 'px'
               , position     : 'relative'
               , zoom         : 1
               , zIndex       : 0
            })
            .insertBefore($select);

          // if the selected is disabled
          if(t.disabled){
              t.$wrap.addClass(o.disabledClass);
          }

          // create current element
          t.$curr = $('<label></label>');
          t.$sprite = $('<a href="javascript:void(0);" hidefocus=true class="beauty-select-sprite"></a>');
          t.$dom  = $('<div class="beauty-select-curr"></div>')
            .append(t.$sprite)
            .append(t.$curr)
            .appendTo(t.$wrap)
            .css({
                'white-space':'nowrap',
                height: t.height
            });

          // fix for ie, sprite
          _isIe && t.$sprite.css({lineHeight: '0px'});

          // create list warp element
          // set base css
          // the width will be set after render all the options
          var $listWrap = $('<div class="beauty-select-list"><ul></ul></div>').css({
                 position   :'absolute'
                 ,left      : 0
                 ,zoom      : 1
                 ,zIndex    : 1
                 ,cursor    : 'default'
                 ,overflowY : 'auto'
              })
              .delegate('li' , 'mouseenter' , function(){
                  _enable_mouse_hover && t.mouseover($(this));
              })
              .delegate('li' , 'click' , function(){
                  t.select($(this) , true);
              })

             , $ul    = $listWrap.find('ul')
             , selectIndex  = 0
             , currIndex    = 0
              // create options , return the jquery element
             , createOption  = function(option , bInGroup){
                  var $t      = $(option)
                      , $li   = $('<li></li>')
                            .attr({
                              'index': currIndex++,
                              'title': $(option).attr('title')
                            })
                            .html(['<span class="'
                              , o.optLabelClass
                              , '"></span><label>'
                              , $t.html()
                              , '</label>'].join(''))
                            .addClass(o.optClass);

                  // if current option is in optgroup
                  bInGroup ? $li.addClass(o.optGroupClass) : '';
                  // if current option is disabled
                  option.disabled ? $li.attr('disabled' , 'disabled')
                                    .addClass(o.optDisabledClass) : '';
                  // fix option image ,
                  var $img      = $li.find('span')
                      , bg      = $t.attr('image')
                      , cssObj  = {
                          'float'   :'left'
                          ,'width'  : o.imageWidth
                          ,'height' : o.imageHeight
                          ,'margin' : (t.height - o.imageHeight)/2
                      };
                  bg ? $.extend(cssObj , {background: bg} , true) : '';
                  o.withImage ? $img.css(cssObj) : $img.remove();
                  if(option.selected)
                      selectIndex = currIndex - 1;
                  return $li;
              }
              // create optgroup , return the jquery element
             , createGroup = function(option){
                  var $t    = $(option)
                      ,$li  = $('<li></li>')
                              .html(['<span></span><label>'
                                  , $t.attr('label')
                                  , '</label>'].join(''))
                              .addClass(o.groupClass);
                  return $li ;
              }
             , initListWrap = function(){
                  // reset all status
                  selectIndex = 0;
                  currIndex   = 0;

                  $ul.html("");

                  $select.children().each(function(i , option){
                      var $t  = $(option);
                      if(option.tagName.toLowerCase() === 'optgroup'){ // if is group
                          $ul.append(createGroup(option));
                          $t.children().each(function(i , option){
                              $ul.append(createOption(option , true));
                          });
                      }else{
                          $ul.append(createOption(option));
                      }
                  });

                  // init selected first , so that it can count the real width of $wrap. Because if it not set the innerHTML of $curr , the width of $wrap is smaller than real value
                  var $li = t.$listWrap.find('.'+o.optClass).eq(selectIndex);
                  t.select($li , false);


                  if(t.width == 'auto'){
                      t.width = t.$wrap.outerWidth();
                  }

                  if(o.align == 'right'){
                      t.$listWrap.css({left:'' , right:0});
                  }

              };

          t.$wrap.append($listWrap);
          t.$listWrap   = $listWrap;
          t.$ul         = $ul;
          // create list from select element
          initListWrap();
          /**
           * event bind , include keydown event and eventhook
           */
          if(!t.disabled){
              t.$wrap.hover(function(){
                  //if(t.$listWrap.is(':hidden')){
                      t.$wrap.addClass(o.hoverClass);
                  //}
              } , function(){
                  t.$wrap.removeClass(o.hoverClass);
              });
              t.$sprite.focus(function(){
                  t.$wrap.addClass(o.hoverClass);
              }).blur(function(){
                  t.$wrap.removeClass(o.hoverClass);
              });
          }

          $(document).keydown(function(ev){
              if(t.$listWrap.is(':hidden')) return;
              switch(ev.keyCode){
                  case 13: // enter
                     t.select(null , true);
                     break;
                  case 38: // up
                     t.prev();
                     break;
                  case 40: // down
                     t.next();
                     break;
              }
              return false;
          });

          // event hook
          _eventHook[o.event || 'click'].apply(t);
          // trigger event to update the plugin
          $select.bind("beautify-update" , function(){
              initListWrap();
          });
          // make unselectable
          //_makeUnselectable(t.$wrap.get(0));
      }
     , select: function($li , triggerChange){

          var t = this , o = t.config ;
          $li = $li || t.$currLi;

          // if disabled
          if(!this.isActive($li)) return;


          // if current li is already selected li , return
          if($li && t.$selectLi && t.$selectLi.get(0) == $li.get(0)){
              // hide list
              t.hideList();
              return;
          }

          // set curr li and select li
          t.$currLi   = $li;
          t.$selectLi = $li;

          // change select value
          var option = t.$select
                        .find('option')
                        .get($li.attr('index'))
              , html = $(option).html()
              , bg   = $(option).attr('image');
          option.selected = true;
          // set currValue
          t.$curr.html(html).attr('title' , html);
          // set image
          if(o.withImage){
              // remove prev span
              t.$curr.prevAll('span').remove();
              var cssObj = {
                  'float'   :'left'
                  , 'width' : o.imageWidth
                  , 'height': o.imageHeight
                  , 'margin': (t.height - o.imageHeight)/2
              };
              bg ? $.extend(cssObj , {
                  background: bg
              } , true) : '';
              $('<span></span>').css(cssObj).insertBefore(t.$curr);
          }else{
              t.$curr.css('padding-left' , '3px');
          }

          // set current label width and wrap width to right value
          var allWidth = 0;
          t.$dom.children().each(function(){
              var $t    = $(this);
              allWidth += $t.outerWidth();
              allWidth += parseInt($t.css('margin-left')) || 0;
              allWidth += parseInt($t.css('margin-right')) || 0;
          });
          // fix IE6 and IE7 for it is not support inline-block
          if( !o.width || o.width == 'auto' ){
             if( _isIe7 || _isIe6 ){
                t.$wrap.css( 'width' , allWidth );
                //t.$wrap.css('width' , allWidth + parseInt(t.$dom.css('padding-left'))
                //   + parseInt(t.$dom.css('padding-right')));
             }
          } else {
              var width       = o.width - 2
                  , currWidth = t.$curr.outerWidth()
                  , paddLeft  = parseInt(t.$curr.css('padding-left'))
                  , paddRight = parseInt(t.$curr.css('padding-right'))
                  , extendWidth = paddLeft + paddRight + 10 ;
              t.$curr.css({
                width: width - ( allWidth - currWidth ) - extendWidth
                , overflow: 'hidden'});
          }
          // hide list
          t.hideList();
          t.$wrap.removeClass(o.hoverClass);
          // if trigger select change event
          if(triggerChange)
             t.$select.trigger('change');
      }
     , mouseover: function($li){
          // on object  return
          if( !$li || !$li.length ) return;
          // if current li is not active , return with do nothing
          if( !this.isActive( $li ) ) return;
          var t = this
              , className = t.config.optHoverClass;

          // change t.$currLi , and toggle li hover class
          t.$listWrap.find( 'li' ).removeClass( className );

          t.$currLi = $li.addClass( className );

          // scroll current li to view
          t.scrollIntoView( $li );
      }
     , scrollIntoView: function($li){

          $li = $li || this.$currLi;
          var t       = this
              , $listWrap   = t.$listWrap
              , scrollTop   = $listWrap.scrollTop()
              , height      = $listWrap.height();


          // get current li position , and if current li is not visible , then scroll ul to right position
          var position  = $li.position();
          var liHeight  = $li.height();
          var posTop    = position.top - scrollTop;

          if( posTop < 0 ){
              $listWrap.scrollTop( scrollTop + posTop );
          } else if( posTop + liHeight >=  height ){
              $listWrap.scrollTop( scrollTop + posTop + liHeight - height );
          }

          clearTimeout( _enable_mouse_timer );
          _enable_mouse_timer = setTimeout(function(){_enable_mouse_hover = true;} , 200);
      }
     , toggle: function(){
          var t = this;
          t[t.$listWrap.is(':hidden')? 'showList' : 'hideList']();
      }
     , showList: function(){
          var t = this , o = t.config;

          // hide other select
          $.each(_obj_cache , function(i , obj){
              // not current obj
              if( obj != t ){
                obj.hideList();
              };
          });

          if( t.disabled ) return;
          t.$listWrap.css({
              zoom    : 1
            , width   : 'auto'
            , height  : 'auto'
            , top     : ''
            , bottom  : ''
          }).show();
          // in better browser, use $listWrap or use $ul.
          // ugly browser just as ie6 and ie7
          var $tmpDom = _isIe7 || _isIe6 ? t.$ul : t.$listWrap;
          var listWrapWidth   = $tmpDom.outerWidth() > t.$dom.outerWidth() ? 'auto' : t.$dom.outerWidth() - 2;
          var listWrapHeight  = t.$listWrap.height();
          listWrapHeight  = listWrapHeight > o.maxHeight + 30 ?
                      o.maxHeight : listWrapHeight;
          t.$listWrap.css({
              top         : t.height + 2
              // reset list wrap width
              // make sure the width is not smaller than $dom's width
              , height    : listWrapHeight < o.maxHeight ? "auto" : listWrapHeight//o.maxHeight && t.$listWrap.height() >= o.maxHeight ? o.maxHeight : 'auto'// set max height
              , width     : listWrapWidth
              , overflowY : 'auto'
              , overflowX : 'hidden'
          }).show();

          // in ie7 and ie6, the listwrap's max width is less than the outer width
          // of wrap. So we should set tle listwrap's width style equal to the width
          // of the $ul , when listWrapWidth is 'auto'.

          if((_isIe7 || _isIe6) && listWrapWidth == 'auto'){
             t.$listWrap.width( t.$ul.outerWidth() );
          }
          var port  = _getViewPort()
              , off = t.$wrap.offset()
              , h   = listWrapHeight
              , $ul = t.$ul.css({
                  //width       : '100%'
                  //, position  : _isIe6 || _isIe7 ? 'relative' : 'absolute'
                  position  : 'relative'
                  //, height    : h
                  , bottom    : 0
              });

          if( off.top + h + t.height > port.top + port.height
            && off.top - h > 0 ){
              $ul.css({
                  top       : 0
                  , bottom  : ''
              });
              t.$listWrap.css({
                'top'     : ''
                , 'bottom': t.height + 2
              });
          }else{
              $ul.css({
                  bottom  : 0
                  , top   : ''
              });
          }
          t.$wrap.css({position: 'relative'});


          t.scrollIntoView(t.$selectLi);
          t.$wrap.addClass(o.openedClass).css('z-index' , 1);
          /*
          t.$listWrap.css({
              'height'      : 0
              , 'overflow-y': 'hidden'
          })
          .stop(true)
          .animate({
              height  : h
            } , 200  , '' , function(){
                  t.$listWrap.css('overflow-y' , 'visible');
                  t.scrollIntoView(t.$selectLi);
                  t.$wrap.addClass(o.openedClass);
              });
              */
      }
     , hideList: function(){
          var t       = this
              , o     = t.config
              , $listWrap   = t.$listWrap
              , port  = _getViewPort()
              , off   = t.$listWrap.offset()
              , h     = t.$listWrap.height()
              , className = t.config.optHoverClass;

          // save curr scroll top value

          t.$listWrap.hide();
          if( t.$currLi && t.$currLi.length )
            t.$currLi.removeClass(className);
          if( t.$selectLi && t.$selectLi.length )
            t.$selectLi.addClass(className);
          t.$wrap.removeClass(o.openedClass).css('z-index' , 0);
          /*
          t.$listWrap
            .stop(true)
            .animate({
              height: 0
            } , 200 , '' , function(){
              t.$listWrap.hide();
              //t.$wrap.css({position: 'static'});
              t.$currLi.removeClass(className);
              t.$selectLi.addClass(className);
              t.$wrap.removeClass(o.openedClass);
            });
            */
      }
      // judge if current li is available
     , isActive: function($li){
          return !!($li.length
            && !$li.attr('disabled')
            && !$li.hasClass(this.config.groupClass));
      }
    , next: function(){
          var t = this , $next = t.$currLi.next();
          while ( $next.length && !t.isActive($next) ){
              $next = $next.next();
          }
          t.mouseover( $next );
      }
     , prev: function(){
          var t = this , $prev = t.$currLi.prev();
          while( $prev.length && !t.isActive($prev) ){
              $prev = $prev.prev();
          }
          t.mouseover($prev);
      }
  };


  // exports for jquery plugin
  $.fn.beautySelect = function(o){
      $(this).each(function(i , select){
          _obj_cache.push( new SelectBeautify(select , o) );
      });
      return this;
  }
})();
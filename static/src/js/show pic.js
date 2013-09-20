         /*
         * 帖子列表-放大图片
         */
        _addAction('big-pic' , function(data , cb){
            var $this = $(this);
            var $allImgs = $(data.selector);

            showOrignPic($this, $allImgs);
        });

        /*
        * 图片查看大图
        */
        function showOrignPic($img , $allImgs){
         var src  = $img.attr('src'), 
             view = GJ.getViewPort(),
             off  = $img.is(':hidden') ? $img.data('__canvas__').offset() : $img.offset(), 
             imgWidth  = $img.width(), 
             imgHeight = $img.height(),

             $wrap     = $('<div></div>').css({position: 'fixed' , zIndex: 9999999 , top: 0 , left: 0 , width: view.width , height: view.height}).appendTo(document.body).click(function(ev){
                    if(ev.target == $mask.get(0) || ev.target == this) return;
                    $close.trigger('click');
                }),

             $mask = $('<div></div>').css({position: 'absolute' ,display:'none', left: 0 , top: 0 , width: '100%' , height: '100%' , background: 'black' ,opacity: 0.9}).appendTo($wrap),

             $picWrap = $('<div></div>').css({position: 'absolute' , overflow: 'visible', top: 0, left: 0 , width: '100%' , height: '100%'}).appendTo($wrap),

             $loadingWrap = $('<div></div>').css({position:'absolute' ,zIndex: 8888 , display: 'none' , top: 0 , left: 0 , height: '100%' , width: '100%'}).appendTo($wrap),

             $showImg = $img.clone()
                        .css({
                            position:'absolute' , 
                            zIndex: 1 , 
                            top: off.top - view.top, 
                            left: off.left - view.left
                        })
                        .show()
                        .attr({
                            'action-type': '',
                            id: ''
                        })
                        .appendTo($picWrap)
                        .data('__img_jquery__' , $img);

            var preferSize = calcPreferImgSize({
                                width: $showImg.data('__img_bigW__') || imgWidth, 
                                height:$showImg.data('__img_bigH__') || imgHeight
                            });
            $showImg.animate({
                left  : Math.max((view.width - preferSize.width)/2 , 0),
                top   : Math.max((view.height - preferSize.height)/2 , 0),
                width : preferSize.width,
                height: preferSize.height
            } , 200);

            var createLoading = function(wrap , o , text ){
                
            }
            createLoading.prototype.remove = function(){
                
            }
            var $close = $('<img />')
                  .css({position:'absolute' , zIndex: 1 , top: 10 , right: 20 , cursor: 'pointer'})
                  .appendTo($wrap)
                  .attr('src' , GJ.getFormatUrl('app/guazi/imagev4/old/close.png'))
                  .click(function(){
                        $('html,body').css('overflow' , '');
                        $(window).unbind('resize' , resize).scrollTop(scrollTop);
                        $(document).unbind('keydown',keyEvent);
                        $wrap.remove();

                        // Nullfy stuffs, because the closure may be held by the fetch pic callback
                        $wrap = $mask = $currimg = $loadingWrap = loading = $picWrap = null;
                    }),


            createLoading = function(){
                $loadingWrap.show();
                
                return GZ.createLoading($loadingWrap , {
                    css: {
                        position:'absolute',
                        zIndex: 10000
                    },
                    callback: function($dom){
                        var w = $dom.width() , h = $dom.height();
                        $loadingWrap.css({
                            top: (view.height - h) /2,
                            left: (view.width - w) /2,
                            width: w,
                            height: h
                        });
                    }
                });
                
            },
            removeLoading = function(){
                loading.remove();
                $loadingWrap.hide();
            },
            loading = createLoading() ,
            scrollTop = $(window).scrollTop(),

            $originImg = $('<img />').load(function() {
                var oSrc   = this.src;
                var preferSize = calcPreferImgSize(this);

                removeLoading();

                $currimg.data('__img_bigW__', this.width);
                $currimg.data('__img_bigH__', this.height);

                $showImg.css({
                    width : $showImg.width(),
                    height: $showImg.height()
                }).animate({
                    left  : Math.max((view.width - preferSize.width)/2 , 0),
                    top   : Math.max((view.height - preferSize.height)/2 , 0),
                    width : preferSize.width,
                    height: preferSize.height
                } , 200 )
                .attr('src' , oSrc);
            }).attr('src' , GZ.formatPicUrl(src , 0 , 0)),

            resize = function(){
                view = GJ.getViewPort();

                var imgW = $currimg.data('__img_bigW__') || $showImg.width();
                var imgH = $currimg.data('__img_bigH__') || $showImg.height();

                var preferSize = calcPreferImgSize({width:imgW, height:imgH});
                $showImg.stop(true,false).animate({
                   left  : Math.max((view.width - preferSize.width)/2 , 0),
                   top   : Math.max((view.height - preferSize.height)/2 , 0),
                   width : preferSize.width,
                   height: preferSize.height
                },200);

                $wrap.css({width: view.width , height: view.height});
                imgH = $showImg.height() , imgW = $showImg.width();

                if ($loadingWrap.is(':visible')) {
                   var w = $loadingWrap.width() , h = $loadingWrap.height();
                    $loadingWrap.css({
                        top: (view.height - h) /2,
                        left: (view.width - w) /2,
                        width: w,
                        height: h
                    });
                }
            },
            getSrc = function($img){
                if($img.attr('original') && $img.attr('src') != $img.attr('original')){
                    return $img.attr('original');
                }
                return $img.attr('src');
            },
            getIndex = function(){
                var $img  = $showImg.data('__img_jquery__'), 
                    index = + $img.attr('data-index') || 0 ;
                $allImgs.each(function(i , dom){
                    if(dom == $img.get(0)) {
                        index = i;
                    }
                });
                return index;
            },
            $currimg = $img,
            prev = function(){
                var index = getIndex() - 1;
                if(index < 0 ) return;
                
                // create loading
                removeLoading();
                loading = createLoading();
                $currimg = $allImgs.eq(index);
                $('<img />').load(onNextImgLoad).attr('src' , GZ.formatPicUrl(getSrc($currimg) , 0 , 0));
                $showImg.data('__img_jquery__', $currimg);
            },
            next = function() {
                var index = getIndex() + 1;
                if (index == $allImgs.length){
                    var $nextImg = $allImgs.eq(-1);
                    // We are at the end of the image, but maybe we can fetch more.
                    if ($nextImg.attr('gallery-data')){
                        var evt       = $.Event('fetchPics');
                        evt.callback  = function fetchCallback($moreImgs) {
                            if (!$currimg) { return; }
                            $allImgs = $allImgs.add($moreImgs);
                            if (index == getIndex() + 1) {
                                // The user pressed prev button.
                                // Goto new image
                                next();
                            }
                        };
                        $nextImg.trigger(evt);
                        if (!evt.isDefaultPrevented()){
                            // At this point, we only need to show the loading
                            removeLoading();
                            loading = createLoading();
                        }
                    }
                    return;
                }

                // create loading
                removeLoading();
                loading = createLoading();
                $currimg = $allImgs.eq(index);
                $('<img />').load(onNextImgLoad).attr('src' , GZ.formatPicUrl(getSrc($currimg) , 0 , 0));
                $showImg.data('__img_jquery__', $currimg);
            },
            keyEvent = function(ev){
                switch(ev.keyCode){
                    case 27: // esc
                       $close.trigger('click');
                       break;
                    case 37: // prev
                    case 38:
                       prev();
                       return false;
                       break;
                    case 39: // next
                    case 40:
                       next();
                       return false;
                       break;
                }
            };
        var onNextImgLoad = function() {
            // 如果不是当前这一张，则返回
            if(GZ.formatPicUrl(getSrc($currimg) , 0 , 0) != this.src) return;
            removeLoading();

            $currimg.data('__img_bigW__', this.width);
            $currimg.data('__img_bigH__', this.height);

            var preferSize = calcPreferImgSize(this);
            $showImg.attr({
                'src': this.src
            }).stop(true,false).animate({
                width  : preferSize.width,
                height : preferSize.height,
                left   : Math.max((view.width - preferSize.width)/2 , 0),
                top    : Math.max((view.height - preferSize.height)/2 , 0)
            }, 200);
        }

        function calcPreferImgSize(showcaseImg) {
            var toWidth  = showcaseImg.width,
                toHeight = showcaseImg.height;

            if ( toWidth * 2 < toHeight ) { return { width:toWidth, height:toHeight}; }

            var maxW     = $(window).width()  * .82,
                maxH     = $(window).height() * .82;

            // Reduce size to fit the window if necessary, respect the img ratio.
            if (maxH / toHeight < maxW / toWidth){
                if (toHeight > maxH){
                    toWidth  = maxH / toHeight * toWidth;
                    toHeight = maxH;
                }
            } else if (toWidth > maxW) {
                toHeight = maxW / toWidth * toHeight;
                toWidth  = maxW;
            }
            return { width : Math.round(toWidth), height : Math.round(toHeight) };
        };

        $showImg.mousemove(function(ev) {
            var offImg = $showImg.offset(),
                offx = ev.pageX - offImg.left;

            if(offx > $(this).width()/2){
                if(getIndex() == $allImgs.length - 1 && $allImgs.eq(-1).attr('gallery-data') != 'more'){
                    $showImg.removeClass('next-cursor').removeClass('prev-cursor').attr('title' , '已经是最后一张了');
                }else{
                    $showImg.addClass('next-cursor').removeClass('prev-cursor').attr('title' , '下一张');
                }
            }else{
                if(getIndex() == 0){
                    $showImg.removeClass('prev-cursor').removeClass('next-cursor').attr('title' , '已经是第一张了');
                }else{
                    $showImg.addClass('prev-cursor').removeClass('next-cursor').attr('title' , '上一张');
                }
            }
        }).click(function(ev){
            if($(this).hasClass('prev-cursor'))
                prev();
            if($(this).hasClass('next-cursor'))
                next();
            return false;
        });
        $mask.fadeIn();
        $('html,body').css('overflow' , 'hidden').scrollTop(scrollTop);
        resize();
        $(window).resize(resize);
        $(document).keydown(keyEvent);
    };
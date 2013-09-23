seajs.config({
  // 配置 shim 信息，这样我们就可以通过 require("jquery") 来获取 jQuery
  //plugins: ['shim']

  shim: {
    // for jquery
    jquery: {
      src: "../jquery/jquery-1.8.3.min.js"
      , exports: "jQuery"
    }
    , uploadify: {
        src: '../uploadify/jquery.uploadify-3.1.js'
      , deps: ['jquery' , "../uploadify/uploadify.css"]
    }
    , jcrop : {
      src: '../jcrop/jquery.Jcrop.min.js'
      , deps: ['jquery' , "../jcrop/jquery.Jcrop.css"]
    }
    , datepicker_local : {
      src: '../datepicker/i18n/jquery.ui.datepicker-{locale}.js'
      , deps: ['jquery']
    }
    , datepicker: {
      src: '../datepicker/jquery.ui.datepicker.js'
      , deps: ["jquery"
        , "../datepicker/jquery-ui-datepicker.css"
        , "datepicker_local"]
    }
    , ueditor: {
      src: '../ueditor/ueditor.all'
      , deps: ['../ueditor/ueditor.config.js']
      , exports: "UE"
    }
    , upload: {
      src: '../uploader/ajaxUpload'
      , exports: 'AjaxUpload'
    }
  }
  , alias: {
    i18n: '../i18n/{locale}.js'
    , api: '../api.js'
    , util: '../util/util'
    , panel: "../panel/panel"
    , autoComplete: '../autocomplete/autoComplete'
    , validator: '../validator/validator.js'
    , html2json: '../com/html2json'
    , tooltip: '../util/tooltip'
  }
});
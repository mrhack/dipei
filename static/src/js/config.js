seajs.config({
  // 配置 shim 信息，这样我们就可以通过 require("jquery") 来获取 jQuery
  //plugins: ['shim']

  shim: {
    // for jquery
    jquery: {
      src: "../jquery/jquery-1.8.3.min.js"
      , exports: "jQuery"
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
  }
  , alias: {
    i18n: '../i18n/{locale}.js'
    , api: '../api.js'
    , domUtil: '../util/dom-util'
    , panel: "../panel/panel"
    , autoComplete: '../autocomplete/autoComplete'
  }
});
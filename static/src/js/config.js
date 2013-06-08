seajs.config({
  // 加载 shim 插件
  "plugins": ["shim"]
  // 配置 shim 信息，这样我们就可以通过 require("jquery") 来获取 jQuery
  ,"shim": {
    "i18n" :{
      src: '../i18n/{locale}.js'
    }
    // for jquery
    ,"jquery": {
      "src": "jquery-1.102",
      "exports": "jQuery"
    }
    /*
    ,"jquery.finger": {
      "src": "../../src/jquery.finger",
      "deps": ["jquery"]
    }*/
  }
  ,"alias" : {
    "base" : "../base.js"
  }
});
#!/usr/local/bin/node
var $ = require('jquery');
var fs = require('fs');

function JSON_stringify(s)
{
    var json = JSON.stringify(s);
    return json.replace(/[\u007f-\uffff]/g,
        function(c) {
            return '\\u'+('0000'+c.charCodeAt(0).toString(16)).slice(-4);
        }
    );
}


$.get('http://www.booking.com/index.zh-cn.html?sid=83a4ed34a5b65ad80d9f1a233d7706d2;dcid=1;selected_currency=EUR',function(doc) {
    var moneys={};
    var langs={};
    $('#currencyChange option',doc).each(function(i,e){
        if(/\w+/.test(e.value) && e.value == e.value.toUpperCase()){
            var seldesc= e.innerHTML.replace(/\s*/g,'');
            moneys[e.value]={
                'symbol':e.getAttribute('data-label'),
                'desc':seldesc
            };
        }
    });
    $('#languageChange option',doc).each(function(i,e){
        var lang= e.innerHTML.replace(/\s*/g,'');
        var langFlag = e.value.replace(/-/g, '_');
        langs[langFlag]=lang;
    });
    console.log(moneys);
    console.log(langs);
    fs.writeFileSync('money.json', JSON_stringify(moneys));
    fs.writeFileSync('langs.json', JSON_stringify(langs));
})

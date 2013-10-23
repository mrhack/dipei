#!/usr/local/bin/node
var $=require('jquery');//npm install jquery
var fs=require('fs');
var mkdirp=require('mkdirp');//npm install mkdirp
var http=require('http');
var req=require('request');

var indexPath='travel_index.json';//save like:{xxx(sid):"/yazhou/zhongguo/xizang/lasa.json(path)"}
var sitemap={};
var enqueued=[];
var fetchBasePath="datas";

if(fs.existsSync(indexPath)){
   sitemap=JSON.parse(fs.readFileSync(indexPath,{encoding:'utf8'}));
}

function requestBinary(url,path){
   if(fs.existsSync(path)){
      console.log('get binary from file '+url);
      return;
   }
   console.log('get binary from http '+url);
   http.get(url,function(res){
      res.on('data',function(buf){
         fs.appendFileSync(path,buf);
      });
   });
}

function fetchCover(data){
   if(!data || !data.data || !data.data.scene_album || !data.data.scene_album[0].pic_url){
      console.error("can not fetch cover from "+data);
      return;
   }
   var path=fetchBasePath+"/";
   data.data.scene_path.forEach(function(e,i){
      path+=e.surl+"/"
   });
   if(!fs.existsSync(path)){
      mkdirp.sync(path);
   }
   path+=data.data.scene_path[data.data.scene_path.length-1].surl+'.jpg';
   requestBinary('http://hiphotos.baidu.com/lvpics/pic/item/'+data.data.scene_album[0].pic_url+'.jpg',path);
}

function request(sid,callback)
{
  if(sitemap[sid] && fs.existsSync(sitemap[sid])) {
     var data=JSON.parse(fs.readFileSync(sitemap[sid],{encoding:'utf8'}));
     console.log('get from file '+sid);
     try{
        fetchCover(data);
        callback(data.data);
     }catch(ex){
        console.error(ex);
     }
  }else{
      //ex:http://lvyou.baidu.com/scene/ajax/allview/c921e59aba1c706693d2d7f3
      //pn:pageNo
      //ajax2:http://lvyou.baidu.com/search/ajax/query?sid=1837ac2d3cbf3757b4f009d3&word=%E6%B5%B7%E6%B4%8B%E5%85%AC%E5%9B%AD
      //album_pic_url
      //scene_list.full_path-> parent_sid
     $.getJSON("http://lvyou.baidu.com/scene/ajax/allview/"+sid,function(data){
        if(!data || !data.data || !data.data.scene_path){
           console.error('invalid data:'+data);
           return;
        }
        console.log('get from http '+sid);
        var path=fetchBasePath+"/";
        data.data.scene_path.forEach(function(e,i){
           path+=e.surl+"/"
        });
        if(!fs.existsSync(path)){
           mkdirp.sync(path);
        }
        path+=data.data.scene_path[data.data.scene_path.length-1].surl+'.json';
        fs.writeFileSync(path,JSON.stringify(data));

        sitemap[data.data.sid]=path;
        fs.writeFileSync(indexPath,JSON.stringify(sitemap),{encoding:'utf8'});
        try{
           fetchCover(data);
           callback(data.data);
        }catch(ex){
           console.error(ex);
        }
     });
  }
}

//http://nssug.baidu.com/su?wd=keluodiya&pre=gSug&cb=gSug&ie=utf-8&prod=lvyou_new&su_num=20&callback=undefined fetch
//gSug({q:"keluodiya",p:false,s:
//    ["克罗地亚$$628c84d01a1d89d2a0f442fe{#S+_}$$克罗地亚$$欧洲$$628c84d01a1d89d2a0f442fe$$$$0$$0.000000$$0.000000$$0$$0$$keluodiya$$2$$e79aba1c706693d2d06fd4f3$$0$$628c84d01a1d89d2a0f442fe$$$$0442fe",
//        "克罗地亚萨格勒布$$6d96c1b446649c1ed1b1f6c8{#S+_}__1$$萨格勒布$$欧洲,克罗地亚$$6d96c1b446649c1ed1b1f6c8$$$$1$$1777922.377152$$5728160.315841$$1$$0$$sagelebu$$4$$628c84d01a1d89d2a0f442fe$$0$$6d96c1b446649c1ed1b1f6c8","克罗地亚杜布罗夫尼克$$a9a46b2265c83e2b6ddee429{#S+_}__1$$杜布罗夫尼克$$欧洲,克罗地亚$$a9a46b2265c83e2b6ddee429$$$$0$$0.000000$$0.000000$$1$$0$$dubuluofunike$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚拉古萨$$a9a46b2265c83e2b6ddee429{#S+_}__1$$拉古萨___$$欧洲,克罗地亚$$a9a46b2265c83e2b6ddee429$$$$0$$0.000000$$0.000000$$1$$0$$dubuluofunike$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚罗夫尼克$$a9a46b2265c83e2b6ddee429{#S+_}__1$$杜布罗夫尼克$$欧洲,克罗地亚$$a9a46b2265c83e2b6ddee429$$$$0$$0.000000$$0.000000$$1$$0$$dubuluofunike$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚尼克$$a9a46b2265c83e2b6ddee429{#S+_}__1$$杜布罗夫尼克$$欧洲,克罗地亚$$a9a46b2265c83e2b6ddee429$$$$0$$0.000000$$0.000000$$1$$0$$dubuluofunike$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚罗维尼$$23651cc84836d6f3c8212b2e{#S+_}__1$$罗维尼$$欧洲,克罗地亚$$23651cc84836d6f3c8212b2e$$$$0$$0.000000$$0.000000$$1$$0$$luoweini$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚扎达尔$$82d84d001b3184b2b2b67f2c{#S+_}__1$$扎达尔$$欧洲,克罗地亚$$82d84d001b3184b2b2b67f2c$$$$0$$0.000000$$0.000000$$1$$0$$zhadaer$$4$$628c84d01a1d89d2a0f442fe$$0$$82d84d001b3184b2b2b67f2c","克罗地亚斯普利特$$a6f29b7e6b2265c83e2be729{#S+_}__1$$斯普利特$$欧洲,克罗地亚$$a6f29b7e6b2265c83e2be729$$$$0$$0.000000$$0.000000$$1$$0$$sipulite$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚里耶卡$$48da3aad20f5663ee7c8eb29{#S+_}__1$$里耶卡$$欧洲,克罗地亚$$48da3aad20f5663ee7c8eb29$$$$0$$0.000000$$0.000000$$1$$0$$liyeka$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚吉尔$$95fc608cbb0e13551b12a12d{#S+_}__1$$特罗吉尔$$欧洲,克罗地亚$$95fc608cbb0e13551b12a12d$$$$0$$0.000000$$0.000000$$1$$0$$teluojier$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚特罗吉尔$$95fc608cbb0e13551b12a12d{#S+_}__1$$特罗吉尔$$欧洲,克罗地亚$$95fc608cbb0e13551b12a12d$$$$0$$0.000000$$0.000000$$1$$0$$teluojier$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚普拉$$79e64ffbffdcdfcc000b992e{#S+_}__1$$普拉$$欧洲,克罗地亚$$79e64ffbffdcdfcc000b992e$$$$0$$0.000000$$0.000000$$1$$0$$pula$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚瓦拉日丁$$0bf16dde39224b57f937e329{#S+_}__1$$瓦拉日丁$$欧洲,克罗地亚$$0bf16dde39224b57f937e329$$$$0$$0.000000$$0.000000$$1$$0$$walariding$$4$$628c84d01a1d89d2a0f442fe$$0$$0bf16dde39224b57f937e329","克罗地亚奥帕蒂亚$$73783dbf3757b4f0059936a6{#S+_}__1$$奥帕蒂亚$$欧洲,克罗地亚$$73783dbf3757b4f0059936a6$$$$0$$1593183.103633$$5645104.536816$$1$$0$$aopadiya$$5$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe","克罗地亚戴克里先宫$$37f94e47a4211acb2b024efe{#S+_}__1$$戴克里先宫$$欧洲,克罗地亚$$37f94e47a4211acb2b024efe$$$$0$$1830876.738079$$5360866.622975$$1$$0$$daikelixiangong$$6$$628c84d01a1d89d2a0f442fe$$0$$628c84d01a1d89d2a0f442fe"]});
function fetchBySugCountries()
{
    var countries=['法国', '西班牙', '美国', '中国', '意大利', '英国', '德国', '乌克兰', '土耳其', '墨西哥', '马来西亚', '奥地利', '俄罗斯', '加拿大', '香港', '希腊', '波兰', '泰国', '澳门', '葡萄牙', '沙特阿拉伯', '荷兰', '埃及', '克罗地亚', '南非', '匈牙利', '瑞士', '日本', '新加坡', '爱尔兰共和国', '摩洛哥', '阿拉伯联合酋长国', '比利时', '突尼斯', '捷克', '阿根廷', '印尼', '瑞典', '保加利亚', '澳大利亚', '巴西', '印度',
        '丹麦', '韩国', '巴林', '越南', '多米尼加共和国', '挪威', '台湾', '波多黎各', '法国', '美国', '西班牙', '中国', '意大利', '英国', '土耳其', '德国', '马来西亚', '墨西哥', '坦桑尼亚', '南非', '突尼斯', '莫桑比克', '津巴布韦', '阿尔及利亚', '博茨瓦纳', '肯尼亚', '斯威士兰', '毛里求斯', '埃及', '沙特阿拉伯', '阿拉伯联合酋长国', '叙利亚', '巴林', '约旦', '以色列', '黎巴嫩', '卡塔尔', '也门', '美国', '墨西哥', '加拿大', '阿根廷', '巴西', '多米尼加共和国', '波多黎各', '智利', '古巴', '哥伦比亚', '中国', '马来西亚', '香港', '泰国', '澳门', '韩国', '新加坡', '日本', '印尼', '澳大利亚', '法国', '西班牙', '意大利', '英国', '土耳其', '德国', '奥地利', '乌克兰', '俄罗斯', '希腊', '法国', '美国', '中国', '西班牙', '意大利', '英国', '土耳其', '德国', '马来西亚', '墨西哥', '摩洛哥', '南非', '突尼斯', '津巴布韦', '莫桑比克', '阿尔及利亚', '博茨瓦纳', '尼日利亚', '肯尼亚', '纳米比亚', '埃及', '沙特阿拉伯', '叙利亚', '阿拉伯联合酋长国', '黎巴嫩', '巴林', '约旦', '以色列', '卡塔尔', '阿曼', '美国', '墨西哥', '加拿大', '阿根廷', '巴西', '多米尼加共和国', '波多黎各', '智利', '古巴', '哥伦比亚', '中国', '马来西亚', '香港', '泰国', '澳门', '新加坡', '韩国', '日本',
        '印尼', '澳大利亚', '法国', '西班牙', '意大利', '英国', '土耳其', '德国', '奥地利', '乌克兰', '俄罗斯', '希腊'];
    console.log('fetch by suggestion countries');
    countries.forEach(function(country){
        var url='http://nssug.baidu.com/su?wd='+country+'&pre=gSug&cb=gSug&ie=utf-8&prod=lvyou_new&su_num=20&callback=undefined';
        console.log(url);
        console.log('fetch '+country);
        req(url,function(error,response,data){
            if(error) {
                console.error('fetch '+country+'failed');
            }else{
                var reg=new RegExp(country+'\\$\\$(\\w+)\\{#S\\+_\\}');
                var match=reg.exec(data);
                if(match){
                    console.log('fetch '+ country + ' : ' + match[1]);
                    fetchFromRoot(match[1]);
                }
            }
        });
    });
}

function fetchScene(){
   console.log('fetch scene');
   $.get('http://lvyou.baidu.com/scene/',function(doc){
      $('.head-menu-item>textarea',doc).each(function(i,e){
         var confs=JSON.parse(e.innerHTML);
         confs.forEach(function(conf){
            conf.sub.forEach(function(sub){
               request(sub.sid,function(scene){
                  fetchFromRoot(scene.scene_path[0].sid);
               }); 
            });
         });
      });
   });
}

function fetchFromRoot(sid){
   if(enqueued.indexOf(sid)>=0){
      console.log('has enqueued,request will skip '+sid);
      return;
   }
   enqueued.push(sid);
   console.log('fetch '+sid);
   request(sid,function(scene){
      if(scene.scene_list){
         scene.scene_list.forEach(function(e){
            fetchFromRoot(e.sid);
         });
      }
   });
}

fetchBySugCountries();
fetchScene();
console.log('end fetch');

#!/usr/local/bin/node
var $=require('jquery');//npm install jquery
var fs=require('fs');
var mkdirp=require('mkdirp');//npm install mkdirp
var http=require('http');

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

fetchScene();

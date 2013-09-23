#!/usr/local/bin/node
var fs=require('fs');
var walk=require('fs-walk');
var mkdirp=require('mkdirp');//npm install mkdirp
var http=require('http');

var fetchBasePath="datas";

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

function adaptScene()
{
   walk.filesSync(fetchBasePath,function(basedir,filename,stat){
      var path=basedir+"/"+filename;
      if(/.*\.json/.test(filename)){
         var data=JSON.parse(fs.readFileSync(path));
         if(data && data.data && data.data.scene_list){
            data=data.data;
            data.scene_list.forEach(function(s){
               var output=s;
               //set scene_path
               output['scene_path']=data.scene_path;
               output.scene_path.push({
                  'sid':s.sid,
                  'surl':s.surl,
                  'sname':s.sname,
                  'parent_sid':s.parent_sid
               });
               if(s.pic_list && s.pic_list.length>0){
                  output['scene_album']=[{
                     'pic_url':s.pic_list[0].pic_url
                  }];
               }
               fetchCover({'data':output});
               basedir=basedir+'/'+s.surl;
               if(!fs.existsSync(basedir)){
                  fs.mkdirSync(basedir);
               }
               var outPath=basedir+'/'+s.surl+'.json';
               if(!fs.existsSync(outPath)){
                  fs.writeFileSync(outPath,JSON.stringify({'data':output}),{encoding:'utf8'});
                  console.log('adapt '+s.sid);
               }else{
                  console.log('skip '+s.sid);
               }
            });
         }
      }
   });
}

console.log('begin adapt');
adaptScene();

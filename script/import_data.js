#!/usr/local/bin/node
var conn='mongodb://localhost:27018/lepei?w=1';
var fs=require('fs');
var mongo = require('mongodb');

var smap={};//sid => _id
var id=0;//sequence id

function nextId(){
    id++;
    return id;
}

function iterator(folders,loc)
{
    if(folders.length == 0){
        return;
    }
    var next=[];
    folders.forEach(function(root) {
        var files = fs.readdirSync(root);
        files.forEach(function(file) {
            if(file[0]=='.'){
                return false;//skip
            }
            if(fs.statSync(root+"/"+file).isDirectory()){
                next.push(root + "/" + file);
            }else if(/.*\.json/.test(file)){
                var data = JSON.parse(fs.readFileSync(root + "/" + file));
                if(data && data.data){
                    data=data.data;
                    data.content = JSON.parse(data.content);
                    var path=[];
                    data.scene_path.forEach(function(e) {
                        if(smap[e.sid]){
                            path.push(smap[e.sid]);
                        }
                    });
                    var record={
                        '_id':nextId(),
                        'n':data.sname,
                        'dsc':data.abstract,
                        'pt':path,
                        'ims':[]
                    };
                    smap[data.sid]=record['_id'];
                    loc.insert(record,function(err,rec) {
                       //do nothing
                    });
                    console.log(record);
                }
            }
        })
    });
    iterator(next,loc);
}


require('mongodb').MongoClient.connect(conn,function(err,db){
        if(err){
            throw err;
        }

        var baseDir = '/Volumes/wpp/travel_spider/';
        //var baseDir = '';
        var datas = fs.readdirSync(baseDir+'datas');
        var dataDirs=[];
        datas.forEach(function(e) {
            if(e[0]!='.' && fs.statSync(baseDir+'datas/'+e).isDirectory())
            {
                dataDirs.push(baseDir+'datas/'+e);
            }
        });
        var loc=db.collection('location');
        iterator(dataDirs,loc);
        db.close();
    }
);


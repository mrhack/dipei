#!/bin/bash
#author wangfeng
cd ..
if [[ $(basename $(pwd)) != 'dipei' ]];then
   echo must run script in dipei/script path
   exit 1
fi
echo do extacting..
out='script/datas/base.properties'

echo > $out
for file in $(find . -name '*.php1') $(find . -name '*.twig'); do
   eval keys=($(perl -lne 'if(/_e\(([\x27"])(.+?[^\\])\1/){$_=$2;s/([#\x27=])/"\\$1"/eg;print " \x27$_\x27"}' $file));
   for ((i=0;i<${#keys[@]};i++));do
      key=${keys[$i]}
      echo extract $key from $file
      #echo extract $key from $file >> $out
      echo $key >> $out
   done
done

echo do extracting error desc..
errorDesc='application/library/GenErrorDesc.php'
for errorKey in $(perl -lne 'print /"(.+?)"/' $errorDesc); do
   echo extract error key $errorKey
   echo $errorKey >> $out
done

echo extract done,handle unique
temp=_temp.properties
cat $out|sort -n|uniq > $temp
perl -i -lpe 's/$/=/' $temp
mv $temp $out

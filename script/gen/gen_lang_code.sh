json_pp -json_opt pretty < ../langs.json |\
perl -F/:/ -lane '$i =20 unless $i;print sprintf "const LANG_CODE_%s=%s;",uc [/"([^"]+)"/]->[0],++$i if $F[1]' | \
perl -0777 -i -lpe '$replace=join "",(<STDIN>);s!(//gen lang start)(.*?)(//gen lang end)!$1\n$replace\n$3!ms;' ../../application/library/ModelConstants.php 
json_pp -json_opt pretty < ../langs.json |\
perl -F/:/ -lane '$i =20 unless $i;print sprintf "const LANG_CODE_%s=%s;",uc [/"([^"]+)"/]->[0],++$i if $F[1]' | \

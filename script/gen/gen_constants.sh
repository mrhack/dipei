if [ $# != 3 ]; then
   echo "[USEAGE]
      ./gen_constans.sh ConstantPrefix ConstantSoureFile ConstantTargetVar
      example ./gen_constants.sh LANG LangConstants.php LANGS
      "
   exit
fi
perl -ne 'print "self::",/const\s+('"$1"'_[\w_]+)/,"," if /const\s+'"$1"'.*/' ../../application/library/$2 |\
perl -0777 -i -lpe '$gen=join "",(<STDIN>);s/static\s+\$'"$3"'[^;]+;/static \$'"$3"'=array($gen);/' ../../application/library/Constants.php 
cat ../../application/library/Constants.php


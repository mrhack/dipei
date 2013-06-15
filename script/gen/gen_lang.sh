perl -ne 'print "self::",/const\s+(LANG_[\w_]+)/,"," if /const\s+LANG.*/' ../../application/library/LangConstants.php |\
perl -0777 -i -lpe '$gen=join "",(<STDIN>);s/static\s+\$LANGS[^;]+;/static \$LANGS=array($gen);/' ../../application/library/Constants.php 
cat ../../application/library/Constants.php


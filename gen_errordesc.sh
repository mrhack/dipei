perl -lne 'printf qq(Constants::%s=>"desc.%s",\n),$1,lc $3 if /const\s+(([a-zA-Z]+)_([a-z_A-Z]+))/' application/library/ErrorConstants.php | \
perl -le 'use File::Slurp qw(:edit);$replace=join "",(<STDIN>);$replace=sprintf q($descs=array(%s);),$replace;edit_file {s/\$descs\s*=\s*array\(([\s\S]*)\);/$replace/} shift @ARGV;' application/library/GenErrorDesc.php
cat application/library/GenErrorDesc.php

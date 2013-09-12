#run test
binPath=$(pear config-get bin_dir)
phpunit=$binPath/phpunit

for testdir in $(ls -d tests/**/) ; do
   cd $testdir
   for test in $(ls *Test.php) ; do
      echo $test
      if ! $phpunit -v $test ; then
         echo "Test Failure!"
         read
         exit 1
      fi
   done
   cd -
done

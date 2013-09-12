if [[ -z $1 ]]; then
echo push.sh [commit message]
exit
fi
#run generation
cd script/gen
./gen_all_constants.sh
cd ../..
#pull to newest
git pull
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
         exit
      fi
   done
   cd -
done

#do push
git add --all
git commit --all  -m "$1"
git push

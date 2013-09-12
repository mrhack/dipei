if [[ -z $1 ]]; then
echo push.sh [commit message]
exit
fi
#run generation
cd script/gen
./gen_all_constants.sh
cd ../..
#pull to newest
if ! git pull ; then
   echo pull failed
   exit
fi
#run test
if ! ./runTest.sh ; then
   echo test failed
   exit
fi

#do push
git add --all
git commit --all  -m "$1"
git push

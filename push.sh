if [[ -z $1 ]]; then
echo push.sh [commit message]
exit
fi
git pull
cd script/gen
./gen_all_constants.sh
cd ../..
git add --all
git commit --all  -m $1
git push

if [[ -z $1 ]]; then
echo push.sh [commit message]
exit
fi
git pull
git add --all
git commit --all  -m $1
git push

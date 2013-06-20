sudo mkdir -p /data/logs/nginx
sudo chown -R www:www ..
sudo chown -R www:www /data/logs
perl -i -ple 's!root.*!root /www/dipei;! if `uname` =~ /linux/i' lepei.conf 
sudo pkill -f nginx
sudo pkill -f php-fpm
sudo pkill -f mongod
sudo nginx -c `pwd`/lepei.conf &
sudo php-fpm -c etc --fpm-config etc/php-fpm.conf&
sudo nginx -V
sudo mongod&

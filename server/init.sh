mkdir -p /data/logs/nginx
sudo chown -R www:www ..
sudo chown -R www:www /data/logs
rpm -Uvh http://repo.webtatic.com/yum/e16/latest.rpm
echo "[10gen]" > /etc/yum.repos.d/10gen.repo
echo "name=10gen Repository" >> /etc/yum.repos.d/10gen.repo
echo "baseurl=http://downloads-distro.mongodb.org/repo/redhat/os/x86_64" >> /etc/yum.repos.d/10gen.repo
echo "gpgcheck=0" >> /etc/yum.repos.d/10gen.repo
echo "enabled=1" >> /etc/yum.repos.d/10gen.repo
yum uninstall php php-common php-cli
yum install vim nginx12 cpan php54w php54w-devel php54-pear mongo-10gen mongo-10gen-server php54w-fpm ImageMagick.x86_64 ImageMagick-devel.x86_64 gd.x86_64 gd-devel.x86_64 php54w-gd.x86_64 p7zip.x86_64 -v -y --skip-broken >initsh.log
pecl install mongo yaf
echo "extension=mongo.so" > /etc/php.d/mongo.ini
echo "extension=yaf.so" > /etc/php.d/yaf.ini
pear config-set preferred_state beta
pecl install imagick
echo "extension=imagick.so" > /etc/php.d/imagick.ini
pecl install apc
echo "extension=apc.so" > /etc/php.d/apc.ini
mkdir -p /data/db
monogd &
#install nodejs
cd /usr/local/src
wget http://nodejs.org/dist/node-latest.tar.gz
tar zxvf node-latest.tar.gz
cd node*
make
make install
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR/../static
npm install cmd-util;npm install grunt;npm install sqwish;npm install uglify-js


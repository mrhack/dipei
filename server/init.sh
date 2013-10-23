#run in dipei directory!!!
yum update
mkdir -p /data/logs/nginx
mkdir -p /data/logs/lepei
sudo chown -R www:www ..
sudo chown -R www:www /data/logs
rpm -Uvh http://mirror.webtatic.com/yum/el6/latest.rpm
echo "[10gen]" > /etc/yum.repos.d/10gen.repo
echo "name=10gen Repository" >> /etc/yum.repos.d/10gen.repo
echo "baseurl=http://downloads-distro.mongodb.org/repo/redhat/os/x86_64" >> /etc/yum.repos.d/10gen.repo
echo "gpgcheck=0" >> /etc/yum.repos.d/10gen.repo
echo "enabled=1" >> /etc/yum.repos.d/10gen.repo
yum uninstall php php-common php-cli
yum install vim nginx12 cpan php54w php54w-devel php54w-pear mongo-10gen mongo-10gen-server php54w-fpm ImageMagick.x86_64 ImageMagick-devel.x86_64 gd.x86_64 gd-devel.x86_64 php54w-gd.x86_64 php54w-mbstring.x86_64 p7zip.x86_64 -v -y --skip-broken >initsh.log
#init configuration
echo "extension=mongo.so" > /etc/php.d/mongo.ini
echo "extension=yaf.so" > /etc/php.d/yaf.ini
echo "extension=apc.so" > /etc/php.d/apc.ini
echo "extension=imagick.so" > /etc/php.d/imagick.ini
pear config-set preferred_state beta
pecl install mongo yaf
printf "\n" | pecl install apc
mkdir -p /data/db
extension_dir=$(php -r "echo ini_get('extension_dir');")
#install imagick http://stackoverflow.com/questions/15569996/imagick-installation-on-webfaction
if [[ ! -f $extension_dir/imagick.so ]]; then
    wget http://pecl.php.net/get/imagick-3.1.0RC2.tgz
    tar xzvf imagick-3.1.0RC2.tgz
    cd imagick-3.1.0RC2
    phpize
    ./configure
    make
    cp modules/imagick.so $extension_dir
    cd ..
    rm -rf imagick-3.1.0RC2 imagick-3.1.0RC2.tgz
fi

#verify install env
echo installed:
which vim nginx cpan php mongo php-fpm convert 7za
for ext in gd mbstring mongo yaf imagick apc;
do
    echo $ext
    php -m|grep $ext
done
echo gd:
php -m|grep gd
echo mbstring:
php -m|grep mbstring
echo mongo:
php -m|grep mongo
echo imagick:
php -m|grep
#install nodejs
if ! which node; then
    cd /usr/local/src
    wget http://nodejs.org/dist/node-latest.tar.gz
    tar zxvf node-latest.tar.gz
    rm node-latest.tar.gz
    cd node*
    make
    make install
    DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
    cd $DIR/static
    npm install cmd-util;npm install grunt;npm install sqwish;npm install uglify-js 
    npm install jquery;npm install mkdirp;npm install mongodb;npm install fs-walk;npm install request
fi

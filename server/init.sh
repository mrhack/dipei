rpm -Uvh http://repo.webtatic.com/yum/e16/latest.rpm
echo "[10gen]" > /etc/yum.repos.d/10gen.repo
echo "name=10gen Repository" >> /etc/yum.repos.d/10gen.repo
echo "baseurl=http://downloads-distro.mongodb.org/repo/redhat/os/x86_64" >> /etc/yum.repos.d/10gen.repo
echo "gpgcheck=0" >> /etc/yum.repos.d/10gen.repo
echo "enabled=1" >> /etc/yum.repos.d/10gen.repo
yum uninstall php php-common php-cli
yum install vim nginx12 cpan php54w php54w-devel php54-pear mongo-10gen mongo-10gen-server php54w-fpm -v -y --skip-broken >initsh.log
pecl install mongo yaf
echo "extension=mongo.so" > /etc/php.d/mongo.ini
echo "extension=yaf.so" > /etc/php.d/yaf.ini
mkdir -p /data/db
monogd &
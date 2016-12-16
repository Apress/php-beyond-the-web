mkdir php5.4
cd php5.4
wget http://uk3.php.net/get/php-7.0.11.tar.gz/from/uk.php.net/mirror -o php-7.0.11.tar.gz
tar zxvf php-7.0.11.tar.gz
cd php-7.0.11
./configure
make clean
make
sudo make install

mkdir /home/rob/myMemoryDrive

sudo mount -t tmpfs /mnt/tmpfs /home/rob/myMemoryDrive

php -r "file_put_contents('/home/rob/myMemoryDrive/test.txt','Hello');"

cat /home/rob/myMemoryDrive/test.txt

sudo umount -a /mnt/tmpfs

cat /home/rob/myMemoryDrive/test.txt

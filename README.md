# GC

## Install
### 1. Software
```
VirtualBox
v5.2.8 or higher with guest additions 
(open virtualbox after install and you will be asked to install guest additions as well) 
https://www.virtualbox.org/wiki/Downloads

Vagrant 
v2.0.3 or higher 
https://www.vagrantup.com/downloads.html
```

### 2. Install Vagrant plugins. 
Open shell/cmd and execute the following commands:
```
vagrant plugin install vagrant-bindfs
vagrant plugin install vagrant-vbguest
vagrant plugin install vagrant-winnfsd
```

### 3. Add this to your hosts file.
```
192.168.97.100 dev.phpmyadmin.de
192.168.97.100 dev.redis-commander.de
192.168.97.100 dev.mailcatcher.de
192.168.97.100 dev.galactic-conquest.de
```

### 4. Start Vagrant
Open shell/cmd (in Administrator mode!) and execute the 
following command in the directory where the "Vagrantfile" is.
```
vagrant up
```
Wait until proivisioning is completed.

### 5. Additional

Stack 
```
ubuntu 16.04 (xenial)
php v7.2 with xdebug and some other plugins
mariadb v10
nginx v1.14
redis
npm
composer
```

Included 
```
dev.phpmyadmin.de // user: dev pw: dev
dev.redis-commander.de // view redis kv-store
dev.mailcatcher.de // view your sent mails from php
```

Vagrant commands (All in the "Vagrantfile"-directory)
```
vagrant ssh // Login to the vagrant machine 
vagrant halt // stop vagrant machine
vagrant start // start vagrant machine
```

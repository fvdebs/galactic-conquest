Vagrant.configure(2) do |config|
    config.vm.box = "ubuntu/xenial32"
    config.vm.network "forwarded_port", guest: 80, host: 1234
    config.vm.network "private_network", ip: "192.168.97.100"
    config.vm.provision :shell, :path => ".vagrant/provision/bootstrap.sh"

    config.vm.synced_folder "./../", "/var/www", type: "nfs", create: true
    config.winnfsd.uid = 33
    config.winnfsd.gid = 33

    config.vm.provision :shell, :inline => "sudo service redis restart", run: "always"
    config.vm.provision :shell, :inline => "sudo service php7.2-fpm restart", run: "always"
    config.vm.provision :shell, :inline => "sudo service nginx restart", run: "always"
    config.vm.provision :shell, :inline => "sudo service mysql restart", run: "always"
    config.vm.provision :shell, :inline => "sudo nohup redis-commander > /dev/null 2>&1 & ", run: "always"

    config.vm.provider :virtualbox do |vb|
        vb.customize ["modifyvm", :id, "--memory", "2048"]
        vb.customize ["modifyvm", :id, "--natdnshostresolver1", "off"]
        vb.customize ["modifyvm", :id, "--natdnsproxy1", "off"]
    end
end
Vagrant.configure("2") do |config|

    config.vm.box = "ubuntu/trusty64"

    config.vm.network "private_network", ip: "192.168.33.10"
    config.vm.network :forwarded_port, guest: 3000, host: 3000
    config.vm.network "forwarded_port", guest: 80, host: 80

    config.vm.synced_folder "./", "/opt/nuticket/current", id: "vagrant-ost2", :mount_options => ["dmode=777","fmode=777"]

    require 'rbconfig'
    is_windows = (RbConfig::CONFIG['host_os'] =~ /mswin|mingw|cygwin/)
    if is_windows
      # Provisioning configuration for shell script.
      config.vm.provision "shell" do |sh|
        sh.path = "develop/windows.sh"
      end
    else
      # Provisioning configuration for Ansible (for Mac/Linux hosts).
      config.vm.provision "ansible" do |ansible|
        ansible.playbook = "develop/vagrant.yml"
        ansible.inventory_path = "develop/hosts"
        ansible.sudo = true
      end
    end

    config.vm.provider :virtualbox do |virtualbox|
       virtualbox.customize ["modifyvm", :id, "--name", "nuticket"]
       virtualbox.memory = 1024
    end

end
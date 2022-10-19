# -*- mode: ruby -*-
# vi: set ft=ruby :

def vagrant_plugins_check()
    plugins_installed = false

    unless Vagrant.has_plugin?("vagrant-hostsupdater")
        system("vagrant plugin install vagrant-hostsupdater")
        puts "Dependency 'vagrant-hostsupdater' installed."
        plugins_installed = true
    end
    unless Vagrant.has_plugin?("vagrant-notify-forwarder")
        system("vagrant plugin install vagrant-notify-forwarder")
        puts "Dependency 'vagrant-notify-forwarder' installed."
        plugins_installed = true
    end
    unless Vagrant.has_plugin?("vagrant-vbguest")
        system("vagrant plugin install vagrant-vbguest")
        puts "Dependency 'vagrant-vbguest' installed."
        plugins_installed = true
    end

    if plugins_installed
        puts "Please try the command again."
        exit
    end
end

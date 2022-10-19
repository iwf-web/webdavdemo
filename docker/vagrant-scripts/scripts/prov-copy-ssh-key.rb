# -*- mode: ruby -*-
# vi: set ft=ruby :

def prov_copy_ssh_key(config)
    # copy the public SSH key of the host system user to the vagrant box to allow GIT access
    if File.exist?(File.expand_path("~/.ssh/id_rsa")) && File.exist?(File.expand_path("~/.ssh/id_rsa.pub"))
        config.vm.provision "file", source: "~/.ssh/id_rsa", destination: "~/.ssh/id_rsa", run: "always"
        config.vm.provision "file", source: "~/.ssh/id_rsa.pub", destination: "~/.ssh/id_rsa.pub", run: "always"
    elsif File.exist?(File.expand_path("~/.ssh/id_ed25519")) && File.exist?(File.expand_path("~/.ssh/id_ed25519.pub"))
        config.vm.provision "file", source: "~/.ssh/id_ed25519", destination: "~/.ssh/id_ed25519", run: "always"
        config.vm.provision "file", source: "~/.ssh/id_ed25519.pub", destination: "~/.ssh/id_ed25519.pub", run: "always"
    else
        puts "No SSH key found, please generate them first"
        puts "RSA:   $ ssh-keygen -t rsa -b 4096 -C \"your_email@example.com\""
        puts "ECDSA: $ ssh-keygen -t ed25519 -C \"your_email@example.com\""
        exit
    end

    $script = <<-SCRIPT
        ssh-keyscan -H "git.iwf.io" >> ~/.ssh/known_hosts
SCRIPT
    config.vm.provision "update-known_hosts", type: "shell", privileged: false, reset: true, inline: $script
end

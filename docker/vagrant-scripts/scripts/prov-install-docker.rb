# -*- mode: ruby -*-
# vi: set ft=ruby :

def prov_install_docker(config)
    $script = <<-SCRIPT
        sudo apt-get update
        sudo apt-get -y install ca-certificates curl gnupg2 curl lsb-release software-properties-common apt-transport-https apt-utils
        curl -fsSL https://download.docker.com/linux/debian/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
        echo \
            "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/debian \
            $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
        sudo apt-get update
        sudo apt-get install -y docker-ce docker-ce-cli containerd.io
        getent group docker >/dev/null || sudo groupadd docker
        sudo usermod -aG docker vagrant

        sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
        sudo chmod +x /usr/local/bin/docker-compose

        DOCKER_CONFIG=${DOCKER_CONFIG:-/usr/libexec/docker}
        sudo mkdir -p $DOCKER_CONFIG/cli-plugins
        sudo curl -SL https://github.com/docker/compose/releases/download/v2.4.0/docker-compose-linux-$(uname -m) -o $DOCKER_CONFIG/cli-plugins/docker-compose
        sudo chmod +x $DOCKER_CONFIG/cli-plugins/docker-compose
SCRIPT
    config.vm.provision "install-docker", type: "shell", privileged: false, reset: true, inline: $script
end

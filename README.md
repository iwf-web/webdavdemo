Webdav Demo Application
=======================

Webdav Demo Application for BaselHack 2022

[Click here for the challenge description](https://github.com/iwf-web/webdavdemo/wiki/Challenge-IWF)


Setting up everything
---------------------

<img align="right" src="docs/assets/overview.png">

A Symfony 5 application running inside Docker in an automatically provisioned Vagrant box.

You can use this example for easy local development and to create Docker images ready to be run on a server with Docker enabled.

It's very useful if you want all your developers to work with the exact some software versions and configurations. And to generate
Docker images you can use to run the exact same software on a production server.

Includes:

- Symfony 5 demo app
- Vagrant provisioning with [vagrant-scripts](https://github.com/iwf-web/vagrant-scripts)
- This documentation


It's based on our Open source Docker images:

- PHP 8.1: [IWF PHP base image on Dockerhub](https://hub.docker.com/repository/docker/iwfwebsolutions/phpfpm)
- Nginx 1.20: [IWF Nginx base image on Dockerhub](https://hub.docker.com/repository/docker/iwfwebsolutions/nginx)
- MySQL 8.0: [IWF MySQL base image on Dockerhub](https://hub.docker.com/repository/docker/iwfwebsolutions/mysql)

The virtual machine is based on Debian 9 (Stretch), using the [debian/contrib-stretch64](https://app.vagrantup.com/debian/boxes/contrib-stretch64) box image.

The following components are used:

- PHP 8.1
- Nginx 1.20
- MySQL 8.0
- PhpMyAdmin


For local development the following tools are available:

- XDebug
- Composer
- Yarn


# Requirements

To run this example project you need:

- [Vagrant](https://www.vagrantup.com)
- [VirtualBox](https://www.virtualbox.org)

If you don't have an Account on [Dockerhub](https://hub.docker.com) you should create one.
The setup process will ask you for the credentials.

You should also install these vagrant plugins:

- vagrant-notifyforwarder
- vagrant-hostsupdater
- vagrant-vbguest

If you're running Windows you should also install:

- vagrant-gatling-rsync
- vagrant-rsync-back

Install plugins like this:

```
vagrant plugin install vagrant-notifyforwarder
vagrant plugin install vagrant-hostsupdater
vagrant plugin install vagrant-vbguest
```

# Setup this project

Just run:

```
./bin/init_project.sh
cd docker/vagrant
vagrant up
```

It takes about 10-15 minutes, depending on your Internet and machine speed.
During the setup you need to enter your docker credentials. Please have
them at hand.

After everything is completed, a message is displayed saying:

```
Machine was booted, startup scripts are running inside the docker containers - please wait. 
If you're curious, you can execute 'docker logs -t fpm' inside vagrant or use the docker-logs.sh script. 
Once finished, the application will be available on http://webdavdemo
``` 

Now you can browse to http://webdavdemo and you see the demo application


# Learn more & start working

- [Understand the project structure](docs/structure.md)
- [Customize & handle your Vagrant box](docs/vagrant.md)
- [Understand the Docker](docs/docker.md)
- [Setup PhpStorm for development](docs/phpstorm.md)
- [Recipes, Tips & How To's](docs/tips.md)



There is no "su"-user in fpm-container anymore. If you need root-privileges, login with root:

    docker exec -ti -uroot fpm bash
	

C) Run yarn
-----------
use yarn to compile react code

    yarn
    yarn watch

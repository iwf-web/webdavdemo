Webdav Demo Application
=======================

Webdav Demo Application for BaselHack 2022

Setting up everything
---------------------

### Prerequisites


a1) Make sure required Vagrant plugins are installed. You can install them with "vagrant plugin install"

    vagrant plugin install vagrant-hostsupdater vagrant-vbguest
  
a2) If you want to use RSync synchronization instead of NFS, install the following plugins, too:
    
    vagrant plugin install vagrant-rsync-back vagrant-gatling-rsync

a3) It's generally good to have filesystem changes propagated to the container to support file watchers inside
    the docker containers like "yarn watch":
    
    vagrant plugin install vagrant-notify-forwarder



### Project setup for developers

a1) **Automatically**: run the script to copy the initial files. Adjust the copied files to your needs.

    ./bin/init_project.sh
       

a2) **Manually**

a2a) **Copy and customize the local vagrant_settings.yml.dist file**. For most cases, you won't need to change anything.
   This is the place to change synchronization type to "rsync" or "nfs".

    cd docker/vagrant
    cp vagrant_settings.yml.dist vagrant_settings.yml
    cd ../..

a2b) **Copy and configure docker-compose.yml**

    cd docker/run
    cp docker-compose.yml.dist docker-compose.yml
    
a3c) **Copy docker/run/data/conf/symfony_configs/parameters_local.yml.dist to parameters_local.yml**

    cd docker/run/data/conf/symfony_configs
    cp parameters_local.yml.dist parameters_local.yml

b) (Optional, if you have one) Grab an initial database dump from some system and put it into docker/run/data/dockerinit.d/mysql/.
   _ATTENTION: don't do this with the init_db.sql dump - it's already loaded through the Migration files._

c) Start the whole thing and take a coffee for 10 minutes. This builds the virtual machine,
   sets up Docker for local deployment and installs vendors inside the vagrant box

    cd docker/vagrant
    vagrant up
    
   During the setup you need to enter your docker credentials. Please have
   them at hand.
   
   This process takes some time to finish. Once it's finished, scripts are starting inside the docker
   containers to install dependencies, assets, clearing cache, ...
   
   To watch the status you can use the `./docker-logs.sh` command.
   
d) Setup PhpStorm for testing: see https://confluence.iwf.io/x/38Ac
   
e) ONLY FOR RSYNC USERS: copy the updated stuff back to your machine (vendors ...) or use an ssh-client

    vagrant rsync-back

f) Test if you can access the application by opening [http://webdavdemo/](http://webdavdemo/) in 
   your browser

g) ONLY FOR RSYNC USERS: If all is well, you can start file synchronization or use phpStorm

    vagrant gatling-rsync-auto


A) Creating test data
----------------------------------

#### B1) Super Admin

The super admin user "system" is automatically created in the init_db.sql.

CHANGE the password of this user in your final apps!


B) Next steps
----------------------------------


There is no "su"-user in fpm-container anymore. If you need root-privileges, login with root:

    docker exec -ti -uroot fpm bash
	

C) Run yarn
-----------
use yarn to compile react code

    yarn
    yarn watch
Vagrant-Dev-Scripts
===================

This is the central managed scripting resource for local Vagrant development.

Please have a look inside the [Symfony Vagrant Docker Example project](https://github.com/iwf-web/symfony-vagrant-docker-example) for a ready-to-run example application.


ATTENTION
---------

**PLASE DO NOT MAKE CHANGES TO THE vagrant-scripts FOLDER IN YOUR PROJECT.**

Use the provided customization points (see below) and centrally update the vagrant-scripts project in GIT.


Installation in your project
----------------------------

* From the root folder of your project, run:
  `git subtree add --prefix docker/vagrant-scripts https://github.com/iwf-web/vagrant-scripts.git master --squash`
  If you get a error "'master' does not refer to a commit", checkout master branch first in your local project and then checkout dev branch again
* Create the folder `docker/vagrant` (if not already existing)
* Create a `Vagrantfile` in your `vagrant` folder containing this line: `load '../vagrant-scripts/Vagrantfile.dist'`
* Copy the `vagrant_settings.yml.dist` to a `vagrant_settings.yml.dist` in your `vagrant` folder and customize it for your users
* Go on as usual to setup your local vagrant/docker stuff (copy .dist to local copy, run vagrant up...)


Update
------

From time to time, and to use newly introduced features, you should upgrade your vagrant-scripts:

* `git subtree pull --prefix docker/vagrant-scripts https://github.com/iwf-web/vagrant-scripts.git master --squash`
 

Customization
-------------

* Use the `vagrant_settings.yml(.dist)` to specify developer specific settings for local development
* Setup the following scripts in your `vagrant` folder:
  * `vagrant-setup.sh`: this one is run once on the first `vagrant up` and on `vagrant provision`
  * `vagrant-run-after-boot.sh`: this one is run each time you boot up the machine with `vagrant up` or `vagrant reload`
* In rare special cases you can customize the `Vagrantfile` in your `vagrant` folder to include a custom set of scripts


Usage
-----

The scripts register some provisioning scripts which can be run even after a project was setup. The usage is always the same:

`docker provision --provision-with="PROVISIONER"`

The following provisioners only run on initial setup, but can be re-run later:

* `startup`: Use this one to run the project-specific `vagrant-run-after-boot.sh` script (if available)
* `vagrant-setup`: Use this one to run the project-specific `vagrant-setup.sh` script (if available)
* `docker-login`: Use this one to re-login to Docker in your Vagrant box
* `run-docker-local-build`: Use this one to run the local Docker build in your Vagrant box
* `install-docker`: Use this one to update Docker inside your Vagrant box

To re-provision the whole machine, run: `vagrant provision`


Vbguest plugin
--------------

The Vbguest plugin (vagrant-vbguest) should be installed and up-to-date on your host system Basically, the plugin should be updated each time 
you upgrade Virtualbox and/or Vagrant. It installs the "VirtualBox guest additions" inside your VM.

If the guest additions are not running inside your vagrant box, you may experience the following behaviour:

- The VM date/time is not synchronized with the host, each time you put your host into sleep mode, the time stops in your VM - which may lead to wrong
  timestamps and other strange behaviour in your application
  
- The VM tends to get slower and decrease a lot in performance. It's not clear yet when this happens, and if this is really connected with the guest addtions

Installing the guest additions require an up-2-date kernel. The problem is discussed here: https://github.com/dotless-de/vagrant-vbguest/issues/351

That's why we try to do a update and upgrade of the system including a kernel upgrade before installing the plugin. This is done in `init-base-vm.rb`.

After the initial setup you should `halt` your VM and `up` it again to activate the new kernel.

To determine the `vbguest` status, you can use:

`vagrant vbguest --status`

This should output something like: `[default] GuestAdditions 6.1.18 running --- OK.`


Please check inside the VM if the kernel module has been loaded:

```
  root@tourenbuch:/vagrant/docker/run# lsmod | grep -i vbox
  vboxsf                 77824  0
  vboxvideo              36864  1
  ttm                    98304  1 vboxvideo
  drm_kms_helper        155648  1 vboxvideo
  vboxguest             335872  2 vboxsf
  drm                   360448  4 vboxvideo,ttm,drm_kms_helper
```

Troubleshooting:

- on the host: `vagrant vbguest --do install` (e.g. for an update of a running box)
- on the VM: `modprobe vboxguest`, `modprobe vboxsf`


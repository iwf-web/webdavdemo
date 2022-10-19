# -*- mode: ruby -*-
# vi: set ft=ruby :

def vagrant_plugins_config(config)
    # Configure the window for gatling to coalesce writes.
    if Vagrant.has_plugin?("vagrant-gatling-rsync")
        config.gatling.latency = 1.5
        config.gatling.time_format = "%H:%M:%S"

        # Automatically sync when machines with rsync folders come up.
        config.gatling.rsync_on_startup = false
    end
end

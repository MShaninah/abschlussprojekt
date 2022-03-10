#my final project for my apprenticeship
This Project is made as a final project with the composer workflow

## Login
To login, type the word /user after the domain name. for example www.123.de/admin.

Username: `mo`

Password: `bPK5hB7kukW2kPs`

## Overview

## How to beginn

- ###Glossary
    In this section I would like to summarize the most important steps relating to homestead so that we are both on a certain level of Knowledge.

    For specific questions that are not answered in this section, it is always advisable to take a look at the documentation:
    https://laravel.com/docs/6.x/homestead

    "Homestead" is the name of a special virtual machine (VM) for local development.
    The control / expansion of virtual machines is done with a software called "Vagrant".
    In order to be able to use / manage these virtual machines on the computer, you have to use "Virtualbox".

    In Homestead, all projects that you work on are integrated.
    So one virtual machine for everything!

- ###Installation
  First install VirtualBox [Hier](https://www.virtualbox.org/) and Vagrant [Hier](https://www.vagrantup.com/) on your computer.
  Then proceed with the installation as described in the documentation.
  I found this useful article to go step by step to set up Homestead on Windows 10:
  https://medium.com/@eaimanshoshi/i-am-going-to-write-down-step-by-step-procedure-to-setup-homestead-for-laravel-5-2-17491a423aa

    I recommend installing the following Vagrant plugins afterwards.
    To do this, execute these commands:
    ```shell script
    vagrant plugin install vagrant-hostsupdater

    vagrant plugin install vagrant-vbguest
    ```
  Additionally for Windows users:
    ```shell script
    vagrant plugin install vagrant-winnfsd
    ```
-  ###Deployment
  In your Homestead.yaml the 2nd line must be added under "Keys".
  You as a windows user adjust the path if necessary. Because Windows uses this Dumpass \ instead of this beautiful backslash /

    keys:
    - ~ / .ssh / id_rsa
    - ~ / .ssh / id_rsa.pub

- ###Add a new Project to your local machine
    In order to be able to use a new project in Homestead, there are some necessary adjustments in 3 places in Homestead.yaml.

    EXAMPLE with comments below the lines in gray for understanding (do not include in the file):

    folders:
    - map: / home / user / desk / projects / onlineshop
    ### ------> The path on your computer where the project is located
          to: / home / vagrant / code / onlineshop
    ### ------> The path in the homestead that will correspond to the local folder
          type: "nfs"
    ### ------> The type of integration of the folder (in some cases rsync is also suitable)
          mount_options: [nolock, vers = 3, udp, nocto, noatime, nodiratime, actimeo = 1]
    ### ------> Options for the NFS integration which (supposedly) accelerates everything a bit


Please read the installation instructions included in the repository or download file.

## Links

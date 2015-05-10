#!/bin/bash

# Install Ansible and its dependencies if it's not installed already.
if [ ! -f /usr/bin/ansible ]; then
  echo "Installing Ansible dependencies."
  apt-get install -y software-properties-common
  echo "Adding Ansible repositories."
  apt-add-repository ppa:ansible/ansible
  apt-get update
  echo "Installing Ansible."
  apt-get install -y ansible
fi

cp /opt/tickets/current/develop/hosts /tmp/ansible_hosts && chmod -x /tmp/ansible_hosts
echo "Running Ansible developer defined in Vagrantfile."
ansible-playbook /opt/tickets/current/develop/vagrant.yml --inventory-file=/tmp/ansible_hosts --extra-vars "is_windows=true" --connection=local
rm /tmp/ansible_hosts
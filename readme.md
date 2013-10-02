# souschef

souschef is a command-line wrapper for common chef tasks like deploying and updating environment files. It is designed to make [blue-green deployments](http://martinfowler.com/bliki/BlueGreenDeployment.html) very simple, and also has some verb-y wrappers around common knife commands.

## Installation

Clone this repo, then run [`composer install`](http://getcomposer.org/download/) in the souschef folder. Add the `bin/sc` command to your path. When you can run `sc` and see the list of commands (below) you're good to go!

## Commands

Here are some examples of the commands:

#### dump

`sc dump environment-blue`: Backup the environment to a local file (for git checkin or safekeeping)

#### update

Create `latest.json`:

```json
{
    "cookbook_versions": {
        "mysql": "= 3.0.2",
        "php": "= 1.2.2"
    },
    "default_attributes": {
        "cookbook1": {
            "version": "1.3.0"
        },
        "cookbook3": {
            "version": "1.5.5"
        }
    }
}
```

`sc update environment-blue latest.json`: Merges in the changes from `latest.json`.

#### promote

`sc promote environment-blue`: Copies environment-blue to environment-green (if both environments exist)

#### deploy

`sc deploy node hostname.blah.com`: Deploys the specified hostname

`sc deploy environment environment-blue`: Deploys all nodes in the specified environment

#### ssh

`sc ssh -e environment_name`: opens cssh to all the boxes within an environment

`sc ssh -n node_name`: opens cssh to boxes by name

`sc ssh -i ip_address`: opens cssh to boxes by ip

`sc ssh -r recipe`: opens cssh to boxes with a given recipe in their run list

`sc ssh -e environment_name -u username -c command`: runs an arbitrary `command` on all boxes under a given environment, logging in as the user `username`

If you set the environment variable `SC_SSH_USERNAME`, souschef will use that value as the username unless an option
with -u is used to override that value.

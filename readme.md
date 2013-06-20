# souschef

souschef is a command-line wrapper for common chef tasks like deploying and updating environment files. It is designed to make [blue-green deployments](http://martinfowler.com/bliki/BlueGreenDeployment.html) very simple, and also has some verb-y wrappers around common knife commands.

## Commands

Here are some examples of the commands:

#### dump

`sc dump environment-blue`: Backup the environment to a local file (for git checkin or safekeeping)

#### update

Create `latest.json`:

```json
{
  "cookbook_versions": {
  	"mysql": "= 0.0.111",
		"php": "= 0.0.45"
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

#### deploy

`sc deploy environment environment-blue`: Deploys all nodes in the specified environment

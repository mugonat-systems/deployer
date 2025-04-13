# Deploying using `deployer/deployer`

First install deployer using
```bash
composer require deployer/deployer --dev
```

## Configuration

Inside `.env` add:
```
DEPLOYER_GIT_USER=<your-git-username>
DEPLOYER_GIT_PASS=<your-git-token>
DEPLOYER_USER=<your-server-username>
DEPLOYER_DOMAIN=<your-app-server-domain>
```


## Usage

NOTE: When Deploying, make sure you have configured your public key on the server
NOTE: customEnv($name) will append "DEPLOYER_" to $name
  
If you are "DEPLOYER_USER" then run:
  - `vendor/bin/dep deploy`

If not, run:
 - `vendor/bin/dep deploy -o remote_user=root -o become=<replace-with-DEPLOYER_USER>`
 - !!! This will only work if you have root access

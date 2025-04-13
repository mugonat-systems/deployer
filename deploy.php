<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'deploy/custom_env.php';

/*
* =========================================================================
* NOTE: When Deploying, make sure you have configured your public key on the server
* NOTE: customEnv($name) will append "DEPLOYER_" to $name
* =========================================================================
* If you are "$user" then run:
*  - vendor/bin/dep deploy
* =========================================================================
* If not, run:
*  - vendor/bin/dep deploy -o remote_user=root -o become=<replace-with-$user>
*============================================================================
*/

$user = customEnv('USER');
$domain = customEnv('DOMAIN');
$gitUser = customEnv('GIT_USER');
$gitPass = customEnv('GIT_PASS');
$gitRepo = customEnv('GIT_REPO');

// Config
set('http_user', $user);
set('repository', "https://$gitUser:$gitPass@github.com/mugonat-systems/$gitRepo.git");

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('lin-low-server.mugonat.dev')
    ->setRemoteUser('root')
    ->setDeployPath("/home/$user/web/$domain/public_html");

// Tasks

task('npm-build', function () {
    cd('{{current_path}}');
    run('npm install');
    run('npm run build');
});

task('update-document-root', function () use ($user, $domain) {
    become('root');
    run("/usr/local/hestia/bin/v-change-web-domain-docroot $user $domain $domain 'current/public'");
});

// Hooks

after('npm-build', 'update-document-root');
after('deploy:success', 'npm-build');
after('deploy:failed', 'deploy:unlock');

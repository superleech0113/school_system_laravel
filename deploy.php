<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project repository
set('repository', 'git@gitlab.com:vinaysudani/bce.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

set('ssh_multiplexing', true);

set('keep_releases', 4);

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', [
    'resources/generated', // ziggy.js file is created in this folder
]);

// Hosts
host('test')
    ->hostname('uteach_test_ec2')
    ->set('branch', 'test')
    ->set('deploy_path', '/var/www/uteach')
    ->set('composer_options', 'install --no-scripts')
    ->set('bin/composer', "composer");

host('live')
    ->hostname('uteach_live_ec2')
    ->set('branch', 'master')
    ->set('deploy_path', '/var/www/uteach')
    ->set('composer_options', 'install --no-scripts')
    ->set('bin/composer', "composer");
    

// Tasks
task('firstdeploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'create_env',
    'deploy:unlock',
]);

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link',
    'ziggy_file',
    'npm_install',
    'npm_prod',
    'artisan:view:cache',
    //'artisan:config:cache',
    //'artisan:optimize',
    'artisan:migrate',
    'migrate_tenants',
    //'artisan:seed',
    'seed_tenants',
    //'clear_permission_cache',
    'deployment_specific_code',
    'make_sh_script_executable',
    'deploy:symlink',
    'deploy:unlock',
    'artisan:queue:restart',
    'cleanup',
]);

task('create_env', function () {
    run('cd {{release_path}} && sudo cp -R .env.example .env');
    run('cd {{release_path}} && {{bin/php}} artisan key:generate');
});

task('make_sh_script_executable', function () {
    run('cd {{release_path}} && chmod 744 nginx_vhost.sh');
});

task('migrate_tenants', function () {
    run('cd {{release_path}} && {{bin/php}} artisan tenants:migrate --force');
});

task('seed_tenants', function () {
    run('cd {{release_path}} && {{bin/php}} artisan tenants:seed --force');
});

task('clear_permission_cache', function () {
    run('cd {{release_path}} && {{bin/php}} artisan permission:cache-reset');
});

task('deployment_specific_code', function () {
    // run('cd {{release_path}} && {{bin/php}} artisan onetime:special_deployment');
});

task('ziggy_file', function () {
    run('cd {{release_path}} && {{bin/php}} artisan ziggy:generate "resources/generated/ziggy.js"');
});

task('npm_install', function () {
    run('cd {{release_path}} && npm install');
});

task('npm_prod', function () {
    run('cd {{release_path}} && npm run prod');
});

after('deploy', 'success');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
# cloud

## Setup Project In localhost

### Requirements
- PHP 7.2 or greater
- composer
- MySql 5.7
- Node

### Steps
- Clone Project From git
- copy .env.example to .env & update .env
- composer install
- php artisan key:generate
- npm install
- php artisan migrate
- php artisan ziggy:generate "resources/generated/ziggy.js"
- npm run dev OR npm run watch
- create new tenant
    - call create tenant-subscription api from postman
    - submit create site form


### Commands to run when changing branches in localhost

npm install
composer install
php artisan ziggy:generate "resources/generated/ziggy.js"
php artisan permission:cache-reset
npm run dev / npm run watch

Npm command to do this all
`npm run branch-changed`

### Git workflow

#### Golden Rules
 - Always create new branch from `master` branch
 - Never ever create new branch from `test` branch

#### Step 1
- Create new branch for each new feature (from master)
- for e.g. we create 3 branches feauture-1, feature-2, feature-3

#### Step 2
- when development is done for particular feature, merge that branch with "test" branch and push it to test server.
- for e.g. when development for feature-1 is completed, merge that branch into test branch (and deploy to test server)
- similarly when development for feature-2 is completed, merge that branch into test branch (and deploy to test server)
- Likewise for all branches

#### Step 3
- Once the particular feature is tested and approved on test server, we can merge that feature branch into master
- for e.g. feature-1 and feture-2 are complete - merge those both branch into master (and deploy to live server)
- for e.g. fetaure-3 needs more updates - continue work on that branch, once done follow step 2 above

#### Execptions
- Development for feauture-1 is done and pushed to test server, but not merged into master yet, and you need to work on feature-2 that needs all the changes of feature-1 then you can create new branch from master branch called feature-2 and merge feature-1 branch into feature-2 branch you just created.

## Project Instances

## New Instance Creation
- Add details in deploy.php file
- dep firstdeploy {projectname}
- create new db & [ user - no need to create user if already exists].
- update details in .env file
- dep deploy {projectname}
- add instance details to "Instances" section above.
- Cron Job Setup (Create new scheduled task from plesk interface)
- Que Setup
- Remove cache - php artisan cache:clear
- Check Settings on server
    upload_max_filesize = 64MB
    post_max_size = 64MB
- update deployment script in package.json file

## Other Notes
- Sytem generated timestamps are stored in UTC, other dates & time are stored in as it is so it will be considered to be in timezone set in school settings page.

### Deployer notes

dep ssh {host} - login into current version forlder for given project
dep deploy {host} - deploy to particular instance

### Ziggy Integration

php artisan ziggy:generate "resources/generated/ziggy.js" - need to run this command whenever route files are being modified.


## ngrok
ngrok http tenant1.localhost

## Import database in local
docker exec -i laradock-uteach_mysql_1 mysql -uroot -proot {databasename} < {sql_file_to_import}

## Custom Seeder Implementation
#### Goal:
Run a seeder file only once (per instance), same as how laravel migration works.

#### Components:

##### Custom Seeder File Generator
`php artisan make:custom_seeder` (handled by custom console command named `CreateCustomSeederFile.php`)

##### Custom Seeder File Runner

`php artisan db:seed` (Same as how seeding works normally, handled by `DatabaseSeeder.php` file)

#### for file upload settings
- sudo nano /etc/php/7.2/fpm/php.ini
- sudo nano /etc/nginx/nginx.conf
- sudo systemctl restart php7.2-fpm.service
- sudo service nginx reload

## Changes introduced due to multi-tenancy architecture
- database migrations folder
   - for central database put migrations in `database/migrations` folder
   - for tenant databse put migrations in `database/migrations/tenant`
- running migration & seeder commnds
  - `php artisan migrate` - to migrate central database
  - `php artisan tenants:migrate` - to migrate all tenant databases
  - `php artisan tenants:seed` - to run seeder files for all tenant databases
  - currenly we dont have mechanism to run seeder files on central database as we dont need it
  - `php artisan tenants:rollback` - to rollback database changes in tenant databases
  - `php artisan migrate:rollback` - to rollback database changes in central database 
- routes
  - routes for central part of application will be in `routes/web.php`
  - routes for tenant part of application will be in `routes/tenant.php`
- file system
    - files for central part of appication will be in `storage/` folder
    - files for each tenant will be in theire respective folder e.g. `storage/tenant3012f47a-b02c-4f85-a58b-2ba907e589cb/`
    - for tenants part of applicatin instead of using `Storage::disk('public')->url('books/'.$this->thumbnail)` use `tenant_asset('books/'.$this->thumbnail)`
  

## Old Plesk server notes

### View Status of que workers
1. SSH as root user
2. `supervisorctl status`

### Add Remove que workers
1. SSH as root user
2. nano /etc/supervisord.conf
3. supervisorctl reload
4. supervisorctl status

### Notes
- `supervisorctl` - to enter into interactive mode 
- `help` - to view all commands)
- to use specific version of php use `/opt/plesk/php/7.2/bin/php` ()

- https://stackoverflow.com/a/47322239/6336363
- https://stackoverflow.com/a/42911758/6336363
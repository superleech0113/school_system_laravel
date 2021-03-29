<?php

namespace App\Console\Commands;

use App\Activity;
use App\Books;
use App\CancellationPolicies;
use App\CancelType;
use App\Category;
use App\ClassCategories;
use App\Classes;
use App\Contacts;
use App\Courses;
use App\EmailTemplates;
use App\FooterLinks;
use App\LessonExercise;
use App\LessonFile;
use App\LessonHomework;
use App\Lessons;
use App\NotificationText;
use App\Permission;
use App\Role;
use App\Schedules;
use App\Settings;
use App\Students;
use App\Tag;
use App\Teachers;
use App\Units;
use Illuminate\Console\Command;

class CollectSeedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collect_seed_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        tenancy()->init(env('SEED_DATABASE_TENANT_DOMAIN'));
        $records = [];

        $records['activities'] = [
            'eloquent' => '\App\Activity',
            'records' => Activity::select(['id', 'name'])->get()->toArray()
        ];
        $records['books'] = [
            'eloquent' => '\App\Books',
            'records' => Books::all()->toArray()
        ];
        $records['cancellation_policies'] = [
            'eloquent' => '\App\CancellationPolicies',
            'records' => CancellationPolicies::all()->toArray()
        ];
        $records['cancel_types'] = [
            'eloquent' => '\App\CancelType',
            'records' => CancelType::all()->toArray()
        ];
        $records['categories'] = [
            'eloquent' => '\App\Category',
            'records' => Category::select(['id','name'])->get()->toArray()
        ];
        $records['classes'] = [
            'eloquent' => '\App\Classes',
            'records' => Classes::all()->toArray()
        ];
        $records['class_categories'] = [
            'eloquent' => '\App\ClassCategories',
            'records' => ClassCategories::all()->toArray()
        ];
        $records['contacts'] = [
            'eloquent' => '\App\Contacts',
            'records' =>  Contacts::select(['id','customer_id','message','status','type','user_id'])->get()->toArray()
        ];
        $records['courses'] = [
            'eloquent' => '\App\Courses',
            'records' => Courses::all()->toArray()
        ];
        $records['email_templates'] = [
            'eloquent' => '\App\EmailTemplates',
            'records' => EmailTemplates::all()->toArray()
        ];
        $records['footer_links'] = [
            'eloquent' => '\App\FooterLinks',
            'records' => FooterLinks::select(['id','label_en','label_ja','link','display_order'])->get()->toArray()
        ];
        $records['lessons'] = [
            'eloquent' => '\App\Lessons',
            'records' => Lessons::get()->toArray()
        ];
        $records['lesson_exercises'] = [
            'eloquent' => '\App\LessonExercise',
            'records' => LessonExercise::get()->toArray()
        ];
        $records['lesson_files'] = [
            'eloquent' => '\App\LessonFile',
            'records' => LessonFile::select(['id','lesson_id','section','file_path','file_name'])->get()->toArray()
        ];
        $records['lesson_homeworks'] = [
            'eloquent' => '\App\LessonHomework',
            'records' => LessonHomework::get()->toArray()
        ];
        $records['model_has_roles'] = [
            'eloquent' => null,
            'records' => \DB::table('model_has_roles')->get()->toArray()
        ];
        $records['notification_texts'] = [
            'eloquent' => '\App\NotificationText',
            'records' => NotificationText::get()->toArray()
        ];
        $records['permissions'] = [
            'eloquent' => '\App\Permission',
            'records' => Permission::select(['id','name','guard_name','category_id','tooltip_en','tooltip_ja'])->get()->toArray()
        ];
        $records['roles'] = [
            'eloquent' => '\App\Role',
            'records' => Role::select(['id','name','guard_name','login_redirect_path','is_student','default_lang','send_login_details','can_login','can_add_user'])->get()->toArray()
        ];
        $records['role_has_permissions'] = [
            'eloquent' => null,
            'records' => \DB::table('role_has_permissions')->get()->toArray()
        ];
        $records['schedules'] = [
            'eloquent' => '\App\Schedules',
            'records' => Schedules::get()->toArray()
        ];
        $records['settings'] = [
            'eloquent' => '\App\Settings',
            'records' => Settings::get()->toArray()
        ];
        $records['students'] = [
            'eloquent' => '\App\Students',
            'records' => Students::get()->toArray()
        ];
        $records['tags'] = [
            'eloquent' => '\App\Tag',
            'records' => Tag::select(['id','name','color','icon','is_automated'])->get()->toArray()
        ];
        $records['teachers'] = [
            'eloquent' => '\App\Teachers',
            'records' => Teachers::get()->toArray()
        ];
        $records['units'] = [
            'eloquent' => '\App\Units',
            'records' => Units::get()->toArray()
        ];
        $records['users'] = [
            'eloquent' => '\App\User',
            'records' => \Db::table('users')->select(['id','name','email','username','email_verified_at','password','remember_token','lang','receive_emails','receive_line_messsges','calendar_view','stripe_customer_id','zoom_email','line_user_id'])->get()->toArray()
        ];


        $base_path = base_path() . "/seed-data-".date('Y-m-d-H-i-s-').rand(111,222);
        mkdir($base_path, 0755, true);

        $records = file_put_contents($base_path."/seed.json", json_encode($records));
        
        $source_dir = storage_path().'/app/public/*';
        $dest_dir = $base_path . '/files/app/public/';
        mkdir($dest_dir, 0755, true);
        exec('cp -r '. $source_dir .' '.$dest_dir);

        dump("Seed data exported to folder ".$base_path);
    }
}
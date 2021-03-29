<?php

namespace Database\Seeds\Custom;

use App\EmailTemplates;
use App\Permission;
use App\Role;
use App\Settings;
use Illuminate\Database\Seeder;

class Seeder1593678274 extends Seeder
{
    public function run()
    {
        $settingsToSeed = [
            'application_series' => '100',
        ];
        foreach($settingsToSeed as $key => $value){
            $setting = new Settings();
            $setting->name = $key;
            $setting->value = $value;
            $setting->save();
        } 

        // Email Templates
        $emailTemplatesToSeed = array(
            [   'name' => 'new_application_notification_without_docs',
                'subject_en' => 'You are registered for {school_name}!',
                'content_en' => 'Dear {full_name},
                                <br><br>
                                Thank you for sending in your application.
                                Your application number is {application_no}.
                                Please upload your documents using the below link to complete your application.<br>
                                <a href="{application_link}">{application_link}</a>
                                <br><br>
                                Have a great day!
                                <br>
                                uTeach Cloud',
                'subject_ja' => '{school_name}のアプリケーションありがとうございます！',
                'content_ja' => '{full_name}様
                                <br><br>
                                登録ありがとうございました！
                                アプリケーション番号は {application_no}です。
                                下記のリンクにより書類をアップロードしてください。<br>
                                <a href="{application_link}">{application_link}</a>
                                <br><br>
                                よろしくお願いします。
                                <br>
                                uTeach Cloud',
                'enable' => '1'
            ],
            [   'name' => 'new_application_notification',
                'subject_en' => 'You are registered for {school_name}!',
                'content_en' => 'Dear {full_name},
                                <br><br>
                                Thank you for sending in your application.
                                Your application number is {application_no}.
                                We will be in touch with regards to your application soon.
                                <br><br>
                                Have a great day!
                                <br>
                                uTeach Cloud',
                'subject_ja' => '{school_name}のアプリケーションありがとうございます！',
                'content_ja' => '{full_name}様
                                <br><br>
                                登録ありがとうございました！
                                アプリケーション番号は {application_no}です。
                                アプリケーションについて近々連絡させていただきます。
                                <br><br>
                                よろしくお願いします。
                                <br>
                                uTeach Cloud',
                'enable' => '1'
            ],
        );
        
        foreach($emailTemplatesToSeed as $record) {
            $emailTemplate = new EmailTemplates();
            $emailTemplate->name = $record['name'];
            $emailTemplate->subject_en = $record['subject_en'];
            $emailTemplate->content_en = $this->formatMultilineWhiteSpace($record['content_en']);
            $emailTemplate->subject_ja = $record['subject_ja'];
            $emailTemplate->content_ja = $this->formatMultilineWhiteSpace($record['content_ja']);
            $emailTemplate->enable = $record['enable'];
            $emailTemplate->save();
        }

        $permissionTooltipsToSeed = array(
            [
                'name' => 'convert-to-student',
                'category_id' => 8,
                'tooltip_en' => 'Allow user to convert an applicant to a student.',
                'tooltip_ja' => 'ユーザーにアプリケーションを生徒に変更する権限を与える。'
            ],
            [
                'name' => 'application-edit',
                'category_id' => 8,
                'tooltip_en' => 'Allow user to edit an application.',
                'tooltip_ja' => 'ユーザーにアプリケーションを編集する権限を与える。'
            ],
            [
                'name' => 'application-delete',
                'category_id' => 8,
                'tooltip_en' => 'Allow user to delete an application.',
                'tooltip_ja' => 'ユーザーにアプリケーションを生徒に削除する権限を与える。'
            ],
            [
                'name' => 'application-list',
                'category_id' => 8,
                'tooltip_ja' => 'ユーザーにアプリケーション一覧を見る権限を与える。',
                'tooltip_en' => 'Allow user to see list of applications.'
            ],
            [
                'name' => 'application-create',
                'category_id' => 8,
                'tooltip_en' => 'Allow user to create an application.',
                'tooltip_ja' => 'ユーザーにアプリケーションを生徒に変更する権限を与える。'
            ]
        );
        foreach ($permissionTooltipsToSeed as $permissionTooltip){
            Permission::create([ 'name' => $permissionTooltip['name'],
                    'category_id' => $permissionTooltip['category_id'],
                    'tooltip_en' => $permissionTooltip['tooltip_en'],
                    'tooltip_ja' => $permissionTooltip['tooltip_ja'],
            ]);
            $roleHasPermissionsToSeed = array(
                'role' => 'Super Admin','permission' => $permissionTooltip['name']
            );
            $role = Role::where('name', $roleHasPermissionsToSeed['role'])->first();
            $role->givePermissionTo($roleHasPermissionsToSeed['permission']);
        }

    }


    public function formatMultilineWhiteSpace($content)
    {
        $new_line_delimeter = "\n";
        $new_content = '';
        $lines = explode($new_line_delimeter, $content);
        foreach($lines as $line) {
            $new_content .= trim($line) . $new_line_delimeter;
        }
        return trim($new_content);
    }

}
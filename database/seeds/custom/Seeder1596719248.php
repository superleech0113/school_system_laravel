<?php

namespace Database\Seeds\Custom;

use App\EmailTemplates;
use App\NotificationText;
use Illuminate\Database\Seeder;

class Seeder1596719248 extends Seeder
{
    public function run()
    {
         // Email Templates
         $emailTemplatesToSeed = array(
            [   'name' => 'stripe_subscription_created',
                'subject_en' => 'uTeach Cloud: You have been subscribed!',
                'content_en' => 'Dear {student_name},
                                <br><br>
                                You are now subscribed to a credit card subscription.
                                <br><br>
                                Click below link to add or update card details.
                                <br>
                                <a href="{cards_page_link}" target="_blank">{cards_page_link}</a>
                                <br><br>
                                Have a great day!<br>
                                - uTeach Cloud',
                'subject_ja' => 'uTeach Cloud: サブスクリプション追加されました',
                'content_ja' => '{student_name}様,
                                <br><br>
                                カード払いのサブスクリプションが始まりました。
                                <br><br>
                                下記のリンクによりカード情報を追加してください。
                                <br>
                                <a href="{cards_page_link}" target="_blank">{cards_page_link}</a>
                                <br><br>
                                よろしくお願いします。<br>
                                - uTeach Cloud',
                'enable' => '1',
                'notification_texts' => [
                    [
                        'type' => NotificationText::TYPE_LINE_TEXT,
                        'key' => 'message_text',
                        'text_en' => 'You are now subscribed to a stripe subscription.',
                        'text_ja' => 'カード払いのサブスクリプションが始まりました。'
                    ],
                    [
                        'type' => NotificationText::TYPE_LINE_TEXT,
                        'key' => 'cards_page_button_text',
                        'text_en' => 'Add / Update card',
                        'text_ja' => 'カード追加・編集'
                    ]
                ]
            ],
            [   'name' => 'stripe_subscription_requires_new_payment_method',
                'subject_en' => 'uTeach Cloud: Subscription payment has failed',
                'content_en' => 'Dear {student_name},
                                <br><br>
                                We failed to charge the invoice for your subscription with card details you provided. Please add new card details and set it as default card via below link.
                                <br>
                                <a href="{cards_page_link}" target="_blank">{cards_page_link}</a>
                                <br><br>
                                Have a great day!<br>
                                - uTeach Cloud',
                'subject_ja' => 'uTeach Cloud: 支払いが失敗しました',
                'content_ja' => 'Dear {student_name},
                                <br><br>
                                カード払いサブスクリプションの支払いが失敗しました。下記のリンクによりカード情報を編集しました。
                                <br>
                                <a href="{cards_page_link}" target="_blank">{cards_page_link}</a>
                                <br><br>
                                よろしくお願いします。<br>
                                - uTeach Cloud',
                'enable' => '1',
                'notification_texts' => [
                    [
                        'type' => NotificationText::TYPE_LINE_TEXT,
                        'key' => 'message_text',
                        'text_en' => 'Stripe failed to charge the invoice for your subscription with card details you provided, Please add new card details and set it as default card.',
                        'text_ja' => 'カード払いのサブスクリプションが失敗しました。'
                    ],
                    [
                        'type' => NotificationText::TYPE_LINE_TEXT,
                        'key' => 'cards_page_button_text',
                        'text_en' => 'Update card details',
                        'text_ja' => 'カード情報を編集する'
                    ]
                ]
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

            foreach($record['notification_texts'] as $notification_text_record)
            {
                $notificationText = new NotificationText();
                $notificationText->email_template_id = $emailTemplate->id;
                $notificationText->type = $notification_text_record['type'];
                $notificationText->key = $notification_text_record['key'];
                $notificationText->text_en = $notification_text_record['text_en'];
                $notificationText->text_ja = $notification_text_record['text_ja'];
                $notificationText->save();
            }
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
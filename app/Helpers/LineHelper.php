<?php

namespace App\Helpers;

use App\Jobs\SendLineMessage;
use App\LineAccountConnectNonce;
use App\LineChannelAccessToken;
use App\Settings;
use App\User;
use Carbon\Carbon;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

class LineHelper
{
    public static function getBot()
    {
        $res = self::getAccessToken();

        if($res['status'] == 0) {
            return [
                'status' => 0,
                'message' => $res['message']
            ];
        }
        $channel_access_token = $res['access_token'];
        $channel_secret = Settings::get_value('line_channel_secret');

        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($channel_access_token);
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channel_secret]);

        return [
            'status' => 1,
            'bot' => $bot
        ];
    }

    public static function getAccessToken()
    {
        $lineAccessToken = LineChannelAccessToken::first();
        if(!$lineAccessToken) {
            return [
                'status' => 0,
                'message' => 'Line access token not found'
            ];
        }

        return [
            'status' => 1,
            'access_token' => $lineAccessToken->access_token
        ];
    }

    public static function handleFollowEvent($bot, $event)
    {
        $line_user_id = $event->getUserId();

        $response = $bot->createLinkToken($line_user_id);
        if(!$response->isSucceeded())
        {
            \Log::error($response->getJSONDecodedBody());
            return;
        }
            
        $body = $response->getJSONDecodedBody();
        $link_token = $body['linkToken'];

        $url_to_send = route('link.line.force.login',$link_token);

        $line_account_link_meesage_text = Settings::get_value('line_account_link_meesage_text');
        $line_account_link_meesage_button_text = Settings::get_value('line_account_link_meesage_button_text');        

        $templateMessageBuilder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder($line_account_link_meesage_text, 
            new ButtonTemplateBuilder(null, $line_account_link_meesage_text, null, [
                new UriTemplateActionBuilder($line_account_link_meesage_button_text, $url_to_send)
            ])
        );
        $response = $bot->pushMessage($line_user_id, $templateMessageBuilder);
        if(!$response->isSucceeded())
        {
            \Log::error($response->getJSONDecodedBody());
        }
    }

    public static function handleUnFollowEvent($bot, $event)
    {
        $line_user_id = $event->getUserId();
        $user = User::where('line_user_id',$line_user_id)->first();
        if($user) {
           $user->setLineUserId(NULL);
        }
    }

    public static function handleAccountLinkEvent($bot, $event)
    {
        if(!$event->isSuccess()) 
        {
            return;
        }

        $nonce = $event->getNonce();
        $line_user_id = $event->getUserId();

        $lineAccountConnectNonce = LineAccountConnectNonce::where('nonce', $nonce)->first();
        if($lineAccountConnectNonce)
        {
            // delete line_user_id if already linked with any user, to make sure that one line_user_id is always linked to only one user account
            $user = User::where('line_user_id',$line_user_id)->first();
            if($user) {
               $user->setLineUserId(NULL);
            }
            
            $user = User::find($lineAccountConnectNonce->user_id);
            $user->setLineUserId($line_user_id);

            $lineAccountConnectNonce->delete();

            $lang = $user->get_lang();
            $message_text = Settings::get_value('line_account_linked_message_text_'.$lang);
            
            $templateMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message_text);
            $response = $bot->pushMessage($line_user_id, $templateMessageBuilder);
            if(!$response->isSucceeded())
            {
                \Log::error($response->getJSONDecodedBody());
            }
        }
    }

    public static function sendMessgeInBackground($to, $messageBuilder)
    {
        SendLineMessage::dispatch($to, $messageBuilder)->onQueue('line');
    }

    public static function syncChannelAccessTokens()
    {
        $lineClient = new LineClient();
        $res = $lineClient->getChannelAccessTokenKeyids();
        if($res['status'] == 0) {
            return [
                'status' => 0,
                'message' => $res['message']
            ];
        }
        LineChannelAccessToken::whereNotIn('key_id', $res['kids'])->delete();

        $count = LineChannelAccessToken::notExpiringSoon()->count();
        if ($count == 0) 
        {
            $lineClient = new LineClient();
            $res = $lineClient->issueChannelAccessToken();
            if($res['status'] == 0) {
                return [
                    'status' => 0,
                    'message' => $res['message']
                ];
            }
            $lineChannelAccessToken = new LineChannelAccessToken();
            $lineChannelAccessToken->key_id = $res['key_id'];
            $lineChannelAccessToken->access_token = $res['access_token'];
            $lineChannelAccessToken->expires_at = Carbon::now('UTC')->addSeconds($res['expires_in'])->format('Y-m-d H:i:s');
            $lineChannelAccessToken->save();
        }
        LineChannelAccessToken::expiringSoon()->delete();

        return [
            'status' => 1,
        ];
    }
}

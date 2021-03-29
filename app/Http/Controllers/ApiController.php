<?php

namespace App\Http\Controllers;

use App\ClassUsage;
use App\Helpers\AutomatedTagsHelper;
use App\Helpers\LineHelper;
use App\Helpers\PaymentHelper;
use App\MonthlyPayments;
use App\Settings;
use App\TenantSubscription;
use App\ZoomMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\UnfollowEvent;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;
use LINE\LINEBot\Event\AccountLinkEvent;

class ApiController extends Controller
{
    public function stripeWebhook()
    {
        \Stripe\Stripe::setApiKey(Settings::get_value('stripe_secret_key'));
        $endpoint_secret = Settings::get_value('stripe_webhook_signing_secret_key');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'invoice.payment_succeeded': // For keeping old setup working - will be removed in future
                $stripeInvoice = $event->data->object;
                if(!$stripeInvoice->subscription)
                {
                    PaymentHelper::markStripePaymentRecordAsPaid($stripeInvoice);
                }
                break;
            case 'invoice.paid':
                $stripeInvoice = $event->data->object;
                if($stripeInvoice->subscription)
                {
                    PaymentHelper::createPaymentEntryFromInvoice($stripeInvoice->id);
                }
                else
                {
                    PaymentHelper::markStripePaymentRecordAsPaid($stripeInvoice);
                }
                break;
            case 'customer.subscription.updated':
                $data = $event->data->object;
                PaymentHelper::updateLocalSubscriptionData($data->toArray());
                break;
        }
        
        http_response_code(200);
    }

    public function zoomWebhook(Request $request)
    {
        // Validate request
        $verification_token = Settings::get_value('zoom_webhook_verification_token');
        $req_verification_token = $request->header('authorization');
        if($req_verification_token != $verification_token)
        {
            http_response_code(400);
            exit();
        }

        $payload = @file_get_contents('php://input');
        $data = json_decode($payload,1);

        if($data['event'] == 'meeting.deleted') {

            $meeting_id = $data['payload']['object']['id'];
            ZoomMeeting::where('id', $meeting_id)->delete();

        } else if($data['event'] == 'meeting.updated') {
            
            $meeting_id = $data['payload']['object']['id'];
            $zoomMeeting = ZoomMeeting::find($meeting_id);
            if($zoomMeeting) {
                $zoomMeeting->updatedFromZoom($data['payload']['object']);
            }
        }

        http_response_code(200);
    }

    public function lineWebhook(Request $request)
    {
        $signature = $request->header(HTTPHeader::LINE_SIGNATURE);
        if (!$signature) {
            return response('Bad Request', 400);
        }

        $res = LineHelper::getBot();
        if($res['status'] == 0) {
            \Log::error($res['message']);
            return response( __('messages.something-went-wrong'), 500);
        }
        $bot = $res['bot'];

        // Check request with signature and parse request
        try {
            $events = $bot->parseEventRequest($request->getContent(), $signature);
        } catch (InvalidSignatureException $e) {
            return response(__('messages.invalid-signature'), 400);
        } catch (InvalidEventRequestException $e) {
            return response(__('messages.invalid-event-request'), 400);
        }

        foreach ($events as $event) {
            if ($event instanceof FollowEvent) {
                LineHelper::handleFollowEvent($bot, $event);
            }
            else if ($event instanceof UnfollowEvent) {
                LineHelper::handleUnFollowEvent($bot, $event);
            }
            else if ($event instanceof AccountLinkEvent) {
                LineHelper::handleAccountLinkEvent($bot, $event);
            }
        }

        return response('',200);
    }

    public function createTenantSubscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required|max:50|unique:tenant_subscriptions,id',
        ]);

        if ($validator->fails()) {
            $validationErrors = $validator->errors()->toArray();
            $out['error'] = array_values($validationErrors)[0][0];
            return response()->json($out, 422);
        }

        try {
            $tenantSubscription = new TenantSubscription();
            $tenantSubscription->id = $request->subscription_id;
            $tenantSubscription->status = TenantSubscription::TENANT_NOT_CREATED;
            $tenantSubscription->subscription_status = TenantSubscription::SUBSCRIPTION_ACTIVE;
            $tenantSubscription->save();

            return response()->json([
                'subscription_id' => $tenantSubscription->id,
                'subscription_status' => $tenantSubscription->subscription_status
            ], 201);
        } catch (\Exception $e) {
            \Log::error($e);
            $out['error'] = 'Something went wrong';
            return response()->json($out, 500);
        }
    }

    public function updateTenantSubscription(Request $request, $subscription_id)
    {
        $tenantSubscription = TenantSubscription::findOrFail($subscription_id);

        $validator = Validator::make($request->all(), [
            'subscription_status' => 'required',
        ]);

        if ($validator->fails()) {
            $validationErrors = $validator->errors()->toArray();
            $out['error'] = array_values($validationErrors)[0][0];
            return response()->json($out, 422);
        }

        try {
            $subscription_status = $request->subscription_status == TenantSubscription::SUBSCRIPTION_ACTIVE ? TenantSubscription::SUBSCRIPTION_ACTIVE : TenantSubscription::SUBSCRIPTION_INACTIVE;
            $tenantSubscription->subscription_status = $subscription_status;
            $tenantSubscription->save();

            return response()->json([
                'subscription_id' => $tenantSubscription->id,
                'subscription_status' => $tenantSubscription->subscription_status
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            $out['error'] = 'Something went wrong';
            return response()->json($out, 500);
        }
    }
}

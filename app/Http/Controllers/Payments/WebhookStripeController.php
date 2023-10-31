<?php

namespace App\Http\Controllers\Payments;


use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookStripeController extends Controller
{
    public function webHookHandelSubscribers(Request $request)
    {

        // The library needs to be configured with your account's secret key.
        // Ensure the key is kept out of any version control system you might be using.
        // This is your Stripe CLI webhook secret for testing your endpoint locally.

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        switch ($event->type) {
            case 'customer.subscription.created':
                $subscription = $event->data->object;

                DB::table('webhooktest')->insert([
                    'details' => $subscription->id,
//                    'type' => "customer.subscription.deleted",
                ]);

                Subscription::where('st_sub_id',$subscription->id)
                ->update([
                    'st_end_at'=> isset($subscription->current_period_end) ? Carbon::createFromTimestamp($subscription->current_period_end) : null,
                    'st_cus_id'=> $subscription->customer,
                    'st_sub_id'=> $subscription->id,
                    'st_total_price'=> $subscription->plan->product,
                ]);

            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                DB::table('webhooktest')->insert([
                    'details' => $subscription->id,
                ]);
                $order = Subscription::where('st_sub_id',$subscription->id)->update([
                        'unsubscribed_at' => now(),
                        'unsubscribe_event_id' => 4,
                        'st_payment_status' => 'customer.subscription.deleted',
                    ]);

            case 'customer.subscription.paused':
                $subscription = $event->data->object;
                Subscription::where('st_sub_id',$subscription->id)
                    ->update([
                        'unsubscribed_at'=>now(),
                        'unsubscribe_event_id'=> 2,
                        'st_payment_status'=> 'customer.subscription.trial_will_end',
                    ]);

                DB::table('webhooktest')->insert([
                    'details' => $subscription,
                ]);

            case 'customer.subscription.updated':
                $subscription = $event->data->object;
                Subscription::where('st_sub_id',$subscription->id)
                    ->update([
                        'st_end_at'=> isset($subscription->current_period_end) ? Carbon::createFromTimestamp($subscription->current_period_end) : null,
                        'st_payment_status'=> 'customer.subscription.updated',
                    ]);

            case 'customer.subscription.pending_update_applied':
                $subscription = $event->data->object;
                 DB::table('webhooktest')->insert([
                    'details' => $subscription,
//                    'type' => "customer.subscription.pending_update_applied",
                ]);

            case 'customer.subscription.pending_update_expired':
                $subscription = $event->data->object;
                 DB::table('webhooktest')->insert([
                    'details' => $subscription,
//                    'type' => "customer.subscription.pending_update_expired",
                ]);

            case 'customer.subscription.resumed':
                $subscription = $event->data->object;
                 DB::table('webhooktest')->insert([
                    'details' => $subscription,
//                    'type' => "customer.subscription.resumed",
                ]);

            case 'customer.subscription.trial_will_end':
                $subscription = $event->data->object;
                 DB::table('webhooktest')->insert([
                    'details' => $subscription,
//                    'type' => "customer.subscription.trial_will_end",
                ]);
            default:
                echo 'Received unknown event type ' . $event->type;
        }


        http_response_code(200);
    }

// Define methods to handle different event types
    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Implement your logic to handle a successful payment intent
    }

    private function handlePaymentMethodAttached($paymentMethod)
    {
        // Implement your logic to handle the attachment of a payment method
    }


}

<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Workspace;
use App\Services\WorkspaceServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubscriptionPaymentStripeController extends Controller
{

    public function checkout(Request $request, $id, $plan_id)
    {


        try {

            $plan = Plan::find($plan_id);

            $workspace = Workspace::find($id);
            $order = Subscription::where('workspace_id', '=', $workspace->id)
                ->whereNull('unsubscribed_at')
               ;


            if (!$plan) {
                return returnResponseJson([
                    "message" => "doesn't have that plan please choose other from plans",
                ], Response::HTTP_NOT_FOUND);
            }


//            if ($order->count() > 0) {
//                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
//
//                $sessionId = $order->where('st_payment_status','paid')->first()->st_session_id ;
//
//                $session = $stripe->checkout->sessions->retrieve($sessionId);
//
//
//                return returnWarningsResponse([
//                    "message" => "you can't repay the plan you already paid",
//                    "notice" => "choose other plan if you need to upgrade your plan"
//                ]);
//            }

            Stripe::setApiKey(env('STRIPE_SECRET'));

            if ($plan->is_subscription) {

                $lineItems = [[
                    'price' => $plan->st_plan_id,
                    'quantity' => 1,
                ]];

                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'customer_email' => returnUserApi()->email,
                    'line_items' => $lineItems,
                    'mode' => 'subscription',
                    'subscription_data' => [
                        'trial_from_plan' => true,
                    ],

                    'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}&plan=" . $plan_id,
                    'cancel_url' => route('checkout.cancel', [], true),

                ]);
                $this->saveSubscriptionDetails($workspace, $plan, 'subscribe', $session);
            }
//            else {
//                $lineItems = [[
//                    'price_data' => [
//                        'currency' => 'usd',
//                        'product_data' => [
//                            'name' => $plan->name,
//                        ],
//                        'unit_amount' => $plan->price * 100,
//                    ],
//                    'quantity' => 1,
//                ]];
//
//                $session = \Stripe\Checkout\Session::create([
//                    'customer_email' => returnUserApi()->email,
//                    'line_items' => $lineItems,
//                    'mode' => 'payment',
//                    'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}&plan=" . $plan_id,
//                    'cancel_url' => route('checkout.cancel', [], true),
//                ]);
//
//                $this->saveSubscriptionDetails($workspace, $plan, 'purchase', $session);
//            }


            return response()->json([
                'url' => $session->url
            ]);

        } catch (\Exception $e) {

            return returnCatchException($e);
        }

    }

    private function saveSubscriptionDetails($workspace, $plan, $order_type, $session)
    {
        $order = new Subscription();
        $order->st_payment_status = 'unpaid';
        $order->st_total_price = $plan->price;
        $order->st_session_id = $session->id;
        $order->workspace_id = $workspace->id;
        $order->user_id = returnUserApi()->id;
        $order->save();
    }


    public function success(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $sessionId = $request->get('session_id');
            $planId = $request->get('plan');
            $plan = Plan::find($planId);
            $session = $stripe->checkout->sessions->retrieve($sessionId);
            $order = Subscription::where('st_session_id', $session->id)->first();


            if (!$session) {
                throw new NotFoundHttpException();
            }

            // Get the subscription ID from the session
            $subscriptionId = $session->subscription;
            if ($subscriptionId) {
                $subscription = \Stripe\Subscription::retrieve($subscriptionId);
                $currentPeriodEnd = $subscription->current_period_end;
            }

            // Create subscriber record
            // update order status
            if ($order->st_payment_status === 'unpaid') {
                $order->st_end_at = isset($currentPeriodEnd) ? Carbon::createFromTimestamp($currentPeriodEnd) : null;
                $order->st_cus_id = $session->customer;
                $order->st_sub_id = $session->subscription;
                $order->st_payment_method = $session->payment_method_types[0];
                $order->st_payment_status = $session->payment_status;
                $order->save();
            }


            //update in workspace table
            $workspace = Workspace::where('id', '=', $order->workspace_id)->first();
            $countApp = $plan->number_app_building + $workspace->count_app_building;
            $workspace->count_app_building = $countApp;
            $workspace->plan_id = $planId;
            $workspace->save();


            if (!$order) {
                $user = returnUserApi();
                $user->plan_id = null;
                $user->save();
                throw new NotFoundHttpException();
            }



            return redirect()->away('http://127.0.0.1:3000/plans/payment/success');

        } catch (\Exception $e) {
            return returnCatchException($e);
        }
    }


    public function cancel()
    {
        return redirect()->away('http://localhost:3000/payment/cancellation');
    }


    public function unsubscription(Request $request, $id, $plan_id)
    {
        // Set your Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $workspace = Workspace::find($id);
            $order = $workspace->subscriptions->first();

            $subscription = \Stripe\Subscription::retrieve($order->st_sub_id);
            $canceledSubscription = $subscription->cancel();

//            if ($canceledSubscription) {
//                $workspace->status = "unsubscription";
//                $workspace->save();
//            }

//            $canceledSubscriptionId = $canceledSubscription->id;
//
//            return returnResponseJson(['canceledSubscription' => $canceledSubscription], '200');
            // Handle the canceled subscription as needed
        } catch (\Exception $e) {
            return returnCatchException($e);
            // Handle any errors or exceptions
        }


    }
}

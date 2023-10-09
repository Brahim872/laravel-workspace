<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Workspace;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Subscription;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentStripeController extends Controller
{

    public function checkout(Request $request, $id, $plan_id)
    {


        try {

            $plan = Plan::find($plan_id);

            $workspace = Workspace::find($id);
            $order = Order::where('workspace_id', '=', $workspace->id)
                ->where('status', '=', 'paid')
                ->join('payments', 'payments.order_id', '=', 'orders.id')
                ->count();


            if (!$plan) {
                return returnResponseJson([
                    "message" => "doesn't have that plan please choose other from plans",
                ], Response::HTTP_NOT_FOUND);
            }


            if ($order > 0) {
                returnWarningsResponse([
                    "message" => "you can't repay the plan you already paid",
                    "notice" => "choose other plan if you need to upgrade your plan"
                ]);
            }

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

            } else {
                $lineItems = [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $plan->name,
                        ],
                        'unit_amount' => $plan->price * 100,
                    ],
                    'quantity' => 1,
                ]];

                $session = \Stripe\Checkout\Session::create([
                    'customer_email' => returnUserApi()->email,
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}&plan=" . $plan_id,
                    'cancel_url' => route('checkout.cancel', [], true),
                ]);

                $this->saveSubscriptionDetails($workspace, $plan, 'purchase', $session);
            }


            return response()->json([
                'url' => $session->url
            ]);

        } catch (\Throwable $e) {

            return returnCatchException($e);
        }

    }

    private function saveSubscriptionDetails($workspace, $plan, $order_type, $session)
    {
        $order = new Order();
        $order->status = 'unpaid';
        $order->total_price = $plan->price;
        $order->order_type = Order::TYPEPLAN[$order_type];
        $order->session_id = $session->id;
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
            $order = Order::where('session_id', $session->id)->first();


            if (!$session) {
                throw new NotFoundHttpException();
            }

            // Get the subscription ID from the session
            $subscriptionId = $session->subscription;
            if ($subscriptionId) {
                $subscription = \Stripe\Subscription::retrieve($subscriptionId);
                $currentPeriodEnd = $subscription->current_period_end;
            }

            // Create Payment record
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->st_cus_id = $session->customer;
            $payment->st_sub_id = $session->subscription;
            $payment->st_payment_intent_id = $session->payment_intent;
            $payment->st_payment_method = $session->payment_method_types[0];
            $payment->st_payment_status = $session->payment_status;
            $payment->date = $session->created;
            $payment->save();


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


            // update order status
            if ($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->date_end = isset($currentPeriodEnd) ? Carbon::createFromTimestamp($currentPeriodEnd) : null;
                $order->save();
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
            $order = Order::where('workspace_id', '=', $id)
                ->where('user_id', '=', returnUserApi()->id)
                ->where('status', '=', 'paid')
                ->where('order_type', '=', 'subscribe')
                ->join('payments', 'payments.order_id', 'orders.id')
                ->first();


            $subscription = Subscription::retrieve($order->st_sub_id);
            $canceledSubscription = $subscription->cancel();

            if ($canceledSubscription){
                $workspace = Workspace::where('id', '=', $order->workspace_id)->first();
                $workspace->deactivated_at = now();
                $workspace->save();
            }
//            $canceledSubscriptionId = $canceledSubscription->id;

            return returnResponseJson(['canceledSubscription' => $canceledSubscription], '200');
            // Handle the canceled subscription as needed
        } catch (\Throwable $e) {
            return returnCatchException($e);
            // Handle any errors or exceptions
        }
    }


}

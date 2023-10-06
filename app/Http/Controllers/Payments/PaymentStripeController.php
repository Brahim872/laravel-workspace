<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Subscription;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentStripeController extends Controller
{


    public function checkout(Request $request, $id, $plan_id)
    {



//// Set your Stripe API key
//        Stripe::setApiKey(env('STRIPE_SECRET'));
//
//// Retrieve the Stripe Customer ID from your database
//        $stripeCustomerId = 'cs_test_a1FomxMQpJrBq08avSmg54u4TcE2WaOjcSboT6RK8S982umc78mKJ3VTdl';
//
//// Retrieve subscriptions for the customer
//        $subscriptions = Subscription::all([
//            'customer' => $stripeCustomerId,
//        ]);
//
//// Loop through subscriptions (usually a user will have only one subscription)
//        foreach ($subscriptions as $subscription) {
//            $subscriptionStatus = $subscription->status;
//            // You can also get other subscription details like plan, current_period_end, etc.
//            // $planId = $subscription->items->data[0]->price->id;
//            // $currentPeriodEnd = $subscription->current_period_end;
//        }
//
//
//        dd();

        try {

            $plan = Plan::find($plan_id);




            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            if (!$plan) {
                return returnResponseJson([
                    "message" => "doesn't have that plan please choose other from plans",
                ], Response::HTTP_NOT_FOUND);
            }


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

                'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => route('checkout.cancel', [], true),

            ]);

//            dd($session);

            $this->saveSubscriptionDetails($id, $plan,$plan_id, $session);

            return response()->json([
                'url' => $session->url
            ]);
        } catch (\Throwable $e) {

          return returnCatchException($e);
        }

    }

    private function saveSubscriptionDetails($id, $plan,$plan_id, $session)
    {
        $workspace = Workspace::find($id);
        $workspace->update(['plan_id' => $plan_id]);

        $order = new Order();
        $order->status = 'unpaid';
        $order->total_price = $plan->price;
        $order->order_type = Order::TYPEPLAN['plan'];
        $order->session_id = $session->id;
        $order->workspace_id = $id;
        $order->details = json_encode(["expires_at"=>$session->expires_at,"client_id"=>$session->customer]);
        $order->user_id = returnUserApi()->id;
        $order->save();
    }


    public function success(Request $request)
    {


        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $sessionId = $request->get('session_id');
        try {

            $session = $stripe->checkout->sessions->retrieve($sessionId);
            $order = Order::where('session_id', $session->id)->first();

            if (!$session) {
                throw new NotFoundHttpException();
            }


            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->st_cus_id = $session->customer;
            $payment->st_sub_id = $session->subscription;
            $payment->st_payment_intent_id = $session->payment_intent;
            $payment->st_payment_method = $session->payment_method_types[0];
            $payment->st_payment_status = $session->payment_status;
            $payment->date = $session->created;
            $payment->save();

            $workspace = Workspace::where('id', '=', $order->workspace_id)->first();
            if (!$workspace->payment_id) {
                $workspace->payment_id = $payment->id;
                $workspace->save();
            }


            if (!$order) {
                $user = returnUserApi();
                $user->plan_id = null;
                $user->save();

                throw new NotFoundHttpException();
            }

            if ($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->payment_id = $payment->id;
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
}

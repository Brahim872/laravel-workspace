<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentStripeController extends Controller
{


    public function checkout(Request $request, $id, $plan_id)
    {

        try {

            $plan = Plan::find($plan_id);

            \Stripe\Stripe::setApiKey(config('app.stripe_secret'));


            $lineItems = [[
                'price' => $plan->st_plan_id,
                'quantity' => 1,
            ]];


            $session = \Stripe\Checkout\Session::create([

                'payment_method_types' => ['card'],
                // 'phone_number_collection' => [
                //     'enabled' => true,
                // ],
                'customer_email' => returnUserApi()->email,
                'line_items' => $lineItems,
                'mode' => 'subscription',
                'subscription_data' => [
                    'trial_from_plan' => true,
                ],
                'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}",
                'cancel_url' => route('checkout.cancel', [], true),
            ]);


            $workspace = Workspace::find($id);
            $workspace->update(['plan_id' => $plan_id]);

            $order = new Order();
            $order->status = 'unpaid';
            $order->total_price = $plan->price;
            $order->order_type = Order::TYPEPLAN['plan'];
            $order->session_id = $session->id;
            $order->workspace_id = $id;
            $order->user_id = returnUserApi()->id;
            $order->save();

            return response()->json([
                'url' => $session->url
            ]);
        } catch (\Throwable $e) {

            return returnResponseJson([
                "message" => $e->getMessage(),
                "Line" => $e->getLine()
            ], 500);
        }

    }

    public function success(Request $request)
    {


        $stripe = new \Stripe\StripeClient(config('app.stripe_secret'));
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
            throw new NotFoundHttpException();
        }
    }

    public function cancel()
    {
        return redirect()->away('http://localhost:3000/payment/cancellation');
    }
}

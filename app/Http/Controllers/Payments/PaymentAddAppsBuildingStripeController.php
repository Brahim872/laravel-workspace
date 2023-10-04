<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\PlanPlusApp;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentAddAppsBuildingStripeController extends Controller
{

    public function checkout(Request $request, $id, $plan_id)
    {

        try {

            $plan = PlanPlusApp::find($plan_id);
            \Stripe\Stripe::setApiKey(config('app.stripe_secret'));

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
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.add.app.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}&plan=" . $plan_id,
                'cancel_url' => route('checkout.add.app.cancel', [], true),
            ]);


//            $plan->users()->detach();
//            $plan->users()->attach(returnUserApi()->id, [
//                'type_user' => 0 // = admin
//            ]);

            $order = new Order();
            $order->status = 'unpaid';
            $order->total_price = $plan->price;
            $order->order_type = Order::TYPEPLAN['add_apps'];
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
        $planId = $request->get('plan');
        try {

            $session = $stripe->checkout->sessions->retrieve($sessionId);




            $order = Order::where('session_id', $session->id)->first();
            $planPlusApp = PlanPlusApp::find($planId);

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


            $countApp = $planPlusApp->number_app_building + $workspace->count_app_building;


            $workspace->count_app_building = $countApp;
            $workspace->save();

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
//            return redirect()->away('http://127.0.0.1:3000/plans/payment/success');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function cancel()
    {
        return redirect()->away('http://localhost:3000/payment/cancellation');
    }
}

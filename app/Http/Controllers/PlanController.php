<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::where('is_subscription', '=', 1)->get();
        return returnResponseJson(['plans' => $plans], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $plan = $stripe->products->create([
                'name' => $request->name,
                'default_price_data' => [
                    'unit_amount' => $request->price * 100,
                    'currency' => 'usd',
                    'recurring' => [
                        'interval' => $request->interval,
//                        'trial_period_days' => $request->trial_period_days,
                    ],
                ],
                'expand' => ['default_price'],
            ]);


            $newPlan = new Plan();
            if ($plan && $plan->active === true) {
                $newPlan->st_plan_id = $plan->default_price->id;
                $newPlan->name = $request->name;
                $newPlan->price = $request->price;
                $newPlan->interval = $request->interval;
                $newPlan->trial_period_days = $request->trial_period_days;
                $newPlan->lookup_key = str()->snake($request->name);
                $newPlan->save();
            }

            return returnResponseJson([
                'plan' => $newPlan
            ], Response::HTTP_OK);

        } catch (\Exception $e) {

            returnCatchException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        try {

            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $price = $stripe->prices->retrieve(
                $plan->st_plan_id,
                []);

            if ($request->name || $request->description) {
                $product = $stripe->products->update($price->product, [
                    'name' => $request->name,
                    'description' => $request->description,
//                    'images' => [ $request->images ],
                ]);
            }

            $newPlan = new Plan();

            if (isset($product) && $product->active === true) {
                $dta = [
                    'name' => $product->name,
//                    'images' => $product->images,
                    'description' => $product->description,
                    'number_app_building' => $request->number_app_building,
                ];


            }else{
                $dta = [
                    'number_app_building' => $request->number_app_building,
                ];

            }
            $updatPlan = $newPlan->find($plan->id);
            $newPlan = $updatPlan->update($dta);
            return returnResponseJson([
                'plan' => $updatPlan
            ], Response::HTTP_OK);

        } catch (\Exception $e) {

            returnCatchException($e);
        }

    }


    /**
     * Update the specified resource in storage.
     */
    public function priceUpdate(Request $request, Plan $plan)
    {
        try {

            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $price = $stripe->prices->retrieve(
                $plan->st_plan_id,
                []);

            $product = $stripe->products->retrieve($price->product);

            // Create a new price object
            $_price = $stripe->prices->create([
                'unit_amount' => $request->price,
                'currency' => 'usd', // Change to the appropriate currency code
                'product' => $price->product,
                'recurring' => [
                    'interval' => $request->interval, // Change to the appropriate interval (day, week, month, year, etc.)
                ],
            ]);
            $product->default_price = $_price->id;
            $product->save();
            $newPlan = new Plan();

            if ($_price && $_price->active === true) {
                $dta = [
                    'st_plan_id' => $_price->id,
                    'price' => $_price->unit_amount,
                    'interval' => $_price->recurring->interval ?? 0,
                    'trial_period_days' => $_price->recurring->trial_period_days ?? 0,
                ];
                $newPlan->find($plan->id)->update($dta);

                return [$newPlan, $dta];
            }

            return returnResponseJson([
                'plan' => $plan
            ], Response::HTTP_OK);

        } catch (\Exception $e) {

            returnCatchException($e);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        //
    }
}

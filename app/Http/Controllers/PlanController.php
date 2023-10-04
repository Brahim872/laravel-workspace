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
        $plans = Plan::all();
        return returnResponseJson(['plans'=>$plans],Response::HTTP_OK);
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
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $plan = \Stripe\Price::create([
                'unit_amount' => $request->price * 100,
                'currency' => 'usd',
                'recurring' => [
                    'interval' => $request->interval,
                    'trial_period_days' => $request->trial_period_days,
                ],
                'lookup_key' => str()->snake($request->name),
                'transfer_lookup_key' => true,
                'product_data' => [
                    'name' => $request->name,
                ],
            ]);

            $newPlan = new Plan();
            if ($plan && $plan->active === true) {
                $newPlan->st_plan_id = $plan->id;
                $newPlan->name = $request->name;
                $newPlan->price = $request->price;
                $newPlan->interval = $request->interval;
                $newPlan->trial_period_days = $request->trial_period_days;
                $newPlan->lookup_key = str()->snake($request->name);
                $newPlan->save();
            }

            return returnResponseJson([
                'plan'=>$newPlan
            ],Response::HTTP_OK);

        }catch (\Exception $e){
            return returnResponseJson([
                'message'=>$e->getMessage(),
                'file'=>$e->getFile()." / ".$e->getLine(),
            ],Response::HTTP_BAD_REQUEST);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        //
    }
}

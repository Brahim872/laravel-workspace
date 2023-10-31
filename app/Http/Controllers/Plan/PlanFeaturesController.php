<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Plan\PlanFeaturesResource;
use App\Models\PlanFeatures;
use App\Models\Setting\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PlanFeaturesController extends Controller
{

    public function rules()
    {
        return [
            'plan_id' => 'required',
            'key' => 'required|unique:plan_features',
            'value' => 'required',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plansFeatures = PlanFeatures::get();
        return returnResponseJson(['plans_features' => $plansFeatures], Response::HTTP_OK);
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

            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails()) {
                return returnValidatorFails($validator);
            }

            $plan_features = PlanFeatures::create([
                "plan_id" => $request->plan_id,
                "key" => $request->key,
                "value" => $request->value,
                "type" => $request->type ?? 'text',
                "description" => $request->description ?? null,
            ]);

            return returnResponseJson([
                'permissionPlan' => new PlanFeaturesResource($plan_features)
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return returnCatchException($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PlanFeatures $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlanFeatures $plan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param PlanFeatures $planFeatures
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        try {

            $attributesPlan = $request->all();


            $model = new PlanFeatures();

            foreach ($attributesPlan as $planId => $attributes) {
                foreach ($attributes as $key => $value) {
                    if ($key !== '_token' && $key !== 'save') {
                        if ($request->hasFile($key)) {
                            Storage::disk('public')->delete('img/' . $value);
                            $value = $request->file($key)->store($model->path, ['disk' => 'public']);
                        }
                        $setting = $model->where('plan_id', $planId)->where('key', $key)->first();
                        if ($setting) {
                            $setting->value = $value;
                            $setting->update();
                        } else {
                            $setting = new PlanFeatures();
                            $setting->fill([
                                'plan_id' => $planId,
                                'key' => $key,
                                'value' => $value
                            ]);
                            $setting->save();
                        }
                    }
                }
            }

            return returnResponseJson([
                'permissionPlan' => new PlanFeaturesResource($plan_features)
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return returnCatchException($e);
        }

    }


    /**
     * Update the specified resource in storage.
     */
    public function priceUpdate(Request $request, PlanFeatures $plan)
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
            $newPlanFeatures = new PlanFeatures();

            if ($_price && $_price->active === true) {
                $dta = [
                    'st_plan_id' => $_price->id,
                    'price' => $_price->unit_amount,
                    'interval' => $_price->recurring->interval ?? 0,
                    'trial_period_days' => $_price->recurring->trial_period_days ?? 0,
                ];
                $newPlanFeatures->find($plan->id)->update($dta);

                return [$newPlanFeatures, $dta];
            }

            return returnResponseJson([
                'plan' => $plan
            ], Response::HTTP_OK);

        } catch (\Exception $e) {

            return returnCatchException($e);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlanFeatures $plan)
    {
        //
    }
}

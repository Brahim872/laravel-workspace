<?php
namespace App\Http\Controllers\Payments;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookStripeController extends Controller
{

    public function webhook(Request $request){
        Log::debug('webhook event',$request->all());
    }

}

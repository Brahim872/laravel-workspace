<?php

namespace App\Http\Controllers\Payments;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookStripeController extends Controller
{
    public function webhook(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $event = null;

        if ($payload !== false) {
            $jsonPayload = json_decode($payload, true);
            if ($jsonPayload !== null) {
                try {
                    $event = \Stripe\Event::constructFrom($jsonPayload);
                } catch (\UnexpectedValueException $e) {
                    // Invalid payload
                    echo '⚠️  Webhook error while parsing basic request.';
                    http_response_code(400);
                    exit();
                }
            } else {
                // Handle JSON decoding failure
                echo '⚠️  JSON decoding error';
                http_response_code(400);
                exit();
            }
        } else {
            // Handle file_get_contents() failure
            echo '⚠️  Failed to get webhook data';
            http_response_code(400);
            exit();
        }

        if ($endpoint_secret) {
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload, $sig_header, $endpoint_secret
                );
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                error_log('Webhook error while validating signature: ' . $e->getMessage());
                http_response_code(400);
                exit();
            }
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Call a method to handle the successful payment intent
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Call a method to handle the successful attachment of a PaymentMethod
                $this->handlePaymentMethodAttached($paymentMethod);
                break;
            default:
                // Unexpected event type
                error_log('Received unknown event type: ' . $event->type);
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

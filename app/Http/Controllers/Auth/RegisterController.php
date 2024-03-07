<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\amount;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class RegisterController extends Controller
{
    //

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    public function register(Request $request)
    {
        // $this->validator($request->all())->validate();
        $validator = Validator::make($request->all(), [
            // Add validation rules for name, email, password, etc.
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Validate captcha
        if ($request->captcha != session('captcha_answer')) {
            throw ValidationException::withMessages(['captcha' => 'Captcha validation failed.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        // Auth::login($user);

        return redirect()->route('payment.charge', $user);
    }
   

    public function showPaymentCharge($user){
        return view('payment-stuff', compact('user'));
    }

    public function paymentProcess(Request $request, User $user)
    {
        $this->middleware('guest');
        try{
                    // Set your Stripe API key.
            \Stripe\Stripe::setApiKey(env('STRIPE_KEY'));

            // Get the payment amount and email address from the form.
            $amount = $request->input('amount') * 100;
            $email = $request->input('email');

            // Create a new Stripe customer.
            $customer = \Stripe\Customer::create([
                'email' => $email,
                'source' => $request->input('stripeToken'),
            ]);

            $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));

                $stripe->paymentIntents->create([
                'amount' => $amount,
                'currency' => 'gbp',
                'payment_method' => 'pm_card_visa',
                ]);

                // Auth::login($user);

                $amount = amount::create([
                    'amount' => $amount,
                    'user_id' => $user->id,
                ]);
                $amount->save();

            
            // Display a success message to the user.
            return view('success');   
            
        } catch (ApiErrorException $e) {
            $user->delete();
            return back()->withErrors(['payment' => $e->getMessage()]);
        }
         
    }

    public function processPayment(Request $request, User $user)
{
    dd($request->all() );
    try {
        // Retrieve payment method ID from request
        $paymentMethodId = $request->payment_method_id;
        $paymentIntentId = $request->payment_intent_id;
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Confirm the PaymentIntent with the payment method
        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

        $paymentIntent->confirm([
            'payment_method' => $paymentMethodId,
        ]);

        if ($paymentIntent->status === 'succeeded') {
            // Payment successful, log in user or perform other actions
            Auth::login($user);
            return redirect('/register'); // Replace with your desired success URL
        } else {
            throw new Exception('Payment failed.');
        }
    } catch (Exception $e) {
        // Handle payment failure, potentially delete user and redirect back
        $user->delete();
        return back()->withErrors(['payment' => 'Payment failed. ' . $e->getMessage()]);
    }
}



public function paymentSuccess(Request $request)
    {
        try {
            // Handle successful payment scenario here
            return view('success'); // You can return a success view or perform any other actions
        } catch (Exception $e) {
            // Handle exception if any
            return back()->withErrors(['payment' => 'An error occurred while processing the payment.']);
        }
    }

}

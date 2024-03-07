@extends('layouts.app')
@section('content')


<div id="payment-form1" style="width:500px; align-items:center">

    <form id="payment-form" action="{{ route('charge',$user) }}" method="POST" >
        <!-- {{ csrf_field() }} -->
        @csrf
        <div class="input-group">
            <label for="amount">
                Amount (in cents):
            </label>
            <input type="text" name="amount" id="amount" value="50" disabled>
        </div>

        <div class="input-group">
            <label for="email">
                Email:
            </label>
            <input type="text" name="email" id="email">
        </div>
        <br>

        <label for="card-element">
            Credit or debit card
        </label>
        <div id="card-element"></div>

        <br>
        <br>

        <div id="card-errors" role="alert"></div>
        <br>
        <br>

        <button type="submit">Submit Payment</button>
    </form>

</div>

<style>
    .input-group {
        display: grid;
        grid-template-columns: 150px 1fr; /* Adjust widths as needed */
        margin-bottom: 10px; /* Add spacing for better readability */
    }

    .input-group label {
        text-align: right; /* Right-align labels */
    }
</style>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>

<script>
    var stripe = Stripe('{{ env('STRIPE_SECRET') }}');
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    var style = {
        base: {
            // Add your base input styles here
        }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', { style: style });

    // Add an instance of the card Element into the `card-element` div.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function (event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        console.log('form submitted');

        stripe.createToken(card).then(function (result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Token is created, submit the form with token to your server.
                stripeTokenHandler(result.token, form); // Pass the form as an argument
            }
        });
    });

    // Submit the form with the token ID.
    function stripeTokenHandler(token, form) {
        // Insert the token ID into the form so it gets submitted to the server
        console.log(token,form);
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }
</script>

@endsection

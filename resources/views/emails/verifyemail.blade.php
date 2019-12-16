@component('mail::emailer')
<h1>Email Verification</h1>

<p>Thank you for joining with us, please click the button below to verify your email.</p>

@component('mail::button', ['url' => env('FRONTEND_URL') . '/verify/' . $verify ])
Verify Email
@endcomponent

@endcomponent
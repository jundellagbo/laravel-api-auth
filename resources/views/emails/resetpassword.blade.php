@component('mail::emailer')
<h1>Reset Password</h1>

<p>Welcome to Easycity, please click the button below to reset your password.</p>

@component('mail::button', ['url' => env('FRONTEND_URL') . '/reset-password?token=' . $token["token"]])
Reset Password
@endcomponent

@endcomponent
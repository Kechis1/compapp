@component('mail::message')
{{__('email.forgot_password_body', ['url' => $action])}}
@endcomponent
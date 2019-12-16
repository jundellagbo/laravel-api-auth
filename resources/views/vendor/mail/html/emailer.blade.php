@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => env('FRONTEND_URL')])
            <img src="https://www.easycity.sg/wp-content/uploads/2018/02/website-logo.png" alt="Easycity Logo" />
        @endcomponent
    @endslot

    <table>
        <tr>
            <td>
                {{-- Body --}}
                {{ $slot }}
            </td>
        </tr>
        <tr>
            <td>
                <div style="margin-top: 30px;">
                    <p style="text-align: center; margin-bottom: 0;">Need help? Ask at <a href="mailto:support@easycity.com" class="text-link">support@easycity.com</a> or visit our <a href="{{ env('FRONTEND_URL') }}/" class="text-link" target="_blank">Help Center.</a></p>
                </div>
            </td>
        </tr>
    </table>

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <table>
                <tr>
                    <td class="text-center">
                        <a href="https://facebook.com" target="_blank" style="margin-right: 15px;"><img src="https://dl.dropbox.com/s/bbxj86whfnoy6w5/logo-facebook.png" alt="Facebook" /></a>
                        <a href="https://twitter.com" target="_blank"><img src="https://dl.dropbox.com/s/0e4epls3dtnniwv/image%202.png" alt="Twitter" /></a>
                        <a href="https://linkedin.com" target="_blank" style="margin-left: 15px;"><img src="https://dl.dropbox.com/s/rg6lod4hy3962un/logo-linkedin.png" alt="Linkedin" /></a>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <div class="margin-top-10">
                            <span>© Easycity {{ date('Y') }}. @lang('All rights reserved.')</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <div class="margin-top-10">
                            <a href="{{ env('FRONTEND_URL') }}/terms-and-conditions/" target="_blank" style="padding-right: 10px; text-decoration: underline; color: #939393;">Terms and Conditions</a>
                            <a href="{{ env('FRONTEND_URL') }}/privacy-policy/"  target="_blank" style="text-decoration: underline;color: #939393;">Privacy Policy</a>
                            <a href="{{ env('FRONTEND_URL') }}/"  target="_blank"  style="padding-left: 10px; text-decoration: underline; color: #939393;">View in browser</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <div class="margin-top-10">
                            <a href="{{ env('FRONTEND_URL') }}/terms-and-conditions/" target="_blank" style="padding-right: 3px; text-decoration: underline; color: #939393;">Unsubscribe</a>
                            <span style="color: #939393;">or</span>
                            <a href="{{ env('FRONTEND_URL') }}/"  target="_blank" style="padding-left: 3px; text-decoration: underline; color: #939393;">Manage your preferences</a>
                        </div>
                    </td>
                </tr>
            </table>
        @endcomponent
    @endslot
@endcomponent
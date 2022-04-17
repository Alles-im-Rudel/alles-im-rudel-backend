@component('mail::layout')
    {{-- Body --}}
    {{ $slot }}

    {{-- Footer --}}
    @slot('footer')
        <tr>
            <td>
                <table class="footer" align="center" width="500" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td class="content-cell" align="center">
                            <a href="{{ env('APP_FRONTEND_URL') }}" style="display: inline-block; margin-bottom: 8px;">
                                <img
                                    src="{{ env('APP_FRONTEND_URL') . '/assets/logos/logo-white-slim.png' }}"
                                    class="logo"
                                    alt="Alles im Rudel Logo"
                                >
                            </a>

                            <br>
                            © {{ date('Y') }} Alles im Rudel e.V.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endslot
@endcomponent

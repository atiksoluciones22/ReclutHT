@php
    $OfferLogo = env('FTP_URL', $setting->url) . 'imagenes/' . $setting->logo_oferta;
@endphp

<div>
    <div class="container recruitment-list">
        @if ($byUser)
            @include('includes.application-list-by-user')
        @else
            @include('includes.application-list')
        @endif
    </div>
    <livewire:postulation-show />
</div>

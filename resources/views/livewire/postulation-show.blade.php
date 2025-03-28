@push('head')
    <link rel="stylesheet" href="{{ asset('css/postulation-show.css') }}">
@endpush

<div>
    @if ($postulation)
        <div class="popup">
            <div class="postulation-show">
                <button class="button-close" wire:click="close">
                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <h4 class="title">{{ $postulation->NOM }}</h4>

                <ul class="informations">
                    <div class="information">
                        <span>Detalles de la vacante:</span>
                        <p>
                            {{ utf8_decode($postulation->DESPUB) }}
                        </p>
                    </div>
                </ul>

                @if (auth()->check())
                    @if (in_array($postulation->COD, auth()->user()->applications()) && !in_array($postulation->COD, $rejectedUserPostulationOffers))
                        <form action="{{ route('postulations.rejectApply', ['cod' => $postulation->COD]) }}" method="post">
                            @csrf @method('delete')
                            <button type="submit" class="button-link w-100 rejectApply">Desestimar</button>
                        </form>
                    @else
                        <form action="{{ route('postulations.saveApply') }}" method="post">
                            @csrf
                            <input type="hidden" name="cod" value="{{ $postulation->COD }}">
                            <button type="submit" class="button-link w-100">Postularme</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('postulations.apply', ['cod' => $postulation->COD]) }}" class="button-link">Postularme</a>
                @endif
            </div>
        </div>
    @endif
</div>

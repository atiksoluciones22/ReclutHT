<div class="postulations">
    @foreach ($PostulationOffers as $PostulationOffer)
        <div class="recruitment">
            <img src="{{ $OfferLogo }}" alt="">
            <h4>{{ get_array_value($PostulationOffer, 'NOM') }}</h4>
            <div class="buttons">
                <a href="javascript:void(0)" class="button-link"
                    wire:click="show({{ get_array_value($PostulationOffer, 'COD') }})">
                    Ver descripción de la oferta
                </a>

                @if (auth()->check())
                    @if (in_array(get_array_value($PostulationOffer, 'COD'), auth()->user()->applications()) &&
                            !in_array(get_array_value($PostulationOffer, 'COD'), $rejectedUserPostulationOffers))
                        <form
                            action="{{ route('postulations.rejectApply', ['cod' => get_array_value($PostulationOffer, 'COD')]) }}"
                            method="post">
                            @csrf @method('delete')
                            <button type="submit" class="button-link w-100 rejectApply">Desestimar</button>
                        </form>
                    @else
                        <form action="{{ route('postulations.saveApply') }}" method="post">
                            @csrf
                            <input type="hidden" name="cod" value="{{ get_array_value($PostulationOffer, 'COD') }}">
                            <button type="submit" class="button-link w-100">Postularme</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('postulations.apply', ['cod' => get_array_value($PostulationOffer, 'COD')]) }}"
                        class="button-link">Postularme</a>
                @endif
            </div>
        </div>
    @endforeach

    @if (!auth()->check())
        <div class="recruitment">
            <img src="{{ $OfferLogo }}" alt="">
            <h4>¿No encuentra su oferta de trabajo?</h4>
            <div class="buttons">
                <p>Envíenos sus datos personales y será incluido en nuestra bolsa de trabajo.</p>
                <a href="{{ route('postulations.apply', ['cod' => 0]) }}"
                    class="button-link">Inscriberme</a>
            </div>
        </div>
    @endif
</div>

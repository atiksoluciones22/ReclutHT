@if (count($PostulationOffers) > 0)
<div class="data-table">
    <div class="data-table-header">
        <div class="data-table-row">
            <div class="data-table-cell">Puesto</div>
            <div class="data-table-cell">Estado</div>
            <div class="data-table-cell">Pruebas pendiente</div>
            <div class="data-table-cell">Acciones</div>
        </div>
    </div>
    @foreach ($PostulationOffers as $PostulationOffer)
        <div class="data-table-body">
            <div class="data-table-row">
                <div class="data-table-cell">{{ get_array_value($PostulationOffer, 'NOM') }}</div>

                <div class="data-table-cell">{{ get_array_value(APPLY_STATUS, get_array_value($PostulationOffer, 'SITUAC')) }}</div>

                <div class="data-table-cell list">
                    @php
                        $AccreditationOfferRequirement = get_array_value($PostulationOffer, 'AccreditationOfferRequirement');
                    @endphp

                    @if(count($AccreditationOfferRequirement) > 0)
                        <ul>
                            @foreach($AccreditationOfferRequirement as $data)

                                @php
                                    $key = get_array_value($data, 'LIN') . '-' . get_array_value($data, 'OFERTA');

                                    $alertData  = [
                                        'message' => "Comenzar prueba de " . get_array_value($data, 'NOM'),
                                        'text' => 'Una vez pulses en comenzar, no habrá regreso a esta prueba. Por favor, no la desestimes.',
                                        'key' => $key,
                                        'method' => 'GET',
                                        'action' => true,
                                        'route' => route('panel.exam', [
                                            'offer' => get_array_value($data, 'OFERTA'),
                                            'linea' => get_array_value($data, 'LIN')
                                            ])
                                    ];

                                    if (get_array_value($data, 'TIPO') == 12) {
                                        $examen = get_array_value($data, 'EXAMEN');

                                        $fileMap = [
                                            1 => 'pdf/20240722T174645.034-InstruccionesWonderlic1.pdf',
                                            2 => 'pdf/20240722T174710.691-Instrucciones16PF1.pdf',
                                            3 => 'pdf/InstruccionesDISC1.pdf'
                                        ];

                                        if (array_key_exists($examen, $fileMap)) {
                                            $alertData['file'] = asset($fileMap[$examen]);
                                        }
                                    }

                                    if(in_array(get_array_value($data, 'TIPO'), [3, 6, 2, 7])){
                                        $alertData['message'] = get_array_value($data, 'NOM');
                                        $alertData['text'] = 'Esta prueba se debe realizar junto a una persona encargada o administrativa del puesto.';
                                        $alertData['action'] = false;
                                    }
                                @endphp

                                @include('includes.confirm-alert', $alertData)

                                <button onclick="openSweetAlert('{{ $key }}')" class="button">
                                    <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                                    </svg>
                                    {{ get_array_value($data, 'NOM') }}
                                </button>
                            @endforeach
                        </ul>
                    @else
                        <span>Sin requisitos</span>
                    @endif
                </div>

                <div class="data-table-cell actions actions-flex">
                    <a href="javascript:void(0)" class="button-link w-100" wire:click="show({{ get_array_value($PostulationOffer, 'OFERTA') }})">
                        <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"  viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z">
                            </path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                        </svg>
                    </a>

                    <form action="{{ route('postulations.rejectApply', ['cod' =>  get_array_value($PostulationOffer, 'OFERTA')]) }}" class="w-100" method="post">
                        @csrf @method('delete')
                        <button type="submit" class="button-link rejectApply">Desestimar</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@else
    <p>No tiene postulaciones.</p>
@endif

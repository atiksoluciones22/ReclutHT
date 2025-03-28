<div>
    @if (!auth()->check())
        <h3 class="title-step"><span>Paso 1:</span> Postulación</h3>

        <div wire:key="PostulationForms">
            @foreach ($PostulationForms as $key => $value)
                @if (count($PostulationForms) > 1)
                    <div class="step-number">
                        <span>#{{ $key }}</span>
                        <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                @endif

                <div class="input-x1">
                    <div class="input">
                        <label for="postulationPositionRequested{{ $key }}">Puesto solicitado
                            {{ $key }}:</label>
                        <select name="postulationPositionRequested{{ $key }}"
                            id="postulationPositionRequested{{ $key }}">
                            @foreach($postulationOffers as $postulationOffer)
                                 <option value="{{ $postulationOffer['COD'] }}">{{ $postulationOffer['NOM'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endforeach

            <button wire:click="addForm('PostulationForms', true)" type="button" class="button-link button-plus"
                style="margin-bottom: 20px">
                Agregar puesto
                <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
            </button>
        </div>
    @else
        <h3 class="title-step"><span>Paso 1:</span> Detalles</h3>
    @endif

    <div class="input-x2">
        <div class="input">
            <label for="postulationSalaryPretension">Pretensión salarial RD$:</label>
            <input type="text" name="postulationSalaryPretension" placeholder="Pretensión salarial"
                id="postulationSalaryPretension" data-type="currency" maxlength="15" oninput="limitInput(this)" value="{{ format_money(get_array_value($profile, 'SUELDO')) }}">
        </div>

        <div class="input">
            <label for="postulationWorkRotatingSchedule">¿Trabajaría en horario rotativo?:</label>
            <select name="postulationWorkRotatingSchedule" id="postulationWorkRotatingSchedule">
                <option value="*" selected>Si</option>
                <option value="" {{ get_array_value($profile, 'TRAROT') != '*' ? 'selected' : '' }}>No</option>
            </select>
        </div>
    </div>

    <h3 class="title-step"><span>Paso 2:</span> Otras condiciones</h3>

    <div class="input-x2">
        <div class="input">
            <label for="postulationWorkExterior">¿Puede trabajar en el exterior?:</label>
            <select name="postulationWorkExterior" id="postulationWorkExterior">
                <option value="*" selected>Si</option>
                <option value="" {{ get_array_value($profile, 'TRAEXT') != '*' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="input">
            <label for="postulationWorkInterior">¿Puede trabajar en el interior?:</label>
            <select name="postulationWorkInterior" id="postulationWorkInterior">
                <option value="*" selected>Si</option>
                <option value="" {{ get_array_value($profile, 'TRAINT') != '*' ? 'selected' : '' }}>No</option>
            </select>
        </div>
    </div>

    <div class="input-x1">
        <div class="input">
            <label for="postulationComment">Comentario:</label>
            <textarea name="postulationComment" id="postulationComment" placeholder="Comentario"></textarea>
        </div>
    </div>


    <div class="terms">
        <input type="checkbox" wire:model.live="terms" name="terms" id="terms">
        <label for="terms">
            Acepto la política de privacidad, protección de datos y veracidad de la información. <strong><a href="{{ route('privacy-policy') }}" target="_blank" rel="noopener noreferrer">Ver política</a></strong>
        </label>
    </div>
</div>

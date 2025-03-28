<div>
    <h3 class="title-step"><span>Paso 1:</span> Experiencia profesional</h3>

    <div wire:key="ExperienceForms">
        @foreach ($ExperienceForms as $key => $value)

            @php
                $candidateExperience = [];
                if (auth()->check()) $candidateExperience = get_array_value($profile['candidateExperiences'], $key - 1)
            @endphp

            @if (count($ExperienceForms) > 1)
                <div class="step-number">
                    <span>#{{ $key }}</span>
                    <svg wire:click="removeForm('ExperienceForms', {{ $key }})" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>
                </div>
            @endif

            <div class="input-x3">
                <div class="input">
                    <label for="experienceCompany{{ $key }}">Empresa:</label>
                    <input type="text" name="experienceCompany{{ $key }}" placeholder="Empresa"
                        id="experienceCompany{{ $key }}" value="{{ get_array_value($candidateExperience, 'EMP') }}">
                </div>

                <div class="input">
                    <label for="experienceStartDate{{ $key }}">Fecha de inicio:</label>
                    <input type="date" name="experienceStartDate{{ $key }}"
                        placeholder="Fecha de inicio" id="experienceStartDate{{ $key }}" value="{{ convert_date(get_array_value($candidateExperience, 'FECINI')) }}" min="1900-01-01" max="{{ date('Y-m-d') }}">
                </div>

                <div class="input">
                    <label for="experienceDepartureDate{{ $key }}">Fecha de salida:</label>
                    <input type="date" name="experienceDepartureDate{{ $key }}"
                        placeholder="Fecha de inicio" id="experienceDepartureDate{{ $key }}" value="{{ convert_date(get_array_value($candidateExperience, 'FECFIN')) }}" min="1900-01-01" max="{{ date('Y-m-d') }}">
                </div>

                <div class="input">
                    <label for="experiencePosition{{ $key }}">Puesto:</label>
                    <input type="text" name="experiencePosition{{ $key }}" placeholder="Puesto"
                        id="experiencePosition{{ $key }}" value="{{ get_array_value($candidateExperience, 'PUESTO') }}">
                </div>

                <div class="input">
                    <label for="experienceSalary{{ $key }}">Salario:</label>
                    <input type="text" name="experienceSalary{{ $key }}" maxlength="15" oninput="limitInput(this)" placeholder="Salario"
                        id="experienceSalary{{ $key }}" value="{{ format_money(get_array_value($candidateExperience, 'SUELDO')) }}" data-type="currency">
                </div>

                <div class="input">
                    <label for="experienceResponsibilities{{ $key }}">Responsabilidades:</label>
                    <input type="text" maxlength="100" oninput="limitInput(this)" name="experienceResponsibilities{{ $key }}"
                        placeholder="Responsabilidades" id="experienceResponsibilities{{ $key }}" value="{{ get_array_value($candidateExperience, 'RESPON') }}">
                </div>

                <div class="input">
                    <label for="experienceCountry{{ $key }}">Pais:</label>
                    <select name="experienceCountry{{ $key }}"
                        id="experienceCountry{{ $key }}">
                        @foreach($countries as $country)
                            <option value="{{ $country->COD }}" {{ (get_array_value($candidateExperience, 'PAIS') ?? "DO") == $country->COD ? 'selected' : '' }}>{{ $country->NOM }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input">
                    <label for="experienceCity{{ $key }}">Ciudad:</label>
                    <input type="text" name="experienceCity{{ $key }}" placeholder="Ciudad"
                        id="experienceCity{{ $key }}" maxlength="50" oninput="limitInput(this)" value="{{ get_array_value($candidateExperience, 'CIUDAD') }}">
                </div>

                <div class="input">
                    <label for="experienceWebDomain{{ $key }}">Dirección web:</label>
                    <input type="text" name="experienceWebDomain{{ $key }}"
                        placeholder="Dirección web" maxlength="50" oninput="limitInput(this)" id="experienceWebDomain{{ $key }}" value="{{ get_array_value($candidateExperience, 'WEB') }}">
                </div>
            </div>

            <div class="input-x1">
                <div class="input">
                    <label for="experienceReasonDeparture{{ $key }}">Razón de la salida:</label>
                    <textarea name="experienceReasonDeparture{{ $key }}" id="experienceReasonDeparture{{ $key }}"
                        placeholder="Razón de la salida">{{ get_array_value($candidateExperience, 'MOTSAL') }}</textarea>
                </div>
            </div>
        @endforeach

        <button wire:click="addForm('ExperienceForms', true)" type="button" class="button-link button-plus">
            Agregar experiencia
            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
        </button>
    </div>

    <h3 class="title-step"><span>Paso 2:</span> Referencias</h3>

    <div wire:key="ReferenceForms">
        @foreach ($ReferenceForms as $key => $value)

            @php
                $candidateReference = [];
                if (auth()->check()) $candidateReference = get_array_value($profile['candidateReferences'], $key - 1);
            @endphp

            @if (count($ReferenceForms) > 1)
                <div class="step-number">
                    <span>#{{ $key }}</span>
                    <svg wire:click="removeForm('ReferenceForms', {{ $key }})" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>
                </div>
            @endif

            <div class="input-x2">
                <div class="input">
                    <label for="referenceName{{ $key }}">Nombre:</label>
                    <input type="text" name="referenceName{{ $key }}" placeholder="Nombre"
                        id="referenceName{{ $key }}" maxlength="100" oninput="limitInput(this)" value="{{ get_array_value($candidateReference, 'NOM') }}">
                </div>

                <div class="input">
                    <label for="referencePhone{{ $key }}">Teléfono:</label>
                    <input type="tel" name="referencePhone{{ $key }}" class="phone-input" placeholder="Teléfono"
                        id="referencePhone{{ $key }}" value="{{ get_array_value($candidateReference, 'TELEFO') }}">
                </div>
            </div>

            <div class="input-x3">
                <div class="input">
                    <label for="referenceEmail{{ $key }}">Correo electrónico:</label>
                    <input type="text" name="referenceEmail{{ $key }}"
                        placeholder="Correo electrónico" maxlength="150" oninput="limitInput(this)" id="referenceEmail{{ $key }}" value="{{ get_array_value($candidateReference, 'EMAIL') }}">
                </div>

                <div class="input">
                    <label for="referenceType{{ $key }}">Tipo de referencia: {{ $key }}</label>
                    <select name="referenceType{{ $key }}" id="referenceType{{ $key }}">
                        @foreach(REFERENCE_TYPES as $code => $value)
                            <option value="{{ $code }}" {{ get_array_value($candidateReference, 'TIPO') == $code ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input">
                    <label for="referenceProfession{{ $key }}">Profesión: {{ $key }}</label>
                    <input type="text" maxlength="100" oninput="limitInput(this)" name="referenceProfession{{ $key }}" placeholder="Profesión"
                        id="referenceProfession{{ $key }}" value="{{ get_array_value($candidateReference, 'PROFES') }}">
                </div>
            </div>
        @endforeach

        <button wire:click="addForm('ReferenceForms', true)" type="button" class="button-link button-plus">
            Agregar referencia
            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
        </button>
    </div>
</div>

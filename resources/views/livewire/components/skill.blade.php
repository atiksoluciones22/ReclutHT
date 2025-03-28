<div>
    <h3 class="title-step"><span>Paso 1:</span> Idiomas</h3>

    <div wire:key="LanguageForms">
        @foreach ($LanguageForms as $key => $value)

            @if (count($LanguageForms) > 1)
                <div class="step-number">
                    <span>#{{ $key }}</span>
                    <svg wire:click="removeForm('LanguageForms', {{ $key }})" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>
                </div>
            @endif

            @php
                $candidateLanguage = [];
                if (auth()->check()) $candidateLanguage = get_array_value($profile['candidateLanguages'], $key - 1);
            @endphp

            <div class="input-x2">
                <div class="input">
                    <label for="skillLanguage{{ $key }}">Idioma {{ $key }}:</label>
                    <select name="skillLanguage{{ $key }}" id="skillLanguage{{ $key }}">
                        <option value="">Seleccione un idioma</option>
                            @foreach($languages as $language)
                                <option value="{{ $language->COD }}" {{ (get_array_value($candidateLanguage, 'IDIOMA') ?? 'SPA') == $language->COD ? 'selected' : '' }}>{{ $language->NOM }}</option>
                            @endforeach
                    </select>
                </div>

                <div class="input">
                    <label for="skillLanguageLevel{{ $key }}">Nivel Idioma {{ $key }}:</label>
                    <select name="skillLanguageLevel{{ $key }}"
                        id="skillLanguageLevel{{ $key }}">
                        @foreach(LANGUAGE_LEVELS as $code => $value)
                            <option value="{{ $code }}" {{ get_array_value($candidateLanguage, 'NIVACR') == $code ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endforeach

        <button wire:click="addForm('LanguageForms')" type="button" class="button-link button-plus">
            Agregar idioma
            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
        </button>
    </div>

    <h3 class="title-step"><span>Paso 2:</span> Habilidades profesionales</h3>

    <div wire:key="SkillsForms">
        @foreach ($SkillsForms as $key => $value)
            @if (count($SkillsForms) > 1)
                <div class="step-number">
                    <span>#{{ $key }}</span>
                    <svg wire:click="removeForm('SkillsForms', {{ $key }})" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>
                </div>
            @endif

            <div class="input-x1">
                <div class="input">
                    <label for="skillProfessional{{ $key }}">Habilidad profesional {{ $key }}:</label>
                    <select name="skillProfessional{{ $key }}"
                        id="skillProfessional{{ $key }}">
                        <option value="">Seleccione una habilidad</option>
                        @foreach($skills as $skill)
                            @if(get_array_value($profile, 'HAB1') == $skill->COD)
                                <option value="{{ $skill->COD }}" selected>{{ $skill->NOM }}</option>
                            @elseif(get_array_value($profile, 'HAB2') == $skill->COD)
                                <option value="{{ $skill->COD }}" selected>{{ $skill->NOM }}</option>
                            @elseif(get_array_value($profile, 'HAB3') == $skill->COD)
                                <option value="{{ $skill->COD }}" selected>{{ $skill->NOM }}</option>
                            @else
                                <option value="{{ $skill->COD }}">{{ $skill->NOM }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        @endforeach

        <button wire:click="addForm('SkillsForms')" type="button" class="button-link button-plus">
            Agregar Habilidad
            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
            </svg>
        </button>
    </div>

    <h3 class="title-step"><span>Paso 3:</span> Nivel formativo</h3>

    <div class="input-x2">
        <div class="input">
            <label for="skillLevelStudy">Nivel formativo:</label>
            <select name="skillLevelStudy" id="skillLevelStudy">
                <option value="">Seleccione un nivel</option>
                @foreach($trainings as $training)
                    <option value="{{ $training->COD }}" {{ $training->COD == get_array_value($profile, 'NIVFOR') ? 'selected' : '' }}>{{ $training->NOM }}</option>
                @endforeach
            </select>
        </div>

        <div class="input">
            <label for="skillLevelStudyOficial">Nivel formativo oficial:</label>
            <select name="skillLevelStudyOficial" id="skillLevelStudyOficial">
                @foreach(TRAINING_LEVELS as $code => $value)
                    <option value="{{ $code }}" {{ $code == get_array_value($profile, 'NOFOR') ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

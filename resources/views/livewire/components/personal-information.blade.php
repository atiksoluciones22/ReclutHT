<div>
    <h3 class="title-step"><span>Paso 1:</span> Adjuntar Documentos</h3>

    <div wire:ignore class="input">
        <label for="name">Curriculum: <span class="required">*</span></label>
        <input type="file" class="my-pond" id="curriculum" />
        <input type="hidden" name="curriculum" wire:model.live="curriculum">
    </div>

    <div class="input">
        @error('curriculum')
            <span class="message-error">{{ $message }}</span>
        @enderror
    </div>

    <h3 class="title-step"><span>Paso 2:</span> Información personal</h3>

    <div class="input-x3">
        <div class="input {{ $errors->has('personal_informationName') ? 'input-error' : '' }}">
            <label for="personal_informationName">Nombre: <span class="required">*</span></label>
            <input type="text" placeholder="Nombre" class="uppercase" name="personal_informationName" maxlength="60" oninput="limitInput(this)" id="personal_informationName"
                wire:model.live="personal_informationName" >
            @error('personal_informationName')
                <span class="message-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="input {{ $errors->has('personal_informationLastname') ? 'input-error' : '' }}">
            <label for="personal_informationLastname">Apellidos: <span class="required">*</span></label>
            <input type="text" name="personal_informationLastname" class="uppercase" maxlength="60" oninput="limitInput(this)" placeholder="Apellidos"
                id="personal_informationLastname" wire:model.live="personal_informationLastname">
            @error('personal_informationLastname')
                <span class="message-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="input {{ $errors->has('personal_informationBirthdate') ? 'input-error' : '' }}">
            <label for="personal_informationBirthdate">Fecha de Nacimiento: <span class="required">*</span></label>
            <input type="date" name="personal_informationBirthdate" id="personal_informationBirthdate"
                wire:model.live="personal_informationBirthdate" min="1900-01-01" max="{{ date('Y-m-d') }}">
            @error('personal_informationBirthdate')
                <span class="message-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="input-x3">
        <div class="input">
            <label for="personal_informationSex">Sexo:</label>
            <select name="personal_informationSex" id="personal_informationSex">
                @foreach(GENDER as $code => $value)
                    <option value="{{ $code }}" {{ $code == get_array_value($profile, 'SEXO') ? 'selected' : '' }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <div class="input">
            <label for="personal_informationCivilStatus">Estado civil:</label>
            <select name="personal_informationCivilStatus" id="personal_informationCivilStatus">
                @foreach (MARITAL_STATUSES as $code => $value)
                    <option value="{{ $code }}" {{ $code == get_array_value($profile, 'ESTCIV') ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="input">
            <label for="personal_informationNationality">Nacionalidad:</label>
            <select name="personal_informationNationality" id="personal_informationNationality">
                @foreach ($nationalities as $nationality)
                    <option value="{{ $nationality->COD }}"
                        {{ $nationality->COD == (get_array_value($profile, 'PAISOR') ?? 1) ? 'selected' : '' }}>
                        {{ $nationality->NOM }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="input-x3">
        <div class="input">
            <label for="personal_informationTypeDocument">Tipo de Documento:</label>
            <select name="personal_informationTypeDocument" id="personal_informationTypeDocument">
                <option value="1" selected>Cedula</option>
                <option value="2">Pasaporte</option>
                <option value="3">Carné migración</option>
                <option value="4">Carné interior</option>
            </select>
        </div>

        <div
            class="input {{ $errors->has('personal_informationNumDocument') ? 'input-error' : '' }} {{ $personal_informationNumDocumentType }}">
            <label for="personal_informationNumDocument">Nº de documento: <span class="required">*</span></label>
            <input type="text" placeholder="Nº de documento" name="personal_informationNumDocument"
                id="personal_informationNumDocument" wire:model.live="personal_informationNumDocument">
            @error('personal_informationNumDocument')
                <span class="message-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="input">
            <label for="personal_informationCuantityChildren">Cantidad de hijos:</label>
            <input type="text" placeholder="Cantidad de hijos" name="personal_informationCuantityChildren"
                id="personal_informationCuantityChildren" value="{{ get_array_value($profile, 'NUMHIJ') }}">
        </div>
    </div>

    <div class="input-x3">
        <div class="input">
            <label for="personal_informationTel">Teléfono:</label>
            <input type="tel" placeholder="Número de teléfono" class="phone-input" name="personal_informationTel"
                id="personal_informationTel" maxlength="14" oninput="limitInput(this)" value="{{ get_array_value($profile, 'TEL') }}">
        </div>

        <div class="input {{ $errors->has('personal_informationPhone') ? 'input-error' : '' }}">
            <label for="personal_informationPhone">Celular: <span class="required">*</span></label>
            <input type="tel" placeholder="Celular" class="phone-input" name="personal_informationPhone"
                id="personal_informationPhone" maxlength="14" oninput="limitInput(this)" wire:model.live="personal_informationPhone">
            @error('personal_informationPhone')
                <span class="message-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="input {{ $errors->has('personal_informationEmail') ? 'input-error' : '' }}">
            <label for="personal_informationEmail">Correo electónico: <span class="required">*</span></label>
            <input type="text" placeholder="Correo electónico" maxlength="200" oninput="limitInput(this)" name="personal_informationEmail"
                id="personal_informationEmail" wire:model.live="personal_informationEmail">
            @error('personal_informationEmail')
                <span class="message-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <h3 class="title-step"><span>Paso 3:</span> Dirección Residencia</h3>

    <div class="input-x3">
        <div class="input">
            <label for="personal_informationProvince">Provincia:</label>
            <select wire:model.live="selectedProvince" name="personal_informationProvince"
                id="personal_informationProvince">
                <option value="">Seleccione una provincia</option>
                @foreach ($provinces as $province)
                    <option value="{{ json_encode($province) }}">{{ $province->NOM }}</option>
                @endforeach
            </select>
        </div>

        <div class="input">
            <label for="personal_informationMunicipality">Municipio:</label>
            <select wire:model.live="selectedMunicipality" name="personal_informationMunicipality"
                id="personal_informationMunicipality">
                <option value="">Seleccione un municipio</option>
                @foreach ($municipalities as $municipality)
                    <option value="{{ json_encode($municipality) }}">{{ $municipality->NOM }}</option>
                @endforeach
            </select>
        </div>

        <div class="input">
            <label for="personal_informationDistrict">Distrito:</label>
            <select wire:model.live="selectedDistrict" name="personal_informationDistrict"
                id="personal_informationDistrict">
                <option value="">Seleccione un distrito</option>
                @foreach ($districts as $district)
                    <option value="{{ json_encode($district) }}">{{ $district->NOM }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="input-x3">
        <div class="input">
            <label for="personal_informationSection">Sección:</label>
            <select wire:model.live="selectedSection" name="personal_informationSection"
                id="personal_informationSection">
                <option value="">Seleccione una sección</option>
                @foreach ($sections as $section)
                    <option value="{{ json_encode($section) }}">{{ $section->NOM }}</option>
                @endforeach
            </select>
        </div>

        <div class="input">
            <label for="personal_informationSector">Sector:</label>
            <select wire:model.live="selectedSector" name="personal_informationSector"
                id="personal_informationSector">
                <option value="">Seleccione un sector</option>
                @foreach ($sectors as $sector)
                    <option value="{{ json_encode($sector) }}">{{ $sector->NOM }}</option>
                @endforeach
            </select>
        </div>

        <div class="input">
            <label for="personal_informationDomicile">Domicilio:</label>
            <input type="text" placeholder="Domicilio, calle, número, piso, escalera"
                name="personal_informationDomicile" id="personal_informationDomicile" maxlength="150" oninput="limitInput(this)"
                value="{{ get_array_value($profile, 'DIR') }}">
        </div>
    </div>

    <h3 class="title-step"><span>Paso 4:</span> Licencia de conducir</h3>

    <div class="input-x2">
        <div class="input">
            <label for="personal_informationCategory">Categoría:</label>
            <select name="personal_informationCategory" id="personal_informationCategory">
                <option value="" selected>No poseo de licencia</option>
                @foreach (LICENSE_CATEGORIES as $code => $value)
                    <option value="{{ $code }}"
                        {{ get_array_value($profile, 'TIPLIC') == $code ? 'selected' : '' }}>{{ $value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="input">
            <label for="personal_informationRestrictions">Restricciones:</label>
            <input type="tex" placeholder="Restricciones" name="personal_informationRestrictions"
                id="personal_informationRestrictions"  maxlength="150" oninput="limitInput(this)" value="{{ get_array_value($profile, 'RESTRI') }}">
        </div>

        <div class="input">
            <label for="personal_informationIssueDate">Fecha de emisión:</label>
            <input type="date" placeholder="Restricciones" name="personal_informationIssueDate"
                id="personal_informationIssueDate" value="{{ convert_date(get_array_value($profile, 'ANTLIC')) }}" min="1900-01-01" max="{{ date('Y-m-d') }}">
        </div>

        <div class="input">
            <label for="personal_informationOwnVehicle">¿Posee vehículo propio?:</label>
            <select name="personal_informationOwnVehicle" id="personal_informationOwnVehicle">
                <option selected value="">No</option>
                <option value="*" {{ get_array_value($profile, 'POSVEH') == '*' ? 'selected' : '' }}>Si</option>
            </select>
        </div>
    </div>
</div>

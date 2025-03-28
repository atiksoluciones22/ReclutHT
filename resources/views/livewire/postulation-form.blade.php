<div>
    @auth
        <form method="POST" class="recruitment-form" wire:submit.prevent="submit" action="{{ route('panel.candidates.update') }}"
            id="form" enctype="multipart/form-data">
            @method('put')
        @else
            <form method="POST" class="recruitment-form" wire:submit.prevent="submit" action="{{ route('postulations.saveApply') }}"
                id="form" enctype="multipart/form-data">
            @endauth

            @csrf

            <input type="hidden" name="cod" value="{{ $cod }}" wire:ignore>

            <div class="progressx {{ count($steps) === 5 ? 'progress--five' : '' }}">
                @foreach ($steps as $key => $value)
                    <div class="progress__step {{ $step === $key ? 'progress__step--active' : '' }}">
                        <div class="progress__label">
                            <span class="progress__badge">
                                <span class="progress__number">{{ $loop->index + 1 }}</span>
                            </span>
                            <span class="progress__title">@lang('postulation.' . $key)</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- PERSONAL INFORMATION -->
            <div style="display: {{ $step === 'personal-information' ? 'block' : 'none' }}">
                @include('livewire.components.personal-information')
            </div>

            <!-- SKILLS -->
            <div style="display: {{ $step === 'skills' ? 'block' : 'none' }}">
                @include('livewire.components.skill')
            </div>

            <!-- EXPERIENCES -->
            <div style="display: {{ $step === 'experiences' ? 'block' : 'none' }}">
                @include('livewire.components.experience')
            </div>

            <!-- POSTULATION -->
            <div style="display: {{ $step === 'postulation' ? 'block' : 'none' }}">
                @include('livewire.components.postulation')
            </div>

            <div class="buttons">
                @if (array_search($step, $steps))
                    <button type="button" class="button-link" wire:click="goToBack('{{ $step }}')">
                        <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5"></path>
                        </svg>
                        Anterior
                    </button>
                @endif

                <button type="submit" class="button-link">
                    @if ($steps[$step] === 'submit')
                        @auth
                            Guardar
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3">
                                </path>
                            </svg>
                        @else
                            Postular
                            <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z">
                                </path>
                            </svg>
                        @endauth
                    @else
                        Siguiente
                        <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5"></path>
                        </svg>
                    @endif
                </button>

            </div>
        </form>
</div>

@php
    $pathCurriculum = get_array_value(get_array_value($profile, 'candidateDocumentManagements'), 'RUTFTP');
    if(auth()->check() && $pathCurriculum) $fileCurriculum = route("panel.files.show", ["file" => $pathCurriculum]);
@endphp

@push('scripts')
    <script src="{{ asset('js/file-pond.js') }}"></script>
    <script>
        function loadCurrencyInputScript() {
            const script = document.createElement('script');
            script.src = "{{ asset('js/currency-input.js') }}";
            document.head.appendChild(script);
        }

        loadCurrencyInputScript();

        function loadPhoneInputScript() {
            const script = document.createElement('script');
            script.src = "{{ asset('js/phone-input.js') }}";
            document.head.appendChild(script);
        }

        loadPhoneInputScript();

        function loadCedulaInputScript() {
            const script = document.createElement('script');
            script.src = "{{ asset('js/cedula-input.js') }}";
            document.head.appendChild(script);
        }

        loadCedulaInputScript();

        Livewire.on('reloadScript', () => {
            loadCurrencyInputScript();
        });

        handleFileUpload(
            'curriculum',
            'Arrastra y suelta tu curriculum o <span class="filepond--label-action"> busca en tu dispositivo</span>',
            'fileUploaded',
            '{{ $fileCurriculum ?? null }}'
        );

        Livewire.on('submit', function() {
            document.getElementById('form').submit();
        });
    </script>
@endpush

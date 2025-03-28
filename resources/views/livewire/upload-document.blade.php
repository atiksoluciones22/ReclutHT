<div>
    <form method="POST" class="recruitment-form" wire:submit.prevent="submit"
        action="{{ route('panel.documents.upload') }}" id="form">
        @csrf

        <div class="input {{ $errors->has('subject') ? 'input-error' : '' }}">
            <label for="subject">Asunto: <span class="required">*</span></label>
            <input type="text" placeholder="Asunto" name="" maxlength="60" oninput="limitInput(this)"
                id="subject" wire:model.live="subject">
            @error('subject')
                <span class="message-error">{{ $message }}</span>
            @enderror
        </div>

        <div wire:ignore class="input" style="margin-top: 30px;">
            <label for="file">Documento: <span class="required">*</span></label>
            <input type="file" class="my-pond" id="file" />
            <input type="hidden" name="file" wire:model.live="file">
        </div>

        <div class="input">
            @error('file')
                <span class="message-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" wire:loading.remove.delay.long class="button-link" style="margin-top: 25px;">
            Cargar
        </button>

        <button type="button" wire:loading.delay.long style="display: none; margin-top: 25px;" class="button-link">
            Cargando...
        </button>
    </form>
</div>

@push('scripts')
    <script src="{{ asset('js/file-pond.js') }}"></script>
    <script>
        handleFileUpload(
            'file',
            'Arrastra y suelta el documento o <span class="filepond--label-action"> busca en tu dispositivo</span>',
            'fileUploaded',
            ''
        );

        Livewire.on('reloadPage', function() {
            setTimeout(function() {
                window.location.href = "{{ route('panel.documents.upload') }}";
            }, 4100);
        });
    </script>
@endpush

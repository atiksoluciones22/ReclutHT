<div class="confirm-alert" id="{{ $key }}" style="display: none;">
    <form action="{{ $route }}" method="{{ $method }}">
        <h4 class="title">{{ $message }}</h4>

        <p class="content">
            <svg class="info" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"></path>
            </svg>

            {{ $text }}

            @isset($file)
            <div>
                <a class="file" href="{{ $file }}" target="_blank" rel="noopener noreferrer">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Ver las instrucciones antes de tomar la prueba.
                </a>
            </div>
        @endisset
        </p>

        <div class="actions">
            <button type="button" class="cancel" onclick="closeSweetAlert('{{ $key }}')">Cancelar</button>
            @if ($action)
                <button type="submit" class="confirm">Comenzar</button>
            @endif
        </div>
    </form>
</div>

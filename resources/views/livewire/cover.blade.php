<div>
    <div class="cover">
        <div class="container">
            <h1>{{ $title }}</h1>
            @if ($subtitle)
                <p>{{ utf8_decode($subtitle) }}</p>
            @endif
        </div>
    </div>
</div>

<div class="container-test">
    @switch($type)
        @case(5)
            <livewire:timer :duration="get_array_value($exam, 'TIEMPO') * 60" />
            @break

        @case(12)
            @if($examcode == 1)
                <livewire:timer :duration="12 * 60" />
            @elseif($examcode == 2)
                <livewire:timer :duration="45 * 60" />
            @endif
            @break
    @endswitch

    <form method="post" id="form">
        @csrf

        <div class="content">
            <div class="questions">

            @switch($type)
                @case(5)
                    @foreach (get_array_value($exam, 'questions') as $key => $question)
                        <div class="question" style="display: {{ $key === $currentQuestion ? 'block' : 'none' }}">
                            <h2 class="title">{{ get_array_value($question, 'DESCRI') }}</h2>
                            <ul>
                                <input type="radio" checked style="display: none;" value="" name="{{ get_array_value($question, 'COD') }}">

                                @for ($i = 1; $i < 6; $i++)
                                    @php $key = "RES" . $i;  @endphp

                                    @if (get_array_value($question, $key) != null)
                                        @php $keyWithCode = get_array_value($question, 'COD') . "_" . $key; @endphp
                                        <li>
                                            <input type="radio" id="{{ $keyWithCode }}" value="{{ $i }}" name="{{ get_array_value($question, 'COD') }}">
                                            <label for="{{ $keyWithCode }}">{{ get_array_value($question, $key) }}</label>
                                        </li>
                                    @endif
                                @endfor
                            </ul>
                        </div>
                    @endforeach
                    @break

                    @case(12)
                        @if($examcode == 3)
                            @foreach ($exam as $key => $questions)
                                <div class="question" style="display: {{ $key === $currentQuestion ? 'block' : 'none' }}">
                                    <table class="disc-cleaver-question">
                                        <tr>
                                          <th></th>
                                          <th class="size-input">Más</th>
                                          <th class="size-input">Menos</th>
                                        </tr>
                                        @foreach($questions as $keyQuestion => $question)
                                            <tr>
                                                <td class="title">{{ $question }} </td>
                                                <td style="display: none;"><input type="radio" id="{{ $keyQuestion }}" checked value="0" name="{{ $keyQuestion }}"></td>
                                                <td><input type="radio" id="{{ $keyQuestion }}" value="1" name="{{ $keyQuestion }}"></td>
                                                <td><input type="radio" id="{{ $keyQuestion }}" value="2" name="{{ $keyQuestion }}"></td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @endforeach
                        @else
                            @foreach ($exam as $key => $question)
                                <div class="question" style="display: {{ $key === $currentQuestion ? 'block' : 'none' }}">

                                    <h2 class="title">{{ convert_rtf_to_text(get_array_value($question, 'PREGUN')) }}</h2>

                                    <ul>
                                        @php
                                            $input = true;
                                            $group = get_array_value($question, 'PREGU') . '_' . get_array_value($question, 'POSIB');
                                        @endphp

                                        @for ($i = 1; $i < 6; $i++)
                                            @php
                                                $number = "RESP" . $i;
                                            @endphp

                                            @if (get_array_value($question, $number) != null)
                                                @php $input = false; @endphp

                                                @if($examcode == 4)

                                                    <h2 class="title">{{ get_array_value($question, $number) }}</h2>

                                                    @for($num = 1; $num < 6; $num++)
                                                        <li>
                                                            <input type="radio" id="{{ $group . $num }}" value="{{ $num }}" name="{{ $group }}">
                                                            <label for="{{ $group  . $num }}">{{ $num }}</label>
                                                        </li>
                                                    @endfor

                                                @else
                                                    <li>
                                                        <input type="radio" id="{{ $key . $number }}" value="{{ $i }}" name="{{ $group }}">
                                                        <label for="{{ $key . $number  }}">{{ get_array_value($question, $number) }}</label>
                                                    </li>
                                                @endif
                                            @endif
                                        @endfor

                                        @if($input)
                                            <div class="input">
                                                <label for="">Respuesta:</label>
                                                <input type="text" maxlength="100" oninput="limitInput(this)">
                                            </div>
                                            @else
                                            <input type="radio" checked style="display: none;" value="" name="{{ $group }}">
                                        @endif
                                    </ul>
                                </div>
                            @endforeach
                        @endif
                    @break
            @endswitch
            </div>
        </div>

        <div class="grid-x3">
            <button type="button" wire:click="back" class="button">
                <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"></path>
                </svg>
                <span>Atrás</span>
            </button>
            <span class="current-question">{{ $currentQuestion + 1 }} de {{ $totalQuestions }}</span>
            <button type="button" wire:click="submit" class="button">
                <span>Siguiente</span>
                <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"></path>
                </svg>
            </button>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        /*if (window.performance.navigation.type == 1) {
           confirm('Desea Actualizar ? ');
        }*/

        Livewire.on('submitForm', function() {
            document.getElementById('form').submit();
        });
    </script>
@endpush

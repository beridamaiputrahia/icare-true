{{-- Form dinamis sesuai game_type. $gameType tersedia (dari create) atau
     $question->game_type (dari edit) -- controller yang memutuskan mana yang
     dipakai lewat variabel $gameType yang selalu di-pass ke view ini. --}}
@switch($gameType)
    @case('kuis')
        <div class="mb-3">
            <label class="form-label">Pertanyaan</label>
            <textarea name="q" class="form-control" rows="2" required>{{ old('q', $question->data['q'] ?? '') }}</textarea>
        </div>
        @php $opsiLama = old('opsi', $question->data['opsi'] ?? ['', '', '', '']); @endphp
        <div class="mb-3">
            <label class="form-label">Pilihan Jawaban (A-D)</label>
            @foreach(range(0, 3) as $i)
                <div class="input-group mb-2">
                    <span class="input-group-text">{{ chr(65 + $i) }}</span>
                    <input type="text" name="opsi[]" class="form-control" value="{{ $opsiLama[$i] ?? '' }}" required>
                </div>
            @endforeach
        </div>
        <div class="mb-3">
            <label class="form-label">Jawaban Benar</label>
            <select name="benar" class="form-select" required>
                @foreach(range(0, 3) as $i)
                    <option value="{{ $i }}" @selected(old('benar', $question->data['benar'] ?? null) == $i)>
                        {{ chr(65 + $i) }}
                    </option>
                @endforeach
            </select>
        </div>
        @break

    @case('susun')
        <div class="mb-3">
            <label class="form-label">Referensi Ayat</label>
            <input type="text" name="ref" class="form-control" placeholder="Contoh: Yohanes 3:16"
                   value="{{ old('ref', $question->data['ref'] ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Teks Ayat</label>
            <textarea name="teks" class="form-control" rows="3" required>{{ old('teks', $question->data['teks'] ?? '') }}</textarea>
            <div class="form-text">Ayat akan diacak per-kata untuk disusun ulang pemain.</div>
        </div>
        @break

    @case('tebak')
        <div class="mb-3">
            <label class="form-label">Nama Tokoh (jawaban)</label>
            <input type="text" name="jawaban" class="form-control"
                   value="{{ old('jawaban', $question->data['jawaban'] ?? '') }}" required>
        </div>
        @php $cluesLama = old('clues', $question->data['clues'] ?? ['', '', '']); @endphp
        <div class="mb-3">
            <label class="form-label">Clue (dari samar ke jelas, 3 clue)</label>
            @foreach(range(0, 2) as $i)
                <input type="text" name="clues[]" class="form-control mb-2" placeholder="Clue #{{ $i + 1 }}"
                       value="{{ $cluesLama[$i] ?? '' }}" required>
            @endforeach
        </div>
        @php $salahLama = old('salah', $question->data['salah'] ?? ['', '', '']); @endphp
        <div class="mb-3">
            <label class="form-label">Pengecoh (3 nama tokoh lain)</label>
            @foreach(range(0, 2) as $i)
                <input type="text" name="salah[]" class="form-control mb-2" placeholder="Pengecoh #{{ $i + 1 }}"
                       value="{{ $salahLama[$i] ?? '' }}" required>
            @endforeach
        </div>
        @break

    @case('memory')
        <div class="mb-3">
            <label class="form-label">Sisi A (nama tokoh/istilah)</label>
            <input type="text" name="a" class="form-control" value="{{ old('a', $question->data['a'] ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Sisi B (pasangannya)</label>
            <input type="text" name="b" class="form-control" value="{{ old('b', $question->data['b'] ?? '') }}" required>
            <div class="form-text">Dua kartu berisi A dan B akan dicocokkan pemain sebagai pasangan.</div>
        </div>
        @break
@endswitch

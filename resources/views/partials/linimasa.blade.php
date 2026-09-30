{{--
    Linimasa penanganan: kapan diajukan, dibaca, diproses, dan selesai.
    Variabel: $aspirasi
--}}
<ol class="timeline">
    @foreach ($aspirasi->linimasa() as $tahap)
        <li class="timeline-item {{ $tahap['waktu'] ? 'done' : '' }}">
            <span class="timeline-mark"></span>
            <div>
                <strong>{{ $tahap['label'] }}</strong>
                <small>
                    {{ $tahap['waktu'] ? $tahap['waktu']->translatedFormat('d F Y, H:i').' WIB' : 'Belum' }}
                </small>
            </div>
        </li>
    @endforeach
</ol>

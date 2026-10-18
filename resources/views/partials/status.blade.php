@if ($p->status === 'dikembalikan')
    <span class="chip chip-green">Dikembalikan</span>
@elseif ($p->hitungHariTelat() > 0)
    <span class="chip chip-red">Terlambat {{ $p->hitungHariTelat() }} hari</span>
@else
    <span class="chip chip-blue">Dipinjam</span>
@endif
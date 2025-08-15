@php
use App\Helpers\FormatHelper;
@endphp
<div class="table-responsive">
    <table class="table table-hover datatable-custom" id="{{ $tableId }}" style="width: 100%">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th colspan="2">Spesifikasi Barang</th>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2">Jenis Pemeliharaan</th>
                <th rowspan="2">Yang Memelihara</th>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">Biaya</th>
                <th rowspan="2">Ket</th>
            </tr>
            <tr>
                <th>Kode Barang</th>
                <th>No Register</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kendaraan->kode_barang ?? '-' }}</td>
                <td>{{ $item->kendaraan->no_register ?? '-' }}</td>
                <td>{{ $item->kendaraan->nama_barang ?? '-' }}</td>
                <td>{{ $item->kendaraan->no_polisi ?? '' }} - {{ $item->kendaraan->jenis ?? '' }}</td>
                <td>{{ $item->bengkel }}</td>
                <td>{{ FormatHelper::formatTanggal($item->tanggal_pemeliharaan_sebelumnya) }}</td>
                <td data-biaya="{{ $item->biaya }}">{{ FormatHelper::formatRupiah($item->biaya) }}</td>
                <td>{{ $item->deskripsi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align:right">Total Biaya:</th>
                <th class="total-biaya">Rp 0</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
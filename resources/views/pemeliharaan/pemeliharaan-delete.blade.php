@php
    use App\Helpers\FormatHelper;
@endphp
@foreach ($view_pemeliharaan as $pm)
    <div class="modal fade" id="basicModal{{ $pm->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Pemeliharaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('pemeliharaan/' . $pm->id) }}" method="post">
                        @method('DELETE')
                        @csrf
                        <div class="mb-3">
                            <p style="color: black">Apakah anda yakin untuk menghapus data pemeliharaan
                                <b>{{ FormatHelper::formatTanggal($pm->tanggal_pemeliharaan_sebelumnya) }} </b> ?
                            </p>
                        </div>
                        <input type="hidden" readonly class="form-control " name="id_kendaraan"
                            value="{{ old('id_kendaraan', $pm->id_kendaraan) }}">
                        <input type="hidden" readonly class="form-control " name="id_rekening"
                            value="{{ old('id_rekening', $pm->id_rekening) }}">
                        <input type="hidden" readonly class="form-control " name="slug"
                            value="{{ old('slug', $pemeliharaan->kendaraan->slug) }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-outline-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- End Basic Modal-->
@endforeach
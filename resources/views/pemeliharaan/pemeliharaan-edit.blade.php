@php
    use App\Helpers\FormatHelper;
@endphp
@foreach ($view_pemeliharaan as $pm)
    <div class="modal fade" id="largeModal{{ $pm->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Pemeliharaan </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('pemeliharaan/' . $pm->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="inputEmail" class="col-sm-4 col-form-label">Frekuensi per berapa bulan <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control @error('frekuensi') is-invalid @enderror"
                                    name="frekuensi" value="{{ old('frekuensi', $pm->interval_bulan) }}">

                                @error('frekuensi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label class="col-sm-4 col-form-label">Bulan</label>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-8">
                                <input type="hidden" readonly class="form-control " name="id_kendaraan"
                                    value="{{ old('id_kendaraan', $pm->id_kendaraan) }}">
                                <input type="hidden" readonly class="form-control " name="id_rekening"
                                    value="{{ old('id_rekening', $pm->id_rekening) }}">
                                <input type="hidden" readonly class="form-control " name="slug"
                                    value="{{ old('slug', $pemeliharaan->kendaraan->slug) }}">


                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputEmail" class="col-sm-4 col-form-label">Nama Bengkel <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('nama_bengkel') is-invalid @enderror"
                                    name="nama_bengkel" value="{{ old('nama_bengkel', $pm->bengkel) }}">
                                @error('nama_bengkel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputEmail" class="col-sm-4 col-form-label">Biaya <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control format-rupiah @error('biaya') is-invalid @enderror"
                                    name="biaya" value="{{ old('biaya', FormatHelper::formatRupiah($pm->biaya)) }}">
                                @error('biaya')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputEmail" class="col-sm-4 col-form-label">Deskripsi Pemeliharaan <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-sm-8">
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                    id="">{{ old('deskripsi', $pm->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <small><i>Tanda bintang (<sup class="text-danger"> * </sup>) di samping label
                                        kolom formulir
                                        menunjukkan bahwa kolom tersebut wajib diisi</i></small>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-outline-warning">Edit</button>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- End Basic Modal-->
@endforeach
@php
use App\Helpers\FormatHelper;
use Carbon\Carbon;
@endphp
@foreach ($view_bbm as $pm)
<div class="modal fade" id="largeModal{{ $pm->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pengeluaran Bahan Bakar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('pengeluaran-bbm/' . $pm->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <label for="inputText" class="col-sm-2 col-form-label">Kendaraan </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('kendaraan') is-invalid @enderror"
                                name="kendaraan" value="{{ old('kendaraan', $kendaraan->no_polisi) }}" readonly>
                            @error('nama_rek')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Merk </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('merk') is-invalid @enderror" name="merk"
                                value="{{ old('merk', $kendaraan->merk) }}" readonly>
                            @error('merk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Nama SPBU </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('spbu') is-invalid @enderror" name="spbu"
                                value="{{ old('spbu', $pm->spbu) }}">
                            @error('spbu')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Jumlah Liter <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control @error('jumlah_liter') is-invalid @enderror"
                                name="jumlah_liter" id="jumlah_liter_edit"
                                value="{{ old('jumlah_liter', $pm->jumlah_liter) }}">

                            @error('jumlah_liter')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <label class="col-sm-2 col-form-label">Liter</label>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Harga BBM / Liter <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-10">
                            <input type="text"
                                class="form-control format-rupiahEdit @error('harga_bbm') is-invalid @enderror"
                                name="harga_bbm" id="harga_bbm_edit" inputmode="numeric"
                                value="{{ old('harga_bbm', FormatHelper::formatRupiah($pm->harga_bbm)) }}">
                            @error('harga_bbm')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Tanggal Pengisian <sup
                                class="text-danger">*</sup></label>

                        <div class="col-sm-10">
                            <input type="text"
                                class="form-control format-tanggal @error('tanggal') is-invalid @enderror"
                                name="tanggal"
                                value="{{ old('tanggal', Carbon::parse($pm->tanggal_pengisian)->format('d/m/Y')) }}">

                            @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                            <input type="hidden" readonly class="form-control " name="id_kendaraan"
                                value="{{ old('id_kendaraan', $kendaraan->id) }}">
                            <input type="hidden" readonly class="form-control " name="id_rekening"
                                value="{{ old('id_rekening', $kendaraan->id_rekening) }}">
                            <input type="hidden" readonly class="form-control " name="slug"
                                value="{{ old('slug', $kendaraan->slug) }}">


                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Foto Struk</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control @error('foto_struk') is-invalid @enderror"
                                name="foto_struk" value="{{ old('foto_struk') }}">
                            @if ($pm->foto_struk)
                            <img src="{{ asset('strukImage/' . $pm->foto_struk) }}" class="img-thumbnail mt-2"
                                width="150">
                            @endif
                            @error('foto_struk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Biaya <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-10">
                            <input type="text"
                                class="form-control format-rupiahEdit1 @error('biaya') is-invalid @enderror"
                                name="biaya" inputmode="numeric" id="biaya_edit" readonly
                                value="{{ old('biaya', FormatHelper::formatRupiah($pm->nominal)) }}">
                            @error('biaya')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-sm-6">
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
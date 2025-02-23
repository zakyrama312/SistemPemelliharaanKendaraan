@php
    use App\Helpers\FormatHelper;
@endphp
@foreach ($view_pajakTahunan as $pm)
    <div class="modal fade" id="largeModal{{ $pm->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Pajak Tahunan </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('pajak-tahunan/' . $pm->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-sm-8">
                                <input type="hidden" readonly class="form-control " name="id_kendaraan"
                                    value="{{ old('id_kendaraan', $pm->id_kendaraan) }}">
                                <input type="hidden" readonly class="form-control " name="id_rekening"
                                    value="{{ old('id_rekening', $pm->id_rekening) }}">
                                <input type="hidden" readonly class="form-control " name="slug"
                                    value="{{ old('slug', $kendaraan->slug) }}">


                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputEmail" class="col-sm-4 col-form-label">Masa Berlaku <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-sm-8">
                                <input type="text"
                                    class="form-control format-tanggal @error('masa_berlaku') is-invalid @enderror"
                                    name="masa_berlaku" value="{{ old('masa_berlaku', $masa_berlaku) }}">
                                @error('masa_berlaku')
                                <div class=" invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputEmail" class="col-sm-4 col-form-label">Biaya <sup
                                    class="text-danger">*</sup></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control @error('biaya') is-invalid @enderror" name="biaya"
                                    value="{{ old('biaya', $pm->nominal) }}">
                                @error('biaya')
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
@foreach ($view_pemeliharaan as $pm)
    <div class="modal fade" id="basicModal{{ $pm->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Rekening</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('pemeliharaan/' . $id = $pm->slug) }}" method="post">
                        @method('DELETE')
                        @csrf
                        <div class="mb-3">
                            <p style="color: black">Apakah anda yakin untuk menghapus data
                                <!-- <b>{{ $pm->kendaraan->no_polisi }} - {{ $pm->kendaraan->merk }} </b> ? -->
                            </p>
                        </div>
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
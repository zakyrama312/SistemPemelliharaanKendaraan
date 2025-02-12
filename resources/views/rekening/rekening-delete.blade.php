@foreach ($rekening as $rek)
    <div class="modal fade" id="basicModal{{ $rek->id }}" tabindex="-1">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Hapus Data Rekening</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="{{ url('rekening/'. $id = $rek->id ) }}" method="post">
                @method('DELETE')
                @csrf
                <div class="mb-3">
                    <p style="color: black">Apakah anda yakin untuk menghapus data <b>{{ $rek -> nama_rekening }}</b> ?</p>
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

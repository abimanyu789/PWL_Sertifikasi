<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Pelatihan Dosen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @if($pelatihan->isEmpty())
                <div class="alert alert-info">
                    Belum ada pelatihan yang diupload.
                </div>
            @else
                <div class="list-group">
                    @foreach($pelatihan as $item)
                        <div class="list-card" 
                             onclick="modalAction('{{ url('/view_dosen/pelatihan/' . $item->upload_id . '/detail') }}')">
                            <div class="badge-jenis">{{ $item->jenis->jenis_nama }}</div>
                            <h5 class="sertif-title">{{ $item->nama_sertif }}</h5>
                            <p class="vendor-name">{{ $item->nama_vendor }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

<style>
.list-card {
    background: #fff;
    padding: 20px;
    margin-bottom: 15px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.list-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.12);
}

.list-card:last-child {
    margin-bottom: 0;
}

.badge-jenis {
    display: inline-block;
    background-color: #e3f2fd;
    color: #1976d2;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 12px;
}

.sertif-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0 0 8px 0;
}

.vendor-name {
    font-size: 14px;
    color: #666;
    margin: 0;
}

.modal-body {
    padding: 20px;
    background: #f8f9fa;
}

.list-group {
    border: none;
}
</style>
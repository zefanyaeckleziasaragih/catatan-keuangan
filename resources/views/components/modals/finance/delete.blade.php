<form wire:submit.prevent="deleteRecord">
    <div class="modal fade" tabindex="-1" id="deleteRecordModal" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">🗑️ Hapus Transaksi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>⚠️ Perhatian!</strong><br>
                        Apakah Anda yakin ingin menghapus transaksi berikut?<br>
                        <strong>{{ $deleteRecordInfo }}</strong>
                    </div>

                    <p class="mb-2">Untuk konfirmasi, masukkan <strong>jumlah</strong> transaksi:</p>
                    <div class="mb-3">
                        <input type="number" class="form-control" wire:model="deleteConfirmAmount" 
                               placeholder="Masukkan jumlah transaksi" step="0.01" min="0">
                        @error('deleteConfirmAmount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <small class="text-muted">
                        <strong>Catatan:</strong> Data yang sudah dihapus tidak dapat dikembalikan!
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <span wire:loading.remove wire:target="deleteRecord">Hapus</span>
                        <span wire:loading wire:target="deleteRecord">
                            <span class="spinner-border spinner-border-sm"></span> Menghapus...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<form wire:submit.prevent="addRecord">
    <div class="modal fade" tabindex="-1" id="addRecordModal" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">➕ Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model.live="addType">
                                <option value="expense">Pengeluaran</option>
                                <option value="income">Pemasukan</option>
                            </select>
                            @error('addType')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="addCategory">
                                <option value="">Pilih Kategori</option>
                                @if($addType == 'income')
                                    @foreach($incomeCategories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                @else
                                    @foreach($expenseCategories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('addCategory')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model="addAmount" 
                                   placeholder="0" step="0.01" min="0">
                            @error('addAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="addTransactionDate">
                            @error('addTransactionDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <div wire:ignore>
                            <input id="addDescription" type="hidden" name="content">
                            <trix-editor input="addDescription" 
                                         wire:model="addDescription"
                                         placeholder="Masukkan deskripsi transaksi..."></trix-editor>
                        </div>
                        @error('addDescription')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Transaksi (Gambar)</label>
                        <input type="file" class="form-control" wire:model="addReceiptImage" 
                               accept="image/*" onchange="previewImage(this, 'addPreview')">
                        @error('addReceiptImage')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        
                        @if($addReceiptImage)
                            <div class="mt-2">
                                <img id="addPreview" class="receipt-preview" style="display: none;">
                            </div>
                        @endif

                        <div wire:loading wire:target="addReceiptImage" class="mt-2">
                            <small class="text-muted">Uploading...</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target="addRecord">Simpan</span>
                        <span wire:loading wire:target="addRecord">
                            <span class="spinner-border spinner-border-sm"></span> Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('trix-change', function(event) {
        if (event.target.id === 'addDescription') {
            @this.set('addDescription', event.target.value);
        }
    });
</script>
@endpush
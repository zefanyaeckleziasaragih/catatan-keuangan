<form wire:submit.prevent="editRecord">
    <div class="modal fade" tabindex="-1" id="editRecordModal" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">✏️ Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model.live="editType">
                                <option value="expense">Pengeluaran</option>
                                <option value="income">Pemasukan</option>
                            </select>
                            @error('editType')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" wire:model="editCategory">
                                <option value="">Pilih Kategori</option>
                                @if($editType == 'income')
                                    @foreach($incomeCategories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                @else
                                    @foreach($expenseCategories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('editCategory')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" wire:model="editAmount" 
                                   placeholder="0" step="0.01" min="0">
                            @error('editAmount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" wire:model="editTransactionDate">
                            @error('editTransactionDate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <div wire:ignore>
                            <input id="editDescription" type="hidden" name="content">
                            <trix-editor input="editDescription" 
                                         wire:model="editDescription"
                                         placeholder="Masukkan deskripsi transaksi..."></trix-editor>
                        </div>
                        @error('editDescription')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Transaksi (Gambar)</label>
                        
                        @if($currentReceiptImage)
                            <div class="mb-2">
                                <p class="mb-1"><small class="text-muted">Gambar saat ini:</small></p>
                                <img src="{{ asset('storage/' . $currentReceiptImage) }}" 
                                     class="receipt-preview rounded" 
                                     alt="Current Receipt"
                                     onclick="viewImage('{{ asset('storage/' . $currentReceiptImage) }}')">
                            </div>
                        @endif

                        <input type="file" class="form-control" wire:model="editReceiptImage" 
                               accept="image/*" onchange="previewImage(this, 'editPreview')">
                        <small class="text-muted">Upload gambar baru untuk mengganti</small>
                        
                        @error('editReceiptImage')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                        
                        @if($editReceiptImage)
                            <div class="mt-2">
                                <p class="mb-1"><small class="text-muted">Preview gambar baru:</small></p>
                                <img id="editPreview" class="receipt-preview" style="display: none;">
                            </div>
                        @endif

                        <div wire:loading wire:target="editReceiptImage" class="mt-2">
                            <small class="text-muted">Uploading...</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target="editRecord">Simpan Perubahan</span>
                        <span wire:loading wire:target="editRecord">
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
        if (event.target.id === 'editDescription') {
            @this.set('editDescription', event.target.value);
        }
    });
</script>
@endpush
<div>
    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card card-stats income">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Total Pemasukan</h6>
                            <h3 class="mb-0 text-success glow-text" style="font-family: 'Playfair Display', serif;">
                                Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="text-success" style="font-size: 3rem; opacity: 0.3;">
                            ↑
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card card-stats expense">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Total Pengeluaran</h6>
                            <h3 class="mb-0 text-danger" style="font-family: 'Playfair Display', serif;">
                                Rp {{ number_format($totalExpense, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="text-danger" style="font-size: 3rem; opacity: 0.3;">
                            ↓
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card card-stats balance">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Saldo</h6>
                            <h3 class="mb-0 {{ $balance >= 0 ? 'text-success' : 'text-danger' }}" style="font-family: 'Playfair Display', serif;">
                                Rp {{ number_format($balance, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }}" style="font-size: 3rem; opacity: 0.3;">
                            💰
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Actions --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="🔍 Cari transaksi..." wire:model.live="search">
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="filterType">
                        <option value="">Semua Tipe</option>
                        <option value="income">Pemasukan</option>
                        <option value="expense">Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="filterCategory">
                        <option value="">Semua Kategori</option>
                        <optgroup label="Pemasukan">
                            @foreach($incomeCategories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Pengeluaran">
                            @foreach($expenseCategories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" placeholder="Dari" wire:model.live="filterDateFrom">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" placeholder="Sampai" wire:model.live="filterDateTo">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-secondary w-100" wire:click="resetFilters" title="Reset Filter">
                        🔄
                    </button>
                </div>
            </div>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                    ➕ Tambah Transaksi
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 10%">Tanggal</th>
                            <th style="width: 10%">Tipe</th>
                            <th style="width: 15%">Kategori</th>
                            <th style="width: 15%">Jumlah</th>
                            <th style="width: 25%">Deskripsi</th>
                            <th style="width: 10%">Bukti</th>
                            <th style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $key => $record)
                            <tr>
                                <td>{{ $records->firstItem() + $key }}</td>
                                <td>{{ $record->transaction_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge {{ $record->type == 'income' ? 'badge-income' : 'badge-expense' }}">
                                        {{ $record->type == 'income' ? '↑ Pemasukan' : '↓ Pengeluaran' }}
                                    </span>
                                </td>
                                <td>{{ $record->category }}</td>
                                <td class="{{ $record->type == 'income' ? 'text-success' : 'text-danger' }} fw-bold">
                                    Rp {{ number_format($record->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <small>{{ Str::limit(strip_tags($record->description), 50) }}</small>
                                </td>
                                <td>
                                    @if($record->receipt_image)
                                        <img src="{{ asset('storage/' . $record->receipt_image) }}" 
                                             class="receipt-preview" 
                                             alt="Receipt"
                                             onclick="viewImage('{{ asset('storage/' . $record->receipt_image) }}')">
                                    @else
                                        <small class="text-muted">Tidak ada</small>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-warning btn-edit-record me-1" 
                                            data-id="{{ $record->id }}"
                                            style="padding: 0.25rem 0.5rem;">
                                        ✏️
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger btn-delete-record" 
                                            data-id="{{ $record->id }}"
                                            style="padding: 0.25rem 0.5rem;">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div style="opacity: 0.5; font-size: 3rem;">📊</div>
                                    <p class="text-muted mb-0 mt-2">Belum ada data transaksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $records->links() }}
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('components.modals.finance.add')
    @include('components.modals.finance.edit')
    @include('components.modals.finance.delete')
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event delegation untuk tombol edit
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-edit-record')) {
                const button = e.target.closest('.btn-edit-record');
                const id = button.getAttribute('data-id');
                console.log('Edit clicked for ID:', id);
                @this.call('prepareEditRecord', id);
            }
            
            if (e.target.closest('.btn-delete-record')) {
                const button = e.target.closest('.btn-delete-record');
                const id = button.getAttribute('data-id');
                console.log('Delete clicked for ID:', id);
                @this.call('prepareDeleteRecord', id);
            }
        });
    });
</script>
@endpush
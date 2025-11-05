<div>
    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card card-stats income shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Pemasukan</h6>
                            <h3 class="mb-0 text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-arrow-up-circle" style="font-size: 2.5rem;">↑</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card card-stats expense shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Pengeluaran</h6>
                            <h3 class="mb-0 text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                        </div>
                        <div class="text-danger">
                            <i class="bi bi-arrow-down-circle" style="font-size: 2.5rem;">↓</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card card-stats balance shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Saldo</h6>
                            <h3 class="mb-0 {{ $balance >= 0 ? 'text-primary' : 'text-danger' }}">
                                Rp {{ number_format($balance, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="{{ $balance >= 0 ? 'text-primary' : 'text-danger' }}">
                            <i class="bi bi-wallet2" style="font-size: 2.5rem;">💰</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Actions --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Cari..." wire:model.live="search">
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
            <div class="mt-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                    ➕ Tambah Transaksi
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
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
                                             class="receipt-preview rounded" 
                                             alt="Receipt"
                                             onclick="viewImage('{{ asset('storage/' . $record->receipt_image) }}')">
                                    @else
                                        <small class="text-muted">Tidak ada</small>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" 
                                            wire:click="prepareEditRecord({{ $record->id }})">
                                        ✏️
                                    </button>
                                    <button class="btn btn-sm btn-danger" 
                                            wire:click="prepareDeleteRecord({{ $record->id }})">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">Belum ada data transaksi</p>
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
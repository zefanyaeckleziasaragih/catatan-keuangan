<div>
    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-stats income shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Pemasukan</h6>
                    <h4 class="mb-0 text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card card-stats expense shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Pengeluaran</h6>
                    <h4 class="mb-0 text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Pemasukan Bulan Ini</h6>
                    <h4 class="mb-0 text-success">Rp {{ number_format($thisMonthIncome, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Pengeluaran Bulan Ini</h6>
                    <h4 class="mb-0 text-danger">Rp {{ number_format($thisMonthExpense, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Pilih Tahun</label>
                    <select class="form-select" wire:model.live="selectedYear">
                        @for($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pilih Bulan</label>
                    <select class="form-select" wire:model.live="selectedMonth">
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly Chart --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Grafik Pemasukan & Pengeluaran Bulanan ({{ $selectedYear }})</h5>
        </div>
        <div class="card-body">
            <div id="monthlyChart" wire:ignore></div>
        </div>
    </div>

    {{-- Category Breakdown --}}
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Pemasukan per Kategori</h5>
                    <small>
                        {{ DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear }}
                    </small>
                </div>
                <div class="card-body">
                    <div id="incomeCategoryChart" wire:ignore></div>
                    
                    @if($incomeByCategory->count() > 0)
                        <div class="mt-3">
                            <table class="table table-sm">
                                <tbody>
                                    @foreach($incomeByCategory as $item)
                                        <tr>
                                            <td>{{ $item->category }}</td>
                                            <td class="text-end text-success fw-bold">
                                                Rp {{ number_format($item->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted my-4">Tidak ada data pemasukan</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Pengeluaran per Kategori</h5>
                    <small>
                        {{ DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear }}
                    </small>
                </div>
                <div class="card-body">
                    <div id="expenseCategoryChart" wire:ignore></div>
                    
                    @if($expenseByCategory->count() > 0)
                        <div class="mt-3">
                            <table class="table table-sm">
                                <tbody>
                                    @foreach($expenseByCategory as $item)
                                        <tr>
                                            <td>{{ $item->category }}</td>
                                            <td class="text-end text-danger fw-bold">
                                                Rp {{ number_format($item->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted my-4">Tidak ada data pengeluaran</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let monthlyChart, incomeChart, expenseChart;

    document.addEventListener('livewire:initialized', () => {
        renderCharts();
    });

    Livewire.on('chartDataUpdated', () => {
        // Destroy existing charts
        if (monthlyChart) monthlyChart.destroy();
        if (incomeChart) incomeChart.destroy();
        if (expenseChart) expenseChart.destroy();
        
        // Re-render after a short delay
        setTimeout(() => renderCharts(), 100);
    });

    function renderCharts() {
        // Monthly Chart
        const monthlyData = @json($chartData);
        
        const monthlyOptions = {
            series: [{
                name: 'Pemasukan',
                data: monthlyData.map(d => d.income)
            }, {
                name: 'Pengeluaran',
                data: monthlyData.map(d => d.expense)
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            fill: {
                opacity: 1
            },
            colors: ['#28a745', '#dc3545'],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            }
        };

        monthlyChart = new ApexCharts(document.querySelector("#monthlyChart"), monthlyOptions);
        monthlyChart.render();

        // Income Category Chart
        const incomeData = @json($incomeByCategory->values());
        if (incomeData.length > 0) {
            const incomeOptions = {
                series: incomeData.map(d => parseFloat(d.total)),
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: incomeData.map(d => d.category),
                colors: ['#28a745', '#20c997', '#17a2b8', '#6610f2', '#6f42c1'],
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            };

            incomeChart = new ApexCharts(document.querySelector("#incomeCategoryChart"), incomeOptions);
            incomeChart.render();
        }

        // Expense Category Chart
        const expenseData = @json($expenseByCategory->values());
        if (expenseData.length > 0) {
            const expenseOptions = {
                series: expenseData.map(d => parseFloat(d.total)),
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: expenseData.map(d => d.category),
                colors: ['#dc3545', '#fd7e14', '#ffc107', '#e83e8c', '#6c757d'],
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            };

            expenseChart = new ApexCharts(document.querySelector("#expenseCategoryChart"), expenseOptions);
            expenseChart.render();
        }
    }
</script>
@endpush
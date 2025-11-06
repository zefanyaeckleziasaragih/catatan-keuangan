<div>
    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card card-stats income">
                <div class="card-body">
                    <h6 class="text-muted mb-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Total Pemasukan</h6>
                    <h4 class="mb-0 text-success glow-text" style="font-family: 'Playfair Display', serif;">
                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card card-stats expense">
                <div class="card-body">
                    <h6 class="text-muted mb-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Total Pengeluaran</h6>
                    <h4 class="mb-0 text-danger" style="font-family: 'Playfair Display', serif;">
                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Pemasukan Bulan Ini</h6>
                    <h4 class="mb-0 text-success" style="font-family: 'Playfair Display', serif;">
                        Rp {{ number_format($thisMonthIncome, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Pengeluaran Bulan Ini</h6>
                    <h4 class="mb-0 text-danger" style="font-family: 'Playfair Display', serif;">
                        Rp {{ number_format($thisMonthExpense, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
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
    <div class="card mb-4">
        <div class="card-header" style="background: var(--dark-bg); border-bottom: 1px solid var(--dark-border); border-radius: 15px 15px 0 0;">
            <h5 class="mb-0" style="color: var(--neon-green); font-family: 'Playfair Display', serif;">
                Grafik Pemasukan & Pengeluaran Bulanan ({{ $selectedYear }})
            </h5>
        </div>
        <div class="card-body">
            <div id="monthlyChart" wire:ignore></div>
        </div>
    </div>

    {{-- Category Breakdown --}}
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, var(--neon-green) 0%, var(--neon-green-dark) 100%); border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0" style="color: var(--dark-bg); font-family: 'Playfair Display', serif;">
                        Pemasukan per Kategori
                    </h5>
                    <small style="color: rgba(10, 14, 39, 0.8); font-weight: 500;">
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
                                        <tr style="background: rgba(21, 27, 53, 0.5);">
                                            <td style="color: var(--text-primary); border: none; padding: 0.75rem;">{{ $item->category }}</td>
                                            <td class="text-end fw-bold" style="color: var(--neon-green); border: none; padding: 0.75rem;">
                                                Rp {{ number_format($item->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center my-4" style="color: var(--text-secondary); opacity: 0.7;">
                            <span style="font-size: 2rem; display: block; margin-bottom: 0.5rem;">📊</span>
                            Tidak ada data pemasukan
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #ff4757 0%, #ff3838 100%); border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0" style="color: white; font-family: 'Playfair Display', serif;">
                        Pengeluaran per Kategori
                    </h5>
                    <small style="color: rgba(255, 255, 255, 0.9); font-weight: 500;">
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
                                        <tr style="background: rgba(21, 27, 53, 0.5);">
                                            <td style="color: var(--text-primary); border: none; padding: 0.75rem;">{{ $item->category }}</td>
                                            <td class="text-end fw-bold" style="color: #ff4757; border: none; padding: 0.75rem;">
                                                Rp {{ number_format($item->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center my-4" style="color: var(--text-secondary); opacity: 0.7;">
                            <span style="font-size: 2rem; display: block; margin-bottom: 0.5rem;">📊</span>
                            Tidak ada data pengeluaran
                        </p>
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
                height: 350,
                background: 'transparent',
                foreColor: '#8b92b0',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 8
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
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                labels: {
                    style: {
                        colors: '#8b92b0',
                        fontSize: '12px'
                    }
                },
                axisBorder: {
                    color: '#1e2847'
                },
                axisTicks: {
                    color: '#1e2847'
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#8b92b0',
                        fontSize: '12px'
                    },
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            fill: {
                opacity: 1
            },
            colors: ['#00ff87', '#ff4757'],
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                },
                style: {
                    fontSize: '12px',
                    background: '#151b35'
                }
            },
            grid: {
                borderColor: '#1e2847',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            legend: {
                labels: {
                    colors: '#8b92b0'
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
                    height: 300,
                    background: 'transparent'
                },
                labels: incomeData.map(d => d.category),
                colors: ['#00ff87', '#00cc6d', '#00994a', '#00ffb3', '#00e07a'],
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        colors: ['#0a0e27']
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    color: '#ffffff'
                                },
                                value: {
                                    color: '#00ff87',
                                    fontSize: '20px',
                                    fontWeight: 'bold',
                                    formatter: function(val) {
                                        return 'Rp ' + parseFloat(val).toLocaleString('id-ID');
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: '#8b92b0',
                                    formatter: function(w) {
                                        const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        return 'Rp ' + total.toLocaleString('id-ID');
                                    }
                                }
                            }
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
                    height: 300,
                    background: 'transparent'
                },
                labels: expenseData.map(d => d.category),
                colors: ['#ff4757', '#ff6b81', '#ffa502', '#ff7f00', '#ff3838'],
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        colors: ['#ffffff']
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                name: {
                                    color: '#ffffff'
                                },
                                value: {
                                    color: '#ff4757',
                                    fontSize: '20px',
                                    fontWeight: 'bold',
                                    formatter: function(val) {
                                        return 'Rp ' + parseFloat(val).toLocaleString('id-ID');
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'Total',
                                    color: '#8b92b0',
                                    formatter: function(w) {
                                        const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        return 'Rp ' + total.toLocaleString('id-ID');
                                    }
                                }
                            }
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
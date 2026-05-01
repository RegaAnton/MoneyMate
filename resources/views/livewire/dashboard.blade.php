<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Expense;
use App\Models\Category;
use App\Models\CategoryBudget;
use Illuminate\Support\Carbon;

new #[Layout('components.layouts.app')] #[Title('Dashboard - MoneyMate')] class extends Component {
    public string $filter = 'bulan';
    public $startDate, $endDate;
    public array $chartData = [];

    public function mount()
    {
        $this->generateChart();
    }

    public function setFilter($val)
    {
        $this->filter = $val;
        if ($val !== 'custom') {
            $this->generateChart();
        }
    }

    public function applyCustomFilter()
    {
        $this->generateChart();
    }

    public function generateChart()
    {
        $query = Expense::where('user_id', auth()->id());
        $tz = 'Asia/Jakarta';

        if ($this->filter === 'hari') {
            $query->whereDate('date', Carbon::today($tz));
        } elseif ($this->filter === 'minggu') {
            $query->whereBetween('date', [Carbon::now($tz)->startOfWeek(Carbon::MONDAY)->toDateString(), Carbon::now($tz)->endOfWeek(Carbon::SUNDAY)->toDateString()]);
        } elseif ($this->filter === 'bulan') {
            $query->whereMonth('date', Carbon::now($tz)->month)->whereYear('date', Carbon::now($tz)->year);
        } elseif ($this->filter === 'ytd') {
            $query->whereYear('date', Carbon::now($tz)->year);
        } elseif ($this->filter === 'custom' && $this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }

        $expenses = $query->get();
        $categories = Category::all();
        $data = [];

        foreach ($categories as $cat) {
            $sum = $expenses->where('category_id', $cat->id)->sum('amount');
            if ($sum > 0) {
                $data[$cat->name] = $sum;
            }
        }

        arsort($data);

        $this->chartData = $data;
        $this->dispatch('chart-updated');
    }

    public function with()
    {
        $user = auth()->user();
        $query = Expense::where('user_id', $user->id);
        $tz = 'Asia/Jakarta';

        if ($this->filter === 'hari') {
            $query->whereDate('date', Carbon::today($tz));
        } elseif ($this->filter === 'minggu') {
            $query->whereBetween('date', [Carbon::now($tz)->startOfWeek(Carbon::MONDAY)->toDateString(), Carbon::now($tz)->endOfWeek(Carbon::SUNDAY)->toDateString()]);
        } elseif ($this->filter === 'bulan') {
            $query->whereMonth('date', Carbon::now($tz)->month)->whereYear('date', Carbon::now($tz)->year);
        } elseif ($this->filter === 'ytd') {
            $query->whereYear('date', Carbon::now($tz)->year);
        } elseif ($this->filter === 'custom' && $this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }

        $totalAmount = $query->sum('amount');

        $currentMonthExpenses = $totalAmount;

        $budget = $user->monthly_budget;
        $budgetPercentage = $budget > 0 ? ($currentMonthExpenses / $budget) * 100 : 0;

        // Query Target Kategori (mengikuti filter yang dipilih)
        $monthlyExpensesByCategory = (clone $query)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $categoryBudgets = CategoryBudget::with('category')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($cb) use ($monthlyExpensesByCategory) {
                $spent = $monthlyExpensesByCategory[$cb->category_id] ?? 0;
                $percentage = $cb->amount > 0 ? ($spent / $cb->amount) * 100 : 0;

                $cb->spent = $spent;
                $cb->percentage = min(100, round($percentage));
                $cb->is_over = $spent > $cb->amount;

                if ($percentage >= 100) {
                    $cb->color = 'bg-danger';
                } elseif ($percentage >= 75) {
                    $cb->color = 'bg-warning';
                } else {
                    $cb->color = 'bg-success';
                }

                return $cb;
            });

        return [
            'totalAmount' => $totalAmount,
            'budget' => $budget,
            'currentMonthExpenses' => $currentMonthExpenses,
            'budgetPercentage' => $budgetPercentage,
            'categoryBudgets' => $categoryBudgets,
        ];
    }
}; ?>

<div>
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <style>
        .premium-card {
            background: rgba(var(--bs-body-bg-rgb), 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(var(--bs-border-color-rgb), 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .premium-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        }

        .filter-pill-container {
            background: var(--bs-tertiary-bg);
            border: 1px solid rgba(var(--bs-border-color-rgb), 0.5);
            padding: 4px;
            border-radius: 50px;
            display: inline-flex;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }

        .filter-btn {
            border: none;
            background: transparent;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--bs-body-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }

        .filter-btn.active {
            background: var(--bs-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
            font-weight: 700;
        }

        .custom-range-card {
            background: var(--bs-body-bg);
            border: 1px dashed var(--bs-primary);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-premium-gradient {
            background: linear-gradient(135deg, var(--bs-primary), #6f42c1);
            border: none;
            color: white;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 animate__animated animate__fadeInDown">
        <div>
            <h4 class="fw-bold m-0 text-body display-6" style="font-size: 1.75rem;">Ringkasan Keuangan</h4>
            <p class="text-muted small m-0 mt-1">Pantau arus kas Anda dengan intelijen visual.</p>
        </div>
        <div class="filter-pill-container hide-scrollbar overflow-auto">
            @foreach(['hari' => 'Hari', 'minggu' => 'Minggu', 'bulan' => 'Bulan', 'ytd' => 'Tahun', 'custom' => 'Kustom'] as $key => $label)
                <button wire:click="setFilter('{{ $key }}')"
                    class="filter-btn {{ $filter == $key ? 'active' : '' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    @if ($filter === 'custom')
        <div class="custom-range-card animate__animated animate__fadeIn">
            <div class="row g-3 align-items-end">
                <div class="col-6 col-md-4">
                    <label class="form-label text-muted small fw-bold">Tanggal Mulai</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body-tertiary border-0 rounded-start-3"><i class="fa-regular fa-calendar-plus text-primary"></i></span>
                        <input type="date" wire:model="startDate" class="form-control border-0 bg-body-tertiary rounded-end-3">
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <label class="form-label text-muted small fw-bold">Tanggal Selesai</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-body-tertiary border-0 rounded-start-3"><i class="fa-regular fa-calendar-minus text-danger"></i></span>
                        <input type="date" wire:model="endDate" class="form-control border-0 bg-body-tertiary rounded-end-3">
                    </div>
                </div>
                <div class="col-12 col-md-4 mt-md-0">
                    <button wire:click="applyCustomFilter" class="btn btn-sm btn-premium-gradient w-100 rounded-pill py-2 shadow-sm">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> Terapkan Filter
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden premium-card animate__animated animate__zoomIn">
        <div class="card-body p-4 p-md-5 position-relative">
            <div class="position-relative z-1 text-center text-md-start">
                <span
                    class="badge bg-primary-subtle text-primary fw-medium px-3 py-2 rounded-pill mb-3 border border-primary-subtle">
                    <i class="fa-regular fa-calendar me-1"></i>
                    {{ ['hari' => 'Hari Ini', 'minggu' => 'Minggu Ini', 'bulan' => 'Bulan Ini', 'ytd' => 'Tahun Ini', 'custom' => 'Rentang Kustom'][$filter] }}
                    @if ($filter === 'custom' && $startDate && $endDate)
                        <span class="ms-1 opacity-75">({{ \Carbon\Carbon::parse($startDate)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/y') }})</span>
                    @endif
                </span>

                <div wire:loading.class="d-none" wire:target="setFilter">
                    <h1 class="display-4 fw-bold text-body mb-0" style="letter-spacing: -1px;">Rp
                        {{ number_format($totalAmount, 0, ',', '.') }}</h1>
                </div>

                <div class="d-none placeholder-glow mt-1" wire:loading.class.remove="d-none" wire:target="setFilter">
                    <span class="placeholder rounded-3 bg-secondary opacity-25"
                        style="height: 55px; width: 300px;"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="card h-100 border-0 shadow-sm rounded-4 premium-card animate__animated animate__fadeInLeft">
                <div class="card-body p-4 d-flex flex-column">
                    <h6 class="fw-bold text-body mb-4">Distribusi Pengeluaran</h6>
                    <div class="chart-container flex-grow-1 d-flex align-items-center justify-content-center"
                        style="min-height: 280px;" wire:ignore>
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card h-100 border-0 shadow-sm rounded-4 premium-card animate__animated animate__fadeInRight">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-body mb-4">Rincian Kategori</h6>

                    <div wire:loading.class="d-none" wire:target="setFilter" class="pe-md-2 hide-scrollbar"
                        style="max-height: 280px; overflow-y: auto; overflow-x: hidden;">
                        @forelse($chartData as $categoryName => $amount)
                            @php
                                $percentage = $totalAmount > 0 ? ($amount / $totalAmount) * 100 : 0;
                                $colors = ['#0d6efd', '#20c997', '#ffc107', '#dc3545', '#0dcaf0', '#6f42c1', '#fd7e14'];
                                $color = $colors[$loop->index % count($colors)];
                            @endphp
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-medium text-body d-flex align-items-center">
                                        <span class="d-inline-block rounded-circle me-2 shadow-sm"
                                            style="width: 12px; height: 12px; background-color: {{ $color }};"></span>
                                        {{ $categoryName }}
                                    </span>
                                    <span class="fw-bold text-body">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div class="progress flex-grow-1 bg-body-tertiary"
                                        style="height: 8px; border-radius: 10px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $percentage }}%; background-color: {{ $color }}; border-radius: 10px;">
                                        </div>
                                    </div>
                                    <span class="text-muted small fw-medium"
                                        style="min-width: 45px; text-align: right;">{{ number_format($percentage, 1) }}%</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5 mt-3">
                                <div class="bg-body-tertiary rounded-circle d-inline-flex p-4 mb-3 border">
                                    <i class="fa-solid fa-receipt fa-2x opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-body mb-1">Belum ada pengeluaran</h6>
                                <p class="small mb-0">Tetap hemat dan pantau keuanganmu di sini.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="pe-md-2 d-none placeholder-glow mt-2" wire:loading.class.remove="d-none"
                        wire:target="setFilter">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="mb-4 pb-1">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="placeholder col-4 rounded bg-secondary opacity-25"></span>
                                    <span class="placeholder col-3 rounded bg-secondary opacity-25"></span>
                                </div>
                                <span class="placeholder col-12 rounded bg-secondary opacity-25"
                                    style="height: 8px;"></span>
                            </div>
                        @endfor
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if ($budget > 0 || $categoryBudgets->count() > 0)
        <div class="mt-2 animate__animated animate__fadeInUp">
            <h5 class="fw-bold mb-3 text-body">Status Anggaran ({{ ['hari' => 'Hari Ini', 'minggu' => 'Minggu Ini', 'bulan' => 'Bulan Ini', 'ytd' => 'Tahun Ini', 'custom' => 'Periode Kustom'][$filter] }})</h5>

            @if ($budget > 0)
                @if ($budgetPercentage >= 100)
                    <div class="alert alert-danger d-flex align-items-center shadow-sm mb-4 rounded-4 border-0">
                        <i class="fa-solid fa-triangle-exclamation fa-2x me-3"></i>
                        <div>
                            <strong class="d-block mb-1">Overbudget!</strong>
                            Pengeluaran periode ini (Rp {{ number_format($currentMonthExpenses, 0, ',', '.') }}) melebihi
                            anggaran (Rp {{ number_format($budget, 0, ',', '.') }}).
                        </div>
                    </div>
                @elseif($budgetPercentage >= 80)
                    <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4 rounded-4 border-0">
                        <i class="fa-solid fa-circle-exclamation fa-2x me-3"></i>
                        <div>
                            <strong class="d-block mb-1">Peringatan Anggaran</strong>
                            Pengeluaran mencapai {{ number_format($budgetPercentage, 1) }}% dari target periode ini.
                        </div>
                    </div>
                @else
                    <div class="alert alert-info d-flex align-items-center shadow-sm mb-4 rounded-4 border-0">
                        <i class="fa-solid fa-bullseye fa-2x me-3"></i>
                        <div>
                            <strong class="d-block mb-1">Status Anggaran: Aman</strong>
                            Sisa anggaran Anda untuk periode ini adalah Rp
                            {{ number_format($budget - $currentMonthExpenses, 0, ',', '.') }}.
                        </div>
                    </div>
                @endif
            @endif

            @if ($categoryBudgets->count() > 0)
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-transparent border-0 p-4 pb-0">
                        <h6 class="fw-bold m-0 text-body"><i class="fa-solid fa-crosshairs text-danger me-2"></i> Rapor
                            Target Kategori Spesifik</h6>
                    </div>
                    <div class="card-body p-4 pt-3">
                        <div class="row">
                            @foreach ($categoryBudgets as $cb)
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex justify-content-between align-items-end mb-2">
                                        <span class="fw-bold text-body"
                                            style="font-size: 0.95rem;">{{ $cb->category->name }}</span>
                                        <span class="small text-muted fw-bold">Rp
                                            {{ number_format($cb->spent, 0, ',', '.') }} <span
                                                class="fw-normal opacity-75">/
                                                {{ number_format($cb->amount, 0, ',', '.') }}</span></span>
                                    </div>
                                    <div class="progress rounded-pill bg-body-tertiary border" style="height: 12px;">
                                        <div class="progress-bar {{ $cb->color }} progress-bar-striped progress-bar-animated rounded-pill"
                                            role="progressbar" style="width: {{ $cb->percentage }}%"></div>
                                    </div>
                                    @if ($cb->is_over)
                                        <div
                                            class="text-danger small mt-2 fw-bold bg-danger-subtle px-2 py-1 rounded d-inline-block">
                                            <i class="fa-solid fa-triangle-exclamation"></i> Overbudget Rp
                                            {{ number_format($cb->spent - $cb->amount, 0, ',', '.') }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

</div>

@script
    <script>
        let chartInstance = null;
        const renderChart = (data) => {
            const ctx = document.getElementById('expenseChart').getContext('2d');
            if (chartInstance) chartInstance.destroy();
            const labels = Object.keys(data).length ? Object.keys(data) : ["Belum ada data"];
            const values = Object.values(data).length ? Object.values(data) : [1];
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            const colors = Object.values(data).length ? ["#0d6efd", "#20c997", "#ffc107", "#dc3545", "#0dcaf0",
                "#6f42c1", "#fd7e14"
            ] : [isDark ? "#2b2b2b" : "#f8f9fa"];

            chartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderWidth: isDark ? 4 : 4,
                        borderColor: isDark ? '#212529' : '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    layout: {
                        padding: 10
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: isDark ? 'rgba(0, 0, 0, 0.8)' : 'rgba(255, 255, 255, 0.9)',
                            titleColor: isDark ? '#fff' : '#000',
                            bodyColor: isDark ? '#fff' : '#000',
                            borderColor: isDark ? '#333' : '#ddd',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed !== null) label += 'Rp ' + context.parsed
                                        .toLocaleString('id-ID');
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        };
        renderChart($wire.chartData);
        $wire.on('chart-updated', () => {
            renderChart($wire.chartData);
        });
        document.getElementById('themeToggle').addEventListener('click', () => {
            setTimeout(() => renderChart($wire.chartData), 50);
        });
    </script>
@endscript

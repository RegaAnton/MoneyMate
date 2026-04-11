<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $date, $category_id, $amount, $note;
    public string $filter = 'bulan';
    public string $sortDir = 'desc';

    public $filterCategory = '';

    public function mount()
    {
        $this->date = now('Asia/Jakarta')->format('Y-m-d');
        if (Category::count() == 0) {
            $defaultCategories = ['Makanan', 'Transportasi', 'Hiburan', 'Tagihan', 'Belanja', 'Lainnya'];
            foreach ($defaultCategories as $cat) {
                Category::create(['name' => $cat]);
            }
        }
    }

    public function setFilter($val)
    {
        $this->filter = $val;
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function toggleSort()
    {
        $this->sortDir = $this->sortDir === 'desc' ? 'asc' : 'desc';
    }

    public function save()
    {
        $this->validate(
            [
                'date' => 'required|date',
                'category_id' => 'required|exists:categories,id',
                'amount' => 'required',
            ],
            [
                'category_id.required' => 'Silakan pilih kategori dari daftar.',
                'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            ],
        );

        $cleanAmount = (int) str_replace('.', '', $this->amount);
        Expense::create([
            'user_id' => auth()->id(),
            'category_id' => $this->category_id,
            'amount' => $cleanAmount,
            'date' => $this->date,
            'note' => $this->note,
        ]);

        $this->reset(['amount', 'note', 'category_id']);
        $this->date = now('Asia/Jakarta')->format('Y-m-d');

        $this->dispatch('transaction-saved');
        $this->dispatch('show-toast', message: 'Transaksi berhasil dicatat!');
    }

    public function delete($id)
    {
        Expense::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();
        $this->dispatch('show-toast', message: 'Transaksi berhasil dihapus!', icon: 'success');
    }

    protected function getFilteredQuery()
    {
        $query = Expense::where('user_id', auth()->id())->with('category');
        $tz = 'Asia/Jakarta';

        if ($this->filter === 'hari') {
            $query->whereDate('date', Carbon::today($tz));
        } elseif ($this->filter === 'minggu') {
            $query->whereBetween('date', [Carbon::now($tz)->startOfWeek(Carbon::MONDAY)->toDateString(), Carbon::now($tz)->endOfWeek(Carbon::SUNDAY)->toDateString()]);
        } elseif ($this->filter === 'bulan') {
            $query->whereMonth('date', Carbon::now($tz)->month)->whereYear('date', Carbon::now($tz)->year);
        } elseif ($this->filter === 'ytd') {
            $query->whereYear('date', Carbon::now($tz)->year);
        }

        if ($this->filterCategory !== '') {
            $query->where('category_id', $this->filterCategory);
        }

        return $query->orderBy('date', $this->sortDir)->orderBy('created_at', $this->sortDir);
    }

    protected function getPeriodeText()
    {
        $tz = 'Asia/Jakarta';
        $now = Carbon::now($tz);
        $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $text = '';
        if ($this->filter === 'hari') {
            $text = 'Periode tanggal ' . $now->format('d ') . $bulanIndo[$now->month - 1] . $now->format(' Y');
        } elseif ($this->filter === 'minggu') {
            $start = $now->copy()->startOfWeek(Carbon::MONDAY);
            $end = $now->copy()->endOfWeek(Carbon::SUNDAY);
            if ($start->month === $end->month) {
                $text = 'Periode tanggal ' . $start->format('d') . '-' . $end->format('d ') . $bulanIndo[$end->month - 1] . $end->format(' Y');
            } else {
                $text = 'Periode tanggal ' . $start->format('d ') . $bulanIndo[$start->month - 1] . ' - ' . $end->format('d ') . $bulanIndo[$end->month - 1] . $end->format(' Y');
            }
        } elseif ($this->filter === 'bulan') {
            $text = 'Periode ' . $bulanIndo[$now->month - 1] . $now->format(' Y');
        } elseif ($this->filter === 'ytd') {
            $text = 'Periode tahun ' . $now->format('Y');
        }

        if ($this->filterCategory !== '') {
            $categoryName = Category::find($this->filterCategory)->name ?? '';
            $text .= ' | Kategori: ' . strtoupper($categoryName);
        }

        return $text;
    }

    public function with()
    {
        return [
            'expensesList' => $this->getFilteredQuery()->paginate(10),
            'categories' => Category::orderBy('name')->get(),
        ];
    }

    public function exportPDF()
    {
        $expenses = $this->getFilteredQuery()->get();
        $total = $expenses->sum('amount');
        $pdf = Pdf::loadView('exports.pdf', ['expenses' => $expenses, 'total' => $total, 'periodeText' => $this->getPeriodeText()]);
        $filename = 'Laporan_MoneyMate_' . now('Asia/Jakarta')->format('Ymd_His') . '.pdf';
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
}; ?>

<div>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold m-0 text-body">Pencatatan Transaksi</h4>
            <p class="text-muted small m-0 mt-1">Catat dan kelola pengeluaran Anda.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-body mb-4">Catat Pengeluaran</h6>
                    <form wire:submit.prevent="save">

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">Tanggal</label>
                            <input type="date" wire:model="date"
                                class="form-control rounded-3 bg-body-tertiary border-0" required />
                        </div>

                        <div class="mb-3 position-relative" x-data="{ open: false, search: '', selectCategory(id, name) { $wire.category_id = id;
                                this.search = name;
                                this.open = false; } }"
                            @transaction-saved.window="search = ''; open = false"
                            @click.outside="open = false; if(!$wire.category_id) search = ''">

                            <label class="form-label text-muted small fw-medium">Kategori</label>
                            <div class="position-relative">
                                <input type="text" x-model="search" @focus="open = true"
                                    @input="$wire.category_id = null; open = true"
                                    class="form-control rounded-3 bg-body-tertiary border-0 @error('category_id') is-invalid @enderror"
                                    placeholder="Ketik untuk mencari kategori..." autocomplete="off" />
                                <div class="position-absolute top-50 end-0 translate-middle-y pe-3 text-muted"
                                    style="pointer-events: none;"><i class="fa-solid fa-chevron-down small"></i></div>
                            </div>
                            @error('category_id')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror

                            <div x-show="open" x-transition.opacity.duration.200ms
                                class="list-group position-absolute w-100 shadow-lg mt-1 z-3 border-0 rounded-3"
                                style="max-height: 200px; overflow-y: auto; display: none; top: 100%;">
                                @foreach ($categories as $cat)
                                    <button type="button"
                                        x-show="search === '' || '{{ strtolower(addslashes($cat->name)) }}'.includes(search.toLowerCase())"
                                        @click="selectCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                                        class="list-group-item list-group-item-action border-0 border-bottom py-2 px-3 text-start bg-body">{{ $cat->name }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">Jumlah (Rp)</label>
                            <input type="text" inputmode="numeric" wire:model="amount" oninput="formatRibuan(this)"
                                class="form-control rounded-3 bg-body-tertiary border-0" placeholder="Contoh: 50.000"
                                required />
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-medium">Catatan (Opsional)</label>
                            <input type="text" wire:model="note"
                                class="form-control rounded-3 bg-body-tertiary border-0" placeholder="Makan siang..." />
                        </div>

                        <button type="submit"
                            class="btn btn-primary w-100 fw-bold py-2 rounded-pill shadow-sm transition-all">
                            <span wire:loading.remove wire:target="save"><i class="fa-solid fa-plus me-1"></i> Simpan
                                Transaksi</span>
                            <span wire:loading wire:target="save"><i class="fa-solid fa-spinner fa-spin"></i>
                                Menyimpan...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-transparent border-0 p-4 pb-2">
                    <h6 class="fw-bold m-0 text-body">Riwayat Transaksi</h6>
                </div>

                <div class="card-body p-4 pt-2">

                    <div
                        class="p-3 mb-4 bg-body-tertiary border shadow-sm rounded-4 d-flex flex-column flex-lg-row gap-3 align-items-lg-center justify-content-between">

                        <div class="bg-body p-1 rounded-pill d-flex border shadow-sm w-100 w-lg-auto overflow-auto hide-scrollbar"
                            style="flex-wrap: nowrap;">
                            <button wire:click="setFilter('hari')"
                                class="btn rounded-pill flex-fill text-nowrap px-2 px-md-3 {{ $filter == 'hari' ? 'btn-primary shadow-sm fw-bold' : 'btn-link text-body text-decoration-none fw-medium opacity-75' }} transition-all"
                                style="font-size: 0.875rem; padding-top: 0.4rem; padding-bottom: 0.4rem;">Hari</button>
                            <button wire:click="setFilter('minggu')"
                                class="btn rounded-pill flex-fill text-nowrap px-2 px-md-3 {{ $filter == 'minggu' ? 'btn-primary shadow-sm fw-bold' : 'btn-link text-body text-decoration-none fw-medium opacity-75' }} transition-all"
                                style="font-size: 0.875rem; padding-top: 0.4rem; padding-bottom: 0.4rem;">Minggu</button>
                            <button wire:click="setFilter('bulan')"
                                class="btn rounded-pill flex-fill text-nowrap px-2 px-md-3 {{ $filter == 'bulan' ? 'btn-primary shadow-sm fw-bold' : 'btn-link text-body text-decoration-none fw-medium opacity-75' }} transition-all"
                                style="font-size: 0.875rem; padding-top: 0.4rem; padding-bottom: 0.4rem;">Bulan</button>
                            <button wire:click="setFilter('ytd')"
                                class="btn rounded-pill flex-fill text-nowrap px-2 px-md-3 {{ $filter == 'ytd' ? 'btn-primary shadow-sm fw-bold' : 'btn-link text-body text-decoration-none fw-medium opacity-75' }} transition-all"
                                style="font-size: 0.875rem; padding-top: 0.4rem; padding-bottom: 0.4rem;">Tahun</button>
                        </div>

                        <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-lg-auto align-items-stretch">
                            <select wire:model.live="filterCategory"
                                class="form-select rounded-pill border shadow-sm px-4 bg-body text-body fw-medium"
                                style="font-size: 0.875rem; min-height: 40px; cursor: pointer;">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>

                            <button wire:click="exportPDF"
                                class="btn btn-danger rounded-pill fw-bold px-4 shadow-sm transition-all text-nowrap d-flex align-items-center justify-content-center"
                                style="font-size: 0.875rem; min-height: 40px;">
                                <span wire:loading.remove wire:target="exportPDF"><i
                                        class="fa-solid fa-file-pdf me-2"></i> Ekspor PDF</span>
                                <span wire:loading wire:target="exportPDF"><i
                                        class="fa-solid fa-spinner fa-spin me-2"></i> Ekspor...</span>
                            </button>
                        </div>

                    </div>

                    <div class="table-responsive hide-scrollbar border rounded-4">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="text-muted small bg-body-tertiary">
                                <tr>
                                    <th class="ps-4 border-0 fw-medium py-3" style="cursor: pointer;"
                                        wire:click="toggleSort">Tanggal @if ($sortDir === 'desc')
                                            <i class="fa-solid fa-sort-down ms-1 text-primary"></i>
                                        @else
                                            <i class="fa-solid fa-sort-up ms-1 text-primary"></i>
                                        @endif
                                    </th>
                                    <th class="border-0 fw-medium py-3">Kategori</th>
                                    <th class="border-0 fw-medium py-3">Catatan</th>
                                    <th class="border-0 fw-medium py-3">Jumlah</th>
                                    <th class="text-end pe-4 border-0 fw-medium py-3">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="border-top-0" wire:loading.class="d-none"
                                wire:target="setFilter, toggleSort, previousPage, nextPage, gotoPage, filterCategory">
                                @forelse($expensesList as $exp)
                                    <tr>
                                        <td class="ps-4 py-3">{{ $exp->date->format('d/m/Y') }}</td>
                                        <td class="py-3"><span
                                                class="badge bg-body-tertiary text-body fw-medium border px-2 py-1">{{ $exp->category->name }}</span>
                                        </td>
                                        <td class="text-truncate text-muted py-3" style="max-width:150px">
                                            {{ $exp->note ?? '-' }}</td>
                                        <td class="fw-bold text-body py-3">Rp
                                            {{ number_format($exp->amount, 0, ',', '.') }}</td>
                                        <td class="text-end pe-4 py-3">
                                            <button wire:click="delete({{ $exp->id }})"
                                                wire:confirm="Hapus data ini?"
                                                class="btn btn-sm btn-light text-danger rounded-circle border-0"
                                                style="width: 32px; height: 32px;"><i
                                                    class="fa-solid fa-trash-can"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted"><i
                                                class="fa-solid fa-file-invoice-dollar fa-2x mb-3 opacity-50 d-block"></i>Belum
                                            ada transaksi pada periode/kategori ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tbody class="border-top-0 d-none" wire:loading.class.remove="d-none"
                                wire:target="setFilter, toggleSort, previousPage, nextPage, gotoPage, filterCategory">
                                @for ($i = 0; $i < 5; $i++)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <p class="placeholder-glow m-0"><span
                                                    class="placeholder col-8 rounded bg-secondary opacity-25"></span>
                                            </p>
                                        </td>
                                        <td class="py-3">
                                            <p class="placeholder-glow m-0"><span
                                                    class="placeholder col-10 rounded bg-secondary opacity-25"></span>
                                            </p>
                                        </td>
                                        <td class="py-3">
                                            <p class="placeholder-glow m-0"><span
                                                    class="placeholder col-12 rounded bg-secondary opacity-25"></span>
                                            </p>
                                        </td>
                                        <td class="py-3">
                                            <p class="placeholder-glow m-0"><span
                                                    class="placeholder col-8 rounded bg-secondary opacity-25"></span>
                                            </p>
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <p class="placeholder-glow m-0"><span
                                                    class="placeholder col-4 rounded-circle bg-secondary opacity-25"
                                                    style="width:30px; height:30px;"></span></p>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>

                        </table>
                    </div>
                </div>
                @if ($expensesList->hasPages())
                    <div class="card-footer bg-transparent border-top p-3 pb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Urutan:
                                {{ $sortDir === 'desc' ? 'Terbaru' : 'Terlama' }}</span>
                            <div class="pagination-rounded">{{ $expensesList->links() }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .pagination-rounded .pagination {
        margin-bottom: 0;
    }

    .pagination-rounded .page-link {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 3px;
        border: none;
        background: transparent;
        color: var(--bs-body-color);
        font-weight: 500;
    }

    .pagination-rounded .page-item.active .page-link {
        background-color: var(--bs-primary);
        color: white;
        box-shadow: 0 0.125rem 0.25rem rgba(var(--bs-primary-rgb), 0.4);
    }

    .pagination-rounded .page-item.disabled .page-link {
        opacity: 0.5;
    }
</style>

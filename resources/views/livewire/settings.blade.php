<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Category;
use App\Models\CategoryBudget;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;

new #[Layout('components.layouts.app')] #[Title('Settings - MoneyMate')] class extends Component {
    public $name, $username, $email, $old_password, $new_password;

    public $monthly_budget;
    public $budget_category_id = '';
    public $budget_amount = '';

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->monthly_budget = $user->monthly_budget > 0 ? number_format($user->monthly_budget, 0, ',', '.') : '';
    }

    public function save()
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|min:3',
            'username' => ['required', 'min:3', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->name = $this->name;
        $user->username = $this->username;
        $user->email = $this->email;

        if (!empty($this->new_password)) {
            $this->validate([
                'old_password' => 'required',
                'new_password' => 'required|min:6',
            ]);

            if (!Hash::check($this->old_password, $user->password)) {
                $this->addError('old_password', 'Password lama salah!');
                return;
            }

            $user->password = Hash::make($this->new_password);
        }

        $user->save();

        session()->flash('success', 'Profil dan keamanan berhasil diperbarui!');
        return $this->redirect(route('settings'), navigate: true);
    }

    public function saveGlobalBudget()
    {
        $user = auth()->user();
        $cleanBudget = (int) str_replace('.', '', $this->monthly_budget);

        $user->monthly_budget = $cleanBudget;
        $user->save();

        $this->dispatch('show-toast', message: 'Anggaran bulanan global berhasil disimpan!');
    }

    public function saveCategoryBudget()
    {
        $this->validate(
            [
                'budget_category_id' => 'required|exists:categories,id',
                'budget_amount' => 'required',
            ],
            [
                'budget_category_id.required' => 'Silakan pilih kategori terlebih dahulu.',
            ],
        );

        $cleanAmount = (int) str_replace('.', '', $this->budget_amount);

        CategoryBudget::updateOrCreate(['user_id' => auth()->id(), 'category_id' => $this->budget_category_id], ['amount' => $cleanAmount]);

        $this->reset(['budget_category_id', 'budget_amount']);
        $this->dispatch('budget-saved');
        $this->dispatch('show-toast', message: 'Target anggaran kategori berhasil disimpan!');
    }

    public function deleteCategoryBudget($id)
    {
        CategoryBudget::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();
        $this->dispatch('show-toast', message: 'Target anggaran kategori dihapus!', icon: 'success');
    }

    public function with()
    {
        return [
            'categories' => Category::orderBy('name')->get(),
            'categoryBudgets' => CategoryBudget::with('category')
                ->where('user_id', auth()->id())
                ->get(),
        ];
    }
}; ?>

<div>
    <div class="mb-4">
        <h4 class="fw-bold m-0 text-body">Pengaturan Akun</h4>
        <p class="text-muted small m-0 mt-1">Sesuaikan profil dan target keuangan Anda.</p>
    </div>

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 p-md-5">

                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <i class="fa-regular fa-id-badge fs-5"></i>
                            </div>
                            <h6 class="fw-bold m-0">Informasi Dasar</h6>
                        </div>

                        <div class="row g-3 mb-2">
                            <div class="col-md-12">
                                <label class="form-label text-muted small fw-medium">Nama Lengkap</label>
                                <input type="text" wire:model="name"
                                    class="form-control rounded-3 bg-body-tertiary border-0 px-3 py-2 @error('name') is-invalid @enderror"
                                    required />
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-medium">Username</label>
                                <input type="text" wire:model="username"
                                    class="form-control rounded-3 bg-body-tertiary border-0 px-3 py-2 @error('username') is-invalid @enderror"
                                    required />
                                @error('username')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-medium">Alamat Email</label>
                                <input type="email" wire:model="email"
                                    class="form-control rounded-3 bg-body-tertiary border-0 px-3 py-2 @error('email') is-invalid @enderror"
                                    required />
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <i class="fa-solid fa-shield-halved fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold m-0">Keamanan Akun</h6>
                                <span class="small text-muted">Biarkan kosong jika tidak diubah</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">Password Lama</label>
                            <input type="password" wire:model="old_password"
                                class="form-control rounded-3 bg-body-tertiary border-0 px-3 py-2 @error('old_password') is-invalid @enderror"
                                placeholder="••••••••" />
                            @error('old_password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">Password Baru</label>
                            <input type="password" wire:model="new_password"
                                class="form-control rounded-3 bg-body-tertiary border-0 px-3 py-2 @error('new_password') is-invalid @enderror"
                                placeholder="••••••••" />
                            @error('new_password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="btn btn-primary w-100 fw-bold py-3 rounded-4 shadow-sm transition-all fs-6">
                    <span wire:loading.remove wire:target="save"><i class="fa-solid fa-floppy-disk me-2"></i> Simpan
                        Profil & Keamanan</span>
                    <span wire:loading wire:target="save"><i class="fa-solid fa-spinner fa-spin me-2"></i>
                        Memproses...</span>
                </button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                        <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 48px; height: 48px;">
                            <i class="fa-solid fa-bullseye fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold m-0">Target & Manajemen Anggaran</h5>
                            <span class="small text-muted">Atur batas maksimal pengeluaran bulanan agar keuangan tetap
                                sehat.</span>
                        </div>
                    </div>

                    <div class="row g-4">

                        <div class="col-lg-4 border-end-lg pe-lg-4">
                            <form wire:submit.prevent="saveGlobalBudget"
                                class="bg-body-tertiary p-4 rounded-4 border h-100 d-flex flex-column">
                                <h6 class="fw-bold text-body mb-2"><i
                                        class="fa-solid fa-globe text-primary me-2"></i>Anggaran Global</h6>
                                <p class="small text-muted mb-4">Batas maksimal total semua pengeluaran dalam satu
                                    bulan.</p>

                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-medium m-0 mb-2">Target Bulanan
                                        (Rp)</label>
                                    <div class="input-group shadow-sm rounded-3">
                                        <span
                                            class="input-group-text bg-body border-0 text-muted fw-bold ps-3">Rp</span>
                                        <input type="text" inputmode="numeric" wire:model="monthly_budget"
                                            oninput="formatRibuan(this)"
                                            class="form-control bg-body border-0 py-2 fs-6 fw-bold" placeholder="0" />
                                    </div>
                                    <div class="form-text mt-2" style="font-size: 0.75rem;">Kosongkan atau isi 0 jika
                                        tidak ingin dibatasi.</div>
                                </div>

                                <button type="submit"
                                    class="btn btn-outline-primary w-100 fw-bold py-2 rounded-pill transition-all mt-auto shadow-sm">
                                    <span wire:loading.remove wire:target="saveGlobalBudget"><i
                                            class="fa-solid fa-check me-1"></i> Simpan Target Global</span>
                                    <span wire:loading wire:target="saveGlobalBudget"><i
                                            class="fa-solid fa-spinner fa-spin"></i> Menyimpan...</span>
                                </button>
                            </form>
                        </div>

                        <div class="col-lg-8 ps-lg-4">
                            <h6 class="fw-bold text-body mb-2"><i
                                    class="fa-solid fa-layer-group text-success me-2"></i>Anggaran per Kategori</h6>
                            <p class="small text-muted mb-4">Atur batas pengeluaran spesifik untuk kategori tertentu
                                (Misal: Khusus Makanan & Minuman).</p>

                            <form wire:submit.prevent="saveCategoryBudget"
                                class="bg-body-tertiary p-3 rounded-4 border mb-4">
                                <div class="row g-2 align-items-end">

                                    <div class="col-md-5 position-relative" x-data="{
                                        open: false,
                                        search: '',
                                        selectCategory(id, name) {
                                            $wire.budget_category_id = id;
                                            this.search = name;
                                            this.open = false;
                                        }
                                    }"
                                        @budget-saved.window="search = ''; open = false"
                                        @click.outside="open = false; if(!$wire.budget_category_id) search = ''">

                                        <label class="form-label text-muted small fw-medium m-0 mb-1">Pilih
                                            Kategori</label>
                                        <div class="position-relative">
                                            <input type="text" x-model="search" @focus="open = true"
                                                @input="$wire.budget_category_id = null; open = true"
                                                class="form-control border-0 shadow-sm rounded-3 py-2 @error('budget_category_id') is-invalid @enderror"
                                                placeholder="Ketik untuk mencari kategori..." autocomplete="off" />
                                            <div class="position-absolute top-50 end-0 translate-middle-y pe-3 text-muted"
                                                style="pointer-events: none;"><i
                                                    class="fa-solid fa-chevron-down small"></i></div>
                                        </div>
                                        @error('budget_category_id')
                                            <span
                                                class="text-danger small mt-1 d-block position-absolute">{{ $message }}</span>
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

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small fw-medium m-0 mb-1">Batas Maksimal
                                            (Rp)</label>
                                        <input type="text" inputmode="numeric" wire:model="budget_amount"
                                            oninput="formatRibuan(this)"
                                            class="form-control border-0 shadow-sm rounded-3 py-2"
                                            placeholder="Contoh: 3.000.000" required />
                                    </div>

                                    <div class="col-md-3">
                                        <button type="submit"
                                            class="btn btn-dark w-100 fw-bold rounded-3 shadow-sm py-2 text-nowrap">
                                            <span wire:loading.remove wire:target="saveCategoryBudget"><i
                                                    class="fa-solid fa-plus me-1"></i> Set Target</span>
                                            <span wire:loading wire:target="saveCategoryBudget"><i
                                                    class="fa-solid fa-spinner fa-spin"></i></span>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="row g-3">
                                @forelse($categoryBudgets as $cb)
                                    <div class="col-md-6 mb-2">
                                        <div
                                            class="p-3 bg-body border shadow-sm rounded-4 d-flex justify-content-between align-items-center transition-all h-100">
                                            <div>
                                                <h6 class="m-0 fw-bold text-body mb-1">{{ $cb->category->name }}</h6>
                                                <span class="small text-muted fw-medium d-block">Maks: Rp
                                                    {{ number_format($cb->amount, 0, ',', '.') }}</span>
                                            </div>
                                            <button wire:click="deleteCategoryBudget({{ $cb->id }})"
                                                class="btn btn-sm btn-outline-danger rounded-circle border"
                                                style="width: 35px; height: 35px; flex-shrink: 0;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-4 text-muted small">
                                        <i class="fa-solid fa-crosshairs fa-2x mb-2 opacity-25 d-block"></i>
                                        Belum ada target anggaran kategori yang dibuat.
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 992px) {
        .border-end-lg {
            border-right: 1px solid var(--bs-border-color);
        }
    }
</style>

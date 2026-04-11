<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public function mount()
    {
        if (!auth()->user()->is_admin) {
            return redirect()->route('dashboard');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage(); // Reset pagination kalau admin lagi ngetik pencarian
    }

    public function forceResetPassword($userId, $newPassword)
    {
        $user = User::findOrFail($userId);
        $user->password = Hash::make($newPassword);
        $user->save();

        session()->flash('success', "Password milik {$user->username} berhasil direset paksa!");
    }

    public function with()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('username', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return ['usersData' => $users];
    }
}; ?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="fw-bold m-0 text-danger"><i class="fa-solid fa-shield-halved me-2"></i>Admin Panel</h3>
        <span class="badge bg-danger">Hanya Admin</span>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-top border-danger border-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="fw-bold m-0 text-body">Daftar Pengguna MoneyMate</h6>
            <div class="input-group input-group-sm" style="max-width: 280px;">
                <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" wire:model.live="search" class="form-control border-start-0 ps-0"
                    placeholder="Cari nama, username, email...">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th class="text-center">Admin?</th>
                        <th class="text-end pe-4">Aksi Reset</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usersData as $u)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td><span
                                    class="badge bg-secondary-subtle border text-secondary-emphasis">{{ $u->username }}</span>
                            </td>
                            <td>{{ $u->email }}</td>
                            <td class="text-center">
                                @if ($u->is_admin)
                                    <i class="fa-solid fa-check-circle text-success"></i>
                                @else
                                    <i class="fa-solid fa-xmark-circle text-muted opacity-50"></i>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button onclick="promptReset({{ $u->id }}, '{{ $u->username }}')"
                                    class="btn btn-sm btn-outline-danger fw-bold">
                                    <i class="fa-solid fa-key me-1"></i> Reset Pass
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Pengguna tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top bg-body-tertiary">
            {{ $usersData->links() }}
        </div>
    </div>
</div>

@script
    <script>
        // Menyambungkan JavaScript Prompt murni dengan Backend Livewire Volt
        window.promptReset = function(userId, username) {
            let newPass = prompt(`RESET PASSWORD PAKSA\nMasukkan password BARU untuk pengguna: ${username}`);
            if (newPass && newPass.trim() !== "") {
                $wire.forceResetPassword(userId, newPass);
            }
        }
    </script>
@endscript

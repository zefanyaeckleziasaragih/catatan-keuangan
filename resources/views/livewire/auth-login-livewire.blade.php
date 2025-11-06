<form wire:submit.prevent="login">
    <div class="card">
        <div class="card-body">
            <div class="logo-section">
                <img src="/logo.png" alt="Logo">
                <h2 class="heading-font">CASHFLOW</h2>
                <p>Masuk ke akun Anda</p>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" wire:model="email" placeholder="nama@example.com">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label">Kata Sandi</label>
                <input type="password" class="form-control" wire:model="password" placeholder="••••••••">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            {{-- Submit Button --}}
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <span wire:loading.remove wire:target="login">Masuk</span>
                    <span wire:loading wire:target="login">Memproses...</span>
                </button>
            </div>

            <hr>
            
            <p class="text-center mb-0">
                Belum memiliki akun? 
                <a href="{{ route('auth.register') }}">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</form>
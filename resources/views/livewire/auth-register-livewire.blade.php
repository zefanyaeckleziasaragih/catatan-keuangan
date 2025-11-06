<form wire:submit.prevent="register">
    <div class="card">
        <div class="card-body">
            <div class="logo-section">
                <img src="/logo.png" alt="Logo">
                <h2 class="heading-font">CASHFLOW</h2>
                <p>Buat akun baru Anda</p>
            </div>

            {{-- Name --}}
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" wire:model="name" placeholder="John Doe">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
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
                    <span wire:loading.remove wire:target="register">Daftar</span>
                    <span wire:loading wire:target="register">Memproses...</span>
                </button>
            </div>

            <hr>
            
            <p class="text-center mb-0">
                Sudah memiliki akun? 
                <a href="{{ route('auth.login') }}">Masuk</a>
            </p>
        </div>
    </div>
</form>
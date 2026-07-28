@extends('layouts.owner')

@section('title', 'Ganti Password')

@section('content')
<div style="max-width: 600px;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 28px; font-weight: 600; color: #111827; margin-bottom: 8px;">Ganti Password</h1>
        <p style="font-size: 14px; color: #6b7280;">Ubah password pengguna sistem</p>
    </div>

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px;">
            <p style="color: #991b1b; font-weight: 600; margin-bottom: 8px;">Terjadi Kesalahan</p>
            <ul style="margin: 0; padding: 0; color: #991b1b; font-size: 14px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px;">
            <p style="color: #166534; font-weight: 600;">{{ session('success') }}</p>
        </div>
    @endif

    <form action="{{ route('owner.change-password.store') }}" method="POST" style="background: white; padding: 24px; border-radius: 8px; border: 1px solid #e5e7eb;">
        @csrf

        <!-- User Selection Dropdown -->
        <div style="margin-bottom: 20px;">
            <label for="user_id" style="display: block; font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 6px;">
                Pilih Pengguna
            </label>
            <select id="user_id" name="user_id" onchange="toggleOldPasswordField()" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit;">
                <option value="">-- Pilih Pengguna --</option>
                @foreach ($users as $user)
                    @php
                        $roleName = $user->role == 0 ? 'Admin' : 'Pemilik';
                    @endphp
                    <option value="{{ $user->id }}" data-user-id="{{ $user->id }}" data-current-user="{{ auth()->id() }}">
                        {{ $user->name }} ({{ $roleName }})
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Old Password Field (Conditional) -->
        <div id="oldPasswordField" style="margin-bottom: 20px; display: none;">
            <label for="old_password" style="display: block; font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 6px;">
                Password Lama
            </label>
            <input type="password" id="old_password" name="old_password" placeholder="Masukkan password lama" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit;">
            @error('old_password')
                <p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password Field -->
        <div style="margin-bottom: 20px;">
            <label for="new_password" style="display: block; font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 6px;">
                Password Baru
            </label>
            <input type="password" id="new_password" name="new_password" placeholder="Masukkan password baru (minimal 8 karakter)" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit;">
            <p style="font-size: 12px; color: #6b7280; margin-top: 4px;">Minimal 8 karakter</p>
            @error('new_password')
                <p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password Field -->
        <div style="margin-bottom: 24px;">
            <label for="new_password_confirmation" style="display: block; font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 6px;">
                Konfirmasi Password Baru
            </label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Konfirmasi password baru" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit;">
            @error('new_password_confirmation')
                <p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div style="display: flex; gap: 12px;">
            <button type="submit" style="flex: 1; padding: 10px 16px; background: #2563eb; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                Ganti Password
            </button>
            <a href="{{ route('dashboard.pemilik') }}" style="flex: 1; padding: 10px 16px; background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; transition: all 0.2s;">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
function toggleOldPasswordField() {
    const userSelect = document.getElementById('user_id');
    const selectedOption = userSelect.options[userSelect.selectedIndex];
    const userId = selectedOption.value;
    const currentUserId = selectedOption.getAttribute('data-current-user');
    const oldPasswordField = document.getElementById('oldPasswordField');

    // Tampilkan old password field hanya jika user memilih dirinya sendiri
    if (userId && userId == currentUserId) {
        oldPasswordField.style.display = 'block';
        document.getElementById('old_password').required = true;
    } else {
        oldPasswordField.style.display = 'none';
        document.getElementById('old_password').required = false;
        document.getElementById('old_password').value = '';
    }
}
</script>

@endsection

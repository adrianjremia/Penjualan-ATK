@extends('layouts.owner')

@section('title', 'Ganti Password')

@section('content')
<div style="max-width: 600px;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 28px; font-weight: 600; color: #111827; margin-bottom: 8px;">Ganti Password</h1>
        <p style="font-size: 14px; color: #6b7280;">Ubah password pengguna sistem</p>
    </div>

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fee2e2; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
            <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                <div style="flex-shrink: 0;">
                    <svg style="width: 20px; height: 20px; color: #dc2626;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #7f1d1d;">Terjadi Kesalahan</h3>
                    <ul style="margin: 0; padding-left: 20px; color: #991b1b; font-size: 13px; line-height: 1.6;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div style="background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
            <div style="display: flex; gap: 12px;">
                <div style="flex-shrink: 0;">
                    <svg style="width: 20px; height: 20px; color: #16a34a;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div style="flex: 1;">
                    <p style="margin: 0; color: #166534; font-weight: 600; font-size: 14px;">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('owner.change-password.store') }}" method="POST" style="background: white; padding: 24px; border-radius: 8px; border: 1px solid #e5e7eb;">
        @csrf

        <!-- User Selection Dropdown -->
        <div style="margin-bottom: 20px;">
            <label for="user_id" style="display: block; font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 6px;">
                Pilih Pengguna
            </label>
            <select id="user_id" name="user_id" onchange="toggleOldPasswordField()" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit;">
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
            <div style="position: relative; display: flex; align-items: center;">
                <input type="password" id="old_password" name="old_password" placeholder="Masukkan password lama" style="width: 100%; padding: 10px 12px; padding-right: 40px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit;">
                <button type="button" onclick="togglePasswordVisibility('old_password')" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('images/icons/detail.png') }}" alt="Show Password" style="width: 18px; height: 18px; opacity: 0.6;">
                </button>
            </div>
            @error('old_password')
                <p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password Field -->
        <div style="margin-bottom: 20px;">
            <label for="new_password" style="display: block; font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 6px;">
                Password Baru
            </label>
            <div style="position: relative; display: flex; align-items: center;">
                <input type="password" id="new_password" name="new_password" placeholder="Masukkan password baru (minimal 8 karakter)" style="width: 100%; padding: 10px 12px; padding-right: 40px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit;">
                <button type="button" onclick="togglePasswordVisibility('new_password')" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('images/icons/detail.png') }}" alt="Show Password" style="width: 18px; height: 18px; opacity: 0.6;">
                </button>
            </div>
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
            <div style="position: relative; display: flex; align-items: center;">
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Konfirmasi password baru" style="width: 100%; padding: 10px 12px; padding-right: 40px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; font-family: inherit;">
                <button type="button" onclick="togglePasswordVisibility('new_password_confirmation')" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('images/icons/detail.png') }}" alt="Show Password" style="width: 18px; height: 18px; opacity: 0.6;">
                </button>
            </div>
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

function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const isPassword = field.type === 'password';
    field.type = isPassword ? 'text' : 'password';
}
</script>

@endsection

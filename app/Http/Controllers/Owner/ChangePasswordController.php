<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    public function index()
    {
        // Get all users (Admin & Pemilik)
        $users = User::all();
        
        return view('owner.change-password.index', compact('users'));
    }

    public function changePassword(Request $request)
    {
        // Debug: Log request data
        \Log::info('[v0] Change password request:', [
            'user_id' => $request->input('user_id'),
            'user_id_type' => gettype($request->input('user_id')),
        ]);

        // Validate input
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'old_password' => 'nullable|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'user_id.required' => 'Pilih user yang ingin diubah passwordnya',
            'user_id.integer' => 'Format user tidak valid',
            'user_id.exists' => 'User tidak ditemukan',
            'old_password.required' => 'Password lama diperlukan',
            'new_password.required' => 'Password baru diperlukan',
            'new_password.min' => 'Password minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        $selectedUser = User::findOrFail($validated['user_id']);
        $currentUser = Auth::user();
        $isChangingOwnPassword = $selectedUser->id === $currentUser->id;

        // Validate old password jika mengubah password sendiri
        if ($isChangingOwnPassword) {
            if (!Hash::check($validated['old_password'], $currentUser->password)) {
                return back()->withErrors(['old_password' => 'Password lama tidak sesuai']);
            }
            
            // Pastikan password baru berbeda dengan password lama
            if (Hash::check($validated['new_password'], $currentUser->password)) {
                return back()->withErrors(['new_password' => 'Password baru harus berbeda dengan password lama']);
            }
        }

        // Update password
        $selectedUser->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        // Log activity
        $logDescription = $isChangingOwnPassword 
            ? "Mengganti password sendiri"
            : "Mengganti password user " . $selectedUser->name;

        ActivityLogService::logUpdate(
            auth()->id(),
            'User',
            $selectedUser->id,
            ['password' => 'hidden'],
            ['password' => 'updated']
        );

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
}

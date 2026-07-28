<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecordAktivitas;
use App\Models\User;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = RecordAktivitas::with('user');

        // Filter by user
        if ($request->filled('id_user')) {
            $query->byUser($request->input('id_user'));
        }

        // Filter by action type
        if ($request->filled('action')) {
            $query->byAction($request->input('action'));
        }

        // Filter by date range
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $tanggalMulai = $request->input('tanggal_mulai') . ' 00:00:00';
            $tanggalAkhir = $request->input('tanggal_akhir') . ' 23:59:59';
            $query->byDateRange($tanggalMulai, $tanggalAkhir);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->searchDescription($request->input('search'));
        }

        // Handle sorting
        $currentSort = request('sort', 'created_at');
        $currentDirection = request('direction', 'desc');

        // Validate allowed sort columns
        $allowedSorts = ['created_at', 'id_user', 'action'];
        if (!in_array($currentSort, $allowedSorts)) {
            $currentSort = 'created_at';
            $currentDirection = 'desc';
        }

        // Validate direction
        if (!in_array($currentDirection, ['asc', 'desc'])) {
            $currentDirection = 'desc';
        }

        // Apply sorting to query
        $query->orderBy($currentSort, $currentDirection);

        $recordAktivitas = $query->paginate(50);

        // Get unique actions for filter dropdown
        $actions = RecordAktivitas::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // Get users for filter dropdown (exclude pemilik, only admin/staff)
        $users = User::where('role', 0)->orderBy('username')->get();

        return view('owner.record_aktivitas.index', compact(
            'recordAktivitas',
            'actions',
            'users',
            'currentSort',
            'currentDirection'
        ));
    }
}

<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

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

        $activityLogs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get unique actions for filter dropdown
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // Get users for filter dropdown
        $users = User::where('role', 0)->orderBy('username')->get();

        return view('owner.activity-log.index', compact(
            'activityLogs',
            'actions',
            'users'
        ));
    }
}

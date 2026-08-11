<?php

namespace App\Http\Controllers\Learning;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificatesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Certificate::with(['driver', 'training'])->orderByDesc('issue_date');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $certificates = $query->paginate($perPage)->withQueryString();

        $stats = [
            'issued' => Certificate::count(),
            'pending' => Certificate::where('status', 'pending')->count(),
            'active' => Certificate::where('status', 'issued')->count(),
            'expired' => Certificate::where('status', 'expired')->count(),
        ];

        if (config('database.default') === 'pgsql') {
            $certsPerMonth = Certificate::selectRaw("TO_CHAR(issue_date, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('issue_date')
                ->groupByRaw("TO_CHAR(issue_date, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $certsPerMonth = Certificate::selectRaw('strftime("%m", issue_date) as month_num, COUNT(*) as total')
                ->whereNotNull('issue_date')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        $certDist = Certificate::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        return view('admin.learning.certificates', compact('certificates', 'stats', 'certsPerMonth', 'certDist'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bhakt;
use App\Models\AuditLog;
use App\Models\User;
use App\Services\LanguageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $stats = [
            'total'      => Bhakt::count(),
            'approved'   => Bhakt::where('status', 'approved')->count(),
            'pending'    => Bhakt::where('status', 'pending')->count(),
            'rejected'   => Bhakt::where('status', 'rejected')->count(),
            'dikshit'    => Bhakt::where('diksha_taken', true)->where('status', 'approved')->count(),
            'whatsapp'   => Bhakt::where('whatsapp_consent', true)->count(),
        ];

        $stateStats = Bhakt::selectRaw('state, count(*) as total')
            ->whereNotNull('state')
            ->groupBy('state')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $branchStats = Bhakt::selectRaw('branch, count(*) as total')
            ->whereNotNull('branch')
            ->groupBy('branch')
            ->orderByDesc('total')
            ->get();

        $recent = Bhakt::latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'stateStats', 'branchStats', 'recent'));
    }

    // List all bhakts
    public function index(Request $request)
    {
        $query = Bhakt::query();

        $search  = trim((string)$request->input('search'));
        $status  = $request->input('status');
        $state   = $request->input('state');
        $branch  = $request->input('branch');
        $diksha  = $request->input('diksha');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name',        'like', "%{$search}%")
                  ->orWhere('name_en',   'like', "%{$search}%")
                  ->orWhere('name_hi',   'like', "%{$search}%")
                  ->orWhere('mobile',    'like', "%{$search}%")
                  ->orWhere('bhakt_id',  'like', "%{$search}%")
                  ->orWhere('city',      'like', "%{$search}%")
                  ->orWhere('district',  'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($state) {
            $hiState = LanguageHelper::translateState($state);
            $enState = LanguageHelper::translateState($state);
            $query->where(function ($q) use ($state, $hiState, $enState) {
                $q->where('state', $state)
                  ->orWhere('state', $hiState)
                  ->orWhere('state', $enState);
            });
        }

        if ($branch) {
            $hiBranch = LanguageHelper::translateBranch($branch);
            $enBranch = LanguageHelper::translateBranch($branch);
            $query->where(function ($q) use ($branch, $hiBranch, $enBranch) {
                $q->where('branch', $branch)
                  ->orWhere('branch', $hiBranch)
                  ->orWhere('branch', $enBranch);
            });
        }

        if ($diksha === 'yes') {
            $query->where('diksha_taken', true);
        } elseif ($diksha === 'no') {
            $query->where('diksha_taken', false);
        }

        $bhakts   = $query->latest()->paginate(15)->withQueryString();
        $states   = Bhakt::whereNotNull('state')->distinct()->orderBy('state')->pluck('state');
        
        $defaultBranches = ['Shivreinarayan', 'Vrindavan', 'Raipur', 'Champa', 'Janjgir', 'Varanasi'];
        $dbBranches = Bhakt::whereNotNull('branch')->distinct()->pluck('branch')->toArray();
        $branches = collect(array_values(array_unique(array_merge($defaultBranches, $dbBranches))));

        return view('admin.bhakts.index', compact('bhakts', 'states', 'branches'));
    }

    // View single bhakt
    public function show(Bhakt $bhakt)
    {
        $bhakt->load('reviewer', 'auditLogs.actor');
        return view('admin.bhakts.show', compact('bhakt'));
    }

    // Approve bhakt
    public function approve(Request $request, Bhakt $bhakt)
    {
        $request->validate(['remarks' => 'nullable|string|max:500']);

        if ($bhakt->status === 'pending') {
            $bhakt->update([
                'status'       => 'approved',
                'bhakt_id'     => Bhakt::generateBhaktId($bhakt),
                'admin_remarks'=> $request->remarks,
                'reviewed_by'  => Auth::id(),
                'reviewed_at'  => now(),
            ]);
            AuditLog::create([
                'bhakt_id' => $bhakt->id,
                'action'   => 'approved',
                'actor_id' => Auth::id(),
                'remarks'  => $request->remarks,
            ]);
        }

        return redirect()->route('admin.bhakts.show', $bhakt)->with('success', __('admin.approved_success'));
    }

    // Reject bhakt
    public function reject(Request $request, Bhakt $bhakt)
    {
        $request->validate(['remarks' => 'required|string|max:500']);

        $bhakt->update([
            'status'        => 'rejected',
            'admin_remarks' => $request->remarks,
            'reviewed_by'   => Auth::id(),
            'reviewed_at'   => now(),
        ]);
        AuditLog::create([
            'bhakt_id' => $bhakt->id,
            'action'   => 'rejected',
            'actor_id' => Auth::id(),
            'remarks'  => $request->remarks,
        ]);

        return redirect()->route('admin.bhakts.show', $bhakt)->with('success', __('admin.rejected_success'));
    }

    // Send back for correction
    public function correction(Request $request, Bhakt $bhakt)
    {
        $request->validate(['remarks' => 'required|string|max:500']);

        $bhakt->update([
            'status'        => 'correction',
            'admin_remarks' => $request->remarks,
            'reviewed_by'   => Auth::id(),
            'reviewed_at'   => now(),
        ]);
        AuditLog::create([
            'bhakt_id' => $bhakt->id,
            'action'   => 'correction_requested',
            'actor_id' => Auth::id(),
            'remarks'  => $request->remarks,
        ]);

        return redirect()->route('admin.bhakts.show', $bhakt)->with('success', __('admin.correction_success'));
    }

    // Delete a devotee record
    public function destroy(Bhakt $bhakt)
    {
        $bhakt->delete();

        if (url()->previous() === route('admin.bhakts.show', $bhakt)) {
            return redirect()->route('admin.bhakts.index')->with('success', __('messages.deleted_successfully'));
        }

        return back()->with('success', __('messages.deleted_successfully'));
    }

    // Export CSV (Bilingual)
    public function export(Request $request)
    {
        $query = Bhakt::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }
        if ($request->filled('branch')) {
            $query->where('branch', $request->branch);
        }
        if ($request->filled('diksha')) {
            $query->where('diksha_taken', $request->diksha === 'yes');
        }

        $bhakts = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="bhakts_export_' . now()->format('Ymd_His') . '.csv"',
        ];

        $isHi = app()->getLocale() === 'hi';

        $callback = function () use ($bhakts, $isHi) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel support
            fputs($file, "\xEF\xBB\xBF");

            // CSV Column Headers
            fputcsv($file, [
                $isHi ? 'भक्त ID' : 'Bhakt ID',
                $isHi ? 'नाम' : 'Full Name',
                $isHi ? 'मोबाइल' : 'Mobile Number',
                $isHi ? 'ईमेल' : 'Email',
                $isHi ? 'लिंग' : 'Gender',
                $isHi ? 'जन्मतिथि' : 'Date of Birth',
                $isHi ? 'शहर' : 'City',
                $isHi ? 'जिला' : 'District',
                $isHi ? 'राज्य' : 'State',
                $isHi ? 'आश्रम शाखा' : 'Ashram Branch',
                $isHi ? 'दीक्षा स्थिति' : 'Diksha Status',
                $isHi ? 'दीक्षा तिथि' : 'Diksha Date',
                $isHi ? 'दीक्षा स्थान' : 'Diksha Place',
                $isHi ? 'दीक्षा गुरु' : 'Diksha Guru',
                $isHi ? 'परिवार के सदस्य' : 'Family Members',
                $isHi ? 'सेवा' : 'Seva',
                $isHi ? 'WhatsApp सहमति' : 'WhatsApp Consent',
                $isHi ? 'स्थिति' : 'Status',
                $isHi ? 'पंजीकरण तिथि' : 'Registered At',
            ]);

            foreach ($bhakts as $b) {
                fputcsv($file, [
                    $b->bhakt_id ?? $b->display_reg_number,
                    $b->display_name,
                    $b->mobile,
                    $b->email,
                    $b->gender ? ucfirst($b->gender) : '',
                    $b->dob?->format('d/m/Y'),
                    $b->display_city,
                    $b->display_district,
                    $b->display_state,
                    $b->display_branch,
                    $b->diksha_taken ? ($isHi ? 'दीक्षित' : 'Initiated') : ($isHi ? 'नहीं' : 'No'),
                    $b->diksha_date?->format('d/m/Y'),
                    $b->diksha_place ? LanguageHelper::translatePlace($b->diksha_place) : '',
                    $b->diksha_guru,
                    $b->family_members,
                    $b->seva,
                    $b->whatsapp_consent ? ($isHi ? 'हाँ' : 'Yes') : ($isHi ? 'नहीं' : 'No'),
                    $b->display_status,
                    $b->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

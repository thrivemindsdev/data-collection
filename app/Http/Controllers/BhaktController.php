<?php

namespace App\Http\Controllers;

use App\Models\Bhakt;
use App\Models\AuditLog;
use App\Services\LanguageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BhaktController extends Controller
{
    // Show registration form (public)
    public function create()
    {
        return view('bhakt.register');
    }

    // Handle form submission (public)
    public function store(Request $request)
    {
        // Sanitize mobile before validation
        if ($request->has('mobile')) {
            $cleanMobile = preg_replace('/[^0-9]/', '', (string)$request->mobile);
            $request->merge(['mobile' => $cleanMobile]);
        }

        $validated = $request->validate([
            'name'             => 'required|string|max:120',
            'mobile'           => ['required', 'digits:10', 'regex:/^[6-9][0-9]{9}$/', 'unique:bhakts,mobile'],
            'email'            => 'nullable|email|max:120',
            'gender'           => 'nullable|in:male,female,other',
            'dob'              => 'nullable|date|before:today',
            'city'             => 'nullable|string|max:100',
            'district'         => 'nullable|string|max:100',
            'state'            => 'nullable|string|max:100',
            'address'          => 'nullable|string|max:500',
            'branch'           => 'nullable|string|max:100',
            'other_branch'     => 'nullable|string|max:100',
            'diksha_taken'     => 'nullable|boolean',
            'diksha_date'      => 'nullable|date',
            'diksha_place'     => 'nullable|string|max:200',
            'diksha_guru'      => 'nullable|string|max:120',
            'family_members'   => 'nullable|string|max:1000',
            'seva'             => 'nullable|string|max:200',
            'whatsapp_consent' => 'nullable|boolean',
            'sms_consent'      => 'nullable|boolean',
            'consent'          => 'accepted',
        ], [
            'name.required'    => __('validation.name_required'),
            'mobile.required'  => __('validation.mobile_required'),
            'mobile.digits'    => __('validation.mobile_digits'),
            'mobile.regex'     => app()->getLocale() === 'hi' ? 'वैध 10 अंकों का भारतीय मोबाइल नंबर दर्ज करें (6-9 से शुरू)।' : 'Please enter a valid 10-digit Indian mobile number (starting with 6-9).',
            'mobile.unique'    => __('validation.mobile_unique'),
            'consent.accepted' => __('validation.consent_required'),
        ]);

        $rawName = trim($validated['name']);
        $nameEn = null;
        $nameHi = null;

        if (LanguageHelper::hasDevanagari($rawName)) {
            $nameHi = $rawName;
            $nameEn = LanguageHelper::phoneticHindiToEnglish($rawName);
        } else {
            $nameEn = $rawName;
            $nameHi = LanguageHelper::phoneticEnglishToHindi($rawName);
        }

        $branch = $validated['branch'] ?? null;
        if (($branch === 'Other' || $branch === 'other') && !empty($request->other_branch)) {
            $branch = trim($request->other_branch);
        } elseif ($branch === 'Other' || $branch === 'other') {
            $branch = 'Other Center';
        }

        $bhakt = Bhakt::create([
            'registration_number' => Bhakt::generateRegistrationNumber(),
            'name'             => $rawName,
            'name_en'          => $nameEn,
            'name_hi'          => $nameHi,
            'mobile'           => $validated['mobile'],
            'email'            => $validated['email'] ?? null,
            'gender'           => $validated['gender'] ?? null,
            'dob'              => $validated['dob'] ?? null,
            'city'             => $validated['city'] ?? null,
            'district'         => $validated['district'] ?? null,
            'state'            => $validated['state'] ?? null,
            'address'          => $validated['address'] ?? null,
            'branch'           => $branch,
            'diksha_taken'     => $request->boolean('diksha_taken'),
            'diksha_date'      => $validated['diksha_date'] ?? null,
            'diksha_place'     => $validated['diksha_place'] ?? null,
            'diksha_guru'      => $validated['diksha_guru'] ?? null,
            'family_members'   => $validated['family_members'] ?? null,
            'seva'             => $validated['seva'] ?? null,
            'whatsapp_consent' => $request->boolean('whatsapp_consent'),
            'sms_consent'      => $request->boolean('sms_consent'),
            'status'           => 'pending',
        ]);

        return redirect()->route('register.success', $bhakt->id);
    }

    // Success page
    public function success(Bhakt $bhakt)
    {
        return view('bhakt.success', compact('bhakt'));
    }

    // Official printable receipt
    public function receipt(Bhakt $bhakt)
    {
        return view('bhakt.receipt', compact('bhakt'));
    }

    // Download Receipt as PDF
    public function pdf(Bhakt $bhakt)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bhakt.pdf', compact('bhakt'));
        $filename = 'Receipt_' . $bhakt->display_reg_number . '.pdf';

        return $pdf->download($filename);
    }

    // Check application status (public)
    public function status(Request $request)
    {
        $bhakt = null;
        $mobile = trim((string)$request->mobile);
        $regInput = trim((string)$request->reg_id);

        if (!empty($mobile) || !empty($regInput)) {
            $query = Bhakt::query();

            if (!empty($mobile)) {
                $cleanMobile = preg_replace('/[^0-9]/', '', $mobile);
                if (!empty($cleanMobile)) {
                    $query->where('mobile', $cleanMobile);
                }
            }

            if (!empty($regInput)) {
                $idNum = null;
                if (preg_match('/^REG-0*(\d+)$/i', $regInput, $m)) {
                    $idNum = (int)$m[1];
                } elseif (is_numeric($regInput)) {
                    $idNum = (int)$regInput;
                }

                if ($idNum) {
                    $query->where('id', $idNum);
                } else {
                    $upper = strtoupper($regInput);
                    $query->where(function($q) use ($upper) {
                        $q->where('registration_number', $upper)
                          ->orWhere('bhakt_id', $upper);
                    });
                }
            }

            $bhakt = $query->first();
        }

        return view('bhakt.status', compact('bhakt'));
    }
}

<?php

namespace App\Models;

use App\Services\LanguageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Bhakt extends Model
{
    protected $fillable = [
        'registration_number', 'bhakt_id', 'name', 'name_en', 'name_hi', 'mobile',
        'email', 'gender', 'dob', 'city', 'district', 'state', 'address', 'branch',
        'diksha_taken', 'diksha_date', 'diksha_place', 'diksha_guru',
        'family_members', 'seva', 'whatsapp_consent', 'sms_consent',
        'photo', 'status', 'admin_remarks', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'dob' => 'date',
        'diksha_date' => 'date',
        'diksha_taken' => 'boolean',
        'whatsapp_consent' => 'boolean',
        'sms_consent' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Generate an authentic, prestigious, collision-free Registration Number.
     * Format: SAD-REG-YYMM-XXXXX (e.g. SAD-REG-2611-7K94B)
     */
    public static function generateRegistrationNumber(): string
    {
        $prefix = 'SAD-REG-' . now()->format('ym') . '-';
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // exclude 0, 1, O, I for clarity

        do {
            $randomPart = '';
            for ($i = 0; $i < 5; $i++) {
                $randomPart .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $code = $prefix . $randomPart;
        } while (static::where('registration_number', $code)->exists());

        return $code;
    }

    /**
     * Generate an authentic, official Bhakt ID.
     * Format: SAD-[CATEGORY][YEAR]-[UNIQUE] (e.g. SAD-DK26-78419, SAD-VR26-40291)
     */
    public static function generateBhaktId(?self $bhakt = null): string
    {
        $year = now()->format('y');

        // Determine category code
        $category = 'BK'; // General Bhakt
        if ($bhakt) {
            if ($bhakt->diksha_taken) {
                $category = 'DK'; // Dikshit (Initiated)
            } else {
                $branchStr = strtolower(trim((string)$bhakt->branch));
                if (str_contains($branchStr, 'shivr') || str_contains($branchStr, 'शिवरी')) {
                    $category = 'SN'; // Shivreinarayan
                } elseif (str_contains($branchStr, 'vrind') || str_contains($branchStr, 'वृंदा')) {
                    $category = 'VR'; // Vrindavan
                } elseif (str_contains($branchStr, 'raipur') || str_contains($branchStr, 'रायपुर')) {
                    $category = 'RP'; // Raipur
                } elseif (str_contains($branchStr, 'champa') || str_contains($branchStr, 'चांपा')) {
                    $category = 'CH'; // Champa
                } elseif (str_contains($branchStr, 'janjgir') || str_contains($branchStr, 'जांजगीर')) {
                    $category = 'JG'; // Janjgir
                } elseif (str_contains($branchStr, 'varanasi') || str_contains($branchStr, 'वाराणसी') || str_contains($branchStr, 'kashi')) {
                    $category = 'VN'; // Varanasi
                }
            }
        }

        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

        do {
            $randomPart = '';
            for ($i = 0; $i < 5; $i++) {
                $randomPart .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $code = "SAD-{$category}{$year}-{$randomPart}";
        } while (static::where('bhakt_id', $code)->exists());

        return $code;
    }

    /**
     * Formatted registration number accessor
     */
    public function getDisplayRegNumberAttribute(): string
    {
        if (!empty($this->registration_number)) {
            return $this->registration_number;
        }
        return 'SAD-REG-' . now()->format('ym') . '-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved'   => 'success',
            'pending'    => 'warning',
            'rejected'   => 'danger',
            'correction' => 'info',
            default      => 'secondary',
        };
    }

    /**
     * Localized name based on active locale (EN / HI)
     */
    public function getDisplayNameAttribute(): string
    {
        return LanguageHelper::translateName($this->name, $this->name_en, $this->name_hi);
    }

    /**
     * Localized branch based on active locale
     */
    public function getDisplayBranchAttribute(): string
    {
        return LanguageHelper::translateBranch($this->branch);
    }

    /**
     * Localized city based on active locale
     */
    public function getDisplayCityAttribute(): string
    {
        return LanguageHelper::translatePlace($this->city);
    }

    /**
     * Localized district based on active locale
     */
    public function getDisplayDistrictAttribute(): string
    {
        return LanguageHelper::translatePlace($this->district);
    }

    /**
     * Localized state based on active locale
     */
    public function getDisplayStateAttribute(): string
    {
        return LanguageHelper::translateState($this->state);
    }

    /**
     * Localized status label based on active locale
     */
    public function getDisplayStatusAttribute(): string
    {
        return LanguageHelper::translateStatus($this->status);
    }

    /**
     * Localized Diksha label
     */
    public function getDisplayDikshaAttribute(): string
    {
        return $this->diksha_taken ? __('messages.dikshit') : __('messages.not_dikshit');
    }
}

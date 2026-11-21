<?php

namespace App\Services;

class LanguageHelper
{
    /**
     * Common Indian names dictionary for accurate translation
     */
    protected static array $nameMap = [
        'राधेश्याम शर्मा' => 'Radheshyam Sharma',
        'सुरेश अग्रवाल'   => 'Suresh Agrawal',
        'गीता देवी'       => 'Geeta Devi',
        'महेश गुप्ता'    => 'Mahesh Gupta',
        'कमला बाई'       => 'Kamla Bai',
        'राम कुमार'       => 'Ram Kumar',
        'राधा देवी'       => 'Radha Devi',
        'अमित कुमार'     => 'Amit Kumar',
        'पूजा शर्मा'      => 'Pooja Sharma',
        'मोहन लाल'       => 'Mohan Lal',
        'अनिल वर्मा'      => 'Anil Verma',
        'दिनेश सिंह'      => 'Dinesh Singh',
        'सुनीता यादव'     => 'Sunita Yadav',
        'विकास पटेल'      => 'Vikas Patel',
        'मनोज तिवारी'     => 'Manoj Tiwari',
        'संजय मिश्रा'     => 'Sanjay Mishra',
        'राजेश चौहान'     => 'Rajesh Chauhan',
        'संतोष कुमार'     => 'Santosh Kumar',
        'सरोजिनी देवी'    => 'Sarojini Devi',
        'कौशल्या देवी'    => 'Kaushalya Devi',
    ];

    /**
     * Indian States dictionary
     */
    protected static array $stateMap = [
        'Andhra Pradesh'    => 'आंध्र प्रदेश',
        'Arunachal Pradesh' => 'अरुणाचल प्रदेश',
        'Assam'             => 'असम',
        'Bihar'             => 'बिहार',
        'Chhattisgarh'      => 'छत्तीसगढ़',
        'Goa'               => 'गोवा',
        'Gujarat'           => 'गुजरात',
        'Haryana'           => 'हरियाणा',
        'Himachal Pradesh'  => 'हिमाचल प्रदेश',
        'Jharkhand'         => 'झारखंड',
        'Karnataka'         => 'कर्नाटक',
        'Kerala'            => 'केरल',
        'Madhya Pradesh'    => 'मध्य प्रदेश',
        'Maharashtra'       => 'महाराष्ट्र',
        'Manipur'           => 'मणिपुर',
        'Meghalaya'         => 'मेघालय',
        'Mizoram'           => 'मिजोरम',
        'Nagaland'          => 'नागालैंड',
        'Odisha'            => 'ओडिशा',
        'Punjab'            => 'पंजाब',
        'Rajasthan'         => 'राजस्थान',
        'Sikkim'            => 'सिक्किम',
        'Tamil Nadu'        => 'तमिलनाडु',
        'Telangana'         => 'तेलंगाना',
        'Tripura'           => 'त्रिपुरा',
        'Uttar Pradesh'     => 'उत्तर प्रदेश',
        'Uttarakhand'       => 'उत्तराखंड',
        'West Bengal'       => 'पश्चिम बंगाल',
        'Delhi'             => 'दिल्ली',
        'Jammu & Kashmir'   => 'जम्मू और कश्मीर',
        'Ladakh'            => 'लद्दाख',
    ];

    /**
     * Common Cities & Districts dictionary
     */
    protected static array $placeMap = [
        'Raipur'        => 'रायपुर',
        'Bilaspur'      => 'बिलासपुर',
        'Delhi'         => 'दिल्ली',
        'New Delhi'     => 'नई दिल्ली',
        'Lucknow'       => 'लखनऊ',
        'Jabalpur'      => 'जबलपुर',
        'Bhopal'        => 'भोपाल',
        'Indore'        => 'इंदौर',
        'Vrindavan'     => 'वृंदावन',
        'Mathura'       => 'मथुरा',
        'Ayodhya'       => 'अयोध्या',
        'Varanasi'      => 'वाराणसी',
        'Kashi'         => 'काशी',
        'Prayagraj'     => 'प्रयागराज',
        'Haridwar'      => 'हरिद्वार',
        'Rishikesh'     => 'ऋषिकेश',
        'Patna'         => 'पटना',
        'Gaya'          => 'गया',
        'Jaipur'        => 'जयपुर',
        'Udaipur'       => 'उदयपुर',
        'Kota'          => 'कोटा',
        'Mumbai'        => 'मुंबई',
        'Pune'          => 'पुणे',
        'Nagpur'        => 'नागपुर',
        'Kolkata'       => 'कोलकाता',
        'Shivreinarayan'=> 'शिवरीनारायण',
        'Durg'          => 'दुर्ग',
        'Bhilai'        => 'भिलाई',
        'Rajnandgaon'   => 'राजनंदगांव',
        'Korba'         => 'कोरबा',
        'Raigarh'       => 'रायगढ़',
        'Agra'          => 'आगरा',
        'Kanpur'        => 'कानपुर',
        'Gorakhpur'     => 'गोरखपुर',
        'Meerut'        => 'मेरठ',
        'Ghaziabad'     => 'गाजियाबाद',
        'Noida'         => 'नोएडा',
        'Gwalior'       => 'ग्वालियर',
        'Ujjain'        => 'उज्जैन',
    ];

    /**
     * Ashram Branches
     */
    protected static array $branchMap = [
        'Shivreinarayan' => 'शिवरीनारायण',
        'Vrindavan'      => 'वृंदावन',
        'Other Center'   => 'अन्य केंद्र',
    ];

    /**
     * Statuses
     */
    protected static array $statusMap = [
        'pending'    => ['en' => 'Pending',    'hi' => 'लंबित'],
        'approved'   => ['en' => 'Approved',   'hi' => 'स्वीकृत'],
        'rejected'   => ['en' => 'Rejected',   'hi' => 'अस्वीकृत'],
        'correction' => ['en' => 'Correction', 'hi' => 'सुधार आवश्यक'],
    ];

    /**
     * Localize a person's name based on current locale
     */
    public static function translateName(?string $name, ?string $nameEn = null, ?string $nameHi = null): string
    {
        if (empty($name)) {
            return '—';
        }

        $isHindi = app()->getLocale() === 'hi';

        if ($isHindi) {
            if (!empty($nameHi)) {
                return $nameHi;
            }
            // Check if already in Hindi (Devanagari)
            if (self::hasDevanagari($name)) {
                return $name;
            }
            // Check dictionary for reverse map
            $rev = array_flip(self::$nameMap);
            if (isset($rev[$name])) {
                return $rev[$name];
            }
            return self::phoneticEnglishToHindi($name);
        } else {
            if (!empty($nameEn)) {
                return $nameEn;
            }
            // Check if already in English (Latin)
            if (!self::hasDevanagari($name)) {
                return $name;
            }
            // Check dictionary
            if (isset(self::$nameMap[$name])) {
                return self::$nameMap[$name];
            }
            return self::phoneticHindiToEnglish($name);
        }
    }

    /**
     * Localize an Ashram Branch
     */
    public static function translateBranch(?string $branch): string
    {
        if (empty($branch)) {
            return '—';
        }

        $isHindi = app()->getLocale() === 'hi';
        $trimmed = trim($branch);

        // Normalize matching
        foreach (self::$branchMap as $en => $hi) {
            if (strcasecmp($trimmed, $en) === 0 || $trimmed === $hi) {
                return $isHindi ? $hi : $en;
            }
        }

        return $trimmed;
    }

    /**
     * Localize a State name
     */
    public static function translateState(?string $state): string
    {
        if (empty($state)) {
            return '—';
        }

        $isHindi = app()->getLocale() === 'hi';
        $trimmed = trim($state);

        foreach (self::$stateMap as $en => $hi) {
            if (strcasecmp($trimmed, $en) === 0 || $trimmed === $hi) {
                return $isHindi ? $hi : $en;
            }
        }

        return $trimmed;
    }

    /**
     * Localize a City or District name
     */
    public static function translatePlace(?string $place): string
    {
        if (empty($place)) {
            return '';
        }

        $isHindi = app()->getLocale() === 'hi';
        $trimmed = trim($place);

        // Check state map first
        foreach (self::$stateMap as $en => $hi) {
            if (strcasecmp($trimmed, $en) === 0 || $trimmed === $hi) {
                return $isHindi ? $hi : $en;
            }
        }

        // Check place map
        foreach (self::$placeMap as $en => $hi) {
            if (strcasecmp($trimmed, $en) === 0 || $trimmed === $hi) {
                return $isHindi ? $hi : $en;
            }
        }

        // If English mode and place has Devanagari, convert phonetically
        if (!$isHindi && self::hasDevanagari($trimmed)) {
            return self::phoneticHindiToEnglish($trimmed);
        }

        // If Hindi mode and place has Latin, convert phonetically
        if ($isHindi && !self::hasDevanagari($trimmed)) {
            return self::phoneticEnglishToHindi($trimmed);
        }

        return $trimmed;
    }

    /**
     * Localize a Status label
     */
    public static function translateStatus(?string $status): string
    {
        if (empty($status)) {
            return '—';
        }
        $locale = app()->getLocale();
        $statusKey = strtolower(trim($status));
        return self::$statusMap[$statusKey][$locale] ?? ucfirst($status);
    }

    /**
     * Check if string contains Devanagari characters
     */
    public static function hasDevanagari(string $str): bool
    {
        return preg_match('/[\x{0900}-\x{097F}]/u', $str) === 1;
    }

    /**
     * Phonetic Hindi to English converter
     */
    public static function phoneticHindiToEnglish(string $text): string
    {
        $vowels = [
            'अ' => 'A', 'आ' => 'Aa', 'इ' => 'I', 'ई' => 'Ee', 'उ' => 'U', 'ऊ' => 'Oo',
            'ऋ' => 'Ri', 'ए' => 'E', 'ऐ' => 'Ai', 'ओ' => 'O', 'औ' => 'Au',
        ];
        $matras = [
            'ा' => 'a', 'ि' => 'i', 'ी' => 'ee', 'ु' => 'u', 'ू' => 'oo',
            'ृ' => 'ri', 'े' => 'e', 'ै' => 'ai', 'ो' => 'o', 'ौ' => 'au',
            'ं' => 'n', 'ँ' => 'n', 'ः' => 'h', '्' => '',
        ];
        $consonants = [
            'क' => 'k', 'ख' => 'kh', 'ग' => 'g', 'घ' => 'gh', 'ङ' => 'ng',
            'च' => 'ch', 'छ' => 'chh', 'ज' => 'j', 'झ' => 'jh', 'ञ' => 'ny',
            'ट' => 't', 'ठ' => 'th', 'ड' => 'd', 'ढ' => 'dh', 'ण' => 'n',
            'त' => 't', 'थ' => 'th', 'द' => 'd', 'ध' => 'dh', 'न' => 'n',
            'प' => 'p', 'फ' => 'ph', 'ब' => 'b', 'भ' => 'bh', 'म' => 'm',
            'य' => 'y', 'र' => 'r', 'ल' => 'l', 'व' => 'v',
            'श' => 'sh', 'ष' => 'sh', 'स' => 's', 'ह' => 'h',
            'क्ष' => 'ksh', 'त्र' => 'tr', 'ज्ञ' => 'gy', 'श्र' => 'shr',
        ];

        // Replace known words
        if (isset(self::$nameMap[$text])) {
            return self::$nameMap[$text];
        }

        $chars = mb_str_split($text);
        $out = '';
        $len = count($chars);

        for ($i = 0; $i < $len; $i++) {
            $c = $chars[$i];
            $next = ($i + 1 < $len) ? $chars[$i + 1] : '';

            if (isset($vowels[$c])) {
                $out .= $vowels[$c];
            } elseif (isset($consonants[$c])) {
                $cVal = $consonants[$c];
                if ($out === '' || substr($out, -1) === ' ') {
                    $cVal = ucfirst($cVal);
                }
                $out .= $cVal;
                // Add inherent 'a' if not followed by matra, halant, or space/end
                if (!isset($matras[$next]) && $next !== ' ' && $next !== '' && $next !== '्') {
                    $out .= 'a';
                }
            } elseif (isset($matras[$c])) {
                $out .= $matras[$c];
            } else {
                $out .= $c;
            }
        }

        return ucwords(trim($out));
    }

    /**
     * Phonetic English to Hindi converter
     */
    public static function phoneticEnglishToHindi(string $text): string
    {
        // Check dictionary reverse map
        $rev = array_flip(self::$nameMap);
        if (isset($rev[$text])) {
            return $rev[$text];
        }

        // Check common place reverse map
        $revPlaces = array_flip(self::$placeMap);
        if (isset($revPlaces[$text])) {
            return $revPlaces[$text];
        }

        $revStates = array_flip(self::$stateMap);
        if (isset($revStates[$text])) {
            return $revStates[$text];
        }

        return $text;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PartnerCompatibility;

class CompatibilityController extends Controller
{
    /**
     * Shared result (no login required).
     * Uses verification_token from PartnerCompatibility for backwards compatibility.
     */
    public function shared(string $token)
    {
        $compatibility = PartnerCompatibility::where('verification_token', $token)
            ->with(['user', 'partnerCity'])
            ->firstOrFail();

        // Create a result object compatible with the shared view
        $result = new \stdClass();
        $result->score = $compatibility->overall_score;
        $result->score_description = $compatibility->score_description;
        $result->ai_report = $compatibility->ai_report;
        $result->synastry = $compatibility->synastry_data;

        return view('compatibility.shared', ['result' => $result]);
    }
}

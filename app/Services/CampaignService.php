<?php

namespace App\Services;

use App\Models\Campaign;
use Carbon\Carbon;

class CampaignService
{
    /**
     * Check if the campaign is currently active.
     *
     * @param  int  $campaignId
     * @return array
     */
    public function checkCampaignStatus(int $campaignId): array
    {
        // Fetch the active campaign from the database
        $campaign = Campaign::find($campaignId);

        if (!$campaign) {
            return ['status' => false, 'message' => 'The active campaign does not exist.'];
        }

        // Get the campaign's timezone
        $campaignTimezone = $campaign->timezone;

        // Ensure the timezone is valid; fallback to UTC if it's not
        if (!in_array($campaignTimezone, \DateTimeZone::listIdentifiers())) {
            $campaignTimezone = 'UTC'; // Default to UTC if invalid
        }

        // Convert the campaign's start and end times to the campaign's timezone
        $startTime = Carbon::parse($campaign->starts_at)->setTimezone($campaignTimezone);
        $endTime = Carbon::parse($campaign->ends_at)->setTimezone($campaignTimezone);

        // Get the current date and time in the campaign's timezone
        $currentDate = Carbon::now($campaignTimezone);

        // Check if the campaign has started
        if ($currentDate->lt($startTime)) {
            return ['status' => false, 'message' => 'The campaign has not started yet.'];
        }

        // Check if the campaign has ended
        if ($currentDate->gt($endTime)) {
            return ['status' => false, 'message' => 'The campaign has ended.'];
        }

        // If the campaign is active
        return ['status' => true, 'message' => 'The campaign is active.'];
    }
}

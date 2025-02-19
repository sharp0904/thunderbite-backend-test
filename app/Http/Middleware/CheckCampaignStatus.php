<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Services\CampaignService;

class CheckCampaignStatus
{
    protected $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $activeCampaignId = session('activeCampaign');

        // If there's no active campaign, return an error message
        if (!$activeCampaignId) {
            return response()->json(['message' => 'No active campaign available.'], 400);
        }

        // Use the CampaignService to check the campaign status
        $status = $this->campaignService->checkCampaignStatus($activeCampaignId);

        if (!$status['status']) {
            return response()->json(['message' => $status['message']], 400);
        }

        // If campaign is active, proceed with the request
        return $next($request);
    }
}

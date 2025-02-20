<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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
            session()->flash('error', 'No active campaign available.');
            return redirect()->route('backstage.campaigns.index');
        }

        // Use the CampaignService to check the campaign status
        $status = $this->campaignService->checkCampaignStatus($activeCampaignId);

        if (!$status['status']) {
            session()->flash('error', $status['message']);
            return redirect()->route('backstage.campaigns.index');
        }

        // If campaign is active, proceed with the request
        return $next($request);
    }
}

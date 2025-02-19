<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\Campaigns\StoreRequest;
use App\Http\Requests\Backstage\Campaigns\UpdateRequest;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('backstage.campaigns.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('backstage.campaigns.create', [
            'campaign' => new Campaign,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $campaign = Campaign::create($request->validated());

        session()->flash('success', 'The campaign has been created!');

        return redirect()->route('backstage.campaigns.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign): View
    {
        return view('backstage.campaigns.edit', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Campaign $campaign): RedirectResponse
    {
        // Update the campaign with validated data
        $campaign->update($request->validated());

        session()->flash('success', 'The campaign details have been updated!');

        return redirect()->route('backstage.campaigns.edit', $campaign->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();

        session()->flash('success', 'The campaign has been deleted!');

        return redirect()->route('backstage.campaigns.index');
    }


    /**
     * Activate campaign
     */
    public function use(Campaign $campaign): RedirectResponse
    {
        if (empty($campaign->timezone)) {
            session()->flash('error', 'This campaign does not have a valid timezone!');
            return redirect()->route('backstage.campaigns.index');
        }

        // Check if any prize in the campaign has exhausted the daily limit
        $prizes = $campaign->prizes;

        foreach ($prizes as $prize) {
            if ($prize->daily_limit <= 0) {
                session()->flash('error', 'One or more prizes for this campaign have exhausted their daily limit.');
                return redirect()->route('backstage.campaigns.index');
            }
        }

        session()->put('activeCampaign', $campaign->id);

        return redirect()->route('backstage.campaigns.index');
    }

    /**
     * Deactivate campaign
     */
    public function deactivate(): RedirectResponse
    {
        session()->forget('activeCampaign');

        session()->flash('success', 'The active campaign has been deactivated!');

        return redirect()->route('backstage.campaigns.index');
    }
}

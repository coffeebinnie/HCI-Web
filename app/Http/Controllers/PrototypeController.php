<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PrototypeController extends Controller
{
    public function forgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function resetSent(): View
    {
        return view('auth.reset-sent');
    }

    public function settings(): View
    {
        return view('settings.index', $this->shared());
    }

    public function profile(): View
    {
        return view('settings.profile', [
            ...$this->shared(),
            'activity' => [
                ['area' => 'Profile', 'detail' => 'Profile details reviewed', 'when' => 'Today'],
                ['area' => 'Security', 'detail' => 'Password settings', 'when' => 'Recently'],
                ['area' => 'Privacy', 'detail' => 'Preferences updated', 'when' => 'Recently'],
            ],
        ]);
    }

    public function editProfile(): View
    {
        return view('settings.edit-profile', $this->shared());
    }

    public function password(): View
    {
        return view('settings.password', $this->shared());
    }

    public function privacy(): View
    {
        return view('settings.privacy', [
            ...$this->shared(),
            'preferences' => [
                ['label' => 'Usage analytics', 'description' => 'Allow anonymous usage data to improve the experience.', 'enabled' => true],
                ['label' => 'Personalized insights', 'description' => 'Use spending patterns to tailor dashboard suggestions.', 'enabled' => true],
                ['label' => 'Email summaries', 'description' => 'Receive optional weekly budget and savings summaries.', 'enabled' => false],
                ['label' => 'Account activity alerts', 'description' => 'Show alerts for important account activity.', 'enabled' => true],
                ['label' => 'Profile visibility', 'description' => 'Keep your account details private from other users.', 'enabled' => true],
            ],
        ]);
    }

    public function saved(): View
    {
        return view('settings.saved', $this->shared());
    }
}

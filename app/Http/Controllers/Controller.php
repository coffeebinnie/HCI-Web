<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Shared navigation and signed-in account data for the prototype.
     *
     * @return array<string, mixed>
     */
    protected function shared(): array
    {
        $user = auth()->user();
        $email = $user?->email ?? 'you@example.com';

        return [
            'user' => [
                'name' => $user?->name ?? 'Kyrie Santos',
                'email' => $email,
                'username' => str($email)->before('@')->toString(),
                'phone' => 'Add phone number (optional)',
                'currency' => 'Philippine Peso (₱ PHP)',
                'timezone' => 'Asia/Manila (UTC+8)',
                'account_type' => 'Personal',
                'member_since' => $user?->created_at?->format('F Y') ?? 'October 2026',
            ],
            'navigation' => [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'paths' => '/dashboard', 'icon' => 'grid'],
                ['label' => 'Transactions', 'route' => 'transactions', 'paths' => '/transactions', 'icon' => 'swap'],
                ['label' => 'Budgets', 'route' => 'budgets', 'paths' => '/budgets', 'icon' => 'wallet'],
                ['label' => 'Savings', 'route' => 'savings', 'paths' => '/savings', 'icon' => 'target'],
                ['label' => 'Reports', 'route' => 'reports', 'paths' => '/reports', 'icon' => 'chart'],
            ],
        ];
    }

    /**
     * Format an amount as Philippine pesos.
     */
    protected function money(float|int|string $amount): string
    {
        return '₱'.number_format((float) $amount, 2);
    }
}

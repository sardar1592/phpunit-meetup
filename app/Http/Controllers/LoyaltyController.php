<?php

namespace App\Http\Controllers;

use App\Models\User;

class LoyaltyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(User $user)
    {
        return response()->json([

            'user_id' => $user->id,
            'loyalty_tier' => $user->loyaltyTier(),
            'orders_total' => $user->getTotalOrderAmountForUser(),
            'order_count' => $user->orders()->count(),
        ]);
    }
}

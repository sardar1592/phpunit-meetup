<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(User $user)
    {
        if (Auth::user()->id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([

            'user_id' => $user->id,
            'loyalty_tier' => $user->loyaltyTier(),
            'orders_total' => $user->getTotalOrderAmountForUser(),
            'order_count' => $user->orders()->count(),
        ]);
    }
}

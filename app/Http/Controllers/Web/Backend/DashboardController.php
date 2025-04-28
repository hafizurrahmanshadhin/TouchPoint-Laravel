<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\SystemSetting;
use App\Models\TouchPoint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller {
    /**
     * Display the dashboard page with key metrics.
     */
    public function index(): View {
        // Total users
        $totalUsers    = User::where('role', '!=', 'admin')->count();
        $activeUsers   = User::where('status', 'active')->where('role', '!=', 'admin')->count();
        $inactiveUsers = $totalUsers - $activeUsers;

        // Subscription plan breakdown
        $plans = Plan::where('status', 'active')->get()->map(function ($plan) {
            return [
                'name'  => ucfirst($plan->subscription_plan),
                'price' => $plan->price,
                'users' => $plan->userSubscription()->count(),
            ];
        });

        // Active subscriptions
        $activeSubs  = User::with('activeSubscription')->has('activeSubscription')->count();
        $expiredSubs = User::with('activeSubscription')->doesntHave('activeSubscription')->count();

        // TouchPoint summary
        $today        = Carbon::today();
        $overdueCount = TouchPoint::whereDate('touch_point_start_date', '<', $today)
            ->where('is_completed', false)->count();
        $todayCount     = TouchPoint::whereDate('touch_point_start_date', $today)->count();
        $upcomingCount  = TouchPoint::whereDate('touch_point_start_date', '>', $today)->count();
        $completedCount = TouchPoint::where('is_completed', true)->count();

        // System settings (your site metadata)
        $settings = SystemSetting::first();

        return view('backend.layouts.dashboard.index', compact(
            'totalUsers', 'activeUsers', 'inactiveUsers',
            'plans', 'activeSubs', 'expiredSubs',
            'overdueCount', 'todayCount', 'upcomingCount', 'completedCount',
            'settings'
        ));
    }
}

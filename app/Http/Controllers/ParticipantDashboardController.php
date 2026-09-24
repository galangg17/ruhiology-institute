<?php

namespace App\Http\Controllers;

use App\Models\AssessmentPeriod;

class ParticipantDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Active assessment periods
        $activePeriods = AssessmentPeriod::where('status', 'active')
            ->with(['program', 'instrument'])
            ->get();

        // User's participant records if linked by email or user_id
        $participantRecords = \App\Models\Participant::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->with(['institution', 'program'])
            ->get();

        $participantIds = $participantRecords->pluck('id');

        // Submissions
        $submissions = \App\Models\AssessmentSubmission::whereIn('participant_id', $participantIds)
            ->with(['period.instrument', 'result'])
            ->latest()
            ->get();

        // Training Registrations
        $trainingRegistrations = \App\Models\TrainingRegistration::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->with(['training', 'batch'])
            ->latest()
            ->get();

        // Orders
        $orders = \App\Models\Order::where('user_id', $user->id)
            ->orWhere('customer_email', $user->email)
            ->with('items')
            ->latest()
            ->get();

        return view('public.dashboard', compact(
            'user',
            'activePeriods',
            'participantRecords',
            'submissions',
            'trainingRegistrations',
            'orders'
        ));
    }
}

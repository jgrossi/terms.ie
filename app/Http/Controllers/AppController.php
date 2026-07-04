<?php

namespace App\Http\Controllers;

use App\Enums\SignatureStatus;
use App\Models\Signature;

class AppController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        $signaturesBase = Signature::whereHas('client', fn ($q) => $q->where('user_id', $user->id));

        return view('app.dashboard', [
            'termCount'          => $user->terms()->count(),
            'clientCount'        => $user->clients()->count(),
            'pendingCount'       => (clone $signaturesBase)->where('status', SignatureStatus::Pending)->count(),
            'signedCount'        => (clone $signaturesBase)->where('status', SignatureStatus::Signed)->count(),
            'pendingSignatures'  => (clone $signaturesBase)
                ->where('status', SignatureStatus::Pending)
                ->with(['client', 'termVersion.term'])
                ->latest()
                ->limit(10)
                ->get(),
            'recentSigned'       => (clone $signaturesBase)
                ->where('status', SignatureStatus::Signed)
                ->with(['client', 'termVersion.term'])
                ->latest('signed_at')
                ->limit(5)
                ->get(),
        ]);
    }
}

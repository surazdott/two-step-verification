<?php

namespace SurazDott\TwoStep\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use SurazDott\TwoStep\Notifications\TwoStepCodeNotification;

class TwoStepVerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Two Step verification
     *
     * @return View
     */
    public function verify(Request $request)
    {
        $user = Auth::user();

        if ($user->hasTwoStepVerification()) {
            if ($request->method() == 'GET') {
                return view('auth.twostep');
            } elseif ($request->method() == 'POST') {
                $request->validate([
                    'code' => 'required',
                ]);

                $code = implode('', $request->get('code'));

                if ($code == $user->getTwoStepCode()) {
                    if ($user->getTwoStepExpiry() < Carbon::now()) {
                        return redirect()
                            ->route('twostep.verify')
                            ->withErrors([
                                'code' =>  'The verification code you have entered is expired.'
                            ]);
                    }

                    $user->clearTwoStepCode();

                    return redirect()->intended('dashboard');
                } else {
                    return redirect()
                        ->route('twostep.verify')
                        ->withErrors([
                            'code' =>  'The verification code you have entered does not match.'
                        ]);
                }
            }
        } else {
            return redirect()->intended('dashboard');
        }
    }

    /**
     * Resend a two step authentication code
     *
     * @return void
     */
    public function resend()
    {
        $user = Auth::user();

        $executed = RateLimiter::attempt(
            'send-message:'.$user->id,
            $perTwoMinutes = 1,
            function () {
            },
            $decayRate = 120,
        );

        if (! $executed) {
            $seconds = RateLimiter::availableIn('send-message:'.$user->id);
            $message = 'You may try again in ' . $seconds . ' seconds.';

            return redirect()
                ->back()
                ->withErrors(['code' => $message]);
        }

        try {
            $user->resetTwoStepCode();
            $user->notify(new TwoStepCodeNotification());

            return redirect()
                ->back()
                ->with('success', 'The verification code has been sent again.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Unable to sent the verification code.');
        }
    }
}

<?php

namespace SurazDott\TwoStep\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use SurazDott\TwoStep\Models\TwoStepVerification as TwoStepVerify;
use SurazDott\TwoStep\Notifications\TwoStepCodeNotification;

trait TwoStepVerification
{
    /**
     * HasOne relationship
     *
     * @return void
     */
    public function twoStepVerification(): HasOne
    {
        return $this->hasOne(TwoStepVerify::class);
    }

    /**
     * Check user has verification code or not
     *
     * @return boolean
     */
    public function hasTwoStepVerification(): bool
    {
        $twoStepVerification = $this->twoStepVerification();

        return $twoStepVerification->count() == null ? false : true;
    }

    /**
     * Get verification code
     *
     * @return string|null
     */
    public function getTwoStepCode(): ?string
    {
        $twoStepVerification = $this->twoStepVerification();

        return $this->hasTwoStepVerification() == true ?
            $twoStepVerification->first()->code : null;
    }

    /**
     * Generate verification exoiry datetime
     *
     * @return string|null
     */
    public function getTwoStepExpiry(): ?string
    {
        $twoStepVerification = $this->twoStepVerification();

        return $this->hasTwoStepVerification() == true ?
            $twoStepVerification->first()->expires_at : null;
    }

    /**
     * Generate verification code
     *
     * @return void
     */
    public function generateTwoStepCode(): void
    {
        $twoStepVerification = $this->twoStepVerification();

        if ($this->hasTwoStepVerification() == false) {

            // Create verification code
            $twoStepVerification->create([
                'code' => rand(1000, 9999),
                'expires_at' => $this->expiresAt(),
            ]);

            // Send verification mail
            $this->notify(new TwoStepCodeNotification());
        } else {
            // Reset verification code
            $this->resetTwoStepCode();

            // Send reset code in mail
            $this->notify(new TwoStepCodeNotification());
        }
    }

    /**
     * Reset verification code
     *
     * @return void
     */
    public function resetTwoStepCode(): void
    {
        $twoStepVerification = $this->twoStepVerification;
        $twoStepVerification->code = rand(1000, 9999);
        $twoStepVerification->expires_at = $this->expiresAt();
        $twoStepVerification->save();
    }

    /**
     * Clear verification code
     *
     * @return void
     */
    public function clearTwoStepCode(): void
    {
        $twoStepVerification = $this->twoStepVerification;
        $twoStepVerification->delete();
    }

    /**
     * Expire code after defined time
     * Default expiresAt = 10 minutes
     *
     * @param $minutes string
     * @return datetime
     */
    private function expiresAt($minutes = 10): ?string
    {
        $this->expiresAt = Carbon::now()->addMinutes($minutes);

        return $this->expiresAt;
    }
}

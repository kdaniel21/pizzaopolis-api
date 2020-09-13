<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Order;

class Coupon extends Model {
    protected $guarded = [];
    protected $appends = ['valid', 'timesUsed'];

    public function timesUsed() {
        return $this->hasMany(Order::class)->where('status', 'finished')->count();
    }

    public function isValid() {
        // Check times used (count only finished orders)
        $hasReachedMaxUsage = $this->timesUsed() >= $this->max_times_used;

        // Check expiration
        $isExpired = $this->expires_at < Carbon::now();

        // Check whether it is active
        $isActive = $this->active;

        return !$hasReachedMaxUsage && !$isExpired && $isActive;
    }

    public function getValidAttribute() {
        return $this->isValid();
    }

    public function getTimesUsedAttribute() {
        return $this->timesUsed();
    }
}

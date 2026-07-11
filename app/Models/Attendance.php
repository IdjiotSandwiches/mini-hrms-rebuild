<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon $check_in
 * @property Carbon|null $check_out
 * @property float $duration
 * @property bool $early
 * @property bool $late
 * @property bool $absence
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['check_in', 'check_out', 'duration', 'early', 'late', 'absence', 'user_id'])]
class Attendance extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'check_in' => 'datetime',
            'check_out' => 'datetime',
            'duration' => 'float',
        ];
    }

    /**
     * User relation
     *
     * @return BelongsTo<User, Attendance>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

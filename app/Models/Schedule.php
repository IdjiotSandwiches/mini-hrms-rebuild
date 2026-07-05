<?php

namespace App\Models;

use App\Enums\DayEnum;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property DayEnum $day
 * @property Carbon|null $start_time
 * @property Carbon|null $end_time
 * @property int $work_time
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['day', 'start_time', 'end_time', 'work_time', 'user_id'])]
class Schedule extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'day' => DayEnum::class
        ];
    }

    /**
     * User relation
     *
     * @return BelongsTo<User, Schedule>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

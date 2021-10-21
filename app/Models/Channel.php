<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Channel.
 *
 * @property int $id
 * @property int $enabled
 * @property mixed|null $valid_plans
 * @property int $sync
 * @property string $sync_option
 * @property int $whitelist_dirty
 * @property int $requests
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property \App\Models\TwitchUser|null $owner
 * @property Collection|\App\Models\RequestStat[] $stats
 * @property int|null $stats_count
 * @property Collection|\App\Models\Whitelist[] $whitelist
 * @property int|null $whitelist_count
 *
 * @method static Builder|Channel newModelQuery()
 * @method static Builder|Channel newQuery()
 * @method static Builder|Channel query()
 * @method static Builder|Channel whereCreatedAt($value)
 * @method static Builder|Channel whereEnabled($value)
 * @method static Builder|Channel whereId($value)
 * @method static Builder|Channel whereRequests($value)
 * @method static Builder|Channel whereSync($value)
 * @method static Builder|Channel whereSyncOption($value)
 * @method static Builder|Channel whereUpdatedAt($value)
 * @method static Builder|Channel whereValidPlans($value)
 * @method static Builder|Channel whereWhitelistDirty($value)
 */
class Channel extends Model
{
    public function owner(): HasOne
    {
        return $this->hasOne(TwitchUser::class);
    }

    public function whitelist(): HasMany
    {
        return $this->hasMany(Whitelist::class);
    }

    public function stats(): HasMany
    {
        return $this->hasMany(RequestStat::class);
    }

    protected static function booted()
    {
        static::deleting(function (Channel $channel) {
            $channel->whitelist->each(function (Whitelist $whitelist) {
                $whitelist->delete();
            });
        });
    }
}

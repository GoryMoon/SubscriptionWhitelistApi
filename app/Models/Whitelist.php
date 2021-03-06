<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Whitelist
 *
 * @property int $id
 * @property string $username
 * @property int $valid
 * @property int|null $user_id
 * @property int $channel_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $minecraft_id
 * @property int|null $steam_id
 * @property-read Channel $channel
 * @property-read mixed $hash_id
 * @property-read bool $is_subscriber
 * @property-read array $status
 * @property-read MinecraftUser|null $minecraft
 * @property-read SteamUser|null $steam
 * @property-read TwitchUser|null $user
 * @method static Builder|Whitelist newModelQuery()
 * @method static Builder|Whitelist newQuery()
 * @method static Builder|Whitelist query()
 * @method static Builder|Whitelist whereChannelId($value)
 * @method static Builder|Whitelist whereCreatedAt($value)
 * @method static Builder|Whitelist whereId($value)
 * @method static Builder|Whitelist whereMinecraftId($value)
 * @method static Builder|Whitelist whereSteamId($value)
 * @method static Builder|Whitelist whereUpdatedAt($value)
 * @method static Builder|Whitelist whereUserId($value)
 * @method static Builder|Whitelist whereUsername($value)
 * @method static Builder|Whitelist whereValid($value)
 */
class Whitelist extends Model
{

    protected $hidden = ['id', 'user_id', 'channel_id', 'created_at', 'updated_at', 'valid', 'minecraft', 'minecraft_id', 'steam', 'steam_id'];
    protected $appends = ['hash_id', 'is_subscriber', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(TwitchUser::class, 'user_id');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function minecraft(): BelongsTo
    {
        return $this->belongsTo(MinecraftUser::class, 'minecraft_id');
    }

    public function steam(): BelongsTo
    {
        return $this->belongsTo(SteamUser::class, 'steam_id');
    }

    public function getIsSubscriberAttribute(): bool
    {
        return $this->user_id != null;
    }

    public function getHashIdAttribute(): string
    {
        return app('hashids')->connection('whitelist')->encode($this->id);
    }

    /**
     * @return array
     */
    public function getStatusAttribute(): array
    {
        $minecraft = $this->minecraft;
        $name = "";
        if (!is_null($minecraft)) {
            $name = $minecraft->username;
        }
        return ['valid'  => $this->valid == true, 'minecraft' => $name, 'steam' => isset($this->steam)];
    }
}

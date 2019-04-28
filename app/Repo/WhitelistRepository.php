<?php


namespace App\Repo;


use App\Models\Channel;
use App\Models\Whitelist;
use Illuminate\Support\Collection;

class WhitelistRepository
{

    /**
     * @var Channel
     */
    private $channel;

    /**
     * @param string $id
     * @return Channel|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getChannel($id) {
        return $this->channel = Channel::whereId($id)->first();
    }

    /**
     * @param string $type
     * @param string $id
     * @return mixed
     */
    public function getWhitelist($type, $id) {
        $dirty = $this->channel->whitelist_dirty;
        $key = $type . '-' . $id;

        if (app('cache')->has($key)) {
            if ($dirty) {
                app('cache')->forget($key);
            } else {
                return app('cache')->get($key);
            }
        }
        $this->channel->whitelist_dirty = false;
        $this->channel->save();

        $whitelist = $this->channel->whitelist;
        $list = $this->$type($whitelist);

        app('cache')->put($key, $list, 1800);
        return $list;
    }

    /**
     * @param Collection $list
     * @return mixed
     */
    private function process($list) {
        return $list->filter([$this, 'filterValid'])->map([$this, 'mapUsername'])->flatten()->toArray();
    }

    /**
     * @param Whitelist $value
     * @return bool
     */
    public function filterValid($value) {
        return $value->valid;
    }

    /**
     * @param Whitelist $value
     * @return string mixed
     */
    public function mapUsername($value) {
        return $value->username;
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function csv(Collection $list) {
        return join(',', $this->process($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function nl(Collection $list) {
        return join("\n", $this->process($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function json_array(Collection $list) {
        return json_encode($this->process($list));
    }

}
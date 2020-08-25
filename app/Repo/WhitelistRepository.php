<?php


namespace App\Repo;


use App\Models\Channel;
use App\Models\RequestStat;
use App\Models\Whitelist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WhitelistRepository
{

    /**
     * @var Channel
     */
    private $channel;

    /**
     * @param string $id
     * @return Channel|Builder|Model|object|null
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

        $stat = new RequestStat;
        $stat->channel()->associate($this->channel);
        $stat->save();
        $this->channel->requests++;

        if (app('cache')->tags($id)->has($key)) {
            if ($dirty) {
                app('cache')->tags($id)->flush();
            } else {
                $this->channel->save();
                return app('cache')->tags($id)->get($key);
            }
        }
        $this->channel->whitelist_dirty = false;
        $this->channel->save();

        $whitelist = $this->channel->whitelist()->with('minecraft')->get();
        $list = $this->$type($whitelist);

        app('cache')->tags($id)->put($key, $list, 1800);
        return $list;
    }



    /**
     *
     * Process to filter and map values
     *
     */

    /**
     * @param Collection $list
     * @return mixed
     */
    private function process($list) {
        return $list->filter([$this, 'filterValid'])->map([$this, 'mapUsername'])->toArray();
    }

    /**
     * @param Collection $list
     * @return Collection
     */
    private function minecraftProcess($list) {
        return $list->filter([$this, 'filterValid'])->filter([$this, 'filterMinecraft']);
    }

    /**
     * @param Collection $list
     * @return mixed
     */
    private function minecraftUuidProcess($list) {
        return $this->minecraftProcess($list)->map([$this, 'mapMinecraftUuid'])->flatten()->toArray();
    }

    /**
     * @param Collection $list
     * @return mixed
     */
    private function minecraftNameProcess($list) {
        return $this->minecraftProcess($list)->map([$this, 'mapMinecraftName'])->flatten()->toArray();
    }

    /**
     * @param Collection $list
     * @return mixed
     */
    private function minecraftWhitelistProcess($list) {
        return $this->minecraftProcess($list)->map([$this, 'mapMinecraftWhitelist'])->values()->toArray();
    }

    /**
     * @param Collection $list
     * @return mixed
     */
    private function steamProcess($list) {
        return $list->filter([$this, 'filterValid'])->filter([$this, 'filterSteam'])->map([$this, 'mapSteam'])->flatten()->toArray();
    }



    /**
     *
     * Value filtering
     *
     */

    /**
     * @param Whitelist $value
     * @return bool
     */
    public function filterValid($value) {
        return $value->valid;
    }

    /**
     * @param Whitelist $value
     * @return bool
     */
    public function filterMinecraft($value) {
        return !is_null($value->minecraft);
    }

    /**
     * @param Whitelist $value
     * @return bool
     */
    public function filterSteam($value) {
        return !is_null($value->steam);
    }



    /**
     *
     * Value mapping
     *
     */

    /**
     * @param Whitelist $value
     * @return string
     */
    public function mapUsername($value) {
        return $value->username;
    }

    /**
     * @param Whitelist $value
     * @return string
     */
    public function mapMinecraftUuid($value) {
        return $value->minecraft->uuid;
    }

    /**
     * @param Whitelist $value
     * @return string
     */
    public function mapMinecraftName($value) {
        return $value->minecraft->username;
    }

    /**
     * @param Whitelist $value
     * @return array
     */
    public function mapMinecraftWhitelist($value) {
        return ['uuid' => $value->minecraft->uuid, 'name' => $value->minecraft->username];
    }

    /**
     * @param Whitelist $value
     * @return string
     */
    public function mapSteam($value) {
        return $value->steam->steam_id;
    }



    /**
     *
     * List assembly
     *
     */

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

    /**
     * @param Collection $list
     * @return string
     */
    public function minecraft_uuid_csv(Collection $list) {
        return join(',', $this->minecraftUuidProcess($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function minecraft_uuid_nl(Collection $list) {
        return join("\n", $this->minecraftUuidProcess($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function minecraft_uuid_json_array(Collection $list) {
        return json_encode($this->minecraftUuidProcess($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function minecraft_csv(Collection $list) {
        return join(',', $this->minecraftNameProcess($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function minecraft_nl(Collection $list) {
        return join("\n", $this->minecraftNameProcess($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function minecraft_json_array(Collection $list) {
        return json_encode($this->minecraftNameProcess($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function minecraft_whitelist(Collection $list) {
        return json_encode($this->minecraftWhitelistProcess($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function steam_csv(Collection $list) {
        return join(',', $this->steamProcess($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function steam_nl(Collection $list) {
        return join("\n", $this->steamProcess($list));
    }

    /**
     * @param Collection $list
     * @return string
     */
    public function steam_json_array(Collection $list) {
        return json_encode($this->steamProcess($list));
    }

}

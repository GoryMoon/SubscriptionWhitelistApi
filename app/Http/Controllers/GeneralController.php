<?php

namespace App\Http\Controllers;

use App\Repo\WhitelistRepository;

class GeneralController extends Controller
{

    /**
     * @var WhitelistRepository
     */
    private $repository;

    public function __construct(WhitelistRepository $repository)
    {
        $this->repository = $repository;
    }

    private function handleRequest(string $id, string $type, string $content) {
        $ids = app('hashids')->decode($id);
        if (count($ids) > 0 && !is_null($ids[0])) {
            $channel = $this->repository->getChannel($ids[0]);
            if (!is_null($channel)) {
                $list = $this->repository->getWhitelist($channel, $type, $id);
                return response($list, 200, ['Content-Type' =>  $content . "; charset=UTF-8"]);
            }
        }
        return response()->json([
            "message" => "Id not found"
        ], 404);
    }

    public function csv(string $id) {
        return $this->handleRequest($id, 'csv', 'text/csv');
    }

    public function nl(string $id) {
        return $this->handleRequest($id, 'nl', 'text/plain');
    }

    public function json_array(string $id) {
        return $this->handleRequest($id, 'json_array', 'application/json');
    }

    public function minecraft_uuid_csv(string $id) {
        return $this->handleRequest($id, 'minecraft_uuid_csv', 'text/csv');
    }

    public function minecraft_uuid_nl(string $id) {
        return $this->handleRequest($id, 'minecraft_uuid_nl', 'text/plain');
    }

    public function minecraft_uuid_json_array(string $id) {
        return $this->handleRequest($id, 'minecraft_uuid_json_array', 'application/json');
    }

    public function minecraft_csv(string $id) {
        return $this->handleRequest($id, 'minecraft_csv', 'text/csv');
    }

    public function minecraft_nl(string $id) {
        return $this->handleRequest($id, 'minecraft_nl', 'text/plain');
    }

    public function minecraft_twitch_nl(string $id) {
        return $this->handleRequest($id, 'minecraft_twitch_nl', 'text/plain');
    }

    public function minecraft_json_array(string $id) {
        return $this->handleRequest($id, 'minecraft_json_array', 'application/json');
    }

    public function minecraft_whitelist(string $id) {
        return $this->handleRequest($id, 'minecraft_whitelist', 'application/json');
    }

    public function steam_csv(string $id) {
        return $this->handleRequest($id, 'steam_csv', 'text/csv');
    }

    public function steam_nl(string $id) {
        return $this->handleRequest($id, 'steam_nl', 'text/plain');
    }

    public function steam_json_array(string $id) {
        return $this->handleRequest($id, 'steam_json_array', 'application/json');
    }

}

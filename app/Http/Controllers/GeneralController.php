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

    private function handleRequest($id, $type, $content) {
        $ids = app('hashids')->decode($id);
        if (count($ids) > 0 && !is_null($ids[0])) {
            $channel = $this->repository->getChannel($ids[0]);
            if (!is_null($channel)) {
                $list = $this->repository->getWhitelist($type, $id);
                return response($list, 200, ['Content-Type' =>  $content . "; charset=UTF-8"]);
            }
        }
        return response()->json([
            "message" => "Id not found"
        ], 404);
    }

    public function csv($id) {
        return $this->handleRequest($id, 'csv', 'text/csv');
    }

    public function nl($id) {
        return $this->handleRequest($id, 'nl', 'text/plain');
    }

    public function json_array($id) {
        return $this->handleRequest($id, 'json_array', 'application/json');
    }


}

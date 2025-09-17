<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use CodeIgniter\HTTP\ResponseInterface;

class CampaignController extends BaseController
{
    public function index()
    {
        $model = new MemberModel();

        // get all rows from the SQL view
        // you can add ->where(...) or ->limit(...) as needed
        $rows = $model->findAll();

        // group rows by groupId and normalize label
        $markets = [];
        foreach ($rows as $r) {
            // Determine the market key (adjust key name if your view uses another column)
            $key = $r['groupId'];

            // Derive a label – prefer groupName, then label, otherwise prettify key
            $label = $r['groupName'];

            // ensure the row contains groupName and label (so view JS can use either)
            $r['groupName'] = $label;
            // $r['label'] = $label;

            if (! isset($markets[$key])) {
                $markets[$key] = [];
            }
            $markets[$key][] = $r;
        }

        // Pass to your view (the view we gave earlier expects $markets grouped like this)
        return view('send-rcs', ['markets' => $markets]);
    }

    public function marketsJson()
    {
        $model = new MemberModel();
        $rows = $model->findAll();

        $markets = [];
        foreach ($rows as $r) {
            $key = $r['groupId'];
            $label = $r['groupName'];
            $r['groupName'] = $label;

            $markets[$key][] = $r;
        }

        return $this->response->setJSON($markets);
    }
}

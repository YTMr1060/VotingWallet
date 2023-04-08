<?php

namespace Azuriom\Plugin\Votingwallet\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Setting;
use Azuriom\Plugin\Vote\Models\Vote;
use Azuriom\Plugin\Votingwallet\Models\VotingWithdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show the home admin page of the plugin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vote_value = setting('vote_value', 0);

        $votes = Vote::orderBy("created_at", "ASC")->get()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m');
        });
        $graph_labels_generated = [];
        $graph_data_generated = [];

        foreach ($votes as $month => $vote) {
            array_push($graph_labels_generated, $month);
            array_push($graph_data_generated, round((count($vote) * $vote_value), 2));
        }

        $voting_withdraw = VotingWithdraw::orderBy("created_at", "ASC")->get()->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m');
        });
        $graph_labels_earned = [];
        $graph_data_earned = [];

        foreach ($voting_withdraw as $month => $withdraws) {
            foreach ($withdraws as $withdraw) {
                if (!in_array($month, $graph_labels_earned)) {
                    array_push($graph_labels_earned, $month);
                }
                if (!isset($graph_data_earned[$month])) {
                    $graph_data_earned[$month] = 0;
                }
                $graph_data_earned[$month] += $withdraw["amount_euro"];
            }
        }

        $voting_withdraw_list = VotingWithdraw::get();
        $users_withdraw = [];

        foreach ($voting_withdraw_list as $withdraw) {
            if (!isset($users_withdraw[$withdraw["user_id"]])) {
                $users_withdraw[$withdraw["user_id"]] = 0;
            }
            $users_withdraw[$withdraw["user_id"]] += $withdraw["amount_euro"];
        }

        return view('votingwallet::admin.index', [
            'graph_labels_generated' => json_encode($graph_labels_generated),
            'graph_data_generated' => json_encode($graph_data_generated),
            'graph_labels_earned' => json_encode($graph_labels_earned),
            'graph_data_earned' => json_encode($graph_data_earned),
            'users_withdraw' => $users_withdraw,
            'vote_value' => $vote_value,
        ]);
    }

    public function settings(Request $request)
    {
        Setting::updateSettings('vote_value', $request->input('vote_value', 0));
        Setting::updateSettings('reset_wallet', $request->input('reset_wallet', "no"));
        Setting::updateSettings('wallet_command', $request->input('wallet_command', "opteco add {player} {amount}"));
        Setting::updateSettings('monetary_value', $request->input('monetary_value', "100"));
        Setting::updateSettings('give_other_players', $request->input('give_other_players', "no"));
        Setting::updateSettings('name_token', $request->input('name_token', "MoonShard"));

        return redirect(route('votingwallet.admin.index'));
    }
}

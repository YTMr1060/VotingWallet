<?php

namespace Azuriom\Plugin\Votingwallet\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Server;
use Azuriom\Plugin\Votingwallet\Models\VotingWithdraw;
use Azuriom\Plugin\Vote\Models\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VotingwalletHomeController extends Controller
{

    /**
     * Show the home plugin page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $votes = Vote::select(['user_id', DB::raw('count(*) as count')])->where("user_id", Auth::user()->id)->groupBy('user_id');
            $taked_token = VotingWithdraw::where('user_id', Auth::user()->id);

            if (setting('reset_wallet', "no") == "yes") {
                $votes = $votes->whereBetween('created_at', [now()->startOfMonth(), $toDate ?? now()]);
                $taked_token = $taked_token->whereBetween('created_at', [now()->startOfMonth(), $toDate ?? now()]);
            }

            $votes = $votes->count();
            $taked_token = $taked_token->get();

            $already_take = 0;
            foreach ($taked_token as $take) {
                $already_take += $take["amount_euro"];
            }

            $vote_value = setting('vote_value', 0);

            $wallet = ($votes * $vote_value) - $already_take;

            $servers_list = Server::all();

            return view('votingwallet::index', [
                'wallet' => $wallet,
                'servers_list' => $servers_list,
            ]);
        }
        else {
            return redirect(route('home'));
        }
    }

    public function withdraw(Request $request)
    {
        if (Auth::check()) {
            $votes = Vote::select(['user_id', DB::raw('count(*) as count')])->where("user_id", Auth::user()->id)->groupBy('user_id');
            $taked_token = VotingWithdraw::where('user_id', Auth::user()->id);

            if (setting('reset_wallet', "no") == "yes") {
                $votes = $votes->whereBetween('created_at', [now()->startOfMonth(), $toDate ?? now()]);
                $taked_token = $taked_token->whereBetween('created_at', [now()->startOfMonth(), $toDate ?? now()]);
            }

            $votes = $votes->count();
            $taked_token = $taked_token->get();

            $already_take = 0;
            foreach ($taked_token as $take) {
                $already_take += $take["amount_euro"];
            }

            $vote_value = setting('vote_value', 0);
            $monetary_value = setting('monetary_value', 100);
            $wallet_command = setting('wallet_command', "opteco add {player} {amount}");

            $wallet = ($votes * $vote_value) - $already_take;

            $cent_monetary = $monetary_value / 100;
            $cent_vote = $wallet * 100;

            $username = Auth::user()->name;
            $withdraw = $cent_vote * $cent_monetary;

            if ($withdraw > 0) {
                $wallet_command = str_replace('{player}', $username, $wallet_command);
                $wallet_command = str_replace('{amount}', $withdraw, $wallet_command);

                $server_id = $request->input("server_selected", 1);

                VotingWithdraw::giveReward(Auth::user(), [$wallet_command], $server_id);

                VotingWithdraw::create([
                    "user_id" => Auth::user()->id,
                    "amount_token" => $withdraw,
                    "amount_euro" => $cent_vote / 100,
                ]);
            }

            return redirect(route('votingwallet.index'));
        }
        else {
            return redirect(route('home'));
        }
    }

    /**
     * Show the home plugin page.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistical()
    {
        if (Auth::check()) {
            $votes = Vote::where("user_id", Auth::user()->id)->orderBy("created_at", "ASC")->get()->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m');
            });
            $vote_value = setting('vote_value', 0);

            $graph_labels = [];
            $graph_data = [];

            foreach ($votes as $month => $vote) {
                array_push($graph_labels, $month);
                array_push($graph_data, round((count($vote) * $vote_value), 2));
            }

            return view('votingwallet::statistical', [
                'graph_labels' => json_encode($graph_labels),
                'graph_data' => json_encode($graph_data),
                'vote_value' => $vote_value,
            ]);
        }
        else {
            return redirect(route('home'));
        }
    }

    /**
     * Show the home plugin page.
     *
     * @return \Illuminate\Http\Response
     */
    public function history()
    {
        if (Auth::check()) {
            $taked_token = VotingWithdraw::where('user_id', Auth::user()->id)->get();
            $name_token = setting('name_token', "MoonShard");

            return view('votingwallet::history', [
                "taked_token" => $taked_token,
                "name_token" => $name_token,
            ]);
        }
        else {
            return redirect(route('home'));
        }
    }
}

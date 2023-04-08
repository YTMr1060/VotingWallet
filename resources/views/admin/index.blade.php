@extends('admin.layouts.admin')

@section('title', trans('votingwallet::admin.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">

            <form action="{{ route('votingwallet.admin.settings') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label" for="vote_value">{{ trans('votingwallet::admin.vote_value') }}</label>
                            <input class="form-control" id="vote_value" name="vote_value" value="{{ setting('vote_value', 0) }}" required="required">
                            <span class="help-block">{{ trans('votingwallet::admin.vote_value_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label" for="reset_wallet">{{ trans('votingwallet::admin.reset_wallet') }}</label>
                            <select class="form-control" name="reset_wallet" id="reset_wallet">
                                <option {{ (setting('reset_wallet', "no") == "yes") ? "selected" : "" }} value="yes">{{ trans('messages.yes') }}</option>
                                <option {{ (setting('reset_wallet', "no") == "no") ? "selected" : "" }} value="no">{{ trans('messages.no') }}</option>
                            </select>
                            <span class="help-block">{{ trans('votingwallet::admin.reset_wallet_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label" for="wallet_command">{{ trans('votingwallet::admin.wallet_command') }}</label>
                            <input class="form-control" id="wallet_command" name="wallet_command" value="{{ setting('wallet_command', "opteco add {player} {amount}") }}" required="required">
                            <span class="help-block">{!! trans('votingwallet::admin.wallet_command_helper') !!}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label" for="monetary_value">{{ trans('votingwallet::admin.monetary_value') }}</label>
                            <input class="form-control" id="monetary_value" name="monetary_value" value="{{ setting('monetary_value', "100") }}" required="required">
                            <span class="help-block">{!! trans('votingwallet::admin.monetary_value_helper') !!}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label" for="give_other_players">{{ trans('votingwallet::admin.give_other_players') }}</label>
                            <select class="form-control" name="give_other_players" id="give_other_players" disabled>
                                <option {{ (setting('give_other_players', "no") == "yes") ? "selected" : "" }} value="yes">{{ trans('messages.yes') }}</option>
                                <option {{ (setting('give_other_players', "no") == "no") ? "selected" : "" }} value="no">{{ trans('messages.no') }}</option>
                            </select>
                            <span class="help-block">{{ trans('votingwallet::admin.give_other_players_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label" for="name_token">{{ trans('votingwallet::admin.name_token') }}</label>
                            <input class="form-control" id="name_token" name="name_token" value="{{ setting('name_token', "MoonShard") }}" required="required">
                            <span class="help-block">{{ trans('votingwallet::admin.name_token_helper') }}</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>

            </form>

        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <h1>{{ trans('votingwallet::admin.money_generated_with_votes') }}</h1>
                    <canvas id="money_generated"></canvas>
                </div>
                <div class="col-6">
                    <h1>{{ trans('votingwallet::admin.money_earned_with_votes') }}</h1>
                    <canvas id="money_earned"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <h1>{{ trans('votingwallet::admin.money_earned_with_votes') }}</h1>
                    <table id="table_money_earned" class="display">
                        <thead>
                        <tr>
                            <th>{{ trans('votingwallet::admin.player') }}</th>
                            <th>{{ trans('votingwallet::admin.amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users_withdraw as $user_id => $withdraw_list)
                            <tr>
                                <td>{{ \Azuriom\Plugin\Votingwallet\Models\VotingWithdraw::get_user_name($user_id) ?? "N/A" }}</td>
                                <td>{{ $withdraw_list ?? "N/A" }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@push('footer-scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('money_generated');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! $graph_labels_generated !!},
                datasets: [{
                    label: '{{ trans('votingwallet::admin.money_generated_with_votes') }}',
                    data: {!! $graph_data_generated !!},
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        const ctx_2 = document.getElementById('money_earned');
        new Chart(ctx_2, {
            type: 'line',
            data: {
                labels: {!! $graph_labels_earned !!},
                datasets: [{
                    label: '{{ trans('votingwallet::admin.money_generated_with_votes') }}',
                    data: {!! $graph_data_earned !!},
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        $('#table_money_earned').DataTable( {
            "bLengthChange": false,
            "pageLength": 10,
            "order": [[1, 'desc']],
        } );
    </script>
@endpush

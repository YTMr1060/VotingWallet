@extends('layouts.app')

@section('title', trans('votingwallet::front.title_page'))

@section('content')

    <div class="card text-center">
        @include("votingwallet::navbar_front")
        <div class="card-body">
            <div>
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

@endsection


@push('footer-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! $graph_labels !!},
                datasets: [{
                    label: '{{ trans('votingwallet::front.money_generated_with_votes') }}',
                    data: {!! $graph_data !!},
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
    </script>
@endpush

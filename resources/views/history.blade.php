@extends('layouts.app')

@section('title', trans('votingwallet::front.title_page'))

@section('content')

    <div class="card text-center">
        @include("votingwallet::navbar_front")
        <div class="card-body">
            <table id="myTable" class="display">
                <thead>
                <tr>
                    <th>{{ trans('votingwallet::front.id') }}</th>
                    <th>{{ $name_token }}</th>
                    <th>{{ trans('votingwallet::front.euro') }}</th>
                    <th>{{ trans('votingwallet::front.date') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($taked_token as $take)
                    <tr>
                        <td>{{ $take["id"] ?? "N/A" }}</td>
                        <td>{{ $take["amount_token"] ?? "N/A" }}</td>
                        <td>{{ $take["amount_euro"] ?? "N/A" }}</td>
                        <td>{{ date('d-m-Y h:i:s', strtotime($take["created_at"])) ?? "N/A" }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('styles')
    <link href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@push('footer-scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        let table = new DataTable('#myTable');
    </script>
@endpush

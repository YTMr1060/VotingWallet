@extends('layouts.app')

@section('title', trans('votingwallet::front.title_page'))

@section('content')

    <div class="card text-center">
        @include("votingwallet::navbar_front")
        <div class="card-body">
            <h5 class="card-title">{{ trans('votingwallet::front.first_line', ["amount" => $wallet]) }}</h5>
            <form action="{{ route('votingwallet.withdraw') }}" method="POST">
                @csrf
                <select class="select" name="server_selected" id="server_selected" @if($servers_list->count() == 1) style="display: none" @endif>
                    @foreach($servers_list as $server_list)
                        <option @if($loop->first) selected @endif value="{{ $server_list->id }}">{{ $server_list->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary {{ ($wallet == 0) ? "disabled" : "" }}">{{ trans('votingwallet::front.withdraw') }}</button>
            </form>
        </div>
    </div>

@endsection

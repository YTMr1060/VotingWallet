<div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
        <li class="nav-item">
            <a class="nav-link {{ request()->is("votingwallet") ? "active" : "" }}" href="{{ route('votingwallet.index') }}">{{ trans('votingwallet::front.wallet') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is("votingwallet/statistical") ? "active" : "" }}" href="{{ route('votingwallet.statistical') }}">{{ trans('votingwallet::front.statistical') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is("votingwallet/history") ? "active" : "" }}" href="{{ route('votingwallet.history') }}">{{ trans('votingwallet::front.history') }}</a>
        </li>
    </ul>
</div>

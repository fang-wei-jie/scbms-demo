<div class="card-body" wire:poll.10000ms>
    <div class="row">
        <div class="col">
            <h3 class="card-title">Rates Enabled</h3>
            Updated on {{ date('H:i:s') }}
        </div>
        <div class="col-auto">
            <a href="{{ route('manager.rates') }}" class="btn btn-primary">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-tags-fill"></i>
                    <span class="d-none d-md-block">&nbsp;Rates</span>
                </span>
            </a>
        </div>
    </div>

    <div class="my-2"></div>

    @foreach ($ratesEnabled as $ratesDetail)
        <div class="card py-2">
            <div class="mx-3 my-1">
                <div class="row no-gutters align-items-center">
                    <div class="col d-flex justify-content-between">
                        <div class="text-xs fw-bold text-primary mb-1">
                            {{ $ratesDetail->rateName }}
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            RM {{ $ratesDetail->ratePrice }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-2"></div>
    @endforeach
</div>

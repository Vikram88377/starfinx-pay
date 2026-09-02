<div class="dropdown d-inline-block">
    <button
        class="btn btn-secondary dropdown-toggle"
        type="button"
        data-bs-toggle="dropdown"
    >
        Filters
    </button>

    <div class="dropdown-menu p-3" style="min-width: 300px;">

        <form method="GET">

            {{-- Status --}}
            <div class="mb-3">
                <label class="form-label">Status</label>

                <select name="status" class="form-control">
                    <option value="">All Status</option>

                    <option value="PENDING"
                        {{ request('status') == 'PENDING' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="SUCCESS"
                        {{ request('status') == 'SUCCESS' ? 'selected' : '' }}>
                        Success
                    </option>

                    <option value="FAILED"
                        {{ request('status') == 'FAILED' ? 'selected' : '' }}>
                        Failed
                    </option>
                </select>
            </div>

            {{-- Merchant --}}
            <div class="mb-3">
                <label class="form-label">Merchant</label>

                <select name="merchant_id" class="form-control">
                    <option value="">All Merchants</option>

                    @foreach(\App\Models\Merchant::orderBy('name')->get() as $merchant)
                        <option
                            value="{{ $merchant->id }}"
                            {{ request('merchant_id') == $merchant->id ? 'selected' : '' }}
                        >
                            {{ $merchant->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date --}}
            <div class="mb-3">
                <label class="form-label">Date</label>

                <input
                    type="date"
                    name="date"
                    class="form-control"
                    value="{{ request('date') }}"
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Apply
            </button>

            <a
                href="{{ url(config('backpack.base.route_prefix') . '/payin') }}"
                class="btn btn-secondary"
            >
                Clear
            </a>

        </form>

    </div>
</div>

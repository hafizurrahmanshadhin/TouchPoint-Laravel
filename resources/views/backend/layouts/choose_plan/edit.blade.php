@extends('backend.app')

@section('title', 'Edit choose plan')

@section('content')

<div class="page-content">
    <div class="container-fluid">
        {{-- start page title --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('choose.plan.index')}}">ChoosePlan
                                    Section</a>
                            </li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        {{-- end page title --}}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">


                        <form action="{{ route('choose.plan.update', $choosePlan->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row gy-2">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="plan" class="form-label">Plan:</label>
                                            <select class="form-control @error('plan') is-invalid @enderror" id="plan" name="plan">
                                                <option value="">-- Select Plan --</option>
                                                
                                                <option value="free" 
                                                    {{ old('plan', $choosePlan->plan) == 'free' ? 'selected' : '' }}
                                                    {{ in_array('free', $usedPlans ?? []) ? 'disabled style=opacity:0.5;' : '' }}>
                                                    Free
                                                </option>
                                            
                                                <option value="monthly" 
                                                    {{ old('plan', $choosePlan->plan) == 'monthly' ? 'selected' : '' }}
                                                    {{ in_array('monthly', $usedPlans ?? []) ? 'disabled style=opacity:0.5;' : '' }}>
                                                    Monthly
                                                </option>
                                            
                                                <option value="yearly" 
                                                    {{ old('plan', $choosePlan->plan) == 'yearly' ? 'selected' : '' }}
                                                    {{ in_array('yearly', $usedPlans ?? []) ? 'disabled style=opacity:0.5;' : '' }}>
                                                    Yearly
                                                </option>
                                            
                                                <option value="lifetime" 
                                                    {{ old('plan', $choosePlan->plan) == 'lifetime' ? 'selected' : '' }}
                                                    {{ in_array('lifetime', $usedPlans ?? []) ? 'disabled style=opacity:0.5;' : '' }}>
                                                    Lifetime
                                                </option>
                                            </select>
                                            
                                            @error('plan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                        
                                        <div class="col-md-6">
                                            <label for="price" class="form-label">Price:</label>
                                            <input type="number" 
                                                class="form-control @error('price') is-invalid @enderror"
                                                id="price" 
                                                name="price"
                                                placeholder="Please Enter Price"
                                                value="{{ old('price', $choosePlan->price) }}">
                                            <input type="hidden" name="price" id="hidden_price" value="{{ old('price', $choosePlan->price) }}">
                                            
                                            @error('price')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                        
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="billing_cycle" class="form-label">Billing Cycle:</label>
                                            <select class="form-select @error('billing_cycle') is-invalid @enderror"
                                                name="billing_cycle" id="billing_cycle">
                                                <option value="">-- Select Billing Cycle --</option>
                                                <option value="monthly" {{ old('billing_cycle', $choosePlan->billing_cycle) == 'monthly' ? 'selected' : '' }}>
                                                    Monthly
                                                </option>
                                                <option value="yearly" {{ old('billing_cycle', $choosePlan->billing_cycle) == 'yearly' ? 'selected' : '' }}>
                                                    Yearly
                                                </option>
                                                <option value="lifetime" {{ old('billing_cycle', $choosePlan->billing_cycle) == 'lifetime' ? 'selected' : '' }}>
                                                    Lifetime
                                                </option>
                                            </select>
                                            @error('billing_cycle')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                        
                                        <div class="col-md-6">
                                            <label for="touchpoint_limit" class="form-label">Touchpoint Limit:</label>
                                            <input type="text"
                                                class="form-control @error('touchpoint_limit') is-invalid @enderror"
                                                id="touchpoint_limit" name="touchpoint_limit"
                                                placeholder="Enter Touchpoint Limit"
                                                value="{{ old('touchpoint_limit', $choosePlan->touchpoint_limit) }}">
                                            @error('touchpoint_limit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                        
                                {{-- has ads --}}
                                <div class="col-md-12">
                                    <label for="has_ads" class="form-label">Has Ads:</label>
                                    <select class="form-select @error('has_ads') is-invalid @enderror" name="has_ads" id="has_ads">
                                        <option value="">-- Select Has Ads --</option>
                                        <option value="1" {{ old('has_ads', $choosePlan->has_ads) == '1' ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('has_ads', $choosePlan->has_ads) == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                        
                            <div class="container">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ route('choose.plan.index') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const planSelect = document.getElementById('plan');
        const priceInput = document.getElementById('price');
        const hiddenPriceInput = document.getElementById('hidden_price');
        const touchpointInput = document.getElementById('touchpoint_limit');

        function toggleFields(plan) {
            if (plan === 'free') {
                priceInput.value = '0.00';
                hiddenPriceInput.value = '0.00';
                priceInput.setAttribute('disabled', 'disabled');

                if (!touchpointInput.value || touchpointInput.value === 'unlimited') {
                    touchpointInput.value = '{{ old('touchpoint_limit', $plan->touchpoint_limit ?? '15') }}';
                }
            } else {
                priceInput.removeAttribute('disabled');
                priceInput.value = '{{ old('price', $plan->price ?? '') }}';
                hiddenPriceInput.value = priceInput.value;

                if (!touchpointInput.value || touchpointInput.value === '15') {
                    touchpointInput.value = '{{ old('touchpoint_limit', $plan->touchpoint_limit ?? 'unlimited') }}';
                }
            }
        }

        toggleFields(planSelect.value); // on page load

        planSelect.addEventListener('change', function () {
            toggleFields(this.value);
        });

        // keep hidden price synced
        priceInput.addEventListener('input', function () {
            hiddenPriceInput.value = this.value;
        });
    });
</script>

@endpush
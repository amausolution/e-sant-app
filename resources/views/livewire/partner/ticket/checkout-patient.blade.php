<div>

@section('block_header')
    @include('partner.header')
@show
<!-- start sidebar menu -->
@section('block_sidebar')
    @include('partner.ticket.sidebar')
@show
<!-- end sidebar menu -->
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">{{__('Registered Patient')}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><Link :href="route('partner.home')">{{__('Dashboard')}}</Link></li>
                            <li class="breadcrumb-item active">{{__('Checkout Patient')}}</li>
                        </ul>
                    </div>
                    <div class="col-auto justify-content-evenly float-right ml-auto">
                        <a href="{{ au_route_partner('ticket.index') }}"  class="btn btn-primary m-r-10 " {{ $disabled==='back'?'disabled':'' }}>{{__('Return Patient')}}</a>
                        <a href="{{ au_route_partner('create.new') }}"  class="btn btn-success " {{ $disabled==='new'?'disabled':''}}>{{__('New Patient')}}</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="search-patient">
                <!-- Search Filter -->
                <div  class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus" wire:ignore.self>
                            <input id="identifier" name="patient_id" type="text" class="form-control floating" wire:model="identifier">
                            <label for="identifier" class="focus-label">{{__('Patient ID Or Phone')}} </label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus" wire:ignore.self>
                            <input name="first_name" type="text" class="form-control floating" wire:model="first_name">
                            <label class="focus-label">{{__('Patient First Name')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus" wire:ignore.self>
                            <input name="last_name" type="text" class="form-control floating" wire:model="last_name">
                            <label class="focus-label">{{__('Patient Last Name')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus gender" wire:ignore>
                            <select id="gender" name="gender" class="select floating" >
                                <option value="null" selected disabled>{{__('Select Gender')}}</option>
                                <option value="1">{{__('Male')}}</option>
                                <option value="0">{{__('Female')}}</option>
                            </select>
                            <label for="gender" class="focus-label">{{__('Gender')}}</label>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            @if(strlen($identifier)>3 || strlen($first_name)>1 || strlen($last_name)>1)
                                <div class="card-header">
                                    <h4 class="card-title mb-0 text-md">{{__('Found')}} <span class="font-italic m-l-5">({{ $dataSearch->total() }})</span></h4>
                                </div>

                                <div class="card-body">
                                    @if(count($dataSearch)>0)
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                <tr>
                                                    <th>{{__('Patient ID')}}</th>
                                                    <th>{{__('Full Name')}}</th>
                                                    <th>{{__('Phone')}}</th>
                                                    <th>{{__('Dob')}}</th>
                                                    <th>{{__('Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($dataSearch as $found)
                                                    <tr wire:key="{{$found->id}}">
                                                        <td>{{$found->doc_number}}</td>
                                                        <td>{{$found->name}}</td>
                                                        <td>{{$found->mobil}}</td>
                                                        <td>{{showDate($found->birthday)}}</td>
                                                        <td>
                                        <span class="btn btn-success " style="cursor: pointer" wire:click="addPatient({{$found->id}})">
                                            <i class="fas fa-spinner fa-pulse" wire:loading wire:target="addPatient({{$found->id}})"></i>
                                            <i class="la la-plus" wire:loading.remove wire:target="addPatient({{$found->id}})"></i>
                                        </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <span>{{ __('No Data Found ') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="checkout" wire:ignore>
                @livewire('partner.ticket.partials.checkout' , key(au_uuid()))
            </div>

            @section('block_footer')
                @include('partner.footer')
            @show
        </div>
        <!-- start footer -->
    </div>
    <!-- end page content -->


</div>
@push('scripts')
    <script>
        div = document.getElementById("app")
        $('.checkout').hide()

        $('.gender').on('change', function (e) {
            let data = e.target.value
            @this.set('gender', data);
        });
        window.addEventListener('close-search',evt => {
            $('.search-patient').hide('slow')
            $('.checkout').show('slow')

        })
        window.addEventListener('new-ticket',evt => {
            document.querySelector(".form-focus").classList.remove('focused')
            $('.search-patient').show('slow')
            $('.checkout').hide('slow')
            if($('#department').length > 0) {
                $('#department').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('select Department') }}'
                });
            }
            if($('#gender').length > 0) {
                $('#gender').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('select Gender') }}'
                });
            }
            if($('#payment').length > 0) {
                $('#payment').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('Select Mode Payment') }}'
                });
            }
        })

    </script>

@endpush

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
                            <li class="breadcrumb-item">
                                <Link :href="route('partner.home')">{{__('Dashboard')}}</Link>
                            </li>
                            <li class="breadcrumb-item active">{{__('Checkout Patient')}}</li>
                        </ul>
                    </div>
                    <div class="col-auto justify-content-evenly float-right ml-auto">
                        <a href="{{ au_route_partner('ticket.index') }}" class="btn btn-primary m-r-10 "
                           disabled="">{{__('Return Patient')}}</a>
                        <a href="{{ au_route_partner('create.new') }}" class="btn btn-success "
                           disabled="">{{__('New Patient')}}</a>
                    </div>
                </div>
            </div>

            <div class="row filter-row" >
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 fname" wire:ignore.self>
                    <div class="form-group form-focus  " wire:ignore.self>
                        <input id="firstname" name="first_name" wire:model="first_name"
                               class="form-control floating fname @error('first_name') is-invalid @elseif($first_name === null) @else is-valid @enderror"
                               type="text"/>
                        <label for="firstname" class="focus-label">{{__('First Name')}}</label>
                    </div>
                    @error('first_name') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 lname" wire:ignore.self>
                    <div class="form-group form-focus  " wire:ignore.self>
                        <input id="lastname" name="last_name" wire:model="last_name"
                               class="form-control floating lname @error('last_name') is-invalid @elseif($last_name === null) @else is-valid @enderror"
                               type="text"/>
                        <label for="lastname" class="focus-label">{{__('Last Name')}}</label>
                    </div>
                    @error('last_name') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 mobil" wire:ignore.self>
                    <div class="form-group form-focus  " wire:ignore.self>
                        <input id="mobil" name="mobil" wire:model="mobil"
                               class="form-control floating numeric-validation @error('mobil') is-invalid @elseif($mobil === null) @else is-valid @enderror"
                               type="text"/>
                        <label for="mobil" class="focus-label">{{__('Mobile')}}</label>
                    </div>
                    @error('mobil') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 mail" wire:ignore.self>
                    <div class="form-group form-focus  " wire:ignore.self>
                        <input id="p-email" name="email"
                               class="form-control floating @error('mail') is-invalid @elseif($email === null) @else is-valid @enderror"
                               wire:model="email" type="text"/>
                        <label for="p-email" class="focus-label">{{__('Address Mail')}}</label>
                    </div>
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 col-12 col-lg-6 col-sm-6 address" wire:ignore.self>
                    <div class="form-group form-focus  " wire:ignore.self>
                <textarea name="address" id="address"
                          class="form-control floating @error('address') is-invalid @elseif($address === null) @else is-valid @enderror"
                          wire:model="address" cols="3"></textarea>
                        <label for="address" class="focus-label">{{__('Address')}}</label>
                    </div>
                    @error('address') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 gender" wire:ignore.self="">
                    <div class="btn-group-toggle btn-group-sm " data-toggle="buttons"
                         style="justify-content: space-between !important;" wire:ignore.self>
                        <label class="btn btn-outline-secondary btn-sm {{ $gender == 1 ?'active':'' }} ">
                            <input type="radio" name="gender" wire:model="gender" id="male" autocomplete="off" value="1" > {{__('Male')}}
                        </label>
                        <label class="btn btn-outline-secondary btn-sm pull-right {{ $gender == 2 ?'active':'' }}">
                            <input type="radio" name="gender" wire:model="gender" id="female" value="2"
                                   autocomplete="off"> {{__('Female')}}
                        </label>
                    </div>
                    @error('gender') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 birthday" wire:ignore.self>
                    <div class="form-group form-focus  focused" wire:ignore.self>
                        <input id="birthday" name="birthday" type="date"
                               class="form-control floating @error('birthday') is-invalid @elseif($birthday === null) @else is-valid @enderror"
                               wire:model="birthday">
                        <label for="birthday" class="focus-label">{{__('Insurer Percentage')}}</label>
                        @error('birthday') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 phone2"  wire:ignore.self>
                    <div class="form-group form-focus  " wire:ignore.self>
                        <input id="phone2" name="phone2" wire:model="phone2"
                               class="form-control floating numeric-validation @error('phone2') is-invalid @elseif($phone2 === null) @else is-valid @enderror"
                               type="text"/>
                        <label for="phone2" class="focus-label">{{__('Mobile Emergency')}}</label>
                    </div>
                    @error('phone2') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-3 col-6 col-lg-3 col-sm-3 godfather" wire:ignore.self="">
                    <div class="form-group form-focus godfather " wire:ignore.self>
                        <input id="godfather_matricule" name="godfather_matricule" wire:model="godfather_matricule"
                               class="form-control floating @error('godfather_matricule') is-invalid @elseif($godfather_matricule === null) @else is-valid @enderror"
                               type="text"/>
                        <label for="godfather_matricule" class="focus-label">{{__('Tutor Matricule')}}</label>
                    </div>
                    @error('godfather_matricule') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-3 col-6 col-lg-3 col-sm-3 godfather" wire:ignore.self="" >
                    <div class="form-group form-focus godfather " wire:ignore.self>
                        <input id="godfather" name="godfather" wire:model="godfather"
                               class="form-control floating @error('godfather') is-invalid @elseif($godfather === null) @else is-valid @enderror"
                               type="text"/>
                        <label for="godfather" class="focus-label">{{__('Tutor Full Name')}}</label>
                    </div>
                    @error('godfather') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-3 col-6 col-lg-3 col-sm-3 type_piece"  wire:ignore>
                    <div class="form-group form-focus select-focus " wire:ignore.self>
                        <select name="type_piece" class="select floating @error('type_piece') is-invalid @enderror" id="type_piece">
                            <option value="null" selected disabled>{{__('Select Type Piece')}}</option>
                            @foreach(typePiece() as $p =>$value)
                                <option value="{{ $p }}">{{$value}}</option>
                            @endforeach
                        </select>
                        <label class="focus-label" for="type_piece">{{__('Type Of Piece')}} </label>
                    </div>
                    @error('type_piece') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 piece " wire:ignore.self>
                    <div class="form-group form-focus " wire:ignore.self="">
                        <input id="piece" name="piece" wire:model="piece"
                               class="form-control floating @error('piece') is-invalid @elseif($piece === null) @else is-valid @enderror"
                               type="text"/>
                        <label for="piece" class="focus-label">{{__('Piece Number')}}</label>
                    </div>
                    @error('piece') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 date-exp" wire:ignore.self>
                    <div class="form-group form-focus  focused" >
                        <input name="date_exp" type="date" id="date-exp"
                               class="form-control floating @error('date_exp') is-invalid @elseif($date_exp === null) @else is-valid @enderror"
                               wire:model="date_exp">
                        <label for="date-exp" class="focus-label">{{__('Date Exp. Piéce')}}</label>
                        @error('date_exp') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-12 insured " wire:ignore.self="">
                    <label id="" class="insured-label" wire:ignore="">{{__('Is insured')}}</label>
                    <div class="form-group d-flex">
                        <label id="yes_insured" class="w-auto d-flex mr-4" onclick="isInsured();">
                            <input wire:model="is_insured" class="form-control form-control-sm" type="radio"
                                   name="assurance" id="yes_insured" value="1" style="width: 20px; height: 20px">
                            <span class="ml-2">{{__('Yes')}}</span>
                        </label>
                        <label for="no_insured" class="w-auto d-flex ml-4" onclick="notInsured();">
                            <input wire:model="is_insured" class="form-control form-control-sm" type="radio" name="assurance"
                                   id="no_insured" value="0" style="width: 20px; height: 20px">
                            <span class="ml-2"> {{ __('No') }}</span>
                        </label>
                    </div>


                    {{-- <div class="status-toggle mb-2 " wire:ignore.self>
                         <input type="checkbox" id="insured" class="check" name="is_insured" wire:model="is_insured">
                         <label for="insured" class="checktoggle">{{__('Is insured')}}</label>
                     </div>--}}
                </div>
{{--                insurer data                                        --}}
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 insurer" wire:ignore.self>
                    <div class="form-group form-focus " wire:ignore.self>
                        <input name="insurer" type="text" id="insurer"
                               class="form-control floating @error('insurer') is-invalid @elseif($insurer === null) @else is-valid @enderror "
                               wire:model="insurer">
                        <label for="insurer" class="focus-label">{{__('Insurer Name')}}</label>
                        @if($check_insurer)
                        @else
                            <span class="error">{{ __('Insurance not covered ') }}</span>
                        @endif
                        @error('insurer') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 insurer-number" wire:ignore.self>
                    <div class="form-group form-focus " wire:ignore.self>
                        <input name="insurer_number" type="text" class="form-control floating
                @error('insurer_number') is-invalid @elseif($insurer_number === null) @else is-valid @enderror"
                               wire:model="insurer_number">
                        <label class="focus-label">{{__('Insurer Number')}}</label>
                        @error('insurer_number') <span class="error">{{ $message }}</span> @enderror
                    </div>

                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 percentage " wire:ignore.self>
                    <div class="form-group form-focus " wire:ignore.self>
                        <input name="insurer_percentage" type="text"
                               class="form-control floating numeric-validation @error('insurer_percentage') is-invalid @elseif($insurer_percentage === null) @else is-valid @enderror"
                               wire:model="insurer_percentage">
                        <label class="focus-label">{{__('Insurer Percentage')}}</label>
                        @error('insurer_percentage') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 percentage " wire:ignore.self>
                    <div class="form-group form-focus " wire:ignore.self>
                        <input name="insurer_percentage" type="text"
                               class="form-control floating numeric-validation @error('insurer_percentage') is-invalid @elseif($nbr_person === null) @else is-valid @enderror"
                               wire:model="nbr_person">
                        <label class="focus-label">{{__('Number Of Persons')}}</label>
                        @error('nbr_person') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3 col-6 col-lg-3 col-sm-3 exp-date" wire:ignore.self>
                    <div class="form-group form-focus  focused" wire:ignore.self>
                        <input name="exp_date" type="date"
                               class="form-control floating @error('exp_date') is-invalid @elseif($exp_date === null) @else is-valid @enderror"
                               wire:model="exp_date">
                        <label class="focus-label">{{__('Insurer Percentage')}}</label>
                        @error('exp_date') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div  class="row filter-row">
                <div class="col-md-3 col-6 col-lg-3 col-sm-3">
                    <div class="form-group form-focus select-focus department" wire:ignore>
                        <select name="department" class="select floating department-select @error('department') is-invalid @enderror" id="department" >
                            <option value="null" selected disabled>{{__('Select Department')}}</option>
                            @foreach(getPartner()->departments as $dep)
                                <option value="{{$dep->id}}">{{$dep->department}}</option>
                            @endforeach
                        </select>
                        <label class="focus-label" for="department">{{__('Department')}} </label>
                        @error('department') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="col-md-3 col-6 col-lg-3 col-sm-3">
                    @if($is_insured = 1)
                        <div class="form-group form-focus select-focus payment" wire:ignore>
                            <select name="mode_payment" class="select floating @error('mode_payment') is-invalid @enderror" id="payment">
                                <option value="null" selected disabled>{{__('Select Mode Payment')}}</option>
                                @foreach(typePayment() as $p =>$value)
                                    <option value="{{ $p }}">{{$value}}</option>
                                @endforeach
                            </select>
                            <label class="focus-label" for="payment">{{__('Method Payment')}} </label>
                        </div>
                    @else
                        <div class="form-group form-focus select-focus payment" wire:ignore.self>
                            <span class="form-control floating">{{__('Cash')}}</span>
                            <label class="focus-label" for="payment">{{__('Method Payment')}} </label>
                        </div>
                    @endif
                    @error('mode_payment') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-3 col-6 col-lg-3 col-sm-3">
                    <div class="form-group form-focus amount focused" wire:ignore.self>
                        <span class="form-control floating">{{$amount}}</span>
                        <label class="focus-label">{{__('Amount Ticket')}}</label>
                    </div>
                </div>

                <div class="col-md-3 col-6 col-lg-3 col-sm-3">
                    <div class="form-group form-focus net focused" wire:ignore.self>
                        <span class="form-control floating ">{{$net}}</span>
                        <label class="focus-label">{{__('Net Ticket')}}</label>
                    </div>
                </div>
            </div>
            @if(!empty($first_name)  & !empty( $last_name) & !empty($address)& !empty($department))
                <div class="mt-3" id="btn-submit" wire:ignore>
                    <button type="button" class="btn btn-custom  pull-right"
                            wire:click.prevent="submitForm">{{ __('Submit Patient') }}</button>
                </div>
            @endif
            <div id="app" class="position-absolute " style="z-index: -6; margin-top: -1000px"></div>

            @section('block_footer')
                @include('partner.footer')
            @show
        </div>
        <!-- start footer -->
    </div>
    <!-- end page content -->

</div>
@push('styles')

@endpush
@push('scripts')
    <script>
        function isInsured(){
            $('.insurer').show('fade')
            document.getElementById('insurer').value=''
            $('.department').hide('slow')
            $('.amount').hide('slow')
            $('.net').hide('slow')
            $('.payment').hide('slow')
        }
        function notInsured(){
            $('.insurer').hide('slow')
            document.getElementById('insurer').value=''
            $('.percentage').hide('slow')
            $('.insurer-number').hide('slow')
            $('.exp-date').hide('slow')
        }
        function intiFields(){
            $('.payment').hide();
            $('.insurer').hide()
            $('.piece').hide()
            $('.type_piece').hide()
            $('.amount').hide();
            $('.department').hide();
            $('.lname').hide();
            $('.date-exp').hide();
            $('.mail').hide();
            $('.mobil').hide();
            $('.gender').hide();
            $('.godfather').hide();
            $('.birthday').hide();
            $('.phone2').hide();
            $('.address').hide();
            $('.insured').hide();
            $('.insured-label').hide();
            $('.net').hide();
            //   $('#btn-submit').hide();
            $('.insurer-number').hide();
            $('.percentage').hide();
            $('.exp-date').hide();
        }
        $( document ).ready(function() {
            intiFields()

        });
        $('.fname').on('keyup',function (e) {
            if(this.value.length >0){
                $('.lname').show('slow')
            }else{
                $('.lname').hide('fade')
            }
        });
        $('.lname').on('keyup',function (e) {
           if($(this).val().length >1){
               $('.mobil').show('slow')
           }
        });
        var mob = document.getElementById('mobil')
        $('.mobil').on('keyup',function (e) {
            if(mob.value.length > 8){
                $('.mail').show('slow')
                $('.address').show('slow')
            }
        });
        $('.address').on('keyup',function (e) {
           $('.gender').show('slow')
        });

        $('input[name="gender"]').change(function(e) { // Select the radio input group
            $('.birthday').show('slow')
        });
        $('.birthday').on('change',function (e) {
            var dateSaisie = document.getElementById("birthday").value;
            var dt = new Date(dateSaisie);
            //calculer la différence entre la date actuelle et la date saisie.
            var ma_diff = Date.now() - dt.getTime();
            //calculer la différence entre la date actuelle et la date saisie.
            var age_dt = new Date(ma_diff);
            //extract year from date
            var an = age_dt.getUTCFullYear();
            //calculer maintenant l'âge de l'utilisateur
            var age = Math.abs(an - 1970);
            $('.phone2').show('fade')
            $('.insured').show('fade')
            $('.insured-label').show('fade')

            if(age < 16){
                $('.godfather').show('fade')
                $('.piece').hide('slow')
                $('.type_piece').hide('slow')
                $('.date-exp').hide('slow')
            }else{
                $('.godfather').hide('fade')
                $('.piece').show('fade')
                $('.type_piece').show('fade')
                $('.date-exp').show('fade')
            }
        });
        $("#insured").click(function (e) {
            if ($(this).is(":checked")) {
                $('.insurer').show('fade')
            } else {
                $('.insurer').hide('fade')
                $('.department').show('slow')
            }
        })


        $("#chkPassport").click(function () {
            if ($(this).is(":checked")) {
                $("#dvPassport").show();
            } else {
                $("#dvPassport").hide();
            }
        });

        $(".insurer").on('keyup',function (e) {
            $('.insurer-number').show('slow')
        });
        $('.insurer-number').on('keyup',function (e) {
            $('.percentage').show('slow')
        });
        $('.percentage').on('keyup',function (e) {
            $('.exp-date').show('slow')
        });
        $('.exp-date').on('change', function () {
           $('.department').show('slow')
        })

        $(".type_piece").on('change', function (e) {
            let data = e.target.value

            @this.set('type_piece', data);

        });

        $(".department").on('change', function (e) {
            let data = e.target.value
            if (data !== null){
                $('.payment').show('slow')
                @this.set('department', data);
            }
        });

        $('.payment').on('change', function (e) {
            let data = e.target.value
            $('#submit-button').show('slow')
            $('.amount').show('slow')
            $('.net').show('slow')
            //  alert(data)
        @this.set('mode_payment', data);
        });

        div = document.getElementById("app")
        $('.checkout').hide()

        $('.gender').on('change', function (e) {
            let data = e.target.value
        @this.set('gender', data);
        });
        window.addEventListener('close-search', evt => {
            $('.search-patient').hide('slow')
            $('.checkout').show('slow')

        })
        window.addEventListener('new-ticket', evt => {
            $('.search-patient').show('slow')
            $('.checkout').hide('slow')
            if ($('#department').length > 0) {
                $('#department').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('select Department') }}'
                });
            }
            if ($('#payment').length > 0) {
                $('#payment').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('Select Mode Payment') }}'
                });
            }
        })
        window.addEventListener('department-updated', evt => {
             $('.amount').show('slow')
             $('.net').show('slow')
             $('.payment').show('slow')
            if ($('#department').length > 0) {
                $('#department').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('select Department') }}'
                });
            }
            if ($('#payment').length > 0) {
                $('#payment').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('Select Mode Payment') }}'
                });
            }
        })
        window.addEventListener('show-department', evt => {
            $('.department').show('slow')
            if ($('#department').length > 0) {
                $('#department').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('select Department') }}'
                });
            }
            if ($('#payment').length > 0) {
                $('#payment').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('Select Mode Payment') }}'
                });
            }
        })
        window.addEventListener('submit-form-new',evt => {
           // alert('submited')
            document.getElementById('form-new').submit()
        });

        $('#form-new').on('submit', function(e){
            e.preventDefault();
            let url = this.action;
            let formData = new FormData(this);
            let btn     = $(this).find('button[type=submit]')
            btn.attr('disabled', 'disabled');
            $.ajax({
                url: url,
                type : "POST",
                cache: false,
                contentType : false,
                processData: false,
                data: formData,
                success: function (response) {
                    if(response.status === 'error'){
                        notify('error', response.message);
                    }else{
                        notify('success', response.message);
                        // window.Livewire.emitTo('partner.doctor.prescription','refresh')
                        $('#add_prescription').modal('hide')
                        resetFormT()
                        $('.specifications').remove()
                    }
                    if(response.reload === true){
                        location.reload();
                    }
                    btn.removeAttr('disabled');
                    loading.fadeOut();
                }
            });

        });
        function resetFormT() {
            document.getElementById("form-ticket").reset();
        }

        window.addEventListener('print-ticket',ev => {
            console.log(ev.detail)
            $('#payment').val(null).trigger('change');
            $('#department').val(null).trigger('change');
            if ($('#department').length > 0) {
                $('#department').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('Select Department') }}'
                });
            }
            if ($('#payment').length > 0) {
                $('#payment').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    placeholder: '{{ __('Select Mode Payment') }}'
                });
            }
            $('.payment').hide();
            $('.insurer').hide();
            $('.amount').hide();
            $('.net').hide();
            $('#submit-button').hide();
            $('.insurer-number').hide();
            $('.percentage').hide();
            $('.exp-date').hide();
            div.innerHTML = `  <div id="invoice-POS"> <center id="top">
                <div class="logo">

                </div>
                <div class="info">
                <h2>${ev.detail.partner}</h2>
            </div>
            </center>

            <div id="mid">
                <div class="info">
                <h5>@lang('Contact Info')</h5>
            <p>
            @lang('Address') : ${ev.detail.address}</br>
            @lang('Email')   : ${ev.detail.e_mail}</br>
            @lang('Phone')   : ${ev.detail.partner_phone}</br>
            </p>
            </div>
            </div>
            <div id="bot">
                <div id="table">
                <table>
                <tr class="tabletitle">
                <td class="item"><h2>{{__('Ticket n°')}}</h2></td>
                <td class="Hours"><h2>{{__('Department')}}</h2></td>
                </tr>

            <tr class="service">
            <td class="tableitem"><p class="itemtext">${ev.detail.ticket}</p></td>
            <td class="tableitem"><p class="itemtext">${ev.detail.depart}</p></td>
            </tr>
            <tr class="tabletitle">

                <td class="Rate"><h2>{{__('Total')}}</h2></td>
                <td class="payment"><h2>${ev.detail.amount_ticket}</h2></td>
            </tr>
            <tr class="tabletitle">

                <td class="Rate"><h2>{{__('tax')}}</h2></td>
            <td class="payment"><h2>0</h2></td>
            </tr>
           <tr class="tabletitle">

                <td class="Rate"><h2>{{__('Net TTC')}}</h2></td>
                <td class="payment"><h2>${ev.detail.net_ticket} Fcfa</h2></td>
            </tr>
            </table>
            </div>
            <div id="legalcopy">
                <p class="legal">
                    <strong>@lang('Thank you for your business!')</strong> 
                   <span> @lang('Ticket is expected within 2 days; please conserve this ticket within that time.')</span>
                </p>
                <p>${ev.detail.date}</p>
            </div>

            </div>
            </div>`
            printJS({
                printable: 'app',
                type: 'html',
                style: '',
            });
            intiFields()
        });
    </script>

@endpush

<div class="position-relative">
  <div>

      @if(!empty($patient))

          <div class="card mb-5">
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="profile-view">
                              <div class="profile-img-wrap">
                                  <div class="profile-img">
                                      <a href="">
                                          <img src="{{asset($patient->getAvatar())}}" alt="">
                                      </a>
                                  </div>

                              </div>
                              <div class="profile-basic">
                                  <div class="row">
                                      <div class="col-md-5">
                                          <div class="profile-info-left">
                                              <h3 class="user-name m-t-0">{{$patient->name}}</h3>
                                              <h5 class="company-role text-muted m-t-0 mb-0">{{__('GroupBlood')}}</h5>
                                              <small class=" text-danger">{{$patient->blood_group}}</small>
                                              <div class="staff-id">{{__('Patient ID ')}}: {{$patient->doc_number}}</div>
                                              @if($patient->assurance)
                                              <div class="staff-msg">
                                                  <a href="" class="btn btn-custom" data-toggle="modal" data-target="#insurer-modal">{{__('See Info Insurer')}}</a>
                                                  <button wire:click.prevent="apply" class="btn btn-warning" {{ !checkIsExp($patient->date_expiration)?'disabled':'' }}>{{__('Apply Insurance')}}</button>
                                              </div>

                                                @if(!checkIsExp($patient->date_expiration))
                                                       <span class="error">{{__('Insurance expired')}}</span>
                                                  @endif
                                              @endif
                                          </div>
                                      </div>
                                      <div class="col-md-7">
                                          <ul class="personal-info">
                                              <li>
                                                  <span class="title">{{__('Phone')}}:</span>
                                                  <span class="text"><a href="">{{$patient->mobil}}</a></span>
                                              </li>
                                              <li>
                                                  <span class="title">{{__('Email')}}:</span>
                                                  <span class="text"><a href="">{{$patient->email??'NEAN'}}</a></span>
                                              </li>
                                              <li>
                                                  <span class="title">{{__('Birthday')}}:</span>
                                                  <span class="text">{{showDate($patient->birthday)}}</span>
                                              </li>
                                              <li>
                                                  <span class="title">{{__('Address')}}:</span>
                                                  <input type="text" wire:model="address" class="form-control">

                                              </li>
                                              <li>
                                                  <span class="title">Gender:</span>
                                                  <span class="text">{{gender()[$patient->gender]}}</span>
                                              </li>
                                          </ul>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <!-- Edit Modal -->
          <div class="modal custom-modal fade" id="insurer-modal" role="dialog">
              <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title">{{ __('Info Insurer') }}</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <ul class="personal-info">
                              <li>
                                  <span class="title">{{__('Insurer Name')}}:</span>
                                  <span class="text"><a href="">{{$patient->assurance_service}}</a></span>
                              </li>
                              <li>
                                  <span class="title">{{__('Insurer Number')}}:</span>
                                  <span class="text"><a href="">{{$patient->assurance_number}}</a></span>
                              </li>
                              <li>
                                  <span class="title">{{__('Insurer Percentage')}}:</span>
                                  <span class="text"><a href="">{{$patient->assurance_percentage}}</a></span>
                              </li>
                          </ul>
                      </div>
                  </div>
              </div>
          </div>
          <!-- /Edit Modal -->
      @endif
  </div>

    <div  class="row filter-row">
        <div class="col-md-3 col-6 col-lg-3 col-sm-3">
            <div class="form-group form-focus select-focus department" wire:ignore>
                <select name="department" class="select floating department-select @error('department') is-invalid @enderror" id="department" >
                    <option value="null" selected disabled>{{__('Select Department')}}</option>
                    @foreach($departments as $dep)
                    <option value="{{$dep->id}}">{{$dep->department}}</option>
                    @endforeach
                </select>
                <label class="focus-label" for="department">{{__('Department')}} </label>
                @error('department') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>



            <div class="col-md-3 col-6 col-lg-3 col-sm-3 payment" wire:ignore>
                <div class="form-group form-focus select-focus " >
                    <select name="mode_payment" class="select floating @error('mode_payment') is-invalid @enderror" id="payment">
                        <option value="null" selected disabled>{{__('Select Mode Payment')}}</option>
                        @foreach(typePayment() as $p =>$value)
                            <option value="{{ $p }}">{{$value}}</option>
                        @endforeach
                    </select>
                    <label class="focus-label" for="payment">{{__('Method Payment')}} </label>
                </div>
                @error('mode_payment') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-3 col-6 col-lg-3 col-sm-3 apply" wire:ignore.self="">
                <div class="form-group form-focus select-focus " wire:ignore.self>
                    <span class="form-control floating">{{__('Insurance')}}</span>
                    <label class="focus-label" for="payment">{{__('Method Payment')}} </label>
                </div>
            </div>

        <div class="col-md-3 col-6 col-lg-3 col-sm-3">
            <div class="form-group form-focus amount focused" wire:ignore.self>
                <span class="form-control floating">{{$amount}}</span>
                <label class="focus-label">{{__('Amount Ticket')}}</label>
            </div>
        </div>

        <div class="col-md-3 col-6 col-lg-3 col-sm-3 net position-relative" wire:ignore.self="">
            <div class="form-group form-focus  focused" wire:ignore.self>
                <span class="form-control floating ">{{$net}}</span>
                <label class="focus-label">{{__('Net Ticket')}}</label>
            </div>
            <span class="text-danger font-18 position-absolute remove-apply" style="top: 0; right: 0" wire:click.prevent="removeApply" wire:ignore.self>
                <i class="fa fa-trash-o"></i>
            </span>
        </div>

        <div class="col-md-3 col-6 col-lg-3 col-sm-3">
            <div class="form-group form-focus insurer" wire:ignore.self>
                <input name="insurer" type="text" class="form-control floating @error('insurer') is-invalid @elseif($insurer === null) @else is-valid @enderror"  wire:model="insurer">
                <label class="focus-label">{{__('Insurer Name')}}</label>
                @error('insurer') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-3 col-6 col-lg-3 col-sm-3">
            <div class="form-group form-focus insurer-number" wire:ignore.self>
                <input name="insurer_number" type="text" class="form-control floating
                    @error('insurer_number') is-invalid @elseif($insurer_number === null) @else is-valid @enderror" wire:model="insurer_number">
                <label class="focus-label">{{__('Insurer Number')}}</label>
                @error('insurer_number') <span class="error">{{ $message }}</span> @enderror
            </div>

        </div>
        <div class="col-md-3 col-6 col-lg-3 col-sm-3 percentage " wire:ignore.self>
            <div class="form-group form-focus " wire:ignore.self>
                <input name="insurer_percentage" type="text" class="form-control floating numeric-validation @error('insurer_percentage') is-invalid @elseif($insurer_percentage === null) @else is-valid @enderror" wire:model="insurer_percentage">
                <label class="focus-label">{{__('Insurer Percentage')}}</label>
                @error('insurer_percentage') <span class="error">{{ $message }}</span> @enderror
            </div>

        </div>
        <div class="col-md-3 col-6 col-lg-3 col-sm-3">
            <div class="form-group form-focus exp-date focused" wire:ignore.self>
                <input name="exp_date" type="date" class="form-control floating @error('exp_date') is-invalid @elseif($exp_date === null) @else is-valid @enderror" wire:model="exp_date">
                <label class="focus-label">{{__('Insurer Date Expiration')}}</label>
                @error('exp_date') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    <div class="mt-3" id="submit-button" wire:ignore>
        <button type="button" class="btn btn-custom pull-right" wire:click.prevent="submitForm">{{ __('Submit Ticket') }}</button>
    </div>

    <div id="app" class="position-absolute " style="z-index: -6; margin-top: -1000px">

    </div>
</div>
@push('styles')
@endpush
@push('scripts')
    <script>
        function initField(){
            $('.payment').hide();
            $('.insurer').hide();
            $('.amount').hide();
            $('.net').hide();
            $('#submit-button').hide();
            $('.insurer-number').hide();
            $('.percentage').hide();
            $('.exp-date').hide();
            $('.apply').hide();
        }
        $( document ).ready(function() {
           initField()
        });
        window.addEventListener('apply',ev =>{
            $('.amount').show('slow')
            $('.net').show('slow')
            $('.apply').show('slow')
            $('.payment').hide()
            $('.remove-apply').show()
            $('#submit-button').show('slow');
        })
        window.addEventListener('remove-apply',e =>{
            $('.apply').hide('slow')
            $('.net').hide('fade')
            $('.amount').hide('fade')
            $('.remove-apply').hide()
            $('#payment option').prop('selected', function() {
                return this.defaultSelected;
            });
        })

        $('.department').on('change', function (e) {
            let data = e.target.value
            if (data !== null){
               $('.payment').show('slow')
                //  alert(data)
            @this.set('department', data);
            }

        });
        $('.payment').on('change', function (e) {
            let data = e.target.value
            $('#submit-button').show('slow')
            $('.amount').show('slow')
            $('.net').show('slow')
            if (data !== 'cash'){
                $('.insurer').show('slow')
            }else{
                $('.insurer').hide('slow')
                $('.percentage').hide('slow')
                $('.exp-date').hide('slow')
            }
            //  alert(data)
        @this.set('mode_payment', data);
        });
        $('.insurer').on('keypress', function (e) {
            if (e.target.value.length > 0){
                $('.insurer-number').show('slow')
            }
        })
        $('.insurer-number').on('keypress', function (e) {
            if (e.target.value.length > 0){
                $('.percentage').show('slow')
            }
        })
        $('.percentage').on('keypress', function (e) {
            if (e.target.value.length > 0){
                $('.exp-date').show('slow')
            }
        })
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
                   initField()
        });
    </script>

@endpush

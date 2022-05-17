<!-- start sidebar menu -->
@php
    $menus = Partner::getMenuVisible();
@endphp

<!-- end sidebar menu -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>{{__('Home')}}</span>
                </li>
                <li class="py-2 {{(\Request::route()->getName() === 'ticket.index')  ?'active':''}}">
                    <a href="{{au_route_partner('ticket.index')}}"><i class="la la-ticket-alt"></i> <span>{{__('Ticket')}}</span></a>
                </li>
                <li class="py-2 {{(\Request::route()->getName() === 'create.new')  ?'active':''}}">
                    <a href="{{au_route_partner('create.new')}}"><i class="la la-users"></i> <span>{{__('New Patient')}}</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>

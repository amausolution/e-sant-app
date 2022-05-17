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
                    <span>Main</span>
                </li>
                @if (count($menus))
                    @foreach ($menus[0] as $level0)
                        @if (!empty($menus[$level0->id]))
                <li class="submenu">
                    <a href="#" class="">
                        <i class="{{ $level0->icon }}"></i>
                        <span> {!! __($level0->title) !!}</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="" style="display: none;">
                        @foreach ($menus[$level0->id] as $level1)
                            @if($level1->uri)
                                 <li class="nav-item" style="padding: 4px">
                                     <a href="{{ $level1->uri?au_url_render($level1->uri):'#' }}"
                                        class="{{ \Partner::checkUrlIsChild(url()->current(), au_url_render($level1->uri)) ? 'active' : '' }}">
                                         <i class="{{ $level1->icon }}"></i>
                                       <span style="margin-left: 8px">{!! __($level1->title) !!}</span>
                                     </a>
                                 </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
                @else
                    @if($level0->uri)
                        <li  class="{{ url()->current()=== au_url_render($level0->uri) ? 'active' : '' }}">
                            <a href="{{ $level0->uri?au_url_render($level0->uri):'#' }}">
                                <i class="{{ $level0->icon }}"></i>
                                <span>{!! __($level0->title) !!}</span>
                            </a>
                        </li>
                    @endif
                @endif
             @endforeach
                    {{-- end level 0 --}}
           @endif
            </ul>
        </div>
    </div>
</div>

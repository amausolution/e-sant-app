@php
    $languages     = au_language_all();
@endphp

<!-- start language menu -->
<li class="dropdown language-switch ">
    <a class="dropdown-toggle flex items-center" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ au_file($languages[session('locale')??app()->getLocale()]['icon']) }}" class="position-left" alt="" style="height: 25px;">
        {{ $languages[session('locale')??app()->getLocale()]['name'] }}
        <span class="fa fa-angle-down"></span>
    </a>
    <ul class="dropdown-menu">
        @foreach ($languages as $key=> $language)
        <li class="">
            <a href="{{ au_route_admin('admin.locale', ['code' => $key]) }}" class="items-center {{ $language['name'] }}">
             <img src="{{ au_file($language['icon']) }}" alt="{{ $language['name'] }}"  style="height: 25px;">
                {{ $language['name'] }}
            </a>
        </li>
        @endforeach
    </ul>
</li>
<!-- end language menu -->

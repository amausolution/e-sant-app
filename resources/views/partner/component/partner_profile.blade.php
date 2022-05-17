      <!-- User Account: style can be found in dropdown.less -->

      <li class="dropdown dropdown-user">
          <a class="dropdown-toggle" data-bs-toggle="dropdown" data-hover="dropdown"
             data-close-others="true">
              <img alt="" class="img-circle " src="{{ Partner::user()->avatar?au_file(Partner::user()->avatar):au_file('partner/avatar/partner.png') }}" />
              <span class="username username-hide-on-mobile"> {{ Partner::user()->username }} </span>
              <i class="fa fa-angle-down"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-default">
              <li>
                  <a href="user_profile.html">
                      <i class="icon-user"></i> Profile </a>
              </li>
              <li>
                  <a href="{{ au_route_admin('partner.setting') }}">
                      <i class="icon-settings"></i> {{ au_language_render('partner.user.setting') }}
                  </a>
              </li>

              <li class="divider"> </li>
              <li>
                  <a href="">
                      <i class="icon-lock"></i> Lock
                  </a>
              </li>
              <li>
                  <a href="{{ au_route_admin('partner.logout') }}">
                      <i class="icon-logout"></i> {{ au_language_render('partner.user.logout') }} </a>
              </li>
          </ul>
      </li>

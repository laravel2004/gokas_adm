<!-- ---------------------------------- -->
<!-- Start Vertical Layout Header -->
<!-- ---------------------------------- -->
<nav class="navbar navbar-expand-lg p-0">
  <ul class="navbar-nav">
    <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
      <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
        <i class="ti ti-menu-2"></i>
      </a>
    </li>
  </ul>

  <div class="d-block d-lg-none py-4">
    <a href="main/index" class="text-nowrap logo-img">
{{--      <img src="{{ URL::asset('build/images/logos/dark-logo.svg') }}" class="dark-logo" alt="Logo-Dark" />--}}
{{--      <img src="{{ URL::asset('build/images/logos/light-logo.svg') }}" class="light-logo" alt="Logo-light" />--}}
        <h4 class="text-body">GPS Tracking</h4>
    </a>
  </div>
  <a class="navbar-toggler nav-icon-hover-bg rounded-circle p-0 mx-0 border-0" href="javascript:void(0)" data-bs-toggle="collapse"
    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <i class="ti ti-dots fs-7"></i>
  </a>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <div class="d-flex align-items-center justify-content-between">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
        <!-- ------------------------------- -->
        <!-- start language Dropdown -->
        <!-- ------------------------------- -->
        <li class="nav-item nav-icon-hover-bg rounded-circle">
          <a class="nav-link moon dark-layout" href="javascript:void(0)">
            <i class="ti ti-moon moon"></i>
          </a>
          <a class="nav-link sun light-layout" href="javascript:void(0)">
            <i class="ti ti-sun sun"></i>
          </a>
        </li>
        <!-- ------------------------------- -->
        <!-- start profile Dropdown -->
        <!-- ------------------------------- -->
        <li class="nav-item dropdown">
          <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
            <div class="d-flex align-items-center">
              <div class="user-profile-img">
                <img src="{{ URL::asset('build/images/profile/user-1.jpg') }}" class="rounded-circle" width="35" height="35"
                  alt="modernize-img" />
              </div>
            </div>
          </a>
          <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
            <x-headers.dd-profile/>
          </div>
        </li>
        <!-- ------------------------------- -->
        <!-- end profile Dropdown -->
        <!-- ------------------------------- -->
      </ul>
    </div>
  </div>
</nav>
<!-- ---------------------------------- -->
<!-- End Vertical Layout Header -->
<!-- ---------------------------------- -->

<!-- ------------------------------- -->
<!-- apps Dropdown in Small screen -->
<!-- ------------------------------- -->
{{--<x-headers.dd-apps-mobile/>--}}

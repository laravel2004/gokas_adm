
    <!-- ---------------------------------- -->
    <!-- Start Vertical Layout Sidebar -->
    <!-- ---------------------------------- -->
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="/main/index" class="text-nowrap logo-img">
        <img src="{{ URL::asset('build/images/logos/logo.png') }}" width=180  height="70" class="dark-logo" alt="Logo-Dark" />
        <img src="{{ URL::asset('build/images/logos/logo.png') }}" width=180  height="70" class="light-logo" alt="Logo-light" />
      </a>
      <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
        <i class="ti ti-x"></i>
      </a>
    </div>

    <nav class="sidebar-nav scroll-sidebar" data-simplebar>
      <ul id="sidebarnav">
        <!-- ---------------------------------- -->
        <!-- Home -->
        <!-- ---------------------------------- -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Home</span>
        </li>
        <!-- ---------------------------------- -->
        <!-- Dashboard -->
        <!-- ---------------------------------- -->
          <li class="sidebar-item">
              <a class="sidebar-link {{ request()->is('sudut-panel/admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" aria-expanded="false">
            <span>
              <i class="ti ti-home"></i>
            </span>
                  <span class="hide-menu">Dashboard</span>
              </a>
          </li>
          <li class="sidebar-item">
              <a class="sidebar-link has-arrow {{ request()->is('sudut-panel/admin/paylater', 'sudut-panel/admin/paylater/need-paid') ? 'active' : '' }}" href="{{ route('admin.paylater.index') }}" aria-expanded="false">
            <span class="d-flex">
                <i class="ti ti-currency-euro"></i>
            </span>
                  <span class="hide-menu">Paylater</span>
              </a>
              <ul aria-expanded="false" class="collapse first-level {{ request()->is('sudut-panel/admin/paylater', 'sudut-panel/admin/paylater/need-paid') ? 'in' : '' }}">
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ request()->is('sudut-panel/admin/paylater') ? 'active' : '' }}" href="{{ route('admin.paylater.index') }}" aria-expanded="false">
                          <div class="round-16 d-flex align-items-center justify-content-center">
                              <i class="ti ti-circle"></i>
                          </div>
                          <span class="hide-menu">All List</span>
                      </a>
                  </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ request()->is('sudut-panel/admin/paylater/need-paid') ? 'active' : '' }}" href="{{ route('admin.paylater.need-paid') }}" aria-expanded="false">
                          <div class="round-16 d-flex align-items-center justify-content-center">
                              <i class="ti ti-circle"></i>
                          </div>
                          <span class="hide-menu">Need Paid</span>
                      </a>
                  </li>
              </ul>
          </li>
          <li class="sidebar-item">
              <a class="sidebar-link has-arrow {{ request()->is('sudut-panel/admin/loan', 'sudut-panel/admin/loan') ? 'active' : '' }}" href="{{ route('admin.loan.index') }}" aria-expanded="false">
            <span class="d-flex">
                <i class="ti ti-building-bank"></i>
            </span>
                  <span class="hide-menu">Loan</span>
              </a>
              <ul aria-expanded="false" class="collapse first-level {{ request()->is('sudut-panel/admin/loan', 'sudut-panel/admin/loan') ? 'in' : '' }}">
                  <li class="sidebar-item">
                      <a class="sidebar-link {{ request()->is('sudut-panel/admin/loan') ? 'active' : '' }}" href="{{ route('admin.loan.index') }}" aria-expanded="false">
                          <div class="round-16 d-flex align-items-center justify-content-center">
                              <i class="ti ti-circle"></i>
                          </div>
                          <span class="hide-menu">All List</span>
                      </a>
                  </li>
              </ul>
          </li>
          <li class="sidebar-item">
              <a class="sidebar-link {{ request()->is('sudut-panel/admin/transaction') ? 'active' : '' }}" href="{{ route('admin.transaction.index') }}" aria-expanded="false">
            <span>
              <i class="ti ti-cash-off"></i>
            </span>
                  <span class="hide-menu">Transaction</span>
              </a>
          </li>
          <li class="sidebar-item">
              <a class="sidebar-link {{ request()->is('sudut-panel/admin/invoice') ? 'active' : '' }}" href="{{ route('admin.invoice.index') }}" aria-expanded="false">
            <span>
              <i class="ti ti-file-invoice"></i>
            </span>
                  <span class="hide-menu">Invoice</span>
              </a>
          </li>
          <li class="sidebar-item">
              <a class="sidebar-link {{ request()->is('sudut-panel/admin/employee') ? 'active' : '' }}" href="{{ route('admin.employee.index') }}" aria-expanded="false">
            <span>
              <i class="ti ti-users"></i>
            </span>
                  <span class="hide-menu">Employee</span>
              </a>
          </li>
          <li class="sidebar-item">
              <a class="sidebar-link {{ request()->is('sudut-panel/admin/account') ? 'active' : '' }}" href="{{ route('admin.account.index') }}" aria-expanded="false">
            <span>
              <i class="ti ti-address-book"></i>
            </span>
                  <span class="hide-menu">Account</span>
              </a>
          </li>
          <li class="sidebar-item">
              <a class="sidebar-link {{ request()->is('sudut-panel/admin/setting-approval') ? 'active' : '' }}" href="{{ route('admin.setting-approval.index') }}" aria-expanded="false">
            <span>
              <i class="ti ti-device-mobile"></i>
            </span>
                  <span class="hide-menu">Setting Approval</span>
              </a>
          </li>
          <li class="sidebar-item">
              <a class="sidebar-link {{ request()->is('sudut-panel/admin/setting-limit') ? 'active' : '' }}" href="{{ route('admin.setting-limit.index') }}" aria-expanded="false">
            <span>
              <i class="ti ti-adjustments-alt"></i>
            </span>
                  <span class="hide-menu">Setting Limit</span>
              </a>
          </li>
      </ul>
    </nav>

    <div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
      <div class="hstack gap-3">
        <div class="john-img">
          <img src="{{ URL::asset('build/images/profile/user-1.jpg') }}" class="rounded-circle" width="40" height="40" alt="modernize-img" />
        </div>
        <div class="john-title">
          <h6 class="mb-0 fs-4 fw-semibold">{{ auth('web')->user()->name }}</h6>
          <span class="fs-2">Admin</span>
        </div>
        <a href="{{ route('admin.logout') }}" class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="button" aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
          <i class="ti ti-power fs-6"></i>
        </a>
      </div>
    </div>

    <!-- ---------------------------------- -->
    <!-- Start Vertical Layout Sidebar -->
    <!-- ---------------------------------- -->

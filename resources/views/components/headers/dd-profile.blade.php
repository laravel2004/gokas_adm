<div class="profile-dropdown position-relative" data-simplebar>
  <div class="py-3 px-7 pb-0">
    <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
  </div>
  <div class="d-flex align-items-center py-9 mx-7 border-bottom">
    <img src="{{ URL::asset('build/images/profile/user-1.jpg')}}" class="rounded-circle" width="80" height="80"
      alt="modernize-img" />
    <div class="ms-3">
      <h5 class="mb-1 fs-3">{{ auth('web')->user()->name }}</h5>
      <span class="mb-1 d-block">Admin</span>
      <p class="mb-0 d-flex align-items-center gap-2">
        <i class="ti ti-mail fs-4"></i> <span>{{ auth('web')->user()->email }}</span>
      </p>
    </div>
  </div>
  <div class="d-grid py-4 px-7 pt-8">
    <div class="upgrade-plan bg-primary-subtle position-relative overflow-hidden rounded-4 p-4 mb-9">
      <div class="row">
        <div class="col-6">
          <h5 class="fs-4 mb-3 fw-semibold">Unlimited Access</h5>
        </div>
        <div class="col-6">
          <div class="m-n4 unlimited-img">
            <img src="{{ URL::asset('build/images/backgrounds/unlimited-bg.png') }}" alt="modernize-img" class="w-100" />
          </div>
        </div>
      </div>
    </div>
    <a href="{{ route('admin.logout') }}" class="btn btn-outline-danger">Log Out</a>
  </div>
</div>

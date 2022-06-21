<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-dark " id="sidenav-main">
	<div class="container-fluid">
		<!-- Toggler -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<!-- Brand -->
		<a class="navbar-brand pt-0" href="{{ route('home') }}">
			<h1>Priority</h1>
		</a>
		<!-- User -->
		<ul class="nav align-items-center d-md-none">
			<li class="nav-item dropdown">
				<a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<div class="media align-items-center">
						<span class="avatar avatar-sm rounded-circle">
						<img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg">
						</span>
					</div>
				</a>
				<div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
					<a href="{{ route('profile.edit') }}" class="dropdown-item">
						<i class="ni ni-single-02"></i>
						<span>{{ __('My profile') }}</span>
					</a>
					<div class="dropdown-divider"></div>
					<a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
					document.getElementById('logout-form').submit();">
						<i class="ni ni-user-run"></i>
						<span>{{ __('Logout') }}</span>
					</a>
				</div>
			</li>
		</ul>
		<!-- Collapse -->
		<div class="collapse navbar-collapse" id="sidenav-collapse-main">
			<!-- Collapse header -->
			<div class="navbar-collapse-header d-md-none">
				<div class="row">
					<div class="col-6 collapse-brand">
						<a href="{{ route('home') }}">
							<img src="{{ asset('argon') }}/img/brand/blue.png">
						</a>
					</div>
					<div class="col-6 collapse-close">
						<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
							<span></span>
							<span></span>
						</button>
					</div>
				</div>
			</div>

			<!-- Navigation -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="{{ route('home') }}">
						<i class="fal fa-gift text-white"></i> {{ __('Total') }}
					</a>
				</li>
        @if(auth()->user()->level == 1)
				<li class="nav-item">
					<a class="nav-link" href="{{ route('products') }}">
						<i class="fal fa-gift text-white"></i> {{ __('Products') }}
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('categories') }}">
            <i class="fal fa-list text-white"></i>
					  <span class="nav-link-text">{{ __('Categories') }}</span>
					</a>
				</li>
				
				<li class="nav-item">
					<a class="nav-link" href="{{ route('team.index') }}">
            <i class="fal fa-user-friends text-white"></i>
            {{ __('User Management') }}
					</a>
				</li>
        @endif
			</ul>

		</div>
	</div>
</nav>

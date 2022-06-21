@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
	<div class="header bg-gradient-primary py-7 py-lg-8">
		<div class="container">
			<div class="header-body mt-7 mb-7">
				<div class="row">
					<div class="col-lg-8 mx-auto text-center">
						<h1 class="mb-5">{{ __('Welcome to Priority!') }}</h1>
						
						<div class="book-demo">
							<a class="btn btn-primary" href="/home">Browser Data</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container mt--10 pb-5"></div>
@endsection

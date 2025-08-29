@extends('front.layout.layout')

@section('content')
	<div class="container-fluid bg-secondary mb-5">
		<div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 150px">
			<h1 class="font-weight-semi-bold text-uppercase mb-3">Login</h1>
			<div class="d-inline-flex">
				<p class="m-0"><a href="{{ url('/') }}">Accueil</a></p>
				<p class="m-0 px-2">-</p>
				<p class="m-0">Login</p>
			</div>
		</div>
	</div>

	<div class="container py-4">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card shadow-sm">
					<div class="card-body p-4">
						<h4 class="font-weight-semi-bold mb-4">Se connecter à votre compte</h4>

						@if (session('status'))
							<div class="alert alert-success" role="alert">
								{{ session('status') }}
							</div>
						@endif

						@if ($errors->any())
							<div class="alert alert-danger">
								<ul class="mb-0">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<form method="POST" action="{{ route('login') }}">
							@csrf

							<div class="form-group">
								<label for="email">Email</label>
								<input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
							</div>

							<div class="form-group">
								<label for="password">Mot de passe</label>
								<input id="password" class="form-control" type="password" name="password" required autocomplete="current-password">
							</div>

							<div class="form-group form-check d-flex align-items-center justify-content-between">
								<div>
									<input id="remember_me" type="checkbox" class="form-check-input" name="remember">
									<label class="form-check-label" for="remember_me">Se souvenir de moi</label>
								</div>
								@if (Route::has('password.request'))
									<a class="small" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
								@endif
							</div>

							<button type="submit" class="btn btn-primary">Se connecter</button>
						</form>

						<hr>
						<p class="mb-0">Pas de compte ? <a href="{{ route('register') }}">S'inscrire</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

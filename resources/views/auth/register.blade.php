@extends('front.layout.layout')

@section('content')
	<div class="container-fluid bg-secondary mb-5">
		<div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 150px">
			<h1 class="font-weight-semi-bold text-uppercase mb-3">Register</h1>
			<div class="d-inline-flex">
				<p class="m-0"><a href="{{ url('/') }}">Accueil</a></p>
				<p class="m-0 px-2">-</p>
				<p class="m-0">Register</p>
			</div>
		</div>
	</div>

	<div class="container py-4">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card shadow-sm">
					<div class="card-body p-4">
						<h4 class="font-weight-semi-bold mb-4">Créer un compte</h4>

						@if ($errors->any())
							<div class="alert alert-danger">
								<ul class="mb-0">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<form method="POST" action="{{ route('register') }}">
							@csrf

							<div class="form-group">
								<label for="name">Nom</label>
								<input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
							</div>

							<div class="form-group">
								<label for="email">Email</label>
								<input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
							</div>

							<div class="form-group">
								<label for="password">Mot de passe</label>
								<input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
							</div>

							<div class="form-group">
								<label for="password_confirmation">Confirmer le mot de passe</label>
								<input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
							</div>

							<div class="form-group">
								<label class="d-block mb-2">S'inscrire en tant que</label>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="type_customer" name="userType" value="Customer" class="custom-control-input" {{ old('userType','Customer')=='Customer' ? 'checked' : '' }} required>
									<label class="custom-control-label" for="type_customer">Client</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="type_vendor" name="userType" value="Vendor" class="custom-control-input" {{ old('userType')=='Vendor' ? 'checked' : '' }}>
									<label class="custom-control-label" for="type_vendor">Fournisseur</label>
								</div>
							</div>

							<button type="submit" class="btn btn-primary">Créer mon compte</button>
							<hr>
							<p class="mb-0">Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

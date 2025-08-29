@extends('front.layout.layout')

@section('content')
	<div class="container py-4">
		<div class="row">
			<div class="col-12 mb-3">
				<h3 class="font-weight-semi-bold mb-0"><i class="fa fa-user mr-2"></i>Mon compte</h3>
			</div>
		</div>

		<div class="row">
			<!-- Sidebar -->
			<div class="col-lg-3 mb-4">
				<div class="list-group shadow-sm">
					<div class="list-group-item active d-flex align-items-center" style="background:#d9a09a;border-color:#d9a09a;">
						<i class="fa fa-user mr-2"></i>
						<strong>Mon Compte</strong>
					</div>
					@php($isVendor = auth()->user()->role === 1)
					@if ($isVendor)
						<a href="{{ route('vendor.dashboard') }}" class="list-group-item list-group-item-action">
							<i class="fa fa-tachometer-alt mr-2"></i> Tableau de bord
						</a>
					@elseif (Route::has('dashboard'))
						<a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
							<i class="fa fa-tachometer-alt mr-2"></i> Tableau de bord
						</a>
					@endif
					<a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action active">
						<i class="fa fa-id-card mr-2"></i> Mon profil
					</a>
					<a href="#password" class="list-group-item list-group-item-action">
						<i class="fa fa-key mr-2"></i> Changer le mot de passe
					</a>
					<a href="#delete" class="list-group-item list-group-item-action text-danger">
						<i class="fa fa-trash-alt mr-2"></i> Supprimer mon compte
					</a>
				</div>
			</div>

			<!-- Content -->
			<div class="col-lg-9">
				<!-- Informations du profil -->
				<div class="card shadow-sm mb-4" id="profile">
					<div class="card-header bg-white">
						<i class="fa fa-user-edit mr-2 text-primary"></i><strong>Modifier mon profil</strong>
					</div>
					<div class="card-body">
						<p class="text-muted mb-3">Informations personnelles</p>

						<form id="send-verification" method="post" action="{{ route('verification.send') }}">
							@csrf
						</form>

						<form method="post" action="{{ route('profile.update') }}">
							@csrf
							@method('patch')

							<div class="form-group">
								<label for="name">Nom complet</label>
								<input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autocomplete="name">
								@error('name')
									<small class="text-danger d-block">{{ $message }}</small>
								@enderror
							</div>

							<div class="form-group">
								<label for="email">Email</label>
								<input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
								@error('email')
									<small class="text-danger d-block">{{ $message }}</small>
								@enderror

								@php($mustVerify = $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail)
								@if ($mustVerify && ! $user->hasVerifiedEmail())
									<div class="alert alert-warning mt-2 mb-0">
										<i class="fa fa-info-circle mr-1"></i>Votre adresse e‑mail n'est pas vérifiée.
										<button form="send-verification" class="btn btn-sm btn-outline-primary ml-2">Renvoyer l'e‑mail</button>
										@if (session('status') === 'verification-link-sent')
											<small class="text-success d-block mt-2">Un nouveau lien de vérification a été envoyé.</small>
										@endif
									</div>
								@endif
							</div>

							@if (session('status') === 'profile-updated')
								<div class="alert alert-success">Modifications enregistrées.</div>
							@endif

							<button type="submit" class="btn btn-sm btn-outline-danger">
								<i class="fa fa-save mr-1"></i> Enregistrer les modifications
							</button>
						</form>
                </div>
            </div>

				<!-- Mot de passe -->
				<div class="card shadow-sm mb-4" id="password">
					<div class="card-header bg-white">
						<i class="fa fa-key mr-2 text-primary"></i><strong>Changer le mot de passe</strong>
					</div>
					<div class="card-body">
						<form method="post" action="{{ route('password.update') }}">
							@csrf
							@method('put')

							<div class="form-group">
								<label for="current_password">Mot de passe actuel</label>
								<input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
								@foreach (($errors->updatePassword->get('current_password') ?? []) as $msg)
									<small class="text-danger d-block">{{ $msg }}</small>
								@endforeach
							</div>

							<div class="form-group">
								<label for="password">Nouveau mot de passe</label>
								<input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
								@foreach (($errors->updatePassword->get('password') ?? []) as $msg)
									<small class="text-danger d-block">{{ $msg }}</small>
								@endforeach
							</div>

							<div class="form-group">
								<label for="password_confirmation">Confirmer le nouveau mot de passe</label>
								<input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
								@foreach (($errors->updatePassword->get('password_confirmation') ?? []) as $msg)
									<small class="text-danger d-block">{{ $msg }}</small>
								@endforeach
							</div>

							@if (session('status') === 'password-updated')
								<div class="alert alert-success">Mot de passe mis à jour.</div>
							@endif

							<button type="submit" class="btn btn-sm btn-warning">
								<i class="fa fa-key mr-1"></i> Changer le mot de passe
							</button>
						</form>
                </div>
            </div>

				<!-- Suppression du compte -->
				<div class="card shadow-sm border-danger" id="delete">
					<div class="card-header bg-white">
						<i class="fa fa-exclamation-triangle mr-2 text-danger"></i><strong>Supprimer mon compte</strong>
					</div>
					<div class="card-body">
						<div class="alert alert-warning">
							<i class="fa fa-info-circle mr-1"></i>
							Attention : Cette action est irréversible. Toutes vos données seront définitivement supprimées.
						</div>

						<form method="post" action="{{ route('profile.destroy') }}">
							@csrf
							@method('delete')
							<div class="form-group">
								<label for="delete_password">Confirmez votre mot de passe</label>
								<input id="delete_password" name="password" type="password" class="form-control" placeholder="Mot de passe">
								@foreach (($errors->userDeletion->get('password') ?? []) as $msg)
									<small class="text-danger d-block">{{ $msg }}</small>
								@endforeach
							</div>
							<button type="submit" class="btn btn-danger">
								<i class="fa fa-trash mr-1"></i> Supprimer définitivement mon compte
							</button>
						</form>
					</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('front.layout.layout')

@section('content')
<div class="container-fluid py-4 px-3">
  <div class="row">
    <div class="col-xl-3 col-lg-4 mb-4">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white py-2">
          <i class="fa fa-user mr-1"></i> Mon Compte
        </div>
        <div class="card-body">
          <div class="text-center mb-3">
            <span class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;">
              <i class="fa fa-user fa-lg text-muted"></i>
            </span>
            <div class="mt-2 font-weight-bold">{{ auth()->user()->name }}</div>
            <div class="small text-muted">{{ auth()->user()->email }}</div>
          </div>
          <ul class="list-group list-group-flush mb-3">
            @if (Route::has('profile.edit'))
            <li class="list-group-item px-0"><i class="fa fa-id-card mr-2 text-muted"></i><a href="{{ route('profile.edit') }}" class="text-dark">Informations du profil</a></li>
            @endif
            <li class="list-group-item px-0"><i class="fa fa-lock mr-2 text-muted"></i><a href="{{ route('profile.edit') }}#v-pills-password" class="text-dark">Mot de passe</a></li>
            <li class="list-group-item px-0"><i class="fa fa-shield-alt mr-2 text-muted"></i><a href="{{ route('profile.edit') }}" class="text-dark">Sécurité</a></li>
          </ul>
          <a href="{{ url('/') }}" class="btn btn-success btn-block mb-2"><i class="fa fa-store mr-1"></i> Retour à la boutique</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger btn-block" type="submit"><i class="fa fa-sign-out-alt mr-1"></i> Déconnexion</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xl-9 col-lg-8">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0 font-weight-bold">MON TABLEAU DE BORD</h5>
        <div class="small text-muted">Accueil  -  Mon Compte</div>
      </div>

      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fa fa-check-circle mr-2"></i>
          {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      <div class="row mb-4">
        <div class="col-md-3 mb-3">
          <div class="stat-card h-100">
            <div class="stat-icon bg-secondary"><i class="fa fa-shopping-cart"></i></div>
            <div class="stat-label">Commandes totales</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="stat-card h-100">
            <div class="stat-icon bg-warning"><i class="fa fa-clock"></i></div>
            <div class="stat-label">En attente</div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="stat-card h-100">
            <div class="stat-icon bg-success"><i class="fa fa-check"></i></div>
            <div class="stat-label">Payées</div>
            <div class="stat-value">{{ $stats['paid'] }}</div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="stat-card h-100">
            <div class="stat-icon bg-info"><i class="fa fa-calendar"></i></div>
            <div class="stat-label">Membre depuis</div>
            <div class="stat-value">{{ $stats['member_since'] }}</div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-header bg-info text-white">Mes Commandes</div>
        <div class="table-responsive">
          <table class="table mb-0 orders-table">
            <thead class="thead-light">
              <tr>
                <th class="align-middle">N° Commande</th>
                <th class="align-middle">Date</th>
                <th class="align-middle">Produits</th>
                <th class="text-right align-middle">Total</th>
                <th class="align-middle">Statut</th>
                <th class="align-middle">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $order)
                <tr>
                  <td>#{{ $order->id }}</td>
                  <td>{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>
                  <td>
                    @foreach($order->details as $d)
                      <div class="order-product d-flex align-items-center mb-1">
                        <img
                          src="{{ asset('product-image/small/' . (($d->product->main_image ?? null) ?: 'no-image.jpg')) }}"
                          alt="{{ $d->product->product_name ?? 'Produit' }}"
                          class="order-thumb mr-2" style="width:60px;height:60px;object-fit:cover;border-radius:6px;border:1px solid #eee;">
                        <div>{{ $d->product->product_name ?? 'Produit' }} x {{ $d->quantity }}</div>
                      </div>
                    @endforeach
                  </td>
                  <td class="text-right">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                  <td>
                    @if($order->order_status === 'paid')
                      <span class="badge badge-success">Payée</span>
                    @elseif($order->order_status === 'pending')
                      <span class="badge badge-warning">En attente</span>
                    @else
                      <span class="badge badge-secondary">{{ ucfirst($order->order_status) }}</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">Voir</a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center text-muted">Aucune commande pour le moment.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
.stat-card {
  background: #fff;
  border-radius: 8px;
  padding: 1.5rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.2s;
}
.stat-card:hover {
  transform: translateY(-2px);
}
.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  color: white;
  font-size: 1.2rem;
}
.stat-label {
  font-size: 0.875rem;
  color: #6c757d;
  margin-bottom: 0.5rem;
}
.stat-value {
  font-size: 1.5rem;
  font-weight: bold;
  color: #212529;
}
.orders-table th {
  background: #f8f9fa;
  border-top: none;
  font-weight: 600;
}
.order-product {
  border-bottom: 1px solid #f8f9fa;
  padding-bottom: 0.5rem;
}
.order-product:last-child {
  border-bottom: none;
  padding-bottom: 0;
}
.order-thumb {
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Style personnalisé pour le message de succès */
.alert-success {
  border: none;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
  border-left: 4px solid #28a745;
  background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
}

.alert-success .fa-check-circle {
  color: #28a745;
  font-size: 1.1rem;
}

.alert-success .close {
  color: #28a745;
  opacity: 0.7;
}

.alert-success .close:hover {
  opacity: 1;
}
</style>
@endpush



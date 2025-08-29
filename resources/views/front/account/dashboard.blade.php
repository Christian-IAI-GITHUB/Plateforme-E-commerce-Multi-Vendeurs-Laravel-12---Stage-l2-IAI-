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
.stat-card{border:1px solid #e9ecef;border-radius:.25rem;padding:12px 12px 10px 12px;position:relative;background:#fff}
.stat-icon{width:36px;height:36px;border-radius:50%;color:#fff;display:flex;align-items:center;justify-content:center;margin-bottom:6px}
.stat-label{font-size:.82rem;color:#6c757d}
.stat-value{font-weight:600;font-size:1.25rem}
.list-group-item{border:0;border-bottom:1px solid #f1f1f1}
.order-thumb{width:28px;height:28px;object-fit:cover;border-radius:4px;border:1px solid #eee}
.orders-table th, .orders-table td{padding-top:14px;padding-bottom:14px}
.orders-table th:nth-child(1){width:10%}
.orders-table th:nth-child(2){width:16%}
.orders-table th:nth-child(3){width:44%}
.orders-table th:nth-child(4){width:15%}
.orders-table th:nth-child(5){width:10%}
.orders-table th:nth-child(6){width:5%}
</style>
@endpush



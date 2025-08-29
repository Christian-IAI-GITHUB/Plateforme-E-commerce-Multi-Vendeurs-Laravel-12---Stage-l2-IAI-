@extends('admin.layout.layout')
@section('content')
<main class="app-main">
  <div class="app-content p-3">
    <div class="container-fluid">
      <h3 class="mb-3">Commandes</h3>
      <div class="card">
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Date</th>
                <th>Client</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orders as $o)
              <tr>
                <td>#{{ $o->id }}</td>
                <td>{{ optional($o->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $o->customer_name }}</td>
                <td>{{ number_format($o->total_amount,0,',',' ') }} FCFA</td>
                <td>
                  @if($o->order_status === 'paid')
                    <span class="badge bg-success">Payée</span>
                  @elseif($o->order_status === 'pending')
                    <span class="badge bg-warning text-dark">En attente</span>
                  @else
                    <span class="badge bg-secondary">{{ ucfirst($o->order_status) }}</span>
                  @endif
                </td>
                <td><a href="{{ url('admin/orders/'.$o->id) }}" class="btn btn-sm btn-primary">Voir</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection



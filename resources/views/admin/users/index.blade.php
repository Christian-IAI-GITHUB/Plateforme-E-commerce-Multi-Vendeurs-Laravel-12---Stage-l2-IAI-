@extends('admin.layout.layout')
@section('content')
<main class="app-main">
  <div class="app-content p-3">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">Utilisateurs</h3>
        <form method="get" class="d-flex align-items-center">
          <select name="role" class="form-select me-2" style="width:auto">
            <option value="" {{ ($currentRole ?? '')=='' ? 'selected' : '' }}>Tous</option>
            <option value="vendor" {{ ($currentRole ?? '')=='vendor' ? 'selected' : '' }}>Vendeurs</option>
            <option value="customer" {{ ($currentRole ?? '')=='customer' ? 'selected' : '' }}>Clients</option>
          </select>
          <button class="btn btn-primary" type="submit">Filtrer</button>
        </form>
      </div>
      <div class="card">
        <div class="table-responsive">
          <table class="table table-striped mb-0">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Type</th>
                <th>Créé le</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $u)
              <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>
                  @if($u->role === 1)
                    <span class="badge bg-dark">Vendeur</span>
                  @elseif($u->role === 2)
                    <span class="badge bg-dark">Client</span>
                  @else
                    <span class="badge bg-secondary">Inconnu</span>
                  @endif
                </td>
                <td>{{ optional($u->created_at)->format('d/m/Y H:i') }}</td>
                <td class="text-right">
                  {{--
                  <form action="{{ url('admin/users/'.$u->id.'/toggle') }}" method="post" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">Activer/Désactiver</button>
                  </form>
                  --}}
                  <form action="{{ url('admin/users/'.$u->id) }}" method="post" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                  </form>
                </td>
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



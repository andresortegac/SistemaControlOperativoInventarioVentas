@extends('layouts.app')

@section('title', 'Mi Perfil - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mi Perfil</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Información Personal
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('perfil') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" name="telefono" value="{{ old('telefono', $user->telefono) }}">
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea class="form-control @error('direccion') is-invalid @enderror" 
                                      id="direccion" name="direccion" rows="2">{{ old('direccion', $user->direccion) }}</textarea>
                            @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Perfil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

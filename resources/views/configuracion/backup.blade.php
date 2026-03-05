@extends('layouts.app')

@section('title', 'Backup - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Backup de Base de Datos</h1>
        <a href="{{ route('configuracion.index') }}" class="btn btn-secondary btn-rounded">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="table-card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-database me-2"></i>Generar Backup
                    </h6>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-cloud-download-alt fa-5x text-warning mb-4"></i>
                    
                    <h5>Descargar Backup de la Base de Datos</h5>
                    <p class="text-muted mb-4">
                        Esta función generará un archivo SQL con todos los datos de la base de datos.
                        Guárdelo en un lugar seguro.
                    </p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        El backup incluye: usuarios, productos, ventas, clientes, gastos, inventario y configuración.
                    </div>
                    
                    <form action="{{ route('configuracion.backup.descargar') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-download me-2"></i>Descargar Backup
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <h6 class="fw-bold">Recomendaciones:</h6>
                    <ul class="text-muted text-start">
                        <li>Realice backups periódicamente</li>
                        <li>Guarde los archivos en un lugar seguro</li>
                        <li>Almacene copias en diferentes ubicaciones</li>
                        <li>Verifique que los backups sean funcionales</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

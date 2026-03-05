@extends('layouts.app')

@section('title', 'Reportes - Licoreras')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reportes</h1>
    </div>

    <div class="row">
        <!-- Reporte de Ventas -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stat-card primary h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon me-3">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Ventas</h5>
                        <small class="text-muted">Reporte detallado de ventas</small>
                    </div>
                </div>
                <p class="text-muted small">Visualiza todas las ventas realizadas en un período específico con detalles de productos, totales y métodos de pago.</p>
                <a href="{{ route('reportes.ventas') }}" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-eye me-2"></i>Ver Reporte
                </a>
            </div>
        </div>

        <!-- Reporte de Inventario -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stat-card success h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon me-3">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Inventario</h5>
                        <small class="text-muted">Estado actual del inventario</small>
                    </div>
                </div>
                <p class="text-muted small">Consulta el valor total del inventario, productos con stock bajo y distribución por categorías.</p>
                <a href="{{ route('reportes.inventario') }}" class="btn btn-success btn-sm w-100">
                    <i class="fas fa-eye me-2"></i>Ver Reporte
                </a>
            </div>
        </div>

        <!-- Reporte de Gastos -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stat-card danger h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon me-3">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Gastos</h5>
                        <small class="text-muted">Control de egresos</small>
                    </div>
                </div>
                <p class="text-muted small">Analiza los gastos del negocio por categoría y período para un mejor control financiero.</p>
                <a href="{{ route('reportes.gastos') }}" class="btn btn-danger btn-sm w-100">
                    <i class="fas fa-eye me-2"></i>Ver Reporte
                </a>
            </div>
        </div>

        <!-- Reporte de Utilidades -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stat-card info h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon me-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Utilidades</h5>
                        <small class="text-muted">Ganancias del negocio</small>
                    </div>
                </div>
                <p class="text-muted small">Calcula tus ganancias brutas y netas considerando ventas, costos y gastos operativos.</p>
                <a href="{{ route('reportes.utilidades') }}" class="btn btn-info btn-sm w-100">
                    <i class="fas fa-eye me-2"></i>Ver Reporte
                </a>
            </div>
        </div>

        <!-- Reporte de Clientes -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="stat-card warning h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon me-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Clientes</h5>
                        <small class="text-muted">Análisis de clientes</small>
                    </div>
                </div>
                <p class="text-muted small">Identifica tus clientes más frecuentes y sus hábitos de compra.</p>
                <a href="{{ route('reportes.clientes') }}" class="btn btn-warning btn-sm w-100">
                    <i class="fas fa-eye me-2"></i>Ver Reporte
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

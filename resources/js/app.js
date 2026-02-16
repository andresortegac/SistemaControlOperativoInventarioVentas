import './bootstrap.js';
import '../css/app.css';

// Import Bootstrap
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Barcode scanner handler
window.handleBarcodeScan = function(callback) {
    let barcode = '';
    let lastKeyTime = Date.now();
    
    document.addEventListener('keydown', function(e) {
        const currentTime = Date.now();
        
        // Reset barcode if too much time passed
        if (currentTime - lastKeyTime > 100) {
            barcode = '';
        }
        
        lastKeyTime = currentTime;
        
        // Ignore special keys
        if (e.key.length === 1) {
            barcode += e.key;
        }
        
        // Enter key indicates end of barcode
        if (e.key === 'Enter' && barcode.length > 3) {
            e.preventDefault();
            if (typeof callback === 'function') {
                callback(barcode);
            }
            barcode = '';
        }
    });
};

// Format currency
window.formatCurrency = function(amount, symbol = '$') {
    return symbol + ' ' + parseFloat(amount).toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
};

// Format number
window.formatNumber = function(number) {
    return parseInt(number).toLocaleString('es-CO');
};

// Confirm delete
window.confirmDelete = function(message = '¿Estás seguro de eliminar este registro?') {
    return confirm(message);
};

// Print element
window.printElement = function(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Imprimir</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        @media print { body { margin: 0; } }
                    </style>
                </head>
                <body>${element.innerHTML}</body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
};

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        }, 5000);
    });
});

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

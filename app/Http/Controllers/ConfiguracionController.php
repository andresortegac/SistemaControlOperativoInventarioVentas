<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $configuracion = Configuracion::getConfiguracion();
        return view('configuracion.index', compact('configuracion'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre_negocio' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'nit' => 'nullable|string|max:50',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'impuesto_porcentaje' => 'required|numeric|min:0|max:100',
            'moneda' => 'required|string|max:10',
            'simbolo_moneda' => 'required|string|max:5',
            'decimales' => 'required|integer|min:0|max:4',
            'formato_fecha' => 'required|string|max:20',
            'stock_alerta' => 'required|integer|min:0',
            'mensaje_factura' => 'nullable|string',
        ]);

        $configuracion = Configuracion::getConfiguracion();
        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => 'image|max:2048',
            ]);

            if ($configuracion->logo && file_exists(public_path($configuracion->logo))) {
                unlink(public_path($configuracion->logo));
            }

            $logo = $request->file('logo');
            $nombreLogo = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $nombreLogo);
            $data['logo'] = 'images/' . $nombreLogo;
        }

        $data['factura_con_impuesto'] = $request->has('factura_con_impuesto');

        $configuracion->update($data);

        return redirect()->route('configuracion.index')
            ->with('success', 'Configuración actualizada exitosamente');
    }

    public function backup()
    {
        return view('configuracion.backup');
    }

    public function descargarBackup()
    {
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host');
        
        $filename = 'backup_' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/' . $filename);
        
        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s > %s',
            escapeshellarg($dbHost),
            escapeshellarg($dbUser),
            escapeshellarg($dbPass),
            escapeshellarg($dbName),
            escapeshellarg($path)
        );
        
        system($command);
        
        if (file_exists($path)) {
            return response()->download($path)->deleteFileAfterSend();
        }
        
        return redirect()->back()->with('error', 'Error al generar el backup');
    }
}

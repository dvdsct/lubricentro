<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class AsistenciaController extends Controller
{
    /**
     * Muestra la vista pública para ingresar el PIN de acceso.
     */
    public function scan(Request $request)
    {
        // Si el PIN ya fue verificado y el usuario ya está autenticado,
        // va directo a la pantalla de registro de asistencia.
        if (session('asistencia_pin_verified') === true) {
            if (Auth::check()) {
                return redirect()->route('asistencia.registrar');
            }
        }

        return view('asistencia.scan');
    }

    /**
     * Verifica el PIN ingresado por el empleado.
     */
    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        if ($request->pin === '1226500') {
            session(['asistencia_pin_verified' => true]);
            
            // Si ya está autenticado, va a registrar, sino va a login
            if (Auth::check()) {
                return redirect()->route('asistencia.registrar');
            } else {
                // Laravel redireccionará de vuelta a /asistencia/registrar después de iniciar sesión
                return redirect()->route('asistencia.registrar');
            }
        }

        return redirect()->back()->withErrors(['pin' => 'El PIN de acceso es incorrecto.']);
    }

    /**
     * Muestra la vista para confirmar la asistencia (Entrada/Salida) y obtener geolocalización.
     */
    public function registrar(Request $request)
    {
        // Obliga a tener verificado el PIN
        if (session('asistencia_pin_verified') !== true) {
            return redirect()->route('asistencia.scan')->with('warning', 'Debes escanear el código QR e ingresar el PIN.');
        }

        $user = Auth::user();
        
        // Determina el tipo de fichaje (Entrada o Salida) según el último registro
        $ultimoRegistro = Asistencia::where('user_id', $user->id)
            ->orderBy('fecha_hora', 'desc')
            ->first();

        $tipo = 'entrada';
        if ($ultimoRegistro && $ultimoRegistro->tipo === 'entrada') {
            $tipo = 'salida';
        }

        return view('asistencia.registrar', compact('user', 'tipo'));
    }

    /**
     * Almacena el registro de entrada o salida.
     */
    public function store(Request $request)
    {
        if (session('asistencia_pin_verified') !== true) {
            return redirect()->route('asistencia.scan')->with('warning', 'Debes ingresar el PIN.');
        }

        $request->validate([
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        $user = Auth::user();
        
        // Recalcular tipo en el backend para evitar manipulación en el frontend
        $ultimoRegistro = Asistencia::where('user_id', $user->id)
            ->orderBy('fecha_hora', 'desc')
            ->first();

        $tipo = 'entrada';
        if ($ultimoRegistro && $ultimoRegistro->tipo === 'entrada') {
            $tipo = 'salida';
        }

        // Crear registro de asistencia
        $asistencia = Asistencia::create([
            'user_id' => $user->id,
            'tipo' => $tipo,
            'fecha_hora' => Carbon::now(),
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        // Limpiar la verificación del PIN para el próximo fichaje
        session()->forget('asistencia_pin_verified');

        return view('asistencia.success', compact('asistencia', 'user'));
    }

    /**
     * Panel del administrador para ver ingresos/egresos y el código QR de asistencia.
     */
    public function control(Request $request)
    {
        $query = Asistencia::with('user');

        // Filtro por fecha
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->fecha);
        }

        // Filtro por empleado/usuario
        if ($request->filled('empleado')) {
            $search = $request->empleado;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Logs paginados
        $asistencias = $query->orderBy('fecha_hora', 'desc')->paginate(15);

        // URL absoluta para el QR
        $scanUrl = route('asistencia.scan');

        return view('asistencia.control', compact('asistencias', 'scanUrl'));
    }

    /**
     * Descarga el QR de asistencia en formato PNG de 1000x1000.
     */
    public function downloadQr()
    {
        $scanUrl = route('asistencia.scan');
        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&data=" . urlencode($scanUrl);

        try {
            $client = new Client();
            $response = $client->get($qrApiUrl);
            $imageContent = $response->getBody()->getContents();

            return response($imageContent)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="qr_asistencia_1000x1000.png"');
        } catch (\Exception $e) {
            // Si la llamada externa falla, redirigimos con error
            return redirect()->back()->with('error', 'No se pudo generar la descarga del QR. Inténtalo de nuevo o usa la descarga local desde el navegador.');
        }
    }

    /**
     * Cierra la sesión del empleado de forma segura y redirige al escáner de PIN.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('asistencia.scan');
    }

    /**
     * Muestra el perfil de asistencia del empleado con estadísticas de horas y el historial completo de turnos.
     */
    public function empleadoPerfil(User $user)
    {
        $records = Asistencia::where('user_id', $user->id)
            ->orderBy('fecha_hora', 'asc')
            ->get();

        $historial = [];
        $totalHoursMonth = 0;
        $totalHoursWeek = 0;

        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        $count = count($records);
        for ($i = 0; $i < $count; $i++) {
            $current = $records[$i];
            
            if ($current->tipo === 'entrada') {
                $entrada = $current;
                $salida = null;
                
                if (isset($records[$i + 1]) && $records[$i + 1]->tipo === 'salida') {
                    $salida = $records[$i + 1];
                    $i++;
                }
                
                if ($salida) {
                    $durationSeconds = $entrada->fecha_hora->diffInSeconds($salida->fecha_hora);
                    $hours = $durationSeconds / 3600;
                    
                    if ($entrada->fecha_hora->greaterThanOrEqualTo($startOfWeek)) {
                        $totalHoursWeek += $hours;
                    }
                    if ($entrada->fecha_hora->greaterThanOrEqualTo($startOfMonth)) {
                        $totalHoursMonth += $hours;
                    }
                    
                    $mins = round(($durationSeconds % 3600) / 60);
                    $hrs = floor($durationSeconds / 3600);
                    
                    if ($mins === 60) {
                        $hrs += 1;
                        $mins = 0;
                    }
                    
                    $duracionFormateada = "{$hrs}h {$mins}m";
                } else {
                    $duracionFormateada = 'En curso / Pendiente';
                }
                
                $historial[] = [
                    'entrada' => $entrada,
                    'salida' => $salida,
                    'duracion' => $duracionFormateada,
                ];
            } else {
                $historial[] = [
                    'entrada' => null,
                    'salida' => $current,
                    'duracion' => 'Sin entrada registrada',
                ];
            }
        }

        $historial = array_reverse($historial);

        $semanaFormateada = floor($totalHoursWeek) . 'h ' . round(($totalHoursWeek - floor($totalHoursWeek)) * 60) . 'm';
        $mesFormateado = floor($totalHoursMonth) . 'h ' . round(($totalHoursMonth - floor($totalHoursMonth)) * 60) . 'm';

        return view('asistencia.empleado', compact('user', 'historial', 'semanaFormateada', 'mesFormateado'));
    }

    /**
     * Muestra la vista pública para ingresar el PIN de registro.
     */
    public function registerPin(Request $request)
    {
        if (session('register_pin_verified') === true) {
            return redirect()->route('register');
        }

        return view('auth.register-pin');
    }

    /**
     * Verifica el PIN de registro.
     */
    public function verifyRegisterPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        if ($request->pin === '1226500') {
            session(['register_pin_verified' => true]);
            return redirect()->route('register');
        }

        return redirect()->back()->withErrors(['pin' => 'El PIN de registro es incorrecto.']);
    }
}

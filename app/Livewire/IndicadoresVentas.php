<?php

namespace App\Livewire;

use App\Models\Pago;
use App\Models\Orden;
use App\Models\MedioPago;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IndicadoresVentas extends Component
{
    public $period = 'this_month'; // 'today', 'this_week', 'this_month', 'this_year', 'custom', 'all_time'
    public $startDate;
    public $endDate;

    // KPIs
    public $totalRevenue = 0;
    public $totalExpenses = 0;
    public $totalBalance = 0;
    public $totalOrdersCount = 0;

    // Payment breakdowns
    public $pagoEfectivo = 0;
    public $pagoTarjeta = 0;
    public $pagoCtaCte = 0;
    public $pagoTransferencia = 0;
    public $pagoCheque = 0;

    // Sector breakdowns
    public $ordersLubricentroCount = 0;
    public $ordersLubricentroTotal = 0;
    public $ordersLavaderoCount = 0;
    public $ordersLavaderoTotal = 0;

    // Top cards
    public $topCards = [];

    // Chart Data
    public $chartLabels = [];
    public $chartData = [];

    public function mount()
    {
        // Rango de fechas por defecto: desde inicio de mes hasta hoy
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->toDateString();
        $this->calculateStats();
    }

    public function updatedPeriod()
    {
        $this->calculateStats();
        $this->dispatch('update-chart', [
            'labels' => $this->chartLabels,
            'data' => $this->chartData,
        ]);
    }

    public function updatedStartDate()
    {
        if ($this->period === 'custom') {
            $this->calculateStats();
            $this->dispatch('update-chart', [
                'labels' => $this->chartLabels,
                'data' => $this->chartData,
            ]);
        }
    }

    public function updatedEndDate()
    {
        if ($this->period === 'custom') {
            $this->calculateStats();
            $this->dispatch('update-chart', [
                'labels' => $this->chartLabels,
                'data' => $this->chartData,
            ]);
        }
    }

    public function calculateStats()
    {
        $start = null;
        $end = Carbon::now()->endOfDay();

        switch ($this->period) {
            case 'today':
                $start = Carbon::today();
                break;
            case 'this_week':
                $start = Carbon::now()->startOfWeek();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                break;
            case 'this_year':
                $start = Carbon::now()->startOfYear();
                break;
            case 'custom':
                $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
                $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : Carbon::now()->endOfDay();
                break;
            case 'all_time':
            default:
                $start = null;
                break;
        }

        // Fetch payments in period
        $pagoQuery = Pago::query();
        if ($start) {
            $pagoQuery->whereBetween('created_at', [$start, $end]);
        }
        $pagos = $pagoQuery->get();

        // 1. Calculate main KPIs
        $this->totalRevenue = $pagos->where('in_out', 'in')->sum(function ($p) {
            return abs((float) $p->total);
        });
        $this->totalExpenses = $pagos->where('in_out', 'out')->sum(function ($p) {
            return abs((float) $p->total);
        });
        $this->totalBalance = $this->totalRevenue - $this->totalExpenses;

        // Fetch orders count in period
        $ordenQuery = Orden::query();
        if ($start) {
            $ordenQuery->whereBetween('created_at', [$start, $end]);
        }
        $ordenes = $ordenQuery->get();
        $this->totalOrdersCount = $ordenes->count();

        // 2. Payment breakdowns
        $this->pagoEfectivo = $pagos->where('in_out', 'in')->where('medio_pago_id', 2)->sum(function ($p) {
            return abs((float) $p->total);
        });
        $this->pagoTransferencia = $pagos->where('in_out', 'in')->where('medio_pago_id', 5)->sum(function ($p) {
            return abs((float) $p->total);
        });
        $this->pagoCtaCte = $pagos->where('in_out', 'in')->where('medio_pago_id', 4)->sum(function ($p) {
            return abs((float) $p->total);
        });
        $this->pagoCheque = $pagos->where('in_out', 'in')->where('medio_pago_id', 3)->sum(function ($p) {
            return abs((float) $p->total);
        });

        $debitoId = MedioPago::where('descripcion', 'like', '%Debito%')->value('id') ?? 6;
        $this->pagoTarjeta = $pagos->where('in_out', 'in')->filter(function ($p) use ($debitoId) {
            return in_array($p->medio_pago_id, [1, $debitoId]);
        })->sum(function ($p) {
            return abs((float) $p->total);
        });

        // 3. Sector breakdowns
        $this->ordersLubricentroCount = $ordenes->filter(function ($o) {
            return $o->motivo != '1';
        })->count();
        $this->ordersLavaderoCount = $ordenes->filter(function ($o) {
            return $o->motivo == '1';
        })->count();

        $this->ordersLubricentroTotal = $pagos->where('in_out', 'in')->where('concepto', 'Lubricentro')->sum(function ($p) {
            return abs((float) $p->total);
        });
        $this->ordersLavaderoTotal = $pagos->where('in_out', 'in')->where('concepto', 'Lavadero')->sum(function ($p) {
            return abs((float) $p->total);
        });

        // 4. Top credit/debit cards
        $cardQuery = DB::table('pago_tarjetas')
            ->join('plans', 'pago_tarjetas.plan_id', '=', 'plans.id')
            ->join('tarjetas', 'plans.tarjeta_id', '=', 'tarjetas.id')
            ->select('tarjetas.nombre_tarjeta', DB::raw('count(*) as count'), DB::raw('sum(cast(pago_tarjetas.total as decimal(10,2))) as total'));

        if ($start) {
            $cardQuery->whereBetween('pago_tarjetas.created_at', [$start, $end]);
        }

        $this->topCards = $cardQuery
            ->groupBy('tarjetas.nombre_tarjeta')
            ->orderByDesc('count')
            ->get()
            ->toArray();

        // 5. Chart labels and datasets
        $labels = [];
        $data = [];
        $ingresos = $pagos->where('in_out', 'in');

        if ($this->period == 'today') {
            for ($h = 8; $h <= 20; $h++) {
                $labels[] = sprintf('%02d:00', $h);
                $data[] = $ingresos->filter(function ($p) use ($h) {
                    return Carbon::parse($p->created_at)->hour == $h;
                })->sum(function ($p) {
                    return abs((float) $p->total);
                });
            }
        } elseif ($this->period == 'this_week') {
            $days = [
                1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom'
            ];
            foreach ($days as $num => $name) {
                $labels[] = $name;
                $data[] = $ingresos->filter(function ($p) use ($num) {
                    return Carbon::parse($p->created_at)->dayOfWeekIso == $num;
                })->sum(function ($p) {
                    return abs((float) $p->total);
                });
            }
        } elseif ($this->period == 'this_month') {
            $daysInMonth = Carbon::now()->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $labels[] = "$d";
                $data[] = $ingresos->filter(function ($p) use ($d) {
                    return Carbon::parse($p->created_at)->day == $d;
                })->sum(function ($p) {
                    return abs((float) $p->total);
                });
            }
        } elseif ($this->period == 'this_year') {
            $months = [
                1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
                7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
            ];
            foreach ($months as $num => $name) {
                $labels[] = $name;
                $data[] = $ingresos->filter(function ($p) use ($num) {
                    return Carbon::parse($p->created_at)->month == $num;
                })->sum(function ($p) {
                    return abs((float) $p->total);
                });
            }
        } elseif ($this->period == 'custom') {
            $diffInDays = $start->diffInDays($end);
            if ($diffInDays <= 1) {
                for ($h = 8; $h <= 20; $h++) {
                    $labels[] = sprintf('%02d:00', $h);
                    $data[] = $ingresos->filter(function ($p) use ($h) {
                        return Carbon::parse($p->created_at)->hour == $h;
                    })->sum(function ($p) {
                        return abs((float) $p->total);
                    });
                }
            } elseif ($diffInDays <= 35) {
                $current = clone $start;
                while ($current <= $end) {
                    $dayStr = $current->format('d/m');
                    $labels[] = $dayStr;
                    $dayNum = $current->day;
                    $monthNum = $current->month;
                    $yearNum = $current->year;
                    $data[] = $ingresos->filter(function ($p) use ($dayNum, $monthNum, $yearNum) {
                        $pDate = Carbon::parse($p->created_at);
                        return $pDate->day == $dayNum && $pDate->month == $monthNum && $pDate->year == $yearNum;
                    })->sum(function ($p) {
                        return abs((float) $p->total);
                    });
                    $current->addDay();
                }
            } else {
                $current = clone $start;
                $limit = 0;
                while ($current <= $end && $limit < 24) {
                    $monthStr = $current->translatedFormat('M Y');
                    $labels[] = $monthStr;
                    $monthNum = $current->month;
                    $yearNum = $current->year;
                    $data[] = $ingresos->filter(function ($p) use ($monthNum, $yearNum) {
                        $pDate = Carbon::parse($p->created_at);
                        return $pDate->month == $monthNum && $pDate->year == $yearNum;
                    })->sum(function ($p) {
                        return abs((float) $p->total);
                    });
                    $current->addMonth();
                    $limit++;
                }
            }
        } else {
            // Last 6 months
            for ($m = 5; $m >= 0; $m--) {
                $monthDate = Carbon::now()->subMonths($m);
                $labels[] = $monthDate->translatedFormat('M Y');
                $data[] = $ingresos->filter(function ($p) use ($monthDate) {
                    $pDate = Carbon::parse($p->created_at);
                    return $pDate->year == $monthDate->year && $pDate->month == $monthDate->month;
                })->sum(function ($p) {
                    return abs((float) $p->total);
                });
            }
        }

        $this->chartLabels = $labels;
        $this->chartData = $data;
    }

    public function render()
    {
        return view('livewire.indicadores-ventas');
    }
}

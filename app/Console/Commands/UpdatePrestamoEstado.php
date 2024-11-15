<?php

namespace App\Console\Commands;

use App\Http\Controllers\PrestamosController;
use App\Models\DetallePrestamoModel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdatePrestamoEstado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prestamo:update-estado';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de los préstamos basados en la fecha de devolución';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::now();

        $prestamos = DetallePrestamoModel::where('EstadoPrestamo', 'pendiente')
        ->where('fecha_devolucion', '<', $today)
        ->get();

        foreach($prestamos as $prestamo){
            $prestamo->EstadoPrestamo = 'atrasado';
            $prestamo->save();
        }

        $this->info('Estados de préstamos actualizados correctamente.');
        return 0;
    }
}

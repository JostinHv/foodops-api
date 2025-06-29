<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CajeroController extends Controller
{
    public function index():View
    {
        // Aquí puedes agregar la lógica para mostrar la vista del cajero
        return view('cajero.facturacion'); 
    }

    public function caja(): View
    {
        // Aquí puedes agregar la lógica para mostrar la vista de facturación
        return view('cajero.caja');
    }
}   
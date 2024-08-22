<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NiubizService;

class NiubizController extends Controller
{
    protected $niubiz;

    public function __construct(NiubizService $niubiz)
    {
        $this->niubiz = $niubiz;
    }

    public function createSession(Request $request)
    {
        $sessionResponse = $this->niubiz->generateSession();
        return response()->json($sessionResponse);
    }

    public function completePurchase(Request $request)
    {
        // Obtener el token de transacción y otros datos de la solicitud
        $transactionToken = $request->input('transactionToken');
        $orderNumber = $request->input('orderNumber');

        // Aquí puedes procesar la transacción, por ejemplo:
        // - Validar el token de transacción con Niubiz
        // - Actualizar el estado del pedido en la base de datos
        // - Notificar al usuario sobre el estado de su compra

        // Para este ejemplo, simplemente redirigimos a una página de éxito
        return redirect()->route('order.success')->with('status', 'Compra completada con éxito.');
    }
}

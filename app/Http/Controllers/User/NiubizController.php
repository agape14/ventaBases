<?php
namespace App\Http\Controllers\User;

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




}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;

class ComprobanteController extends Controller
{
    public function preparaRegistraComprobante($id,$bof)
    {
        $order = Order::where('order_id', $id)->first();

        $orderItems = OrderItem::where('order_id', $id)->get();
        $totalQuantity = $orderItems->sum('quantity');

        $paramCabecera = implode('|', [
            $order->tipo_comprobante,
            $order->order_date,
            $order->name,
            $order->email,
            $order->phone,
            $order->customer_id,
            $order->invoice_number,
            $order->address,
            $order->township_id,
            $order->tipo_persona,
            $order->grand_total,
            $totalQuantity
        ]);
        $result = $this->registrarComprobante($paramCabecera,$id,$bof);
        $response = json_decode($result->getContent(), true);
        if ($response['success']) {
            return redirect()->route('admin#order') ->with('registrosigiconfirmado', $response['message'].' Codigo: '.$response['codComprob']);
        } else {
            return back()->with('error', $response['message']);
        }
    }

    public function registrarComprobante($paramCabecera,$idorder,$boleta_o_factura)
    {
        DB::beginTransaction();
        try {
            $codComprob = DB::connection('sqlsrv')->table('t_comprobante')->max('codComprob') + 1;
            $codConcepto = 57;
            $canal = 'E';
            $nombreconcepto = DB::connection('sqlsrv')->table('t_concepto_ingreso')->where('codConcepto', $codConcepto)->value('nombre');
            $fechaRegistro = Carbon::now();
            $registradoPor = 'adelacruz';
            $cabeceraComprobante = explode('|', $paramCabecera);
            if (!empty($cabeceraComprobante[0])) {
                $tipComprobante = $cabeceraComprobante[0];
                $fecha = Carbon::createFromFormat('d/m/Y', $cabeceraComprobante[1])->format('Y-m-d');
                $nombres = $cabeceraComprobante[2];
                $apellidos = $cabeceraComprobante[2];
                $correo = trim($cabeceraComprobante[3]);
                $telefono = trim($cabeceraComprobante[4]);
                $idCliente = (int)$cabeceraComprobante[5];
                $idPedido = (int)$cabeceraComprobante[6];
                $hora = $fechaRegistro->format('H:i:s');
                $direccion = trim($cabeceraComprobante[7]);
                $iddistrito = $cabeceraComprobante[8];
                $razSoc = null;
                $tipopersona = trim($cabeceraComprobante[9]);
                $grantotal = trim($cabeceraComprobante[10]);
                $cantidad = trim($cabeceraComprobante[11]);
                $nrodocumento=0;
                $nroruc=null;
                $tipodocucliente=null;
                $codtipodocucliente=null;
                $mes = Carbon::parse($fecha)->month;
                if($tipopersona=='N'){
                    $personanatural=DB::table('personas_naturales')->where('customer_id', $idCliente)->first();
                    $nrodocumento=$personanatural->dni;
                    $tipodocucliente='DNI';
                    $codtipodocucliente='01';
                    if ($boleta_o_factura === 'F') {
                        $nroruc = $personanatural->ruc;
                    }
                }elseif($tipopersona=='J'){
                    $personajuridica=DB::table('personas_juridicas')->where('customer_id', $idCliente)->first();
                    $nrodocumento=$personajuridica->ruc;
                    $razSoc =$personajuridica->razon_social;
                    $tipodocucliente='RUC';
                    $nroruc=$nrodocumento;
                    $codtipodocucliente='06';
                }
                $personaExiste = DB::connection('sqlsrv')->table('t_persona')->where('codPersona', $nrodocumento)->exists();
                $tbltown=DB::table('townships')->where('township_id', $iddistrito)->first();
                $tblubigeo = DB::connection('sqlsrv')->table('t_ubigeo')->where('codUbi', $tbltown->codubi)->first();
                $ubigeo = $tblubigeo->codUbi;
                if (!$personaExiste) {
                    DB::connection('sqlsrv')->table('t_persona')->insert([
                        'codPersona' => $nrodocumento,
                        'tipoPersona' => $tipopersona,
                        'nombreCompleto' => $razSoc ?? ($nombres),
                        'tipoDoc' => $tipodocucliente,
                        'titulo' => null,'organizacion' => null,'area' => null,'cargo' => null,'usuario' => null,'clave' => null,
                        'email' => $correo,
                        'telefono' => '',
                        'movil' => $telefono,
                        'estado' => 'A',
                        'codRepresentante' => null,
                        'domicilio' => $direccion,
                        'ruc' => $nroruc,
                        'ubigeo' => $ubigeo,
                        'domicilioCompleto' => null,
                        'tipoUsuario' => 'E',
                        'codPerfil' => null,'sexo' => null,'paterno' => null,'materno' => null,'nombres' => null,
                        'registradoPor' => $registradoPor,
                        'fechaRegistro' => $fechaRegistro,
                        'modificadoPor' => null,'fechaModificado' => null,
                        'dist' => $tblubigeo->dist,
                        'prov' => $tblubigeo->prov,
                        'dpto' => $tblubigeo->dpto,
                        'tipoDocumento' => $codtipodocucliente
                    ]);
                }

                if ($boleta_o_factura === 'F') {
                    $serie = 'F001';
                    $documento = 'Factura';
                    $tipoCompr = '01';
                    if($tipopersona=='N'){
                        $tipodocucliente='RUC';
                        $codtipodocucliente='06';
                    }
                } elseif ($boleta_o_factura === 'B') {
                    $serie = 'B001';
                    $documento = 'Boleta';
                    $tipoCompr = '03';
                }
                $numero = DB::connection('sqlsrv')->table('t_comprobante')
                    ->where('tipo', $boleta_o_factura)
                    ->where('serie', $serie)
                    ->max('numero') + 1;

                if (is_null($numero)) {
                    $numero = 1;
                }
                $nroConCeros= str_pad($numero, 8, '0', STR_PAD_LEFT);
                $facturaElectronica = $tipoCompr . '-' . $serie . '-' . $nroConCeros . '.pdf' ;
                $codIngreso = DB::connection('sqlsrv')->table('t_ingreso_tesoreria')->max('codIngreso') + 1;

                $igv_rate = 0.18;
                $subtotal = (float)$grantotal / (1 + $igv_rate);
                $igv_value = (float)$grantotal - $subtotal;
                $subtotal = number_format($subtotal, 2, '.', '');
                $igv_value = number_format($igv_value, 2, '.', '');
                DB::connection('sqlsrv')->table('t_ingreso_tesoreria')->insert([
                    'item' => 1,
                    'codComprob' => $codComprob,
                    'codConcepto' => $codConcepto,
                    'fecha' => $fecha,
                    'canal' => $canal,
                    'detalle' => $nombreconcepto,
                    'importe' => (float)$grantotal,
                    'intereses' => 0.00,
                    'subtotal' => $subtotal,
                    'igv' => $igv_value ,
                    'total' => (float)$grantotal,
                    'estIngreso' => 'R',
                    'codPla' => null,
                    'codRefer' => $nrodocumento,
                    'serie' => $serie,
                    'numero' => $numero,
                    'documento' => $documento,
                    'pagoPendiente' => 'N',
                    'fechaPago' => $fecha,
                    'registradoPor' => $registradoPor,
                    'fechaRegistro' => $fechaRegistro,
                    'codIngreso' => $codIngreso
                ]);
                DB::connection('sqlsrv')->table('t_comprobante')->insert([
                    'serie' => $serie,
                    'numero' => $numero,
                    'tipo' => $tipComprobante,
                    'fechaEmision' => $fecha,
                    'tipoDoc' => $tipodocucliente,
                    'codPersona' => $nrodocumento,
                    'nombreCompletoSUNAT' => $razSoc ?? ($nombres),
                    'direccionSUNAT' => $direccion,
                    'ruc' => $nroruc,
                    'subtotal' => $subtotal,
                    'igv' => $igv_value ,
                    'total' => (float)$grantotal,
                    'emitido' => 'S',
                    'generado' => 'C',
                    'estComprob' => 'R',
                    'mes' => $mes,
                    'pagoPendiente' => 'N',
                    'origen' => '50',
                    'metodoPago' => 'T',
                    'facturaElectronica' => $facturaElectronica,
                    'tipoMaterial' => 'E',
                    'observaciones' => env('DETALLE_COMPROBANTE'),
                    'fechaPago' => $fecha,
                    'horaComprobante' => $hora,
                    'registradoPor' => $registradoPor,
                    'fechaRegistro' => $fechaRegistro,
                    'codComprob' => $codComprob,
                    'idPedido' => $idPedido,
                    'cantidad' => (int)$cantidad
                ]);

                Order::where('order_id',$idorder)->update([
                    'emitido'=>1,
                ]);
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Comprobante registrado con éxito', 'codComprob' => $codComprob]);
            }else{
                return response()->json(['success' => false, 'message' => 'El tipo de comprobante no puede estar vacío', 'codComprob' => 0]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error al registrar el comprobante', 'error' => $e->getMessage()]);
        }
    }
}

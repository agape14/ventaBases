<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        /*
        $orders =  Order::select('name', 'phone', 'email', 'address','order_date')
        ->where('status','pagado')
        ->get(); // Selecciona las columnas que necesitas
        */
        $orders = Order::select('orders.name', 'orders.phone', 'orders.email', 'orders.address','orders.order_date')
        ->where('status','=', 'pagado')
        ->whereHas('orderItem.product', function ($query) {
            $query->where('publish_status', 1);
        })
        ->with(['user', 'customer', 'orderItem.product' => function ($query) {
            $query->where('publish_status', 1); // Asegura cargar solo productos publicados
        }])
        ->orderby('order_id', 'desc')
        ->get();

        $ordersWithCounter = $orders->map(function($order, $key) {
            return [
                '#' => $key + 1, // Contador comienza desde 1
                'name' => $order->name,
                'phone' => $order->phone,
                'email' => $order->email,
                'address' => $order->address,
                'order_date' => $order->order_date,
            ];
        });

        return $ordersWithCounter;
    }

    public function headings(): array
    {
        return [
            '#',
            'NOMBRES COMPLETOS',
            'CELULAR',
            'CORREO',
            'DIRECCION',
            'FECHA',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply borders to all rows and columns
        $sheet->getStyle('A1:F'.$sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Apply bold font to the header row
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // Contador #
            'B' => 30, // NOMBRES COMPLETOS
            'C' => 15, // CELULAR
            'D' => 30, // CORREO
            'E' => 40, // DIRECCION
            'F' => 20, // FECHA
        ];
    }
}

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Invoice : {{ $order->invoice_number }}</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
    .font{
      font-size: 15px;
    }
    .thanks p {
        color: #02aab0;;;
        font-size: 16px;
        font-weight: normal;
        font-family: serif;
        margin-top: 20px;
    }
    strong{
        margin-bottom: 5px;
    }
</style>

</head>
<body>
@php
    $companyInfo = App\Models\CompanySetting::orderBy('id','desc')->first();
@endphp
  <table width="100%" style="background: #F7F7F7; padding:0 20px 0 20px;">
    <tr>
        <td valign="top">
          <!-- {{-- <img src="" alt="" width="150"/> --}} -->
          <h2 style="color: #02aab0; font-size: 26px;"><strong>{{ $companyInfo->company_name }}</strong></h2>
        </td>
        <td align="right">
            <div class="font" style="margin-top: 10px">
              <p>Website: www.{{$companyInfo->company_name}}.com </p>
              <p>Email: {{ $companyInfo->email }}</p>
              <p>Phone: {{ $companyInfo->phone_one }}</p>
              <p>Address: {{ $companyInfo->address }}</p>
          </div>
          </td>
    </tr>

  </table>


  <table width="100%" style="background:white; padding:2px;""></table>

  <table width="100%" style="background: #F7F7F7; padding:0 20 0 20px;" class="font">
    <tr>
        <td>
          <div class="font" style="">
            <p><strong>Name:</strong> {{$order->name}}</p>
            <p><strong>Email:</strong> {{$order->email}}</p>
            <p><strong>Phone:</strong> {{$order->phone}}</p>
            <p><strong>Address:</strong>{{$order->address}}</p>
         </div>
        </td>
        <td>
          <div class="font" align="right">
            <p><strong>Invoice Number:</strong>{{ $order->invoice_number }}</p>
            <p><strong>Order Date:</strong>{{ $order->order_date }}</p>
            <p><strong>Payment Method:</strong>{{ $order->payment_method }}</p>
            @if ($order->payment_method == 'cos')
                <p><strong>Payment Status:</strong>{{ $order->confirmed_date == null ? 'pending' : 'No Paid' }}</p>
            @else
                <p><strong>Payment Status:</strong>{{ $order->confirmed_date == null ? 'pending' : 'Paid' }}</p>
            @endif
         </div>
        </td>
    </tr>
  </table>
  <br/>
<h3>Ordered Items</h3>


  <table width="100%">
    <thead style="background-color: #02aab0; color:#FFFFFF;">
      <tr class="font">
        <th>Image</th>
        <th>Product Name</th>
        <th>Color</th>
        <th>Size</th>
        {{-- <th>Code</th> --}}
        <th>Quantity</th>
        <th>Unit Price </th>
        <th>Total </th>
      </tr>
    </thead>
    <tbody>

    @foreach ($orderItems as $item)
        <tr class="font">
            <td align="center">
                <img src="{{ public_path('uploads/products/'.$item->product->preview_image) }}" class="shadow-sm" alt="" srcset="" style="width: 40px; height: 40px">
            </td>
            <td align="center">{{ $item->product->name }}</td>
            <td align="center">
                {{ empty($item->color) ? '---' : $item->color->name}}
            </td>
            <td align="center">{{ empty($item->size) ? '---' : $item->size->name}}</td>
            {{-- <td align="center">product_code</td> --}}
            <td align="center">{{ $item->quantity }}</td>
            <td align="center">{{ $item->unit_price }} Ks</td>
            <td align="center">{{ $item->total_price }} Ks</td>
        </tr>
    @endforeach

    </tbody>
  </table>
  <br>
  <table width="100%" style=" padding:0 10px 0 10px;">
    <tr>
        <td align="right" >
            <h2><span style="color: #02aab0;">Subtotal:</span> {{ $order->sub_total }} Ks</h2>
            @if (!empty($order->coupon_id))
                <h2><span style="color: #02aab0;">Coupon Discount:</span> {{ $order->coupon_discount }} Ks</h2>
            @endif
            <hr>
            <h2><span style="color: #02aab0;">Grand Total:</span> {{ $order->grand_total }} Ks</h2>
            {{-- <h2><span style="color: green;">Full Payment PAID</h2> --}}
        </td>
    </tr>
  </table>
  <div class="mt-3 thanks">
    <p>Thanks For Buying Products..!!</p>
  </div>
</body>
</html>

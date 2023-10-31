<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>invoice</title>
    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
</head>
<body>
<div style="margin: 0 auto; padding: 10px">
    <table style="width:100%;">
        <tbody>
            <tr>
                <td colspan="11" class="dr-ltr text-right print-border-0" style="font-size: 20px; padding: 0px; background-color: whitesmoke; position: relative; border-left: none; border-right: none; border-top: none; border-bottom: 2px solid black;">
                    <table style="display: block;">
                        <tbody>
                        <tr>
                            <td style="text-align: right;">شماره سریال: {{ $invoice->invoice_no  }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;">تاریخ: {{ verta($invoice->created_at)->format('Y-m-d') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="12" class="text-center bold" style="font-size: 16px;padding: 0;">  صورتحساب فروش كالا و خدمات </td>
            </tr>
            <tr>
                <th colspan="12" class="text-center bold" style="font-size: 15px;padding: 0;"> مشخصات فروشنده </th>
            </tr>
            <tr>
                <td colspan="12" style="padding:0;">
                    <table style="width: 100%;">
                        <tbody>
                        <tr class="pd">
                            <td class="bd-0" colspan="4" style="white-space: break-spaces;">نام شخص حقیقی / حقوقی: </td>
                            <td class="bd-0" colspan="4">شماره اقتصادی: </td>
                            <td class="bd-0" colspan="4">شناسه / کد ملی: </td>
                        </tr>
                        <tr class="pd">
                            <td class="bd-0" colspan="4">شماره ثبت: </td>
                            <td class="bd-0" colspan="4">کدپستی: </td>
                            <td class="bd-0" colspan="4">شماره تلفن: </td>
                        </tr>
                        <tr class="pd">
                            <td class="bd-0" colspan="12" style="white-space: break-spaces;">نشانی: </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th colspan="12" class="text-center bold" style="font-size: 15px;padding: 0;"> مشخصات خریدار </th>
            </tr>
            <tr>
                <td colspan="12" style="padding:0px;">
                    <table style="width: 100%;">
                        <tbody>
                        <tr class="pd">
                            <td class="bd-0" colspan="4" style="white-space: break-spaces;">نام شخص حقیقی / حقوقی: </td>
                            <td class="bd-0" colspan="4">شماره اقتصادی: </td>
                            <td class="bd-0" colspan="4">شناسه / کد ملی: </td>
                        </tr>
                        <tr class="pd">
                            <td class="bd-0" colspan="4">شماره ثبت: </td>
                            <td class="bd-0" colspan="4">کدپستی: </td>
                            <td class="bd-0" colspan="4">شماره تلفن: </td>
                        </tr>
                        <tr class="pd">
                            <td class="bd-0" colspan="12" style="white-space: break-spaces;">نشانی: </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <th colspan="11" class="text-center bold" style="font-size: 15px;padding: 0;"> مشخصات كالا يا خدمات مورد معامله </th>
            </tr>
            <tr>
                <td colspan="11" style="padding:0;">
                    <table style="width: 100%; table-layout: fixed;">
                        <tbody id="tbody">
                        <tr class="rc">
                            <th class="fw-n bd-t0 bd-r0 text-center" style="width: 5%; white-space: break-spaces;">ردیف</th>
                            <th class="fw-n bd-t0 bd-r0 text-center" style="width: 5%; white-space: break-spaces;">کد کالا</th>
                            <th class="fw-n bd-t0" style="text-align: right; width: 20%; white-space: break-spaces;">شرح کالا یا خدمات</th>
                            <th class="fw-n bd-t0 text-center" style="width: 5%; white-space: break-spaces;">تعداد</th>
                            <th class="fw-n bd-t0 text-center" style="width: 10%; white-space: break-spaces;">مبلغ واحد (ریال)</th>
                            <th class="fw-n bd-t0 text-center" style="width: 10%; white-space: break-spaces;">مبلغ کل (ریال)</th>
                            <th class="fw-n bd-t0 text-center" style="width: 10%; white-space: break-spaces;">مبلغ تخفيف</th>
                            <th class="fw-n bd-t0 text-center" style="width: 10%; white-space: break-spaces;">مبلغ كل پس از <br>تخفيف (ريال)</th>
                            <th class="fw-n bd-t0 text-center" style="width: 10%; white-space: break-spaces;">جمع ماليات <br>و عوارض (ريال)</th>
                            <th class="fw-n bd-t0 bd-l0 text-center" style="width: 15%; white-space: break-spaces;">جمع مبلغ كل بعلاوه <br> جمع ماليات و عوارض (ريال)</th>
                        </tr>
                        @foreach($invoice->products as $product)
                        <tr class="rc">
                            <td class="dr-ltr text-center">{{ $loop->iteration  }}</td>
                            <td class="dr-ltr text-center">{{ $product->sku }}</td>
                            <td class="text-right" style="white-space: break-spaces;">{{ $product->title }}</td>
                            <td class="dr-ltr text-center">{{ $product->qty }}</td>
                            <td class="dr-ltr text-center">{{ number_format($product->price) }}</td>
                            <td class="dr-ltr text-center">{{ number_format($product->qty * $product->price) }}</td>
                            <td class="dr-ltr text-center">{{ number_format($product->discount) }}</td>
                            <td class="dr-ltr text-center">{{ number_format($product->qty * $product->price - $product->discount) }}</td>
                            <td class="dr-ltr text-center">{{ number_format(($product->qty * $product->price - $product->discount) * ($product->tax / 100)) }}</td>
                            <td class="dr-ltr text-center bd-l0">{{ number_format($product->total) }}</td>
                        </tr>
                        @endforeach
                        <tr class="rc">
                            <td colspan="5" class="text-left"><b>جمع کل</b></td>
                            <td class="dr-ltr text-center">{{ number_format($invoice->amount) }}</td>
                            <td class="dr-ltr text-center">{{ number_format($invoice->discount) }}</td>
                            <td class="dr-ltr text-center">{{ number_format($invoice->amount - $product->discount) }}</td>
                            <td class="dr-ltr text-center">{{ number_format($invoice->tax) }}</td>
                            <td class="dr-ltr text-center bd-l0" style="background-color:inherit">{{ number_format($invoice->total) }}</td>
                        </tr>
                        <tr class="rc">
                            <td class="bd-b0 bd-r0 text-left" colspan="8"><b>مبلغ قابل پرداخت</b></td>
                            <td class="bd-b0 bd-l0 text-center">{{ number_format($invoice->total) }} ریال </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="11" style="padding: 0; position: relative;">
                    <table style="position: absolute; top: 20px; width: 100%;">
                        <tbody>
                        <tr>
                            <td style="padding: 10px;position: relative">
                                <img src="#" alt="" style="position: absolute; right: 150px; top:0px; bottom: 0px; width: 170px;">
                                مهر و امضاء فروشنده
                            </td>
                            <td style="padding: 10px;">مهر و امضا خریدار</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>

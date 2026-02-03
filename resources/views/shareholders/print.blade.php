<style>
@media print {
    body {
        font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', 'Courier New', monospace;
        font-size: 9pt;
        margin: 0;
        padding: 10px;
        direction: rtl;
        background: #fff;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .page-break {
        page-break-after: always;
    }

.receipt::before {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: url('{{ asset("image/logo.jpeg") }}') no-repeat center center;
    background-size: 60% auto;
    opacity: 0.y5; /* Lower = more transparent */
    z-index: 0;
}

.receipt {
    position: relative;
    z-index: 1;
    background: none !important; /* prevent double background */
}

}

.receipt-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    width: 100%;
}

.receipt {
    width: 48%;
    height: auto;
    margin-bottom: 10px;
    padding: 10px;
    box-sizing: border-box;
    border: 1px dashed #000;
    position: relative;
    direction: rtl;
}
.receipt::before {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: url('{{ asset("image/logo.jpeg") }}') no-repeat center center;
    background-size: 60% auto;
    opacity: 0.5; /* Watermark effect */
    z-index: 0;
}

.receipt-header {
    text-align: center;
    font-weight: bold;
     font-size: 20px;
    margin-bottom: 1px;
    font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', sans-serif;
}

.line {
    display: flex;
    justify-content: space-between;
    margin: 2px 0;
    border-bottom: 1px solid #000; /* Black border */
    padding: 4px 8px;        /* Optional: space inside the border */
    border-radius: 4px;      /* Optional: rounded corners */
}

.info-row {
  margin-top:   10px !important;
   margin-bottom:25px !important;
  display: flex;
  justify-content: space-between;
  font-weight: bold;
  font-size: 20px;
  direction: rtl;
  gap: 1rem; /* Optional: spacing between items */
  flex-wrap: nowrap; /* Remove wrapping to keep in one line */
}

.label {
    font-weight: bold;
    font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', sans-serif;
      font-size: 20px;
}

.value {
     font-weight: bold;
    min-width: 50px;
    text-align: left;
    font-family: 'Arial', sans-serif;
    font-size:20px;     
}
</style>

<div class="container d-print-block receipt-container">
    @foreach($shareholders as $index => $shareholder)
        @php
                        $animal = $shareholder->animal;
                        $purchase_price = $animal->purchase_price ?? 0;
                        $writing = $animal->writing_cost ?? 0;
                        $transport = $animal->transportation_cost ?? 0;
                        $fodder = $animal->fodder_cost ?? 0;
                        $butcher = $animal->butcher_cost ?? 0;
                        $misc = $animal->miscellaneous_cost ?? 0;

                        $totalAnimalCost = $purchase_price + $writing + $transport + $fodder + $butcher + $misc;
                        $sharePrice = $totalAnimalCost / 7;
                        $expectedAmount = $shareholder->sharecount * $sharePrice;
                        $difference = $expectedAmount - $shareholder->amount_submit;

                        $baqayaDena = $difference > 0 ? number_format($difference, 2) : '';
                        $baqayaLena = $difference < 0 ? number_format(abs($difference), 2) : '';
                    @endphp

        <div class="receipt">
            <div class="receipt-header text-center">
                <img src="{{ asset('image/jamia3.png') }}" alt="مدرسہ محمدیہ دارالقرآن" class="img-fluid" style="max-width: 100%; height: auto;">
            </div>
            <div class="info-row" dir="rtl">
                 <div>نام: {{ $shareholder->name }}</div>
                <div>رسید نمبر: {{ $shareholder->receipt_no }}</div>
               <div>جانور :{{ $animal?->animal_no ?? '-' }}</div>
                 <div class="line"><span class="label">جانور کی قیمت:</span><span class="value">{{ number_format($animal->purchase_price ?? 0, 2) }}</span></div>


            </div>
                <div class="line"><span class="label">جمع رقم:</span><span class="value">{{ number_format($shareholder->amount_submit, 2) }}</span></div>
`               <div class="line"><span class="label">حصہ تعداد:</span><span class="value">{{ $shareholder->sharecount }}</span></div>
                
                <div class="line"><span class="label">قیمت خریدحصہ:</span>
                <span class="value">{{ number_format((($animal->purchase_price ?? 0) / 7) * $shareholder->sharecount, 2) }}</span>
                </div>
                <div class="line"><span class="label">منڈی لکھائی + کرایہ:</span>
                <span class="value">{{ number_format(((($animal->writing_cost ?? 0) + ($animal->transportation_cost ?? 0)) / 7) * $shareholder->sharecount, 2) }}</span>
                </div>
                <div class="line"><span class="label">چارہ:</span>
                <span class="value">{{ number_format((($animal->fodder_cost ?? 0) / 7) * $shareholder->sharecount, 2) }}</span>
                </div>
                <div class="line"><span class="label">اجرت قصائی:</span>
                <span class="value">{{ number_format((($animal->butcher_cost ?? 0) / 7) * $shareholder->sharecount, 2) }}</span>
                </div>
                <div class="line"><span class="label">کیٹرنگ اور شاپنگ بیگ:</span>
                <span class="value">{{ number_format((($animal->miscellaneous_cost ?? 0) / 7) * $shareholder->sharecount, 2) }}</span>
                </div>

           
            
            
            <div class="line"><span class="label">فی حصہ:</span><span class="value">{{ number_format($sharePrice, 2) }}</span></div>
            <div class="line"><span class="label"> کل حصہ:</span><span class="value">{{ number_format($expectedAmount, 2) }}</span></div>
            <div class="line"><span class="label text-danger">رقم بقایا:</span><span class="value">{{ $baqayaDena }}</span></div>
            <div class="line"><span class="label text-success">رقم واپسی:</span><span class="value">{{ $baqayaLena }}</span></div>
            
            <div class="" style="text-align: center;">
            <p style="margin: 0; font-size:15px ">
                Street 56b A-Block 
                New City Phase-2, Wah Cantt<br>
                0303-5077678    
            </p>
            </div>

            

            
        </div>

        @if(($index + 1) % 4 == 0)
            <div class="page-break"></div>
        @endif
    @endforeach
</div>

<div class="text-center d-print-none">
    <button onclick="window.print()" class="btn btn-primary">🖨️ پرنٹ کریں</button>
</div>

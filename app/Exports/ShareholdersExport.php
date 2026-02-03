<?php

namespace App\Exports;

use App\Models\Shareholder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShareholdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Shareholder::select('id', 'name', 'address', 'receipt_no', 'mobile', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Address',
            'Receipt No',
            'Mobile',
            'Created At',
        ];
    }
}

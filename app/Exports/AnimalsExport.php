namespace App\Exports;

use App\Models\Animal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnimalsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Animal::select('animal_no', 'share_count', 'purchase_price', 'writing_cost', 'transportation_cost', 'fodder_cost', 'miscellaneous_cost')->get();
    }

    public function headings(): array
    {
        return [
            'جانور نمبر',
            'حصے',
            'قیمت خرید',
            'منڈی لکھائی',
            'کرایہ',
            'چارہ',
            'دیگر'
        ];
    }
}

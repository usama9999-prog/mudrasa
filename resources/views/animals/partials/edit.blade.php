
<div class="modal fade" id="editAnimalModal-{{ $animal->id }}" tabindex="-1" aria-labelledby="editAnimalModalLabel-{{ $animal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="editAnimalModalLabel-{{ $animal->id }}">جانور ترمیم کریں</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بند کریں"></button>
            </div>
            <div class="modal-body">
                <form id="editAnimalForm-{{ $animal->id }}" method="POST" action="{{ route('animals.update', $animal->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $animal->id }}">
                    <div class="row">
                        @foreach ([
                            ['animal_no', 'جانور نمبر', $animal->animal_no],
                            ['purchase_price', 'قیمت خرید', $animal->purchase_price],
                            ['fodder_cost', 'چارہ', $animal->fodder_cost],
                            ['transportation_cost', 'کرایہ', $animal->transportation_cost],
                            ['butcher_cost', 'قصائی اجرت', $animal->butcher_cost],
                            ['writing_cost', 'منڈی لکھائی', $animal->writing_cost],
                            ['miscellaneous_cost', 'دیگر اخراجات', $animal->miscellaneous_cost]
                            ] as [$name, $label, $value])
                            <div class="col-md-4 mb-3">
                                <label class="form-label">{{ $label }}</label>
                                <input
                                    type="{{ $name === 'animal_no' ? 'text' : 'number' }}"
                                    {{ $name !== 'animal_no' ? 'step=0.01 min=0' : '' }}
                                    name="{{ $name }}"
                                    class="form-control"
                                    value="{{ $value }}"
                                >
                                <div class="text-danger small" id="error-edit-{{ $name }}-{{ $animal->id }}"></div>
                            </div>
                        @endforeach

                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">اپڈیٹ کریں</button>
                </form>
            </div>
        </div>
    </div>
</div>

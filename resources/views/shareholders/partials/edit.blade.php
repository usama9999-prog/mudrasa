<div class="modal fade" id="editShareholderModal-{{ $shareholder->id }}" tabindex="-1" aria-labelledby="editShareholderModalLabel-{{ $shareholder->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" dir="rtl" style="font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', serif;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="editShareholderModalLabel-{{ $shareholder->id }}">حصہ دار میں ترمیم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بند کریں"></button>
            </div>
            <div class="modal-body">
                <form class="editShareholderForm" action="{{ route('shareholders.update', $shareholder->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نام *</label>
                            <input type="text" class="form-control" name="name" value="{{ $shareholder->name }}" required>
                            <div class="text-danger small error-edit-name"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">موبائل نمبر *</label>
                            <input type="text" class="form-control" name="mobile" value="{{ $shareholder->mobile }}" required>
                            <div class="text-danger small error-edit-mobile"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رسید نمبر *</label>
                            <input type="text" class="form-control" name="receipt_no" value="{{ $shareholder->receipt_no }}" required>
                            <div class="text-danger small error-edit-receipt_no"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">پتہ</label>
                            <input type="text" class="form-control" name="address" value="{{ $shareholder->address }}">
                            <div class="text-danger small error-edit-address"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">جانور نمبر *</label>
                            <select class="form-select" name="animal_id" required>
                                <option value="">منتخب کریں</option>
                                @foreach($animals as $animal)
                                    <option value="{{ $animal->id }}" {{ $animal->id == $shareholder->animal_id ? 'selected' : '' }}>
                                        {{ $animal->animal_no }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-danger small error-edit-animal_id"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">حصہ تعداد *</label>
                            <input type="number" class="form-control" name="sharecount" value="{{ $shareholder->sharecount }}" required>
                            <div class="text-danger small error-edit-sharecount"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">جمع رقم *</label>
                            <input type="number" class="form-control" name="amount_submit" value="{{ $shareholder->amount_submit }}" required>
                            <div class="text-danger small error-edit-amount_submit"></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3">اپڈیٹ کریں</button>
                </form>
            </div>
        </div>
    </div>
</div>

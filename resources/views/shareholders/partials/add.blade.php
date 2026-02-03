    <div class="modal fade" id="addShareholderModal" tabindex="-1" aria-labelledby="addShareholderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" dir="rtl" style="font-family: 'Noto Nastaliq Urdu', 'Jameel Noori Nastaleeq', serif;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">نیا حصہ دار شامل کریں</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بند کریں"></button>
                </div>
                <div class="modal-body">
                  <form id="addShareholderForm" method="POST" action="{{ route('shareholders.store') }}">

                        @csrf
                        <input type="hidden" id="edit-id" name="id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">نام *</label>
                                <input type="text" class="form-control" name="name" required>
                                <div class="text-danger small" id="error-name"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">موبائل نمبر *</label>
                                <input type="text" class="form-control" name="mobile" required>
                                <div class="text-danger small" id="error-mobile"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">رسید نمبر *</label>
                                <input type="text" class="form-control" name="receipt_no" required>
                                <div class="text-danger small" id="error-receipt_no"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">پتہ</label>
                                <input type="text" class="form-control" name="address">
                                <div class="text-danger small" id="error-address"></div>
                            </div>
                            <select class="form-select" name="animal_id" required>
                                <option value="">منتخب کریں</option>
                                @foreach($animals as $animal)
                                    @if($animal->shareholders->sum('sharecount') < 7)
                                        <option value="{{ $animal->id }}">
                                            {{ $animal->animal_no }} (حصے: {{ $animal->shareholders->sum('sharecount') }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="text-danger small" id="error-animal_id"></div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">حصہ تعداد *</label>
                                <input type="number" class="form-control" name="sharecount" required>
                                <div class="text-danger small" id="error-sharecount"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">جمع رقم *</label>
                                <input type="number" class="form-control" name="amount_submit" required>
                                <div class="text-danger small" id="error-amount_submit"></div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-3">محفوظ کریں</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Add Animal Modal -->
<div class="modal fade" id="addAnimalModal" tabindex="-1" aria-labelledby="addAnimalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addAnimalModalLabel">نیا جانور شامل کریں</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بند کریں"></button>
            </div>
            <div class="modal-body">
                <form id="addAnimalForm" method="POST" action="{{ route('animals.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">جانور نمبر</label>
                            <input type="text" name="animal_no" class="form-control" required>
                            <div class="text-danger small" id="error-animal_no"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">قیمت خرید</label>
                            <input type="number" step="0.01" min="0" name="purchase_price" class="form-control" required>
                            <div class="text-danger small" id="error-purchase_price"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">چارہ</label>
                            <input type="number" step="0.01" min="0" name="fodder_cost" class="form-control">
                            <div class="text-danger small" id="error-fodder_cost"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">کرایہ</label>
                            <input type="number" step="0.01" min="0" name="transportation_cost" class="form-control">
                            <div class="text-danger small" id="error-transportation_cost"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">قصائی اجرت</label>
                            <input type="number" step="0.01" min="0" name="butcher_cost" class="form-control">
                            <div class="text-danger small" id="error-butcher_cost"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">منڈی لکھائی</label>
                            <input type="number" step="0.01" min="0" name="writing_cost" class="form-control">
                            <div class="text-danger small" id="error-writing_cost"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">دیگر اخراجات</label>
                            <input type="number" step="0.01" min="0" name="miscellaneous_cost" class="form-control">
                            <div class="text-danger small" id="error-miscellaneous_cost"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">محفوظ کریں</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editExamModal{{ $student->id }}" tabindex="-1"
     aria-labelledby="editExamModalLabel{{ $student->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3">

            <!-- Header -->
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="editExamModalLabel{{ $student->id }}">
                    امتحانی تفصیلات میں ترمیم کریں
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <form id="editExamForm{{ $student->id }}"
                      method="POST"
                      action="{{ route('exams.store') }}">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">

                    <div class="row g-3">

                        <!-- Zabt (50) -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ضبط (50)</label>
                            <input type="number" name="zabt"
                                   class="form-control text-center"
                                   value="{{ $student->exam?->zabt }}"
                                   min="0" max="50" required>
                        </div>

                        <!-- Tajweed (20) -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">تجوید (20)</label>
                            <input type="number" name="tajweed"
                                   class="form-control text-center"
                                   value="{{ $student->exam?->tajweed }}"
                                   min="0" max="20" required>
                        </div>

                        <!-- Lehja (10) -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">لہجہ (10)</label>
                            <input type="number" name="lehja"
                                   class="form-control text-center"
                                   value="{{ $student->exam?->lehja }}"
                                   min="0" max="10" required>
                        </div>

                        <!-- Tarbiti Nisab (20) -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">تربیتی نصاب (20)</label>
                            <input type="number" name="tarbiti_nisab"
                                   class="form-control text-center"
                                   value="{{ $student->exam?->tarbiti_nisab }}"
                                   min="0" max="20" required>
                        </div>

                        <!-- Guzashta Jaiza (10) -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">گذشتہ جائزہ (10)</label>
                            <input type="number" name="guzashta_jaiza"
                                   class="form-control text-center"
                                   value="{{ $student->exam?->guzashta_jaiza }}"
                                   min="0" max="10" required>
                        </div>

                        <!-- Hazri (10) -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">حاضری (10)</label>
                            <input type="number" name="hazri"
                                   class="form-control text-center"
                                   value="{{ $student->exam?->hazri }}"
                                   min="0" max="10" required>
                        </div>

                        <!-- Tarjuma (30) -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ترجمہ (30)</label>
                            <input type="number" name="tarjuma"
                                   class="form-control text-center"
                                   value="{{ $student->exam?->tarjuma }}"
                                   min="0" max="30" required>
                        </div>

                    </div>

                    <!-- Save Button -->
                    <div class="mt-4">
                        <button type="submit"
                                class="btn btn-success w-100 py-2 fw-bold">
                            💾 تبدیلی محفوظ کریں
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const examRules = {
        zabt: { max: 50, msg: "ضبط کے نمبر 50 سے زیادہ نہیں ہو سکتے" },
        tajweed: { max: 20, msg: "تجوید کے نمبر 20 سے زیادہ نہیں ہو سکتے" },
        lehja: { max: 10, msg: "لہجہ کے نمبر 10 سے زیادہ نہیں ہو سکتے" },
        tarbiti_nisab: { max: 20, msg: "تربیتی نصاب کے نمبر 20 سے زیادہ نہیں ہو سکتے" },
        guzashta_jaiza: { max: 10, msg: "گذشتہ جائزہ کے نمبر 10 سے زیادہ نہیں ہو سکتے" },
        hazri: { max: 10, msg: "حاضری کے نمبر 10 سے زیادہ نہیں ہو سکتے" },
        tarjuma: { max: 30, msg: "ترجمہ کے نمبر 30 سے زیادہ نہیں ہو سکتے" }
    };

    Object.keys(examRules).forEach(field => {
        document.querySelectorAll(`input[name="${field}"]`).forEach(input => {
            input.addEventListener('input', function () {
                const rule = examRules[field];
                const val = parseInt(this.value);

                if (isNaN(val) || val < 0 || val > rule.max) {
                    toggleValidation(this, field + "-error", false, rule.msg);
                } else {
                    toggleValidation(this, field + "-error", true);
                }
            });
        });
    });

    function toggleValidation(field, errorId, isValid, message = '') {
        let errorDiv = document.getElementById(errorId);

        if (!errorDiv) {
            errorDiv = document.createElement("div");
            errorDiv.className = "invalid-feedback";
            errorDiv.id = errorId;
            field.parentNode.appendChild(errorDiv);
        }

        if (!isValid) {
            errorDiv.textContent = message;
            field.classList.add("is-invalid");
        } else {
            errorDiv.textContent = "";
            field.classList.remove("is-invalid");
        }
    }
});
</script>

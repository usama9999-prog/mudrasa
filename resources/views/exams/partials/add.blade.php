<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addStudentModalLabel">نیا طالبعلم شامل کریں</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بند کریں"></button>
            </div>
            <div class="modal-body">
                <form id="addStudentForm" method="POST" action="{{ route('students.store') }}">
                    @csrf
                    <div class="row">
                        <!-- Roll Number -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">رول نمبر</label>
                            <input type="text" name="roll_no" id="roll_no" class="form-control" required>
                            <div class="invalid-feedback" id="error-roll_no"></div>
                        </div>

                        <!-- Name -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">نام</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                            <div class="invalid-feedback" id="error-name"></div>
                        </div>

                        <!-- Father Name -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">والد کا نام</label>
                            <input type="text" name="father_name" id="father_name" class="form-control" required>
                            <div class="invalid-feedback" id="error-father_name"></div>
                        </div>

                        <!-- Class -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">کلاس</label>
                            <input type="text" name="student_class" id="student_class" class="form-control" required>
                            <div class="invalid-feedback" id="error-student_class"></div>
                        </div>

                        <!-- Miqdaar-e-Khawandgi -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">مقدارِ خواندگی (مثال: 1-10)</label>
                            <input type="text" name="miqdaar_e_khawandgi" id="miqdaar_e_khawandgi" class="form-control" required>
                            <div class="invalid-feedback" id="error-miqdaar_e_khawandgi"></div>
                        </div>

                        <!-- Kul Parah -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">کل پارہ</label>
                            <input type="text" name="kul_parah" id="kul_parah" class="form-control" readonly>
                            <div class="invalid-feedback" id="error-kul_parah"></div>
                        </div>
                        <!-- Description -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">تفصیل</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="طالبعلم کے بارے میں تفصیل درج کریں"></textarea>
                            <div class="invalid-feedback" id="error-description"></div>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="loadingSpinner"></span>
                        <span id="submitText">محفوظ کریں</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const rollNoInput = document.getElementById('roll_no');
    const nameInput = document.getElementById('name');
    const fatherNameInput = document.getElementById('father_name');
    const studentClassInput = document.getElementById('student_class');
    const miqdaarInput = document.getElementById('miqdaar_e_khawandgi');
    const kulParahField = document.getElementById('kul_parah');

    // Roll No validation
    rollNoInput.addEventListener('input', function () {
        const value = this.value.trim();
        const isValid = /^#\.\d+$/.test(value);
        toggleValidation(this, 'error-roll_no', isValid, 'رول نمبر "#." سے شروع ہونا چاہیے (مثال: #.121)');
    });

    // Name only Urdu letters
    nameInput.addEventListener('input', function () {
        const isValid = /^[\u0600-\u06FF\s]+$/.test(this.value.trim());
        toggleValidation(this, 'error-name', isValid, 'نام میں صرف حروف ہونے چاہئیں');
    });

    // Father name only Urdu letters
    fatherNameInput.addEventListener('input', function () {
        const isValid = /^[\u0600-\u06FF\s]+$/.test(this.value.trim());
        toggleValidation(this, 'error-father_name', isValid, 'والد کے نام میں صرف حروف ہونے چاہئیں');
    });

    // Class digits only
    studentClassInput.addEventListener('input', function () {
        const isValid = /^\d+$/.test(this.value.trim());
        toggleValidation(this, 'error-student_class', isValid, 'کلاس میں صرف اعداد ہونے چاہئیں');
    });

    // Miqdaar-e-Khawandgi validation
    miqdaarInput.addEventListener('input', function () {
        const input = this.value.trim();
        const rangeMatch = /^(\d+)\s*-\s*(\d+)$/.exec(input);

        if (rangeMatch) {
            const start = parseInt(rangeMatch[1]);
            const end = parseInt(rangeMatch[2]);
            const total = end - start + 1;

            if (end >= start) {
                if (start >= 1 && end <= 30) {
                    kulParahField.value = total;
                    toggleValidation(this, 'error-miqdaar_e_khawandgi', true);
                } else {
                    kulParahField.value = '';
                    toggleValidation(this, 'error-miqdaar_e_khawandgi', false, 'پاروں کی حد 1 سے 30 کے درمیان ہونی چاہیے');
                }
            } else {
                kulParahField.value = '';
                toggleValidation(this, 'error-miqdaar_e_khawandgi', false, 'اختتامی پارہ ابتدائی پارہ سے بڑا ہونا چاہیے');
            }
        } else {
            kulParahField.value = '';
            toggleValidation(this, 'error-miqdaar_e_khawandgi', false, 'درست فارمیٹ: شروع-اختتام (مثال: 1-10)');
        }
    });
    const descriptionInput = document.getElementById('description');

    // Description validation (Optional: max 200 characters)
    descriptionInput.addEventListener('input', function () {
        const isValid = this.value.length <= 200;
        toggleValidation(this, 'error-description', isValid, 'تفصیل 200 حروف سے زیادہ نہیں ہونی چاہیے');
    });
    // Show loading spinner on form submit
    const addStudentForm = document.getElementById('addStudentForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const submitText = document.getElementById('submitText');

    addStudentForm.addEventListener('submit', function () {
        submitBtn.disabled = true;
        loadingSpinner.classList.remove('d-none');
        submitText.textContent = 'محفوظ ہو رہا ہے...';
    });

    // Utility function for validation
    function toggleValidation(field, errorId, isValid, message = '') {
        const errorDiv = document.getElementById(errorId);
        if (!isValid) {
            errorDiv.textContent = message;
            field.classList.add('is-invalid');
        } else {
            errorDiv.textContent = '';
            field.classList.remove('is-invalid');
        }
    }
</script>

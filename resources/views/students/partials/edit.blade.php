<div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $student->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $student->id }}">طلب علم میں ترمیم کریں</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بند کریں"></button>
            </div>
            <div class="modal-body">
                <form id="editStudentForm{{ $student->id }}" method="POST" action="{{ route('students.update', $student->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Roll Number -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">رول نمبر</label>
                            <input type="text" name="roll_no" id="roll_no_{{ $student->id }}" class="form-control" value="{{ $student->roll_no }}" required>
                            <div class="invalid-feedback" id="error-roll_no-{{ $student->id }}"></div>
                        </div>

                        <!-- Name -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">نام</label>
                            <input type="text" name="name" id="name_{{ $student->id }}" class="form-control" value="{{ $student->name }}" required>
                            <div class="invalid-feedback" id="error-name-{{ $student->id }}"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">کلاس</label>
                            <input type="text" name="class" id="class_{{ $student->id }}" class="form-control" value="{{ $student->class }}" required>
                            <div class="invalid-feedback" id="error-name-{{ $student->id }}"></div>
                        </div>
                        <!-- Father Name -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">والد کا نام</label>
                            <input type="text" name="father_name" id="father_name_{{ $student->id }}" class="form-control" value="{{ $student->father_name }}" required>
                            <div class="invalid-feedback" id="error-father_name-{{ $student->id }}"></div>
                        </div>

                        <!-- Miqdaar-e-Khawandgi -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">مقدارِ خواندگی (مثال: 1-10)</label>
                            <input type="text" name="miqdaar_e_khawandgi" id="miqdaar_e_khawandgi_{{ $student->id }}" class="form-control" value="{{ $student->miqdar_e_khundgi }}" required>
                            <div class="invalid-feedback" id="error-miqdaar_e_khawandgi-{{ $student->id }}"></div>
                        </div>

                        <!-- Kul Parah -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">کل پارہ</label>
                            <input type="text" name="kul_parah" id="kul_parah_{{ $student->id }}" class="form-control" value="{{ $student->kul_para }}" readonly>
                            <div class="invalid-feedback" id="error-kul_parah-{{ $student->id }}"></div>
                        </div>
                        <!-- Tarbiti Nisab Khuwandi -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">تربیتی نصاب خواندگی</label>
                            <input type="text" 
                                name="tarbiti_nisab_khuangi" 
                                id="tarbiti_nisab_khuangi_{{ $student->id }}" 
                                class="form-control" 
                                value="{{ $student->tarbiti_nisab_khuangi }}">
                            <div class="invalid-feedback" id="error-tarbiti_nisab_khuangi-{{ $student->id }}"></div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">تفصیل</label>
                            <textarea name="description" id="description_{{ $student->id }}" class="form-control" rows="3" placeholder="طالبعلم کے بارے میں تفصیل درج کریں">{{ $student->description }}</textarea>
                            <div class="invalid-feedback" id="error-description-{{ $student->id }}"></div>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-success w-100 mt-3">تبدیلی محفوظ کریں</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('[id^="roll_no_"]').forEach(input => {
            input.addEventListener('input', function () {
                const id = this.id.split("_")[2];
                const isValid = /^#\.\d+$/.test(this.value.trim());
                toggleValidation(this, `error-roll_no-${id}`, isValid, 'رول نمبر "#." سے شروع ہونا چاہیے (مثال: #.121)');
            });
        });

        document.querySelectorAll('[id^="name_"]').forEach(input => {
            input.addEventListener('input', function () {
                const id = this.id.split("_")[1];
                const isValid = /^[\u0600-\u06FF\s]+$/.test(this.value.trim());
                toggleValidation(this, `error-name-${id}`, isValid, 'نام میں صرف حروف ہونے چاہئیں');
            });
        });

        document.querySelectorAll('[id^="father_name_"]').forEach(input => {
            input.addEventListener('input', function () {
                const id = this.id.split("_")[2];
                const isValid = /^[\u0600-\u06FF\s]+$/.test(this.value.trim());
                toggleValidation(this, `error-father_name-${id}`, isValid, 'والد کے نام میں صرف حروف ہونے چاہئیں');
            });
        });
        const descriptionInputEdit = document.querySelectorAll('[id^="description_"]');

        descriptionInputEdit.forEach(input => {
            input.addEventListener('input', function () {
                const isValid = this.value.length <= 300;
                const errorId = 'error-description-' + this.id.split('_')[1];
                toggleValidation(this, errorId, isValid, 'تفصیل 300 حروف سے زیادہ نہیں ہونی چاہیے');
            });
        });
        document.querySelectorAll('[id^="miqdaar_e_khawandgi_"]').forEach(input => {
            input.addEventListener('input', function () {
                const id = this.id.split("_")[3];
                const rangeMatch = /^(\d+)\s*-\s*(\d+)$/.exec(this.value.trim());
                const kulParahField = document.getElementById(`kul_parah_${id}`);

                if (rangeMatch) {
                    const start = parseInt(rangeMatch[1]);
                    const end = parseInt(rangeMatch[2]);
                    const total = end - start + 1;

                    if (end >= start && start >= 1 && end <= 30) {
                        kulParahField.value = total;
                        toggleValidation(this, `error-miqdaar_e_khawandgi-${id}`, true);
                    } else {
                        kulParahField.value = '';
                        toggleValidation(this, `error-miqdaar_e_khawandgi-${id}`, false, 'پاروں کی حد 1 سے 30 کے درمیان ہونی چاہیے');
                    }
                } else {
                    kulParahField.value = '';
                    toggleValidation(this, `error-miqdaar_e_khawandgi-${id}`, false, 'درست فارمیٹ: شروع-اختتام (مثال: 1-10)');
                }
            });
        });

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
    });
</script>

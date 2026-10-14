<div class="employee-modal" id="employeeModal">
    <div class="employee-modal-overlay" onclick="closeEmployeeModal()"></div>

    <div class="employee-modal-card">
        <!-- Header -->
        <div class="employee-modal-header">
            <div>
                <h2>Yangi hodim</h2>
                <p>Hodim ma'lumotlarini kiriting</p>
            </div>

            <button type="button" class="modal-close" onclick="closeEmployeeModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.employees.store') }}">
            @csrf
            <div class="employee-form-group">
                <label> Ism </label>

                <input type="text" name="name" placeholder="Hodim ismini kiriting" required />
            </div>

            <div class="employee-form-group">
                <label> Username </label>

                <input type="text" name="username" placeholder="Username kiriting" required />
            </div>

            <div class="employee-form-group">
                <label> Parol </label>

                <input type="text" name="password" placeholder="Parol kiriting" required />
            </div>

            <!-- Departments -->
            <div class="employee-form-group">
                <label> Qaysi bo‘limlarda ishlaydi? </label>

                <div class="department-checkboxes">
                    @foreach($departments as $department)

                    <label class="department-checkbox">

                        <input
                        type="checkbox"
                        name="departments[]"
                        value="{{ $department->id }}"
                        {{ in_array(
                        $department->id,
                        old('departments', [])
                        ) ? 'checked' : '' }}
                        >

                        <span class="checkbox-custom">
                            <i class="fa-solid fa-check"></i>
                        </span>

                        <span class="department-checkbox-name">
                            {{ $department->name }}
                        </span>

                    </label>

                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="employee-modal-actions">
                <button type="button" class="modal-cancel" onclick="closeEmployeeModal()">Bekor qilish</button>

                <button type="submit" class="modal-submit">
                    <i class="fa-solid fa-user-plus"></i>
                    Hodim qo‘shish
                </button>
            </div>
        </form>
    </div>
</div>
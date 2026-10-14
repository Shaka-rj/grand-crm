<div class="employee-modal" id="editEmployeeModal">
    <div class="employee-modal-overlay" onclick="closeEditEmployeeModal()"></div>

    <div class="employee-modal-card">
        <!-- Header -->
        <div class="employee-modal-header">
            <div>
                <h2>Hodimni tahrirlash</h2>
                <p>Hodim ma'lumotlarini o‘zgartirish</p>
            </div>

            <button type="button" class="modal-close" onclick="closeEditEmployeeModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Form -->
        <form method="POST" id="editEmployeeForm" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="employee-form-group">
                <label>Ism</label>

                <input
                type="text"
                name="name"
                id="editName"
                required />
            </div>

            <div class="employee-form-group">
                <label>Username</label>

                <input
                type="text"
                name="username"
                id="editUsername"
                required
                >
            </div>

            <div class="employee-form-group">
                <label>Yangi parol</label>

                <input
                type="text"
                name="password"
                placeholder="O‘zgartirmasangiz bo‘sh qoldiring"
                >

                <small class="password-hint"> Parolni o‘zgartirish shart emas. </small>
            </div>

            <!-- Departments -->
            <div class="employee-form-group">

                <label>Qaysi bo‘limlarda ishlaydi?</label>

                <div class="department-checkboxes">

                    @foreach($departments as $department)

                    <label class="department-checkbox">

                        <input
                        type="checkbox"
                        name="departments[]"
                        value="{{ $department->id }}"
                        class="edit-department"
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
            <div class="edit-employee-actions">
        <div class="edit-actions-right">
            <button type="button" class="modal-cancel" onclick="closeEditEmployeeModal()">
                Bekor qilish
            </button>

            <button type="submit" class="modal-submit">
                <i class="fa-solid fa-check"></i>
                Saqlash
            </button>
        </div>
    </div>
</form>
<form method="POST"
    id="deleteEmployeeForm">
    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="delete-employee-btn"
        onclick="return confirm('Haqiqatan ham bu hodimni o‘chirmoqchimisiz?')">
        <i class="fa-regular fa-trash-can"></i>
        Hodimni o‘chirish2
    </button>

</form>
</div>
</div>
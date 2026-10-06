<div class="lead-modal" id="createLeadModal">

    <div class="lead-modal-overlay" onclick="closeCreateLeadModal()"></div>

    <div class="lead-modal-card">

        <div class="lead-modal-header">
            <div>
                <h2>Yangi lead qo‘shish</h2>
                <p>Yangi mijoz ma’lumotlarini kiriting</p>
            </div>

            <button
            type="button"
            class="lead-modal-close"
            onclick="closeCreateLeadModal()"
            >
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <form
    method="POST"
    action="{{ route('employee.leads.store') }}"
    id="createLeadForm"
    >
    @csrf

    <div class="lead-form-body">

        <div class="lead-form-group">
            <label>Mijoz ismi</label>

            <input
            type="text"
            name="client_name"
            placeholder="Mijoz ismini kiriting"
            value="{{ old('client_name') }}"
            required
            >
        </div>

        <div class="lead-form-group">
            <label>Telefon raqami</label>

            <input
            type="text"
            name="phone"
            id="leadPhone"
            placeholder="+998 90 123 45 67"
            maxlength="17"
            value="{{ old('phone') }}"
            required
            >
        </div>

        <div class="lead-form-group">
            <label>Qachon bormoqchi?</label>

            <input
            type="text"
            name="travel_date"
            placeholder="Masalan: Dekabr oyida"
            value="{{ old('travel_date') }}"
            >
        </div>

        <div class="lead-form-group">
            <label>Status</label>

            <select name="status_id" required>
                <option value="">Statusni tanlang</option>

                @foreach($statuses as $status)
                    <option value="{{ $status->id }}">
                        {{ $status->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="lead-modal-footer">

        <button
        type="button"
        class="lead-modal-cancel"
        onclick="closeCreateLeadModal()"
        >
        Bekor qilish
    </button>

    <button
    type="submit"
    class="lead-modal-submit"
    >
    <i class="fa-solid fa-plus"></i>
    Lead qo‘shish
</button>

</div>

</form>

</div>
</div>
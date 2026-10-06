<div class="status-modal" id="statusModal">

    <div class="status-modal-overlay"></div>

    <div class="status-modal-box">

        <div class="status-modal-header">
            <div>
                <h3>Statusni o‘zgartirish</h3>
                <span id="statusLeadName"></span>
            </div>

            <button class="status-modal-close" type="button">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>


        <form id="statusUpdateForm" method="POST">

            @csrf
            @method('PATCH')

            <div class="status-options">

                @foreach($statuses as $status)

                    <label class="status-option">

                        <input
                            type="radio"
                            name="status_id"
                            value="{{ $status->id }}"
                            class="lead-status-radio"
                        >

                        <span
                            class="status-option-color"
                            style="background: {{ $status->color }}"
                        ></span>

                        <span>{{ $status->name }}</span>

                    </label>

                @endforeach

            </div>


            <div class="status-modal-footer">

                <button
                    type="button"
                    class="cancel-status"
                >
                    Bekor qilish
                </button>

                <button
                    type="submit"
                    class="save-status"
                >
                    <i class="fa-solid fa-check"></i>
                    Saqlash
                </button>

            </div>

        </form>

    </div>

</div>
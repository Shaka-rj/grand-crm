const statusModal = document.getElementById('statusModal');
const statusLeadName = document.getElementById('statusLeadName');
const statusUpdateForm = document.getElementById('statusUpdateForm');

let currentLeadButton = null;


/* =========================
   STATUS MODAL OCHISH
========================= */

document.querySelectorAll('.lead-status-btn').forEach(button => {

    button.addEventListener('click', function () {

        currentLeadButton = this;

        const leadName = this.dataset.lead;
        const leadId = this.dataset.leadId;
        const statusId = this.dataset.statusId;

        statusLeadName.textContent = leadName;

        // Form action
        statusUpdateForm.action = `/employee/leads/${leadId}`;

        // Barcha statuslarni yechish
        document.querySelectorAll('input[name="lead_status"]')
            .forEach(input => {
                input.checked = false;
            });

        document.getElementById('statusComment').value = '';

        // Hozirgi statusni belgilash
        const currentStatus = document.querySelector(
            `input[name="lead_status"][value="${statusId}"]`
        );

        if (currentStatus) {
            currentStatus.checked = true;
        }

        statusModal.classList.add('active');
    });

});


/* =========================
   STATUS MODAL YOPISH
========================= */

function closeStatusModal() {

    statusModal.classList.remove('active');

    document.querySelectorAll('input[name="lead_status"]')
        .forEach(input => {
            input.checked = false;
        });

    currentLeadButton = null;
}


document.querySelector('.status-modal-close')
    .addEventListener('click', closeStatusModal);

document.querySelector('.cancel-status')
    .addEventListener('click', closeStatusModal);

document.querySelector('.status-modal-overlay')
    .addEventListener('click', closeStatusModal);


/* =========================
   CREATE LEAD MODAL
   BU QISMGA TEGILMAYDI
========================= */

function openCreateLeadModal() {

    const modal = document.getElementById('createLeadModal');

    if (!modal) return;

    modal.classList.add('show');
    document.body.style.overflow = 'hidden';

    document.querySelector(
        '#createLeadForm input[name="client_name"]'
    )?.focus();
}


function closeCreateLeadModal() {

    const modal = document.getElementById('createLeadModal');

    if (!modal) return;

    modal.classList.remove('show');
    document.body.style.overflow = '';
}


/* ESC bilan yopish */
document.addEventListener('keydown', function (e) {

    if (e.key === 'Escape') {

        closeCreateLeadModal();

        if (statusModal?.classList.contains('active')) {
            closeStatusModal();
        }
    }

});


///history modal
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('historyModal');
    const content = document.getElementById('historyContent');
    const leadName = document.getElementById('historyLeadName');

    function closeModal() {
        modal.hidden = true;
    }

    document.querySelectorAll('.lead-history-btn').forEach(button => {
        button.addEventListener('click', async function () {
            modal.hidden = false;
            leadName.textContent = this.dataset.lead || '';
            content.replaceChildren();

            const loading = document.createElement('p');
            loading.textContent = 'Tarix yuklanmoqda...';
            content.appendChild(loading);

            try {
                const response = await fetch(this.dataset.historyUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(
                        response.status === 403
                            ? 'Bu tarixni ko‘rishga ruxsatingiz yo‘q.'
                            : 'Tarixni yuklashda xatolik yuz berdi.'
                    );
                }

                const data = await response.json();
                content.replaceChildren();

                if (!data.histories.length) {
                    const empty = document.createElement('p');
                    empty.textContent = 'Hozircha status tarixi mavjud emas.';
                    content.appendChild(empty);
                    return;
                }

                data.histories.forEach(item => {
                    const card = document.createElement('div');
                    card.className = 'history-item';

                    const status = document.createElement('strong');
                    status.textContent = item.status;
                    status.style.color = item.color;

                    const comment = document.createElement('p');
                    comment.textContent = item.comment || 'Izoh qoldirilmagan';

                    const meta = document.createElement('small');
                    meta.textContent = `${item.user} · ${item.date}`;

                    card.append(status, comment, meta);
                    content.appendChild(card);
                });

            } catch (error) {
                content.replaceChildren();

                const message = document.createElement('p');
                message.textContent = error.message || 'Xatolik yuz berdi.';
                content.appendChild(message);
            }
        });
    });

    document.getElementById('closeHistory')
        .addEventListener('click', closeModal);

    modal.querySelector('.history-overlay')
        .addEventListener('click', closeModal);
});
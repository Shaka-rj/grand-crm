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
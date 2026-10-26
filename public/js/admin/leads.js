document.querySelector('.filter-select-btn')?.addEventListener('click', function () {
    document.querySelector('.department-filter').classList.toggle('active');
});
document.querySelector('.status-select-btn')?.addEventListener('click', function () {
    document.querySelector('.status-filter').classList.toggle('active');
});

const departmentFilterText = document.getElementById('departmentFilterText');

function updateDepartmentFilterText() {

    const checked = document.querySelectorAll(
        '.department-dropdown input[name="department_ids[]"]:checked'
    );

    const names = Array.from(checked).map(input => input.dataset.name);

    if (names.length === 0) {
        departmentFilterText.textContent = 'Bo‘limni tanlang';
    } else {
        departmentFilterText.textContent = names.join(', ');
    }
}

document.querySelectorAll(
    '.department-dropdown input[name="department_ids[]"]'
).forEach(input => {
    input.addEventListener('change', updateDepartmentFilterText);
});

updateDepartmentFilterText();



document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('historyModal');
    const content = document.getElementById('historyContent');
    const leadName = document.getElementById('historyLeadName');

    if (!modal || !content || !leadName) return;

    // Tarix tugmasi bosilganda
    document.addEventListener('click', async function (event) {
        const button = event.target.closest('.lead-history-btn');

        if (!button) return;

        modal.hidden = false;
        leadName.textContent = button.dataset.lead || '';
        content.replaceChildren();

        const loading = document.createElement('p');
        loading.textContent = 'Tarix yuklanmoqda...';
        content.appendChild(loading);

        try {
            const response = await fetch(button.dataset.historyUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(
                    response.status === 403
                        ? 'Bu tarixni ko‘rishga ruxsat yo‘q.'
                        : 'Tarixni yuklab bo‘lmadi.'
                );
            }

            const data = await response.json();

            leadName.textContent = data.lead || button.dataset.lead || '';
            content.replaceChildren();

            if (!data.histories || data.histories.length === 0) {
                const empty = document.createElement('p');
                empty.textContent = 'Hozircha tarix mavjud emas.';
                content.appendChild(empty);
                return;
            }

            data.histories.forEach(item => {
                const card = document.createElement('div');
                card.className = 'history-item';

                const status = document.createElement('strong');
                status.textContent = item.status;
                status.style.color = item.color || '#64748b';

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

    // Modalni yopish
    function closeHistoryModal() {
        modal.hidden = true;
    }

    document.getElementById('closeHistory')
        ?.addEventListener('click', closeHistoryModal);

    modal.querySelector('.history-overlay')
        ?.addEventListener('click', closeHistoryModal);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) {
            closeHistoryModal();
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const filter = document.querySelector('.employee-filter');

    if (!filter) return;

    const button = filter.querySelector('.filter-select-btn');
    const dropdown = filter.querySelector('.employee-dropdown');
    const text = document.getElementById('employeeFilterText');
    const checkboxes = dropdown.querySelectorAll(
        'input[type="checkbox"]'
    );

    // Dropdownni ochish va yopish
    button.addEventListener('click', function (e) {
        e.stopPropagation();

        // Boshqa filter dropdownlarini yopish
        document.querySelectorAll(
            '.department-filter, .status-filter'
        ).forEach(function (item) {
            item.classList.remove('open');
        });

        filter.classList.toggle('open');
    });

    // Tanlangan xodimlarni ko'rsatish
    function updateEmployeeText() {
        const selected = Array.from(checkboxes)
            .filter(function (checkbox) {
                return checkbox.checked;
            })
            .map(function (checkbox) {
                return checkbox.value === '0'
                    ? 'Biriktirilmagan'
                    : checkbox.dataset.name;
            });

        if (selected.length === 0) {
            text.textContent = 'Xodimni tanlang';
        } else if (selected.length === 1) {
            text.textContent = selected[0];
        } else {
            text.textContent = selected.length + ' ta tanlandi';
        }
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateEmployeeText);
    });

    // Sahifa yuklanganda tanlanganlarni chiqarish
    updateEmployeeText();

    // Dropdown tashqarisiga bosilganda yopish
    document.addEventListener('click', function (e) {
        if (!filter.contains(e.target)) {
            filter.classList.remove('open');
        }
    });

    // Dropdown ichiga bosish uni yopmasin
    dropdown.addEventListener('click', function (e) {
        e.stopPropagation();
    });
});

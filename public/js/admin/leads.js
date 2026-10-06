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
function openEmployeeModal() {
    document.getElementById('employeeModal').classList.add('show');

    document.body.style.overflow = 'hidden';
}

function closeEmployeeModal() {
    document.getElementById('employeeModal').classList.remove('show');

    document.body.style.overflow = '';
}


function deleteEmployee(id) {

    const confirmed = confirm(
        'Haqiqatan ham bu hodimni o‘chirmoqchimisiz?'
    );

    if (!confirmed) {
        return;
    }

    window.location.href = '/employee/delete/' + id;
}



function openEditEmployeeModal(
    id,
    name,
    username,
    departments
) {

    document.getElementById('editName').value = name;

    document.getElementById('editUsername').value = username;


    document
        .querySelectorAll('.edit-department')
        .forEach(function (checkbox) {

            checkbox.checked =
                departments.includes(
                    Number(checkbox.value)
                );

        });

    document.getElementById('deleteEmployeeForm').action = '/admin/employees/' + id;

    document.getElementById('editEmployeeForm').action =
        '/admin/employees/' + id;


    document
        .getElementById('editEmployeeModal')
        .classList.add('show');


    document.body.style.overflow = 'hidden';
}


function closeEditEmployeeModal() {

    document
        .getElementById('editEmployeeModal')
        .classList.remove('show');

    document.body.style.overflow = '';

}
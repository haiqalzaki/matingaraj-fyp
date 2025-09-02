async function addUser(body) {
    const url = `${BASE_PATH}/user/add`;

    try {
        const response = await fetch(url, {
            method: "POST",
            body: body,
        }) 

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();

        if (!json.status) {
            showAlert(json.message);
        } else {
            showAlert(json.message);
            window.location.href = `${BASE_PATH}/user`;
        }
    } catch (error) {
        console.error(error.message);
        throw error;
    }
}

async function getUsers() {
    const url = `${BASE_PATH}/user/getAll`;

    try {
        const response = await fetch(url, {
            method: "GET",
        }) 

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();

        if (!json.status) {
            throw new Error(`Data status: ${json.message}`);
        }
        
        return json.data;
    } catch (error) {
        console.error(error.message);
        throw error;
    }
}

async function editUser(id) {
    const modifyModal = document.getElementById('modify-user-modal');
    const myModal = new bootstrap.Modal(modifyModal);
    myModal.show(); 

    let result;

    try {
        result = await fetchUser(id);
    } catch (error) {
        console.error(error.message);
    }

    document.getElementById('user-id-edit').value = id;
    document.getElementById('user-name-edit').value = result.username;
    document.getElementById('user-phone-edit').value = result.phone;
    document.getElementById('user-email-edit').value = result.email;
    document.getElementById('user-role-edit').value = result.role === 1 ? 1 : 0;
}

async function fetchUser(id) {
    const url = `${BASE_PATH}/user/get/${id}`; 

    try {
        const response = await fetch(url, {
            method: "GET",
        })

        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const json = await response.json();

        return json.data;
    } catch (error) {
        console.error(error.message);
        throw error;
    }  
}

async function deleteUser(id) {
    let user = await fetchUser(id);

    document.getElementById('user-id-delete').value = id;

    await deletePopup(`Account Deletion`, `Are you sure you want to delete >> ${user.username} << account?`, `Proceed`);
}

async function deletePopup(title, message, button) {
    let deleteModal = document.getElementById('delete-modal');
    let deleteModalTitle = document.getElementById('delete-modal-title');
    let deleteModalContent = document.getElementById('delete-modal-content');
    let deleteModalButton = document.getElementById('delete-user-btn');

    const myModal = new bootstrap.Modal(deleteModal);
    deleteModalTitle.textContent = title;
    deleteModalContent.textContent = message;
    deleteModalButton.textContent = button;
    myModal.show(); 
}

document.addEventListener("DOMContentLoaded", async () => {
    let adminSpinner = document.getElementById('admin-spinner');
    let workerSpinner = document.getElementById('worker-spinner');
    
    try {
        users = await getUsers();

        dataAdmin = users.admin.map(admin => [
            admin.u_name,
            admin.u_email,
            admin.u_phone,
            `
            <button type="button" class="btn btn-primary btn-sm w-auto" onclick="editUser('${admin.u_id}')">Edit</button>
            <button type="button" class="btn btn-danger btn-sm w-auto" onclick="deleteUser('${admin.u_id}')">Delete</button>
            `
        ]);
    
        dataWorker = users.worker.map(worker => [
            worker.u_name,
            worker.u_email,
            worker.u_phone,
            `
            <button type="button" class="btn btn-primary btn-sm w-auto" onclick="editUser('${worker.u_id}')">Edit</button>
            <button type="button" class="btn btn-danger btn-sm w-auto" onclick="deleteUser('${worker.u_id}')">Delete</button>
            `
        ]);
    } catch (error) {
        console.error("Failed fetching users on main!");   
    } finally {
        adminSpinner.setAttribute("class","d-none");
        workerSpinner.setAttribute("class","d-none");
    }

    new DataTable('#table-admin', {
        paging: true,
        responsive: true,
        data: dataAdmin,
        columns: [
            { title: "Name", className: "text-center" },  
            { title: "Email", className: "text-center" },  
            { title: "Telephone", className: "text-center" }, 
            { title: "Modify", className: "text-center" }
        ],
        columnDefs: [
            { responsivePriority: 0, targets: 0 },
            { responsivePriority: 1, targets: 1 },
            { responsivePriority: 2, targets: 2 }
        ],
        order: [0, 'asc'],
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
    });

    new DataTable('#table-user', {
        paging: true,
        responsive: true,
        data: dataWorker,
        columns: [
            { title: "Name", className: "text-center" },  
            { title: "Email", className: "text-center" },  
            { title: "Telephone", className: "text-center" }, 
            { title: "Modify", className: "text-center" }
        ],
        columnDefs: [
            { responsivePriority: 0, targets: 0 },
            { responsivePriority: 1, targets: 1 },
            { responsivePriority: 2, targets: 2 }
        ],
        order: [0, 'asc'],
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
    });

    document.getElementById("add-user-btn").addEventListener('click', async (e) => {
        e.preventDefault();

        const passwordVerify = document.getElementById('user-password-add').value;
        const passwordConfirm = document.getElementById('user-password-confirm-add').value;

        if (passwordVerify.length < 10) {
            showAlert('Password must be greater than 10 characters! Try again.');
            document.getElementById('user-password-add').value = '';
            document.getElementById('user-password-confirm-add').value = '';
            return;
        }

        if (passwordVerify !== passwordConfirm) {
            showAlert('Password didn\'t match! Try again.');
            return;
        }

        const userForm = document.getElementById('add-user-form');
        const addUserForm = new FormData(userForm);

        try {
            await addUser(addUserForm);
        } catch (error) {
            console.error("Failed adding user on main!");
            showAlert("Bad Request: 400");
        }
    })

    document.getElementById("update-user-btn").addEventListener('click', async (e) => {
        e.preventDefault();

        const url = `${BASE_PATH}/user/update`;
        const userForm = document.getElementById('modify-user-form');
        const formUpdate = new FormData(userForm);

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formUpdate,
            })

            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const json = await response.json();

            if (!json.status) {
                showAlert(json.message);
            } else {
                showAlert(json.message);
                window.location.href = `${BASE_PATH}/user`;
            }
        } catch (error) {
            console.error(error.message);
        }
    })

    document.getElementById('delete-user-btn').addEventListener('click', async (e) => {
        e.preventDefault();

        const url = `${BASE_PATH}/user/delete`;
        const userForm = document.getElementById('delete-user-form');
        const formUpdate = new FormData(userForm);

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formUpdate,
            })

            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const json = await response.json();

            if (!json.status) {
                showAlert(json.message);
            } else {
                showAlert(json.message);
                window.location.href = `${BASE_PATH}/user`;
            }
        } catch (error) {
            console.error(error.message);
        }
    });
})

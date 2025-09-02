async function addCustomer(body) {
    const url = `${BASE_PATH}/customer/add`;

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
            window.location.href = `${BASE_PATH}/customer`;
        }
    } catch (error) {
        console.error(error.message);
        throw error;
    }
}

async function getCustomers() {
    const url = `${BASE_PATH}/customer/getAll`;

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

async function editCustomer(id) {
    const modifyModal = document.getElementById('modify-customer-modal');
    const myModal = new bootstrap.Modal(modifyModal);
    myModal.show(); 

    let result;

    try {
        result = await fetchCustomer(id);
    } catch (error) {
        console.error(error.message);
    }

    document.getElementById('customer-id-edit').value = id;
    document.getElementById('customer-name-edit').value = result.name;
    document.getElementById('customer-email-edit').value = result.email !== 'Tiada' ? result.email : '';
    document.getElementById('customer-phone-edit').value = result.phone !== 'Tiada' ? result.phone : '';
    document.getElementById('customer-platform-edit').value = result.platform;
    document.getElementById('customer-remark-edit').value = result.remark !== 'No remarks' ? result.remark : '';
}

async function fetchCustomer(id) {
    const url = `${BASE_PATH}/customer/get/${id}`; 

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

async function deleteCustomer() {
    await deletePopup(`Customer Deletion`, `Are you sure you want to delete this customer?`, `Delete`);
}

async function getCustomersStats() {
    const url = `${BASE_PATH}/customer/statistics`;

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

async function deletePopup(title, message, button) {
    let deleteModal = document.getElementById('delete-modal');
    let deleteModalTitle = document.getElementById('delete-modal-title');
    let deleteModalContent = document.getElementById('delete-modal-content');
    let deleteModalButton = document.getElementById('delete-customer-btn');

    const myModal = new bootstrap.Modal(deleteModal);
    deleteModalTitle.textContent = title;
    deleteModalContent.textContent = message;
    deleteModalButton.textContent = button;
    myModal.show(); 
}

let myCustomer;

document.addEventListener("DOMContentLoaded", async () => {
    let pelangganSpinnerTable = document.getElementById('pelanggan-spinner-table');
    
    try {
        customers = await getCustomers();

        cxData = customers.map(cx => {
            const originalTarikh = cx.createdOn.split(' ')[0];
            const formatTarikh = reverseDateFormat(originalTarikh);
            
            return [ 
                cx.c_name,
                cx.c_email,
                cx.c_phone,
                cx.c_platform,
                { original: originalTarikh, display: formatTarikh },  
                `
                <button type="button" class="btn btn-primary btn-sm rounded-4 shadow" style="font-size: 0.8rem;" onclick="editCustomer('${cx.c_id}')">Edit</button>
                `,
		        `
                <a class="btn btn-warning btn-sm rounded-4 shadow" style="font-size: 0.8rem;" href="${BASE_PATH}/customer/detail/${cx.c_id}">Go to</a>
                `
            ]});
    } catch (error) {
        console.error("Failed fetching customers on main!", error);   
    } finally {
        pelangganSpinnerTable.setAttribute("class","d-none");
    }

    // Cara buat table
    myCustomer = new TableClass('table-customer', 10);
    myCustomer.setPagination(true);
    myCustomer.setFontSizeCol(1.05);
    myCustomer.setFontSizeRow(0.9);
    myCustomer.setColumnHeader(["Customer Name","Email","Telephone","Platform","Date Created","Modify", "Customer Details"]);

    // Load Data to Table
    myCustomer.load(cxData);

    document.getElementById('search-control-customer').addEventListener('input', function() {
        myCustomer.search(this.value);
    });
    
    document.getElementById("add-customer-btn").addEventListener('click', async (e) => {
        e.preventDefault();

        const customerForm = document.getElementById('add-customer-form');
        const addCustomerForm = new FormData(customerForm);

        try {
            await addCustomer(addCustomerForm);
        } catch (error) {
            console.error("Failed adding customer on main!");
            showAlert("Bad Request: 400");
        }
    })

    document.getElementById("update-customer-btn").addEventListener('click', async (e) => {
        e.preventDefault();

        const url = `${BASE_PATH}/customer/update`;
        const customerForm = document.getElementById('modify-customer-form');
        const formUpdate = new FormData(customerForm);

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
                window.location.href = `${BASE_PATH}/customer`;
            }
        } catch (error) {
            console.error(error.message);
        }
    })

    document.getElementById('delete-customer-btn').addEventListener('click', async (e) => {
        e.preventDefault();

        let id = document.getElementById('customer-id-edit').value;

        const url = `${BASE_PATH}/customer/delete`;
        const formDelete = new FormData();
        formDelete.append('cxID', id);

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formDelete,
            })

            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }

            const json = await response.json();

            if (!json.status) {
                showAlert(json.message);
            } else {
                window.location.href = `${BASE_PATH}/customer`;
            }
        } catch (error) {
            console.error(error.message);
        }
    });
})


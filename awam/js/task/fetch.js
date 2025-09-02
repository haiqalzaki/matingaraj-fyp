// ------------------ //
// ------------------ //
// ---- Fetch JS ----- //
// ------------------ //
// ------------------ //

function addBarangToTask(id, name, price) {
    id = parseInt(id);
    price = parseFloat(price);

    const existingItem = myBarang.displayData.findIndex(item => item[0] === id);

    if (existingItem !== -1) {
        alert("This item is already added.");
        return;
    }

    let quantityItem = prompt(`Barang: ${name} | Enter quantity: `);
    quantityItem = parseInt(quantityItem);

    if (isNaN(quantityItem) || quantityItem <= 0) {
        alert("Please enter a valid quantity.");
        return;
    }

    let productIndex = myTable.displayData.findIndex(item => parseInt(item[0]) === parseInt(id));
    
    if (productIndex !== -1) {
        let availableStock = myTable.displayData[productIndex][3]; 

        if (quantityItem > availableStock) {
            alert("Not enough stock available.");
            return;
        }

        myTable.displayData[productIndex][3] -= quantityItem;
    }

    let totalPrice = Math.round(quantityItem * price * 100) / 100;

    let brg = [
        id, 
        name, 
        parseInt(quantityItem),
        parseFloat(totalPrice),
        `<a class="btn btn-danger btn-sm" onclick='removeBarangFromTask(${id})'>Remove</a>`
    ];

    myBarang.displayData.push(brg);

    myBarang.displayData = [...myBarang.displayData];
    myBarang.render(myBarang.displayData);

    myTable.displayData = [...myTable.displayData];
    myTable.render(myTable.displayData);
}

function removeBarangFromTask(id) {
    const index = myBarang.displayData.findIndex(item => item[0] === id);

    if (index !== -1) {
        let quantityItem = myBarang.displayData[index][2]; 

        myBarang.displayData.splice(index, 1);

        let productIndex = myTable.displayData.findIndex(item => parseInt(item[0]) === parseInt(id));

        if (productIndex !== -1) {
            myTable.displayData[productIndex][3] += quantityItem;
        } else {
            console.error("Error: Product not found in Available Barang Table for ID:", id);
        }

        myBarang.displayData = [...myBarang.displayData];
        myBarang.render(myBarang.displayData);

        myTable.displayData = [...myTable.displayData];
        myTable.render(myTable.displayData);
    } else {
        console.error("Error: Item not found in Item List for ID:", id);
    }
}

async function updateTask(body) {
    const url = `${BASE_PATH}/task/update`;

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
            const successFlasher = document.getElementById('success-flasher');
            successFlasher.classList.remove('d-none');

            setTimeout(() => {
                successFlasher.classList.add('d-none');

                window.location.reload();
            }, 1000);
        }
    } catch (error) {
        console.error(error.message);
        throw error;
    }
}

async function deleteTask() {
    await deletePopup(`Task Deletion`, `Are you sure you want to delete this task?`, `Proceed`);
}

async function deletePopup(title, message, button) {
    let deleteModal = document.getElementById('delete-modal');
    let deleteModalTitle = document.getElementById('delete-modal-title');
    let deleteModalContent = document.getElementById('delete-modal-content');
    let deleteModalButton = document.getElementById('delete-task-btn');

    const myModal = new bootstrap.Modal(deleteModal);
    deleteModalTitle.textContent = title;
    deleteModalContent.textContent = message;
    deleteModalButton.textContent = button;
    myModal.show();
}

async function getBarangs() {
    const url = `${BASE_PATH}/barang/getAll`;

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

async function getTask() {
    const pathSegments = window.location.pathname.split("/");
    const taskId = pathSegments[pathSegments.length - 1];

    const url = `${BASE_PATH}/task/getTask/${taskId}`;

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

let myTable;
let myBarang;
let taskBarangDataUpdate; 

document.addEventListener("DOMContentLoaded", async () => { 
    try {
        barangs = await getBarangs();
        task = await getTask();

        brgData = barangs.map(row => {

            return [
            row.i_id,
            `<img src="${BASE_PATH}${row.i_image_path}" alt="Barang Image" width="50" height="50" style="border: solid grey 1px; border-radius: 10%;" class="image-fluid ">`,
            row.i_name,
            parseInt(row.i_stock),
            { original: row.i_totalPrice, display: row.i_totalPrice },  
            `<a class="btn btn-success btn-sm" onclick="addBarangToTask(${row.i_id},'${row.i_name}', '${row.i_totalPrice}')">Add</a>`,
            ]
        });

        brg = task.barang.map(row => {

            return [
                row.barang_id_fetch,
                row.barang_nama_fetch,
                parseInt(row.barang_kuantiti_fetch),
                parseFloat(row.barang_hargaTotal_fetch),
                `<a class="btn btn-danger btn-sm" onclick='removeBarangFromTask(${row.barang_id_fetch})'>Remove</a>`
            ]
        })

        myBarang = new TableClass('myBarang', 5);
        myBarang.setPagination(true);
        myBarang.setFontSizeCol(0.9);
        myBarang.setFontSizeRow(0.85);
        myBarang.setColumnHeader(["ID","Name","Quantity","Total Price","Option"]);

        myBarang.load(brg);
    } catch (error) {
        console.error("Failed fetching barangs on main!", error);
    }

    // Initialize Table
    myTable = new TableClass('myTable', 5);
    myTable.setPagination(true);
    myTable.setFontSizeCol(0.9);
    myTable.setFontSizeRow(0.85);
    myTable.setColumnHeader(["ID","Picture","Name","Quantity","Price","Option"]);

    // Load Data to Table
    myTable.load(brgData);

    // Event listener for Search Function
    document.getElementById('search-control-brg').addEventListener('input', function() {
        myTable.search(this.value);
    });

    // Event listener for Refresh Table Function
    document.getElementById('refreshTable').addEventListener('click', async function() {
        try {
            barangs = await getBarangs();
    
            brgDataRefresh = barangs.map(row => {
                return [
                row.i_id,
                `<img src="${BASE_PATH}${row.i_image_path}" alt="Barang Image" width="50" height="50" style="border: solid grey 1px; border-radius: 10%;" class="image-fluid ">`,
                row.i_name,
                parseInt(row.i_stock),
                { original: row.i_totalPrice, display: row.i_totalPrice },  
                `<a class="btn btn-success btn-sm" onclick="addBarangToTask(${row.i_id},'${row.i_name}', '${row.i_totalPrice}')">Add</a>`,
            ]});

            myTable.load(brgDataRefresh);
        } catch (error) {
            console.error("Failed fetching barangs on main during refresh!", error);
        }
    });

    

    document.getElementById('update-task-btn').addEventListener('click', debounce( async (e) => {
        e.preventDefault();

        const taskForm = document.getElementById('taskFormUpdate');
        let updateTaskForm = new FormData(taskForm);

        taskBarangDataUpdate = myBarang.getData();

        taskBarangDataUpdate.forEach((item, index) => {
            updateTaskForm.append(`barang[${index}][barang_id]`, item[0]);
            updateTaskForm.append(`barang[${index}][barang_name]`, item[1]);
            updateTaskForm.append(`barang[${index}][total_quantity]`, item[2]);
            updateTaskForm.append(`barang[${index}][total_price]`, item[3]);
        });

        try {
            await updateTask(updateTaskForm);
        } catch (error) {
            console.error("Failed updating task on main!");
            showAlert("Bad Request: 400");
        }
    }, 400))

    document.getElementById('delete-task-btn').addEventListener('click', debounce( async (e) => {
        e.preventDefault();

        let id = document.getElementById('task-id').value;

        const url = `${BASE_PATH}/task/delete`;
        const formDelete = new FormData();
        formDelete.append('taskID', id);

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
                window.location.href = `${BASE_PATH}/task`;
            }
        } catch (error) {
            console.error(error.message);
        }
    }, 300))
});
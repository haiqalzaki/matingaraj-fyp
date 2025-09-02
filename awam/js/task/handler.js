// ------------------ //
// ------------------ //
// ---- Task JS ----- //
// ------------------ //
// ------------------ //

async function clearCustomer() {
    const cxName = document.getElementById('customer-search');
    const cxID = document.getElementById('customer-id');

    console.log(cxName, cxID);

    cxName.value = '';
    cxID.value = '';
}

async function checkCustomer() {
    const cxName = document.getElementById('customer-search').value;

    const status = true;

    const messageBox = document.getElementById('customer-box');

    const box = document.createElement('div');
    box.className = "col d-flex align-items-center justify-content-center mt-2 py-2 w-100 border rounded-3";

    const message = document.createElement('p');
    message.className = "text-light text-capitalize m-0"; // Use m-0 to remove default margin for perfect centering

    if (status) {
        message.textContent = "Customer didn't exist!";
        box.classList.add('bg-danger');
    } else {
        message.textContent = "Customer exist!";
        box.classList.add('bg-success');
    }

    box.appendChild(message);
    messageBox.appendChild(box);

    setTimeout(() => {
        box.remove();
    }, 2500);
}

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

async function addTask(body) {
    const url = `${BASE_PATH}/task/add`;

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
            window.location.href = `${BASE_PATH}/task`;
        }
    } catch (error) {
        console.error(error.message);
        throw error;
    }
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

let myBarang; 
let myTable; 
let myTable2;
let taskBarangData; 

document.addEventListener("DOMContentLoaded", async () => { 
    try {
        barangs = await getBarangs();

        brgData = barangs.map(row => {

            return [
            row.i_id,
            `<img src="${BASE_PATH}${row.i_image_path}" alt="Barang Image" width="50" height="50" style="border: solid grey 1px; border-radius: 10%;" class="image-fluid ">`,
            row.i_name,
            parseInt(row.i_stock),
            { original: row.i_totalPrice, display: row.i_totalPrice },  
            `<a class="btn btn-success btn-sm" onclick="addBarangToTask(${row.i_id},'${row.i_name}', '${row.i_totalPrice}')">Add</a>`,
        ]});

        delete barangs;
    } catch (error) {
        console.error("Failed fetching barangs on main!", error);
    }

    // Initialize Table
    myTable = new TableClass('myTable', 5);
    myTable.setPagination(true);
    myTable.setFontSizeCol(0.9);
    myTable.setFontSizeRow(0.85);
    myTable.setColumnHeader(["ID","Picture","Name","Quantity","Price","Option"]);

    myBarang = new TableClass('myBarang', 5);
    myTable.setFontSizeCol(0.9);
    myBarang.setFontSizeRow(0.85);
    myBarang.setColumnHeader(["ID","Name","Quantity","Total","Edit"]);

    // Load Data to Table
    myTable.load(brgData);
    myBarang.load();

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
    
            delete barangs;

            myTable.load(brgDataRefresh);
        } catch (error) {
            console.error("Failed fetching barangs on main during refresh!", error);
        }
    });

    // Search Box For Customer
    let customerSearch = document.getElementById('customer-search');
    let customerID = document.getElementById('customer-id');
    let customerSearchContainer = document.getElementById('customer-search-container')

    // Search Start (When input box is typed in)
    customerSearch.addEventListener('input', debounce( async () => {
        const carian = customerSearch.value.trim().replace(/\s+/g, '');

        if (carian === '') {
            customerSearchContainer.style.display = 'none';
            customerSearchContainer.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`${BASE_PATH}/customer/search/${encodeURIComponent(carian)}`);
            const data = await response.json();

            if (data.length > 0) {
                customerSearchContainer.innerHTML = ''; 

                data.forEach(item => {
                    const div = document.createElement('div');
                    div.classList.add('result-item');
                    div.classList.add('border-bottom');
                    div.textContent = `${item.c_name}`;

                    div.dataset.nama = item.c_name;

                    div.addEventListener('click', () => {
                        customerSearch.value = item.c_name; 
                        customerID.value = item.c_id;

                        customerSearchContainer.style.display = 'none';
                    });

                    customerSearchContainer.appendChild(div);
                });

                customerSearchContainer.style.display = 'block';
            } else {
                customerSearchContainer.style.display = 'none';
            }
        } catch (error) {
            console.error('Error fetching search data:', error);
        }
    }, 0));

    document.getElementById('add-task-btn').addEventListener('click', debounce( async () => {
        const taskForm = document.getElementById('taskForm');
        let addTaskForm = new FormData(taskForm);

        // Store Barang ID
        taskBarangData = myBarang.getData();

        taskBarangData.forEach((item, index) => {
            addTaskForm.append(`barang[${index}][barang_id]`, item[0]);
            addTaskForm.append(`barang[${index}][barang_name]`, item[1]);
            addTaskForm.append(`barang[${index}][total_quantity]`, item[2]);
            addTaskForm.append(`barang[${index}][total_price]`, item[3]);
        });

        try {
            await addTask(addTaskForm);
        } catch (error) {
            console.error("Failed adding barang on main!");
            showAlert("Bad Request: 400");
        }
    }, 400))
})










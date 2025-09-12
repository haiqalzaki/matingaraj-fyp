async function addBarang(body) {
    const url = `${BASE_PATH}/barang/add`;

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
            window.location.href = `${BASE_PATH}/barang`;
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

async function editBarang(id) {
    const modifyModal = document.getElementById('modify-barang-modal');
    const myModal = new bootstrap.Modal(modifyModal);
    myModal.show();

    let result;

    try {
        result = await fetchBarang(id);
    } catch (error) {
        console.error(error.message);
    }

    document.getElementById('barang-id-edit').value = id;
    document.getElementById('barang-image-edit').src = BASE_PATH + result.img;
    document.getElementById('barang-name-edit').value = result.name;
    document.getElementById('barang-stock-edit').value = result.stock;
    document.getElementById('barang-harga-edit').value = result.price;
    document.getElementById('barang-markup-edit').value = result.markup;
    document.getElementById('barang-total-edit').value = result.total;
    document.getElementById('barang-remark-edit').value = result.remark !== 'No remarks' ? result.remark : '';
}

async function deleteBarang() {
    await deletePopup(`Item Deletion`, `Are you sure you want to delete this item?`, `Proceed`);
}

async function fetchBarang(id) {
    const url = `${BASE_PATH}/barang/get/${id}`;

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

async function fetchAvailableBarang() {
    const url = `${BASE_PATH}/barang/available`;

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

async function fetchBarangStats() {
    const url = `${BASE_PATH}/barang/fetchStats`;

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

async function clearForm() {
    const barangForm = document.getElementById('add-barang-form');
    barangForm.reset();
}

async function deletePopup(title, message, button) {
    let deleteModal = document.getElementById('delete-modal');
    let deleteModalTitle = document.getElementById('delete-modal-title');
    let deleteModalContent = document.getElementById('delete-modal-content');
    let deleteModalButton = document.getElementById('delete-barang-btn');

    const myModal = new bootstrap.Modal(deleteModal);
    deleteModalTitle.textContent = title;
    deleteModalContent.textContent = message;
    deleteModalButton.textContent = button;
    myModal.show(); 
}

function showBarangGrossAdd() {
    const modal = parseFloat(document.getElementById('barang-price-add').value) || 0;
    const markup = parseFloat(document.getElementById('barang-markup-add').value) || 0; 

    const gross = modal + markup;

    document.getElementById('barang-total-add').value = gross.toFixed(2);
}

function showBarangGrossEdit() {
    const modal = parseFloat(document.getElementById('barang-harga-edit').value) || 0;
    const markup = parseFloat(document.getElementById('barang-markup-edit').value) || 0; 

    const gross = modal + markup;

    document.getElementById('barang-total-edit').value = gross.toFixed(2);
}

document.addEventListener("DOMContentLoaded", async () => {
    let barangSpinnerTable = document.getElementById('barang-spinner-table');

    try {
        barangs = await getBarangs();
        stats = await fetchBarangStats();

        console.log(stats);

        barangData = barangs.map(brg => [
            `<img src="${BASE_PATH}${brg.i_image_path}" alt="Barang Image" width="100" height="100" style="border: solid grey 1px; border-radius: 10%;" class="image-fluid ">`,
            brg.i_name,
            brg.i_stock,
            brg.i_cost,
            brg.i_markup,
            brg.i_totalPrice,
            brg.createdOn,
            `
            <div class="d-flex flex-column p-2">
                <button type="button" class="btn btn-primary btn-sm rounded-4 shadow mt-1" onclick="editBarang('${brg.i_id}')">Edit</button>
            </div>
            `
        ]);

        available = await fetchAvailableBarang();

        document.getElementById('barang-available').textContent = available.total_barang;

        var donutOptions = {
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 25,
                        pointStyle: 'rect', 
                        usePointStyle: true,
                        font: {
                            size: window.innerWidth < 600 ? 12 : 16,
                        }
                    }
                }
            },
            cutout: '50%',
        };

        var chDonutData1 = {
            labels: stats.map(item => item.ti_name),
            datasets: [
                {
                    backgroundColor: ['red', 'blue', 'green', 'yellow', 'orange'],
                    borderWidth: 5,
                    data: stats.map(item => Number(item.total_quantity)) 
                }
            ]
        };

        var chDonut1 = document.getElementById("chDonut1");
        if (chDonut1) {
            new Chart(chDonut1, {
                type: 'pie',
                data: chDonutData1,
                options: donutOptions
            });
        }
    } catch (error) {
        console.error("Failed fetching barangs on main!", error);
    } finally {
        barangSpinnerTable.setAttribute("class", "d-none");
    }

    new DataTable('#table-barang', {
        responsive: true,
        data: barangData,
        columns: [
            { title: "Item Image", className: "text-center" },
            { title: "Item Name", className: "text-center" },
            { title: "Stock", className: "text-center" },
            { title: "Unit Cost (RM)", className: "text-center" },
            { title: "Unit Markup (RM)", className: "text-center" },
            { title: "Total Cost (RM)", className: "text-center" },
            { title: "Date Added", className: "text-center" },
            { title: "Modify", className: "text-center" }
        ],
        columnDefs: [
            { responsivePriority: 0, targets: 0 },
            { responsivePriority: 1, targets: 1 },
            { responsivePriority: 2, targets: 2 }
        ],
        // order: [6, 'asc'],
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
    });

    document.getElementById("add-barang-btn").addEventListener('click', async (e) => {
        e.preventDefault();

        const barangForm = document.getElementById('add-barang-form');
        const addBarangForm = new FormData(barangForm);

        try {
            await addBarang(addBarangForm);
        } catch (error) {
            console.error("Failed adding barang on main!");
            showAlert("Bad Request: 400");
        }
    });

    document.getElementById("update-barang-btn").addEventListener('click', async (e) => {
        e.preventDefault();

        const url = `${BASE_PATH}/barang/update`;
        const barangForm = document.getElementById('modify-barang-form');
        const formUpdate = new FormData(barangForm);

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
                window.location.href = `${BASE_PATH}/barang`;
            }
        } catch (error) {
            console.error(error.message);
        }
    });

    document.getElementById('delete-barang-btn').addEventListener('click', async (e) => {
        e.preventDefault();

        let id = document.getElementById('barang-id-edit').value;

        const url = `${BASE_PATH}/barang/delete`;
        const formDelete = new FormData();
        formDelete.append('barangID', id);

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
                showAlert(json.message);
                window.location.href = `${BASE_PATH}/barang`;
            }
        } catch (error) {
            console.error(error.message);
        }
    });

    document.getElementById('barang-price-add').addEventListener('input', showBarangGrossAdd);
    document.getElementById('barang-markup-add').addEventListener('input', showBarangGrossAdd);
    document.getElementById('barang-harga-edit').addEventListener('input', showBarangGrossEdit);
    document.getElementById('barang-markup-edit').addEventListener('input', showBarangGrossEdit);
})
async function getTaskCustomer() {
    id = document.getElementById('cx-id').value;

    const url = `${BASE_PATH}/customer/dapatkanTask/${id}`;

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

document.addEventListener("DOMContentLoaded", async () => { 
    customer = await getTaskCustomer();

    taskData = customer.map(task => [
        task.t_plate,
        task.t_serviceStatus,
        task.t_totalPrice,
        `<a href="${BASE_PATH}/task/fetch/${task.t_id}" class="user_button_dash btn btn-warning btn-sm rounded-2 shadow">Go to</a>`,
        `
        <div class="btn-group btn-group-sm justify-content-start" role="group">
            <a href="${BASE_PATH}/pdf/quotation/${task.t_id}" class="user_button_dash btn btn-success">Quotation</a>
            <a href="${BASE_PATH}/pdf/receipt/${task.t_id}" class="user_button_dash btn btn-secondary">Receipt</a>
        </div>
        `,
        task.createdOn,
    ]);

    let tableTask = new TableClass('table-task', 5);
    tableTask.setColumnHeader(['Plate','Service Status','Total (RM)','Task Details','Get PDF', 'Date Added']);
    tableTask.setPagination(false);
    tableTask.setFontSizeRow(0.90);
    tableTask.load(taskData);

    document.getElementById('search-control-task').addEventListener('input', function() {
        tableTask.search(this.value);
    });
})
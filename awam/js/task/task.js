async function getTasks() {
    const url = `${BASE_PATH}/task/getAll`;

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

let tableTask;

document.addEventListener('DOMContentLoaded', async () => {
    let taskSpinnerTable = document.getElementById('task-spinner-table');

    try {
        allTask = await getTasks();

        task = allTask.map(row => {
            const originalTarikh = row.createdOn.split(' ')[0];
            const formatTarikh = reverseDateFormat(originalTarikh);
            const dibayar = row.isPaid == 0 ? 
                            `<button class="btn btn-danger btn-sm rounded-5 p-2"></button>` 
                            : `<button class="btn btn-success btn-sm rounded-5 p-2"></button>`;
    
            return [
                `<a class="text-decoration-none" href="${BASE_PATH}/customer/detail/${row.c_id}">${row.c_nama}</a>`,
                row.t_bike,
                row.t_plate,
                row.t_serviceType,
                row.t_serviceStatus === "Pending" ? `<span class="badge bg-warning text-dark">${row.t_serviceStatus}</span>`
        		: row.t_serviceStatus === "Ongoing" ? `<span class="badge bg-primary">${row.t_serviceStatus}</span>`
        		: `<span class="badge bg-success">${row.t_serviceStatus}</span>`,
                parseFloat(row.t_totalPrice),
                dibayar,
                { original: originalTarikh, display: formatTarikh },  
                `<a href="${BASE_PATH}/task/fetch/${row.t_id}" class="btn btn-primary btn-sm rounded-4 shadow" style="font-size: 0.8rem;">Edit</a>`,
        ]});

        tableTask = new TableClass('table-task', 10);
    	tableTask.setPagination(true);
    	tableTask.setFontSizeCol(0.9);
    	tableTask.setFontSizeRow(0.85);
    	tableTask.setColumnHeader(["Customer","Type","Plate","Service","Status","Total (RM)","Paid","Date Created","Modify"]);

    	tableTask.load(task);
    } catch (error) {
        console.error("Failed fetching tasks on main!", error);
    } finally {
        taskSpinnerTable.setAttribute("class","d-none");
    }

    

    

    document.getElementById('search-control-task').addEventListener('input', function() {
        tableTask.search(this.value);
    });
})

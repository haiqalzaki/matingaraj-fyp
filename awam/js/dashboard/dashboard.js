function latestTaskCustomer(cxID, taskID) {
    console.log("Opening task for customer... " + cxID);
    console.log("Populating latest task form... " + taskID);
}

function goToTask($taskID) {
    console.log("Opening task... " + taskID);
}

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
            window.location.href = `${BASE_PATH}/dashboard`;
        }
    } catch (error) {
        console.error(error.message);
        throw error;
    }
}

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
            window.location.href = `${BASE_PATH}/dashboard`;
        }
    } catch (error) {
        console.error(error.message);
        throw error;
    }
}

async function getCustomersDash() {
    const url = `${BASE_PATH}/dashboard/cxTable`;

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

async function getTaskDash() {
    const url = `${BASE_PATH}/dashboard/taskTable`;

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

async function getChartData() {
    const url = `${BASE_PATH}/dashboard/taskChart`;

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
    let pelangganSpinnerTable = document.getElementById('pelanggan-spinner-dashboard');
    let taskSpinnerTable = document.getElementById('task-spinner-dashboard');

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

        if (!/[!@#$%^&*(),.?":{}|<>]/.test(passwordVerify)) {
            showAlert('Password must contain at least one special character!');
            document.getElementById('user-password-add').value = '';
            document.getElementById('user-password-confirm-add').value = '';
            return;
        }

        if (/\s/.test(passwordVerify)) {
            showAlert('Password cannot contain spaces!');
            document.getElementById('user-password-add').value = '';
            document.getElementById('user-password-confirm-add').value = '';
            return;
        }

        if (passwordVerify !== passwordConfirm) {
            showAlert('Password didn\'t match! Try again.');
            document.getElementById('user-password-confirm-add').value = '';
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


    try {
        tasks = await getTaskDash();
        customers = await getCustomersDash();
        chart = await getChartData();
        
        taskData = tasks.map(task => [
            task.t_bike,
            task.t_plate,
            task.t_serviceStatus === "Pending" ? `<span class="badge bg-warning text-dark">${task.t_serviceStatus}</span>`
            : task.t_serviceStatus === "Ongoing" ? `<span class="badge bg-primary">${task.t_serviceStatus}</span>`
            : `<span class="badge bg-success">${task.t_serviceStatus}</span>`,
            `<a href="${BASE_PATH}/task/fetch/${task.t_id}" class="user_button_dash btn btn-primary btn-sm rounded-4 shadow">Edit</a>`,
        ]);
        
        cxData = customers.map(cx => [
            cx.c_name,
            cx.c_email,
            cx.c_total_task,
            `
            <a href="${BASE_PATH}/customer/detail/${cx.c_id}" type="button" class="user_button_dash btn btn-warning btn-sm w-auto rounded-4 shadow">Go to</a>
            `
        ]);

        // line data
        var chLineData1 = {
            labels: chart.latestPaid.map(row => row.update_date),
            datasets: [{
                label: 'Sales (RM)',
                data: chart.latestPaid.map(row => row.total_profit),
                fill: true,
                borderColor: 'rgb(82, 67, 255)',
                backgroundColor: 'rgb(135, 124, 255)',
                tension: 0.1
            }]
        };

        var chLine1 = document.getElementById("chLine1")
        if (chLine1) {
            new Chart(chLine1, {
                type: 'line',
                data: chLineData1,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 14,
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: {
                                    size: 14,
                                },
                            },
                        },
                        y: {
                            ticks: {
                                font: {
                                    size: 14,
                                },
                            },
                        }
                    }
                }
            });
        }

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
            cutout: '25%',
        };
    
        // donut data
        var chDonutData1 = {
            labels: Object.keys(chart.revenueByService),
            datasets: [
                {
                    backgroundColor: ['Purple', 'RebeccaPurple', 'Plum'],
                    borderWidth: 5,
                    data: Object.values(chart.revenueByService)
                }
            ]
        };

        var chDonutData2 = {
            labels: Object.keys(chart.customerByPlatform),
            datasets: [
                {
                    backgroundColor: ['ForestGreen','DarkViolet','DodgerBlue','Black','GainsBoro'],
                    borderWidth: 5,
                    data: Object.values(chart.customerByPlatform)
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
    
        var chDonut2 = document.getElementById("chDonut2");
        if (chDonut2) {
            new Chart(chDonut2, {
                type: 'pie',
                data: chDonutData2,
                options: donutOptions
            });
        }
    } catch (error) {
        console.error("Failed fetching customers on main!", error); 
    } finally {
        pelangganSpinnerTable.setAttribute("class","d-none");
        taskSpinnerTable.setAttribute("class","d-none");
    }

    let tableTask = new TableClass('table-task', 5);
    tableTask.setColumnHeader(['Type','Plate','Status','Detail']);
    tableTask.setPagination(false);
    tableTask.setFontSizeRow(0.90);
    tableTask.load(taskData);
    
    let tableCx = new TableClass('table-cx', 5);
    tableCx.setColumnHeader(['Name','Telephone','Total Task','Overview']);
    tableCx.setPagination(false);
    tableCx.setFontSizeRow(0.90);
    tableCx.load(cxData)
})
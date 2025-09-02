async function getStatisticData() {
    const url = `${BASE_PATH}/statistic/fetch`;

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

let tablePlatform;
let tableMotorcyle;
let tableBarang;

document.addEventListener("DOMContentLoaded", async () => {
    try {
        statistic = await getStatisticData();

        console.log(statistic.customerByPlatform);

        platform = statistic.customerByPlatform.map(row => [
            row.c_platform,
            parseInt(row.total)
        ]);

        motor = statistic.usedMotorcycle.map(row => [
            row.t_bike,
            parseInt(row.total)
        ]);

        barang = statistic.usedBarang.map(row => [
            row.ti_name,
            parseInt(row.total_quantity)
        ]);

        tablePlatform = new TableClass('table-platform', 5);   //set kan berapa data nak keluar
        tablePlatform.setPagination(false);
        tablePlatform.setFontSizeCol(0.9);
        tablePlatform.setFontSizeRow(0.85);
        tablePlatform.setColumnHeader(["Platform", "Bilangan"]);
        tablePlatform.load(platform);

        tableMotorcyle = new TableClass('table-motosikal', 5);   //set kan berapa data nak keluar
        tableMotorcyle.setPagination(false);
        tableMotorcyle.setFontSizeCol(0.9);
        tableMotorcyle.setFontSizeRow(0.85);
        tableMotorcyle.setColumnHeader(["Jenis", "Bilangan"]);
        tableMotorcyle.load(motor);

        tableBarang = new TableClass('table-barang', 5);   //set kan berapa data nak keluar
        tableBarang.setPagination(false);
        tableBarang.setFontSizeCol(0.9);
        tableBarang.setFontSizeRow(0.85);
        tableBarang.setColumnHeader(["Nama", "Bilangan"]);
        tableBarang.load(barang);

        //design chart
        var chartOptions = {
            responsive: true,
            maintainAspectRatio: true,

            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 25,
                        pointStyle: 'circle',
                        usePointStyle: true,
                        font: {
                            size: window.innerWidth < 600 ? 12 : 16,
                        }
                    }
                }
            }
        };

        //data
        var chartData1 = {
            labels: ['Major', 'Minor', 'Modify'],  
            datasets: [
                {
                    backgroundColor: ['Aquamarine', 'DarkCyan', 'Cyan'],
                    borderWidth: 5,
                    data: statistic.serviceCount.map(row => row.total) 
                }
            ]
        };

        var chartData2 = {
            
            labels: ['Pending', 'Ongoing', 'Completed'],
            datasets: [
                {
                    backgroundColor: ['DarkMagenta', 'DarkOrchid', 'DarkSlateBlue'],
                    borderWidth: 5,
                    data: statistic.statusCount.map(row => row.total)  
                }
            ]
        };

        var chartData3 = {
            labels: ['Paid', 'Unpaid'],  
            datasets: [
                {
                    backgroundColor: ['PaleVioletRed', 'Plum'],
                    borderWidth: 5,
                    data: statistic.paidStatusCount.map(row => row.total) 
                }
            ]
        };

	var chartData4 = {
            labels: ['Admin', 'Worker'],  
            datasets: [
                {
                    backgroundColor: ['RoyalBlue', 'SkyBlue'],
                    borderWidth: 5,
                    data: statistic.totalUsers.map(row => row.total) 
                }
            ]
        };

        //hasilchart
        var chart1 = document.getElementById("chartbarangan");
        if (chart1) {

            new Chart(chart1, {
                type: 'pie',
                data: chartData1,
                options: chartOptions
            });
        }

        var chart2 = document.getElementById("chartservis");
        if (chart2) {

            new Chart(chart2, {
                type: 'pie',
                data: chartData2,
                options: chartOptions
            });
        }

        var chart3 = document.getElementById("chartpayment");
        if (chart3) {

            new Chart(chart3, {
                type: 'pie',
                data: chartData3,
                options: chartOptions
            });
        }
	var chart4 = document.getElementById("chartpalsu");
        if (chart4) {

            new Chart(chart4, {
                type: 'pie',
                data: chartData4,
                options: chartOptions
            });
        }


        
    } catch (error) {
        console.error("Failed fetching data on main!", error);
    }
})
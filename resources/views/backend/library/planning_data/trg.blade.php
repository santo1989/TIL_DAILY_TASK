<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Production Data Graphs
    </x-slot>
    <h1 class="h3 mb-3 text-center">Production Data Graphs </h1>


    <section class="content">
        <div class="container-fluid">

            @if (session('message'))
                <div class="alert alert-success">
                    <span class="close" data-dismiss="alert">&times;</span>
                    <strong>{{ session('message') }}.</strong>
                </div>
            @endif

            <div class="row justify-content-between">
                <!-- /.col 1 start-->
                <div class="col-md-4 col-sm-12 p-1">
                    <div class="card">
                        <div class="card-header">
                            Line Total Bar
                        </div>
                        <!-- /.card-header -->

                        <!-- card-body -->
                        <div class="card-body" style="height: 325px">

                            <canvas id="rejectionBarChart"></canvas>


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- /.card -->
                </div>
                <!-- /.col 1 end-->
                <div class="col-md-4 col-sm-12 p-1">
                    <div class="card">
                        <div class="card-header">
                            Most Alter On Line
                        </div>
                        <!-- /.card-header -->

                        <!-- card-body -->
                        <div class="card-body" style="height: 325px">

                            <div style="display: flex;">
                                <!-- Left 50% for Labels -->
                                <div style="width: 50%; padding-right: 10px;">
                                    <ul id="alterationLabelsList"
                                        style="overflow-y: auto; max-height: 300px; list-style: none; padding: 0;"></ul>
                                </div>

                                <!-- Right 50% for Doughnut Chart -->
                                <div style="width: 50%;">
                                    <canvas id="alterationChart"></canvas>
                                </div>
                            </div>



                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- /.card -->
                </div>
                <!-- /.col 2 end-->
                <div class="col-md-4 col-sm-12 p-1">
                    <div class="card">
                        <div class="card-header">
                            Most Reject On Line
                        </div>
                        <!-- /.card-header -->

                        <!-- card-body -->
                        <div class="card-body" style="height: 325px">
                            <div style="display: flex;">
                                <!-- Left 50% for Labels with Colors for Rejection Data -->
                                <div style="width: 50%; padding-right: 10px;">
                                    <ul id="rejectionLabelsList"
                                        style="overflow-y: auto; max-height: 300px; list-style: none; padding: 0;"></ul>
                                </div>

                                <!-- Right 50% for Pie Chart -->
                                <div style="width: 50%;">
                                    <canvas id="RJChart"></canvas>
                                </div>
                            </div>






                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- /.card -->
                </div>
                <!-- /.col 3 end-->
                <div class="col-md-4 col-sm-12 p-1">
                    <div class="card">
                        <div class="card-header">
                            Buyer Ways Statistics Bar
                        </div>
                        <!-- /.card-header -->

                        <!-- card-body -->
                        <div class="card-body" style="height: 325px">

                            <canvas id="buyerChart"></canvas>


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- /.card -->
                </div>
                <!-- /.col 4 end-->
                <div class="col-md-4 col-sm-12 p-1">
                    <div class="card">
                        <div class="card-header">
                            Item Ways Statistics Bar
                        </div>
                        <!-- /.card-header -->

                        <!-- card-body -->
                        <div class="card-body" style="height: 325px">

                            <canvas id="itemChart"></canvas>


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- /.card -->
                </div>
                <!-- /.col 5 end-->
                <div class="col-md-4 col-sm-12 p-1">
                    <div class="card">
                        <div class="card-header">
                            Day Ways Statistics Bar
                        </div>
                        <!-- /.card-header -->

                        <!-- card-body -->
                        <div class="card-body" style="height: 325px">

                            <canvas id="dayChart"></canvas>


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <!-- /.card -->
                </div>
                <!-- /.col 6 end-->
            </div>
            <!-- /.row -->
            <a href="{{ route('planning_data.index') }}" class="btn btn-outline-success"><i
                    class="fas fa-arrow-left"></i>
                Back</a>
        </div>
        <!-- /.container-fluid -->
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Chart.js Date Adapter for date-fns -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
    <script>
        // Helper function to generate random unique colors for the charts
        function randomUniqueColorGenerator(num) {
            const colors = [];
            for (let i = 0; i < num; i++) {
                colors.push('rgba(' + Math.floor(Math.random() * 255) + ',' + Math.floor(Math.random() * 255) + ',' +
                    Math.floor(Math.random() * 255) + ', 0.9)');
            }
            return colors;
        }

        // Prepare the bar chart data for total production by line
        const ctxBar = document.getElementById('rejectionBarChart').getContext('2d');
        const totalData = @json($totalData);

        const totalLabels = totalData.map(item => item.line);
        const totalDataPoints = totalData.map(item => item.total_production);

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: totalLabels,
                datasets: [{
                    label: 'Total Production per Line',
                    data: totalDataPoints,
                    backgroundColor: randomUniqueColorGenerator(totalLabels.length),
                    borderColor: randomUniqueColorGenerator(totalLabels.length),
                    borderWidth: 1
                }]
            },
            //     options: {
            //         responsive: true,
            //         plugins: {
            //             legend: {
            //                 position: 'right',
            //             },
            //             tooltip: {
            //                 enabled: true
            //             }
            //         },
            //         scales: {
            //             y: {
            //                 beginAtZero: true
            //             }
            //         }
            //     }
            // });
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        enabled: true
                    },
                    datalabels: {
                        anchor: 'end', // Position at the top of the bar
                        align: 'start', // Align labels at the start (top)
                        color: '#000', // Set label color
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        // Doughnut Chart: Alterations by Part Name starts here


        // Doughnut Chart: Alterations by Part Name
        const ctxDoughnutAlter = document.getElementById('alterationChart').getContext('2d');
        const alterationData = @json($alterationData);

        const alterationLabels = [
            'Uneven Shape', 'Broken Stitch', 'Dirty Mark', 'Oil Stain', 'Down Stitch',
            'Hiking', 'Improper Tuck', 'Label Alter', 'Needle Mark Hole', 'Open Seam',
            'Skip Stitch', 'Pleat', 'Sleeve Shoulder Up/Down', 'Puckering', 'Raw Edge',
            'Shading', 'Uncut Thread', 'Others'
        ];

        const alterationDataPoints = alterationLabels.map(label =>
            alterationData.reduce((sum, item) => sum + (item[`total_${label.replace(/ /g, '_')}`] || 0), 0)
        );

        // Generate random colors for each label and chart segment
        const backgroundColors = randomUniqueColorGenerator(alterationLabels.length);

        // Populate the left 50% with labels and totals with colors
        const labelList = document.getElementById('alterationLabelsList');
        alterationLabels.forEach((label, index) => {
            const listItem = document.createElement('ol');
            listItem.innerHTML =
                `<span style="display:inline-block; width: 20px; height: 20px; background-color: ${backgroundColors[index]}; margin-right: 10px;"></span> ${label}: ${alterationDataPoints[index]}`;
            labelList.appendChild(listItem);
        });

        // Create the Doughnut Chart
        new Chart(ctxDoughnutAlter, {
            type: 'doughnut',
            data: {
                labels: alterationLabels,
                datasets: [{
                    label: 'Alterations by Part Name',
                    data: alterationDataPoints,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Hide the default legend because labels are in the left column
                    },
                    tooltip: {
                        enabled: true
                    },
                    datalabels: {
                        color: '#fff',
                        formatter: function(value) {
                            return value; // Display the number inside the chart segment
                        }
                    }
                }
            }
        });



        // Doughnut Chart: Rejections by Part Name starts here
    </script>

    <script>
        const buyerChartCtx = document.getElementById('buyerChart').getContext('2d');
        const buyerChartData = @json($buyerdata);

        const buyerChartLabels = buyerChartData.map(item => item.buyer);
        const buyerChartTotalProduction = buyerChartData.map(item => item.total_production);
        const buyerChartTotalReject = buyerChartData.map(item => item.total_reject);
        const buyerChartTotalAlter = buyerChartData.map(item => item.total_alter);

        const buyerChart = new Chart(buyerChartCtx, {
            type: 'bar',
            data: {
                labels: buyerChartLabels,
                datasets: [{
                        label: 'Total Production',
                        data: buyerChartTotalProduction,
                        backgroundColor: randomUniqueColorGenerator(buyerChartLabels.length),
                        borderColor: randomUniqueColorGenerator(buyerChartLabels.length),
                        borderWidth: 1
                    },
                    {
                        label: 'Total Reject',
                        data: buyerChartTotalReject,
                        backgroundColor: randomUniqueColorGenerator(buyerChartLabels.length),
                        borderColor: randomUniqueColorGenerator(buyerChartLabels.length),
                        borderWidth: 1
                    },
                    {
                        label: 'Total Alter',
                        data: buyerChartTotalAlter,
                        backgroundColor: randomUniqueColorGenerator(buyerChartLabels.length),
                        borderColor: randomUniqueColorGenerator(buyerChartLabels.length),
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        const itemChartCtx = document.getElementById('itemChart').getContext('2d');
        const itemChartData = @json($itemdata);

        const itemChartLabels = itemChartData.map(item => item.item);
        const itemChartTotalProduction = itemChartData.map(item => item.total_production);
        const itemChartTotalReject = itemChartData.map(item => item.total_reject);
        const itemChartTotalAlter = itemChartData.map(item => item.total_alter);

        const itemChart = new Chart(itemChartCtx, {
            type: 'bar',
            data: {
                labels: itemChartLabels,
                datasets: [{
                        label: 'Total Production',
                        data: itemChartTotalProduction,
                        backgroundColor: randomUniqueColorGenerator(itemChartLabels.length),
                        borderColor: randomUniqueColorGenerator(itemChartLabels.length),
                        borderWidth: 1
                    },
                    {
                        label: 'Total Reject',
                        data: itemChartTotalReject,
                        backgroundColor: randomUniqueColorGenerator(itemChartLabels.length),
                        borderColor: randomUniqueColorGenerator(itemChartLabels.length),
                        borderWidth: 1
                    },
                    {
                        label: 'Total Alter',
                        data: itemChartTotalAlter,
                        backgroundColor: randomUniqueColorGenerator(itemChartLabels.length),
                        borderColor: randomUniqueColorGenerator(itemChartLabels.length),
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>


    <script>
        const dayChartCtx = document.getElementById('dayChart').getContext('2d');
        const dayChartData = @json($daydata);

        // Check the data format
        console.log(dayChartData);

        const dayChartLabels = dayChartData.map(item => item.date);
        const dayChartTotalProduction = dayChartData.map(item => item.total_production);
        const dayChartTotalReject = dayChartData.map(item => item.total_reject);
        const dayChartTotalAlter = dayChartData.map(item => item.total_alter);

        // Create the chart
        const dayChart = new Chart(dayChartCtx, {
            type: 'line',
            data: {
                labels: dayChartLabels,
                datasets: [
                    // {
                    //         label: 'Total Production',
                    //         data: dayChartTotalProduction,
                    //         backgroundColor: randomUniqueColorGenerator(dayChartLabels.length),
                    //         borderColor: randomUniqueColorGenerator(dayChartLabels.length),
                    //         borderWidth: 1,
                    //         fill: false
                    //     },
                    {
                        label: 'Total Reject',
                        data: dayChartTotalReject,
                        backgroundColor: randomUniqueColorGenerator(dayChartLabels.length),
                        borderColor: randomUniqueColorGenerator(dayChartLabels.length),
                        borderWidth: 1,
                        fill: false
                    },
                    {
                        label: 'Total Alter',
                        data: dayChartTotalAlter,
                        backgroundColor: randomUniqueColorGenerator(dayChartLabels.length),
                        borderColor: randomUniqueColorGenerator(dayChartLabels.length),
                        borderWidth: 1,
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        },
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Counts'
                        }
                    }
                }
            }
        });
    </script>
    <script>
        // Doughnut Chart: Rejections by Part Name
        const ctxDoughnutReject = document.getElementById('RJChart').getContext('2d');
        const rejectionData = @json($rejectionData[0]); // Single result set, so access the first element

        const rejectionLabels = [
            'Fabric Hole', 'Scissor/Cutter Cut', 'Needle Hole', 'Print/Emb Damage',
            'Shading', 'Others'
        ];

        const rejectionDataPoints = [
            rejectionData.total_reject_Fabric_hole,
            rejectionData.total_reject_scissor_cuttar_cut,
            rejectionData.total_reject_Needle_hole,
            rejectionData.total_reject_Print_emb_damage,
            rejectionData.total_reject_Shading,
            rejectionData.total_reject_Others
        ];

        // Generate random colors for each rejection label
        const rejectionBackgroundColors = randomUniqueColorGenerator(rejectionLabels.length);

        // Create the Pie Chart for Rejections
        new Chart(ctxDoughnutReject, {
            type: 'pie',
            data: {
                labels: rejectionLabels,
                datasets: [{
                    label: 'Rejections by Part Name',
                    data: rejectionDataPoints,
                    backgroundColor: rejectionBackgroundColors,
                    borderColor: rejectionBackgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Hide the chart legend since we are using custom labels
                    },
                    tooltip: {
                        enabled: true
                    },
                    datalabels: {
                        color: '#fff',
                        formatter: function(value) {
                            return value; // Display the number inside the chart segment
                        }
                    }
                }
            }
        });

        // Populate the left 50% with labels and totals with colors
        const rejectionLabelsList = document.getElementById('rejectionLabelsList');
        rejectionLabels.forEach((label, index) => {
            const listItem = document.createElement('li'); // Changed from <ol> to <li>
            listItem.style.display = 'flex';
            listItem.style.alignItems = 'center';
            listItem.innerHTML =
                `<span style="display:inline-block; width: 20px; height: 20px; background-color: ${rejectionBackgroundColors[index]}; margin-right: 10px;"></span> ${label}: ${rejectionDataPoints[index]}`;
            rejectionLabelsList.appendChild(listItem);
        });
    </script>
    <style>
        .color-box {
            width: 20px;
            /* Adjust size as needed */
            height: 20px;
            /* Adjust size as needed */
            display: inline-block;
            margin-right: 8px;
        }
    </style>
    <!--Relaod all graph every 20 seconds for update real time data from backend-->
    <script>
        setInterval(() => {
            window.location.reload();
        }, 20000);
    </script>
    <style>
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;

        }
    </style>
    <!-- when page load in the tab then 2 card show in the each row -->
    <style>
        @media (min-width: 768px) {
            .col-md-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
        }
    </style>

</x-backend.layouts.master>

<?php

require '../../QUTGo/setup.php';

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>QUT Go - Dashboard - Statistics</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body>


    <div class="wrapper">
        <!-- Sidebar Holder -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>QUT Go
                    <br>Dashboard</h3>
            </div>

            <ul class="list-unstyled components">
                <p>Welcome Cameron Cross</p>
                <li class="active">
                    <a href="#">Home</a>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Dashboard</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="/dashboard/challenges">Your Challenges</a>
                        </li>
                        <li>
                            <a href="/dashboard/statistics">Statistics</a>
                        </li>
                        <li>
                            <a href="/dashboard/trends">Weekly Trends</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">Contact Us</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="#" class="download">Download QUT Go</a>
                </li>
                <li>
                    <a href="#" class="article">Report an Issue</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content Holder -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="navbar-btn">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/dashboard">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Conatct Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Log In</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>


            <!-- Graphs and Stuff -->
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <canvas id="lineChart"></canvas>
                </div>
                <div class="col-md-4 col-sm-6">
                    <canvas id="radarChart"></canvas>
                </div>
                <div class="col-12">
                    <canvas id="stackedChart"></canvas>
                </div>
            </div>



        </div>
    </div>

    <?php 

    function getLastWeekDates()
    {
        $lastWeek = array();
    
        $prevMon = abs(strtotime("previous monday"));
        $currentDate = abs(strtotime("today"));
        $seconds = 86400; //86400 seconds in a day
    
        $dayDiff = ceil( ($currentDate-$prevMon)/$seconds ); 
    
        if( $dayDiff < 7 )
        {
            $dayDiff += 1; //if it's monday the difference will be 0, thus add 1 to it
            $prevMon = strtotime( "previous monday", strtotime("-$dayDiff day") );
        }
    
        $prevMon = date("Y-m-d",$prevMon);
    
        // create the dates from Monday to Sunday
        for($i=0; $i<7; $i++)
        {
            $d = date("Y-m-d", strtotime( $prevMon." + $i day") );
            $lastWeek[]=$d;
        }
    
        return $lastWeek;
    }

    ?>


    <?php

    $sql = "SELECT * FROM step WHERE user_id = 262";
    $result = $connect->query($sql);

    $lastWeek = getLastWeekDates();

    ?>


    <!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <script src="/assets/js/jQuery-3.3.1.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/Chart.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script>
        function stepNumber(min, max){
            return Math.floor((Math.random() * max) + min);
        }

        var ctx = document.getElementById("lineChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Mon", "Tues", "Wed", "Thurs", "Fri", "Sat", "Sun"],
                datasets: [{
                    label: 'Last Week',
                    data: [stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000)],
                    borderColor: [
                        'rgba(8,217,214,1)'
                    ],
                    backgroundColor: 'rgba(8,217,214,0.1)',
                    borderWidth: 1
                }, {
                    label: 'This Week',
                    data: [
                            <?php
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        if(in_array($row['date'], $lastWeek)){
                                            echo $row['steps'];
                                            // switch (date('D', strtotime($row['date']))) {
                                            //     case 'Monday':
                                            //         echo $row['steps'] . ',';
                                            //         break;
                                            //     case 'Tuesday':
                                            //         echo $row['steps'] . ',';
                                            //         break;
                                            //     case 'Wednesday':
                                            //         echo $row['steps'] . ',';
                                            //         break;
                                            //     case 'Thursday':
                                            //         echo $row['steps'] . ',';
                                            //         break;
                                            //     case 'Friday':
                                            //         echo $row['steps'] . ',';
                                            //         break;
                                            //     case 'Saturday':
                                            //         echo $row['steps'] . ',';
                                            //         break;
                                            //     case 'Sunday':
                                            //         echo $row['steps'] . ',';
                                            //         break;
                                            //     default;
                                            //         echo '0,';
                                            //         break;
                                        }
                                    }
                                }
                            ?>

                        ],
                    borderColor: [
                        'rgba(255,46,99,1)'
                    ],
                    backgroundColor: 'rgba(255,46,99,0.1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var ctx = document.getElementById("radarChart").getContext('2d');
        var myRadarChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Last Year",
                    data: [stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000)],
                    borderColor: ['rgba(247,73,6,1)'],
                    backgroundColor: ['rgba(247,73,6,0.1)'],
                    borderWidth: 1
                }, {
                    label: "Current Year",
                    data: [stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000), stepNumber(20000,450000)],
                    borderColor: ['rgba(85,78,68,1)'],
                    backgroundColor: ['rgba(85,78,68,0.1)'],
                    borderWidth: 1
                }]
            }
        });

        var ctx = document.getElementById("stackedChart").getContext("2d");
        var stackedLine = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Mon", "Tues", "Wed", "Thurs", "Fri", "Sat", "Sun"],
                datasets: [{
                    label: 'You',
                    data: [stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000)],
                    borderColor: [
                        'rgba(255,116,115,1)'
                    ],
                    backgroundColor: 'rgba(255,116,115,0.1)',
                    borderWidth: 1
                }, {
                    label: 'Jason',
                    data: [stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000)],
                    borderColor: [
                        'rgba(255,201,82,1)'
                    ],
                    backgroundColor: 'rgba(255,201,82,0.1)',
                    borderWidth: 1
                }, {
                    label: 'Luke',
                    data: [stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000)],
                    borderColor: [
                        'rgba(71,184,224,1)'
                    ],
                    backgroundColor: 'rgba(71,184,224,0.1)',
                    borderWidth: 1
                }, {
                    label: 'Daniel',
                    data: [stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000), stepNumber(2000,45000)],
                    borderColor: [
                        'rgba(52,49,76,1)'
                    ],
                    backgroundColor: 'rgba(52,49,76,0.1)',
                    borderWidth: 1
                }]
            },
            options: {
                tooltips: {
                    mode: 'index',
                    intersect: true
                },
                responsive: true
            }
        });
    </script>

    <?php $connect->close(); ?>
</body>

</html>
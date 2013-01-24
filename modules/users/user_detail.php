<?php
    //check for login
    session_start();
    session_write_close();
    if(!isset($_SESSION['valid']) || !$_SESSION['valid']){
        echo "Please log in.";
        die();
    }

    echo "<h1>Users</h1>";
    
      include "../../include/common.php";
    connect_to_mysql_database(false);

    //get merchant details
    $id = sanitize($_GET['id']);

    $query = "SELECT ID
		,User
		,Name
		,Email
		,DDA
		,Profile
            FROM CMC.CMC_Users";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    $id = $row['ID'];
    $user = $row['User'];
    $email = $row['Email'];
    $profile = $row['profile'];
    $dda = $row['DDA'];
 
?>

<script type='text/javascript' src='../../include/HighCharts/highcharts.js'></script>
<script type='text/javascript' src='../../include/HighCharts/modules/exporting.js'></script>
<script type="text/javascript">
     var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart1',
                zoomType: 'xy'
            },
            title: {
                text: 'Average Monthly Temperature and Rainfall in Tokyo'
            },
            subtitle: {
                text: 'Source: WorldClimate.com'
            },
            xAxis: [{
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    formatter: function() {
                        return this.value +'°C';
                    },
                    style: {
                        color: '#89A54E'
                    }
                },
                title: {
                    text: 'Temperature',
                    style: {
                        color: '#89A54E'
                    }
                }
            }, { // Secondary yAxis
                title: {
                    text: 'Rainfall',
                    style: {
                        color: '#4572A7'
                    }
                },
                labels: {
                    formatter: function() {
                        return this.value +' mm';
                    },
                    style: {
                        color: '#4572A7'
                    }
                },
                opposite: true
            }],
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +': '+ this.y +
                        (this.series.name == 'Rainfall' ? ' mm' : '°C');
                }
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 100,
                floating: true,
                backgroundColor: '#FFFFFF'
            },
            series: [{
                name: 'Rainfall',
                color: '#4572A7',
                type: 'column',
                yAxis: 1,
                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
    
            }, {
                name: 'Temperature',
                color: '#89A54E',
                type: 'spline',
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
            }]
        });
    });
</script>

<table>
    <tbody>
        <tr>
            <td colspan="2">
                <h1>Employee Detail for <?php echo $name; ?></h1>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id='button_row'>
                    <button id='add_case'>Add Case</button>
                <?php if($_SESSION['department'] == '10' || $_SESSION['department'] == '13'): ?>
                    <button id='edit_employee'>Edit</button>
                <?php endif; ?>
                    <button id='print_employee'>Print</button>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <fieldset>
                    <legend>Employee Detail</legend>
                    <table id='detail_table'>
                        <tbody>
                            <tr>
                                <th width='20%'>Employee Name</th>
                                <td width='30%'><?php echo $name; ?></td>
                                <th width='20%'>User</th>
                                <td width='30%'><?php echo $user; ?></td>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <td><?php echo $id; ?></td>
                                <th>Account Number</th>
                                <td><?php echo $dda; ?></td>
                            </tr>
                            <tr>
                                <th>Profile</th>
                                <td><?php echo $profile; ?></td>
                                <th>Email</th>
                                <td><?php echo $email; ?></td>
                            </tr>
                            <tr>
                                <th></th>
                                <td</td>
                                <th></th>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td>
                <div id="status">
                    <fieldset>
                        <legend>Status</legend>
                        <input type="radio" name="status" value="Denied" disabled="disabled">Denied<br/>
                        <input type="radio" name="status" value="Received" disabled="disabled">Received<br/>
                        <input type="radio" name="status" value="Pending" disabled="disabled">Pending<br/>
                        <input type="radio" name="status" value="Accepted" disabled="disabled">Accepted<br/>
                        <input type="radio" name="status" value="Boarded"  disabled="disabled" checked>Boarded<br/>
                        <input type="radio" name="status" value="Live" disabled="disabled">Live<br/>
                    </fieldset>
                </div>
            </td>
            <td>
                <div id="cases">
                    <fieldset>
                        <legend>Cases</legend>
                <?php include "../cases/case_view.php"; ?>
                    </fieldset>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="chart1" class="chart" style="height:400px; width:950px;"></div>
            </td>
        </tr>
    </tbody>
</table>
?>

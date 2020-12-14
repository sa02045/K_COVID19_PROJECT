<?php
    $link = mysqli_connect("localhost","root","password", "k_covid19");
    if( $link === false )
    {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    echo "Coneect Successfully. Host info: " . mysqli_get_host_info($link) . "\n";
?>
<style>
    table {
        width: 100%;
        border: 1px solid #444444;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #444444;
    }
</style>
<body>
    <h1 style="text-align:center"> 데이터베이스 팀 프로젝트 3주차 </h1>
    <hr style = "border : 5px solid yellowgreen">
    avg_temp를 선택하세요
    <form method="POST" action="./weather2.php">
        <select name = 'avg_temp'>
            <option value=0> avg_temp <= 0 </option>
            <option value=10> 0 < avg_temp <= 10 </option>
            <option value=20> 10 < avg_temp <= 20</option>
            <option value=30> 20 < avg_temp <= 30</option>
            <option value='NULL' selected> avg_temp = NULL </option>
        </select>
    <input type="submit" value = "load"> 
    <?php
        if(!isset($_POST['avg_temp']))
        {
            $max='NULL';
        }
        else{
            $max = $_POST['avg_temp'];
        }
        if($max!='NULL'){
            $min = $max - 10;
            $sql="select count(*) as num from weatherinfo where avg_temp <= {$max} and avg_temp > {$min}";
        }
        else {
            $sql="select count(*) as num from weatherinfo where avg_temp is NULL";
        }       
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_assoc($result);
    ?>

<p><h3>Weather Info table (Currently <?php echo $data['num']; ?>) weathers in database in database which avg_temp is <?php if($max!='NULL') {echo 'between '; echo $min; echo ' and '; echo $max;} else {echo $max;}?> </h3></p>

    <table cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Region_code</th>
            <th>Province</th>
            <th>Wdate</th>
            <th>Avg_temp</th>
            <th>Min_temp</th>
            <th>Max_temp</th>
        </tr>
        </thead>
        <tbody>
            <?php
                if($max!='NULL')
                    $sql = "select * from weatherinfo where avg_temp <= {$max} and avg_temp > {$min}";
                else      
                    $sql= "select * from weatherinfo where avg_temp is NULL";
                $result = mysqli_query($link,$sql);
                while( $row = mysqli_fetch_assoc($result)  )
                {
                    print "<tr>";
                    foreach($row as $key => $val)
                    {
                        print "<td>" . $val . "</td>";
                    }
                    print "</tr>";
                }
            ?>
            
        </tbody>
    </table>


</body>
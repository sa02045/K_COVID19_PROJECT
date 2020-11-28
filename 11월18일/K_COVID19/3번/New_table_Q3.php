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
    <?php
        $sql="select count(*) as num from patientinfo where age IS NOT NULL";
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_assoc($result);
    ?>
    <p><h3>Number of patients and deaths by age(execept NULL) group table (Total Patient <?php echo $data['num']; ?>)</h3></p>

    <table cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Age</th>
            <th># of Patient</th>
            <th># of Dece_patient</th>
        </tr>
        </thead>
        <tbody>
            <?php
                $sql = "select age ,count(*), count(deceased_date)
                from patientinfo
                where age is not NULL
                group by age
                order by FIELD (age, '0s', '10s', '20s', '30s', '40s', '50s', '60s', '70s', '80s', '90s', '100s');";
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
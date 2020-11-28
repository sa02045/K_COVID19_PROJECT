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
        $sql="select count(*) as num from regioninfo";
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_assoc($result);
    ?>
    <p>
        <h3>Region Info table (Currently <?php echo $data['num']; ?>) regions in database </h3>
    </p>

    <table cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Region_Code</th>
            <th>Province</th>
            <th>City</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Elementary_school_Count</th>
            <th>Kindergarten_Count</th>
            <th>University_Count</th>
            <th>Academy_Ratio</th>
            <th>Elderly_population_Ratio</th>
            <th>Elderly_alone_Ratio</th>
            <th>Nursing_home_Count</th>
        </tr>
        </thead>
        <tbody>
            <?php
                $sql = "select * from regioninfo";
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
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
    집단 감염 여부를 선택하세요
    <form method="POST" action="./case2.php">
        <select name = 'infection_group'>
            <option value="1">TRUE</option>
            <option value="0">FALSE</option>
        </select>
    <input type="submit" value = "load"> 
    <?php
        if(!isset($_POST['infection_group']))
        {
            $value='1';
        }
        else{
            $value = $_POST['infection_group'];
        }
        $sql="select count(*) as num from caseinfo where infection_group = '{$value}'";
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_assoc($result);
    ?>
    <p>
        <h3>Case Info table (Currently <?php echo $data['num']; ?>) cases in database cases in database which infection_group is <?php echo $value?> </h3>
    </p>

    <table cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Case_ID</th>
            <th>Province</th>
            <th>City</th>
            <th>Infection_group</th>
            <th>Infection_case</th>
            <th>Confirmed</th>
            <th>Latitude</th>
            <th>Longitude</th>
        </tr>
        </thead>
        <tbody>
            <?php
                $sql = "select * from caseinfo where infection_group = '{$value}'";
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
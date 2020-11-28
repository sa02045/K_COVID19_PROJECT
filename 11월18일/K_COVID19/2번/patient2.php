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
    <h1 style="text-align:center"> 데이터베이스 팀 프로젝트 3주차 예시 </h1>
    <hr style = "border : 5px solid yellowgreen">
    age를 선택하세요
    <form method="POST" action="./patient2.php">
        <select name = 'age'>
            <option value="0s">0s</option>
            <option value="10s">10s</option>
            <option value="20s">20s</option>
            <option value="30s">30s</option>
            <option value="40s">40s</option>
            <option value="50s">50s</option>
            <option value="60s">60s</option>
            <option value="70s">70s</option>
            <option value="80s">80s</option>
            <option value="90s">90s</option>
            <option value="100s">100s</option>
        </select>
    <input type="submit" value = "load"> 
    <?php
        if(!isset($_POST['age']))
        {
            $value='0s';
        }
        else{
            $value = $_POST['age'];
        }
        $sql="select count(*) as num from patientinfo where age = '{$value}'";
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_assoc($result);
    ?>
    <p>
        <h3>Patient Info table (Currently <?php echo $data['num']; ?>) patients in database which age is <?php echo $value; ?></h3>
    </p>

    <table cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Patient_ID</th>
            <th>Sex</th>
            <th>Age</th>
            <th>Country</th>
            <th>province</th>
            <th>City</th>
            <th>Infection_Case</th>
            <th>Infected_by</th>
            <th>contact_number</th>
            <th>symptom_onset_date</th>
            <th>confirmed_date</th>
            <th>released_date</th>
            <th>deceased_date</th>
            <th>state</th>
        </tr>
        </thead>
        <tbody>
            <?php
                $sql = "select * from patientinfo where age = '{$value}'";
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
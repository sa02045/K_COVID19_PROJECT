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
    
    <h1 style="text-align:center"> 데이터베이스 팀 프로젝트 4주차 </h1>
    <hr style = "border : 5px solid yellowgreen">
    
    <form method="POST" action="./patient3.php">
    Put Hospital_id : 
    <input type="text" name="hospital_id" />
    <input type="submit" value = "load">
    </form>
    <?php
        if(!isset($_POST['hospital_id']))
        {
            $value='ANY (select hospital_id from patientinfo where hospital_id IS NOT NULL)';
        }
        else{
            if($_POST['hospital_id'] != null)
                $value = $_POST['hospital_id'];
            else $value='ANY (select hospital_id from patientinfo where hospital_id IS NOT NULL)';
        }
        $sql="select count(*) as num from patientinfo where hospital_id = {$value}";
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_assoc($result);
    ?>
    <p>
        <h3>Hospital Info table (Currently <?php echo $data['num']; ?>) cases in database cases in database which hospital_id is <?php if ($value=="ANY (select hospital_id from patientinfo where hospital_id IS NOT NULL)") {echo ('1~43');} else {echo $value;}?> </h3>
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
            <th>Hospital_id</th>
        </tr>
        </thead>
      
        <tbody>
            <?php
                $sql = "select * from patientinfo where hospital_id = {$value}";
                $result = mysqli_query($link,$sql);
                $_COOKIE['varname'] = 3;

                while( $row = mysqli_fetch_assoc($result)  )
                {
                    print "<tr>";
                    foreach($row as $key => $val)
                    {
                        if($key=='hospital_id')
                        {
                            $sql1 = "select hospital_latitude,hospital_longitude from hospitalinfo where hospital_id ={$val}";
                            $result1 = mysqli_query($link, $sql1);
                            $data1 = mysqli_fetch_assoc($result1);
                            $latitude= $data1['hospital_latitude'];
                            $longitude = $data1['hospital_longitude'];
                            
                            print "<td>" . "<a href= 'index.php?latitude=$latitude&longitude=$longitude'> $val </a>" ."</td>"; // 하이퍼링크
                        }
                        else    print "<td>" . $val . "</td>";
                    }
                    print "</tr>";
                }
            ?>
            
        </tbody>
    </table>


</body>
<?php


	$servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "hrm_tis";


?>

<?php
            
            
            ?>

            <?php
			
				$conn = new mysqli($servername, $username, $password, $dbname);
				for($i=0;$i<3;$i++){
					if($i==0){
					try{
						$sql = "INSERT INTO shift (`inTime`, `outTime`, `shiftName`)SELECT '08:00:00','15:00:00', CONCAT(employeeinfo.firstName, '-M') 		
						FROM attemployeemap LEFT JOIN employeeinfo on employeeinfo.id= attemployeemap.employeeId WHERE attemployeemap.attDeviceUserId=167";
						$result = $conn->query($sql);
					}
					catch(DBException $ex){
						echo $ex;
					}
					}elseif($i==1){
						
						try{
						$sql = "INSERT INTO shift (`inTime`, `outTime`, `shiftName`)SELECT '15:00:00','23:00:00', CONCAT(employeeinfo.firstName, '-E')		
						FROM attemployeemap LEFT JOIN employeeinfo on employeeinfo.id= attemployeemap.employeeId WHERE attemployeemap.attDeviceUserId=167";
						$result = $conn->query($sql);
					}
					catch(DBException $ex){
						echo $ex;
					}
						
					}elseif($i==2){
						
						try{
						$sql = "INSERT INTO shift (`inTime`, `outTime`, `shiftName`)SELECT '23:00:00','08:00:00', CONCAT(employeeinfo.firstName, '-N')		
						FROM attemployeemap LEFT JOIN employeeinfo on employeeinfo.id= attemployeemap.employeeId WHERE attemployeemap.attDeviceUserId=167";
						$result = $conn->query($sql);
					}
					catch(DBException $ex){
						echo $ex;
					}
						
					}
					
				}
					
			
				
      
			?>


<?php



?>

<?php

	header("Content-type: application/json");

	include "location.php";

	$action = (string)$_POST["action"];

	//$output = []; $output_len = 0 ;

	global $output, $output_len ;

	//$file_header = array(array('Date and Time','Location','Signalments','Clinical Signs','Diseases'));

	//update_file($file_header);

	$species=(string)$_POST["species_list"];
	$gender=(string)$_POST["sex_list"];
	$age=(string)$_POST["age_list"];
	$onset=(string)$_POST["onset_list"];
	$status=(string)$_POST["status_list"];
	$signs=$_POST["signs_array"];

	$DATE = (string)date("Y/m/d");
	$TIME = (string)date("h:i:sa");
	$location = get_client_location();

	if( strpos($action, "search") !== FALSE )
	{
        search_disease($species, $gender, $age, $onset, $status, $signs);

        $str_signs = implode(", ",$signs);		

		$file_content = array(
			array($DATE, $TIME, $location, $species, $gender, $age, $onset, $status, $str_signs)
		);
	}
	elseif( strpos($action, "close") !== FALSE )
	{
		$str_diseases = (string)$_POST["content"];

		$file_content = array(
			array($DATE, $TIME, $location, $species, $gender, $age, $onset, $status, $str_signs, $str_diseases)
		);
	}

	update_file($file_content);

	function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    function search_disease($species, $gender, $age, $onset, $status, $signs){

		$servername = "localhost";
		//$username = "arvinsmit";
		//$password = "717arvinsmit";
		$username = "root";
		$password = "";

		$dbname = "ask_fido";

		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		
		$pre_sql = "SELECT name, common, sign FROM diseases WHERE species LIKE '%{$species}%' AND gender LIKE '%{$gender}%' AND p_age LIKE '%{$age}%' AND onset LIKE '%{$onset}%' AND status LIKE '%{$status}%'";

		$len = count($signs);
        if ( $len > 0 )
		{
			for ( $cnt = 0 ; $cnt < $len ; $cnt++ )
			{
				$pre_sql = $pre_sql." AND sign LIKE '%{$signs[$cnt]}%'";
			}
		}

		$sql = $pre_sql;

		//echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if (mysqli_num_rows($result) > 0) {

			$i = 0 ;
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				//echo "name: " . $row["name"]. " - Description: " . $row["description"]. "<br>";

				$return[$i]["name"]=$row["name"];
				$return[$i]["common"]=$row["common"];
				$return[$i]["sign"]=$row["sign"];

				$i = $i + 1 ;
			}			

			$diseases = $return ;
			$diseases_len = $i ;
						
						for ( $i = 0 ; $i < $diseases_len ; $i++ )
						{
							for ($j = $i ; $j < $diseases_len ; $j++)
							{
								if( (int)$diseases[$i]["common"] < (int)$diseases[$j]["common"] )
								{
									$tmp=$diseases[$i]; $diseases[$i]=$diseases[$j]; $diseases[$j]=$tmp;
								}
							}
						}

						$commSigns=[]; $commSignsLen=0;
						$tmpSigns = explode(",",$diseases[0]["sign"]);
						$tmpSignsLen = count($tmpSigns);
						$flgNew = true; $flg_equal = false;

						for ($i=0; $i<$tmpSignsLen; $i++)
						{
							$commSigns[$i]["name"] = $tmpSigns[$i];
							$commSigns[$i]["times"] = 1;
						}
						$commSignsLen = $tmpSignsLen ;
							
						$newSigns = 0 ;

						for ( $i = 1 ; $i < $diseases_len ; $i++ )
						{
							$tmpSigns = explode(",",$diseases[$i]["sign"]);
							$tmpSignsLen = count($tmpSigns);

							for ( $j = 0 ; $j < $tmpSignsLen ; $j++ )
							{
								for ( $k = 0 ; $k < $commSignsLen ; $k++ )
								{
									if ( $tmpSigns[$j] == $commSigns[$k]["name"] )
									{
										$commSigns[$k]["times"]++;
										$flg_equal = true ;
									}
									else
									{
										$flg_equal = false ;
									}
									$flgNew = $flgNew & !$flg_equal;
								}

								if ($flgNew == true)
								{
									$newSigns ++ ;
									$commSigns[$commSignsLen+$newSigns-1]["name"] = $tmpSigns[$j];
									$commSigns[$commSignsLen+$newSigns-1]["times"] = 1;
								}
								$flgNew = true;
							}
							$commSignsLen += $newSigns;
							$newSigns = 0 ;
						}						

						for ( $i = 0 ; $i < $commSignsLen ; $i++)
						{
							for ( $j = $i ; $j < $commSignsLen ; $j++ )
							{
								if( (int)$commSigns[$i]["times"] < (int)$commSigns[$j]["times"] )
								{
									$temp=$commSigns[$i]; $commSigns[$i]=$commSigns[$j]; $commSigns[$j]=$temp;
								}
							}
						}

						$return_data["result"] = $diseases;
						$return_data["signs"] = $commSigns;
		} 
		else {
			$return_data["result"] = [];
			$return_data["signs"] = [];
		}

		echo json_encode($return_data);
		
		mysqli_close($conn);
	}

	function update_file($list)
	{
		$fp = fopen('search_info.csv', 'a');

		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);
	}
?>
<?php
	/* Configuration start*/
    $prefix_old = 'bbfaa_'; // old prefix
    $prefix_new = 'ndboh_'; // new prefix

    $hostname	= 'localhost';
    $username	= 'root';
    $password	= '';
    $base 		= 'database_name';
	/* Configuration end*/

	log("*************** START ************");
	
    $mysqli = new mysqli($hostname, $username, $password, $base);
    if(mysqli_connect_errno()){
        log('error '.mysqli_connect_error());
    } else {
        $mysqli->set_charset("utf-8");
        $result = $mysqli->query("SHOW TABLE STATUS FROM `$base`");
        $info = array();
        
        if($result){
            $yes    = 0; $no     = 0;
            $info['number of tables'] = $result->num_rows.PHP_EOL;
            $tables = array();
            
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                $tables[] = $row['Name'];
            }
            $result->free();
            
            foreach ($tables as $name_old){
                $pos = stripos($name_old, $prefix_old, 0);
                if(($pos !== false) && $pos === 0){
                    $name_new = str_replace($prefix_old, $prefix_new, $name_old);
                    $sql = "RENAME TABLE `$name_old` TO `$name_new`";
                    
                    $result = $mysqli->query($sql);
                    log($sql.' :: '.($result ? 'true' : 'false'));
                    log($mysqli->error);
					
                    $yes++;
                } else {
                    $no++;
                }
            }
            
            $info['renamed tables'] = $yes;
            $info['skipped tables'] = $no;
        }
        
        print_r($info);
        
        $mysqli->close();
    }
	
	log("*************** FINISH ************");
	
	function log($msg){
		echo  date("[H:i:s] ").$msg.PHP_EOL;
	}
        
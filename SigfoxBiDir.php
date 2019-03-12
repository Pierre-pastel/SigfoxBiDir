<?php

//Callback exemple: [BIDIR] http://exemple.com/reponseDownlink.php?id={device}&data={data}&ack={ack}

parse_str($_SERVER['QUERY_STRING']);
date_default_timezone_set('Europe/Paris');

$logfile = 'log-tpbidir.txt';
$fichierLogs = fopen($logfile, 'a+');

if(isset($_GET['id']) AND isset($_GET['data'])) { // Requis
    
	if(isset($_GET['ack'])) { // Message UP/BIDIR
        
        if($ack == 'true') { // BIDIR
            		
			fputs($fichierLogs, "[".date('Y/m/d-H:i:s')."] ID:".$id." [Device-->Network] [BIDIR] Time:".date('Y/m/d-H:i:s',$time)." Data:".$data." RSSI:".$rssi." seqNum:".$seqNum."\n");
			
			// traitement et création de la data downlink
			$dlData = "0123456789ABCDEF";
			
			echo("{\"".$id."\" : { \"downlinkData\" : \"".$dlData."\"}}");
			fputs($fichierLogs, "[".date('Y/m/d-H:i:s')."] ID:".$id." [Network-->Device] [BIDIR] DownlinkData: ".$dlData."\n");
        }
        else { // UPLINK
            fputs($fichierLogs, "[".date('Y/m/d-H:i:s')."] ID:".$id." [Device-->Network] [UPLINK] Time:".date('Y/m/d-H:i:s',$time)." Data:".$data." RSSI:".$rssi." seqNum:".$seqNum."\n");
        }
    }
    elseif(isset($_GET['downlinkAck']) AND isset($_GET['infoMessage'])) { // ACK station
        fputs($fichierLogs, "[".date('Y/m/d-H:i:s')."] ID:".$id." [Station-->Network] [ACK] Time:".date('Y/m/d-H:i:s',$time)." infoMsg:".$infoMessage."\n");
    }
    elseif(isset($_GET['service'])) { // SERVICE
        fputs($fichierLogs, "[".date('Y/m/d-H:i:s')."] ID:".$id." [Device-->Network] [SERVICE] Time:".date('Y/m/d-H:i:s',$time)."\n");
    }
    elseif(isset($_GET['severity'])) { // ERROR
        fputs($fichierLogs, "[".date('Y/m/d-H:i:s')."] ID:".$id." [Device-->Network] [ERROR] Time:".date('Y/m/d-H:i:s',$time)." Info:".$info." Severity:".$severity."\n");
    }
    else { // ERROR (comm)
        fputs($fichierLogs, "[".date('Y/m/d-H:i:s')."][COMM-ERROR-1] ".$_SERVER['REMOTE_ADDR']."\n");
    }
}
else {
    fputs($fichierLogs, "[".date('Y/m/d-H:i:s')."][COMM-ERROR-2] ".$_SERVER['REMOTE_ADDR']."\n");
}
fclose($fichierLogs);
?>

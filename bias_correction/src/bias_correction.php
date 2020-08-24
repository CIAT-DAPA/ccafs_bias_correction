<?php
# Dependencies
require '/var/www/html/libs/tools/email.php';
require '/var/www/html/libs/tools/zip.php';

# Configuration variables
$root = "/mnt/";
$date = date("Y-m-d");
$time = date("H:i:s");

# functions that gets data from request
# (string) name: Name of the parameter that you want to get
function getData($name){
    $empty = "NA";
    return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $empty;
}

# Creating downloads folder
$workspace = '/var/www/html/downloads/'.$date;
if (!file_exists($workspace)) {
    mkdir($workspace, 0777, true);
}

# Creating workspace
$final_name = md5($date." ".$time);
$workspace = $workspace."/".$final_name;
mkdir($workspace, 0777, true);

# Email
$email = getData('email'); 

# Setting parameters
$serverData = $root."data_cluster_4/portals/ccafs_climate/download_data/files/data/bc_platform"; # "S:/portals/ccafs_climate/download_data/files/data/bc_platform" $ si se corre local no es necesario modificar esta variable
$downData = "http://backend.ccafs-climate.org/downloads/"; # si se corre local no es necesario modificar esta variable
$dirWork = $workspace; #"/var/www/html/downloads"; #"C:/Temp/bc/Request_jramirez" $"C:/Temp" $directorio de salida "/home/jtarapues/request/request_oriana" $ 
$dirgcm = $root."data_cluster_2/gcm/cmip5/raw/daily"; # $"T:/gcm/cmip5/raw/daily"
$dirobs = $root."data_cluster_5/cropdata"; # "U:/cropdata" #"S:/observed/gridded_products/ncep-cru-srb-gsod-merge-for-east-west-africa" $  

$dataset = getData('observation-acronym');  #"station" wfd, wfdei, agmerra, grasp, agcfsr, princeton, princenton-afr
$methBCList = getData('methods'); #c("5")#c('1','2','3','4','5')  #1=SH,2=BC,3=DEL,4=CF,5=QM c('5')#
$varlist = getData('variables-acronym'); #c("pr","tasmin")#c("pr","tasmax","tasmin","rsds") 

$periodh = explode(";", getData('periodh'));
$Obyi = $periodh[0]; #1980#1985
$Obyf = $periodh[1]; #2010#1987
$period = explode(";", getData('period'));
$fuyi = $period[0]; #2030#2072#2020
$fuyf = $period[1]; #2060#2100#2049

$rcpList = getData('scenarios-acronym'); #c("rcp85") $rcp26, rcp45, rcp60, rcp85 "rcp26", "rcp45", "rcp60",  $aun no esta funcionando bien para varios rcps
$xyList = getData('lon').",".getData('lat');  #c("-85.717,14.817") #c("-49.28,-16.47") $c("-73.84,4.91") #c("-76.38558333,3.533333333") $para correr pocos sitios
$xyfile = ""; #  "/home/temp/Request_andy/CSVs.txt" $para correr varios sitios, este debe contener las columnas id,lon,lat
$gcmlist = getData('model'); # c("bcc_csm1_1","bcc_csm1_1_m","bnu_esm","cccma_canesm2","cesm1_bgc","cesm1_cam5","cmcc_cms","csiro_access1_0","csiro_mk3_6_0","ec_earth","gfdl_cm3","gfdl_esm2g","gfdl_esm2m","inm_cm4","ipsl_cm5a_lr","ipsl_cm5a_mr","ipsl_cm5b_lr","lasg_fgoals_g2","miroc_esm","miroc_esm_chem","miroc_miroc5","mohc_hadgem2_cc","mohc_hadgem2_es","mpi_esm_lr","mpi_esm_mr","mri_cgcm3","ncar_ccsm4","ncc_noresm1_m")#c("bcc_csm1_1_m","cesm1_cam5","csiro_mk3_6_0","mohc_hadgem2_es","mohc_hadgem2_cc","gfdl_esm2g")#c("mohc_hadgem2_es","mohc_hadgem2_cc")#c("bcc_csm1_1", "bcc_csm1_1_m", "cesm1_cam5", "csiro_mk3_6_0", "gfdl_cm3", "gfdl_esm2g", "gfdl_esm2m", "ipsl_cm5a_lr", "ipsl_cm5a_mr", "miroc_esm", "miroc_esm_chem", "miroc_miroc5", "mohc_hadgem2_es", "mri_cgcm3", "ncar_ccsm4", "ncc_noresm1_m")
$statList = getData('formats'); #c('1','2','3') $c('1') $1=files bc, 2=tables and graphics, 3=convert files to wth format DSSAT
$fileStat = getData('file'); # "C:/Temp/bc_-73.84_4.91/file_1465990447.txt"#"/home/temp/file_1465990447.txt" #"/home/temp/bc_-49.28_-16.47/obs/stat_-49.28_-16.47.txt"$ "C:/Temp/bc/bc_-76.38558333_3.533333333/apto_alfonso_bonilla.txt" $"/home/jtarapues/apto_alfonso_bonilla.txt"$ "D:/jetarapues/Request/Request_jramirez/stat_-51.82_-16.97.txt" $"C:/Temp/bc/Request_jramirez/stat_-49.28_-16.47.txt" #
$sepFile = getData('delimitator'); #"tab" # puntocoma,space,Comma

$leap = 1; #1=rellena los leap year con el promedio del dia antes y despues (e.g. DSSAT, Oryza2000), 2=quita los dias leap year (e.g. para GLAM), 3=conserva los datos con leap NA
$typeData = 1; #1=Remueve los NA si todos los modelos los tienen en comun, 2=remueve todos los datos con NA, 3=conserva los datos con leeps NA $opci?n 2 pone problema en qmap dejarlo en valor 1
$remote = "NO"; # YES | NO (local) -> importante para definir la ruta en fileStat, si es YES debe ser http://file.txt, NO es path relativo
$dircdo = "/usr/bin/cdo"; #modificar si no encuentra el path de cdo
$order = "NA"; #no modificar si remote=NO
$dir = "NO"; #"YES" | "NO" if is YES dirWork is the workspace here are the results
##$For run on windows:
$ver_python="python";
$dirScript_py="/var/www/html/python/bc_extract_gcm.py";

# Send email to users
$email_name = "/var/www/html/assets/emails/requested.html";
$email_file = fopen($email_name, "r") or die("Unable to open file!");
$email_text = fread($email_file,filesize($email_name));
fclose($email_file);
$email_text = str_replace("#1#",$order,$email_text);
$email_text = str_replace("#2#",$email,$email_text);
$email_text = str_replace("#3#",$date." ".$time,$email_text);
$email_text = str_replace("#4#",getData('lon'),$email_text);
$email_text = str_replace("#5#",getData('lat'),$email_text);
$email_text = str_replace("#6#",$rcpList,$email_text);
$email_text = str_replace("#7#",$dataset,$email_text);
$email_text = str_replace("#8#",$Obyi." - ".$Obyf,$email_text);
$email_text = str_replace("#9#",$fuyi,$email_text);
$email_text = str_replace("#10#",$fuyf,$email_text);
$email_text = str_replace("#11#",$varlist,$email_text);
$email_text = str_replace("#12#",$gcmlist,$email_text);
$email_text = str_replace("#13#",$methBCList,$email_text);
send_msg("CCAFS Climate - Bias Correction requested",$email_text,$email);

# Command to execute
$cmd = 'Rscript /var/www/html/r/run_bias.R "'.$serverData.'" '.
                                            '"'.$downData.'" '.
                                            '"'.$dirWork.'" '.
                                            '"'.$dirgcm.'" '.
                                            '"'.$dirobs.'" '.
                                            '"'.$dataset.'" '.
                                            '"'.$methBCList.'" '.
                                            '"'.$varlist.'" '.
                                            '"'.$Obyi.'" '.
                                            '"'.$Obyf.'" '.
                                            '"'.$fuyi.'" '.
                                            '"'.$fuyf.'" '.
                                            '"'.$rcpList.'" '.
                                            '"'.$xyList.'" '.
                                            '"'.$xyfile.'" '.
                                            '"'.$gcmlist.'" '.
                                            '"'.$statList.'" '.
                                            '"'.$fileStat.'" '.
                                            '"'.$sepFile.'" '.
                                            '"'.$leap.'" '.
                                            '"'.$typeData.'" '.                                            
                                            '"'.$remote.'" '.
                                            '"'.$dircdo.'" '.
                                            '"'.$order.'" '.
                                            '"'.$dir.'" '.
                                            '"'.$ver_python.'" '.
                                            '"'.$dirScript_py.'" ';

# Outputs
echo "<h1>Input</h1><br />";
echo $cmd;

# Log
file_put_contents("/var/log/apache2/app/".$date.".txt", $time."|".$_SERVER['REMOTE_ADDR']."|".$cmd, FILE_APPEND);

# Execution of R Script through console
$output=shell_exec($cmd);

#zip_download($final_name);
#rmdir($final_name);
if(file_exists($workspace.".zip")){
    # email to download data
    $email_name = "/var/www/html/assets/emails/finished.html";
    $email_file = fopen($email_name, "r") or die("Unable to open file!");
    $email_text = fread($email_file,filesize($email_name));
    fclose($email_file);
    $email_text = str_replace("#1#",$order,$email_text);
    $email_text = str_replace("#2#",$email,$email_text);
    $email_text = str_replace("#3#",$date." ".$time,$email_text);
    $email_text = str_replace("#4#",getData('lon'),$email_text);
    $email_text = str_replace("#5#",getData('lat'),$email_text);
    $email_text = str_replace("#6#",$rcpList,$email_text);
    $email_text = str_replace("#7#",$dataset,$email_text);
    $email_text = str_replace("#8#",$Obyi." - ".$Obyf,$email_text);
    $email_text = str_replace("#9#",$fuyi,$email_text);
    $email_text = str_replace("#10#",$fuyf,$email_text);
    $email_text = str_replace("#11#",$varlist,$email_text);
    $email_text = str_replace("#12#",$gcmlist,$email_text);
    $email_text = str_replace("#13#",$methBCList,$email_text);
    $email_text = str_replace("#14#",$downData.$date."/".$final_name.".zip",$email_text);
    send_msg("CCAFS Climate - Bias Correction done",$email_text,$email);
} else{
    # email to show error
    $email_name = "/var/www/html/assets/emails/error.html";
    $email_file = fopen($email_name, "r") or die("Unable to open file!");
    $email_text = fread($email_file,filesize($email_name));
    fclose($email_file);
    $email_text = str_replace("#1#",$order,$email_text);
    $email_text = str_replace("#2#",$email,$email_text);
    $email_text = str_replace("#3#",$date." ".$time,$email_text);
    $email_text = str_replace("#4#",getData('lon'),$email_text);
    $email_text = str_replace("#5#",getData('lat'),$email_text);
    $email_text = str_replace("#6#",$rcpList,$email_text);
    $email_text = str_replace("#7#",$dataset,$email_text);
    $email_text = str_replace("#8#",$Obyi." - ".$Obyf,$email_text);
    $email_text = str_replace("#9#",$fuyi,$email_text);
    $email_text = str_replace("#10#",$fuyf,$email_text);
    $email_text = str_replace("#11#",$varlist,$email_text);
    $email_text = str_replace("#12#",$gcmlist,$email_text);
    $email_text = str_replace("#13#",$methBCList,$email_text);    
    $email_text = str_replace("#14#",$workspace,$email_text);    
    send_msg("CCAFS Climate - Bias Correction - Error",$email_text,$email);
}



echo "<br /><h1>Output</h1><br />";
echo $output;

?>
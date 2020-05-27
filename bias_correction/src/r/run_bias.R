source("/var/www/html/r/gcm_calibration_bc_local.R")

args = commandArgs(TRUE)
 
## Input Simulation parameters

serverData = as.character(args[1]) # "/mnt/data_cluster_4/portals/ccafs_climate/download_data/files/data/bc_platform" # "S:/portals/ccafs_climate/download_data/files/data/bc_platform" #  si se corre local no es necesario modificar esta variable
downData = as.character(args[2]) # "http://gisweb.ciat.cgiar.org/ccafs_climate/files/data/bc_platform" # si se corre local no es necesario modificar esta variable
dirWork =  as.character(args[3]) # "/home/temp" #"C:/Temp/bc/Request_jramirez" # "C:/Temp" # directorio de salida "/home/jtarapues/request/request_oriana" #  
dirgcm = as.character(args[4]) # "/mnt/data_cluster_2/gcm/cmip5/raw/daily" # "T:/gcm/cmip5/raw/daily" #  
dirobs = as.character(args[5]) # "/mnt/data_cluster_5/cropdata/" # "U:/cropdata" #"S:/observed/gridded_products/ncep-cru-srb-gsod-merge-for-east-west-africa" #   
dataset = as.character(args[6]) # "agmerra"  "station wfd, wfdei, agmerra, grasp, agcfsr, princeton, princenton-afr"
methBCList = unlist(strsplit(args[7], ",")) # c("5")#c('1','2','3','4','5')  # 1=SH,2=BC,3=DEL,4=CF,5=QM c('5')#
varlist = unlist(strsplit(args[8], ",")) # c("pr","tasmin")#c("pr","tasmax","tasmin","rsds") # 
Obyi = as.integer(args[9]) # 1980#1985
Obyf = as.integer(args[10]) # 2010#1987
fuyi = as.integer(args[11]) #  2030#2072#2020
fuyf = as.integer(args[12]) #  2060#2100#2049
rcpList = unlist(strsplit(args[13], ",")) # c("rcp85") # rcp26, rcp45, rcp60, rcp85 "rcp26", "rcp45", "rcp60",  # aun no esta funcionando bien para varios rcps
xyList = c(args[14]) # c("-85.717,14.817") #c("-49.28,-16.47") # c("-73.84,4.91") #c("-76.38558333,3.533333333") # para correr pocos sitios
xyfile =  as.character(args[15]) # para correr varios sitios, este debe contener las columnas id,lon,lat
gcmlist = unlist(strsplit(args[16], ",")) #  c("bcc_csm1_1","bcc_csm1_1_m","bnu_esm","cccma_canesm2","cesm1_bgc","cesm1_cam5","cmcc_cms","csiro_access1_0","csiro_mk3_6_0","ec_earth","gfdl_cm3","gfdl_esm2g","gfdl_esm2m","inm_cm4","ipsl_cm5a_lr","ipsl_cm5a_mr","ipsl_cm5b_lr","lasg_fgoals_g2","miroc_esm","miroc_esm_chem","miroc_miroc5","mohc_hadgem2_cc","mohc_hadgem2_es","mpi_esm_lr","mpi_esm_mr","mri_cgcm3","ncar_ccsm4","ncc_noresm1_m")#c("bcc_csm1_1_m","cesm1_cam5","csiro_mk3_6_0","mohc_hadgem2_es","mohc_hadgem2_cc","gfdl_esm2g")#c("mohc_hadgem2_es","mohc_hadgem2_cc")#c("bcc_csm1_1", "bcc_csm1_1_m", "cesm1_cam5", "csiro_mk3_6_0", "gfdl_cm3", "gfdl_esm2g", "gfdl_esm2m", "ipsl_cm5a_lr", "ipsl_cm5a_mr", "miroc_esm", "miroc_esm_chem", "miroc_miroc5", "mohc_hadgem2_es", "mri_cgcm3", "ncar_ccsm4", "ncc_noresm1_m")
statList = unlist(strsplit(args[17], ",")) # c('1','2','3') # c('1') # 1=files bc, 2=tables and graphics, 3=convert files to wth format DSSAT
fileStat = as.character(args[18]) #  "C:/Temp/bc_-73.84_4.91/file_1465990447.txt"#"/home/temp/file_1465990447.txt" #"/home/temp/bc_-49.28_-16.47/obs/stat_-49.28_-16.47.txt"#  "C:/Temp/bc/bc_-76.38558333_3.533333333/apto_alfonso_bonilla.txt" # "/home/jtarapues/apto_alfonso_bonilla.txt"#  "D:/jetarapues/Request/Request_jramirez/stat_-51.82_-16.97.txt" # "C:/Temp/bc/Request_jramirez/stat_-49.28_-16.47.txt" #
sepFile = as.character(args[19]) # "tab"# puntocoma,space,Comma
leap = as.integer(args[20]) #1 # 1=rellena los leap year con el promedio del dia antes y despues (e.g. DSSAT, Oryza2000), 2=quita los dias leap year (e.g. para GLAM), 3=conserva los datos con leap NA
typeData = as.integer(args[21]) #1 #1=Remueve los NA si todos los modelos los tienen en comun, 2=remueve todos los datos con NA, 3=conserva los datos con leeps NA # opci?n 2 pone problema en qmap dejarlo en valor 1
remote = as.character(args[22]) # "NO"  # YES | NO (local) -> importante para definir la ruta en fileStat, si es YES debe ser http://file.txt, NO es path relativo
dircdo = as.character(args[23]) # "cdo" # modificar si no encuentra el path de cdo
order = as.character(args[24]) # NA # no modificar si remote=NO
dir = as.character(args[25]) # "YES" | "NO" # if is YES dirWork is the workspace here are the results
# ## For run on windows:
ver_python = as.character(args[26]) # "C:/Python26/python.exe"
dirScript_py = as.character(args[27]) # "C:/Temp/bc/Request_jramirez/bc_extract_gcm.py"

print(args)

bc_processing(serverData,downData,dirWork,dirgcm,dirobs,dataset,methBCList,varlist,Obyi,Obyf,fuyi,fuyf,rcpList,xyList,xyfile,gcmlist,statList,fileStat,sepFile,leap,typeData,ver_python,dirScript_py,remote,dircdo,order,dir)

setwd(dirWork)
files2zip = dir(dirWork)
zip(zipfile = paste0(dirWork,".zip"), files = files2zip)
<?php

# Function to compress files
function zip_download($folder){    
    $zip = new ZipArchive();
    $zip_file = $folder.".zip";
    if ($zip->open($zip_file, ZipArchive::CREATE)!==TRUE) 
    {
        exit("cannot open <$zip_file>\n");
    }

    $all= new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));
    
    foreach ($all as $f=>$value) {
        $zip->addFile(realpath($f), $f) or die ("ERROR: Unable to add file: $f");
    }
    $zip->close();

}
?>
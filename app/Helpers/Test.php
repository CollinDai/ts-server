<?php namespace App\Helpers;


$lans = [];
if (($handle = fopen("export_languages.php", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, "	")) !== FALSE) {
        $lans[] = ['name'=>$data[2],'iso'=>$data[0]];
    }
    fclose($handle);
}

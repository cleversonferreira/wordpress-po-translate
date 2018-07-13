<?php

/**
 * @author Cleverson Franco
 * @date 07/13/2018
 */

/**
 * Include wordpress files
 */
require_once('wp-config.php');
require_once(ABSPATH . 'wp-includes/wp-db.php');
require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');

/**
 * Config vars
 */

$matches = array();
//theme name
$theme_name = 'classify';
//path from the root of the theme, don't put '/' at the beginning or end of the path
$translate_file_folder = 'languages';
//name of the .po file to be translated, the .po extension will be added automatically.
$translate_po_file_name = 'en_US';
//pattern to get text to translate
$pattern = "/^(msgid ['|\"](.+)['|\"])/";
//path to .po file to translate
$file_to_translate_url = get_theme_root_uri() . '/' . $theme_name . '/' . $translate_file_folder . '/' . $translate_po_file_name . '.po';
//translate api url (using yandex API)
$translate_api_url = 'https://translate.yandex.net/api/v1.5/tr.json/translate';
//generate your API key on https://translate.yandex.com/developers
$translate_api_key = 'trnsl.1.1.20180713T165642Z.25f18a5c7b62335b.6f60e0b1581cedfa7e9337eff2711592dbe4e70c';
//set language to translate
$translate_api_lang = 'pt';

//create new file with translations
$new_file_url = fopen("newfile.po", "w") or die("Unable to open file!");
//get original file content
$file = fopen($file_to_translate_url, "r");

//read all file line
while (!feof($file)) {
    $line = fgets($file);

    //if regex match
    if(preg_match($pattern,$line,$matches)) {

        //connect to translate api
        $curl = curl_init();
        $translate_url = $translate_api_url . '?key=' . $translate_api_key . '&text=' . urlencode($matches[2]) . '&lang=' . $translate_api_lang;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $translate_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        //if error, use original line
        if ($err) {
            fwrite($new_file_url, "msgid \"$matches[2]\"\n");
        }else {
            $translated = json_decode($response);
            $translated = $translated->text[0];
            fwrite($new_file_url, "msgid \"$translated\"\n");
        }

    }else{
        fwrite($new_file_url, $line);
    }
}

//close new file
fclose($new_file_url);

/**
 * Download new .po file
 */
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename('newfile.po'));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize('newfile.po'));
readfile('newfile.po');

# wordpress-po-translate
Script to translate .po file to pt or other language using Yandex API

- insert file on your wordpress root dir
- Set theme name
  $theme_name = 'your_theme_name';
  
- Set path from the root of the theme, don't put '/' at the beginning or end of the path
  $translate_file_folder = 'languages';
  
- Set name of the .po file to be translated, the .po extension will be added automatically.
  $translate_po_file_name = 'en_US';
  
- generate your API key on https://translate.yandex.com/developers
  $translate_api_key = 'YOUR_API_KEY';
  
- set language to translate
  $translate_api_lang = 'pt';

- Run file

- A .po file will be automatically downloaded with the translated version

** Important
- This script does not convert to .mo (a good opportunity for a fork). Use an online converter.
- Some lines may not be translated, so check the file.
- Use Loco Translate plugin (https://br.wordpress.org/plugins/loco-translate/) to help generate the translation file (if it does not exist) and word correction

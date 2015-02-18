<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

/*
|-------------------------------------------------------------------------
| Autoload the current language
|-------------------------------------------------------------------------
*/
$hook['post_controller_constructor'] = array(
                                'class'    => 'LanguageLoader',
                                'function' => 'loadCurrentLanguage',
                                'filename' => 'LanguageLoader.php',
                                'filepath' => 'hooks',
                                'params'   => ''
                                );

/*
|-------------------------------------------------------------------------
| Autoload language files that are needed globally
|-------------------------------------------------------------------------
*/
$hook['post_controller_constructor'] = array(
                                'class'    => 'LanguageLoader',
                                'function' => 'loadLanguageFiles',
                                'filename' => 'LanguageLoader.php',
                                'filepath' => 'hooks',
                                'params'   => ''
                                );

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */

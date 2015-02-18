<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LanguageLoader {

/*
|-------------------------------------------------------------------------
| Load the current language
|-------------------------------------------------------------------------
*/
	public function loadCurrentLanguage()
	{
		$ci =& get_instance();
		$language = $ci->lang->languages[$ci->lang->lang()];
		$ci->lang->load($language);
	}

/*
|-------------------------------------------------------------------------
| Load language files that are needed globally
|-------------------------------------------------------------------------
*/
	public function loadLanguageFiles()
	{
		$ci =& get_instance();
		$ci->lang->l_load('urls');
		$ci->lang->l_load('menu');
		$ci->lang->l_load('header');
		$ci->lang->l_load('footer');
	}

}

?>

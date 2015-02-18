<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Lang extends CI_Lang {

	var $languages = array(
		'en' => 'english',
		'pt' => 'portuguese'//,
		//'es' => 'spanish'
	);

	var $special = array (
		"admin", "language"
	);

	var $default_uri = '';

	public function __construct()
	{
		parent::__construct();

		global $CFG;
		global $URI;
		global $RTR;

		$segment = $URI->segment(1);

		if (isset($this->languages[$segment])) { // URI with language -> ok
			$language = $this->languages[$segment];
			$CFG->set_item('language', $language);
		} elseif ($this->is_special($segment)) { // special URI -> no redirect
			return;
		} else { // URI without language -> redirect to default_uri
			// set default language
			$CFG->set_item(
				'language',
			    $this->languages[$this->default_lang()]
			);

			// redirect
			header(
				"Location: "
				.$CFG->site_url($this->localized($this->default_uri)), TRUE, 302
			);
			exit;
		}
	}

	// get current language
	// ex: return 'en' if language in CI config is 'english'
	public function lang()
	{
		global $CFG;
		$language = $CFG->item('language');

		$lang = array_search($language, $this->languages);
		if ($lang) {
			return $lang;
		}

		return null; // this should not happen
	}

	//same as the standard load but always loads the file from the current language
	public function l_load($file)
	{
		$langfiles = (array) $file;

		foreach ($langfiles as $langfile) {
			$this->load($langfile, $this->languages[$this->lang()]);
		}
	}

	public function is_special($uri)
	{
		$exploded = explode('/', $uri);
		if (in_array($exploded[0], $this->special)) {
			return true;
		}
		if (isset($this->languages[$uri])) {
			return true;
		}

		return false;
	}

	public function switch_uri($lang)
	{
		$CI =& get_instance();
		$uri = $CI->uri->uri_string();

		if ($uri != "") {
			$exploded = explode('/', $uri);

			if ($exploded[0] == $this->lang()) {
				$exploded[0] = $lang;
			}

			$uri = implode('/',$exploded);
		}

		return $uri;
	}

	// is there a language segment in this $uri?
	public function has_language($uri)
	{
		$first_segment = null;

		$exploded = explode('/', $uri);
		if (isset($exploded[0])) {
			if ($exploded[0] != '') {
				$first_segment = $exploded[0];
			} elseif (isset($exploded[1]) && $exploded[1] != '') {
				$first_segment = $exploded[1];
			}
		}

		if ($first_segment != null) {
			return isset($this->languages[$first_segment]);
		}

		return false;
	}

	// default language: first element of $this->languages
	public function default_lang()
    {
        $browser_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])
		    ? strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ',')
			: '';
        $browser_lang = substr($browser_lang, 0,2);

        if (! $browser_lang) {
        	return array_shift(array_keys($this->languages));
        } else {
        	return (array_key_exists($browser_lang, $this->languages))
			    ? $browser_lang: array_shift(array_keys($this->languages));
        }
    }

	// add language segment to $uri (if appropriate)
	public function localized($uri)
	{
		if ($this->has_language($uri)
			|| $this->is_special($uri)
			|| preg_match('/(.+)\.[a-zA-Z0-9]{2,4}$/', $uri)) {
			// we don't need a language segment because:
			// - there's already one or
			// - it's a special uri (set in $special) or
			// - that's a link to a file
		} else {
			$uri = $this->lang() . '/' . $uri;
		}

		return $uri;
	}

	public function unload()
	{
		$this->is_loaded = array();
		$this->language = array();
	}

}

/* End of file */

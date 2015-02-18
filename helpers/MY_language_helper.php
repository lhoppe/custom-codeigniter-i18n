<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------------
| Fetches the specified language line in the current language
|------------------------------------------------------------------------------
| @params
|     string $line => The key to the language line in the language file
|     array/string $vars => The values which should replace placeholders
| @return
|     string $line => The specified language line for the current language
|------------------------------------------------------------------------------
*/
function lang($line, $vars = array())
{
  $CI =& get_instance();
  $line = $CI->lang->line($line);

  if (! is_array($vars)) {
    return str_replace('%s', $vars, $line);
  }

  foreach ($vars as $var) {
    $line = str_replace_first('%s', $var, $line);
  }

  return $line;
}

/*
|------------------------------------------------------------------------------
| Replaces the first occurance of $search_for with $replace_with in $in
|------------------------------------------------------------------------------
| @params
|     string $url => The key to the url in the urls_lang files
| @return
|     string url => The localized URL
|------------------------------------------------------------------------------
*/
function str_replace_first($search_for, $replace_with, $in)
{
   $pos = strpos($in, $search_for);
   if ($pos === false) {
       return $in;
   } else {
       return substr($in, 0, $pos)
           . $replace_with
           . substr($in, $pos + strlen($search_for), strlen($in));
   }
}

/*
|------------------------------------------------------------------------------
| Returns the shorthanded current language
|------------------------------------------------------------------------------
| @params
| @return
|     string language => The shorthanded current language
|------------------------------------------------------------------------------
*/
function current_language()
{
    $ci =& get_instance();

    return $ci->lang->lang();
}

/*
|------------------------------------------------------------------------------
| Fetches the localized specified URL
|------------------------------------------------------------------------------
| @params
|     string $url => The key to the url in the urls_lang files
| @return
|     string url => The localized URL
|------------------------------------------------------------------------------
*/
function l_url($url)
{
  return site_url(lang('urls.'.$url));
}

/*
|------------------------------------------------------------------------------
| [Convinience function] Equivalent to l_url() but echoes instead of returning
|------------------------------------------------------------------------------
| @params
|      string $url => The key to the url in the urls_lang files
| @return
|------------------------------------------------------------------------------
*/
function el_url($url)
{
  echo site_url(lang('urls.'.$url));
}

/*
|------------------------------------------------------------------------------
| Loads the specified languages' urls_lang files and returns an array with the
| localizations for the current URI. Keys are the shorthanded languages and the
| values are the localized URIs.
|------------------------------------------------------------------------------
| @params
| @return
|     array $uri_set
|------------------------------------------------------------------------------
*/
function set_uris()
{
    $ci =& get_instance();
    $langs = array('pt', 'en');
    $uri_set = array();

    $current_uri = $ci->uri->segment_array();

    array_shift($current_uri);

    $current_language = $ci->lang->languages[$ci->lang->lang()];
    $ci->lang->load('urls', $current_language);

    $translations = $ci->lang->language;
    $uri = implode('/', $current_uri);
    $key_to_url = array_search($uri, $translations);

    foreach ($langs as $lang) {
      $language = $ci->lang->languages[$lang];

      $ci->lang->unload();
      $ci->lang->load('urls', $language);

      $translated_uri = '';
      if (isset($ci->lang->language[$key_to_url])) {
        $translated_uri = $ci->lang->language[$key_to_url];
      }

      $redirect_to = base_url().$lang.'/'.$translated_uri;
      $uri_set[$lang] = $redirect_to;
    }

    $ci->lang->unload();
    
    return $uri_set;
}

/* End of file MY_language_helper.php */
/* Location: ./application/helpers/MY_language_helper */

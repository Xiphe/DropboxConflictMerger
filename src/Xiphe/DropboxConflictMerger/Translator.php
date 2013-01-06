<?php

namespace Xiphe\DropboxConflictMerger;
use Xiphe as X;

/**
 * Handles Translations for the dbcm.
 *
 * @copyright Copyright (c) 2013 Hannes Diercks
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE
 * @author    Hannes Diercks
 * @version   1.0.0
 * @link      
 * @package   Xiphe\DropboxConflictMerger
 */
class Translator extends Base
{

	public static $singleton = true;

	public $country = 'EN';
	public $languageFile;
	public $Language;

	public function init($initArgs = null)
	{
		global $HTML;

		$translation = (array) json_decode(eden('file', $this->changeFile())->getContent());
		$this->Language = eden('language', $translation);

		eden('event')->listen('xiphe_dbcm_connected', function($e, $name, $info) use ($translation) {
			if ($info['country'] !== 'EN') {
				$self = Translator::getInstance();
				$self->country = $info['country'];
				$file = eden('file', $self->changeFile($info['country']));

				if (!$file->isFile()) {
					$_GET['page'] = 'nolang';
				} else {
					$translation = array_merge(
						$translation,
						(array) json_decode($file->getContent())
					);
					$self->Language = eden('language', $translation);

					$GLOBALS['HTML']->setOption('translator', array($self->Language, 'get'));
				}
			}
		});

		$HTML->setOption('translator', array($this->Language, 'get'));
	}

	public function changeFile($to = 'EN')
	{
		$this->languageFile = sprintf('%s/lang/%s.json', dirname(__FILE__), $to);
		return $this->languageFile;
	}

	/**
	 * Converts data into a readable json format
	 *
	 * by bohwaz http://www.php.net/manual/de/function.json-encode.php#102091
	 * 
	 * modified code-style to fit to the rest and changed logic from function to method
	 * by Hannes Diercks, 2012
	 * 
	 * @access private
	 * @param  mixed $in the object or array to be converted
	 * @return string json
	 */
	public function json_readable_encode($in, $indent = 0, $from_array = false) {
	    $_escape = function ($str) {
	        return preg_replace("!([\b\t\n\r\f\"\\'])!", "\\\\\\1", $str);
	    };

	    $out = '';

	    foreach ($in as $key => $value) {
	        $out .= str_repeat("\t", $indent+1);
	        $out .= "\"".$_escape((string)$key)."\" : ";

	        if (is_object($value) || is_array($value)) {
	            $out .= "";
	            $out .= $this->json_readable_encode($value, $indent + 1);
	        } elseif (is_bool($value)) {
	            $out .= $value ? 'true' : 'false';
	        } elseif (is_null($value)) {
	            $out .= 'null';
	        } elseif (is_string($value)) {
	            $out .= "\"".$_escape($value)."\"";
	        } else {
	            $out .= $value;
	        }

	        $out .= ",\n";
	    }

	    if (!empty($out)) {
	        $out = substr($out, 0, -2);
	    }

	    $out = "{\n".$out;
	    $out .= "\n".str_repeat("\t", $indent)."}";

	    return $out;
	}

	public function __destruct()
	{
		if (is_file($this->languageFile)) {
			file_put_contents(
				$this->languageFile,
				$this->json_readable_encode($this->Language->getLanguage())
			);
		}
	}
}
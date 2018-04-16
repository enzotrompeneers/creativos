<?php
/**
 * Property list class
 *
 * Miralbo
 *
 * @author Daniel Beard <daniel@creativos.be>
 */

namespace Brunelencantado\Viviendas;

class ViviendasHelpers
{
	
    /**
     * @brief Creates title from property data
     * 
     * @param <type> $dormitorios int
     * @param <type> $tipo string
     * @param <type> $poblacion string
     * 
     * @return <type> string
     */
	
	public static function frase($dormitorios, $tipo, $poblacion, $language = null) 
	{

		$lang = ($language) ? $language : LANGUAGE;

		switch ($lang) {
			case 'en':
				$frase1 = $dormitorios . ' bedroom ';
				$frase = $frase1 . $tipo.' in '.$poblacion;
				if ($dormitorios == 0){ $frase = $tipo.' in '.$poblacion; }
				break;
			case 'se':
				$frase1 = ($tipo!='Business premises')?$dormitorios.' '.trad("bedroom").' ':'';
				$frase = $frase1.$tipo.' i '.$poblacion;
				if ($dormitorios=='any'){ $frase = $tipo.' in '.$poblacion; }
				break;			
			case 'es':
				$plural = ($dormitorios==1)?'dormitorio':'dormitorios';
				$frase1 = ($tipo!=7)?' de '.$dormitorios.' '.$plural:'';
				$frase = $tipo.$frase1.' en '.$poblacion;
				break;
			default:
				$frase1 = ($tipo!='Business premises')? $dormitorios.' '.trad('bedroom', $lang).' ':'';
				$frase = $frase1.$tipo.' ' . trad('en', $lang) . ' '.$poblacion;
				if ($dormitorios=='any'){ $frase = $tipo.' ' . trad('en', $language) . ' '.$poblacion; }
				break;		
		}
		return $frase;
	}
	
	
}
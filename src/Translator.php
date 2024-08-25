<?php

	namespace SosoRicsi\Translator;

	use Exception;

	/* 
	|----------------------------------------------------------------------------
	| A simple Translator class made by SosoRicsi
	|----------------------------------------------------------------------------
	*/

	class Translator
	{
		/* 
		|----------------------------------------------------------------------------
		| Translates a given key based on the specified language.
		| 
		| This method loads a language file, retrieves the translation for the 
		| given key, and applies an optional modifier to the translation before 
		| returning it. If the language file or translation key does not exist, 
		| an exception is thrown.
		|
		| @param string $language The language code to determine which language file to load.
		| @param string $key The translation key to retrieve the corresponding translated string.
		| @param string|null $modifier Optional. A modifier that can transform the translation.
		|                              Available modifiers:
		|                              - 'upper': Converts the translation to uppercase.
		|                              - 'lower': Converts the translation to lowercase.
		|                              - 'dump': Returns a detailed string representation of the variable's structure, similar to var_dump.
		|                              Default is an empty string, meaning no modification.
		|
		| @return string The translated and possibly modified string.
		|
		| @throws Exception If the language file does not exist, or if the file does not 
		|                    return an array, or if the translation key is not found.
		|----------------------------------------------------------------------------
		*/
    		public static function translate(string $key, ?string $language, ?string $modifier = ''): string
    		{

			$language = strtoupper($language);

			$translationFile = __DIR__."/language/{$language}.php";

			if (!file_exists($translationFile)) {
				throw new Exception("Translator file for [{$language}] not found!");
			}

			$translations = include($translationFile);

			if (!is_array($translations)) {
				throw new Exception("Translation file [{$translationFile}] must return an array. [".gettype($translations)."] given.");
			}

			if (!array_key_exists($key, $translations)) {
				throw new Exception("Translation key [{$key}] not found in language [{$language}] at [{$translationFile}]!");
			}

			$variable = $translations[$key];

			switch ($modifier) {
				case 'upper':
					return strtoupper($variable);
				case 'lower':
					return strtolower($variable);
				case 'dump':
					return gettype($variable)."(".mb_strlen($variable, 'UTF-8').") => ".var_export($variable, true);
				default:
					return $variable;
			}
		}
	}

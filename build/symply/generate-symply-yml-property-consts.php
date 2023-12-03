<?php


declare(strict_types=1);

use pocketmine\utils\Filesystem;
use pocketmine\utils\Utils;

require dirname(__DIR__) . '/vendor/autoload.php';

$defaultConfig = yaml_parse(Filesystem::fileGetContents(dirname(__DIR__) . '/resources/symply.yml'));

if(!is_array($defaultConfig)){
	fwrite(STDERR, "Invalid default symply.yml\n");
	exit(1);
}

$constants = [];

/**
 * @param mixed[]  $properties
 * @param string[] $constants
 * @phpstan-param array<string, string> $constants
 * @phpstan-param-out array<string, string> $constants
 */
function collectProperties(string $prefix, array $properties, array &$constants) : void{
	foreach($properties as $propertyName => $property){
		$fullPropertyName = ($prefix !== "" ? $prefix . "." : "") . $propertyName;

		$constName = str_replace([".", "-"], "_", strtoupper($fullPropertyName));
		$constants[$constName] = $fullPropertyName;

		if(is_array($property)){
			collectProperties($fullPropertyName, $property, $constants);
		}
	}
}

collectProperties("", $defaultConfig, $constants);
ksort($constants, SORT_STRING);

$file = fopen(dirname(__DIR__) . '/symply/YmlSymplyProperties.php', 'wb');
if($file === false){
	fwrite(STDERR, "Failed to open output file\n");
	exit(1);
}
fwrite($file, "<?php\n");
fwrite($file, <<<'HEADER'

/*
 *
 *  _____                       _       
 * /  ___|                     | |      
 * \ `--. _   _ _ __ ___  _ __ | |_   _ 
 *  `--. \ | | | '_ ` _ \| '_ \| | | | |
 * /\__/ / |_| | | | | | | |_) | | |_| |
 * \____/ \__, |_| |_| |_| .__/|_|\__, |
 *         __/ |         | |       __/ |
 *        |___/          |_|      |___/ 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Symply Team
 * @link http://www.symplymc.com/
 *
 *
 */


HEADER
);
fwrite($file, "declare(strict_types=1);\n\n");
fwrite($file, "namespace symply;\n\n");

fwrite($file, <<<'DOC'
/**
 * @internal
 * Constants for all properties available in symply.yml.
 * This is generated by build/generate-symply-yml-property-consts.php.
 * Do not edit this file manually.
 */

DOC
);
fwrite($file, "final class YmlSymplyProperties{\n");
fwrite($file, <<<'CONSTRUCTOR'

	private function __construct(){
		//NOOP
	}


CONSTRUCTOR
);
foreach(Utils::stringifyKeys($constants) as $constName => $propertyName){
	fwrite($file, "\tpublic const $constName = '$propertyName';\n");
}
fwrite($file, "}\n");

fclose($file);

echo "Done. Don't forget to run CS fixup after generating code.\n";

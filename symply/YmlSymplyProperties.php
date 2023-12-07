<?php

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

declare(strict_types=1);

namespace symply;

/**
 * @internal
 * Constants for all properties available in symply.yml.
 * This is generated by build/generate-symply-yml-property-consts.php.
 * Do not edit this file manually.
 */
final class YmlSymplyProperties{

	private function __construct(){
		//NOOP
	}

	public const PERSONA_SKIN = 'persona-skin';
	public const SERVER_BREAK_SIDE = 'server-break-side';
	public const WATERDOGPE_SUPPORT = 'waterdogpe-support';
}

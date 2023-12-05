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

namespace symply\behavior\items;

use pocketmine\item\Item;
use symply\behavior\items\builder\ItemBuilder;
use function assert;

abstract class ItemCustom extends Item
{
	public function __construct(ItemCustomIdentifier $identifier,string $name = "Unknown", array $enchantmentTags = [])
	{
		parent::__construct($identifier, $name, $enchantmentTags);
	}
	public function getIdentifier() : ItemCustomIdentifier
	{
		$identifier = parent::getIdentifier();
		assert($identifier instanceof  ItemCustomIdentifier);
		return $identifier;
	}
	public function getItemBuilder() : ItemBuilder{
		return ItemBuilder::create()
			->setItem($this)
			->setDefaultMaxStack()
			->setDefaultName();
	}
}

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

namespace symply\behavior\items\component;

use pocketmine\nbt\tag\CompoundTag;
use symply\behavior\common\component\IComponent;

class BlockPlacerComponent implements IComponent
{

	public function __construct(private readonly string $blockIdentifier, private readonly bool $useBlockDescription = false)
	{
	}

	public function getBlockIdentifier() : string
	{
		return $this->blockIdentifier;
	}

	public function isUseBlockDescription() : bool
	{
		return $this->useBlockDescription;
	}

	public function toNbt() : CompoundTag
	{
	   return CompoundTag::create()->setTag("minecraft:block_placer", CompoundTag::create()
	   ->setString("block", $this->getBlockIdentifier())
	   ->setByte("use_block_description", $this->isUseBlockDescription() ? 1 : 0));
	}

	public function getName() : string
	{
		return "minecraft:block_placer";
	}
}

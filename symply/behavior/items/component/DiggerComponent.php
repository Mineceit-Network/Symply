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

use pocketmine\block\Block;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use symply\behavior\common\component\IComponent;
use function array_map;
use function implode;
use function is_string;

class DiggerComponent implements IComponent
{
	/** @var ListTag<CompoundTag> */
	private ListTag $diggers;

	private bool $efficiency = false;

	public function __construct()
	{
		$this->diggers = new ListTag([]);
	}

	public static function create() : self{
		return new self();
	}

	public function getDiggers() : ListTag
	{
		return $this->diggers;
	}

	public function setEfficiency(bool $efficiency = true) : void
	{
		$this->efficiency = $efficiency;
	}

	public function isEfficiency() : bool
	{
		return $this->efficiency;
	}

	public function addBlock(Block|string $block, int $speed) : self{
		$this->diggers->push(CompoundTag::create()->setTag("block", CompoundTag::create()
		->setString("name", is_string($block) ? $block : GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName()))
		->setInt("speed", $speed));
		return $this;
	}

	/**
	 * @return $this
	 */
	public function withBlocks(int $speed, Block|string ...$blocks) : self{
		foreach ($blocks as $block){
			$this->addBlock($block, $speed);
		}
		return $this;
	}

	public function addTag(array|string $tags, int $speed) : self{
		$this->diggers->push(CompoundTag::create()->setTag("block", CompoundTag::create()
			->setString("tags", "query.any_tag(" . (is_string($tags) ? "'$tags'" : implode(", ", array_map(fn (string $tag) => "'$tag'", $tags))) . ")"))
			->setInt("speed", $speed  ));
		return $this;
	}

	public function toNbt() : CompoundTag
	{
	   return CompoundTag::create()
		   ->setTag($this->getName(), CompoundTag::create()
			   ->setTag("destroy_speeds", $this->getDiggers())
			   ->setTag("on_dig", CompoundTag::create())
			   ->setByte("use_efficiency", $this->isEfficiency() ? 1 : 0));
	}

	public function getName() : string
	{
		return "minecraft:digger";
	}
}

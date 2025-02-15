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

namespace symply\behavior\blocks;

use pocketmine\block\BlockTypeInfo;
use pocketmine\block\Opaque as PMOpaque;
use symply\behavior\blocks\builder\BlockPermutationBuilder;
use symply\behavior\blocks\info\BlockCreativeInfo;
use symply\behavior\common\enum\CategoryCreativeEnum;
use symply\behavior\common\enum\GroupCreativeEnum;
use function assert;

abstract class OpaquePermutation extends PMOpaque implements IPermutationBlock
{

	public function __construct(
		BlockIdentifier $idInfo,
		string          $name,
		BlockTypeInfo   $typeInfo
	)
	{
		parent::__construct($idInfo, $name, $typeInfo);
	}

	public function getIdInfo() : BlockIdentifier
	{
		$idInfo = parent::getIdInfo();
		assert($idInfo instanceof BlockIdentifier);
		return $idInfo;
	}

	public function getBlockBuilder() : BlockPermutationBuilder
	{
		return BlockPermutationBuilder::create()
			->setBlock($this)
			->setUnitCube()
			->setCreativeInfo(new BlockCreativeInfo(CategoryCreativeEnum::CONSTRUCTION, GroupCreativeEnum::NONE));
	}
}

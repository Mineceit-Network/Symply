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

namespace symply\behavior;

use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\network\mcpe\convert\BlockStateDictionaryEntry;
use pocketmine\network\mcpe\convert\BlockTranslator;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\SingletonTrait;
use ReflectionProperty;
use function array_keys;
use function count;
use function hash;
use function strcmp;
use function usort;

final class SymplyBlockPalette
{
	use SingletonTrait;

	/** @var BlockStateDictionaryEntry[] */
	private array $states;
	/** @var BlockStateDictionaryEntry[] */
	private array $customStates = [];

	private BlockTranslator $translator;
	private ReflectionProperty $bedrockKnownStates;
	private ReflectionProperty $stateDataToStateIdLookup;
	private ReflectionProperty $idMetaToStateIdLookupCache;
	private ReflectionProperty $fallbackStateId;

	public function __construct() {
		$this->translator = $instance = TypeConverter::getInstance()->getBlockTranslator();
		$dictionary = $instance->getBlockStateDictionary();
		$this->states = $dictionary->getStates();

		$this->bedrockKnownStates = new ReflectionProperty($dictionary, "states");
		$this->stateDataToStateIdLookup = new ReflectionProperty($dictionary, "stateDataToStateIdLookup");
		$this->idMetaToStateIdLookupCache = new ReflectionProperty($dictionary, "idMetaToStateIdLookupCache");
		$this->fallbackStateId = new ReflectionProperty($instance, "fallbackStateId");
	}

	/**
	 * @return BlockStateDictionaryEntry[]
	 */
	public function getStates() : array {
		return $this->states;
	}

	/**
	 * @return BlockStateDictionaryEntry[]
	 */
	public function getCustomStates() : array {
		return $this->customStates;
	}

	/**
	 * Inserts the provided state in to the correct position of the palette.
	 */
	public function insertState(BlockStateDictionaryEntry $entry) : void {
		$this->sortWith($entry);
	}

	/**
	 * Sorts the palette's block states in the correct order, also adding the provided state to the array.
	 */
	private function sortWith(BlockStateDictionaryEntry $newState) : void {
		// To sort the block palette we first have to split the palette up in to groups of states. We only want to sort
		// using the name of the block, and keeping the order of the existing states.
		$states = [];
		foreach($this->getStates() as $state){
			$states[$state->getStateName()][] = $state;
		}
		// Append the new state we are sorting with at the end to preserve existing order.
		$states[$newState->getStateName()][] = $newState;

		$names = array_keys($states);
		// As of 1.18.30, blocks are sorted using a fnv164 hash of their names.
		usort($names, static fn(string $a, string $b) => strcmp(hash("fnv164", $a), hash("fnv164", $b)));
		$sortedStates = [];
		$stateId = 0;
		$stateDataToStateIdLookup = [];
		foreach($names as $name){
			// With the sorted list of names, we can now go back and add all the states for each block in the correct order.
			foreach($states[$name] as $state){
				$sortedStates[$stateId] = $state;
				if(count($states[$name]) === 1) {
					$stateDataToStateIdLookup[$name] = $stateId;
				}else{
					$stateDataToStateIdLookup[$name][$state->getRawStateProperties()] = $stateId;
				}
				$stateId++;
			}
		}
		$this->states = $sortedStates;
		$dictionary = $this->translator->getBlockStateDictionary();
		$this->bedrockKnownStates->setValue($dictionary, $sortedStates);
		$this->stateDataToStateIdLookup->setValue($dictionary, $stateDataToStateIdLookup);
		$this->idMetaToStateIdLookupCache->setValue($dictionary, null); //set this to null so pm can create a new cache
		$this->fallbackStateId->setValue($this->translator, $stateDataToStateIdLookup[BlockTypeNames::INFO_UPDATE] ??
			throw new AssumptionFailedError(BlockTypeNames::INFO_UPDATE . " should always exist")
		);
	}
}

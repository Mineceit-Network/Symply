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

namespace symply\waterdogpe;

/**
 * Model class for LoginPacket JSON data for JsonMapper
 */
final class WDPEClientDataAnimationFrame{

	/** @required */
	public int $ImageHeight;

	/** @required */
	public int $ImageWidth;

	/** @required */
	public float $Frames;

	/** @required */
	public int $Type;

	/** @required */
	public string $Image;

	/** @required */
	public int $AnimationExpression;
}

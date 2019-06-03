<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

namespace phpbb\textreparser;

interface reparser_interface
{
	/**
	* Return the highest ID for all existing records
	*
	* @return integer
	*/
	public function get_max_id();

	/**
	 * Returns the name of the reparser
	 *
	 * @return string Name of reparser
	 */
	public function get_name();

	/**
	 * Sets the name of the reparser
	 *
	 * @param string $name The reparser name
	 */
	public function set_name($name);

	/**
	* Reparse all records in given range
	*
	* The record filter can contain any of the following elements:
	*  - text_like:   a SQL LIKE predicate applied on the text, if applicable, e.g. '<r%'
	*  - text_regexp: a regexp that matches against the text
	*  - callback:    a callback that accepts a record as argument and returns a boolean
	*
	* Only records that match all of the given filters are reparsed.
	*
	* @param integer $min_id Lower bound
	* @param integer $max_id Upper bound
	* @param array   $filter Record filter, can contain any of 'text_like', 'text_regexp', 'callback'
	*/
	public function reparse_range($min_id, $max_id, array $filter = []);
}

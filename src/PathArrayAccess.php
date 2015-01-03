<?php

namespace MONOGON;

/***************************************************************
 *
 *  Copyright notice
 *
 *  Copyright (C) 2015 R3 H6 <r3h6@outlook.com>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * PathArrayAccess
 */
class PathArrayAccess implements \ArrayAccess {

	/**
	 * Seperator
	 *
	 * @var string
	 */
	protected $seperator;

	/**
	 * Array
	 *
	 * @var [type]
	 */
	protected $array;

	// {{{

	/**
	 * Constructor
	 *
	 * @param array  $array     [description]
	 * @param string $seperator [description]
	 */
	public function __construct(array $array = array(), $seperator = '.') {
		$this->array = $array;
		$this->seperator = $seperator;
	}

	// }}}
	// {{{ Methods

	/**
	 * Sets a value in the array at the path position.
	 *
	 * @param string $path example.layoutPath
	 * @param mixed $value [description]
	 * @return PathArrayAccess $this
	 */
	final public function set ($path, $value, $force = FALSE){
		$pathParts = $this->getPathParts($path);
		$reference = &$this->array;
		foreach ($pathParts as $pathPart){
			if (!is_array($reference)){
				if ($force){
					$reference = array();
				} else {
					throw new \Exception("Illegal path part '$path'!", 1420314548);
				}
			}
			if (isset($reference[$pathPart])){
				$reference = &$reference[$pathPart];
			} else {
				$reference[$pathPart] = array();
				$reference = &$reference[$pathPart];
			}
		}
		$reference = $value;
		return $this;
	}

	/**
	 * Returns the path value or a default value.
	 *
	 * @param  string $path example.layoutPath
	 * @return mixed      [description]
	 */
	final public function get ($path, $default = NULL) {
		$value = $default;
		$pathParts = $this->getPathParts($path);
		$reference = &$this->array;
		foreach ($pathParts as $pathPart){
			if (isset($reference[$pathPart])){
				$reference = &$reference[$pathPart];
				$value = $reference;
			} else {
				break;
			}
		}
		return $value;
	}

	/**
	 * Returns the seperator
	 *
	 * @return [type] $seperator
	 */
	final public function getSeperator(){
		return $this->seperator;
	}

	/**
	 * Sets the seperator
	 *
	 * @param [type] $seperator
	 * @return object $this
	 */
	final public function setSeperator($seperator){
		$this->seperator = $seperator;
		return $this;
	}

	/**
	 * Get the path parts
	 *
	 * @param  string $path [description]
	 * @return array      [description]
	 */
	final private function getPathParts($path){
		return explode($this->seperator, $path);
	}

	/**
	 * Returns the array
	 *
	 * @return array [description]
	 */
	final public function toArray(){
		return $this->array;
	}

	// }}}
	// {{{ ArrayAccess

	final public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->array[] = $value;
		} else {
			$this->array[$offset] = $value;
		}
	}

	final public function offsetExists($offset) {
		return isset($this->array[$offset]);
	}

	final public function offsetUnset($offset) {
		unset($this->array[$offset]);
	}

	final public function offsetGet($offset) {
		return isset($this->array[$offset]) ? $this->array[$offset] : null;
	}

	// }}}
}

?>
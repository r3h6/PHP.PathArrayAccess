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

require_once dirname(__FILE__) . '/../src/PathArrayAccess.php';

/**
 * PathArrayAccessTest
 */
class PathArrayAccessTest extends \PHPUnit_Framework_TestCase {

	protected $example = array(
		'a' => 'A',
		'b' => array(
			'ba' => 'BA',
			'bb' => 'BB',
		),
		'c' => 'C',
	);

	protected $subject;

	public function setUp (){
		$this->subject =  new \MONOGON\PathArrayAccess($this->example);
	}
	public function tearDown (){
		unset($this->subject);
	}

	public function testConstructor (){
		$this->assertEquals($this->example, $this->subject->toArray());
	}

	public function testSetGetSeperator (){
		$expected = '/';
		$this->subject->setSeperator($expected);
		$this->assertEquals($expected, $this->subject->getSeperator());
	}

	public function testGet (){
		$path = 'b.bb';
		$expected = 'BB';
		$this->assertEquals($expected, $this->subject->get($path));
	}

	public function testOverride (){
		$path = 'b.bb';
		$expected = '**';
		$this->subject->set($path, $expected);
		$this->assertEquals($expected, $this->subject->get($path));
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testException (){
		$this->subject->set('c.ca.caa', 'CCA');
	}

	public function testPathNull (){
		$this->subject->set(NULL, $this->example);
		$this->assertEquals($this->example, $this->subject->toArray());
	}

	public function testPathEmpty (){
		$this->subject->set('', $this->example);
		$this->assertEquals($this->example, $this->subject->toArray());
	}

	public function testPathEmptyTypeCast (){
		$this->subject->set('', 'A');
		$this->assertEquals(array('A'), $this->subject->toArray());
	}

	public function testSetGet (){
		$expected = 'CCA';
		$path = 'c.ca.caa';
		$this->subject->set($path, $expected, TRUE);
		$this->assertEquals($expected, $this->subject->get($path));
	}

	public function testDefault (){
		$expected = TRUE;
		$this->assertEquals($expected, $this->subject->get('a.aa.aaa', $expected));
	}
}

?>
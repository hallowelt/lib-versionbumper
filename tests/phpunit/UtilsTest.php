<?php

namespace HalloWelt\Lib\VersionBumper\Tests;

use Exception;
use HalloWelt\Lib\VersionBumper\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase {

	/**
	 * @param string $version
	 * @param string $versionList
	 * @param string $expectedPreviousVersion
	 * @param bool $expectsException
	 * @return void
	 * @covers HalloWelt\Lib\VersionBumper\Utils::getPreviousVersion
	 * @dataProvider provideTestGetPreviousVersionData
	 */
	public function testGetPreviousVersion( $version, $versionList, $expectedPreviousVersion, $expectsException ) {
		$bumper = new Utils();

		if ( $expectsException ) {
			$this->expectException( Exception::class );
		}

		$previousVersion = $bumper->getPreviousVersion( $version, $versionList );
		$this->assertEquals( $expectedPreviousVersion, $previousVersion );
	}

	public function provideTestGetPreviousVersionData() {
		return [
			[ '1.0.1', [ '1.0.2', '1.0.1', '1.0.0', '0.9.7' ], '1.0.0', false ],
			[ '1.0.0', [ '1.0.1', '1.0.0', '0.9.7', '0.9.7-beta', '0.9.6' ], '0.9.7', false ],
			[ '1.0.0', [ '1.0.1', '1.0.0' ], '-', true ],
		];
	}

	/**
	 * @param string $version
	 * @param string $previousVersion
	 * @param string $expectedType
	 * @param bool $expectsException
	 * @covers HalloWelt\Lib\VersionBumper\Utils::getUpdateType
	 * @dataProvider provideTestGetUpdateType
	 */
	public function testGetUpdateType( $version, $previousVersion, $expectedType, $expectsException ) {
		$bumper = new Utils();

		if ( $expectsException ) {
			$this->expectException( Exception::class );
		}

		$type = $bumper->getUpdateType( $previousVersion, $version );
		$this->assertEquals( $expectedType, $type );
	}

	public function provideTestGetUpdateType() {
		return [
			'major-1' => [ '2.0.0', '1.0.0', Utils::TYPE_MAJOR, false ],
			'major-2' => [ '2.0.0', '1.9.1-X1', Utils::TYPE_MAJOR, false ],
			'minor' => [ '1.1.0', '1.0.0', Utils::TYPE_MINOR, false ],
			'patch' => [ '1.0.1', '1.0.0', Utils::TYPE_PATCH, false ],
			'nano' => [ '1.0.0.1', '1.0.0.0', Utils::TYPE_NANO, false ],
			'meta' => [ '1.0.1-B', '1.0.1-A', Utils::TYPE_META, false ],
		];
	}

	/**
	 * @param string $version
	 * @param bool $isValid
	 * @return void
	 * @covers HalloWelt\Lib\VersionBumper\Utils::isValidVersion
	 * @dataProvider provideTestIsValidVersionData
	 */
	public function testIsValidVersion( $version, $isValid ) {
		$bumper = new Utils();
		$this->assertEquals( $isValid, $bumper->isValidVersion( $version ) );
	}

	public function provideTestIsValidVersionData() {
		return [
			'valid-1' => [ '1.0.0', true ],
			'valid-2' => [ '1.0.0.2', true ],
			'valid-3' => [ '1.0.0-test', true ],
			'valid-4' => [ '1', true ],
			'valid-5' => [ '1.0', true ],
			'invalid-1' => [ 'SOMETHING', false ],
		];
	}
}

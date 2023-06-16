<?php

namespace HalloWelt\Lib\VersionBumper\Tests;

use HalloWelt\Lib\VersionBumper\VersionBumper;
use PHPUnit\Framework\TestCase;

class VersionBumperTest extends TestCase {

	/**
	 * @param string $baseVersion
	 * @param string $prerelease
	 * @param string $expectedVersion
	 * @return void
	 * @covers HalloWelt\Lib\VersionBumper\VersionBumper::bumpPRERELEASE
	 * @dataProvider provideTestBumpPRERELEASEData
	 */
	public function testBumpPRERELEASE( $baseVersion, $prerelease, $expectedVersion ) {
		$bumper = new VersionBumper();

		$actualVersion = $bumper->bumpPRERELEASE( $baseVersion, $prerelease );
		$this->assertEquals( $expectedVersion, $actualVersion );
	}

	public function provideTestBumpPRERELEASEData() {
		return [
			[ '0.0.1', 'beta', '0.0.1-beta.1' ],
			[ '0.0.1-beta', 'beta', '0.0.1-beta.2' ],
			[ '0.0.1-beta', 'rc', '0.0.1-rc.1' ],
			[ '0.0.1-beta.5', 'rc', '0.0.1-rc.1' ],
			[ '0.0.1-beta+ABC', 'beta', '0.0.1-beta.2' ]
		];
	}

	/**
	 * @param string $baseVersion
	 * @param string $expectedVersion
	 * @return void
	 * @covers HalloWelt\Lib\VersionBumper\VersionBumper::bumpMAJOR
	 * @dataProvider provideTestBumpMAJORData
	 */
	public function testBumpMAJOR( $baseVersion, $expectedVersion ) {
		$bumper = new VersionBumper();

		$actualVersion = $bumper->bumpMAJOR( $baseVersion );
		$this->assertEquals( $expectedVersion, $actualVersion );
	}

	public function provideTestBumpMAJORData() {
		return [
			[ '0.0.1', '1.0.0' ],
			[ '0.0.1-beta', '1.0.0' ],
			[ '1.0.1-beta', '2.0.0' ],
			[ '0.0.1-beta.5', '1.0.0' ],
			[ '0.0.1-beta+ABC', '1.0.0' ]
		];
	}

	/**
	 * @param string $baseVersion
	 * @param string $expectedVersion
	 * @return void
	 * @covers HalloWelt\Lib\VersionBumper\VersionBumper::bumpMINOR
	 * @dataProvider provideTestBumpMINORData
	 */
	public function testBumpMINOR( $baseVersion, $expectedVersion ) {
		$bumper = new VersionBumper();

		$actualVersion = $bumper->bumpMINOR( $baseVersion );
		$this->assertEquals( $expectedVersion, $actualVersion );
	}

	public function provideTestBumpMINORData() {
		return [
			[ '0.0.1', '0.1.0' ],
			[ '0.0.1-beta', '0.1.0' ],
			[ '1.0.1-beta', '1.1.0' ],
			[ '0.1.1-beta.5', '0.2.0' ],
			[ '0.0.1-beta+ABC', '0.1.0' ]
		];
	}

	/**
	 * @param string $baseVersion
	 * @param string $expectedVersion
	 * @return void
	 * @covers HalloWelt\Lib\VersionBumper\VersionBumper::bumpPATCH
	 * @dataProvider provideTestBumpPATCHData
	 */
	public function testBumpPATCH( $baseVersion, $expectedVersion ) {
		$bumper = new VersionBumper();

		$actualVersion = $bumper->bumpPATCH( $baseVersion );
		$this->assertEquals( $expectedVersion, $actualVersion );
	}

	public function provideTestBumpPATCHData() {
		return [
			[ '0.0.1', '0.0.2' ],
			[ '1.0.1', '1.0.2' ],
			[ '1.1.1', '1.1.2' ],
			[ '0.0.1-beta', '0.0.1' ],
			[ '1.0.1-beta', '1.0.1' ],
			[ '0.1.1-beta.5', '0.1.1' ],
			[ '0.0.1-beta+ABC', '0.0.1' ]
		];
	}

	/**
	 * @param string $baseVersion
	 * @param string $meta
	 * @param string $expectedVersion
	 * @return void
	 * @covers HalloWelt\Lib\VersionBumper\VersionBumper::setMETADATA
	 * @dataProvider provideTestSetMETADATAData
	 */
	public function testSetMETADATA( $baseVersion, $meta, $expectedVersion ) {
		$bumper = new VersionBumper();

		$actualVersion = $bumper->setMETADATA( $baseVersion, $meta );
		$this->assertEquals( $expectedVersion, $actualVersion );
	}

	public function provideTestSetMETADATAData() {
		return [
			[ '0.0.1', 'ABC', '0.0.1+ABC' ],
			[ '0.0.1-beta', 'ABC', '0.0.1-beta.1+ABC' ],
			[ '1.0.1-beta', 'ABC', '1.0.1-beta.1+ABC' ],
			[ '0.1.1-beta.5', 'ABC', '0.1.1-beta.5+ABC' ],
			[ '0.0.1-beta+ABC', 'DEF', '0.0.1-beta.1+DEF' ]
		];
	}

	/**
	 * @return void
	 * @covers HalloWelt\Lib\VersionBumper\VersionBumper::setMETADATA
	 */
	public function testREADMEMDExample() {
		$bumper = new VersionBumper();
		$this->assertEquals( "2.0.0", $bumper->bumpMAJOR( '1.1.6' ) );
		$this->assertEquals( "1.2.0",  $bumper->bumpMINOR( '1.1.6' ) );
		$this->assertEquals( "1.1.7", $bumper->bumpPATCH( '1.1.6' ) );
		$this->assertEquals( "1.1.6-rc.1", $bumper->bumpPRERELEASE( '1.1.6', 'rc' ) );
		$this->assertEquals( "1.1.6-beta.1+WEEK27", $bumper->setMETADATA( '1.1.6-beta', 'WEEK27' ) );
	}

}

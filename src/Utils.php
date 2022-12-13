<?php

namespace HalloWelt\Lib\VersionBumper;

use Exception;
use UnexpectedValueException;

class Utils {

	public const TYPE_MAJOR = 'MAJOR';
	public const TYPE_MINOR = 'MINOR';
	public const TYPE_PATCH = 'PATCH';
	public const TYPE_NANO = 'NANO';
	public const TYPE_META = 'META';

	/**
	 * Calculates the type of update based on the
	 * @param string $currentVersion
	 * @param string $newVersion
	 * @return string One of the constants TYPE_MAJOR|TYPE_MINOR|TYPE_PATCH|TYPE_NANO|TYPE_META
	 */
	public function getUpdateType( $currentVersion, $newVersion ) {
		if ( !$this->isValidVersion( $currentVersion ) ) {
			throw new UnexpectedValueException( 'Invalid current version provided' );
		}
		if ( !$this->isValidVersion( $newVersion ) ) {
			throw new UnexpectedValueException( 'Invalid new version provided' );
		}
		$currentVersionParts = explode( '-', $currentVersion, 2 );
		$currentVersionMeta = $currentVersionParts[1] ?? '';
		$actualCurrentVersion = $currentVersionParts[0];
		$currentVersionParts = explode( '.', $actualCurrentVersion );
		$currentVersionParts = $currentVersionParts + [ '0', '0', '0', '0' ];

		$newVersionParts = explode( '-', $newVersion, 2 );
		$newVersionMeta = $newVersionParts[1] ?? '';
		$actualNewVersion = $newVersionParts[0];
		$newVersionParts = explode( '.', $actualNewVersion );
		$newVersionParts = $newVersionParts + [ '0', '0', '0', '0' ];

		// MAJOR
		if ( $currentVersionParts[0] !== $newVersionParts[0] ) {
			return self::TYPE_MAJOR;
		}

		// MINOR
		if ( $currentVersionParts[1] !== $newVersionParts[1] ) {
			return self::TYPE_MINOR;
		}

		// PATCH
		if ( $currentVersionParts[2] !== $newVersionParts[2] ) {
			return self::TYPE_PATCH;
		}

		// NANO
		if ( $currentVersionParts[3] !== $newVersionParts[3] ) {
			return self::TYPE_NANO;
		}

		// META
		if ( $currentVersionMeta !== $newVersionMeta ) {
			return self::TYPE_META;
		}
	}

	/**
	 * @param string $version
	 * @return bool
	 */
	public function isValidVersion( $version ) {
		$versionParts = explode( '-', $version, 2 );
		$actualVersion = $versionParts[0];

		$actualVersionParts = explode( '.', $actualVersion );
		// MAJOR
		if ( !is_numeric( $actualVersionParts[0] ) ) {
			return false;
		}

		// MINOR
		if ( isset( $actualVersionParts[1] ) && !is_numeric( $actualVersionParts[1] ) ) {
			return false;
		}

		// PATCH
		if ( isset( $actualVersionParts[2] ) && !is_numeric( $actualVersionParts[2] ) ) {
			return false;
		}

		// NANO
		if ( isset( $actualVersionParts[3] ) && !is_numeric( $actualVersionParts[3] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Calculates the type of update based on the
	 * @param string $currentVersion
	 * @param array $previousVersions
	 * @return string The previous version
	 * @throws Exception In case no previous version was found in the provided list
	 */
	public function getPreviousVersion( $currentVersion, $previousVersions ) {
		if ( !$this->isValidVersion( $currentVersion ) ) {
			throw new UnexpectedValueException( 'Invalid current version provided' );
		}
		if ( !is_array( $previousVersions ) ) {
			throw new UnexpectedValueException( 'Invalid previous versions provided' );
		}

		$previousVersion = null;
		foreach ( $previousVersions as $version ) {
			if ( $this->isValidVersion( $version ) ) {
				if ( version_compare( $version, $currentVersion, '<' ) ) {
					$previousVersion = $version;
					break;
				}
			}
		}

		if ( $previousVersion === null ) {
			throw new Exception( 'No previous version found!' );
		}

		return $previousVersion;
	}
}

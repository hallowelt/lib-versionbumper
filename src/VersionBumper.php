<?php

namespace HalloWelt\Lib\VersionBumper;

use UnexpectedValueException;

class VersionBumper {

	/**
	 * @var integer
	 */
	private $major = 0;

	/**
	 * @var integer
	 */
	private $minor = 0;

	/**
	 * @var integer
	 */
	private $patch = 0;

	/**
	 * @var integer
	 */
	private $prerelease = 0;

	/**
	 * @var string
	 */
	private $prereleasetype = '';

	/**
	 * @var array
	 */
	private $allowedPrereleaseTypes = [ 'alpha', 'beta', 'rc' ];

	/**
	 * @var int
	 */
	private $meta = '';

	/**
	 * @param string $version - The base version
	 * @return string The resulting version
	 */
	public function bumpPATCH( $version ) : string {
		$this->parseVersion( $version );
		$this->patch++;
		$this->clearPrerelease();
		$this->clearMeta();

		return $this->composeBumpedVersion();
	}

	/**
	 * @param string $version - The base version
	 * @return string The resulting version
	 */
	public function bumpMINOR( $version ) : string {
		$this->parseVersion( $version );
		$this->minor++;
		$this->patch = 0;
		$this->clearPrerelease();
		$this->clearMeta();

		return $this->composeBumpedVersion();
	}

	/**
	 * @param string $version - The base version
	 * @return string The resulting version
	 */
	public function bumpMAJOR( $version ) : string {
		$this->parseVersion( $version );
		$this->major++;
		$this->minor = 0;
		$this->patch = 0;
		$this->clearPrerelease();
		$this->clearMeta();

		return $this->composeBumpedVersion();
	}

	/**
	 * @param string $version - The base version
	 * @param string $prereleasetype string e.g. "alpha", "beta", "rc"
	 * @return string The resulting version
	 */
	public function bumpPRERELEASE( $version, $prereleasetype ) : string {
		$this->parseVersion( $version );
		$normalPrereleasetype = $this->normalizePrereleaseType( $prereleasetype );
		if ( $this->prereleasetype != $normalPrereleasetype ) {
			$this->prerelease = 0;
		}
		$this->prereleasetype = $normalPrereleasetype;
		$this->prerelease++;
		$this->clearMeta();
		return $this->composeBumpedVersion();
	}

	/**
	 * @param string $version - The base version
	 * @param string $meta
	 * @return string The resulting version
	 */
	public function setMETADATA( $version, $meta ) : string {
		$this->parseVersion( $version );
		$this->meta = $meta;

		return $this->composeBumpedVersion();
	}

	private function composeBumpedVersion() {
		$bumpedVersion = $this->major;
		$bumpedVersion .= '.' . $this->minor;
		$bumpedVersion .= '.' . $this->patch;

		if ( !empty( $this->prereleasetype ) ) {
			$bumpedVersion .= '-' . $this->prereleasetype;
			$bumpedVersion .= '.' . $this->prerelease;
		}

		if ( !empty( $this->meta ) ) {
			$bumpedVersion .= '+' . $this->meta;
		}

		return $bumpedVersion;
	}

	/**
	 * @param string $version
	 * @return void
	 */
	private function parseVersion( $version ) {
		$parts = explode( '+', $version );
		$versionWithoutMeta = $parts[0];

		$this->clearMeta();
		if ( isset( $parts[1] ) ) {
			$this->meta = $parts[1];
		}

		$parts2 = explode( '-', $versionWithoutMeta );
		$versionWithoutRereleaseAndMeta = $parts2[0];

		$this->clearPrerelease();
		if ( isset( $parts2[1] ) ) {
			$preRelaseParts = explode( '.', $parts2[1] );
			$normalPrereleasetype = $this->normalizePrereleaseType( $preRelaseParts[0] );
			$this->prereleasetype = $normalPrereleasetype;
			if ( isset( $preRelaseParts[1] ) ) {
				$this->prerelease = (int)$preRelaseParts[1];
			} else {
				$this->prerelease = 1;
			}
		}

		$parts3 = explode( '.', $versionWithoutRereleaseAndMeta );
		$this->major = $this->minor = $this->patch = 0;
		$this->major = (int)$parts3[0];
		if ( isset( $parts3[1] ) ) {
			$this->minor = (int)$parts3[1];
		}
		if ( isset( $parts3[2] ) ) {
			$this->patch = (int)$parts3[2];
		}
	}

	private function clearMeta() {
		$this->meta = '';
	}

	private function clearPrerelease() {
		$this->prereleasetype = '';
		$this->prerelease = 0;
	}

	/**
	 * @param string $prereleasetype
	 * @return string
	 */
	private function normalizePrereleaseType( $prereleasetype ) : string {
		$lcPrereleasetype = strtolower( $prereleasetype );
		if ( !in_array( $lcPrereleasetype, $this->allowedPrereleaseTypes ) ) {
			$allowedPrereleaseTypesList = implode( "', '", $this->allowedPrereleaseTypes );
			throw new UnexpectedValueException(
				"Provided releasetype '$prereleasetype' is not valid. "
				. "Must be one of '$allowedPrereleaseTypesList'!"
			);
		}
		return $lcPrereleasetype;
	}
}

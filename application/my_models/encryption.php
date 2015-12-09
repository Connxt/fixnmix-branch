<?php

class Encryption {
	private $encryption_key = 'wdo';

	public function encrypt($str) {
		return base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $this->encryption_key ), $str, MCRYPT_MODE_CBC, md5( md5( $this->encryption_key ) ) ) );
	}

	public function decrypt($str) {
		return rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $this->encryption_key ), base64_decode( $str ), MCRYPT_MODE_CBC, md5( md5( $this->encryption_key ) ) ), "\0");
	}
}
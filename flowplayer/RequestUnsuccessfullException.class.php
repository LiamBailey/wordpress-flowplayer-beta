<?php

namespace Flowplayer {
	class RequestUnsuccessfullException extends \Exception {
		public function __construct( $msg ) {
			parent::__construct( $msg );
		}
	}
}
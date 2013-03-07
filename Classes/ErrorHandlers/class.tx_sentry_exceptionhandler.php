<?php

class tx_sentry_exceptionhandler implements t3lib_error_ExceptionHandlerInterface {

	static protected $oldExceptionHandler;

	/** @var Raven_ErrorHandler */
	static protected $ravenErrorHandler;

	/**
	 * Constructs this exception handler - registers itself as the default exception handler.
	 */
	public function __construct() {
		$extConf = @unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['sentry']);
		if ($extConf['passErrorsToTypo3']) {
			if (self::$oldExceptionHandler) {
				// The code below will set up a TYPO3 exception handler
				t3lib_div::makeInstance(self::$oldExceptionHandler);
			}

			// We always register exception handler for Sentry, regardless of TYPO3 settings!
			self::$ravenErrorHandler->registerExceptionHandler(true);
		}
	}

	/**
	 * Handles the given exception
	 *
	 * @param Exception $exception: The exception object
	 * @return void
	 */
	public function handleException(Exception $exception) {
		// Empty, not used directly
	}

	/**
	 * Formats and echoes the exception as XHTML.
	 *
	 * @param  Exception $exception The exception object
	 * @return void
	 */
	public function echoExceptionWeb(Exception $exception) {
		// Empty, not used directly
	}

	/**
	 * Formats and echoes the exception for the command line
	 *
	 * @param Exception $exception The exception object
	 * @return void
	 */
	public function echoExceptionCLI(Exception $exception) {
		// Empty, not used directly
	}

	public static function initialize($ravenErrorHandler) {
		self::$ravenErrorHandler = $ravenErrorHandler;

		// Save old error handler
		self::$oldExceptionHandler = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['errors']['exceptionHandler'];

		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['errors']['exceptionHandler'] = 'tx_sentry_exceptionhandler';
	}
}
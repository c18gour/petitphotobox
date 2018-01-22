<?php
/**************************************************************************
 * DO NOT TOUCH THIS FILE !!!                                             *
 *                                                                        *
 * This file contains internal variables for the correct operation of     *
 * the application. Do not touch it unless you know what are you doing.   *
 **************************************************************************/

$protocol = stripos("https", $_SERVER["SERVER_PROTOCOL"]) === 0
  ? "https"
  : "http";
$clientUrl = "$protocol://" . CLIENT_URI;
header("Access-Control-Allow-Origin: $clientUrl");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: \"GET,POST,OPTIONS,DELETE,PUT\"", false);
header("Access-Control-Allow-Methods: \"Content-Type\"", false);
header("Access-Control-Allow-Methods: \"Authorization\"", false);

/**
 * Do not print error messages to the user screen.
 *
 * This feature has been disabled because when the client-side sends HTTP
 * requests it expects the response to be a well formed JSON document. For
 * example, a database connection error would append an error message to the
 * response document, ruining it.
 */
ini_set("display_errors", "Off");

/**
 * General constants.
 */

define("MIN_PASSWORD_LENGTH", 6);


/**
 * List of exception codes.
 *
 * A `client exception` is caused by the client. For example, if the user
 * enters invalid credentials from the `login` form the system throws
 * an `AuthException`. However if the session has expired the system throws
 * a `SessionError`.
 */

// Client exception codes
define("CLIENT_EXCEPTION_CODE", 400);
define("AUTH_EXCEPTION_CODE", 401);

// Application error codes
define("APP_ERROR_CODE", 500);
define("SESSION_ERROR_CODE", 501);
define("ACCESS_DENIED_ERROR_CODE", 502);
define("DATABASE_ERROR_CODE", 503);

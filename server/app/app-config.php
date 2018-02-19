<?php
session_start();

/**************************************************************************
 * DO NOT TOUCH THIS FILE !!!                                             *
 *                                                                        *
 * This file contains internal variables for the correct operation of     *
 * the application. Do not touch it unless you know what are you doing.   *
 **************************************************************************/

$protocol = stripos("https", $_SERVER["SERVER_PROTOCOL"]) === 0
  ? "https"
  : "http";
define("CLIENT_URL", "$protocol://" . CLIENT_URI);
define("CLIENT_REDIRECT_URL", CLIENT_URL . "/verify");
header("Access-Control-Allow-Origin: " . CLIENT_URL);

/**
 * Dropbox access keys.
 */
define("DROPBOX_APP_KEY", "keex5jv7y2rk0zs");
define("DROPBOX_APP_SECRET", "9mmhe17fq78gban");

/**
 * General constants.
 */
define("THUMBNAIL_WIDTH", 500);
define("IMAGE_FOLDER", "images");


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

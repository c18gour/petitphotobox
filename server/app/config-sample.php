<?php
/**
 * Client's URI (without the protocol).
 */
define("CLIENT_URI", "localhost:4200");

/**
 * Database connection.
 */
define("DBHOST", "localhost");
define("DBNAME", "<database name>");
define("DBUSER", "<username>");
define("DBPASS", "<password>");

/**
 * Do not print error messages to the user screen.
 *
 * This feature has been disabled because when the client-side sends HTTP
 * requests it expects the response to be a well formed JSON document. For
 * example, a database connection error would append an error message to the
 * response document, ruining it.
 */
ini_set("display_errors", "Off");

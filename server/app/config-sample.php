<?php

/**
 * Database connection.
 */
define("DBHOST", "localhost");
define("DBNAME", "<database name>");
define("DBUSER", "<username>");
define("DBPASS", "<password>");

/**
 * Directories.
 */
define("DOCUMENT_ROOT", rtrim($_SERVER["DOCUMENT_ROOT"], "/"));
define("USER_DATA_DIR", DOCUMENT_ROOT . "/users");

/**
 * Maximun number of items per page.
 */
define("MAX_ITEMS_PER_PAGE", 10);

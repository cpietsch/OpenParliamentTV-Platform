<?php

/**
 * The URI to your Open Parliament TV instance
 * Example: https://de.openparliament.tv
 */
$config["dir"]["root"] = "";


/**
 * Allow user to register
 * true or false
 */
$config["allow"]["register"] = true;


/**
 * Allow registered user to login
 * true or false
 */
$config["allow"]["login"] = true;


/**
 * Allow public access to all none administrative data and functions
 * true or false
 */
$config["allow"]["publicAccess"] = true;


/**
 * A complicated string to salt the user account password hashes
 */
$config["salt"] = "";


/**
 * for debugging and developing
 * Example: "dev" or "production"
 */
$config["mode"] = "production";


/**
 * E-Mail addresses of the platform
 */
$config["mail"]["from"] = "noreply@openparliament.tv";
$config["mail"]["replyto"] = "noreply@openparliament.tv";


/**
 * Platform Database (mariadb/MySQL) information
 */
$config["platform"]["sql"]["access"]["host"] = "";
$config["platform"]["sql"]["access"]["user"] = "";
$config["platform"]["sql"]["access"]["passwd"] = "";
$config["platform"]["sql"]["db"] = "";

$config["platform"]["sql"]["tbl"]["Organisation"] = "organisation";
$config["platform"]["sql"]["tbl"]["Term"] = "term";
$config["platform"]["sql"]["tbl"]["Document"] = "document";
$config["platform"]["sql"]["tbl"]["Person"] = "person";
$config["platform"]["sql"]["tbl"]["Auth"] = "auth";
$config["platform"]["sql"]["tbl"]["Conflict"] = "conflict";
$config["platform"]["sql"]["tbl"]["User"] = "user";


/**
 * Parliament Database information
 * This is an example for a parliament called "DE" (as it can be seen by the key ["DE"] at the object $config["parliament"])
 * There also can be multiple parliaments available at the platform by duplicating the configuration under different keys
 */
$config["parliament"]["DE"]["label"] = ""; //E.g. "Deutscher Bundestag"
$config["parliament"]["DE"]["sql"]["access"]["host"] = "";
$config["parliament"]["DE"]["sql"]["access"]["user"] = "";
$config["parliament"]["DE"]["sql"]["access"]["passwd"] = "";
$config["parliament"]["DE"]["sql"]["db"] = "";

$config["parliament"]["DE"]["sql"]["tbl"]["AgendaItem"] = "agendaitem";
$config["parliament"]["DE"]["sql"]["tbl"]["ElectoralPeriod"] = "electoralperiod";
$config["parliament"]["DE"]["sql"]["tbl"]["Session"] = "session";
$config["parliament"]["DE"]["sql"]["tbl"]["Media"] = "media";
$config["parliament"]["DE"]["sql"]["tbl"]["Annotation"] = "annotation";
$config["parliament"]["DE"]["sql"]["tbl"]["Text"] = "text";

/**
 * Path to the wikidata dumps. See Docs for more informations
 */
$config["parliament"]["DE"]["cache"]["wp"] = __DIR__."/data/wikidataDumps/DE";


/**
 * Configuration for the ElasticSearch or OpenSearch server
 */
$config["ES"]["hosts"] = []; // E.g. ["https://@localhost:9200"]
$config["ES"]["BasicAuthentication"]["user"] = "";
$config["ES"]["BasicAuthentication"]["passwd"] = "";
$config["ES"]["SSL"]["pem"] = "" // E.g. realpath(__DIR__."/../opensearch-root-ssl.pem");

?>
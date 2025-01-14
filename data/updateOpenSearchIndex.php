<?php
session_start();
include_once(__DIR__ . '/../modules/utilities/auth.php');

$config["ES_Offset"] = false;
/**
 * Example:
 * $config["ES_Offset"] = " LIMIT 5,1000000";
 * will continue with the 6th Media Item and will get 1000000 items in total (or less).
 */

//$config["ES_Offset"] = " LIMIT 27476,1000000";



$config["ES_Parliament_Index"] = "DE";
/**
 * Example:
 * $config["ES_Parliament_Index"] = "DE";
 * $config["ES_Parliament_Index"] = "DE-BB";
 * use the key of the $config["parliament"] object - it will be set to lowercase later.
 */


if (!array_key_exists($config["ES_Parliament_Index"], $config["parliament"])) {
    echo "Wrong configuration. Using first parliament configurated.";
    reset($config["parliament"]);
    $config["ES_Parliament_Index"] = key($config["parliament"]);
}


$auth = auth($_SESSION["userdata"]["id"], "elasticSearch", "updateIndex");
//$auth["meta"]["requestStatus"] = "success";
if (($auth["meta"]["requestStatus"] != "success") && (php_sapi_name() != "cli")) {

    $alertText = $auth["errors"][0]["detail"];
    echo $alertText;


} else {

    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

    set_time_limit(0);
    ini_set('memory_limit', '500M');
    date_default_timezone_set('CET');

    require __DIR__ . '/../vendor/autoload.php';

    require_once(__DIR__.'/../config.php');

    $ESClientBuilder = Elasticsearch\ClientBuilder::create();

    if ($config["ES"]["hosts"]) {
        $ESClientBuilder->setHosts($config["ES"]["hosts"]);
    }
    if ($config["ES"]["BasicAuthentication"]["user"]) {
        $ESClientBuilder->setBasicAuthentication($config["ES"]["BasicAuthentication"]["user"],$config["ES"]["BasicAuthentication"]["passwd"]);
    }
    if ($config["ES"]["SSL"]["pem"]) {
        $ESClientBuilder->setSSLVerification($config["ES"]["SSL"]["pem"]);
    }
    $ESClient = $ESClientBuilder->build();

    setOptions();
    updateIndex();

}


/**
 * @return mixed
 */
function setOptions()
{

    global $ESClient;
    global $config;

    $data = array();

    $data["mappings"] = array("properties" => array(
        "attributes" => array("properties" => array(
            "textContents" => array("properties" => array(
                "textHTML" => array(
                    "type" => "text",
                    "analyzer" => "html_analyzer",
                    //"analyzer" => "standard",
                    "search_analyzer" => "standard",
                    "fields" => array(
                        "autocomplete" => array(
                            "analyzer" => "autocomplete_html_analyzer",
                            "type" => "text"
                        )
                    )
                )
            ))
        )),
        "relationships" => array("properties" => array(
            "electoralPeriod" => array("properties" => array(
                "data" => array("properties" => array(
                    "id" => array(
                        "type" => "keyword"
                    )
                ))
            )),
            "session" => array("properties" => array(
                "data" => array("properties" => array(
                    "id" => array(
                        "type" => "keyword"
                    )
                ))
            )),
            "agendaItem" => array("properties" => array(
                "data" => array("properties" => array(
                    "id" => array(
                        "type" => "keyword"
                    )
                ))
            )),
            "people" => array("properties" => array(
                "data" => array(
                    "type" => "nested",
                    "properties" => array(
                        "attributes" => array("properties" => array(
                            "context" => array(
                                "type" => "keyword"
                            )
                        ))
                    ))
                )
            ),
            "organisations" => array("properties" => array(
                "data" => array(
                    "type" => "nested",
                    "properties" => array(
                        "attributes" => array("properties" => array(
                            "context" => array(
                                "type" => "keyword"
                            )
                        ))
                    ))
                )
            )),
        )
    ));

    /*
    echo "<pre>";
    print_r($data["mappings"]);
    echo "</pre>";
    */

    $data["settings"] = array(
        "index" => array("max_ngram_diff" => 20),
        "number_of_replicas" => 0,
        "number_of_shards" => 2,
        "analysis" => array(
            "analyzer" => array(
                "default" => array(
                    "type" => "custom",
                    //"tokenizer"=>"nGramTokenizer",
                    "tokenizer" => "standard",
                    "filter" => ["lowercase", "custom_stemmer", "custom_synonyms"]
                ),
                "html_analyzer" => array(
                    "type" => "custom",
                    "tokenizer" => "standard",
                    "char_filter" => ["custom_html_strip"],
                    //"filter" => ["lowercase", "custom_stemmer", "custom_synonyms"]
                    "filter" => ["lowercase", "custom_synonyms"]
                ),
                "autocomplete_html_analyzer" => array(
                    "type" => "custom",
                    "tokenizer" => "standard",
                    "char_filter" => ["custom_html_strip"],
                    "filter" => ["custom_stopwords","lowercase", "custom_synonyms"]
                )
            ),
            "char_filter" => array(
                "custom_html_strip" => array(
                    "type" => "pattern_replace",
                    "pattern" => "<\w+\s[^>]+>|</\w+>",
                    "replacement" => " "
                )
            ),
            /*
            "tokenizer"=>array(
                "nGramTokenizer"=>array(
                    "type"=>"nGram",
                    "min_gram"=> 6,
                    "max_gram"=> 20
                )
            ),
            */
            "filter" => array(
                "custom_stopwords" => array(
                    "type" => "stop",
                    "ignore_case" => true,
                    "stopwords" => "_german_"
                ),
                "custom_stemmer" => array(
                    "type" => "stemmer",
                    "name" => "light_german"
                ),
                "custom_synonyms" => array(
                    "type" => "synonym_graph",
                    "lenient" => true,
                    "synonyms_path" => "analysis/synonyms.txt"
                )
            )
        )
    );

    $indexParams = array("index" => "openparliamenttv_".strtolower($config["ES_Parliament_Index"]), "body" => $data);

    try {
        $result = $ESClient->indices()->create($indexParams);
    } catch (Exception $e) {
        $result = $e->getMessage();
    }

    echo '<pre>';
    print_r($result);
    echo '</pre>';

}

/**
 * @return mixed
 */
function updateIndex()
{

    global $ESClient;
    global $config;

    require_once("../api/v1/api.php");

    /*****************************************
     * START UPDATING INDEX PROGRAMMATICALLY
     *****************************************/


    $parliament = "DE";


    try {

        $dbp = new SafeMySQL(array(
            'host' => $config["parliament"][$config["ES_Parliament_Index"]]["sql"]["access"]["host"],
            'user' => $config["parliament"][$config["ES_Parliament_Index"]]["sql"]["access"]["user"],
            'pass' => $config["parliament"][$config["ES_Parliament_Index"]]["sql"]["access"]["passwd"],
            'db' => $config["parliament"][$config["ES_Parliament_Index"]]["sql"]["db"]
        ));

    } catch (exception $e) {

        $return["meta"]["requestStatus"] = "error";
        $return["errors"] = array();
        $errorarray["status"] = "503";
        $errorarray["code"] = "1";
        $errorarray["title"] = "Database connection error";
        $errorarray["detail"] = "Connecting to parliament database failed";
        array_push($return["errors"], $errorarray);
        return $return;

    }

    $allMediaIDs = $dbp->getAll("SELECT MediaID FROM media".($config["ES_Offset"] ? $config["ES_Offset"] : ""));
    //print_r($allMediaIDs);

    foreach ($allMediaIDs as $id) {

        $data = apiV1([
            "action" => "getItem",
            "itemType" => "media",
            "id" => $id["MediaID"]
        ], false, $dbp);

        //print_r($data["data"]);

        $docParams = array(
            "index" => "openparliamenttv_".strtolower($config["ES_Parliament_Index"]),
            "id" => $id["MediaID"],
            "body" => json_encode($data["data"])
        );

        try {
            $result = $ESClient->index($docParams);
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        echo '<pre>';
        print_r($result);
        echo '</pre>';

    }

    /*****************************************
     * END UPDATING INDEX PROGRAMMATICALLY
     *****************************************/

}

?>
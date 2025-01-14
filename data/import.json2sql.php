<?php
session_start();
include_once(__DIR__ . '/../modules/utilities/auth.php');

$auth = auth($_SESSION["userdata"]["id"], "import", "mediaJSON");

//DEV TODO $auth["meta"]["requestStatus"] = "success";

if ($auth["meta"]["requestStatus"] != "success") {

    $alertText = $auth["errors"][0]["detail"];


} else {


    require_once(__DIR__ . "/../config.php");
    require_once(__DIR__ . "/../modules/utilities/safemysql.class.php");
    require_once(__DIR__ . "/../modules/utilities/functions.conflicts.php");
    require_once(__DIR__ . "/../api/v1/api.php");

    error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

    ignore_user_abort(true);
    set_time_limit(0);


    $meta["inputDir"] = __DIR__ . "/input/";
    $meta["doneDir"] = __DIR__ . "/done/";
    $meta["preserveFiles"] = true;

    function importJson2sql()
    {

        global $config;
        global $meta;


        if (!is_dir($meta["inputDir"])) {

            $return["success"] = "false";
            $return["txt"] = "Missing parameter";
            return $return;

        }

        if (($meta["preserveFiles"] == true) && (!is_dir($meta["doneDir"]))) {
            $return["success"] = "false";
            $return["txt"] = "Preserve Directory does not exist.";
            return $return;
        }

        $inputFiles = scandir($meta["inputDir"]);

        if (count(array_diff($inputFiles, array('..', '.'))) < 1) {
            $return["success"] = "false";
            $return["txt"] = "No Inputfiles";
            return $return;
        }

        //If-needed: Limit for 5 files
        $fileCnt = 0;
        $mCnt = 0;

        foreach ($inputFiles as $file) {
            //echo "FileLoop";

            if ((is_dir($meta["inputDir"] . $file)) || (!is_file($meta["inputDir"] . $file)) || (!preg_match('/.*\.json$/DA', $file))) {
                continue;
            }

            try {
                $json = json_decode(file_get_contents($meta["inputDir"] . $file), true);
            } catch (exception $e) {

                $db = new SafeMySQL(array(
                    'host' => $config["platform"]["sql"]["access"]["host"],
                    'user' => $config["platform"]["sql"]["access"]["user"],
                    'pass' => $config["platform"]["sql"]["access"]["passwd"],
                    'db' => $config["platform"]["sql"]["db"]
                ));


                reportConflict("Media", "mediaAdd File Parse Error", "", "", "Could not parse json from file: " . $file . " ||| Error:" . $e->getMessage(), $db);
                echo "<pre>JSON could not parsed!\n";
                print_r($e);
                echo "</pre>";
                //header("Refresh:0");
            }

            foreach ($json as $spKey => $media) {
                //echo "MediaLoop";
                $media["action"] = "addMedia";
                $media["itemType"] = "addMedia";

                $return = apiV1($media);
                if ($return["meta"]["requestStatus"] != "success") {

                    $logMessage = "###########\n";
                    $logMessage .= $file . "\n";
                    $logMessage .= "\n-----------\n";
                    $logMessage .= json_encode($return);
                    $logMessage .= "\nMEDIA ITEM:\n\n";
                    $logMessage .= json_encode($media);
                    $logMessage .= "\n###########\n\n\n\n";

                    file_put_contents("import-error-log.txt", $logMessage, FILE_APPEND);

                } else {
                    $mCnt++;
                    if ($mCnt >= 20) {
                        header("Refresh:0");
                    }
                }

            }

            if ($meta["preserveFiles"] == true) {

                rename($meta["inputDir"] . $file, $meta["doneDir"] . $file);

            } else {

                unlink($meta["inputDir"] . $file);

            }

            //If-needed: Limit for 5 files
            /*
            $fileCnt++;
            if ($fileCnt >= 5) {
                echo 'EXITING ...';
                exit();
            }
            */

        }
    }

    try {
        //print_r(importJson2sql());
        importJson2sql();
    } catch (exception $e) {
        //echo "<pre>";
        //print_r($e);
        //echo "</pre>";
        header("Refresh:0");
    }
}
?>

<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);



include_once(__DIR__ . '/../../../modules/utilities/auth.php');

$auth = auth($_SESSION["userdata"]["id"], "requestPage", "results");

if ($auth["meta"]["requestStatus"] != "success") {
    echo "Not authorized";
} else {

if (!function_exists("L")) {
	require_once(__DIR__."/../../../i18n.class.php");
	$i18n = new i18n(__DIR__.'/../../../lang/lang_{LANGUAGE}.json', __DIR__.'/../../../langcache/', 'de');
	$i18n->init();
}

require_once(__DIR__.'/../../../modules/utilities/functions.entities.php');
require_once(__DIR__."/../../../modules/search/include.search.php");
require_once(__DIR__."/../../../api/v1/api.php");



if (!$_REQUEST["a"] || count($_REQUEST) < 2 || 
	($_REQUEST["queryOnly"] == 1 && !$_REQUEST["q"] && !$_REQUEST["personID"])) {
?>

<div class="row justify-content-center">
	<div id="introHint" class="col-11 col-md-10 col-lg-8 col-xl-6">
		<img src="content/client/images/arrow.png" class="bigArrow d-none d-md-inline">
		<div class="introHintText mt-0 mt-md-2">
			<h1 class="mb-3 introClaim"><?php echo L::claimShort; ?></h1>
			<div><b><span class="countup"><?= $indexCount ?></span> <?php echo L::speeches; ?></b> <?php echo L::inTheGermanBundestag; ?></div>
			<ul>
				<li><?php echo L::featureBullet1; ?></li>
				<li><?php echo L::featureBullet2; ?></li>
				<li><?php echo L::fromTime; ?> 2017 <?php echo L::until; ?> <?php echo L::today; ?>*</li>
				<li><?php echo L::moreParliamentsSoon; ?> <br class="d-inline d-sm-none"/>(<a href='<?= $config["dir"]["root"] ?>/faq'> <u><?php echo L::moreInfo; ?></u> </a>)</li>
			</ul>
			<!--
			<div class="text-center alert mt-3 px-1 py-0 alert-info" style="font-size: 14px;"><span class="icon-attention mr-1"></span><a href="<?= $config["dir"]["root"] ?>/announcements" style="color: inherit; text-decoration: underline;"><?php echo L::messageAnnouncementCurrentState; ?></a></div>
			-->
		</div>
	</div>
</div>
<div class="row justify-content-center">
	<div class="examplesContainer mt-3 mb-5 col-11 col-md-10 col-lg-8 col-xl-6"><?php echo L::examples; ?>: <br>
		<a href='<?= $config["dir"]["root"] ?>/search?q=Mietpreisbremse'>Mietpreisbremse</a><a href='<?= $config["dir"]["root"] ?>/search?q=Rente'>Rente</a><a href='<?= $config["dir"]["root"] ?>/search?q=Brexit'>Brexit</a><a href='<?= $config["dir"]["root"] ?>/search?q=Pariser%20Abkommen'>Pariser Abkommen</a><a href='<?= $config["dir"]["root"] ?>/search?q=NetzDG'>NetzDG</a><a href='<?= $config["dir"]["root"] ?>/search?q=BAMF'>BAMF</a><a href='<?= $config["dir"]["root"] ?>/search?q=Klimawandel'>Klimawandel</a><a href='<?= $config["dir"]["root"] ?>/search?q=Lobbyregister'>Lobbyregister</a><a href='<?= $config["dir"]["root"] ?>/search?q=Pflegeversicherung'>Pflegeversicherung</a><a href='<?= $config["dir"]["root"] ?>/search?q=Datenschutz-Grundverordnung'>Datenschutz-Grundverordnung</a><a href='<?= $config["dir"]["root"] ?>/search?q=Katze%20Sack'>Katze im Sack</a><a href='<?= $config["dir"]["root"] ?>/search?q=Hase%20Igel'>Hase und Igel</a><a href='<?= $config["dir"]["root"] ?>/search?q=%22das%20ist%20die%20Wahrheit%22'>"das ist die Wahrheit"</a><a href='<?= $config["dir"]["root"] ?>/search?q=Tropfen%20hei%C3%9Fen%20Stein'>Tropfen auf den heißen Stein</a>
	</div>
</div>

<?php
} else {

	$parameter = $_REQUEST;
	$parameter["action"] = "search";
	$parameter["itemType"] = "media";
	$result = apiV1($parameter);



	$result_items = $result["data"];

	$totalResults = $result["meta"]["results"]["total"];
	
	$totalResultString = $totalResults;

	if ($totalResults >= 10000 ) {
		$totalResultString = "> ".$totalResults;
	}

	//TODO: Check where to get totalFinds
	/*
	if ($result["totalFinds"] > 0) {
		$findsString = '<strong>'.$result["totalFinds"].'</strong> Treffer in ';
	} else {
		$findsString = '';
	}
	*/
	$findsString = '';

// ELSE FINISHES AT END OF FILE


if ($totalResults != 0) {
?>
	<div class="filterSummary row">
		<div class="col-12 col-sm-6 mb-3 mb-sm-0 px-0 px-sm-2"><label class="col-form-label px-0 mr-0 mr-sm-1 col-12 col-sm-auto text-center text-sm-left"><?= $findsString ?><strong><?= $totalResultString ?></strong> <?php echo L::speechesFound; ?></label>
			<button type="button" id="play-submit" class="btn btn-sm btn-outline-primary col-12 col-sm-auto" style="background-color: var(--highlight-color); color: var(--primary-bg-color);"><?php echo L::autoplayAll; ?><span class="icon-play-1"></span></button>
		</div>
		<div class="col-12 col-sm-6 pr-0 pr-sm-2" style="text-align: right;">
			<label class="col-form-label" for="sort"><?php echo L::sortBy; ?></label>
			<select style="width: auto;" class="custom-select custom-select-sm ml-1" id="sort" name="sort">
				<option value="relevance" selected><?php echo L::relevance; ?></option>
				<option value="topic-asc"><?php echo L::topic; ?> (<?php echo L::sortByAsc; ?>)</option>
				<option value="topic-desc"><?php echo L::topic; ?> (<?php echo L::sortByDesc; ?>)</option>
				<option value="date-asc"><?php echo L::date; ?> (<?php echo L::sortByAsc; ?>)</option>
				<option value="date-desc"><?php echo L::date; ?> (<?php echo L::sortByDesc; ?>)</option>
			</select>
		</div>
	</div>
	<div class="resultList row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
		<?php
		$paramStr = "";
		$allowedParams = array_intersect_key($_REQUEST,array_flip(array("q","name","person","personID","context","party","faction","factionID","parliament","electoralPeriod","electoralPeriodID","agendaItemID","dateFrom","dateTo","gender","degree","abgeordnetenwatchID","organisation","organisationID","documentID","termID","sessionNumber","sessionID","page","sort")));
		$paramCount = 1;
		foreach ($allowedParams as $k=>$v) {
			if ($paramCount == 1) {
				$paramPrefix = "?";
			} else {
				$paramPrefix = "&";
			}
			if (is_array($v)) {
				foreach ($v as $i) {
					if (strlen($i) == 0) { continue; }
					$paramStr .= $paramPrefix.$k."[]=".$i;
				}
			} else if (strlen($v) > 0) {
				$paramStr .= $paramPrefix.$k."=".$_REQUEST[$k];
			}
			$paramCount++;

		}

		$sortFactor = null;
		$currentDate = null;
		$currentAgendaItem = null;
		if (isset($_REQUEST["sort"]) && strlen($_REQUEST["sort"]) > 3) {
			$sortFactor = (strpos($_REQUEST["sort"], 'date') !== false) ? 'date' : 'topic';
		}
		
		foreach($result_items as $result_item) {
			
			$formattedDuration = gmdate("i:s", $result_item["attributes"]['duration']);

			$formattedDate = date("d.m.Y", strtotime($result_item["attributes"]["dateStart"]));
			
			$mainSpeaker = getMainSpeakerFromPeopleArray($result_item["relationships"]["people"]['data']);
			$mainFaction = getMainFactionFromOrganisationsArray($result_item["relationships"]["organisations"]['data']);

			if ($sortFactor == 'date' && $formattedDate != $currentDate) {
				echo '<div class="sortDivider"><b>'.$formattedDate.'</b><span class="icon-down" style="font-size: 0.9em;"></span></div>';
				$currentDate = $formattedDate;
			} elseif ($sortFactor == 'topic' && $result_item["relationships"]["agendaItem"]["data"]["attributes"]["title"] != $currentAgendaItem) {
				echo '<div class="sortDivider"><b>'.$formattedDate.' - '.$result_item["relationships"]["agendaItem"]["data"]["attributes"]["title"].'</b><span class="icon-down" style="font-size: 0.9em;"></span></div>';
				$currentAgendaItem = $result_item["relationships"]["agendaItem"]["data"]["attributes"]["title"];
			}

			$highlightedName = $mainSpeaker['attributes']['label'];
			if (strlen($_REQUEST['person']) > 1) {
				$highlightedName = str_replace($_REQUEST['person'], '<em>'.$_REQUEST['person'].'</em>', $highlightedName);
			}

			include 'content.result.item.php';
		}
		?>
	</div>
	<?php 
	include_once('content.result.pagination.php');
} else {
	?>
	<div class="filterSummary row">
		<div class="col alert alert-info"><?php echo L::noRelevant; ?> <?php echo L::speechesFound; ?></div>
	</div>
<?php 
}
}
}
?>
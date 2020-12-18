<article class="resultItem col" data-speech-id="<?= $result_item["_source"]["meta"]["id"] ?>" data-party="<?= $result_item["_source"]["meta"]["speakerParty"] ?>">
	<div class="resultContent partyIndicator" data-party="<?=$result_item["_source"]["meta"]["speakerParty"]?>">
		<a style="display: block;" href='play?id=<?= $result_item["_source"]["meta"]["id"].$paramStr ?>'>
			<div class="icon-play-1"></div>
			<div class="resultDuration"><?= $formattedDuration ?></div>
			<div class="resultDate"><?= $formattedDate ?></div>
			<div class="resultMeta">
				<?= $highlightedName .' ('.$result_item["_source"]["meta"]["speakerParty"].')' ?>
			</div>
			<hr>
			<?php
			if ($sortFactor == null || $sortFactor != 'topic') {
			?>
				<div class="resultTitle"><?=$result_item["_source"]["meta"]["agendaItemSecondTitle"]?>
				</div>
			<?php
			}
			?>
		</a>
		<?php 
		if (isset($result_item["finds"]) && count($result_item["finds"]) > 0) {
			echo '<div class="resultSnippets">';
		}
		if (isset($result_item['finds'])) {
			foreach($result_item['finds'] as $result) {
				?>
				<a class="resultSnippet" href='play?id=<?= $result_item["_source"]["meta"]["id"].$paramStr.'#t='.$result['data-start'] ?>' title="▶ Ausschnitt direkt abspielen"><?= $result['context'] ?></a>
				<?php
			}
		}
		
		if (isset($result_item["finds"]) && count($result_item['finds']) > 0) {
			echo '</div>';
			echo '<div class="resultTimeline">';
		}

		if (isset($result_item['finds'])) {
			?>
			<span class="badge badge-primary badge-pill"><?=count($result_item["finds"])?></span>
			<?php
			foreach($result_item['finds'] as $result) {
			
					$leftPercent = 100 * ((float)$result["data-start"] / $result_item["_source"]["meta"]["duration"]);
					$widthPercent  = 100 * (($result['data-end'] - $result['data-start']) / $result_item["_source"]["meta"]['duration']);
				?>
				<div class="hit" style="left: <?= $leftPercent ?>%; width: <?= $widthPercent ?>%;"></div>
				<?php
			}
		}
		
		if (isset($result_item["finds"]) && count($result_item['finds']) > 0) {
			echo '</div>';
		}
		?>
	</div>
</article>
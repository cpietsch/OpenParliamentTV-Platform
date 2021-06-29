var playerData;

$(document).ready( function() {

    window.onpopstate = function(event) {
		var regExResult = /media\/([a-zA-Z0-9_-]+)/.exec(location.pathname);
        if (regExResult && regExResult.length > 1) {
            updateContents(regExResult[1] + window.location.search);
        }
	}

	updateAutoplayState();

	updatePlayer();

	$('#toggleAutoplayResults').click(function() {
		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
			autoplayResults = false;
			updateQuery();
		} else {
			$(this).addClass('active');
			autoplayResults = true;
			updateQuery();
			if (OPTV_Player.codeSnippets.length > 1) {
				goToNextResultSnippet();
			}
		}
	});

	$('#prevResultSnippetButton').click(goToPrevResultSnippet);
	$('#nextResultSnippetButton').click(goToNextResultSnippet);

});

function updateContents(resultURL) {
	//$('.loadingIndicator').show();
	if(updateAjax && updateAjax.readyState != 4){
        updateAjax.abort();
    }
    var resultURLParts = resultURL.split('?'),
        ajaxURLParams = 'id='+ resultURLParts[0];
    if (resultURLParts[1]) {
        ajaxURLParams += '&'+ resultURLParts[1];
    }
    updateAjax = $.ajax({
		method: "POST",
		url: "../content/pages/media/content.player.php?"+ ajaxURLParams
	}).done(function(data) {
		$('#content').html($(data));
		updatePlayer();
        updateAutoplayState();
		$('.loadingIndicator').hide();
	}).fail(function(err) {
		console.log(err);
	});
}

function updatePlayer() {

    //console.log(playerData);

	if (window.OPTV_Player && typeof window.OPTV_Player.destroy == "function") {
		window.OPTV_Player.destroy();
        $('#mediaContainer').append('<div id="OPTV_Player" class="frametrail-body" data-frametrail-theme="openparliamenttv"></div>');
	}

    if (prevResultURL) {
        $('#prevResultSnippetButton').attr("disabled", false);
    } else {
        $('#prevResultSnippetButton').attr("disabled", true);
    }
    if (nextResultURL) {
        $('#nextResultSnippetButton').attr("disabled", false);
    } else {
        $('#nextResultSnippetButton').attr("disabled", true);
    }

    var speechCodeSnippets = [];

    for (var i = 0; i < playerData.finds.length; i++) {
        if (i == playerData.finds.length-1) {
            speechCodeSnippets.push({
                "@context": [
                    "http://www.w3.org/ns/anno.jsonld",
                    {
                        "frametrail": "http://frametrail.org/ns/"
                    }
                ],
                "creator": {
                    "nickname": "demo",
                    "type": "Person",
                    "id": "1"
                },
                "created": "Wed Mar 14 2018 11:33:19 GMT+0100 (CET)",
                "type": "Annotation",
                "frametrail:type": "CodeSnippet",
                "target": {
                    "type": "Video",
                    "source": playerData.mediaSource,
                    "selector": {
                        "conformsTo": "http://www.w3.org/TR/media-frags/",
                        "type": "FragmentSelector",
                        "value": "t="+ playerData.finds[i].end +""
                    }
                },
                "body": {
                    "type": "TextualBody",
                    "frametrail:type": "codesnippet",
                    "format": "text/javascript",
                    "value": "if(autoplayResults){OPTV_Player.pause();if(nextResultURL){updateQuery(nextResultID);}}",
                    "frametrail:name": "Custom Code Snippet",
                    "frametrail:thumb": null,
                    "frametrail:resourceId": null
                },
                "frametrail:attributes": {}
            });
        } else {
            speechCodeSnippets.push({
                "@context": [
                    "http://www.w3.org/ns/anno.jsonld",
                    {
                        "frametrail": "http://frametrail.org/ns/"
                    }
                ],
                "creator": {
                    "nickname": "demo",
                    "type": "Person",
                    "id": "1"
                },
                "created": "Wed Mar 14 2018 11:33:19 GMT+0100 (CET)",
                "type": "Annotation",
                "frametrail:type": "CodeSnippet",
                "target": {
                    "type": "Video",
                    "source": playerData.mediaSource,
                    "selector": {
                        "conformsTo": "http://www.w3.org/TR/media-frags/",
                        "type": "FragmentSelector",
                        "value": "t="+ playerData.finds[i].end +""
                    }
                },
                "body": {
                    "type": "TextualBody",
                    "frametrail:type": "codesnippet",
                    "format": "text/javascript",
                    "value": "if(autoplayResults){OPTV_Player.pause();OPTV_Player.currentTime="+ playerData.finds[i+1].start +";OPTV_Player.play();}",
                    "frametrail:name": "Custom Code Snippet",
                    "frametrail:thumb": null,
                    "frametrail:resourceId": null
                },
                "frametrail:attributes": {}
            });
            
        }
        
    }
	
	window.OPTV_Player = FrameTrail.init({
        target:             '#OPTV_Player',
        contentTargets:     {},
        contents:           [{
            hypervideo: {
                "meta": {
                    "name": playerData.title,
                    "thumb": "",
                    "creator": "Open Parliament TV Platform",
                    "creatorId": "0",
                    "created": 1519713627469,
                    "lastchanged": 1521025330334
                },
                "config": {
                    "slidingMode": "adjust",
                    "slidingTrigger": "key",
                    "autohideControls": false,
                    "captionsVisible": false,
                    "clipTimeVisible": false,
                    "hidden": false,
                    "layoutArea": {
                        "areaTop": [],
                        "areaBottom": [
                            /*
                            {
                                "type": "TimedContent",
                                "contentSize": "small",
                                "name": "Erkannte Drucksachen (beta)",
                                "description": "",
                                "cssClass": "",
                                "collectionFilter": {
                                    "tags": [],
                                    "types": [],
                                    "users": [],
                                    "text": ""
                                },
                                "onClickContentItem": "",
                                "html": "",
                                "transcriptSource": ""
                            },
                            {
                                "type": "TimedContent",
                                "contentSize": "small",
                                "name": "Erkannte Begriffe (beta)",
                                "description": "",
                                "cssClass": "",
                                "collectionFilter": {
                                    "tags": [],
                                    "types": [],
                                    "users": [],
                                    "text": ""
                                },
                                "onClickContentItem": "",
                                "html": "",
                                "transcriptSource": ""
                            },
                            {
                                "type": "TimedContent",
                                "contentSize": "small",
                                "name": "Fragen / Antworten",
                                "description": "",
                                "cssClass": "",
                                "collectionFilter": {
                                    "tags": [],
                                    "types": [],
                                    "users": [],
                                    "text": ""
                                },
                                "onClickContentItem": "",
                                "html": "",
                                "transcriptSource": ""
                            }
                            */
                        ],
                        "areaLeft": [
                            /*
                            {
                                "type": "CustomHTML",
                                "contentSize": "large",
                                "name": "<span class=\"icon-torso\"></span>",
                                "description": "",
                                "cssClass": "",
                                "collectionFilter": {
                                    "tags": [],
                                    "types": [],
                                    "users": [],
                                    "text": ""
                                },
                                "onClickContentItem": "",
                                "html": "<iframe style=\"position: relative; width: 100%; height: 100%; border: none;\" src=\"https://embed.abgeordnetenwatch.de/profile/"+ playerData.aw_username +"\"></iframe>",
                                "transcriptSource": ""
                            },
                            */
                            {
                                "type": "CustomHTML",
                                "contentSize": "large",
                                "name": "<span class=\"icon-doc-text-1\"></span>",
                                "description": "",
                                "cssClass": "",
                                "collectionFilter": {
                                    "tags": [],
                                    "types": [],
                                    "users": [],
                                    "text": ""
                                },
                                "onClickContentItem": "",
                                "html": playerData.transcriptHTML,
                                "transcriptSource": ""
                            }
                        ],
                        "areaRight": []
                    }
                },
                "clips": [
                    {
                        "resourceId": null,
                        "src": playerData.mediaSource,
                        "duration": 0,
                        "start": 0,
                        "end": 0,
                        "in": 0,
                        "out": 0
                    }
                ],
                "globalEvents": {
                    "onReady": "",
                    "onPlay": "",
                    "onPause": "",
                    "onEnded": ""
                },
                "customCSS": "",
                "contents": speechCodeSnippets,
                "subtitles": []
            },
            annotations: false
            //annotations: ["<?=$annotationSource?>"]
        }],
        startID: '0',
        resources: [{
            label: "Choose Resources",
            data: {},
            type: "frametrail"
        }],
        tagdefinitions: {},
        config: {
            "updateServiceURL": null,
            "autoUpdate": false,
            "defaultUserRole": "user",
            "captureUserTraces": false,
            "userTracesStartAction": "",
            "userTracesEndAction": "",
            "userNeedsConfirmation": true,
            "alwaysForceLogin": false,
            "allowCollaboration": false,
            "allowUploads": false,
            "theme": "openparliamenttv",
            "defaultHypervideoHidden": false,
            "userColorCollection": [
                "597081",
                "339966",
                "16a09c",
                "cd4436",
                "0073a6",
                "8b5180",
                "999933",
                "CC3399",
                "7f8c8d",
                "ae764d",
                "cf910d",
                "b85e02"
            ],
            "videoFit": "contain"
        },
        users: {}
    });

    OPTV_Player.on('ready', function() {

        /*
        var downloadOptions = $('<div class="downloadOptions">'
                            +       '<div class="icon icon-download"></div>'
                            +   '</div>');

        var prevSpeaker = <?= ($prevSpeech) ? "'".$prevSpeech["_source"]["meta"]['speakerDegree'].' '.$prevSpeech["_source"]["meta"]['speakerFirstName'].' '.$prevSpeech["_source"]["meta"]['speakerLastName'].' <span class="partyIndicator" data-party="'.$prevSpeech["_source"]["meta"]['speakerParty'].'">'.$prevSpeech["_source"]["meta"]['speakerParty']."</span>'" : 'null' ?>;

        var nextSpeaker = <?= ($nextSpeech) ? "'".$nextSpeech["_source"]["meta"]['speakerDegree'].' '.$nextSpeech["_source"]["meta"]['speakerFirstName'].' '.$nextSpeech["_source"]["meta"]['speakerLastName'].' <span class="partyIndicator" data-party="'.$nextSpeech["_source"]["meta"]['speakerParty'].'">'.$nextSpeech["_source"]["meta"]['speakerParty']."</span>'" : 'null' ?>;
        
        var navigationOptions = $('<div class="navigationOptions"></div>');
        var prevSpeakerURL = '?id=<?= $prevSpeech["_source"]["meta"]['id'] ?>'.replace(/\s/g, '+');
        if (prevSpeaker) {
            navigationOptions.append('<a href='+ prevSpeakerURL +' class="prevSpeech"><b>Vorheriger Redebeitrag</b><br>'+ prevSpeaker +'</a>');
        }
        var nextSpeakerURL = '?id=<?= $nextSpeech["_source"]["meta"]['id'] ?>'.replace(/\s/g, '+');
        if (nextSpeaker) {
            navigationOptions.append('<a href='+ nextSpeakerURL +' class="nextSpeech"><b>Nächster Redebeitrag</b><br>'+ nextSpeaker +'</a>');
        }
                            
        var playerOptions = $('<div class="playerOptions"></div>');
        playerOptions.append(navigationOptions, downloadOptions);

        $('.frametrail-body .titlebar').append(playerOptions);
		*/
        window.setTimeout(function() {
            if (autoplayResults && playerData.finds.length > 0) {
                OPTV_Player.currentTime = playerData.finds[0].start;
            }
            /*
            <?php
            if ($speech['finds']) {
            ?>
                if(autoplayResults) {
                    OPTV_Player.currentTime = <?= $speech['finds'][0]['data-start'] ?>;
                }
            <?php
            }
            ?>
            */
            OPTV_Player.play();
        }, 600);
    });

    OPTV_Player.on('pause', function() {
        //$('.videoStartOverlay').removeClass('inactive').show();
    });

    /*
    OPTV_Player.on('ended', function() {
        location.href = $('.nextSpeech').attr('href');
    });
    */
}

function updateAutoplayState() {
	$('a.prevSpeech, a.nextSpeech').each(function() {
		$(this).attr('href', $(this).attr('href').replace(/(&playresults=[0-1])/, ''));
		if (autoplayResults) {
			$(this).attr('href', $(this).attr('href') + '&playresults=1');
		}
	});
}

function updateQuery(resultID) {
	
    var thisResultID;
    if (!resultID) {
        var regExResult = /media\/([a-zA-Z0-9_-]+)/.exec(location.pathname);
        if (regExResult && regExResult.length > 1) {
            thisResultID = regExResult[1];
        }
    } else {
        thisResultID = resultID;
    }

    var locationString = 'media/'+ thisResultID + location.search.replace(/(&playresults=[0-1])/, ''),
		prevResultURLString = (prevResultURL) ? prevResultURL.replace(/(&playresults=[0-1])/, '') : null,
		nextResultURLString = (nextResultURL) ? nextResultURL.replace(/(&playresults=[0-1])/, '') : null;
    
    if (autoplayResults) {
		locationString += '&playresults=1';
		prevResultURLString += '&playresults=1';
		nextResultURLString += '&playresults=1';
	}

	prevResultURL = prevResultURLString;
	nextResultURL = nextResultURLString;
    
	if (resultID) {
        locationString = locationString.replace(/(media\/[a-zA-Z0-9_-]+)/, resultID);
        history.pushState(null, "", locationString);
        updateContents(locationString);
    } else {
        locationString = locationString.replace(/(media\/)/, '');
        history.replaceState(null, "", locationString);
        updateAutoplayState();
    }
    
}

function goToPrevResultSnippet() {
	var currentVideoTime = (OPTV_Player.currentTime) ? OPTV_Player.currentTime : 0;
	var closestTime = null;

	if (OPTV_Player.codeSnippets.length > 1) {
		var referenceTime = 0;

		for (var i=0; i < OPTV_Player.codeSnippets.length; i++) {
			if (OPTV_Player.codeSnippets[i].data.start < currentVideoTime-2 && OPTV_Player.codeSnippets[i].data.start > referenceTime) {
				referenceTime = closestTime = OPTV_Player.codeSnippets[i].data.start;
			}
		}
	}

	if (closestTime) {
		OPTV_Player.currentTime = closestTime-2;
		$('#nextResultSnippetButton').attr("disabled", false);
	} else if (prevResultID) {
		updateQuery(prevResultID);
	} else {
		$('#prevResultSnippetButton').attr("disabled", true);
	}
}

function goToNextResultSnippet() {
	var currentVideoTime = (OPTV_Player.currentTime) ? OPTV_Player.currentTime : 0;
	var closestTime = null;

	if (OPTV_Player.codeSnippets.length > 1) {
		var referenceTime = OPTV_Player.duration;

		for (var i=0; i < OPTV_Player.codeSnippets.length; i++) {
			if (OPTV_Player.codeSnippets[i].data.start > currentVideoTime+2 && OPTV_Player.codeSnippets[i].data.start < referenceTime) {
				referenceTime = closestTime = OPTV_Player.codeSnippets[i].data.start;
			}
		}
	}

	if (closestTime) {
		OPTV_Player.currentTime = closestTime-2;
		$('#prevResultSnippetButton').attr("disabled", false);
	} else if (nextResultID) {
		updateQuery(nextResultID);
	} else {
		$('#nextResultSnippetButton').attr("disabled", true);
	}
}
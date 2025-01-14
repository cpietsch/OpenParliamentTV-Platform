<?php

include_once(__DIR__ . '/../../../modules/utilities/auth.php');

$auth = auth($_SESSION["userdata"]["id"], "requestPage", $pageType);

if ($auth["meta"]["requestStatus"] != "success") {

    $alertText = $auth["errors"][0]["detail"];
    include_once (__DIR__."/../login/page.php");

} else {

include_once(__DIR__ . '/../../header.php');
?>

<main class="container subpage">
	<div class="row" style="position: relative; z-index: 1">
		<div class="col-12">
			<h2>API <?php echo L::documentation; ?></h2>
			<div class="alert bg-white"><?php echo L::messageOpenData; ?>. There is currently no limit on requests nor do you need an api key. But please get in touch if you plan to copy our entire dataset. Instead of making millions of api requests you can just get an SQL dump from us.</div>
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="search-tab" data-toggle="tab" href="#search" role="tab" aria-controls="search" aria-selected="true"><span class="nav-item-label">Search</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="entities-tab" data-toggle="tab" href="#entities" role="tab" aria-controls="entities" aria-selected="true"><span class="nav-item-label">Entities</span></a>
				</li>
				<!--
				<li class="nav-item">
					<a class="nav-link" id="other-tab" data-toggle="tab" href="#other" role="tab" aria-controls="other" aria-selected="true"><span class="nav-item-label">Other Endpoints</span></a>
				</li>
				-->
				<li class="nav-item">
					<a class="nav-link" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><span class="nav-item-label">Specification v1.0</span></a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade bg-white show active" id="search" role="tabpanel" aria-labelledby="search-tab">
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="media-tab" data-toggle="tab" href="#media" role="tab" aria-controls="media" aria-selected="true"><span class="nav-item-label"><span class="icon-hypervideo mr-1"></span> <?php echo L::speeches; ?></span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="people-tab" data-toggle="tab" href="#people" role="tab" aria-controls="people" aria-selected="true"><span class="nav-item-label"><span class="icon-torso"></span> <?php echo L::personPlural; ?></span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="organisations-tab" data-toggle="tab" href="#organisations" role="tab" aria-controls="organisations" aria-selected="true"><span class="nav-item-label"><span class="icon-bank"></span> <?php echo L::organisations; ?></span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="true"><span class="nav-item-label"><span class="icon-doc-text"></span> <?php echo L::documents; ?></span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="terms-tab" data-toggle="tab" href="#terms" role="tab" aria-controls="terms" aria-selected="true"><span class="nav-item-label"><span class="icon-tag-1"></span> <?php echo L::terms; ?></span></a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade bg-white show active" id="media" role="tabpanel" aria-labelledby="media-tab">
							<h3>Endpoint</h3>
							<code>/api/v1/search/media?</code>
							<hr>
							<h3><?php echo L::example; ?> Request</h3>
							<div>(<?php echo L::speeches; ?> containing the query "Rente" by Faction SPD from 11.04.2018 until today)</div>
                            <div class="apiExampleContainer">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">URI</div>
                                    </div>
                                    <input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/search/media/?q=Rente&factionID[]=Q2207512&dateFrom=2018-04-11">
                                    <div class="input-group-append">
                                        <button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
                                    </div>
                                </div>
                                <div class="apiResultContainer"></div>
                            </div>
                            <hr>
							<h3>Parameters</h3>
							<div class="table-responsive-lg">
								<table class="table table-sm table-striped table-hover">
									<thead>
										<tr>
											<th>Parameter</th>
											<th>Validation</th>
											<th>Matches</th>
											<th>Type</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>q</td>
											<td>min 3 chars</td>
											<td>Full Text Search</td>
											<td>String</td>
										</tr>
										<tr>
											<td>parliament</td>
											<td>min 2 chars</td>
											<td></td>
											<td>String</td>
										</tr>
										<tr>
											<td>electoralPeriod</td>
											<td>min 1 char</td>
											<td>electoralPeriod.data.attributes.number</td>
											<td>String</td>
										</tr>
										<tr>
											<td>electoralPeriodID</td>
											<td>min 1 char</td>
											<td>electoralPeriod.data.id</td>
											<td>String</td>
										</tr>
										<tr>
											<td>sessionID</td>
											<td>min 1 char</td>
											<td>session.data.id</td>
											<td>String</td>
										</tr>
										<tr>
											<td>sessionNumber</td>
											<td>min 1 char</td>
											<td>session.data.attributes.number</td>
											<td>String</td>
										</tr>
										<tr>
											<td>dateFrom</td>
											<td>date in ISO format (ex. "2017-10-28")</td>
											<td>dateStart</td>
											<td>String</td>
										</tr>
										<tr>
											<td>dateTo</td>
											<td>date in ISO format (ex. "2017-12-22")</td>
											<td>dateStart</td>
											<td>String</td>
										</tr>
										<tr>
											<td>party</td>
											<td>min 1 char</td>
											<td>people.data.attributes.party.labelAlternative</td>
											<td>String OR Array</td>
										</tr>
										<tr>
											<td>partyID</td>
											<td>min 1 char</td>
											<td>organisations.data.id</td>
											<td>String OR Array</td>
										</tr>
										<tr>
											<td>faction</td>
											<td>min 1 char</td>
											<td>people.data.attributes.faction.labelAlternative</td>
											<td>String OR Array</td>
										</tr>
										<tr>
											<td>factionID</td>
											<td>min 1 char</td>
											<td>organisations.data.id</td>
											<td>String OR Array</td>
										</tr>
										<tr>
											<td>person</td>
											<td>min 3 chars</td>
											<td>people.data.attributes.label</td>
											<td>String</td>
										</tr>
										<tr>
											<td>personID</td>
											<td>Wikidata ID RegEx</td>
											<td>people.data.id</td>
											<td>String OR Array</td>
										</tr>
										<tr>
											<td>abgeordnetenwatchID</td>
											<td>min 1 char</td>
											<td>people.data.attributes.additionalInformation.abgeordnetenwatchID</td>
											<td>String</td>
										</tr>
										<tr>
											<td>organisationID</td>
											<td>Wikidata ID RegEx</td>
											<td>people.data.attributes.party.id, people.data.attributes.faction.id</td>
											<td>String</td>
										</tr>
										<tr>
											<td>context</td>
											<td>min 3 chars</td>
											<td>people.data.attributes.context, organisations.data.attributes.context</td>
											<td>String</td>
										</tr>
										<tr>
											<td>agendaItemID</td>
											<td>min 2 chars</td>
											<td>agendaItem.data.id</td>
											<td>String</td>
										</tr>
										<tr>
											<td>documentID</td>
											<td>min 1 char</td>
											<td>documents.data.id</td>
											<td>String</td>
										</tr>
										<tr>
											<td>termID</td>
											<td>min 1 char</td>
											<td>terms.data.id</td>
											<td>String</td>
										</tr>
										<tr>
											<td>id</td>
											<td>min 4 chars</td>
											<td>id</td>
											<td>String</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade bg-white" id="people" role="tabpanel" aria-labelledby="people-tab">
							<h3>Endpoint</h3>
							<code>/api/v1/search/people?</code>
							<hr>
							<h3><?php echo L::example; ?> Request</h3>
							<div>(<?php echo L::personPlural; ?> with name "Michael" in the Party "SPD")</div>
							<div class="apiExampleContainer">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">URI</div>
                                    </div>
                                    <input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/search/people/?name=Michael&party=SPD">
                                    <div class="input-group-append">
                                        <button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
                                    </div>
                                </div>
                                <div class="apiResultContainer"></div>
                            </div>
                            <hr>
							<h3>Parameters</h3>
							<div class="table-responsive-lg">
								<table class="table table-sm table-striped table-hover">
									<thead>
										<tr>
											<th>Parameter</th>
											<th>Validation</th>
											<th>Matches</th>
											<th>Type</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>name</td>
											<td>min 3 chars</td>
											<td>label, firstName, lastName</td>
											<td>String</td>
										</tr>
										<tr>
											<td>type</td>
											<td>"memberOfParliament", "unknown"</td>
											<td>type</td>
											<td>String</td>
										</tr>
										<tr>
											<td>party</td>
											<td>min 1 char</td>
											<td>organisation.label, organisation.labelAlternative</td>
											<td>String OR Array</td>
										</tr>
										<tr>
											<td>partyID</td>
											<td>Wikidata ID RegEx</td>
											<td>partyOrganisationID</td>
											<td>String OR Array</td>
										</tr>
										<tr>
											<td>faction</td>
											<td>min 1 char</td>
											<td>organisation.label, organisation.labelAlternative</td>
											<td>String OR Array</td>
										</tr>
										<tr>
											<td>factionID</td>
											<td>Wikidata ID RegEx</td>
											<td>factionOrganisationID</td>
											<td>String OR Array</td>
										</tr>
										<tr>
											<td>organisationID</td>
											<td>Wikidata ID RegEx</td>
											<td>factionOrganisationID, partyOrganisationID</td>
											<td>String</td>
										</tr>
										<tr>
											<td>degree</td>
											<td>min 1 char</td>
											<td>degree</td>
											<td>String</td>
										</tr>
										<tr>
											<td>gender</td>
											<td>"male", "female", "nonbinary", "bi", "queer"</td>
											<td>gender</td>
											<td>String</td>
										</tr>
										<tr>
											<td>originID</td>
											<td>min 1 char</td>
											<td>originID</td>
											<td>String</td>
										</tr>
										<tr>
											<td>abgeordnetenwatchID</td>
											<td>min 1 char</td>
											<td>additionalInformation.abgeordnetenwatchID</td>
											<td>String</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade bg-white" id="organisations" role="tabpanel" aria-labelledby="organisations-tab">
							<div class="alert alert-info">Please note that in the Open Parliament TV data model <b>parties</b> and <b>factions</b> as well as other types of bodies like <b>companies</b> or <b>NGOs</b> are all "organisations". They can be <b>filtered</b> via the "type" parameter (eg. type=faction or type=party).</div>
							<hr>
							<h3>Endpoint</h3>
							<code>/api/v1/search/organisations?</code>
							<hr>
							<h3><?php echo L::example; ?> Request</h3>
							<div>(<?php echo L::organisations; ?> with name "Linke")</div>
							<div class="apiExampleContainer">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">URI</div>
                                    </div>
                                    <input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/search/organisations/?name=Linke">
                                    <div class="input-group-append">
                                        <button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
                                    </div>
                                </div>
                                <div class="apiResultContainer"></div>
                            </div>
                            <hr>
							<h3>Parameters</h3>
							<table class="table table-sm table-striped table-hover">
								<thead>
									<tr>
										<th>Parameter</th>
										<th>Validation</th>
										<th>Matches</th>
										<th>Type</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>name</td>
										<td>min 3 chars</td>
										<td>label, labelAlternative, abstract</td>
										<td>String OR Array</td>
									</tr>
									<tr>
										<td>type</td>
										<td>min 2 chars</td>
										<td>type</td>
										<td>String</td>
									</tr>
								</tbody>
							</table>
							<hr>
						</div>
						<div class="tab-pane fade bg-white" id="documents" role="tabpanel" aria-labelledby="documents-tab">
							<div class="alert alert-info">Please note that documents can be official documents ("Drucksachen") as well as law texts and potentially other types of documents, sometimes applying to a specific parliament, sometimes generic, sometimes having a Wikidata ID, sometimes not. This is why the <b>document ID is not based on the official document number</b> or the Wikidata ID but an internal incremental ID. To <b>filter documents of a certain type</b>, use the "type" parameter (eg. type=officialDocument or type=legalDocument).</div>
							<hr>
							<h3>Endpoint</h3>
							<code>/api/v1/search/documents?</code>
							<hr>
							<h3><?php echo L::example; ?> Request</h3>
							<div>(<?php echo L::documents; ?> with label "19/5412", returns "Drucksache 19/5412" as well as documents where "19/5412" is mentioned in the titles or abstact)</div>
							<div class="apiExampleContainer">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">URI</div>
                                    </div>
                                    <input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/search/documents/?label=19/5412">
                                    <div class="input-group-append">
                                        <button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
                                    </div>
                                </div>
                                <div class="apiResultContainer"></div>
                            </div>
                            <hr>
							<h3>Parameters</h3>
							<table class="table table-sm table-striped table-hover">
								<thead>
									<tr>
										<th>Parameter</th>
										<th>Validation</th>
										<th>Matches</th>
										<th>Type</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>label</td>
										<td>min 3 chars</td>
										<td>label, labelAlternative, abstract</td>
										<td>String OR Array</td>
									</tr>
									<tr>
										<td>type</td>
										<td>min 2 chars</td>
										<td>type</td>
										<td>String</td>
									</tr>
									<tr>
										<td>wikidataID</td>
										<td>Wikidata ID RegEx</td>
										<td>wikidataID</td>
										<td>String</td>
									</tr>
								</tbody>
							</table>
							<hr>
						</div>
						<div class="tab-pane fade bg-white" id="terms" role="tabpanel" aria-labelledby="terms-tab">
							<div class="alert alert-warning">Terms are not used yet, so you will get 0 results.</div>
							<hr>
							<h3>Endpoint</h3>
							<code>/api/v1/search/terms?</code>
							<hr>
							<h3><?php echo L::example; ?> Request</h3>
							<div>(<?php echo L::terms; ?> with label "Digitalisierung")</div>
							<div class="apiExampleContainer">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">URI</div>
                                    </div>
                                    <input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/search/terms/?label=Digitalisierung">
                                    <div class="input-group-append">
                                        <button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
                                    </div>
                                </div>
                                <div class="apiResultContainer"></div>
                            </div>
                            <hr>
							<h3>Parameters</h3>
							<table class="table table-sm table-striped table-hover">
								<thead>
									<tr>
										<th>Parameter</th>
										<th>Validation</th>
										<th>Matches</th>
										<th>Type</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>label</td>
										<td>min 3 chars</td>
										<td>label, labelAlternative</td>
										<td>String OR Array</td>
									</tr>
									<tr>
										<td>type</td>
										<td>min 2 chars</td>
										<td>type</td>
										<td>String</td>
									</tr>
									<tr>
										<td>wikidataID</td>
										<td>Wikidata ID RegEx</td>
										<td>wikidataID</td>
										<td>String</td>
									</tr>
								</tbody>
							</table>
							<hr>
						</div>
					</div>
				</div>
				<div class="tab-pane fade bg-white" id="entities" role="tabpanel" aria-labelledby="entities-tab">
					<div class="alert alert-info">Entity URIs are <b>based on the respective platform URL</b> and can be formed by simply adding <b>/api/v1</b> before the entity part. <br><br>
					  <b><?php echo L::example; ?></b>:<br>
					  <a target="_blank" href="<?= $config["dir"]["root"] ?>/person/Q567"><?= $config["dir"]["root"] ?>/person/Q567</a><br>
					  <a target="_blank" href="<?= $config["dir"]["root"] ?>/api/v1/person/Q567"><?= $config["dir"]["root"] ?>/api/v1/person/Q567</a></div>
					<hr>
					<h3><span class="icon-hypervideo mr-1"></span> GET <?php echo L::speech; ?></h3>
					<p class="mb-2"><b>Media IDs</b> contain info about the parliament, electoral period and session. You should however not try to guess those IDs (eg. based on the order of speeches). This might work in some cases, it will not in many others.</p>
					<div><b>Endpoint</b>: <code>/api/v1/media/ID</code></div>
					<div class="mb-2"><b><?php echo L::example; ?></b>: <?php echo L::speech; ?></div>
					<div class="apiExampleContainer">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">URI</div>
							</div>
							<input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/media/DE-0190061003">
							<div class="input-group-append">
								<button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
							</div>
						</div>
						<div class="apiResultContainer"></div>
					</div>
					<hr>
					<h3><span class="icon-torso"></span> GET <?php echo L::personSingular; ?></h3>
					<p class="mb-2"><b>Person IDs</b> are <b>always a Wikidata ID</b>.</p>
					<div><b>Endpoint</b>: <code>/api/v1/person/ID</code></div>
					<div class="mb-2"><b><?php echo L::example; ?></b>: Angela Merkel (Wikidata ID Q567)</div>
					<div class="apiExampleContainer">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">URI</div>
							</div>
							<input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/person/Q567">
							<div class="input-group-append">
								<button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
							</div>
						</div>
						<div class="apiResultContainer"></div>
					</div>
					<hr>
					<h3><span class="icon-bank"></span> GET <?php echo L::organisation; ?></h3>
					<p class="mb-2"><b><?php echo L::organisation; ?> IDs</b> are <b>always a Wikidata ID</b>.</p>
					<div><b>Endpoint</b>: <code>/api/v1/organisation/ID</code></div>
					<div class="mb-2"><b><?php echo L::example; ?></b>: <?php echo L::faction; ?> BÜNDNIS 90/DIE GRÜNEN (Wikidata ID Q1007353)</div>
					<div class="apiExampleContainer">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">URI</div>
							</div>
							<input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/organisation/Q1007353">
							<div class="input-group-append">
								<button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
							</div>
						</div>
						<div class="apiResultContainer"></div>
					</div>
					<hr>
					<h3><span class="icon-doc-text"></span> GET <?php echo L::document; ?></h3>
					<p class="mb-2"><b><?php echo L::document; ?> IDs</b> are internal incremental IDs and contain no reference to the document numbers (like "Drucksache 19/1234"). The rationale for this is that documents can be official documents as well as law texts and potentially other types of documents, sometimes applying to a specific parliament, sometimes generic, sometimes having a Wikidata ID, sometimes not. If you want to get a document by its official document number, you can use the document search. </p>
					<div><b>Endpoint</b>: <code>/api/v1/document/ID</code></div>
					<div class="mb-2"><b><?php echo L::example; ?></b>: Drucksache 19/1184</div>
					<div class="apiExampleContainer">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">URI</div>
							</div>
							<input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/document/14">
							<div class="input-group-append">
								<button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
							</div>
						</div>
						<div class="apiResultContainer"></div>
					</div>
					<hr>
					<h3><span class="icon-tag-1"></span> GET <?php echo L::term; ?></h3>
					<p class="mb-2"><b><?php echo L::term; ?> IDs</b> are internal incremental IDs.</p>
					<div class="alert alert-warning px-2 py-1 mb-2">Terms are not used yet, but this is how the URI would look like:</div>
					<div class="apiExampleContainer">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">URI</div>
							</div>
							<input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/term/1">
						</div>
						<div class="apiResultContainer"></div>
					</div>
					<hr>
					<h3><span class="icon-check"></span> GET <?php echo L::electoralPeriod; ?></h3>
					<p class="mb-2"><b><?php echo L::electoralPeriod; ?> IDs</b> can safely be referenced by the parliament shortcode plus the respective number. </p>
					<div><b>Endpoint</b>: <code>/api/v1/electoralPeriod/ID</code></div>
					<div class="mb-2"><b><?php echo L::example; ?></b>: <?php echo L::electoralPeriod; ?> 19 of the German Bundestag (ID: "DE-019")</div>
					<div class="apiExampleContainer">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">URI</div>
							</div>
							<input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/electoralPeriod/DE-019">
							<div class="input-group-append">
								<button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
							</div>
						</div>
						<div class="apiResultContainer"></div>
					</div>
					<hr>
					<h3><span class="icon-group"></span> GET <?php echo L::session; ?></h3>
					<p class="mb-2"><b><?php echo L::session; ?> IDs</b> can safely be referenced by the parliament shortcode plus the respective numbers. </p>
					<div><b>Endpoint</b>: <code>/api/v1/session/ID</code></div>
					<div class="mb-2"><b><?php echo L::example; ?></b>: <?php echo L::session; ?> 61 in <?php echo L::electoralPeriod; ?> 19 of the German Bundestag (ID: "DE-0190061")</div>
					<div class="apiExampleContainer">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">URI</div>
							</div>
							<input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/session/DE-0190061">
							<div class="input-group-append">
								<button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
							</div>
						</div>
						<div class="apiResultContainer"></div>
					</div>
					<hr>
					<h3><span class="icon-list-numbered"></span> GET <?php echo L::agendaItem; ?></h3>
					<p class="mb-2"><b><?php echo L::agendaItem; ?> IDs</b> are built of the parliament shortcode and an incremental ID. You should not try to guess those IDs (eg. based on the order of agenda items). This might work in some cases, it will not in many others.</p>
					<div><b>Endpoint</b>: <code>/api/v1/agendaItem/ID</code></div>
					<div class="mb-2"><b><?php echo L::example; ?></b>: <?php echo L::agendaItem; ?> "Gesetzliche Rentenversicherung" in <?php echo L::session; ?> 61 in <?php echo L::electoralPeriod; ?> 19 of the German Bundestag (ID: "DE-454")</div>
					<div class="apiExampleContainer">
						<div class="input-group">
							<div class="input-group-prepend">
								<div class="input-group-text">URI</div>
							</div>
							<input readonly class="apiURI form-control" type="text" value="<?= $config["dir"]["root"] ?>/api/v1/agendaItem/DE-454">
							<div class="input-group-append">
								<button class="apiRequestButton btn btn-sm input-group-text"><span class="icon-right-open-big"></span><span class="d-none d-md-inline"><?php echo L::showResult; ?></span></button>
							</div>
						</div>
						<div class="apiResultContainer"></div>
					</div>
				</div>
				<!--
				<div class="tab-pane fade bg-white" id="other" role="tabpanel" aria-labelledby="other-tab"></div>
				-->
				<div class="tab-pane fade bg-white" id="general" role="tabpanel" aria-labelledby="general-tab">
					<div class="alert alert-info">The API structure is based on the <a href="https://jsonapi.org/format/">JSON:API Specification</a>. Whether or not we will fully implement the standard (also for PATCH requests / data updates) is to be discussed.</div>
					<hr>
					<h3>Paths</h3>
					<p><strong>API <?php echo L::documentation; ?></strong><br><code>/api</code></p>
					<p><strong>API Endpoint Base URL</strong><br><code>/api/v1</code></p>
					<hr>
					<h3>API Responses</h3>
					<p>Responses <strong>MUST</strong> include the following properties for any request (GET <strong>and</strong> POST):</p>
<pre><code class="lang-yaml">{
  "meta": {
	"api": {
	  "version": "1.0",
	  "documentation": "https://de.openparliament.tv/api",
	  "license": {
		"label": "ODC Open Database License (ODbL) v1.0",
		"link": "https://opendatacommons.org/licenses/odbl/1-0/"
	  }
	},
	"requestStatus": "success" // OR "error"
  },
  "data": [], // {} OR []
  "errors": [], // EITHER "data" OR "errors"
  "links": {
	"self": "https://de.openparliament.tv/api/v1/search/media?q=Rente" // request URL
  }
}</code></pre>
					<p><strong>Successful</strong> requests <strong>MUST</strong> include the following properties:</p>
<pre><code class="lang-yaml">{
  "meta": {
	"api": {
	  "version": "1.0",
	  "documentation": "https://de.openparliament.tv/api",
	  "license": {
		"label": "ODC Open Database License (ODbL) v1.0",
		"link": "https://opendatacommons.org/licenses/odbl/1-0/"
	  }
	},
	"requestStatus": "success"
  },
  "data": {},
  "links": {
	"self": "https://de.openparliament.tv/api/v1/search/media?q=Rente" // request URL
  }
}</code></pre>
					<p><strong>Errors</strong> <strong>MUST</strong> include the following properties:</p>
<pre><code class="lang-yaml">{
  "meta": {
	"api": {
	  "version": "1.0",
	  "documentation": "https://de.openparliament.tv/api",
	  "license": {
		"label": "ODC Open Database License (ODbL) v1.0",
		"link": "https://opendatacommons.org/licenses/odbl/1-0/"
	  }
	},
	"requestStatus": "error"
  },
  "errors": [
	{
	  "meta": {
		"domSelector": "" // optional
	  },
	  "status": "422", // HTTP Status   
	  "code": "3", 
	  "title":  "Invalid Attribute",
	  "detail": "First name must contain at least three characters."
	}
  ],
  "links": {
	"self": "https://de.openparliament.tv/api/v1/search/media?q=Rente" // request URL
  }
}</code></pre>
					<hr>
					<h3>Data Objects</h3>
					<p>Data Objects <strong>MUST</strong> always include the properties:  </p>
					<ul>
						<li><strong>id</strong></li>
						<li><strong>type</strong></li>
						<li><strong>attributes</strong> (data item specific properties)</li>
					</ul>
					<p>Additionally Data Objects <strong>CAN</strong> include the properties:</p>
					<ul>
						<li><strong>links</strong> (&quot;self&quot; = link to the respective API request URL)</li>
						<li><strong>relationships</strong> (properties derived from other data items)</li>
					</ul>
					<p>Depending on the context, the <strong>attributes</strong> object can only include a subset of all properties. The full set can then be retrieved via an API request to <strong>links</strong> &gt; <strong>self</strong>.</p>
					<p>This principle <strong>SHOULD</strong> be applied on all levels of the data structure.</p>
					<hr>
					<h3>Examples</h3>
					<p><strong>Example for an Entity Response:</strong></p>
<pre><code class="lang-yaml">{
  "meta": {
	"api": {
	  "version": "1.0",
	  "documentation": "https://de.openparliament.tv/api",
	  "license": {
		"label": "ODC Open Database License (ODbL) v1.0",
		"link": "https://opendatacommons.org/licenses/odbl/1-0/"
	  }
	},
	"requestStatus": "success"
  },
  "data": {
	"type": "media",
	"id": "DE-198765837",
	"attributes": {},
	"relationships": {
	  "documents": {
		"data": [
		  {
			"type": "document",
			"id": "201",
			"attributes": {},
			"links": {
			  "self": "https://de.openparliament.tv/api/v1/document/201"
			}
		  }
		],
		"links": {
		  "self": "https://de.openparliament.tv/api/v1/searchAnnotations?mediaID=DE-198765837&type=document"
		}
	  }
	},
	"links": {
	  "self": "https://de.openparliament.tv/api/v1/media/DE-198765837"
	}
  }
}</code></pre>
					<hr>
					<p><strong>Example for a Search Response:</strong></p>
<pre><code class="lang-yaml">{
  "meta": {
	"api": {
	  "version": "1.0",
	  "documentation": "https://de.openparliament.tv/api",
	  "license": {
		"label": "ODC Open Database License (ODbL) v1.0",
		"link": "https://opendatacommons.org/licenses/odbl/1-0/"
	  }
	},
	"requestStatus": "success",
	"results": {
	  "count": 25,
	  "total": 128,
	  "rangeStart": 51,
	  "rangeEnd": 75,
	  "maxScore": 4.7654785 /* float or null */
	}
  },
  "data": [],
  "links": {
	"self": "https://de.openparliament.tv/api/v1/search/people?party=CDU&page[number]=3&page[size]=25",
	"first": "https://de.openparliament.tv/api/v1/search/people?party=CDU&page[number]=1&page[size]=25",
	"prev": "https://de.openparliament.tv/api/v1/search/people?party=CDU&page[number]=2&page[size]=25",
	"next": "https://de.openparliament.tv/api/v1/search/people?party=CDU&page[number]=4&page[size]=25",
	"last": "https://de.openparliament.tv/api/v1/search/people?party=CDU&page[number]=13&page[size]=25"
  }
}</code></pre>
				</div>
			</div>
		</div>
	</div>
</main>
<?php include_once(__DIR__ . '/../../footer.php'); ?>
<link type="text/css" rel="stylesheet" href="<?= $config["dir"]["root"] ?>/content/pages/api/client/jquery.json-view.min.css" media="all">
<link type="text/css" rel="stylesheet" href="<?= $config["dir"]["root"] ?>/content/pages/api/client/atom-one-light.min.css" media="all">
<style type="text/css">
	h3 > span {
		font-size: 11px;
		vertical-align: top;
		margin-top: 3px;
		display: inline-block;
		width: 16px;
	}
	.apiResultContainer {
		background: #fafafa;
		color: #986801;
		max-height: 300px;
		overflow: auto;
		margin-bottom: 20px;
	}
	.apiResultContainer .b {
		color: #383a42;
	}
	.apiResultContainer li > span:not(.num):not(.null):not(.q):not(.block),
	.apiResultContainer .str, .apiResultContainer a {
		color: #50a14f;
	}
</style>
<script type="text/javascript" src="<?= $config["dir"]["root"] ?>/content/pages/api/client/jquery.json-view.min.js"></script>
<script type="text/javascript" src="<?= $config["dir"]["root"] ?>/content/pages/api/client/highlight.min.js"></script>
<script type="text/javascript" src="<?= $config["dir"]["root"] ?>/content/pages/api/client/apiResult.js"></script>

<?php
}
?>
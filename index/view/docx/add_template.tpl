{include file="../header.tpl"}
	<div class="content-wrapper">
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
						<div id="placeholder" style="height: 730px;"></div>
						<script type="text/javascript" src="{$get_curl}:8000/web-apps/apps/api/documents/api.js"></script>
						<script>
							var docEditor;
					        var innerAlert = function (message) {
					            if (console && console.log)
					                console.log(message);
					        };

					        // the application is loaded into the browser
					        var onAppReady = function () {
					            innerAlert("Document editor ready");
					        };

					        // the document is modified
					        var onDocumentStateChange = function (event) {
					            var title = document.title.replace(/\*$/g, "");
					            document.title = title + (event.data ? "*" : "");
					        };

					        // the user is trying to switch the document from the viewing into the editing mode
					        var onRequestEditRights = function () {
					            location.href = location.href.replace(RegExp("action=view\&?", "i"), "");
					        };

					        // an error or some other specific event occurs
					        var onError = function (event) {
					            if (event)
					                innerAlert(event.data);
					        };

					        // the document is opened for editing with the old document.key value
					        var onOutdatedVersion = function (event) {
					            location.reload(true);
					        };

					        // replace the link to the document which contains a bookmark
					        var replaceActionLink = function(href, linkParam) {
					            var link;
					            var actionIndex = href.indexOf("&actionLink=");
					            if (actionIndex != -1) {
					                var endIndex = href.indexOf("&", actionIndex + "&actionLink=".length);
					                if (endIndex != -1) {
					                    link = href.substring(0, actionIndex) + href.substring(endIndex) + "&actionLink=" + encodeURIComponent(linkParam);
					                } else {
					                    link = href.substring(0, actionIndex) + "&actionLink=" + encodeURIComponent(linkParam);
					                }
					            } else {
					                link = href + "&actionLink=" + encodeURIComponent(linkParam);
					            }
					            return link;
					        }

					        var onMakeActionLink = function (event) {
					            var actionData = event.data;
					            var linkParam = JSON.stringify(actionData);
					            docEditor.setActionLink(replaceActionLink(location.href, linkParam));
					        };

					        var onMetaChange = function (event) {
					            var favorite = !!event.data.favorite;
					            var title = document.title.replace(/^\☆/g, "");
					            document.title = (favorite ? "☆" : "") + title;
					            docEditor.setFavorite(favorite);
					        };

					        var onRequestInsertImage = function(event) {
					            docEditor.insertImage({
					                "c": event.data.c,
					                {$dataInsertImage}
					            })
					        };
					        var onRequestCompareFile = function() {
					            docEditor.setRevisedFile({$dataCompareFile});
					        };
					        var onRequestMailMergeRecipients = function (event) {
					            docEditor.setMailMergeRecipients({$dataMailMergeRecipients});
					        };
					        var сonnectEditor = function () {

					            var config = {$config};

					            config.width = "100%";
					            config.height = "730px";

					            config.events = {
					                'onAppReady': onAppReady,
					                'onDocumentStateChange': onDocumentStateChange,
					                'onRequestEditRights': onRequestEditRights,
					                'onError': onError,
					                'onOutdatedVersion': onOutdatedVersion,
					                'onMakeActionLink': onMakeActionLink,
					                'onMetaChange': onMetaChange,
					                'onRequestInsertImage': onRequestInsertImage,
					                'onRequestCompareFile': onRequestCompareFile,
					                'onRequestMailMergeRecipients': onRequestMailMergeRecipients,
					            };

					            {if $history == NULL && $historyData == NULL }

						            config.events['onRequestHistory'] = function () {
						                docEditor.refreshHistory({$history});
						            };
						            config.events['onRequestHistoryData'] = function (event) {
						                var ver = event.data;
						                var histData = {$historyData};
						                docEditor.setHistoryData(histData[ver - 1]);
						            };
						            config.events['onRequestHistoryClose'] = function () {
						                document.location.reload();
						            };
					            {/if}
					            {if $usersForMentions != NULL }
						            config.events['onRequestUsers'] = function () {
						                docEditor.setUsers({
						                    "users": {$usersForMentions}
						                });
						            };
						            config.events['onRequestSendNotify'] = function (event) {
						                var actionLink = JSON.stringify(event.data.actionLink);
						                console.log("onRequestSendNotify:");
						                console.log(event.data);
						                console.log("Link to comment: " + replaceActionLink(location.href, actionLink));
						            };
					            {/if}

					            docEditor = new DocsAPI.DocEditor("placeholder", config);
					        };
					        if (window.addEventListener) {
					            window.addEventListener("load", сonnectEditor);
					        } else if (window.attachEvent) {
					            window.attachEvent("load", сonnectEditor);
					        }
						</script>
					</div>
				</div>
			</div>
		</div>
	</div>
{include file="../footer.tpl"}
<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $title; ?></title>
<link rel="stylesheet" href="Templates/theme1/demos/css/demos.css" media="screen" type="text/css">
<link rel="stylesheet" href="styles/main.css" media="screen" type="text/css">
<style type="text/css">
/* CSS for the demo. CSS needed for the scripts are loaded dynamically by the scripts */
h1{
	margin-top:0px;
}
#menuBarContainer{
	width:99%;
}
h3{
	margin-top:30px;
}	
.DHTMLSuite_paneContent .paneContentInner p{	/* A div inside .DHTMLSuite_paneContent. Add styling to it in case you want some padding */
	margin-top:0px;
}	
html,body{
	width:100%;
	height:100%;
	margin:0px;
	padding:0px;
}
#by_dhtmlgoodies{
	position:absolute;
	right:10px;
	top:2px;	
}
#by_dhtmlgoodies img{
	border:0px;
}
</style>
<script type="text/javascript" src="Templates/theme1/js/ajax.js"></script>
<script type="text/javascript">
var DHTML_SUITE_THEME = 'blue';	// SPecifying gray theme
var DHTML_SUITE_THEME_FOLDER = 'Templates/theme1/themes/';
var DHTML_SUITE_JS_FOLDER = 'Templates/theme1/js/separateFiles/';
</script>

<script type="text/javascript" src="Templates/theme1/js/dhtml-suite-for-applications-without-comments.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>

</head>

<body>
<!-- START DATASOURCES FOR THE PANES -->
<div id="westContent"></div>
<div id="northContent"><h1>Virtual World</h1><div id="menuDiv"><div id="innerDiv"></div><div id="rightDiv"></div></div></div>
<div id="center">
	<?php echo $content_for_layout?>
</div>
<div id="southContent"><p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds / {memory_usage}</p></div>
<div id="eastContent">
</div>
<!-- END DATASOURCES -->
<script type="text/javascript">
var loadingicon = '<img src="/images/loading.gif" />';
/* STEP 1 */
/* Create the data model for the panes */
var paneModel = new DHTMLSuite.paneSplitterModel();
DHTMLSuite.commonObj.setCssCacheStatus(false)
var paneWest = new DHTMLSuite.paneSplitterPaneModel( { position : "west", id:"westPane",size:200,minSize:100,maxSize:300,scrollbars:false } );
paneWest.addContent( new DHTMLSuite.paneSplitterContentModel( { id:"westContent",htmlElementId:'westContent',title:'West',tabTitle:'West pane',contentUrl:"y/questions" } ) );


var paneEast = new DHTMLSuite.paneSplitterPaneModel( { position : "east", id:"eastPane",size:150,minSize:100,maxSize:200 } );
paneEast.addContent( new DHTMLSuite.paneSplitterContentModel( { id:"eastContent",htmlElementId:'eastContent',title:'East',tabTitle: 'Tab 1' } ) );


var paneSouth = new DHTMLSuite.paneSplitterPaneModel( { position : "south", id:"southPane",size:80,minSize:50,maxSize:200,resizable:true } );
paneSouth.addContent( new DHTMLSuite.paneSplitterContentModel( { id:"southContent",htmlElementId:'southContent',title:'South pane' } ) );

var paneNorth = new DHTMLSuite.paneSplitterPaneModel( { position : "north", id:"northPane",size:80,scrollbars:false,resizable:false } );
paneNorth.addContent( new DHTMLSuite.paneSplitterContentModel( { id:"northContent",htmlElementId:'northContent',title:'' } ) );

var paneCenter = new DHTMLSuite.paneSplitterPaneModel( { position : "center", id:"centerPane",size:150,minSize:100,maxSize:200 } );
paneCenter.addContent( new DHTMLSuite.paneSplitterContentModel( { id: 'center',htmlElementId:'center',title:'Virtual World',tabTitle: 'Virtual World',closable:false } ) );


paneModel.addPane(paneSouth);
paneModel.addPane(paneEast);
paneModel.addPane(paneNorth);
paneModel.addPane(paneWest);
paneModel.addPane(paneCenter);



/* STEP 2 */
/* Create the pane object */
var paneSplitter = new DHTMLSuite.paneSplitter();
paneSplitter.addModel(paneModel);	// Add the data model to the pane splitter
paneSplitter.init();	// Add the data model to the pane splitter


// This function opens a new tab - called by the menu items
function openPage(position,id,contentUrl,title,tabTitle,closable)
{
	var inputArray = new Array();
	inputArray['id'] = id;
	inputArray['position'] = position;
	inputArray['contentUrl'] = contentUrl;
	inputArray['title'] = title;
	inputArray['tabTitle'] = tabTitle;
	inputArray['closable'] = closable;
	
	paneSplitter.addContent(position, new DHTMLSuite.paneSplitterContentModel( inputArray ) );
	paneSplitter.showContent(id);		
	
}

function openClassDocumentation()
{
	var docWin = window.open('../doc/js_docs_out/index.html');
	docWin.focus();
	
}
</script>

<ul id="menuModel" style="display:none">
	<li id="50000"><a href="/" title="Home">Home</a></li>
	<li id="50002" itemType="separator"></li>	
	<li id="500011"><a href="#">Modules</a>
		<ul width="150">
			<!--<li id="5000110" jsFunction="openPage('center','answers','y/questions','Questions & Answers','Answers')"><a href="#">Answers</a></li>
			<li id="5000111" jsFunction="openPage('center','pages_sn','pages/sn','Social Network Details','Social Network Details')"><a href="#">Social Network</a></li> -->
			<li id="5000112" jsFunction="openPage('center', 'pages_horo', 'pages/horo', 'Kundali Match Details', 'Kundali Match Details')"><a href="#">Kundli Match</a>	
				<ul width="200">
					<li id="50001121" jsFunction="openPage('center','horo','horo/','Kundali Match','Kundali Match')"><a href="#">Home</a></li>
					<li id="50001122" jsFunction="openPage('center','horo_locations','horo/location','Set Location','Set Location')"><a href="#">Set Locations</a></li>
					<li id="50001123" jsFunction="openPage('center','horo_mylocations','horo/mylocation','My Locations','My Locations')"><a href="#">My Locations</a></li>
					<li id="50001124" jsFunction="openPage('center','horo_mybirthdetails','horo/mybirthdetails','My Birthdetails','My Birthdetails')"><a href="#">My Birth Details</a></li>
					<li id="50001127" jsFunction="openPage('center','horo_dailymatches','horo/dailymatches','Daily Matches','Daily Matches')"><a href="#">Daily Matches</a></li>
					<li id="50001128" jsFunction="openPage('center','horo_bestmatches','horo/bestmatches','Best Matches','Best Matches')"><a href="#">Best Matches</a></li>
					<!--<li id="50001125" jsFunction="openPage('center','horo_matchmaking','horo/matchmaking','Match Making','Match Making')"><a href="#">Match Making</a></li> -->
					<li id="50001126" jsFunction="openPage('center','horo_matchmakingprofile','horo/matchmakingprofile','Match Making Profile','Match Making Profile')"><a href="#">Match Making Profiles</a></li>
					<?php if (!empty($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup'] === 'admin') { ?>
						<li id="50001127" jsFunction="openPage('center','horo_history','horo/history','History','History')"><a href="#">History</a></li>
					<?php } ?>
				</ul></li>
			<?php if (!empty($_SESSION['user_id'])) { ?>
			<li id="5000200"><a href="/user/logout">Logout</a>
			<?php } ?>
		</ul>
	</li>
</ul>
<script type="text/javascript">

	
	var menuModel = new DHTMLSuite.menuModel();
	DHTMLSuite.configObj.setCssPath('Templates/theme1/demos/css/');
	menuModel.addItemsFromMarkup('menuModel');
	menuModel.setMainMenuGroupWidth(00);	
	menuModel.init();
	
	var menuBar = new DHTMLSuite.menuBar();
	menuBar.addMenuItems(menuModel);
	
	menuBar.setLayoutCss('menu-bar-ps.css');
	menuBar.setMenuItemLayoutCss('menu-item-ps.css');
	
	menuBar.setLayoutCss('menu-bar-ps-gray.css');
	menuBar.setMenuItemLayoutCss('menu-item-ps-gray.css');
		

	menuBar.setTarget('innerDiv');
	
	menuBar.init();
	
	DHTMLSuite.configObj.resetCssPath();
	$(document).ready(function() {
		// Handler for .ready() called.
		<?php if (!empty($_GET['url'])) { 
		$url = $_GET['url'];
		?>
			openPage('center','url','<?php echo $url; ?>','<?php echo ucwords($url); ?>','<?php echo ucwords($url); ?>');
		<?php } ?>
	});
</script>
</body>
</html>
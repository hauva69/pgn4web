<?php

/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009-2014 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

error_reporting(E_ALL | E_STRICT);


$enableLogging = false;


$appName = 'Live Games';

if (isset($_SERVER['REQUEST_URI']) && preg_match('/\?install(#|$)/', $_SERVER['REQUEST_URI'], $matches)) {
  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
  if(strstr($ua, 'android')) {
    $platform = 'Android';
  } else if (strstr($ua, 'ipad') || strstr($ua, 'iphone') || strstr($ua, 'ipod')) {
    $platform = 'iOS';
  } else {
    $platform = 'other';
  }

  $html = <<<END
<!DOCTYPE HTML>
<html>
<head>
<title>$appName web application installation</title>
<style type="text/css">
a { color: white; }
body { color: white; background: black; font-family: sans-serif; padding: 2em; }
li { margin-bottom: 1em; }
.icon { float: right; margin-left: 2em; border-radius: 10%; opacity: 0.5; transition: opacity 0.3s; }
</style>
</head>
<body>
<img id="appIcon" class="icon" src="live-games-app-icon-60x60.png" onMouseOver="appIconOpacity(1);" onMouseOut="appIconOpacity(0.5);" />
<h1>$appName web application installation</h1>
END;

  if ($platform == 'Android') {
    $html .= <<<END
<ol>
<li>open the <a id="webapplink" href="" target="_blank">web application URL</a> on a new tab of the android browser</li>
<li>bookmark the URL</li>
<li>open the bookmark list</li>
<li>tap and hold the newly created bookmark and select "add shortcut to home" from the menu</li>
<li>the <span onMouseOver="appIconOpacity(1);" onMouseOut="appIconOpacity(0.5);">web application icon</span> should appear on the home screen</li>
</ol>
END;
  } else if ($platform == 'iOS') {
    $html .= <<<END
<ol>
<li>open the <a id="webapplink" href="" target="_blank">web application URL</a> on a new page of the safari browser</li>
<li>tap on the action menu button at the bottom of the screen</li>
<li>select the "add to home screen" action</li>
<li>the <span onMouseOver="appIconOpacity(1);" onMouseOut="appIconOpacity(0.5);">web application icon</span> should appear on the home screen</li>
</ol>
END;
  } else {
    $html .= <<<END
<ol id="openappOl" style="display: none;">
<li>start the <a href="javascript:void(0);" onclick="installOpenWebApp();">web application installation script</a></li>
<li>the <span onMouseOver="appIconOpacity(1);" onMouseOut="appIconOpacity(0.5);">web application icon</span> should appear on the start menu, on the desktop or on the home screen</li>
</ol>
<ol id="otherOl" style="display: none;">
<li>open the <a id="webapplink" href="" target="_blank">web application URL</a> on a new page of the browser</li>
<li>bookmark the URL</li>
<li>open the newly created bookmark and use the web application from the browser</a>
<li>optionally, if available from the browser, use a command like "add site to start menu", "add site to start screen", "add site to apps", "create application shortcuts", "add shortcut to home", "add to home screen" or similar in order to add the <span onMouseOver="appIconOpacity(1);" onMouseOut="appIconOpacity(0.5);">web application icon</span> to the start menu, to the desktop or to the home screen</li>
</ol>
<script type="text/javascript">
"use strict";
document.getElementById((navigator && navigator.mozApps && navigator.mozApps.install) ? "openappOl" : "otherOl").style.display = "";
function installOpenWebApp() {
  try {
    var installOpenWebAppRequest = navigator.mozApps.install(location.href.replace(/\.php\?install.*/, ".webapp"));
    installOpenWebAppRequest.onerror = function() {
      if ((this.error.name == "MULTIPLE_APPS_PER_ORIGIN_FORBIDDEN") || (this.error.name == "REINSTALL_FORBIDDEN")) {
        alert("error: installation failed: web application already installed for this domain");
      } else {
        alert("error: web application installation failed");
      }
    };
  } catch(e) { alert("error: web application installation not supported"); }
}
</script>
END;
  }

  $html .= <<<END
The <a href="https://code.google.com/p/pgn4web/wiki/WebApp_LiveGames" target="_blank">live games web application wiki page</a> provides further information and a detailed usage tutorial.
<script type="text/javascript">
"use strict";
document.getElementById("webapplink").href = location.href.replace(/\?install(#|$)/, "$1");
function appIconOpacity(o) { document.getElementById("appIcon").style.opacity = o; }
</script>
</body>
</html>
END;

  print $html;
  exit;

}


$html = @file_get_contents("dynamic-frame.html");


function errorExit($errorNum) {
  global $appName;
  $html = <<<END
<!DOCTYPE HTML>
<html>
<head>
<title>$appName web application error</title>
</head>
<body style="color: white; background: black; font-family: sans-serif;">
$appName web application error: $errorNum
</body>
</html>
END;
  print $html;
  exit;
}


$actionNum = 0;
if (!$html) { errorExit($actionNum); }


$text = '("?l=t&" + window.location.hash + "&pf=a&ct=wood&bch=000000&fch=FFEEDD&hch=996633&scf=t&hc=t")';
$oldText = "window.location.search";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = '<html manifest="live-games-app.appcache">';
$oldText = "<html>";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = "<title>$appName</title>";
$oldText = "<title>chess games</title>";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = "liveStatusTickerString";
$oldText = "document.title";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = <<<END
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<script type="text/javascript">
"use strict";
window['defaultOpen'] = window.open;
window.open = function (winUrl, winTarget, winParam) {
  if ((winUrl) && (winUrl.match(/(^|\/)live-games-app-engine\.php/))) {
     window.location.href = winUrl;
     return null;
  } else if (!window.navigator.standalone) {
     return window.defaultOpen(winUrl, winTarget, winParam || "");
  } else if (winUrl) {
     var a = document.createElement("a");
     a.setAttribute("href", winUrl);
     a.setAttribute("target", winTarget ? winTarget : "_blank");
     var e = document.createEvent("HTMLEvents");
     e.initEvent("click", true, true);
     a.dispatchEvent(e);
     return null;
  }
  return null;
};
</script>
END;
$oldText = "<!-- AppCheck: meta -->";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = '<link rel="apple-touch-icon" href="live-games-app-icon-60x60.png" />';
$oldText = '<link rel="apple-touch-icon" href="pawn.png" />';
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = 'pgnData_default = "live-games-app.pgn";';
$oldText = 'pgnData_default = "live/live.pgn";';
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = "";
$oldText = "href='javascript:void(0);'";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = "";
$oldText = 'href="javascript:void(0);"';
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = "gameListLineHeight = Math.floor(2.1 * gameListFontSize);";
$oldText = "gameListLineHeight = Math.floor(1.4 * gameListFontSize);";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = 'myInsertRule(sheet, ".gameListBodyItems", "min-height: " + (gameListBodyHeight + (window.navigator.standalone ? 2 : 0)) + "px; min-width: " + (ww - 2 * framePadding + (window.navigator.standalone ? 1 : 0)) + "px;");';
$oldText = 'myInsertRule(sheet, ".gameListBodyItems", "min-height: " + gameListBodyHeight + "px; min-width: " + (ww - 2 * framePadding) + "px;");';
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = <<<END
  if (!appInitialized) {
    if (localStorage[lsId + "lastGameKey"]) {
      var lastGameKey = localStorage[lsId + "lastGameKey"];
      var lastGameVar = parseInt(localStorage[lsId + "lastGameVar"], 10) || 0;
      var lastGamePly = parseInt(localStorage[lsId + "lastGamePly"], 10);
      var lastGameAutoplay = localStorage[lsId + "lastGameAutoplay"] === "true";
      for (var gg = 0; gg < numberOfGames; gg++) {
        if (lastGameKey === gameKey(gameEvent[gg], gameSite[gg], gameDate[gg], gameRound[gg], gameWhite[gg], gameBlack[gg])) { break; }
      }
      if (gg < numberOfGames) {
        if (gg !== currentGame) { Init(gg); }
        if ((!isNaN(lastGamePly)) && ((lastGamePly !== CurrentPly) || (lastGameVar !== CurrentVar))) { GoToMove(lastGamePly, lastGameVar); }
        SetAutoPlay(lastGameAutoplay);
      }
    }
    appInitialized = true;
  }
END;
$oldText = "<!-- AppCheck: customFunctionOnPgnTextLoad -->";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = <<<END
  if (appInitialized) { localStorage[lsId + "lastGameKey"] = gameKey(gameEvent[currentGame], gameSite[currentGame], gameDate[currentGame], gameRound[currentGame], gameWhite[currentGame], gameBlack[currentGame]); }
END;
$oldText = "<!-- AppCheck: customFunctionOnPgnGameLoad -->";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = <<<END
  if (appInitialized) {
    localStorage[lsId + "lastGameVar"] = CurrentVar;
    localStorage[lsId + "lastGamePly"] = CurrentPly;
    localStorage[lsId + "lastGameAutoplay"] = ((isAutoPlayOn) || (CurrentPly === StartPly + PlyNumber));
  }
END;
$oldText = "<!-- AppCheck: customFunctionOnMove -->";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


$text = <<<END
var appInitialized = false;

var liveStatusTickerString = "";

var lsId = "pgn4web_live_games_app_";

var storageId = "3";
if ((localStorage[lsId + "storageId"] !== storageId) || (localStorage[lsId + "locationHref"] !== window.location.href)) {
  window.localStorage.clear();
  localStorage[lsId + "storageId"] = storageId;
  localStorage[lsId + "locationHref"] = window.location.href;
}

window['defaultSetAutoPlay'] = window['SetAutoPlay'];
window['SetAutoPlay'] = function(vv) {
  defaultSetAutoPlay(vv);
  if (appInitialized) {
    localStorage[lsId + "lastGameAutoplay"] = ((isAutoPlayOn) || (CurrentPly === StartPly + PlyNumber));
  }
};

window['defaultSetAutoplayDelay'] = window['SetAutoplayDelay'];
window['SetAutoplayDelay'] = function(vv) {
  defaultSetAutoplayDelay(vv);
  localStorage[lsId + "Delay"] = Delay;
};
if (typeof(localStorage[lsId + "Delay"]) == "string") {
  var newDelay = parseInt(localStorage[lsId + "Delay"], 10);
  if (!isNaN(newDelay)) { Delay = newDelay; }
}

window['defaultSetHighlightOption'] = window['SetHighlightOption'];
window['SetHighlightOption'] = function(on) {
  defaultSetHighlightOption(on);
  localStorage[lsId + "highlightOption"] = highlightOption;
};
if (typeof(localStorage[lsId + "highlightOption"]) == "string") {
  highlightOption = (localStorage[lsId + "highlightOption"] == "true");
}

window['defaultToggleShowEco'] = window['toggleShowEco'];
window['toggleShowEco'] = function() {
  defaultToggleShowEco();
  localStorage[lsId + "showEco"] = showEco;
};
if (typeof(localStorage[lsId + "showEco"]) == "string") {
  showEco = (localStorage[lsId + "showEco"] == "true");
}

window['defaultToggleColorFlag'] = window['toggleColorFlag'];
window['toggleColorFlag'] = function() {
  defaultToggleColorFlag();
  localStorage[lsId + "showColorFlag"] = showColorFlag;
};
if (typeof(localStorage[lsId + "showColorFlag"]) == "string") {
  showColorFlag = (localStorage[lsId + "showColorFlag"] == "true");
}

window['defaultPauseLiveBroadcast'] = window['pauseLiveBroadcast'];
window['pauseLiveBroadcast'] =  function() {
  defaultPauseLiveBroadcast();
  localStorage[lsId + "LiveBroadcastPaused"] = LiveBroadcastPaused;
  fixGameLiveStatusExtraInfo();
};
window['defaultRestartLiveBroadcast'] = window['restartLiveBroadcast'];
window['restartLiveBroadcast'] =  function() {
  defaultRestartLiveBroadcast();
  localStorage[lsId + "LiveBroadcastPaused"] = LiveBroadcastPaused;
};
if (typeof(localStorage[lsId + "LiveBroadcastPaused"]) == "string") {
  LiveBroadcastPaused = (localStorage[lsId + "LiveBroadcastPaused"] == "true");
}

var lastGameLiveStatusExtraInfoRes = LOAD_PGN_FAIL;
window['defaultCustomFunctionOnCheckLiveBroadcastStatus'] = window['customFunctionOnCheckLiveBroadcastStatus'];
window['customFunctionOnCheckLiveBroadcastStatus'] = function() {
  defaultCustomFunctionOnCheckLiveBroadcastStatus();
  fixGameLiveStatusExtraInfo();
};

function fixGameLiveStatusExtraInfo(res) {
  if (typeof(res) != "undefined") {
    lastGameLiveStatusExtraInfoRes = res;
  }
  var newExtraText = "";
  if (LiveBroadcastDelay && LiveBroadcastDemo) { newExtraText += "<span title='this is a broadcast simulation'>demo</span>"; }
  if (lastGameLiveStatusExtraInfoRes === LOAD_PGN_FAIL) {
    if (newExtraText) { newExtraText += "<span style='margin-left:1.25em;'"; } // 1.25em corresponds to fontSizeRatio = 0.8
    else { newExtraText += "<span"; }
    newExtraText += " title='games from application cache'>";
    newExtraText += ((!localStorage[lsId + "lastGamesValidationTime"]) || ((new Date()).getTime() - localStorage[lsId + "lastGamesValidationTime"]) > 18000000) ? "X" : "x";
    newExtraText += "</span>";
    // 5h = 18000000ms
  }
  if (LiveBroadcastDelay && (LiveBroadcastPaused || LiveBroadcastEnded)) {
    if (newExtraText) { newExtraText += "<a style='margin-left:1.25em;'"; } // 1.25em corresponds to fontSizeRatio = 0.8
    else { newExtraText += "<a"; }
    if (LiveBroadcastEnded) {
      newExtraText += " onclick='refreshPgnSource(); this.blur();' title='live broadcast ended'>O</a>";
    } else {
      newExtraText += " onclick='restartLiveBroadcast(); this.blur();' title='live broadcast automatic games refresh paused'>o</a>";
    }
  }
  var theObj = document.getElementById("GameLiveStatusExtraInfoRight");
  if (theObj) {
    theObj.style.visibility = newExtraText ? "visible" : "hidden";
    var otherObj = document.getElementById("GameLiveStatusExtraInfoLeft");
    if (otherObj) { otherObj.innerHTML = theObj.innerHTML = newExtraText; }
  }
}

window['defaultLoadPgnCheckingLiveStatus'] = window['loadPgnCheckingLiveStatus'];
window['loadPgnCheckingLiveStatus'] = function(res) {
  fixGameLiveStatusExtraInfo(res);
  if (res === LOAD_PGN_OK) {
    var text = "";
    for (var ii = 0; ii < numberOfGames; ++ii) { text += fullPgnGame(ii) + "\\n\\n"; }
    localStorage[lsId + "lastGamesPgnText"] = simpleHtmlentitiesDecode(text);
    localStorage[lsId + "lastGamesLastModifiedHeader"] = LiveBroadcastLastModifiedHeader;
    localStorage[lsId + "lastGamesLastReceivedLocal"] = LiveBroadcastLastReceivedLocal;
  }
  if ((res === LOAD_PGN_OK) || (res === LOAD_PGN_UNMODIFIED)) {
    localStorage[lsId + "lastGamesValidationTime"] = (new Date()).getTime();
  }
  defaultLoadPgnCheckingLiveStatus(res);
};

window['defaultLoadPgnFromPgnUrl'] = window['loadPgnFromPgnUrl'];
window['loadPgnFromPgnUrl'] = function(pgnUrl) {
  var rememberAppInitialized = appInitialized;
  if (!appInitialized) {
    var theObj = document.getElementById("GameLiveStatusExtraInfoRight");
    if (theObj) { theObj.style.visibility = "visible"; }
    var initialPgnGames = localStorage[lsId + "lastGamesPgnText"] || '[Event "please wait..."]\\n[Site "live games app"]\\n[Date "startup"]\\n[Round ""]\\n[White ""]\\n[Black ""]\\n[Result "*"]\\n';
    if (!pgnGameFromPgnText(initialPgnGames)) {
      myAlert("error: invalid games cache");
    } else {
      if (typeof(localStorage[lsId + "lastGamesLastModifiedHeader"]) == "string") {
        LiveBroadcastLastModifiedHeader = localStorage[lsId + "lastGamesLastModifiedHeader"];
        LiveBroadcastLastModified = new Date(LiveBroadcastLastModifiedHeader);
      }
      if (typeof(localStorage[lsId + "lastGamesLastReceivedLocal"]) == "string") {
        LiveBroadcastLastReceivedLocal = localStorage[lsId + "lastGamesLastReceivedLocal"];
      }
      firstStart = true;
      undoStackReset();
      Init();
      LiveBroadcastStarted = true;
      checkLiveBroadcastStatus();
      customFunctionOnPgnTextLoad();
    }
  }
  if (rememberAppInitialized || !LiveBroadcastPaused) { defaultLoadPgnFromPgnUrl(pgnUrl); }
  else { fixGameLiveStatusExtraInfo(); }
};

function detectEngineLocation() {
  return detectJavascriptLocation().replace(/(pgn4web|pgn4web-compacted)\\.js/, "live-games-app-engine.php");
}

engineWinParametersSeparator = "#?";

boardShortcut("F8", "live games web application wiki", function(t,e){ window.open("https://code.google.com/p/pgn4web/wiki/WebApp_LiveGames", "pgn4web_webAppWiki"); });

boardShortcut("H5", "reset live games web application", function(t,e){ if (confirm("Reset live games web application to default configuration?\\n\\nWarning: application settings customizations, games data and engine analysis data will be lost.")) { window.localStorage.clear(); window.location.reload(); } });

function gameKey(event, site, date, round, white, black) {
  var key = "";
  key += "[" + (typeof(event) == "string" ? event : "") + "]";
  key += "[" + (typeof(site) == "string" ? site : "") + "]";
  key += "[" + (typeof(date) == "string" ? date : "") + "]";
  key += "[" + (typeof(round) == "string" ? round : "") + "]";
  key += "[" + (typeof(white) == "string" ? white : "") + "]";
  key += "[" + (typeof(black) == "string" ? black : "") + "]";
  return key;
}


function GoToMove_forTouchEnd(thisPly) {
  GoToMove(thisPly);
}

function pgn4web_handleTouchEnd_HeaderContainer(e) {
  e.stopPropagation();
  var jj, deltaX, deltaY;
  for (var ii = 0; ii < e.changedTouches.length; ii++) {
    if ((jj = pgn4webOngoingTouchIndexById(e.changedTouches[ii].identifier)) != -1) {
      if (pgn4webOngoingTouches.length == 1) {
        deltaX = e.changedTouches[ii].clientX - pgn4webOngoingTouches[jj].clientX;
        deltaY = e.changedTouches[ii].clientY - pgn4webOngoingTouches[jj].clientY;
        if (Math.max(Math.abs(deltaX), Math.abs(deltaY)) >= 13) {
          if (Math.abs(deltaY) > 1.5 * Math.abs(deltaX)) {
            if (deltaY > 0) { // vertical down
              showEngineAnalysisBoard();
            } else { // vertical up
              showGameList();
            }
          } else if (Math.abs(deltaX) > 1.5 * Math.abs(deltaY)) { // horizontal left or right
            GoToMove_forTouchEnd(CurrentPly + sign(deltaX));
          }
        }
        pgn4webMaxTouches = 0;
      }
      pgn4webOngoingTouches.splice(jj, 1);
    }
  }
  clearSelectedText();
}

function pgn4web_handleTouchEnd_Header(e) {
  e.stopPropagation();
  var jj, deltaX, deltaY, theObj;
  for (var ii = 0; ii < e.changedTouches.length; ii++) {
    if ((jj = pgn4webOngoingTouchIndexById(e.changedTouches[ii].identifier)) != -1) {
      if (pgn4webOngoingTouches.length == 1) {
        deltaX = e.changedTouches[ii].clientX - pgn4webOngoingTouches[jj].clientX;
        deltaY = e.changedTouches[ii].clientY - pgn4webOngoingTouches[jj].clientY;
        if (Math.max(Math.abs(deltaX), Math.abs(deltaY)) >= 13) {
          if (Math.abs(deltaX) > 1.5 * Math.abs(deltaY)) { // horizontal left or right
            selectGameList(-1);
          }
        }
        pgn4webMaxTouches = 0;
      }
      pgn4webOngoingTouches.splice(jj, 1);
    }
  }
  clearSelectedText();
}

function pgn4web_handleTouchStart_scroll(e) {
  if (window.navigator.standalone) {
    if (this.scrollTop === 0) { this.scrollTop += 1; }
    if (this.scrollTop === this.scrollHeight - this.clientHeight) { this.scrollTop -= 1; }
  }
  this.allowUp = (this.scrollTop > 0);
  this.allowDown = (this.scrollTop < this.scrollHeight - this.clientHeight);
  this.lastY = e.pageY;
}

function pgn4web_handleTouchMove_scroll(e) {
  var up = (e.pageY > this.lastY);
  var down = (e.pageY < this.lastY);
  var flat = (e.pageY === this.lastY);
  this.lastY = e.pageY;
  if ((up && this.allowUp) || (down && this.allowDown) || (flat)) { e.stopPropagation(); }
  else { e.preventDefault(); }
}

if (touchEventEnabled) {
  if (theObj = document.getElementById("HeaderContainer")) {
    simpleAddEvent(theObj, "touchstart", pgn4web_handleTouchStart);
    simpleAddEvent(theObj, "touchmove", pgn4web_handleTouchMove);
    simpleAddEvent(theObj, "touchend", pgn4web_handleTouchEnd_HeaderContainer);
    simpleAddEvent(theObj, "touchleave", pgn4web_handleTouchEnd_HeaderContainer);
    simpleAddEvent(theObj, "touchcancel", pgn4web_handleTouchCancel);
  }

  if (theObj = document.getElementById("GameListHeader")) {
    simpleAddEvent(theObj, "touchstart", pgn4web_handleTouchStart);
    simpleAddEvent(theObj, "touchmove", pgn4web_handleTouchMove);
    simpleAddEvent(theObj, "touchend", pgn4web_handleTouchEnd_Header);
    simpleAddEvent(theObj, "touchleave", pgn4web_handleTouchEnd_Header);
    simpleAddEvent(theObj, "touchcancel", pgn4web_handleTouchCancel);
  }

  simpleAddEvent(document.body, "touchmove", function(e) { e.preventDefault(); });

  if (theObj = document.getElementById("GameListBody")) {
    simpleAddEvent(theObj, "touchstart", pgn4web_handleTouchStart_scroll);
    simpleAddEvent(theObj, "touchmove", pgn4web_handleTouchMove_scroll);
  }
}

simpleAddEvent(window.applicationCache, "updateready", function(e) {
  window.applicationCache.swapCache();
  window.location.reload();
});
END;
$oldText = "<!-- AppCheck: footer -->";
$actionNum += 1;
if (!strstr($html, $oldText)) { errorExit($actionNum); }
$html = str_replace($oldText, $text, $html);


print $html;


if ($enableLogging) {
  $logentry = strftime("%Y-%m-%d %H:%M:%S") . sprintf("  %15s  ", $_SERVER['REMOTE_ADDR']) . $_SERVER['HTTP_USER_AGENT'] . "\n";
  $logfile = preg_replace("/\.php$/", ".log", __FILE__);
  $logfp = fopen($logfile, 'a');
  fwrite($logfp, $logentry);
  fclose($logfp);
}

?>

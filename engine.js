/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009-2012 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

var pgn4web_engineWindowTarget = "pgn4web_engine_analysis";
var pgn4web_engineWindowUrlParameters = "";
var pgn4web_engineWindowHeight = 30 * 12; // window height/width corresponding to default squareSize = 30
var pgn4web_engineWindowWidth = 30 * 10;

// note: all pages on the same site will use the same engine analysis popup; if the engine analysis is embedded as iframe within a page (see the live-results-viewer.html example) the pgn4web_engineWindowTarget variable should be customized in order to avoid conflicts


function showEngineAnalysisBoard() {
   var engineWin;
   if (window.Worker) {
      if ((typeof(gameVariant[currentGame]) == "undefined") || (gameVariant[currentGame].match(/^(chess|normal|standard|)$/i) !== null)) {
         var options = "resizable=no,scrollbars=no,toolbar=no,location=no,menubar=no,status=no";
         if (pgn4web_engineWindowHeight) { options = "height=" + pgn4web_engineWindowHeight + "," + options; }
         if (pgn4web_engineWindowWidth) { options = "width=" + pgn4web_engineWindowWidth + "," + options; }
         engineWin = window.open("engine.html?fs=" + CurrentFEN() + (pgn4web_engineWindowUrlParameters ? "&" : "") + pgn4web_engineWindowUrlParameters, pgn4web_engineWindowTarget, options);
         if ((typeof(engineWin) != "undefined") && (engineWin.top === engineWin.self) && (window.focus)) { engineWin.focus(); }
      } else {
         myAlert("pgn4web engine analysis warning: the engine supports only normal chess; the " + gameVariant[currentGame] + " variant is not supported", true);
      }
   } else {
      myAlert("pgn4web engine analysis warning: missing web worker functionality from the web browser", true);
   }
   return engineWin ? true : false;
}

if (window.Worker) {
   boardShortcut("E8", "show/update engine analysis board", function(t,e){ if (e.shiftKey) { displayHelp("informant_symbols"); } else { showEngineAnalysisBoard(); } }, true);
}


/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009, 2011 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

//
// some parameters that might need reconfiguring for implementing pgn4web on your server
//

//
// the email for the project, default = 'pgn4web@casaschi.net'
// used by: home.html, board-generator.html and pgn4web.js
//
pgn4web_project_email = 'pgn4web@casaschi.net';
//

//
// the URL for the project's blog, default = 'http://pgn4web-blog.casaschi.net'
// used by: home.html
//
pgn4web_project_blog = 'http://pgn4web-blog.casaschi.net';
//

//
// the URL for the board widged to be used in the board-generator tool, default = full URL of local board.html file = pgn4web_board_url = location.protocol + "//" + location.hostname+location.pathname.substr(0, location.pathname.lastIndexOf("/")) + "/board.html";
// used by: board-generator.html
//
pgn4web_board_url = location.protocol + "//" + location.hostname+location.pathname.substr(0, location.pathname.lastIndexOf("/")) + "/board.html";
// pgn4web_board_url = 'http://pgn4web-board.casaschi.net/';
//

//
// the URL for the board generator tool, default = 'board-generator.html'
// used by: board-generator.html, widget.html
//
pgn4web_generator_url = 'board-generator.html';
// pgn4web_generator_url = 'http://pgn4web-board-generator.casaschi.net/';
//

//
// pointer URL for the live games broadcast, default = '.'
// used by: live.html, live-multi.html
//
pgn4web_live_pointer_url = '.';
// pgn4web_live_pointer_url = 'http://pgn4web-live-pointer.casaschi.net';
//

//
// the URL for the game viewer tool, default = 'demo.html?frame=inputform'
// used by: home.html
//
pgn4web_viewer_url = 'demo.html?frame=inputform';
// pgn4web_viewer_url = 'viewer.php';
//

//
// the URL for the puzzle of the day tool, default = 'demo.html?frame=tactics'
// used by: home.html, demo.html
//
pgn4web_puzzleoftheday_url = 'demo.html?frame=tactics';
// pgn4web_puzzleoftheday_url = 'demo.html?frame=puzzleoftheday';
//


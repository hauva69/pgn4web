# bash script to zip the pgn4web chrome extension for packaging

pgn4webVer=$(grep "var pgn4web_version = " ../pgn4web.js | awk -F "\'" '{print$2}')
chromeExtVer=$(grep '"version":' manifest.json | awk -F "\"" '{print$4}')

pgn4webChromeExtFilename="Chess-games-viewer-pgn4web-chrome-extension-$chromeExtVer-$pgn4webVer.zip"

if [ -e ../../"$pgn4webChromeExtFilename" ]; then
  echo "Error: pgn4web package already exists ../../$pgn4webChromeExtFilename"
  exit 1
fi

zip -9r ../../"$pgn4webChromeExtFilename" * -x *.svn/* -x zip-chrome-extension.sh -x updateInfo.xml

cd ..
zip -9r ../"$pgn4webChromeExtFilename" help.html pawn.ico pawns.png pgn4web.js alpha/README.txt alpha/36/* alpha/48/bp.png alpha/128/bp.png fonts/README.txt fonts/pgn4web-fonts.css fonts/LiberationSans-Regular.woff fonts/LiberationSans-Bold.woff fonts/ChessSansUsual.woff -x *.svn/*


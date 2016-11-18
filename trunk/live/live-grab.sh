# pgn4web javascript chessboard
# copyright (C) 2009-2016 Paolo Casaschi
# see README file and http://pgn4web.casaschi.net
# for credits, license and more details

# bash script periodically fetching a PGN file for a pgn4web live broadcast
# run as "bash script.sh"

set +o posix

localPgnFile_default=live.pgn
refreshSeconds_default=49
timeoutHours_default=12
startupDelaySeconds_default=0

if [ "$1" == "--no-shell-check" ]
then
   shift 1
else
   if [ "$(basename $SHELL)" != "bash" ]
   then
      echo "ERROR: $(basename $0) should be run with bash. Prepend --no-shell-check as first parameters to skip checking the shell type."
      exit
   fi
fi


if [ -z "$1" ] || [ "$1" == "--help" ]
then
   echo
   echo "$(basename $0) [--help] [--check] [remotePgnUrl localPgnFile refreshSeconds timeoutHours startupDelaySeconds]"
   echo
   echo "Shell script periodically fetching from a remote URL a PGN file for a pgn4web live broadcast."
   echo
   echo "Parameters:"
   echo "  remotePgnUrl: URL to fetch"
   echo "  localPgnFile: local PGN filename (default: $localPgnFile_default)"
   echo "  refreshSeconds: refresh rate in seconds (default: $refreshSeconds_default)"
   echo "  timeoutHours: timeout in hours for stopping the process (default: $timeoutHours_default)"
   echo "  startupDelaySeconds: startup delay in seconds (default: $startupDelaySeconds_default)"
   echo
   echo "Logs to 'localPgnFile'.log; it needs to be run using bash and requires curl"
   echo
   echo "--check option checks status of live-grab.sh processes, assuming that live-grab.sh is always started from its own directory so that the logFile path (if any) is relative to that directory; it needs to be run using bash and requires awk"
   echo

   exit
fi


if [ "$1" == "--check" ]
then

   if [ -z "$(which awk)" ]
   then
      echo "ERROR: missing awk"
   fi

   pgn4web_scan=$(ps -U $USER -w -o pid,command | sed -e 's/\--no-shell-check\>/ /g' | awk 'BEGIN {c=0} ($3=="live-grab.sh" && $4!="--check") {printf("pgn4web_pid[%d]=\"%s\";pgn4web_log[%d]=\"%s\".log;",c,$1,c,$5); c++}')

   eval $pgn4web_scan

   length=${#pgn4web_pid[@]}
   if [ $length -gt 0 ]
   then
      echo pgn4web live-grab.sh processes: $length
   fi

   pgn4web_dir=$(dirname $0)

   for ((i=0; i<length; i++))
   do
      if [ "${pgn4web_log[i]}" == ".log" ]
      then
         pgn4web_log[i]="live.pgn.log"
      fi

      if [ -n "$pgn4web_dir" ]
      then
         if [[ ${pgn4web_log[i]} != /* ]]
         then
            pgn4web_log[$i]=$pgn4web_dir"/"${pgn4web_log[i]}
         fi
      fi

      if [ -f "${pgn4web_log[$i]}" ]
      then
         pgn4web_steps[i]=$(cat ${pgn4web_log[$i]} | awk 'END { printf("%4d of %4d", $8, $10) }')
      else
         pgn4web_steps[i]="unavaiable  "
      fi
      echo "  pid: ${pgn4web_pid[$i]}  steps: ${pgn4web_steps[$i]}  log: ${pgn4web_log[$i]}"
   done
   exit

fi


function print_log {
   if [ -n "$1" ]
   then
      log="$(date '+%b %d %T') $(hostname) $(basename $0) [$$]: $1"
   else
      log=""
   fi
   if [ -n "$logFile" ]
   then
      echo $log >> $logFile
   else
      echo $log
   fi
}

first_print_error="notYet";
function print_error {
   if [ -n "$logFile" ]
   then
      echo $(date) $(basename $0) ERROR: $1 >> $logFile
   fi
      if [ -n "$first_print_error" ]
   then
      first_print_error=
      echo > /dev/stderr
   fi
   echo $(basename $0) ERROR: $1 > /dev/stderr
}

umask 0000
if [ $? -ne 0 ]
then
   print_error "failed setting umask 0000"
   exit
fi

if [ -z "$1" ]
then
   exit
else
   remotePgnUrl=$1
fi

if [ -z "$2" ]
then
   localPgnFile=$localPgnFile_default
else
   localPgnFile=$2
fi
if [ -e "$localPgnFile" ] || [ -h "$localPgnFile" ]
then
   print_error "localPgnFile $localPgnFile exists"
   print_error "delete the file or choose another filename and restart"
   exit
fi
if [ $(echo "$localPgnFile" | grep "\*") ]
then
   print_error "localPgnFile should not contain \"*\""
   exit
fi
if [ $(echo "$localPgnFile" | grep "\?") ]
then
   print_error "localPgnFile should not contain \"?\""
   exit
fi
if [ $(echo "$localPgnFile" | grep "\[") ]
then
   print_error "localPgnFile should not contain \"[\""
   exit
fi
if [ $(echo "$localPgnFile" | grep "\]") ]
then
   print_error "localPgnFile should not contain \"]\""
   exit
fi
tmpLocalPgnFile=$localPgnFile.tmp

logFile=$localPgnFile.log
if [ -e "$logFile" ] || [ -h "$logFile" ]
then
   print_error "logFile $logFile exists"
   print_error "delete the file or choose another localPgnFile name and restart"
   exit
fi
print_log "start"

if [ -z "$3" ]
then
   refreshSeconds=$refreshSeconds_default
else
   if [[ "$3" =~ ^[0-9]+$ ]]
   then
      refreshSeconds=$3
   else
      print_error "refreshSeconds must be a number: $3"
      exit
   fi
fi

if [ -z "$4" ]
then
   timeoutHours=$timeoutHours_default
else
   if [[ "$4" =~ ^[0-9]+$ ]]
   then
      timeoutHours=$4
   else
      print_error "timeoutHours must be a number: $4"
      exit
   fi
fi
timeoutSteps=$((3600*$timeoutHours/$refreshSeconds))

if [ -z "$5" ]
then
   startupDelaySeconds=$startupDelaySeconds_default
else
   if [[ "$5" =~ ^[0-9]+$ ]]
   then
      startupDelaySeconds=$5
   else
      print_error "startupDelaySeconds must be a number: $5"
      exit
   fi
fi

if [ -z "$(which curl)" ]
then
   print_error "missing curl"
   exit
else
   grabCmdLine="curl --silent --remote-time --time-cond $tmpLocalPgnFile --output $tmpLocalPgnFile --url $remotePgnUrl"
fi
   # wget alternative to curl, but --timestamping option is not compatible with --output-document (hence some complex sequence of downloading and renaming would be required)
   # grabCmdLine="wget --quiet --output-document=$tmpLocalPgnFile $remotePgnUrl"

print_log "remoteUrl: $remotePgnUrl"
print_log "localPgnFile: $localPgnFile"
print_log "refreshSeconds: $refreshSeconds"
print_log "timeoutHours: $timeoutHours"
print_log "startupDelaySeconds: $startupDelaySeconds"

if [ $startupDelaySeconds -gt 0 ]
then
   print_log "waiting $startupDelaySeconds at startup"
   sleep $startupDelaySeconds
fi

step=0
while [ $step -le $timeoutSteps ]
do
   $grabCmdLine
   cmp -s "$tmpLocalPgnFile" "$localPgnFile"
   if [ $? -ne 0 ]
   then
      cp "$tmpLocalPgnFile" "$localPgnFile"
      print_log "step $step of $timeoutSteps, new PGN data found"
   else
      print_log "step $step of $timeoutSteps, no new data"
   fi
   step=$(($step +1))
   sleep $refreshSeconds
done


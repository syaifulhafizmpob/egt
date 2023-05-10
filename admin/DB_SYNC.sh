#!/bin/bash
# sync.sh: nawawi@rutweb.com

_PATH="/var/www/html/egunatenaga/admin";
_RUNDIR="${_PATH}";
_LOCKFILE="${_RUNDIR}/sync.lock";
_PHPBIN="/usr/bin/php";

if [ ! -d "${_RUNDIR}" ]; then
	echo "${_RUNDIR} not exist";
	exit -1;
fi

cd $_RUNDIR;

if [ ! -x "${_PHPBIN}" ]; then
	echo "php binary not found";
	exit -1;
fi

if [ -f "${_LOCKFILE}" ]; then
	_PID=$(< $_LOCKFILE);
	if [ -z "${_PID//[0-9]/}" -a -d "/proc/$_PID" ]; then
		echo "process run";
		exit -1;
	else
        echo "process terminated, overwrite lock file";
		rm -f $_LOCKFILE;
	fi
fi


trap "{ rm -f $_LOCKFILE ; exit 1; }" SIGINT SIGTERM SIGHUP SIGKILL SIGABRT EXIT;

echo $$ > $_LOCKFILE;

#$_PHPBIN -f $_RUNDIR/sync-kilang.php;
#$_PHPBIN -f $_RUNDIR/sync-biodiesel.php;
#$_PHPBIN -f $_RUNDIR/sync-peniaga.php;


rm -f $_LOCKFILE;
exit 0;

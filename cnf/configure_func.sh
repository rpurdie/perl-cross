# Tests for libc functions availability.

checkfunc() {
	require 'cc'
	mstart "Checking for $2"
	if not hinted $1 'found' 'missing'; then
		try_start
		funcincludes "$3" "$4" "$includes"
		try_add "int main(void) { $2($3); return 0; }"
		try_link -O0 -fno-builtin
		resdef $1 'found' 'missing'
	fi
}

funcincludes() {
	case "$1" in
		*NULL*) try_includes "stdlib.h" ;;
	esac

	test -n "$3" && try_includes $3
	test -n "$2" && try_includes $2
}

# The naming scheme looks regular but it isn't!

includes=''
checkfunc d__fwalk '_fwalk'
checkfunc d_access 'access' "NULL,0" 'unistd.h'
checkfunc d_accessx 'accessx'
checkfunc d_aintl 'aintl'
checkfunc d_alarm 'alarm' "0" 'unistd.h'
checkfunc d_asctime64 'asctime64'
checkfunc d_atolf 'atolf'
checkfunc d_atoll 'atoll'
checkfunc d_backtrace 'backtrace' 'NULL, 0' 'execinfo.h'
checkfunc d_bcmp 'bcmp' "NULL,NULL,0" 'string.h'
checkfunc d_bcopy 'bcopy' "NULL,NULL,0" 'string.h'
checkfunc d_bzero 'bzero' "NULL,0" 'string.h'
checkfunc d_chown 'chown' "NULL,0,0" 'unistd.h'
checkfunc d_chroot 'chroot' "NULL" 'unistd.h'
checkfunc d_chsize 'chsize' "0,0"
checkfunc d_class 'class'
checkfunc d_clearenv 'clearenv' "" 'stdlib.h'
checkfunc d_closedir 'closedir' "NULL"
checkfunc d_crypt 'crypt'
checkfunc d_ctermid 'ctermid'
checkfunc d_ctime64 'ctime64'
checkfunc d_cuserid 'cuserid'
checkfunc d_difftime 'difftime' "0,0"
checkfunc d_difftime64 'difftime64'
checkfunc d_dirfd 'dirfd'
checkfunc d_dladdr 'dladdr' 'NULL, NULL' 'dlfcn.h'
checkfunc d_dlerror 'dlerror'
checkfunc d_dlopen 'dlopen'
checkfunc d_drand48 'drand48'
checkfunc d_dup2 'dup2' "0,0"
checkfunc d_duplocale 'duplocale' '0' 'locale.h'
checkfunc d_eaccess 'eaccess'
checkfunc d_endgrent 'endgrent'
checkfunc d_endhent 'endhostent'
checkfunc d_endnent 'endnetent'
checkfunc d_endpent 'endprotoent'
checkfunc d_endpwent 'endpwent'
checkfunc d_endservent 'endservent'
checkfunc d_fchdir 'fchdir' "0" 'unistd.h'
checkfunc d_fchmod 'fchmod' "0,0" 'unistd.h'
checkfunc d_fchmodat 'fchmodat' "0,NULL,0,0" 'unistd.h'
checkfunc d_fchown 'fchown' "0,0,0" 'unistd.h'
checkfunc d_fcntl 'fcntl' "0,0" 'unistd.h fcntl.h'
checkfunc d_fdclose 'fdclose'
checkfunc d_fgetpos 'fgetpos' "NULL, 0" 'stdio.h'
checkfunc d_flock 'flock' "0,0" 'unistd.h'
checkfunc d_fork 'fork' "" 'unistd.h'
checkfunc d_fp_class 'fp_class'
checkfunc d_fpathconf 'fpathconf' "0,0" 'unistd.h'
checkfunc d_freelocale 'freelocale' '0' 'locale.h'
checkfunc d_fseeko 'fseeko' 'NULL,0,0'
checkfunc d_fsetpos 'fsetpos' 'NULL,0'
checkfunc d_fstatfs 'fstatfs'
checkfunc d_fstatvfs 'fstatvfs'
checkfunc d_fsync 'fsync'
checkfunc d_ftello 'ftello'
checkfunc d_futimes 'futimes'
checkfunc d_gai_strerror 'gai_strerror' '0' 'sys/types.h sys/socket.h netdb.h'
checkfunc d_getaddrinfo 'getaddrinfo'
checkfunc d_getcwd 'getcwd' 'NULL,0'
checkfunc d_getespwnam 'getespwnam'
checkfunc d_getfsstat 'getfsstat'
checkfunc d_getgrent 'getgrent'
checkfunc d_getgrps 'getgroups'
checkfunc d_gethbyaddr 'gethostbyaddr'
checkfunc d_gethbyname 'gethostbyname'
checkfunc d_getnbyaddr 'getnetbyaddr' '0,0' 'netdb.h'
checkfunc d_getnbyname 'getnetbyname' 'NULL' 'netdb.h'
checkfunc d_gethent 'gethostent'
checkfunc d_gethname 'gethostname'
checkfunc d_getitimer 'getitimer'
checkfunc d_getlogin 'getlogin'
checkfunc d_getmnt 'getmnt'
checkfunc d_getmntent 'getmntent'
checkfunc d_getnameinfo 'getnameinfo'
checkfunc d_getnent 'getnetent'
checkfunc d_getnetbyaddr 'getnetbyaddr'
checkfunc d_getnetbyname 'getnetbyname'
checkfunc d_getpagsz 'getpagesize'
checkfunc d_getpbyaddr 'getprotobyaddr'
checkfunc d_getpbyname 'getprotobyname'
checkfunc d_getpbynumber 'getprotobynumber'
checkfunc d_getpent 'getprotoent'
checkfunc d_getpgid 'getpgid'
checkfunc d_getpgrp 'getpgrp' "" 'unistd.h'
checkfunc d_getpgrp2 'getpgrp2'
checkfunc d_getppid 'getppid'
checkfunc d_getprior 'getpriority' "0,0" 'sys/time.h sys/resource.h'
checkfunc d_getprpwnam 'getprpwnam'
checkfunc d_getpwent 'getpwent'
checkfunc d_getsbyaddr 'getservbyaddr'
checkfunc d_getsbyname 'getservbyname'
checkfunc d_getsbyport 'getservbyport'
checkfunc d_getsent 'getservent'
checkfunc d_setsent 'setservent'
checkfunc d_endsent 'endservent'
checkfunc d_getspnam 'getspnam'
checkfunc d_gettimeod 'gettimeofday' 'NULL,NULL'
checkfunc d_gmtime64 'gmtime64'
checkfunc d_hasmntopt 'hasmntopt'
checkfunc d_htonl 'htonl' "0" 'stdio.h sys/types.h netinet/in.h arpa/inet.h'
checkfunc d_ilogbl 'ilogbl'
checkfunc d_index 'index' "NULL,0" 'string.h strings.h'
checkfunc d_inetaton 'inet_aton'
checkfunc d_inetntop 'inet_ntop'
checkfunc d_inetpton 'inet_pton'
checkfunc d_isascii 'isascii' "'A'" 'stdio.h ctype.h'
checkfunc d_isblank 'isblank' "' '" 'stdio.h ctype.h'
checkfunc d_killpg 'killpg'
checkfunc d_lchown 'lchown' "NULL, 0, 0" 'unistd.h'
checkfunc d_link 'link' 'NULL,NULL'
checkfunc d_linkat 'linkat' '0,NULL,0,NULL,0'
checkfunc d_localtime64 'localtime64'
checkfunc d_locconv 'localeconv'
checkfunc d_lockf 'lockf'
checkfunc d_lstat 'lstat'
checkfunc d_madvise 'madvise'
checkfunc d_malloc_good_size 'malloc_good_size'
checkfunc d_malloc_size 'malloc_size'
checkfunc d_mblen 'mblen'
checkfunc d_mbstowcs 'mbstowcs'
checkfunc d_mbtowc 'mbtowc'
checkfunc d_memchr 'memchr' "NULL, 0, 0" 'string.h'
checkfunc d_memcmp 'memcmp' "NULL, NULL, 0" 'string.h'
checkfunc d_memcpy 'memcpy' "NULL, NULL, 0" 'string.h'
checkfunc d_memmem 'memmem' "NULL, 0, NULL, 0" 'string.h'
checkfunc d_memmove 'memmove' "NULL, NULL, 0" 'string.h'
checkfunc d_memrchr 'memrchr' "NULL, 0, 0" 'string.h'
checkfunc d_memset 'memset' "NULL, 0, 0" 'string.h'
checkfunc d_mkdir 'mkdir' 'NULL, 0'
checkfunc d_mkdtemp 'mkdtemp'
checkfunc d_mkfifo 'mkfifo'
checkfunc d_mkstemp 'mkstemp' 'NULL'
checkfunc d_mkstemps 'mkstemps'
checkfunc d_mktime 'mktime' 'NULL'
checkfunc d_mktime64 'mktime64'
checkfunc d_mmap 'mmap'
checkfunc d_mprotect 'mprotect'
checkfunc d_msgctl 'msgctl'
checkfunc d_msgget 'msgget'
checkfunc d_msgrcv 'msgrcv'
checkfunc d_msgsnd 'msgsnd'
checkfunc d_msync 'msync'
checkfunc d_munmap 'munmap'
checkfunc d_newlocale 'newlocale' '0,NULL,0' 'locale.h'
checkfunc d_nice 'nice' '0'
checkfunc d_nl_langinfo 'nl_langinfo'
checkfunc d_open 'open' "NULL,0,0" 'sys/types.h sys/stat.h fcntl.h'
checkfunc d_openat 'openat' "0,NULL,0,0" 'sys/types.h sys/stat.h fcntl.h'
checkfunc d_pathconf 'pathconf'
checkfunc d_pause 'pause'
checkfunc d_pipe 'pipe' 'NULL'
checkfunc d_poll 'poll'
checkfunc d_prctl 'prctl'
checkfunc d_pthread_atfork 'pthread_atfork'
checkfunc d_pthread_attr_setscope 'pthread_attr_setscope'
checkfunc d_pthread_yield 'pthread_yield'
checkfunc d_querylocale 'querylocale'
checkfunc d_qgcvt 'qgcvt' '1.0,1,NULL'
checkfunc d_rand 'rand'
checkfunc d_random 'random'
checkfunc d_re_comp 're_comp'
checkfunc d_readdir 'readdir' 'NULL'
checkfunc d_readlink 'readlink'
checkfunc d_readv 'readv'
checkfunc d_recvmsg 'recvmsg'
checkfunc d_regcmp 'regcmp'
checkfunc d_regcomp 'regcomp'
checkfunc d_rename 'rename' 'NULL,NULL'
checkfunc d_renameat 'renameat' '0,NULL,0,NULL'
checkfunc d_rewinddir 'rewinddir'
checkfunc d_rmdir 'rmdir' 'NULL'
checkfunc d_sched_yield 'sched_yield'
checkfunc d_seekdir 'seekdir'
checkfunc d_select 'select' '0,NULL,NULL,NULL,NULL'
checkfunc d_semctl 'semctl'
checkfunc d_semget 'semget'
checkfunc d_semop 'semop'
checkfunc d_sendmsg 'sendmsg'
checkfunc d_setegid 'setegid'
checkfunc d_setent 'setservent'
checkfunc d_setenv 'setenv'
checkfunc d_seteuid 'seteuid'
checkfunc d_setgrent 'setgrent'
checkfunc d_setgrps 'setgroups'
checkfunc d_sethent 'sethostent'
checkfunc d_setitimer 'setitimer'
checkfunc d_setlinebuf 'setlinebuf'
checkfunc d_setlocale 'setlocale' "0,NULL" 'locale.h'
checkfunc d_setnent 'setnetent'
checkfunc d_setpent 'setprotoent'
checkfunc d_setpgid 'setpgid'
checkfunc d_setpgrp 'setpgrp'
checkfunc d_setpgrp2 'setpgrp2'
checkfunc d_setprior 'setpriority'
checkfunc d_setproctitle 'setproctitle'
checkfunc d_setpwent 'setpwent'
checkfunc d_setregid 'setregid'
checkfunc d_setresgid 'setresgid'
checkfunc d_setresuid 'setresuid'
checkfunc d_setreuid 'setreuid'
checkfunc d_setrgid 'setrgid'
checkfunc d_setruid 'setruid'
checkfunc d_setsid 'setsid'
checkfunc d_setvbuf 'setvbuf' 'NULL,NULL,0,0'
checkfunc d_sfreserve 'sfreserve' "" 'sfio.h'
checkfunc d_shmat 'shmat'
checkfunc d_shmctl 'shmctl'
checkfunc d_shmdt 'shmdt'
checkfunc d_shmget 'shmget'
checkfunc d_sigaction 'sigaction'
checkfunc d_sigprocmask 'sigprocmask'
checkfunc d_sigsetjmp 'sigsetjmp' "NULL,0" 'setjmp.h'
checkfunc d_snprintf 'snprintf'
checkfunc d_sockatmark 'sockatmark'
checkfunc d_socket 'socket' "0,0,0" 'sys/types.h sys/socket.h'
checkfunc d_sockpair 'socketpair'
checkfunc d_socks5_init 'socks5_init'
checkfunc d_stat 'stat'
checkfunc d_statvfs 'statvfs'
checkfunc d_strchr 'strchr' "NULL,0" 'string.h strings.h'
checkfunc d_strcoll 'strcoll' "NULL,NULL" 'string.h'
checkfunc d_strerror 'strerror' "0" 'string.h stdlib.h'
checkfunc d_strerror_l 'strerror_l'
checkfunc d_strftime 'strftime' "NULL,0,NULL,NULL" 'time.h'
checkfunc d_strlcat 'strlcat'
checkfunc d_strlcpy 'strlcpy'
checkfunc d_strtod 'strtod' 'NULL,NULL'
checkfunc d_strtol 'strtol' 'NULL,NULL,0'
checkfunc d_strtold 'strtold'
checkfunc d_strtoll 'strtoll'
checkfunc d_strtoq 'strtoq'
checkfunc d_strtoul 'strtoul' 'NULL,NULL,0'
checkfunc d_strtoull 'strtoull' 'NULL,NULL,0'
checkfunc d_strtouq 'strtouq'
checkfunc d_strxfrm 'strxfrm'
checkfunc d_symlink 'symlink'
checkfunc d_syscall 'syscall'
checkfunc d_sysconf 'sysconf' '0'
checkfunc d_system 'system' 'NULL'
checkfunc d_tcgetpgrp 'tcgetpgrp'
checkfunc d_tcsetpgrp 'tcsetpgrp'
checkfunc d_telldir 'telldir'
checkfunc d_time 'time' 'NULL'
checkfunc d_timegm 'timegm'
checkfunc d_times 'times' 'NULL'
checkfunc d_truncate 'truncate' 'NULL,0'
checkfunc d_ualarm 'ualarm'
checkfunc d_umask 'umask' '0'
checkfunc d_uname 'uname'
checkfunc d_unlinkat 'unlinkat' '0,NULL,0'
checkfunc d_unordered 'unordered'
checkfunc d_unsetenv 'unsetenv'
checkfunc d_uselocale 'uselocale' '0' 'locale.h'
checkfunc d_usleep 'usleep'
checkfunc d_ustat 'ustat'
define d_vfork 'undef' # unnecessary
checkfunc d_vprintf 'vprintf' 'NULL,0'
checkfunc d_vsnprintf 'vsnprintf'
checkfunc d_wait4 'wait4'
checkfunc d_waitpid 'waitpid' '0,NULL,0'
checkfunc d_wcscmp 'wcscmp'
checkfunc d_wcstombs 'wcstombs' 'NULL,NULL,0'
checkfunc d_wcsxfrm 'wcsxfrm'
checkfunc d_wctomb 'wctomb'
checkfunc d_writev 'writev'
unset includes

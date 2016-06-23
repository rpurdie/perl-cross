<? include "_head.php" ?>

<p>Generally you should start by unpacking perl-X.Y.Z-cross-W.Q.tar.gz over
perl-X.Y.Z distribution. It will overwrite some of the files; that's ok.</p>

<p>The build process is similar to that of most autoconfed packages.
For a native build, use something like</p>
<pre>
	./configure --prefix=/usr
	make
	make DESTDIR=/some/tmp/dir install
</pre>
<p>For a cross-build, specify your target:</p>
<pre>
	./configure --prefix=/usr --target=i586-pc-linux-uclibc
	make
	make DESTDIR=/some/tmp/dir install
</pre>

<p>Check below for <a href="#targets">other possible make targets</a>.</p>

<h2>Target-specific notes</h2>

<p><b>arm-linux-uclibc</b>: successful build is highly likely.
Check <tt>--target-tools-prefix</tt>, it may be useful here, especially
if you're using <tt>--sysroot</tt>.</p>
<pre>
	./configure --target=arm-linux-uclibc --target-tools-prefix=arm-linux-
</pre>
<p>That's for a <a href="http://buildroot.uclibc.org/">buildroot</a> toolchain,
which is likely to be misidentified as arm-unknown-linux-gnu while it actually
uses uClibc.</p>

<p><b>ix86-* with Intel cc</b>, native build: works well, use <tt>-Dcc=icc</tt>
(assuming it's in $PATH). Note that configure won't supply any optimization
options to the compiler, so you'll have to use <tt>-Doptimize</tt>.</p>

<p><b>Android</b> (assuming NDK is installed in <tt>/opt/android-ndk</tt>):</p>
<pre>
	ANDROID=/opt/android-ndk
	TOOLCHAIN=arm-linux-androideabi-4.9/prebuilt/linux-x86_64
	PLATFORM=$ANDROID/platforms/android-16/arch-arm
	export PATH=$PATH:$ANDROID/toolchains/$TOOLCHAIN/bin
	./configure --target=arm-linux-androideabi --sysroot=$PLATFORM
</pre>

<p><b>MinGW32</b>: can't be built yet. configure will produce (likely usable) config.sh,
but current perl-cross Makefiles can't handle win32 build.</p>
<pre>
	./configure --target=i486-mingw32 --no-dynaloader --disable-mod=ext/Errno
</pre>
<p>will get you about as far as possible for now.</p>

<h2>Complete configure options list</h2>

<p>Overall call order:</p>
<pre>
	configure [options]
</pre>

<p>configure adheres to common GNU autoconf style, but also accepts most
of the original Configure options. Both short, <tt>-D</tt>, and long, <tt>--define</tt>,
options are supported. Valid ways to supply arguments for the options:
<tt>-f config.sh</tt>, <tt>-fconfig.sh</tt>, <tt>-D key=val</tt>, <tt>-Dkey=val</tt>,
<tt>--set-key=val</tt>, <tt>--set key=val</tt>.
Whenever necessary, dashes in "key" are converted to underscores so it's ok
to use <tt>--set-d-<i>something</i></tt> instead of <tt>--set-d_<i>something</i></tt>.</p>

<p>Setting variables among options (i.e. <tt>configure CC=gcc --prefix=/usr</tt>) is not allowed.</p>

<p>The only essential thing configure does is writing <tt>config.sh</tt>
(and possibly <tt>xconfig.sh</tt>). Most options are meant to alter the values
written there. For description of the variables from <tt>config.sh</tt>, please
refer to Proting/Glossary in the perl source directory. This page only
decribes <i>how</i> to modify them, not <i>which values</i> to use.</p>

<p>The options are not processed in order; see <a href="#workflow">Workflow</a> below.</p>

<p>General configure control options:</p>
<dl>
	<dt>--help</dt>	
		<dd>dump a short help message on stdout and exit</dd>
	<dt>--mode=(native|cross|target|buildmini)</dt>
		<dd>set configure mode:<ul class="fixtabshort">
			<li><tt>native</tt> primary run for a native build; writes <tt>config.sh</tt></li>
			<li><tt>cross</tt> top-level run for a cross build; spawns two copies of
				configure, one with <tt>--mode=buildmini</tt> and
				one with <tt>--mode=target</tt></li>
			<li><tt>buildmini</tt> miniperl configuration for a cross-build, writes <tt>xconfig.sh</tt></li>
			<li><tt>target</tt> target configuration for a cross-build, writes <tt>config.sh</tt></li>
		</ul>
		Default is "native" if target is empty or equals build (see below), "cross" otherwise.</dd>
	<dt>--keeplog</dt>
		<dd>Append to config.log instead of truncating it. Used internally for
		buildmini/target processes in cross mode.</dd>
	<dt>--regenerate</dt>
		<dd>Re-generates config.h, xconfig.h and Makefile.config from config.sh and xconfig.sh.
		Useful after making manual changes to config.sh. Does not change config.sh or xconfig.sh.
		Check the last ten or so lines in cnf/configure to see how it works.</dd>
</dl>

<p>General installation setup:</p>
<dl>
	<dt>--prefix=<i>/usr</i></dt>
		<dd>Installation prefix (on the target). Also, used as a base for default values
		of other --*dir options below.</dd>
	<dt>--html{1,3}dir=<i>dir</i></dt>
		<dd>Installation prefix for HTML documentation (not used)</dd>
	<dt>--man{1,3}dir=<i>dir</i></dt>
		<dd>Installation prefix for manual pages</dd>
	<dt>--target=<i>machine</i></dt>
		<dd>Standard <i>cpu-mach-os</i> target description (e.g. i586-pc-linux-uclibc);
		in most cases it means machine-gcc, machine-ld, machine-ar etc. should be used.
		configure will try to accept almost anyting there (unlike GNU autoconf), but
		it's advised to use description that cnf/config.sub is able to parse.
		Setting this affects three things: default configure mode selection (cross if --target is set),
		default compiler/binutils names to try, and several *arch* variables in config.sh</dd>
	<dt>--target-tools-prefix=<i>prefix</i></dt>
		<dd>Forces <i>prefix-</i>gcc, <i>prefix-</i>ld etc. to be used regardless of
		<tt>--target</tt> value. Does not affect hints, *arch* variables or anything else
		except for toolchain selection. Generally goes together with <tt>--sysroot</tt>.</dd>
	<dt>--build=<i>machine</i></dt>
		<dd>Same as --target but for the build host (i.e. for miniperl)</dd>
	<dt>--hints=<i>h1,h2,...</i></dt>
		<dd>Suggest specified hint files (cnf/hints/h1 etc.). The hints are processed after other options,
		see <a href="#workflow">Workflow</a> below.
		Specifying non-existing hint here will only result in a warning. <!-- TODO -->
		This options does not affect hint selection for modules because they use completely different hint system.
		</dd>

	<dt>--with-libs=<i>lib1,lib2,...</i></dt>
		<dd>Comma-separated list of libraries to check (only basenames,
		use "dl" to have -ldl passed to the linker). <!-- TODO --></dd>

	<dt>--with-cc=<i>cmd</i></dt>
		<dd>(target) C compiler. May be useful if the compiler has non-standard prefix
		and <tt>configure --target</tt> fails to find it.</dd>
	<dt>--with-cpp=<i>cmd</i></dt>
		<dd>(target) C preprocessor.</dd>
	<dt>--with-ranlib=<i>cmd</i></dt>
		<dd>(target) ranlib; set to 'true' or 'echo' if you don't need it.</dd>
	<dt>--with-objdump=<i>cmd</i></dt>
		<dd>(target) objdump; not used during the build, but it's crucial for some
		configure test.</dd>

	<dt>--host-cc=<i>cmd</i></dt>		
	<dt>--host-cpp=<i>cmd</i></dt>		
	<dt>--host-ranlib=<i>cmd</i></dt>
	<dt>--host-objdump=<i>cmd</i></dt>
	<dt>--host-libs=<i>cmd</i></dt>
		<dd>Same, for the build system. Only useful when cross-compiling.</dd>

	<dt>--sysroot=<i>/path</i></dt>
		<dd>target includes and libraries location in the build system, see gcc(1) or clang(1).
		This option is passed directly to the compiler and linker.</dd>
</dl>

<p>Options from the original Configure which are not supported or make
no sense for this version of configure:</p>
<dl>
	<dt>-e</dt>
		<dd>go on without questioning past the production of config.sh (ignored, you'll
		have to run make manually)</dd>
	<dt>-E</dt>	
		<dd>stop at the end of questions, after having produced config.sh (ignored,
		that's the only way it works)</dd>
	<dt>-r</dt>
		<dd>reuse C symbols value if possible, skips costly nm extraction (ignored,
		configure uses completely different method of checking function availability)</dd>
	<dt>-s</dt>
		<dd>silent mode (ignored, that's more or less how it works anyway as there's no
		interactive mode)</dd>
	<dt>-K</dt>
		<dd>(not supported)</dd>
	<dt>-S</dt>
		<dd>perform variable substitutions on all .SH files (ignored, configure can't do that)</dd>
	<dt>-V</dt>
		<dd>show version number (not supported)</dd>
	<dt>-d</dt>
		<dd>use defaults for all answers (ignored, that's how it works anyway)</dd>
	<dt>-h</dt>
		<dd>show help (ignored, use --help instead)</dd>
</dl>

<p>The following options are used to manipulate the values configure will
write to config.sh. Check Porting/Glossary for the list of possible
symbols.</p>
<dl>
	<dt>-f <i>file.sh</i></dt>
		<dd>load configuration from specified file. More precisely,
		append file.sh to the list of files to be loaded after processing
		other options. See <a href="#workflow">Workflow</a> below.</dd>
	<dt>-D <i>symbol[=value]</i></dt>
		<dd>define symbol to have some value:
		<ul class="fixtab">
			<li><tt>-D <i>symbol</i></tt> symbol gets the value 'define'</li>
			<li><tt>-D <i>symbol=value</i></tt> symbol gets the value 'value'</li>
		</ul>
	    	common examples (see INSTALL for more info):
		<ul class="fixtab">
			<li><tt>-Duse64bitint</tt>            use 64bit integers</li>
			<li><tt>-Duse64bitall</tt>            use 64bit integers and pointers</li>
			<li><tt>-Dusethreads</tt>             use thread support (also <tt>--enable-threads</tt>)</li>
			<li><tt>-Dinc_version_list=none</tt>  do not include older perl trees in @INC</li>
			<li><tt>-DEBUGGING=none</tt>          DEBUGGING options</li>
			<li><tt>-Dcc=gcc</tt>                 same as <tt>--with-cc=gcc</tt></li>
			<li><tt>-Dprefix=/opt/perl5</tt>      same as <tt>--prefix=/opt/perl5</tt></li>
		</ul></dd>
	<dt>-U symbol</dt>
		<dd>undefine symbol:
		<ul class="fixtab">
			<li><tt>-U symbol</tt>    symbol gets the value 'undef'</li>
			<li><tt>-U symbol=</tt>   symbol gets completely empty</li>
		</ul>
		e.g.:  <tt>-Uversiononly</tt></dd>
	<dt>-O</dt>	
		<dd>let -D and -U override definitions from loaded configuration file; without
		-O, configuration files specified with -f will overwrite anything that was set
		using configure options. See <a href="#workflow">Workflow</a> below.</dd>

	<dt>-A [a:]symbol=value</dt>
		<dd>manipulate symbol after the platform specific hints have been applied:
		<ul class="fixtab">
			<li><tt>-A append:symbol=value</tt>   append value to symbol</li>
			<li><tt>-A symbol=value</tt>          like append:, but with a separating space</li>
			<li><tt>-A define:symbol=value</tt>   define symbol to have value</li>
			<li><tt>-A clear:symbol</tt>          define symbol to be ''</li>
			<li><tt>-A define:symbol</tt>         define symbol to be 'define'</li>
			<li><tt>-A eval:symbol=value</tt>     define symbol to be eval of value</li>
			<li><tt>-A prepend:symbol=value</tt>  prepend value to symbol</li>
			<li><tt>-A undef:symbol</tt>          define symbol to be 'undef'</li>
			<li><tt>-A undef:symbol=</tt>         define symbol to be ''</li>
		</ul>
		e.g.:   <tt>-A prepend:libswanted='cl pthread '</tt>, <tt>-A ccflags=-DSOME_MACRO</tt><br>
		<br>
		Note that due to intricate internal reasons altering <tt>$cc</tt> won't affect
		<tt>$cctype</tt> (not that there's any reason to use <tt>-A</tt> on <tt>$cc</tt>)</dd>

	<dt>--set <i>symbol=value</i></dt>
		<dd>Set symbol to value. The most basic way to manipulate config.sh variables.
		Note that it's a bit different from -D: -D foo is equivalent to --set-foo=define
		(or --define-foo) but --set-foo is equivalent to -Dfoo=''.
		There are several shortcuts for this option dealing with specific types of
		config.sh variables:</dd>
	<dt>--enable-<i>something</i></dt>
		<dd>Same as <tt>--set use<i>something</i>=define</tt></dd>
	<dt>--has-<i>function</i></dt>
		<dd>Same as <tt>--set d_<i>function</i>=define</tt></dd>
	<dt>--define-<i>something</i></dt>
		<dd>Same as <tt>--set <i>something</i>=define</tt></dd>
	<dt>--include-<i>header</i>[=yes|no]</dt>
		<dd>Set <tt>i_<i>header</i></tt> to 'define' or 'undef';  e.g. to disable <tt>&lt;sys/time.h&gt;</tt>
		use <tt>--include-sys-time-h=no</tt>.</dd>
</dl>

<p>When configuring a cross-build, <tt>-D</tt>/<tt>--set</tt> and other similar options affect
target perl configuration (config.sh) only. Use the following options if you need to tweak xconfig.sh:</p>
<dl>
	<dt>--host-option<i>[=value]</i></dt>
		<dd>Pass --option[=value] to miniperl configure on the host system (xconfig.sh).<br>
		e.g. --host-define-foo, --host-set-foo=bar</dd>

	<dt>--target-option<i>[=value]</i></dt>
		<dd>Same for tconfig.sh; do not use, it's not implemented yet</dd>
</dl>

<p>Generally configure tries to build all modules it can find in the source tree.
Use the following options to alter modules list:</p>
<dl>
	<dt>--static-mod=<i>mod1,mod2,...</i></dt>
		<dd>Build specified modules statically</dd>
	<dt>--disable-mod=<i>mod1,mod2,...</i></dt>
		<dd>Do not build specified modules.</dd>
	<dt>--only-mod=<i>mod1,mod2,...</i></dt>
		<dd>Build listed modules only</dd>
	<dt>--all-static</dt>
		<dd>Build all XS modules as static.
		Does <i>not</i> imply <tt>--no-dynaloader</tt>.</dd>
	<dt>--no-dynaloader</dt>
		<dd>Do not build DynaLoader. Implies <tt>--all-static</tt>.
		Resulting perl won't be able to load <i>any</i> XS modules.
		Same as <tt>-Uusedl</tt>.
</dl>
<p><tt><i>modX</i></tt> should be something like <tt>cpan/Archive-Extract</tt>;
static only applies to XS modules and won't affect modules found to be non-XS.</p>

<p>config.log contains verbose description of what was tested, and how.
Check it if configure output looks suspicious.</p>


<a name="targets"></a>
<h2>make targets</h2>

<div class="warn">Warning: run "make crosspatch" <b>BEFORE</b> making other targets manually.</div>

<p>Default make target is building perl and all found modules. Other possibly
useful targets:</p>
<dl>
	<dt>crosspatch</dt>
		<dd>Apply all patches from cnf/diffs. Files are patched only once,
		and cnf/diffs/path/file.applied locks are created to track them.</dd>
	<dt>miniperl</dt>
		<dd>Build miniperl only. Note that if you have non-empty
		executable extension (e.g. ".exe"), the make target must include it
		("miniperl.exe").</dd>
	<dt>config.h</dt>
	<dt>xconfig.h</dt>
	<dt>Makefile.config</dt>
		<dd>Re-build resp. files from [x]config.sh, may be needed after
		editing [x]config.sh manually. Note that make may try updating
		Makefile.config as a dependency for something else, but it
		won't re-read it immediately.</dd>
	<dt>dynaloader</dt>
		<dd>Build DynaLoader module. This is the first "serious" target after
		miniperl, and the first that requires target compiler. If you can't
		get past dynaloder, something's really wrong. Useful as a test target
		to check whether the target compiler works.</dd>
	<dt>perl</dt>
		<dd>Build the main perl executable. Implies dynaloader and other
		static modules, but not dynamic or non-XS ones.</dd>
	<dt>nonxs_ext</dt>
	<dt>dynamic_ext</dt>
	<dt>static_ext</dt>
		<dd>Build all non-XS / dynamic XS / static XS modules listed in Makefile.config.
		Check <a href="modules.html">Modules page</a> for details. Note that static modules
		are always built before the main perl executable.</dd>
	<dt>modules</dt>
	<dt>extensions</dt>
		<dd>Build all modules at once.</dd>
	<dt>cpan/<i>Some-Module</i></dt>
	<dt>ext/<i>Some-Module</i></dt>
		<dd>Build <i>Some-Module</i>. Only works for modules listed in Makefile.config.</dd>
	<dt>modules-reset</dt>
		<dd>Remove all <tt>pm_to_blib</tt> files in (non-disabled) modules, forcing
		<tt>make -C dir/Some-Module</tt> invocation for all modules during the next
		<tt>make modules</tt> or <tt>make all</tt> run.
		See <a href="modules.html#rebuilding">Modules</a> page for more info.
	<dt>modules-makefiles</dt>
		<dd>Create/update Makefiles for all (non-disabled) modules.</dd>
	<dt>modules-clean</dt>
		<dd>Run <tt>make clean</tt> for all modules. May cause unexpected side effects,
		see <a href="modules.html#cleaning">Modules</a>.</dd>
	<dt>utilites</dt>
		<dd>Build everything in <tt>utils/</tt>.</dd>
	<dt>translators</dt>
		<dd>Same for x2p.</dd>
	<dt>install</dt>
		<dd>Equivalent to <tt>make install.perl install.man</tt>.</dd>
	<dt>install.perl</dt>	
		<dd>Install perl and all the modules.</dd>
	<dt>install.man</dt>
		<dd>Install manual pages.</dd>
	<dt>install.miniperl</dt>
		<dd>Install <tt>miniperl</tt> in the host system. Don't use unless you know
		what you're doing.</dd>
	<dt>test</dt>
		<dd>Run perl test suite from t/</dd>
	<dt>testpack</dt>
		<dd>Build testpack for on-target testing. See <a href="testing.html">Testing</a> page.</dd>
	<dt>clean</dt>
		<dd>Try to clean up the source tree.<br>
		<b>Warning:</b> this target still requires some work. Try not to rely on it too much.
		It will likely work, but some garbage may be left behind.</dd>
</dl>

<p>Also, for most generated files, <tt>make <i>file</i></tt> should be enough to rebuild
<tt><i>file</i></tt>.</p>


<a name="workflow"></a>
<h2>Workflow</h2>

<p>Understanding the way configure works may be helpful in cases when
a variable can get different values from different sources.</p>

<p>configure stores the values as regular environment variables until the last
moment, and the writes them all into config.sh. Most tests are only performed
if the value is not set by that point; otherwise, existing value is kept.
Values supplied by the user typically overwrite anything else.</p>

<p>Simple rules for setting values in <tt>config.sh</tt>:</p>
<ul>
	<li>If there's no <tt>-f</tt> among the options, just use <tt>-D<i>key=val</i></tt>.</li>
	<li>If there is <tt>-f</tt>, use <tt>-O -D<i>key=val</i></tt>.</li>
	<li>If you need to alter some existing value, use <tt>-A</tt>, it works in all cases.</li>
</ul>
<p>To change <tt>xconfig.sh</tt> instead of <tt>config.sh</tt>, prepend <tt>--host</tt> to all
options (i.e. <tt>--host-set <i>key=val</i></tt>).</p>

<p>Here's the complete workflow, in case it's not clear where the value comes from:</p>
<ul>
	<li>configure starts and clears all the variables it knows from the environment</li>
	<li>Command-line options are parsed left-to-right;
	<tt>-D</tt>, <tt>-U</tt>, <tt>--set</tt>, <tt>--define</tt> and other similar
	options take effect immediately, setting their variables without
	looking at any existing values (so for the same variable the last option
	always takes precedence). All these options actually set two variables:
	the one they were told to, and another one with <tt>u_</tt> prefix.
	Files supplied via <tt>-f</tt>, hints supplied via <tt>--hints</tt>,
	and <tt>-A</tt> arguments are stored but not processed.</li>
	<li>After processing all the options, files supplied using -f are loaded
	in the order they were specified in the command line. Because the files
	typically contain simple assignments, they override any existing values
	including those supplied by -D/--set.</li>
	<li>If -O was specified, the values stored in u_ variables are used
	to "replay" -D/--set options, overwriting whatever values the loaded files
	might have set.
	<li>If mode is not set by this point, $target value is analyzed.
	In case it's a cross-build, two copies of configure are spawned,
	and once they're done the top-level configure exits without writing anyting.
	If it's a native build, or we're already in one of those
	copies, configure proceeds to hints.</li>
	<li>User hints (supplied with --hints) are loaded in the order they were
	supplied. After that, configure uses $target value to look for standard
	hints and load them if necessary. Hint files only set variables <b>not</b>
	set by that point, at least if they use 'hint' function as they should.
	Because of that, for any particular variable, the first hint file that
	sets it takes precedence.</li>
	<li>Once all hints are processed, -A options are applied in the order they
	were specified in the command line.</li>
	<li>The tests are performed to set variable that were not set yet</li>
	<li>Variables listed in configure_genc are written to config.sh</li>
	<li>Variables that were set using -D/--set, or by any of the  hint files,
	but which were not written yet, are appended to config.sh; this step allows
	adding arbitrary variables to config.sh using -D or equivalent options.</li>
	<li>configure exits</li>
</ul>

<? include "_foot.php" ?>

======
README
======

To get the staticReflection build process running, you must
checkout the build-commons project, that contains reusable
ant build files and some php scripts. You can find the latest
build-commons version in the subversion repository: ::

  ...$ svn co svn://manuel-pichler.de/build-commons/trunk setup

Or as a git repository on github: ::

  ...$ git clone git://github.com/manuelpichler/build-commons.git setup

Now a simple call to ant will trigger a build of the staticReflection
component.

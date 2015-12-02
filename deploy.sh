#!/usr/bin/env bash
if ([ "$TRAVIS_BRANCH" == "dev" ]) &&
[ "$TRAVIS_PULL_REQUEST" == "false" ]; then
	# automatically deploy this out to the dev staging area. This is currently not moving over
	# .htaccess files -_-
	npm install;
	gem install sass;
	sass --update sass:css;
    gulp deploy --user $FTPUSER --password $FTP_PASSWORD;
else
    echo "not deploying because branch is not dev."
fi
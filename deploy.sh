#!/usr/bin/env bash
if ([ "$TRAVIS_BRANCH" == "master" ]) &&
[ "$TRAVIS_PULL_REQUEST" == "false" ]; then
	npm install;
	gem install sass;
	sass --update sass:css;
    gulp deploy --user $FTPUSER --password $FTP_PASSWORD;
else
    echo "not deploying because branch is not master."
fi
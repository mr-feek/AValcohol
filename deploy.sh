#!/usr/bin/env bash
if ([ "$TRAVIS_BRANCH" == "master" ]) &&
[ "$TRAVIS_PULL_REQUEST" == "false" ]; then
	npm install;
    gulp deploy --user $FTPUSER --password $FTP_PASSWORD;
else
    echo "not deploying because branch is not master."
fi
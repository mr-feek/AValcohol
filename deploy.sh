#!/usr/bin/env bash
if ([ "$TRAVIS_BRANCH" == "master" ]) &&
[ "$TRAVIS_PULL_REQUEST" == "false" ]; then
	sass --update sass:css;
	git remote add production git@github.com:feeekkk/AValcohol-production.git
	git add . -f
	git commit -m "build"
	git push
else
	if ([ "$TRAVIS_BRANCH" == "dev" ]) &&
	[ "$TRAVIS_PULL_REQUEST" == "false" ]; then
		sass --update sass:css;
		gulp deploy --user $FTPUSER --password $FTP_PASSWORD;
	else
		echo "not deploying because branch is not dev."
	fi
fi
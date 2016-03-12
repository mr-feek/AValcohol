#!/usr/bin/env bash
if ([ "$TRAVIS_BRANCH" == "master" ]) &&
[ "$TRAVIS_PULL_REQUEST" == "false" ]; then
	sass --update sass:css;
	rm -rf .git/
	rm -rf .env
	rm -rf ./.env.example
	rm -rf ./.env.travis
	echo "dev/" >> ./.gitignore
	git init
	git config user.name "Travis CI"
	git config user.email "feek-travis@avalcohol.com"
	git config --global push.default simple
	git add . -f
	git commit -m "built with love by travis <3. Build: ${TRAVIS_BUILD_NUMBER} | Commit Range: ${TRAVIS_COMMIT_RANGE}"
	git remote add production https://${GH_TOKEN}@github.com/feeekkk/AValcohol-production.git
	git push production master --force
else
	if ([ "$TRAVIS_BRANCH" == "dev" ]) &&
	[ "$TRAVIS_PULL_REQUEST" == "false" ]; then
		sass --update sass:css;
		gulp deploy --user $FTPUSER --password $FTP_PASSWORD;
	else
		echo "not deploying because branch is not dev."
	fi
fi
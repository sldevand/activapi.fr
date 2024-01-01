#!/bin/bash
SCRIPT_DIR="$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
cd $SCRIPT_DIR

red=`tput setaf 1`
green=`tput setaf 2`
reset=`tput sgr0`

function myEcho() {
    echo ""
    echo "${green}*** $1 ***${reset}"
}

function dotenv () {
  set -a
  [ -f .env ] && . .env
  set +a
}

dotenv

LOCAL_APP_PATH=$LOCAL_WWW_PATH/$LOCAL_APP_NAME
LOCAL_BUILD_PATH=$LOCAL_APP_PATH/build
LOCAL_REPO_PATH=$LOCAL_APP_PATH/build/$LOCAL_APP_NAME
REMOTE_SCRIPT_PATH=$LOCAL_APP_PATH/deploy/remote/commands.sh

myEcho "START $LOCAL_APP_NAME deployer script START"

myEcho "Local : Launch tests"
php $LOCAL_APP_PATH/vendor/bin/phpunit --configuration $LOCAL_APP_PATH/Tests/phpunit.xml &&

myEcho "Local :  Build App" &&
rm -rvf $LOCAL_BUILD_PATH &&
mkdir $LOCAL_BUILD_PATH &&

myEcho "Local : Git clone $GIT_BRANCH branch" &&
cd $LOCAL_BUILD_PATH &&
git clone --single-branch --branch $GIT_BRANCH $GIT_URL &&

myEcho "Local : Sassify scss files" &&
npm run sass-compile &&
myEcho "Local : Babelify all js files" &&
npm run babelAll &&

cd $LOCAL_APP_PATH &&
cp $LOCAL_APP_PATH/Web/dist/*.css $LOCAL_REPO_PATH/Web/dist &&
cp $LOCAL_APP_PATH/Web/dist/*.js $LOCAL_REPO_PATH/Web/dist &&

cd $LOCAL_BUILD_PATH &&

myEcho "Local : Remove unused files for production" &&
rm -rfv $LOCAL_REPO_PATH/deploy &&
rm -rfv $LOCAL_REPO_PATH/src &&
rm -rfv $LOCAL_REPO_PATH/Tests &&
rm -rfv $LOCAL_REPO_PATH/.gitignore &&
rm -rfv $LOCAL_REPO_PATH/*.md &&
rm -rfv $LOCAL_REPO_PATH/*.xml &&
rm -rfv $LOCAL_REPO_PATH/.git &&
rm -rfv $LOCAL_REPO_PATH/package.json &&
rm -rfv $LOCAL_REPO_PATH/package-lock.json &&
rm -rfv $LOCAL_REPO_PATH/.babelrc &&
rm -rfv $LOCAL_REPO_PATH/.gitignore &&
rm -rfv $LOCAL_REPO_PATH/Web/get_oauth_token.php &&
rm -rfv $LOCAL_REPO_PATH/.env.sample &&
rm -rfv $LOCAL_REPO_PATH/.env &&

eval "$(ssh-agent -s)" &&
ssh-add $SSH_PRIVATE_KEY &&

myEcho "Remote : copy from local $LOCAL_REPO_PATH to remote $REMOTE_HOST:$REMOTE_TMP_PATH" &&
scp -r $LOCAL_REPO_PATH $REMOTE_HOST:$REMOTE_TMP_PATH &&
ssh $REMOTE_HOST 'bash -s' < $REMOTE_SCRIPT_PATH &&

myEcho "Local : remove build files" &&
rm -rf $LOCAL_BUILD_PATH &&

myEcho "END $LOCAL_APP_NAME deployer script END"
#!/bin/bash

red=`tput setaf 1`
green=`tput setaf 2`
reset=`tput sgr0`

function myEcho(){
    echo ""
    echo "${green}--> $1 ${reset}"
}

#Local vars
LOCAL_WWW_PATH=/var/www
LOCAL_APP_NAME=activapi.fr
LOCAL_APP_PATH=$LOCAL_WWW_PATH/$LOCAL_APP_NAME
LOCAL_BUILD_PATH=$LOCAL_APP_PATH/build
LOCAL_REPO_PATH=$LOCAL_APP_PATH/build/$LOCAL_APP_NAME

#Git vars
GIT_PATH=https://github.com/sldevand/activapi.fr.git
GIT_BRANCH=master

#Remote vars
REMOTE_HOST=pi@domusbox
REMOTE_WWW_PATH=/var/www
REMOTE_TMP_PATH=/home/pi/tmp
REMOTE_APP_NAME=activapi.fr
REMOTE_SCRIPT_PATH=$LOCAL_APP_PATH/deploy/remote/commands.sh

myEcho "***START $LOCAL_APP_NAME deployer script START***"

myEcho "Local : Build App"
rm -rvf $LOCAL_BUILD_PATH
mkdir $LOCAL_BUILD_PATH

myEcho "Local : Git clone $GIT_BRANCH branch"
cd $LOCAL_BUILD_PATH
git clone --single-branch --branch $GIT_BRANCH $GIT_PATH

myEcho "Local : Remove unused files for production"
rm -rfv $LOCAL_REPO_PATH/deploy
rm -rfv $LOCAL_REPO_PATH/src
rm -rfv $LOCAL_REPO_PATH/Tests
rm -rfv $LOCAL_REPO_PATH/.gitignore
rm -rfv $LOCAL_REPO_PATH/*.md
rm -rfv $LOCAL_REPO_PATH/*.xml
rm -rfv $LOCAL_REPO_PATH/.git
rm -rfv $LOCAL_REPO_PATH/package.json
rm -rfv $LOCAL_REPO_PATH/package-lock.json
rm -rfv $LOCAL_REPO_PATH/.babelrc
rm -rfv $LOCAL_REPO_PATH/.gitignore

ssh-add ~/.ssh/domusbox_rsa

myEcho "***Remote : copy from local $LOCAL_REPO_PATH to remote $REMOTE_HOST:$REMOTE_TMP_PATH***"
scp -r $LOCAL_REPO_PATH $REMOTE_HOST:$REMOTE_TMP_PATH
ssh $REMOTE_HOST 'bash -s' < $REMOTE_SCRIPT_PATH

myEcho "***Local : remove build files***"
rm -rf $LOCAL_BUILD_PATH

myEcho "***END $LOCAL_APP_NAME deployer script END***"
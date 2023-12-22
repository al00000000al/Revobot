#!/bin/bash

# Source the deploy.env file to load the variables
source ../.env

# Change to the local folder
cd $LOCAL_FOLDER

ZIP_NAME="src.zip"
rm $ZIP_NAME


# Compress files into ZIP archive
zip -r $ZIP_NAME . -x ".git/*" "build/*" "vendor/*" "*.zip" ".github*" "tmp/*"

# Upload ZIP archive to remote server
scp -i $SSH_KEY -P $SSH_PORT $ZIP_NAME $SSH_USER@$SSH_HOST:$SSH_DIR

# Connect to remote server and extract ZIP archive
ssh -i $SSH_KEY -p $SSH_PORT $SSH_USER@$SSH_HOST "cd $SSH_DIR; unzip -o $ZIP_NAME; rm $ZIP_NAME;
cd tests;kphp --mode cli --composer-root ../ klua.php"

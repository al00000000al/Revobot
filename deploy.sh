#!/bin/bash

# Source the deploy.env file to load the variables
source .env.staging

# Change to the local folder
cd $LOCAL_FOLDER

ZIP_NAME="deploy_full.zip"
rm $ZIP_NAME


# Compress files into ZIP archive
zip -r $ZIP_NAME . -x ".git/*" "vendor/*" "tests/*"  "storage/logs*" "storage/framework*" "storage/debugbar*" "vendor/*" "*.env" "*.zip" "resources/js*" "resources/sass*" ".github*"

# Upload ZIP archive to remote server
scp -i $SSH_KEY -P $SSH_PORT $ZIP_NAME $SSH_USER@$SSH_HOST:$SSH_DIR

# Connect to remote server and extract ZIP archive
ssh -i $SSH_KEY -p $SSH_PORT $SSH_USER@$SSH_HOST "cd $SSH_DIR; unzip -o $ZIP_NAME; rm $ZIP_NAME; php artisan route:cache; sudo php artisan view:cache"

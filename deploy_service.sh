#!/bin/bash

source .env
cd $LOCAL_FOLDER
scp -i $SSH_DO_KEY -P $SSH_DO_PORT openai_api.php $SSH_DO_USER@$SSH_DO_HOST:$SSH_DO_DIR

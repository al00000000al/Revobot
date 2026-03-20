#!/bin/bash

source ../.env
cd $LOCAL_FOLDER/microservices
scp -i $SSH_DO_KEY -P $SSH_DO_PORT openai_api.php tmdb_api.php config.php t_sender.php $SSH_DO_USER@$SSH_DO_HOST:$SSH_DO_DIR

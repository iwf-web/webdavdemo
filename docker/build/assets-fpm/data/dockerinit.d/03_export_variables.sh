# export variable to reuse in any cron....
ENVFILE=/usr/local/bin/iwfsfconsole.env

# add variables to .env file as needed (use tee -a to append, when needed)
echo "DATABASE_PASSWORD=\"${DATABASE_PASSWORD}\"" | sudo tee $ENVFILE >/dev/null
echo "RUNTIME_ENVIRONMENT=\"${RUNTIME_ENVIRONMENT}\"" | sudo tee -a $ENVFILE >/dev/null

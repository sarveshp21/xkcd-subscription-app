#!/bin/bash
# This script sets up a CRON job to run cron.php every 24 hours at 9 AM.

# Get the current working directory
PROJECT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Write out current crontab to a temporary file
crontab -l > mycron 2>/dev/null

# Add a new cron job to run cron.php every day at 9 AM
echo "0 9 * * * php $PROJECT_DIR/cron.php" >> mycron

# Install the new cron file
crontab mycron
rm mycron

echo "Cron job set to run cron.php daily at 9 AM."

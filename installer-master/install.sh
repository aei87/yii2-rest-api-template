#!/usr/bin/env bash

# Defines colors
default_color='\e[39m'
red_color='\e[31m'
green_color='\e[32m'
yellow_color='\e[33m'
bold_font='\033[1m'
underline_font='\033[4m'
VERSION='dev-master';
INSTALL_DIR='/var/www/html';
ANTARES_DIR='/var/www/html'

if [ ! -z $1 ] 
then 
    LOCATION=$1
else
    LOCATION=/var/www/html
fi
ANTARES_DIR=$LOCATION;
INSTALL_DIR=$LOCATION;
# Assign the current dir to variable
current_dir=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

LOGFILE="$current_dir/install-log.log"

# Function Definitions

random-string()
{
    cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w ${1:-32} | head -n 1
}

download_package()
{
    echo -e "$yellow_color";
    echo "Please wait, creating project...";
    echo -e "$default_color";    
    sudo rm -rf "$INSTALL_DIR"
    sudo composer create-project antaresproject/project "$INSTALL_DIR" "$VERSION" --keep-vcs
}


configure_database()
{
    # Define the dialog exit status codes
    : ${DIALOG_OK=0}
    : ${DIALOG_CANCEL=1}
    : ${DIALOG_ESC=255}

    DB_HOST=$1;
    DB_USERNAME=$2;
    DB_PASSWORD=$3;
    DB_NAME=$4;
    ERROR=$5;

    dialog --backtitle "Antares Project Configuration" --title "Database Configuration" \
    --form "- Use [tab] to switch between buttons\n
- Use [up] [down] to select input field\n
- If you used the configuration script, the full access database username is root\n
- That if the database with provided name won't exists yet, it will automatically be created\n
- It is highly recommended to use the separated database for the Antares Project \n \n
$ERROR" 20 80 0 \
    "Hostname:" 1 1 "$DB_HOST" 1 15 50 0 \
    "Username:" 2 1 "$DB_USERNAME"    2 15 50 0 \
    "Password:" 3 1 "$DB_PASSWORD"    3 15 50 0 \
    "Database:" 4 1 "$DB_NAME"        4 15 50 0 \
    2>/tmp/form.$$

    # Get the exit status
    return_value=$?

    IFS=$'\n' read -d '' -r -a lines < /tmp/form.$$

    DB_HOST="${lines[0]}";
    DB_USERNAME="${lines[1]}";
    DB_PASSWORD="${lines[2]}";
    DB_NAME="${lines[3]}";

    # Act on it
    case $return_value in $DIALOG_OK)
        # Check database connection
        while ! mysql --host=$DB_HOST --user=$DB_USERNAME --password=$DB_PASSWORD -e ";" ; do
               configure_database "$DB_HOST" "$DB_USERNAME" "$DB_PASSWORD" "$DB_NAME" 'Error occured! Could not connect to the database...'
        done

        # Generate random database name
        if [ -z "$DB_NAME" ]
            then
                SUFFIX=$(random-string 8);
                DB_NAME='antares_'$SUFFIX;
        fi

        # Check database name
        RESULT=$(mysqlshow --host=$DB_HOST --user=$DB_USERNAME --password=$DB_PASSWORD $DB_NAME | grep -v Wildcard | grep -ow $DB_NAME);

        if [ "$RESULT" == "$DB_NAME" ]; then
            configure_database "$DB_HOST" "$DB_USERNAME" "$DB_PASSWORD" "$DB_NAME" 'Error occured! Database '$DB_NAME' already exists. Please choose another database or leave empty.'
        fi
        ;;
      $DIALOG_CANCEL)
            echo -e "$red_color";
            echo "ERROR OCCURED!"
            echo "-----------------------------------"
            echo "Database has not been configured...";
            echo -e "$default_color";
            exit
            ;;
      $DIALOG_ESC)
            echo -e "$red_color";
            echo "ERROR OCCURED!"
            echo "-----------------------------------"
            echo "Database has not been configured...";
            echo -e "$default_color";
            exit
            ;;
    esac
}

create_database()
{
    # Everything is good
    echo -e "$yellow_color";
    echo "Configure Antares database...";
    echo -e "$default_color";
    mysql --host=$DB_HOST --user=$DB_USERNAME --password=$DB_PASSWORD -e 'CREATE DATABASE '$DB_NAME'';        
    echo -e "$green_color";
    echo "Database has been successfully configured...";
    echo -e "$default_color";
}

verify_dependencies()
{
    # Permissions
    chmod -R 777 "$INSTALL_DIR"/public
    chmod -R 777 "$INSTALL_DIR"/storage
    chmod -R 777 "$INSTALL_DIR"/bootstrap
    chown -R www-data:www-data "$INSTALL_DIR"
    sudo sed -i 's/DB_HOST=127.0.0.1/DB_HOST='$DB_HOST'/g' "$INSTALL_DIR"/.env
    sudo sed -i 's/DB_DATABASE=foo/DB_DATABASE='$DB_NAME'/g' "$INSTALL_DIR"/.env
    sudo sed -i 's/DB_USERNAME=root/DB_USERNAME='$DB_USERNAME'/g' "$INSTALL_DIR"/.env
    sudo sed -i 's/DB_PASSWORD=/DB_PASSWORD='$DB_PASSWORD'/g' "$INSTALL_DIR"/.env
}



echo -e "$green_color";
echo "#################################################################";
echo "#          Download Antares Package From Github (10/11)         #";
echo "#################################################################";
echo -e "$default_color";

download_package


echo -e "$green_color";
echo "#################################################################";
echo "#             Composer Install And Database (11/11)             #";
echo "#################################################################";
echo -e "$default_color";

configure_database "127.0.0.1" "" "" "antares"

create_database

verify_dependencies

echo -e "$green_color";
echo "#################################################################";
echo -e "Antares Project has been successfully installed!" >&2;
echo -e "Please now open your browser and point to: " >&2;
echo -e "Installation details can be found at $LOGFILE" >&2;
echo -e "http://YOUR_IP_ADDRESS/install";
echo "#################################################################";
echo -e "$default_color";

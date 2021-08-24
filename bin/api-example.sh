#!/bin/bash
# -----
# Run this script to test some API features
# -----

# -----
# NOTE: this script has some configuration constants
# -----
# Response in json+ld or simple json
HEADER_ACCEPT="Accept: application/ld+json"
#HEADER_ACCEPT="Accept: application/json"

# -----
# NOTE: this script requires the jq JSON parser utility
# -> sudo apt install jq
# -----
# If not installed, this script will stop execution
# -----
PRETTY_PRINTING=""
if command -v jq > /dev/null 2>&1; then
  PRETTY_PRINTING="jq ."
else
  echo "jq is not available. You will miss pretty-printing -)"
  echo "-----"
  echo "You should: sudo apt install jq"
  echo "-----"
  exit
fi

# If an .env file exists, source its defined variables
if [ ! -f .env ]
then
  if [ -f .env.dist ]
  then
    cp .env.dist .env
  fi
fi
if [ -f .env ]
then
  export $(cat .env | sed 's/#.*//g' | xargs)
fi

# Extra cUrl arguments
ARGUMENTS=
# Liste and get users
LIST_USERS="0"
# Default is normal
VERBOSE_MODE="0"
VERBOSE_CURL="0"

# ---
# backend URI and endpoints
BACKEND=${BACKEND:-"http://localhost:8000"}
endpoint_api="/api"
endpoint_login="${endpoint_api}/login_check"
endpoint_logout="${endpoint_api}/logout"
endpoint_version="${endpoint_api}/version"
endpoint_profile="${endpoint_api}/me"
endpoint_users="${endpoint_api}/users"
# backend username / password for user API
USERNAME=${USERNAME:-""}
PASSWORD=${PASSWORD:-""}
# Output directory
OUTPUT=${OUTPUT:-""}

usage() {
    cat << END

Usage: $0 [-H|-h|--help] [-b|--backend uri] [-u|--username username] [-p|--password password]

 -h (-H) (--help)       display this message
 -v (--verbose)         verbose mode
 -v2                    verbose mode + verbose curl
 -b (--backend)         backend API URI (default is ${BACKEND})
 -u (--username)        User username (default is ${USERNAME})
 -p (--password)        User password (default is ${PASSWORD})
 -o (--output)          Write responses provide to output files in this directory
 -l (--list)            List and get users

END
}

for i in "$@"
do
case $i in
    -h|-H|--help)
    usage >&1
    exit 0
    ;;
    -l|--list)
    LIST_USERS="1"
    shift
    ;;
    -v|--verbose)
    VERBOSE_MODE="1"
    shift
    ;;
    -v2)
    VERBOSE_MODE="1"
    VERBOSE_CURL="1"
    shift
    ;;
    -b|--backend)
    shift
    BACKEND="$1"
    shift
    ;;
    -o|--output)
    shift
    OUTPUT="$1"
    shift
    ;;
    -u|--username)
    shift
    USERNAME="$1"
    shift
    ;;
    -p|--password)
    shift
    PASSWORD="$1"
    shift
    ;;
esac
done

ARGUMENTS="--silent"
if [ "$VERBOSE_MODE" = "1" ]; then
  ARGUMENTS=""
  if [ "$VERBOSE_CURL" = "1" ]; then
      ARGUMENTS="--verbose -w @api-example-curl-format.txt"
  fi
fi

if [ -n "$OUTPUT" ]; then
  # Variable set and not empty
  mkdir -p "$OUTPUT"
fi

# -----
# Get the API version (unauthenticated)
# -----
# NOTE: no authentication required!
# -----
if [ "$VERBOSE_MODE" = "1" ]; then
  echo "----------"
  echo "Connecting to ${BACKEND}..."
  echo "----------"
fi

json=$(curl $ARGUMENTS "${BACKEND}${endpoint_version}" \
            --header "${HEADER_ACCEPT}" \
            --header "Content-Type: application/json")
result=$?
if test "$result" != "0"; then
   echo "Connecting the configured backend failed with: $result"
   echo "Check the backend configuration: ${BACKEND}"
   exit $result
fi
if test "$json" == "File not found."; then
   echo "Connecting the configured backend failed with: $json"
   echo "Check the backend configuration: ${BACKEND}"
   exit $result
fi
api_title=$(echo $json | jq -r .api_title)
api_version=$(echo $json | jq -r .api_version)
if [ -n "$OUTPUT" ]; then
  echo "$json" | $PRETTY_PRINTING > "${OUTPUT}/api.json"
fi
if [ "$VERBOSE_MODE" = "1" ]; then
  echo "API '${api_title}': version ${api_version}, information: "
  echo "$json" | $PRETTY_PRINTING
fi


# -----
# Login and create an environment variable JWT with the user WS token
# -----
if [ "$VERBOSE_MODE" = "1" ]; then
  echo "----------"
  if [ -n "$endpoint_login" ]; then
    echo "Signing in with credentials: $USERNAME / $PASSWORD"
  fi
  echo "----------"
fi

get_login_data() {
      parameters=$(cat <<EOF
{
  "email": "$1",
  "password": "$2"
}
EOF
    )
  if [ "$VERBOSE_MODE" = "1" ]; then
    echo "Login data: $parameters" > /dev/stderr
  fi
  echo $parameters
}

# User authentication
data=$(get_login_data "${USERNAME}" "${PASSWORD}")
json=$(curl ${ARGUMENTS} "${BACKEND}${endpoint_login}" \
            -X POST \
            --header "${HEADER_ACCEPT}" \
            --header "Content-Type: application/json" \
            --data "$data")
result=$?
if test "$result" != "0"; then
   echo "Connecting the configured backend failed with: $result"
   echo "Check the backend configuration: ${BACKEND}"
   exit $result
fi

api_code=$(echo $json | jq -r .code)
api_message=$(echo $json | jq -r .message)
if test "$api_code" != "null"; then
   echo "Authenticating to the configured backend failed with: $api_code"
   echo "Check the configured credentials, username: ${USERNAME}, password: ${PASSWORD}"
   exit $result
fi

if [ "$VERBOSE_MODE" = "1" ]; then
  echo "Got: "
  echo "$json" | $PRETTY_PRINTING
fi

# Response is: {"token":"eyJhbGciOiJSUzI1NiJ9... ... ..."}
access_token=$(echo $json | jq -r .token)
if [ -n "$OUTPUT" ]; then
  echo "$json" | $PRETTY_PRINTING > "${OUTPUT}/tokens.json"
fi
export _JWT=${access_token}
echo $_JWT

parsed_token=$(echo ${access_token} | cut -d"." -f1,2 | sed 's/\./\n/g' | base64 --decode)
if [ -n "$OUTPUT" ]; then
  echo $parsed_token | $PRETTY_PRINTING > "${OUTPUT}/parsed-token.json"
fi
if [ "$VERBOSE_MODE" = "1" ]; then
  echo "Parsed JWT token: "
  echo $parsed_token | $PRETTY_PRINTING
fi

# -----
# Get the signed-in user profile
# -----
# Get the signed-in patient information (/api/me) - accept redirection!
json=$(curl $ARGUMENTS "${BACKEND}${endpoint_profile}" \
            -L \
            --header "${HEADER_ACCEPT}" \
            --header "Content-Type: application/json" \
            --header "Authorization: Bearer ${access_token}")
if [ -n "$OUTPUT" ]; then
  echo "$json" | $PRETTY_PRINTING > "${OUTPUT}/me.json"
fi
if [ "$VERBOSE_MODE" = "1" ]; then
  echo "Me: "
  echo "$json" | $PRETTY_PRINTING
fi

# -----
# Get the users list
# -----
json=$(curl $ARGUMENTS "${BACKEND}${endpoint_users}" \
            --header "${HEADER_ACCEPT}" \
            --header "Content-Type: application/json" \
            --header "Authorization: Bearer ${access_token}")
if [ -n "$OUTPUT" ]; then
  echo "$json" | $PRETTY_PRINTING > "${OUTPUT}/users.json"
fi
if [ "$VERBOSE_MODE" = "1" ]; then
  echo "The users: "
  echo "$json" | $PRETTY_PRINTING
fi
